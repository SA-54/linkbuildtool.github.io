<?php

namespace App\Http\Requests;

use App\Rules\ValidateCouponCodeRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateUserSubscriptionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'coupon' => ['sometimes', 'min:1', new ValidateCouponCodeRule()]
        ];
    }
}
