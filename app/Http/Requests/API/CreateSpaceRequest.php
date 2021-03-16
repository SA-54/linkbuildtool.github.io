<?php

namespace App\Http\Requests\API;

use App\Http\Requests\CreateSpaceRequest as BaseCreateSpaceRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class CreateSpaceRequest extends BaseCreateSpaceRequest
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

