<?php

namespace App\Http\Controllers;

use App\Domain;
use App\Http\Requests\ValidateLinkPasswordRequest;
use App\Link;
use App\Rules\ValidateLinkPasswordRule;
use App\Stat;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use GeoIp2\Database\Reader as GeoIP;
use WhichBrowser\Parser as UserAgent;

class RedirectController extends Controller
{
    public function index(Request $request, $id)
    {
        // Get the local host
        $local = parse_url(config('app.url'))['host'];

        // Get the request host
        $remote = request()->getHost();

        $link = null;

        if ($local != $remote) {
            // Get the remote domain
            $domain = Domain::where('name', '=', config('settings.short_protocol') . '://' . $remote)->first();

            // If the domain exists
            if ($domain) {
                // Get the link
                $link = Link::where([['alias', '=', $id], ['domain_id', '=', $domain->id]])->first();
            }
        } else {
            $link = Link::where([['alias', '=', $id], ['domain_id', '=', null]])->first();
        }

        // If the link exists
        if ($link) {
            $referrer = parse_url($request->server('HTTP_REFERER'), PHP_URL_HOST) ?? null;

            // If the link is password protected, but no validation has been done
            if ($link->password && session('verified_link') != $id) {
                // Cache the referrer
                session(['referrer' => $referrer]);
            } elseif($link->password && session('verified_link') == $id) {
                // Retrieve the cached referrer
                $referrer = session('referrer');

                // Clear the cached referrer
                session()->forget('referrer');
            }

            if (array_key_exists(1, $request->segments())) {
                if ($link->password && session('verified_link') != $id) {
                    return view('redirect.password', ['link' => $link]);
                }

                return view('redirect.preview', ['link' => $link]);
            }

            // If the URL is from a Guest User
            if ($link->user_id == 0) {
                // Increase the total click count
                Link::where('id', $link->id)->increment('clicks', 1);

                return redirect()->to($this->urlParamsForward($link->url), 301)->header('Cache-Control', 'no-store, no-cache, must-revalidate');
            }

            // If the link has expired
            if(Carbon::now()->greaterThan($link->ends_at) && $link->ends_at) {
                // If the link has an expiration url
                if ($link->expiration_url) {
                    return redirect()->to($link->expiration_url, 301)->header('Cache-Control', 'no-store, no-cache, must-revalidate');
                }

                return view('redirect.expired', ['link' => $link]);
            }

            // If the link expiration clicks exceeded
            if ($link->expiration_clicks && $link->clicks >= $link->expiration_clicks) {
                // If the link has an expiration url
                if ($link->expiration_url) {
                    return redirect()->to($link->expiration_url, 301)->header('Cache-Control', 'no-store, no-cache, must-revalidate');
                }

                return view('redirect.expired', ['link' => $link]);
            }

            // If the link is password protected
            if ($link->password && session('verified_link') != $id) {
                return view('redirect.password', ['link' => $link]);
            }

            // If the link is disabled
            if ($link->disabled) {
                return view('redirect.disabled', ['link' => $link]);
            }

            // If the link contains banned words
            $bannedWords = preg_split('/\n|\r/', config('settings.short_bad_words'), -1, PREG_SPLIT_NO_EMPTY);

            foreach($bannedWords as $word) {
                // Search for the word in string
                if(strpos($link->url, $word) !== false) {
                    return view('redirect.banned', ['link' => $link]);
                }
            }

            $ua = new UserAgent(getallheaders());

            // If the UA is a BOT
            if ($ua->device->type == 'bot') {
                return redirect()->to($this->urlParamsForward($link->url), 301)->header('Cache-Control', 'no-store, no-cache, must-revalidate');
            }

            $geoip = new GeoIP(storage_path('app/geoip/GeoLite2-Country.mmdb'));

            try {
                $country = $geoip->country($request->ip())->country->isoCode;
            } catch(\Exception $e) {
                $country = null;
            }

            $stat = new Stat;
            $stat->link_id = $link->id;
            $stat->user_id = $link->user_id;
            $stat->referrer = $referrer;
            $stat->platform = $ua->os->name ?? null;
            $stat->browser = $ua->browser->name ?? null;
            $stat->device = $ua->device->type ?? null;
            $stat->country = $country ?? null;
            $stat->language = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2) : null;
            $stat->save();

            // Increase the total click count
            Link::where('id', $link->id)->increment('clicks', 1);

            // If rotation targeting is enabled
            if ($link->target_type == 3 && $link->rotation_target !== null) {
                $totalRotations = count($link->rotation_target);

                $last_rotation = 0;
                // If there are links in the rotation
                // And the total available links is higher than the last rotation id
                if ($totalRotations > 0 && $totalRotations > $link->last_rotation) {
                    // Increase the last id
                    $last_rotation = $link->last_rotation + 1;
                }

                // Update the last rotation id
                Link::where('id', $link->id)->update(['last_rotation' => $last_rotation]);
            }

            // If the target type is Geographic
            if ($link->target_type == 1 && $link->geo_target !== null) {
                // Redirect the user based on his location
                if ($link->geo_target) {
                    foreach ($link->geo_target as $geo) {
                        if ($stat->country == $geo->key) {
                            return redirect()->to($this->urlParamsForward($geo->value), 301)->header('Cache-Control', 'no-store, no-cache, must-revalidate');
                        }
                    }
                }
            }

            // If the target type is Platform
            if ($link->target_type == 2 && $link->platform_target !== null) {
                // Redirect the user based on the platform he is on
                if ($link->platform_target) {
                    foreach ($link->platform_target as $platform) {
                        if ($stat->platform == $platform->key) {
                            return redirect()->to($this->urlParamsForward($platform->value), 301)->header('Cache-Control', 'no-store, no-cache, must-revalidate');
                        }
                    }
                }
            }

            // If the target type is Link Rotation
            if ($link->target_type == 3 && $link->rotation_target !== null) {
                if (isset($link->rotation_target[$link->last_rotation])) {
                    return redirect()->to($this->urlParamsForward($link->rotation_target[$link->last_rotation]->value), 301)->header('Cache-Control', 'no-store, no-cache, must-revalidate');
                }
            }

            return redirect()->to($this->urlParamsForward($link->url), 301)->header('Cache-Control', 'no-store, no-cache, must-revalidate');
        }

        // If the request comes from a remote source
        if ($local != $remote) {
            // Get the remote domain
            $domain = Domain::where('name', '=', config('settings.short_protocol') . '://' . $remote)->first();

            // If the domain exists
            if ($domain) {
                // If the domain has a 404 page defined
                if ($domain->not_found_page) {
                    return redirect()->to($domain->not_found_page, 301)->header('Cache-Control', 'no-store, no-cache, must-revalidate');
                }
            }
        }

        abort(404);
    }

    /**
     * Validate the link's password
     *
     * @param ValidateLinkPasswordRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function validatePassword(ValidateLinkPasswordRequest $request, $id)
    {
        return redirect()->back()->with('verified_link', $id);
    }

    /**
     * Format an URL to append additional parameters
     *
     * @param $url
     * @return string
     */
    private function urlParamsForward($url)
    {
        $forwardParams = request()->all();

        // If additional parameters are present
        if ($forwardParams) {
            $urlParts = parse_url($url);

            // Explode the original parameters
            parse_str($urlParts['query'] ?? '', $originalParams);

            // Override and merge the original parameters with the new ones
            $parsedParams = array_merge($originalParams, $forwardParams);

            // Build the URL
            $url = $urlParts['scheme'] . '://' . $urlParts['host'] . ($urlParts['path'] ?? '/') . '?' . http_build_query($parsedParams);

            return $url;
        }

        return $url;
    }
}