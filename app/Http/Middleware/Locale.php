<?php

namespace App\Http\Middleware;

use App\Language;
use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class Locale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            // Get all the available languages
            $languages = config('app.locales');

            if (Auth::check()) {
                $userLocale = Auth::user()->locale;

                if(array_key_exists($userLocale, $languages)) {
                    App::setLocale($userLocale);
                }
            } else if(Cookie::has('locale')) {
                // Get the current language
                $language = Cookie::get('locale');

                if(array_key_exists($language, $languages)) {
                    App::setLocale($language);
                }
            } else {
                App::setLocale(config('app.locale'));
            }
        } catch (\Exception $e) {
        }

        return $next($request);
    }
}
