<?php

namespace App\Http\Controllers;

use App\Link;
use App\Stat;
use App\Traits\UserFeaturesTrait;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StatsController extends Controller
{
    use UserFeaturesTrait;

    public function index($id)
    {
        $link = Link::where('id', $id)->firstOrFail();

        $this->statsGuard($link);

        $user = User::findOrFail($link->user_id);
        $remoteUserFeatures = $this->getFeatures($user);

        $clicks = null;
        if ($user->can('stats', ['App\Link', $remoteUserFeatures['option_stats']])) {
            $clicks = Stat::where('link_id', $link->id)
                ->orderBy('id', 'desc')
                ->paginate(config('settings.paginate'));
        }

        $now = Carbon::now();

        $stats = [
            'Last 24 hours' => [
                'current' => Stat::where('link_id', $link->id)->whereBetween('created_at', [(clone $now)->subDays(1), $now])->count(),
                'previous' => Stat::where('link_id', $link->id)->whereBetween('created_at', [(clone $now)->subDays(2), (clone $now)->subDays(1)])->count()],
            'Last 30 days' => [
                'current' => Stat::where('link_id', $link->id)->whereBetween('created_at', [(clone $now)->subDays(30), $now])->count(),
                'previous' => Stat::where('link_id', $link->id)->whereBetween('created_at', [(clone $now)->subDays(60), (clone $now)->subDays(30)])->count()],
            'All time' => [
                'current' => $link->clicks
            ]
        ];

        return view('stats.content', ['view' => 'general', 'link' => $link, 'clicks' => $clicks, 'stats' => $stats, 'user' => $user, 'remoteUserFeatures' => $remoteUserFeatures]);
    }

    public function hours($id)
    {
        $link = Link::where('id', $id)->firstOrFail();

        $this->statsGuard($link);

        $user = User::findOrFail($link->user_id);
        $remoteUserFeatures = $this->getFeatures($user);

        $now = Carbon::now();

        $values = Stat::select([
                DB::raw("date_format(`created_at`, '%Y-%m-%d %H:00:00') as `date_result`"),
                DB::raw("count(*) as `aggregate`")
            ])
            ->where('link_id', '=', $link->id)
            ->whereBetween('created_at', [(clone $now)->subDay(), $now])
            ->groupBy('date_result')
            ->orderBy('date_result', 'desc')
            ->get();

        // Remap the result set, and format the array
        $collection = $values->mapWithKeys(function ($result) {
            return [Carbon::parse($result->date_result)->format('Y-m-d H:00:00') => $result->aggregate];
        })->all();

        // Merge the results with the pre-calculated possible time ranges
        $result = array_merge($this->calcAllDates($now, (clone $now)->subDay(), 'hour', 'Y-m-d H:00:00'), $collection);

        // Reorder and reformat the result set
        $values = [];
        foreach (array_reverse($result) as $x => $y) {
            $values[] = ['date_result' => $x, 'aggregate' => $y];
        }

        // Get the maximum value of an item from the result set
        $max = 0;
        foreach ($values as $value) {
            if ($value['aggregate'] > $max) {
                $max = $value['aggregate'];
            }
        }

        return view('stats.content', ['view' => 'hours', 'link' => $link, 'values' => $values, 'max' => $max, 'user' => $user, 'remoteUserFeatures' => $remoteUserFeatures]);
    }

    public function days($id)
    {
        $link = Link::where('id', $id)->firstOrFail();

        $this->statsGuard($link);

        $user = User::findOrFail($link->user_id);
        $remoteUserFeatures = $this->getFeatures($user);

        $now = Carbon::now();

        $values = Stat::select([
                DB::raw("date_format(`created_at`, '%Y-%m-%d') as `date_result`"),
                DB::raw("count(*) as `aggregate`")
            ])
            ->where('link_id', '=', $link->id)
            ->whereBetween('created_at', [(clone $now)->subDays(30), $now])
            ->groupBy('date_result')
            ->orderBy('date_result', 'desc')
            ->get();

        // Remap the result set, and format the array
        $collection = $values->mapWithKeys(function ($result) {
            return [Carbon::parse($result->date_result)->format('Y-m-d') => $result->aggregate];
        })->all();

        // Merge the results with the pre-calculated possible time ranges
        $result = array_merge($this->calcAllDates($now, (clone $now)->subDays(30), 'day', 'Y-m-d'), $collection);

        // Reorder and reformat the result set
        $values = [];
        foreach (array_reverse($result) as $x => $y) {
            $values[] = ['date_result' => $x, 'aggregate' => $y];
        }

        // Get the maximum value of an item from the result set
        $max = 0;
        foreach ($values as $value) {
            if ($value['aggregate'] > $max) {
                $max = $value['aggregate'];
            }
        }

        return view('stats.content', ['view' => 'days', 'link' => $link, 'values' => $values, 'max' => $max, 'user' => $user, 'remoteUserFeatures' => $remoteUserFeatures]);
    }

    public function months($id)
    {
        $link = Link::where('id', $id)->firstOrFail();

        $this->statsGuard($link);

        $user = User::findOrFail($link->user_id);
        $remoteUserFeatures = $this->getFeatures($user);

        $now = Carbon::now();

        $values = Stat::select([
                DB::raw("date_format(`created_at`, '%Y-%m') as `date_result`"),
                DB::raw("count(*) as `aggregate`")
            ])
            ->where('link_id', '=', $link->id)
            ->whereBetween('created_at', [(clone $now)->subMonths(12), $now])
            ->groupBy('date_result')
            ->orderBy('date_result', 'desc')
            ->get();

        // Remap the result set, and format the array
        $collection = $values->mapWithKeys(function ($result) {
            return [Carbon::parse($result->date_result)->format('Y-m') => $result->aggregate];
        })->all();

        // Merge the results with the pre-calculated possible time ranges
        $result = array_merge($this->calcAllDates($now, (clone $now)->subMonths(12), 'month', 'Y-m'), $collection);

        // Reorder and reformat the result set
        $values = [];
        foreach (array_reverse($result) as $x => $y) {
            $values[] = ['date_result' => $x, 'aggregate' => $y];
        }

        // Get the maximum value of an item from the result set
        $max = 0;
        foreach ($values as $value) {
            if ($value['aggregate'] > $max) {
                $max = $value['aggregate'];
            }
        }

        return view('stats.content', ['view' => 'months', 'link' => $link, 'values' => $values, 'max' => $max, 'user' => $user, 'remoteUserFeatures' => $remoteUserFeatures]);
    }

    public function geographic(Request $request, $id)
    {
        $link = Link::where('id', $id)->firstOrFail();

        $this->statsGuard($link);

        $user = User::findOrFail($link->user_id);
        $remoteUserFeatures = $this->getFeatures($user);

        $countriesChart = $countries = null;
        if ($user->can('stats', ['App\Link', $remoteUserFeatures['option_stats']])) {
            $countriesChart = Stat::select('country')
                ->selectRaw('COUNT(*) as `count`')
                ->where('link_id', $link->id)
                ->groupBy('country')
                ->orderByDesc('count')
                ->get();

            $countries = Stat::select('country')
                ->selectRaw('COUNT(*) as `count`')
                ->where('link_id', $link->id)
                ->groupBy('country')
                ->orderByDesc('count')
                ->paginate(config('settings.paginate'));
        }

        return view('stats.content', ['view' => 'geographic', 'link' => $link, 'countries' => $countries, 'countriesChart' => $countriesChart, 'user' => $user, 'remoteUserFeatures' => $remoteUserFeatures]);
    }

    public function browsers(Request $request, $id)
    {
        $link = Link::where('id', $id)->firstOrFail();

        $this->statsGuard($link);

        $user = User::findOrFail($link->user_id);
        $remoteUserFeatures = $this->getFeatures($user);

        $browsers = null;
        if ($user->can('stats', ['App\Link', $remoteUserFeatures['option_stats']])) {
            $browsers = Stat::select('browser')
                ->selectRaw('COUNT(*) as `count`')
                ->where('link_id', $link->id)
                ->groupBy('browser')
                ->orderByDesc('count')
                ->paginate(config('settings.paginate'));
        }

        return view('stats.content', ['view' => 'browsers', 'link' => $link, 'browsers' => $browsers, 'user' => $user, 'remoteUserFeatures' => $remoteUserFeatures]);
    }

    public function platforms(Request $request, $id)
    {
        $link = Link::where('id', $id)->firstOrFail();

        $this->statsGuard($link);

        $user = User::findOrFail($link->user_id);
        $remoteUserFeatures = $this->getFeatures($user);

        $platforms = null;
        if ($user->can('stats', ['App\Link', $remoteUserFeatures['option_stats']])) {
            $platforms = Stat::select('platform')
                ->selectRaw('COUNT(*) as `count`')
                ->where('link_id', $link->id)
                ->groupBy('platform')
                ->orderByDesc('count')
                ->paginate(config('settings.paginate'));
        }

        return view('stats.content', ['view' => 'platforms', 'link' => $link, 'platforms' => $platforms, 'user' => $user, 'remoteUserFeatures' => $remoteUserFeatures]);
    }

    public function devices(Request $request, $id)
    {
        $link = Link::where('id', $id)->firstOrFail();

        $this->statsGuard($link);

        $user = User::findOrFail($link->user_id);
        $remoteUserFeatures = $this->getFeatures($user);

        $devices = null;
        if ($user->can('stats', ['App\Link', $remoteUserFeatures['option_stats']])) {
            $devices = Stat::select('device')
                ->selectRaw('COUNT(*) as `count`')
                ->where('link_id', $link->id)
                ->groupBy('device')
                ->orderByDesc('count')
                ->paginate(config('settings.paginate'));
        }

        return view('stats.content', ['view' => 'devices', 'link' => $link, 'devices' => $devices, 'user' => $user, 'remoteUserFeatures' => $remoteUserFeatures]);
    }

    public function sources(Request $request, $id)
    {
        $link = Link::where('id', $id)->firstOrFail();

        $this->statsGuard($link);

        $user = User::findOrFail($link->user_id);
        $remoteUserFeatures = $this->getFeatures($user);

        $referrers = null;
        if ($user->can('stats', ['App\Link', $remoteUserFeatures['option_stats']])) {
            $referrers = Stat::select('referrer')
                ->selectRaw('COUNT(*) as `count`')
                ->where('link_id', $link->id)
                ->groupBy('referrer')
                ->orderByDesc('count')
                ->paginate(config('settings.paginate'));
        }

        return view('stats.content', ['view' => 'sources', 'link' => $link, 'referrers' => $referrers, 'user' => $user, 'remoteUserFeatures' => $remoteUserFeatures]);
    }

    public function social(Request $request, $id)
    {
        $link = Link::where('id', $id)->firstOrFail();

        $this->statsGuard($link);

        $domains = [
            'l.facebook.com' => 'Facebook',
            't.co' => 'Twitter',
            'l.instagram.com' => 'Instagram',
            'out.reddit.com' => 'Reddit',
            'www.youtube.com' => 'YouTube',
            'away.vk.com' => 'VK',
            't.umblr.com' => 'Tumblr'
        ];

        $user = User::findOrFail($link->user_id);
        $remoteUserFeatures = $this->getFeatures($user);

        $socials = $totalCount = null;
        if ($user->can('stats', ['App\Link', $remoteUserFeatures['option_stats']])) {
            $socials = Stat::select('referrer')
                ->selectRaw('COUNT(*) as `count`')
                ->where('link_id', $link->id)
                ->whereIn('referrer', array_keys($domains))
                ->groupBy('referrer')
                ->orderByDesc('count')
                ->paginate(config('settings.paginate'));

            $totalCount = null;
            foreach ($socials as $social) {
                $totalCount += $social->count;
            }
        }

        return view('stats.content', ['view' => 'social', 'link' => $link, 'totalCount' => $totalCount, 'domains' => $domains, 'socials' => $socials, 'user' => $user, 'remoteUserFeatures' => $remoteUserFeatures]);
    }

    public function languages(Request $request, $id)
    {
        $link = Link::where('id', $id)->firstOrFail();

        $this->statsGuard($link);

        $user = User::findOrFail($link->user_id);
        $remoteUserFeatures = $this->getFeatures($user);

        $languages = null;
        if ($user->can('stats', ['App\Link', $remoteUserFeatures['option_stats']])) {
            $languages = Stat::select('language')
                ->selectRaw('COUNT(*) as `count`')
                ->where('link_id', $link->id)
                ->groupBy('language')
                ->orderByDesc('count')
                ->paginate(config('settings.paginate'));
        }

        return view('stats.content', ['view' => 'languages', 'link' => $link, 'languages' => $languages, 'user' => $user, 'remoteUserFeatures' => $remoteUserFeatures]);
    }

    /**
     * @param $link
     */
    private function statsGuard($link)
    {
        // If the link stats is not set to public
        if(!$link->public) {
            $user = Auth::user();

            // If the user is not authenticated
            // Or if the user is not the owner of the link and not an admin
            if ($user == null || $user->id != $link->user_id && $user->role != 1) {
                abort(403);
            }
        }
    }

    /**
     * Calculate all the possible dates between two time frames
     *
     * @param $endDate
     * @param $startDate
     * @param $unit
     * @param $format
     * @return mixed
     */
    private function calcAllDates($endDate, $startDate, $unit, $format)
    {
        $possibleDateResults[$startDate->copy()->format($format)] = 0;

        while ($startDate->lt($endDate)) {
            if ($unit == 'month') {
                $startDate = $startDate->addMonths(1);
            } elseif ($unit == 'day') {
                $startDate = $startDate->addDays(1);
            } elseif ($unit == 'hour') {
                $startDate = $startDate->addHour(1);
            }

            if ($startDate->lte($endDate)) {
                $possibleDateResults[$startDate->copy()->format($format)] = 0;
            }
        }

        return $possibleDateResults;
    }
}
