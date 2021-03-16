<?php

namespace App\Http\Middleware;

use App\Language;
use Closure;
use App\Setting;
use Illuminate\Support\Facades\Cookie;

class SettingsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            // Set the app's default locale
            $languages = Language::all();
            foreach ($languages as $language) {
                if ($language->default) {
                    config(['app.locale' => $language->code]);
                }

                config(['app.locales.' . $language->code => $language->name]);
            }

            $settings = Setting::all()->pluck('value', 'name');

            // Set the app's name
            config(['app.name' => $settings['title']]);

            // Store all the database settings in a config array
            foreach ($settings as $key => $value) {
                config(['settings.' . $key => $value]);
            }

            // Set the app's default theme
            if (!Cookie::has('dark_mode')) {
                Cookie::queue(Cookie::make('dark_mode', config('settings.theme'), (60 * 24 * 365 * 10), null, null, false, false));

                config(['settings.dark_mode' => config('settings.theme')]);
            } else {
                // Rewrite the settings.theme with the user's preference
                if (Cookie::get('dark_mode') == 1) {
                    config(['settings.dark_mode' => 1]);
                } else {
                    config(['settings.dark_mode' => 0]);
                }
            }

            // If cookie law is enabled
            if (config('settings.legal_cookie_url')) {

                // Set the cookie law
                if (!Cookie::has('cookie_law')) {
                    Cookie::queue(Cookie::make('cookie_law', 0, (60 * 24 * 365 * 10), null, null, false, false));
                }
            }

            // Set the app's default mail settings
            config(['mail.driver' => config('settings.email_driver')]);
            config(['mail.host' => config('settings.email_host')]);
            config(['mail.port' => config('settings.email_port')]);
            config(['mail.encryption' => config('settings.email_encryption')]);
            config(['mail.username' => config('settings.email_username')]);
            config(['mail.password' => config('settings.email_password')]);
            config(['mail.from.address' => config('settings.email_address')]);
            config(['mail.from.name' => config('settings.title')]);

            // Set the tripe settings
            config(['cashier.key' => config('settings.stripe_key')]);
            config(['cashier.secret' => config('settings.stripe_secret')]);
            config(['cashier.webhook.secret' => config('settings.stripe_wh_secret')]);

            // Set the reCaptcha keys
            config(['captcha.sitekey' => config('settings.captcha_site_key')]);
            config(['captcha.secret' => config('settings.captcha_secret_key')]);


        } catch (\Exception $e) {
        }

        return $next($request);
    }
}
