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

            return [
                'cep' => static::cep($dados),
                'address' => static::address($dados),
                'number' => static::number($dados),
                'complement' => static::complement($dados),
                'district' => static::district($dados),
                'city' => static::city($dados),
                'state' => static::state($dados),
                'country' => static::country($dados),
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

    protected static function state($data): ?string
    {
        return $data['uf'] ?? null;
    }

    protected static function country($data): ?string
    {
        return 'Brasil';
    }
}
