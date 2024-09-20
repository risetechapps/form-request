<?php

namespace RiseTech\FormRequest\Contracts\Services\Drivers;

abstract class ServiceCep
{
    /** GET CEP */
    abstract protected static function cep($data): ?string;

    /** GET Endereço */
    abstract protected static function address($data): ?string;

    /** GET Número do Endereço */
    abstract protected static function number($data): ?string;

    /** GET Complemento */
    abstract protected static function complement($data): ?string;

    /** GET Bairro */
    abstract protected static function district($data): ?string;

    /** GET Cidade */
    abstract protected static function city($data): ?string;

    /** GET Estado */
    abstract protected static function state($data): ?string;

    /** GET País */
    abstract protected static function country($data): ?string;
}
