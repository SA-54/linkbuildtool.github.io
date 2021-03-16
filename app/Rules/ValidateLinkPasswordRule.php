<?php

namespace App\Rules;

use App\Domain;
use App\Link;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ValidateLinkPasswordRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
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
                $link = Link::where([['alias', '=', request()->route('id')], ['domain_id', '=', $domain->id]])->first();
            }
        } else {
            $link = Link::where([['alias', '=', request()->route('id')], ['domain_id', '=', null]])->first();
        }

        if (Hash::check($value, $link->password)) {
            return true;
        }

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('The entered password is not correct.');
    }
}
