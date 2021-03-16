<?php

namespace App\Http\Requests\API;

use App\Http\Requests\CreateLinkRequest as BaseCreateLinkRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class CreateLinkRequest extends BaseCreateLinkRequest
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

