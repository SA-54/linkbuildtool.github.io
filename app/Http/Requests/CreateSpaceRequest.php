<?php

namespace App\Http\Requests;

use App\Rules\SpaceLimitGateRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class CreateSpaceRequest extends FormRequest
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
            'name' => ['required', 'max:32', 'unique:spaces,name,null,id,user_id,'.$request->user()->id, new SpaceLimitGateRule()],
            'color' => ['required', 'integer', 'between:1,6']
        ];
    }
}
