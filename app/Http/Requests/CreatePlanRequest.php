<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePlanRequest extends FormRequest
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
            'name' => ['required', 'max:64', 'unique:plans,name'],
            'description' => ['required'],
            'trial_days' => ['required', 'integer'],
            'currency' => ['required'],
            'amount_month' => ['required', 'integer', 'min:1'],
            'amount_year' => ['required', 'integer', 'min:1'],
            'visibility' => ['integer', 'between:0,1'],
            'color' => ['required', 'max:32'],
            'option_links' => ['required', 'integer'],
            'option_spaces' => ['required', 'integer'],
            'option_domains' => ['required', 'integer'],
            'option_password' => ['required', 'integer', 'between:0,1'],
            'option_expiration' => ['required', 'integer', 'between:0,1'],
            'option_stats' => ['required', 'integer', 'between:0,1'],
            'option_geo' => ['required', 'integer', 'between:0,1'],
            'option_platform' => ['required', 'integer', 'between:0,1'],
            'option_disabled' => ['required', 'integer', 'between:0,1'],
            'option_api' => ['required', 'integer', 'between:0,1'],
            'option_global_domains' => ['required', 'integer', 'between:0,1'],
            'option_link_rotation' => ['required', 'integer', 'between:0,1'],
            'option_deep_links' => ['required', 'integer', 'between:0,1']
        ];
    }
}
