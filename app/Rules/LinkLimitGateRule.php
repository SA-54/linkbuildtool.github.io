<?php

namespace App\Rules;

use App\Traits\UserFeaturesTrait;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class LinkLimitGateRule implements Rule
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

        if ($user->can('create', ['App\Link', $this->userFeatures['option_links']])) {
            return true;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('You shortened too many links.');
    }
}
