<?php

namespace App\Rules;

use App\Plan;
use Illuminate\Contracts\Validation\Rule;

class ValidateCouponCodeRule implements Rule
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
        $plan = Plan::where([['id', '=', request()->route('id')], ['amount_month', '>', 0], ['amount_year', '>', 0]])->firstOrFail();

        // Get the URLs
        $coupons = preg_split('/\n|\r/', $plan['coupons'], -1, PREG_SPLIT_NO_EMPTY);

        if (in_array($value, $coupons)) {
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
        return __('The coupon code could not be found.');
    }
}
