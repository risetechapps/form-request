<?php

namespace RiseTech\FormRequest\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use RiseTech\FormRequest\Contracts\Services\ServicesFormContract;
use function Laravel\Prompts\select;

class ServicesForm extends Service implements ServicesFormContract
{
    protected static bool $supportTag = false;
    protected static Carbon $tll;
    protected static array $driverNotSupported = ["file"];

    protected static function rememberCache(callable $call, string $method, array $parameters = [])
    {
        if (!isset(self::$tll)) {
            self::$supportTag = !in_array(Cache::getDefaultDriver(), self::$driverNotSupported);
            self::$tll = Carbon::now()->addMonth();
        }

        if (self::$supportTag) {
            return Cache::tags(self::class)->remember(self::getQualifyTagCache($method, $parameters), self::$tll, $call);
        } else {
            return Cache::remember(self::getQualifyTagCache($method, $parameters), self::$tll, $call);
        }
    }

    protected static function getQualifyTagCache(string $method, array $parameters = []): string
    {
        $paramsHash = !empty($parameters) ? '_' . md5(json_encode($parameters)) : '';
        return '_' . $method . $paramsHash;
    }

    public static function getCEP($cep): ?array
    {
        $cep = preg_replace('/\D/', '', $cep);

        return self::rememberCache(function () use ($cep) {

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
        }, 'SERVICES_FORM_CEP', [$cep]);
    }

    public static function getCNPJ($cnpj): ?array
    {
        return self::rememberCache(function () use ($cnpj) {
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
        }, 'SERVICES_FORM_CNPJ', [$cnpj]);
    }

    public static function getCPF($cpf, $date): ?array
    {
        $cpf = preg_replace('/\D/', '', $cpf);

        if (strlen($cpf) !== 11) {
            return null;
        }

        return self::rememberCache(function () use ($cpf, $date) {
            $drivers = ServiceApi::drivers()['cpf'] ?? [];
            $result = null;

            foreach ($drivers as $driver) {
                try {
                    $result = $driver::get($cpf, $date);

                    if (!is_null($result)) {
                        return $result;
                    }
                } catch (\Exception $e) {

                }
            }
            return $result;
        }, 'SERVICES_FORM_CPF', [$cpf, $date]);

    }

    public static function getBANKS(): ?array
    {
        return self::rememberCache(function () {
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
        }, 'SERVICES_FORM_BANKS');
    }

    public static function getCountries(): ?array
    {
        return self::rememberCache(function () {

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
        }, 'SERVICES_FORM_COUNTRIES');
    }

    public static function getAllStates(): ?array
    {
        return self::rememberCache(function () {
            $drivers = ServiceApi::drivers()['all_states'] ?? [];
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
        }, 'SERVICES_FORM_ALL_STATES');
    }

    public static function getStates($country): ?array
    {
        return self::rememberCache(function () use ($country) {
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
        }, 'SERVICES_FORM_STATES', [$country]);
    }

    public static function getAllCities()
    {
        return self::rememberCache(function () {
            $drivers = ServiceApi::drivers()['all_cities'] ?? [];
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
        }, 'SERVICES_FORM_ALL_CITIES');
    }

    public static function getCities(mixed $country, mixed $state)
    {
        return self::rememberCache(function () use ($country, $state) {
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
        }, 'SERVICES_FORM_CITIES', [$country, $state]);
    }

    public static function getCountryInfo(mixed $country)
    {
        $countries = collect(self::getCountries());

        return self::rememberCache(function () use ($country, $countries) {
            return $countries->filter(function ($item) use ($country) {
                return $item['name'] == $country ||
                    $item['iso3'] == $country ||
                    $item['iso2'] == $country ||
                    $item['phone_code'] == $country ||
                    $item['id'] == $country;
            })->first();
        }, 'SERVICES_FORM_COUNTRY_INFO', [$country]);
    }

    public static function getStateInfo(mixed $country = '', mixed $state = '')
    {
        $states = collect(self::getStates($country));

        if (empty($country) || count($states) === 0) {

            $states = collect(self::getAllStates());
        } else {
            $states = collect(self::getStates($country));
        }

        return self::rememberCache(function () use ($state, $states) {
            return $states->filter(function ($item) use ($state) {
                return $item['name'] == $state ||
                    $item['country'] == $state ||
                    $item['state'] == $state ||
                    $item['id'] == $state;
            })->first();
        }, 'SERVICES_FORM_STATE_INFO', [$state]);
    }

    public static function getCityInfo(mixed $city, mixed $state = '', mixed $country = '')
    {

        if (empty($country) || empty($state)) {
            $cities = collect(self::getAllCities($city));
        } else {
            $cities = collect(self::getCities($country, $state));
        }

        return self::rememberCache(function () use ($city, $cities) {
            return $cities->filter(function ($item) use ($city) {
                return $item['name'] == $city ||
                    $item['id'] == $city;
            })->first();
        }, 'SERVICES_FORM_CITY_INFO', [$city]);
    }
}
