<?php

namespace RiseTech\FormRequest\Contracts;

interface ValidatorContract
{
    public static function validate($attribute, $value, $parameters, $validator);
}
