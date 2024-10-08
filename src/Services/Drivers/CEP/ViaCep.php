<?php

namespace RiseTech\FormRequest\Services\Drivers\CEP;

use GuzzleHttp\Client;
use RiseTech\FormRequest\Contracts\Services\Drivers\ServiceCep;

class ViaCep extends ServiceCep
{
    public static function get($cep): ?array
    {
        $client = new Client();

        try {
            $response = $client->get("https://viacep.com.br/ws/{$cep}/json/");

            if ($response->getStatusCode() !== 200) {
                return null;
            }

            $dados = json_decode($response->getBody(), true);

            if (isset($dados['erro'])) {
                return null;
            }


            $country = static::country($dados);
            $state = static::state($dados, $country);

            return [
                'zip_code' => static::cep($dados),
                'address' => static::address($dados),
                'number' => static::number($dados),
                'complement' => static::complement($dados),
                'district' => static::district($dados),
                'country' => $country,
                'state' => $state,
                'city' => static::city($dados),
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
        return $data['logradouro'] ?? null;
    }

    protected static function number($data): ?string
    {
        return $data['unidade'] ?? null;
    }

    protected static function complement($data): ?string
    {
        return $data['complemento'] ?? null;
    }

    protected static function district($data): ?string
    {
        return $data['bairro'] ?? null;
    }

    protected static function city($data): ?string
    {
        return $data['localidade'] ?? null;
    }

    protected static function state($data, $country): ?string
    {
        return static::getIsoState($data['uf'] ?? null, $country);
    }

    protected static function country($data): ?string
    {
        return static::getIsoCountry('Brasil');
    }
}
