<?php

namespace App\Http\Requests;

use App\Rules\LinkPublicGateRule;
use App\Rules\ValidateDomainOwnershipRule;
use App\Rules\ValidateSpaceOwnershipRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserPreferencesRequest extends FormRequest
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
            'default_space' => ['nullable', 'integer', new ValidateSpaceOwnershipRule()],
            'default_domain' => ['nullable', 'integer', new ValidateDomainOwnershipRule()],
            'default_stats' => ['nullable', 'integer', 'between:0,1']
        ];
    }
}
