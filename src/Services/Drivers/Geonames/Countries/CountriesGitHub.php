<?php

namespace RiseTech\FormRequest\Services\Drivers\Geonames\Countries;

use GuzzleHttp\Client;
use RiseTech\FormRequest\Contracts\Services\Drivers\ServiceCountries;

class CountriesGitHub extends ServiceCountries
{

    public static function get(): ?array
    {

        $url = "https://raw.githubusercontent.com/risetechapps/Geonames/main/json/index.json";

        $client = new Client();

        $response = $client->get($url);

        if ($response->getStatusCode() !== 200) {
            return null;
        }

        return json_decode($response->getBody(), true);
    }
}
