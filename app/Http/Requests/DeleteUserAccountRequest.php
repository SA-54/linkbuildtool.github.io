<?php

namespace App\Http\Requests;

use App\Rules\ValidateUserPasswordRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class DeleteUserAccountRequest extends FormRequest
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
     * @param Request $request
     * @return array
     */
    public function rules(Request $request)
    {
        return [
            'current_password' => ['required', 'string', 'min:6', new ValidateUserPasswordRule($request)]
        ];
    }
}
