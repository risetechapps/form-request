<?php

namespace RiseTech\FormRequest\Contracts\Services\Drivers;

abstract class ServiceCities
{
    abstract public static function get($country, $state):?array;
}
