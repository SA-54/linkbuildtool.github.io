<?php

namespace App\Http\Requests;

use App\Rules\ValidateUserByEmailRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateSubscriptionRequest extends FormRequest
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
            'email' => ['required', new ValidateUserByEmailRule()],
            'plan' => ['required'],
            'trial_days' => ['required', 'integer', 'min:1']
        ];
    }
}
