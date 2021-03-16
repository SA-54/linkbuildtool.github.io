<?php

namespace App\Rules;

use App\Traits\UserFeaturesTrait;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class ValidateDeepLinkRule implements Rule
{
    use UserFeaturesTrait;

    private $userFeatures;

    /**
     * Create a new rule instance.
     *
     * @param $userFeatures
     */
    public function __construct($userFeatures)
    {
        $this->userFeatures = $userFeatures;
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
        $user = Auth::user();

        $url = parse_url($value);

        // If the URL is not a website link
        if (isset($url['scheme']) && $url['scheme'] != 'http' && $url['scheme'] != 'https') {
            if (!Auth::check()) {
                return false;
            }

            if ($user->cannot('deepLinks', ['App\Link', $this->userFeatures['option_deep_links']])) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('You don\'t have access to this feature.');
    }
}
