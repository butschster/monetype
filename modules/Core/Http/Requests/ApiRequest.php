<?php

namespace Modules\Core\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use KodiCMS\API\Exceptions\MissingParameterException;
use KodiCMS\API\Exceptions\ValidationException;

abstract class ApiRequest extends FormRequest
{

    /**
     * @param Validator $validator
     */
    protected function failedValidation(Validator $validator)
    {
        throw (new ValidationException)->setValidator($validator);
    }
}