<?php

namespace RiseTech\FormRequest\Contracts\Services\Drivers;

abstract class ServiceStates
{
    abstract public static function get($country):?array;
}
