<?php

namespace RiseTech\FormRequest\Services;

use RiseTech\FormRequest\Contracts\Services\ServicesFormContract;
use SebastianBergmann\CodeCoverage\Driver\Driver;

class ServiceApi
{
    public static function drivers(): array
    {
        return [
            /*
             * https://opencep.com/v1/21720590
             * https://api.brasilaberto.com/v1/zipcode/26277630
             * https://cep.awesomeapi.com.br/json/05424020
             * */


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
