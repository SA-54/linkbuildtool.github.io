<?php

namespace App\Http\Requests\API;

use App\Http\Requests\CreateDomainRequest as BaseCreateDomainRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class CreateDomainRequest extends BaseCreateDomainRequest
{
    /**
     *
     * @param Validator $validator
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'message' => $validator->errors(),
                'status' => 422
            ], 422));
    }
}

