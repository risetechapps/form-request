<?php

namespace RiseTech\FormRequest\Services\Drivers\Geonames\States;

use GuzzleHttp\Client;
use RiseTech\FormRequest\Contracts\Services\Drivers\ServiceStates;

class StatesAllGitHub
{

    public static function get(): ?array
    {
        $url = "https://raw.githubusercontent.com/risetechapps/Geonames/main/json/states.json";

        $client = new Client();

        $response = $client->get($url);

        if ($response->getStatusCode() !== 200) {
            return null;
        }

        return json_decode($response->getBody(), true);
    }
}
