<?php

namespace RiseTech\FormRequest\Contracts\Services;

interface ServicesFormContract
{
    public static function getCEP($cep): ?array;

    public static function getCNPJ($cnpj): ?array;

    public static function getCPF($cpf, $date): ?array;
}
