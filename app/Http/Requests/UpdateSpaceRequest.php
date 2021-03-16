<?php

namespace App\Http\Requests;

use App\Space;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UpdateSpaceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // If the request is to edit a space as a specific user
        // And the user is not an admin
        if (request()->has('user_id') && Auth::user()->role == 0) {
            return false;
        }

        // Check if the space to be edited exists under that user
        if (request()->has('user_id')) {
            Space::where([['id', '=', request()->route('id')], ['user_id', '=', request()->input('user_id')]])->firstOrFail();
        }

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
            'name' => ['sometimes', 'min:1', 'max:32', 'unique:spaces,name,'.request()->route('id').',id,user_id,'.(request()->input('user_id') ?? Auth::user()->id)],
            'color' => ['sometimes', 'integer', 'between:1,6']
        ];
    }
}
