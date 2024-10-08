<?php

namespace RiseTech\FormRequest\Services\Drivers\Banks;

use GuzzleHttp\Client;

class BrasilApiBanks
{
    public static function get():?array
    {
        $client = new Client();

        try {
            $response = $client->get("https://brasilapi.com.br/api/banks/v1");

            if ($response->getStatusCode() !== 200) {
                return null;
            }

            $dados = json_decode($response->getBody(), true);

            if (isset($dados['errors'])) {
                return null;
            }

            return array_map(function ($item){
                if(!is_null($item)){
                    return [
                        'code' => $item['code'] ?? null,
                        'name' => $item['fullName'] ?? null,
                    ];
                }
            }, $dados);

        } catch (\Exception $e) {
            return null;
        }
    }
}
