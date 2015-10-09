<?php

namespace Modules\Support\Helpers;

use Modules\Core\Exceptions\ValidationException;

class Validator
{

    /**
     * @return array
     */
    public function defaultRules()
    {
        return [];
    }


    /**
     * @return array
     */
    public function validatorAttributeNames()
    {
        return [];
    }


    /**
     * @param array $data
     * @param null  $rules
     * @param array $messages
     * @param array $customAttributes
     *
     * @return \Illuminate\Validation\Validator
     */
    public function validator(array $data = [], $rules = null, array $messages = [], array $customAttributes = [])
    {
        if (is_null($rules)) {
            $rules = $this->validationRules;
        }

        return \Validator::make($data, $rules, $messages, $customAttributes);
    }


    /**
     * @param array $data
     * @param null  $rules
     * @param array $messages
     * @param array $customAttributes
     *
     * @return bool
     * @throws ValidationException
     */
    public function validate(array $data = [], $rules = null, array $messages = [], array $customAttributes = [])
    {
        $validator = $this->validator($data, $rules, $messages, $customAttributes);

        return $this->_validate($validator);
    }


    /**
     * @param \Illuminate\Validation\Validator $validator
     *
     * @return bool
     * @throws ValidationException
     */
    protected function _validate(\Illuminate\Validation\Validator $validator)
    {
        if ( ! empty( $attributeNames = $this->validatorAttributeNames() )) {
            $validator->addCustomAttributes($attributeNames);
        }

        if ($validator->fails()) {
            throw (new ValidationException)->setValidator($validator);
        }

        return true;
    }
}