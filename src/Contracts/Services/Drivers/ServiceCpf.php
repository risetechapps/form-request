<?php

namespace RiseTech\FormRequest\Contracts\Services\Drivers;

abstract class ServiceCpf
{
    abstract public static function get($cpf, $date): ?array;

    abstract public static function cpf($data): ?string;

    abstract public static function name($data): ?string;

    abstract public static function status($data): ?string;

    abstract public static function dateBirth($data): ?string;

}
