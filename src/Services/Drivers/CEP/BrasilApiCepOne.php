<?php

namespace RiseTech\FormRequest\Services\Drivers\CEP;

use GuzzleHttp\Client;
use RiseTech\FormRequest\Contracts\Services\Drivers\ServiceCep;

class BrasilApiCepOne extends ServiceCep
{
    public static function get($cep): ?array
    {
        $client = new Client();

        try {
            $response = $client->get("https://brasilapi.com.br/api/cep/v1/{$cep}");

            if ($response->getStatusCode() !== 200) {
                return null;
            }

            $dados = json_decode($response->getBody(), true);

            if (isset($dados['errors'])) {
                return null;
            }

            return [
                'cep' => self::cep($dados),
                'address' => self::address($dados),
                'number' => self::number($dados),
                'complement' => self::complement($dados),
                'district' => self::district($dados),
                'city' => self::city($dados),
                'state' => self::state($dados),
                'country' => self::country($dados),
            ];

        } catch (\Exception $e) {
            return null;
        }
    }

    protected static function cep($data): ?string
    {
        return $data['cep'] ?? null;
    }

    protected static function address($data): ?string
    {
        return $data['street'] ?? null;
    }

    protected static function number($data): ?string
    {
        return $data['number'] ?? null;
    }

    protected static function complement($data): ?string
    {
        return $data['complement'] ?? null;
    }

    protected static function district($data): ?string
    {
        return $data['neighborhood'] ?? null;
    }

    protected static function city($data): ?string
    {
        return $data['city'] ?? null;
    }

    protected static function state($data): ?string
    {
        return $data['state'] ?? null;
    }

    protected static function country($data): ?string
    {
        return 'Brasil';
    }
}
