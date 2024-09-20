<?php

namespace RiseTech\FormRequest\Services;

use RiseTech\FormRequest\Contracts\Services\ServicesFormContract;

class ServicesForm extends Service implements ServicesFormContract
{

    public static function getCEP($cep): ?array
    {
        $cep = preg_replace('/\D/', '', $cep);

        if (strlen($cep) !== 8) {
            return null;
        }
        $drivers = ServiceApi::drivers()['cep'] ?? [];
        $result = null;

        foreach ($drivers as $driver) {
            try {
                $result = $driver::get($cep);

                if (!is_null($result)) {
                    return $result;
                }
            } catch (\Exception $e) {

            }
        }
        return $result;
    }

    public static function getCNPJ($cnpj): ?array
    {
        $cnpj = preg_replace('/\D/', '', $cnpj);

        if (strlen($cnpj) !== 14) {
            return null;
        }
        $drivers = ServiceApi::drivers()['cnpj'] ?? [];
        $result = null;

        foreach ($drivers as $driver) {
            try {
                $result = $driver::get($cnpj);

                if (!is_null($result)) {
                    return $result;
                }
            } catch (\Exception $e) {

            }
        }
        return $result;
    }

    public static function getCPF($cpf, $date): ?array
    {
        $cnpj = preg_replace('/\D/', '', $cpf);

        if (strlen($cpf) !== 11) {
            return null;
        }
        $drivers = ServiceApi::drivers()['cpf'] ?? [];
        $result = null;

        foreach ($drivers as $driver) {
            try {
                $result = $driver::get($cpf, $date);

                if (!is_null($result)) {
                    return $result;
                }
            } catch (\Exception $e) {
                dd($e);
            }
        }
        return $result;
    }

    public static function getBANKS(): ?array
    {
        $drivers = ServiceApi::drivers()['banks'] ?? [];
        $result = null;

        foreach ($drivers as $driver) {
            try {
                $result = $driver::get();

                if (!is_null($result)) {
                    return $result;
                }
            } catch (\Exception $e) {

            }
        }
        return $result;
    }

    public static function getCountries(): ?array
    {
        $drivers = ServiceApi::drivers()['countries'] ?? [];
        $result = null;

        foreach ($drivers as $driver) {
            try {
                $result = $driver::get();

                if (!is_null($result)) {
                    return $result;
                }
            } catch (\Exception $e) {

            }
        }
        return $result;
    }

    public static function getStates($country): ?array
    {
        $drivers = ServiceApi::drivers()['states'] ?? [];
        $result = null;

        foreach ($drivers as $driver) {
            try {
                $result = $driver::get($country);

                if (!is_null($result)) {
                    return $result;
                }
            } catch (\Exception $e) {

            }
        }
        return $result;
    }

    public static function getCities(mixed $country, mixed $state)
    {
        $drivers = ServiceApi::drivers()['cities'] ?? [];
        $result = null;

        foreach ($drivers as $driver) {
            try {
                $result = $driver::get($country, $state);

                if (!is_null($result)) {
                    return $result;
                }
            } catch (\Exception $e) {

            }
        }
        return $result;
    }
}
