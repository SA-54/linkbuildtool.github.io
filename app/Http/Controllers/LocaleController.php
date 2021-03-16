<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class LocaleController extends Controller
{
    public function index(Request $request)
    {
        // Get all the available languages
        $languages = config('app.locales');

        // Get the selected language
        $language = $request->input('locale');

        // If the selected language exists
        if (array_key_exists($language, $languages)) {
            Cookie::queue(Cookie::make('locale', $language, (60 * 24 * 365 * 10)));

            // Update the user's locale
            if(Auth::check()) {
                $user = Auth::user();
                $user->locale = $language;
                $user->save();
            }
        }

        return redirect()->back();
    }
}
