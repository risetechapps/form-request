<?php

namespace RiseTech\FormRequest\Contracts\Services\Drivers;

abstract class ServiceCountries
{
    abstract public static function get():?array;
}
