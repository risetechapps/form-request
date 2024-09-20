<?php

namespace RiseTech\FormRequest\Services\Drivers\Geonames\Cities;

use GuzzleHttp\Client;
use RiseTech\FormRequest\Contracts\Services\Drivers\ServiceCities;

class CitiesGitHub extends ServiceCities
{

    public static function get($country, $state): ?array
    {
        $country = mb_strtoupper($country);
        $state = mb_strtoupper($state);

        $url = "https://raw.githubusercontent.com/risetechapps/Geonames/main/json/{$country}/${state}/index.json";

        $client = new Client();

        $response = $client->get($url);

        if ($response->getStatusCode() !== 200) {
            return null;
        }

        return json_decode($response->getBody(), true);
    }
}
