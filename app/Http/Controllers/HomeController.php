<?php

namespace App\Http\Controllers;

use App\Domain;
use App\Http\Requests\CreateLinkRequest;
use App\Link;
use App\Plan;
use App\Stat;
use App\Traits\LinkTrait;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    use LinkTrait;

    /**
     * Show the application dashboard.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        // If the user is logged-in, redirect to dashboard
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        // Get the local host
        $local = parse_url(config('app.url'));

        // Get the request host
        $remote = $request->getHost();

        $link = null;

        if ($local['host'] != $remote) {
            // Get the remote domain
            $domain = Domain::where('name', '=', config('settings.short_protocol') . '://' . $remote)->first();

            // If the domain exists
            if ($domain) {
                // If the domain has an index page defined
                if ($domain->index_page) {
                    return redirect()->to($domain->index_page, 301);
                }
            }
        }

        // If there's a custom site index
        if (config('settings.index')) {
            return redirect()->to(config('settings.index'), 301);
        }

        if (config('settings.stripe')) {
            $plans = Plan::where('visibility', 1)->get();
        } else {
            $plans = null;
        }

        $stats = [
            'links' => Link::max('id'),
            'redirects' => Stat::max('id'),
            'users' => User::max('id')
        ];

        return view('home.index', ['plans' => $plans, 'stats' => $stats]);
    }

    public function createLink(CreateLinkRequest $request)
    {
        if (!config('settings.short_guest')) {
            abort(404);
        }

        $this->linkCreate($request);

        return redirect()->back()->with('link', Link::where('user_id', '=', 0)->orderBy('id', 'desc')->limit(1)->get());
    }
}
