<?php

namespace RiseTech\FormRequest\Services\Drivers\Geonames\States;


use GuzzleHttp\Client;
use RiseTech\FormRequest\Contracts\Services\Drivers\ServiceStates;

class StatesGitHub extends ServiceStates
{

    public static function get($country): ?array
    {
        $country = mb_strtoupper($country);

        $url = "https://raw.githubusercontent.com/risetechapps/Geonames/main/json/{$country}/index.json";

        $client = new Client();

        $response = $client->get($url);

        if ($response->getStatusCode() !== 200) {
            return null;
        }

        return json_decode($response->getBody(), true);
    }
}
