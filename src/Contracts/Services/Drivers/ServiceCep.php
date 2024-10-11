<?php

namespace RiseTech\FormRequest\Contracts\Services\Drivers;

use RiseTech\FormRequest\Services\ServicesForm;

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
    abstract protected static function state($data, $country): ?string;

    /** GET País */
    abstract protected static function country($data): ?string;


    public static function getIsoCountry(string $name): string
    {
        $name = mb_strtolower($name);
        $data = ServicesForm::getCountries();

        for ($i = 0; $i < count($data); $i++) {

            $translations = $data[$i]['translations'];

            if (mb_strtolower($data[$i]['name']) == $name) {
                return $data[$i]['iso3'];
            }

            foreach ($translations as $key => $value) {
                if (mb_strtolower($value) == $name) {
                    return $data[$i]['iso3'];
                }
            }
        }

        return $name;
    }

    public static function getIsoState(string $name, $country): string
    {
        $name = mb_strtolower($name);
        $data = ServicesForm::getStates($country);

        for ($i = 0; $i < count($data); $i++) {
            if (mb_strtolower($data[$i]['name']) == $name
                || mb_strtolower($data[$i]['country']) == $name
                || mb_strtolower($data[$i]['name']) == $name) {
                return $data[$i]['name'];
            }
        }

        return $name;
    }
}
