<?php

namespace RiseTech\FormRequest\Services;

use RiseTech\FormRequest\Contracts\Services\ServicesFormContract;
use SebastianBergmann\CodeCoverage\Driver\Driver;

class ServiceApi
{
    public static function drivers(): array
    {
        return [
            'cep' => [
                Drivers\CEP\ViaCep::class,
                Drivers\CEP\BrasilApiCepOne::class,
                Drivers\CEP\BrasilApiCepTwo::class,
            ],

            'cnpj' => [
                Drivers\CNPJ\BrasilApiCnpj::class,
                Drivers\CNPJ\WsCnpj::class,
                Drivers\CNPJ\ReceitaWsCnpj::class
            ],

            'cpf' => [
                Drivers\CPF\HubDesenvolvedorCPF::class
            ],

            'banks' => [
                Drivers\Banks\BrasilApiBanks::class
            ],

            'countries' => [
                Drivers\Geonames\Countries\CountriesGitHub::class
            ],

            'states' => [
                Drivers\Geonames\States\StatesGitHub::class
            ],

            'cities' => [
                Drivers\Geonames\Cities\CitiesGitHub::class
            ]
        ];
    }
}
