<?php

namespace RiseTech\FormRequest\Services\Drivers\Geonames\Cities;

use GuzzleHttp\Client;

class CitiesAllGitHub
{
    public static function get(): ?array
    {

        $url = "https://raw.githubusercontent.com/risetechapps/Geonames/main/json/cities.json";

        $client = new Client();

        $response = $client->get($url);

        if ($response->getStatusCode() !== 200) {
            return null;
        }

        return json_decode($response->getBody(), true);
    }
}
