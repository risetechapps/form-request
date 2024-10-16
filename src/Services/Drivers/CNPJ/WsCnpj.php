<?php

namespace RiseTech\FormRequest\Services\Drivers\CNPJ;

use GuzzleHttp\Client;
use RiseTech\FormRequest\Contracts\Services\Drivers\ServiceCnpj;

class WsCnpj extends ServiceCnpj
{
    public static function get($cnpj): ?array
    {
        $client = new Client();

        try {
            $response = $client->get("https://publica.cnpj.ws/cnpj/{$cnpj}");

            if ($response->getStatusCode() !== 200) {
                return null;
            }

            $dados = json_decode($response->getBody(), true);

            if (isset($dados['errors'])) {
                return null;
            }

            $country = static::country($dados);
            $state = static::state($dados, $country);

            return [
                'cnpj' => static::cnpj($dados),
                'social_name' => static::socialName($dados),
                'fantasy_name' => static::fantasyName($dados),
                'size' => static::size($dados),
                'cnae' => static::cnae($dados),
                'cnae_description' => static::cnaeDescription($dados),
                'activities' => static::cnaeSecondary($dados),
                'social_capital' => static::socialCapital($dados),
                'nature' => static::legaNature($dados),
                'status' => static::descriptionRegistrationStatus($dados),
                'date_created' => static::creationDate($dados),
                'date_deleted' => static::deletionDate($dados),
                'type' => static::getType($dados),
                'address' => [
                    'cep' => static::cep($dados),
                    'address' => static::address($dados),
                    'number' => static::number($dados),
                    'complement' => static::complement($dados),
                    'district' => static::district($dados),
                    'country' => $country,
                    'state' => $state,
                    'city' => static::city($dados),
                ],
                'client' => [
                    'name' => static::name($dados),
                    'email' => static::email($dados),
                    'cellphone' => static::cellphone($dados),
                ]
            ];

        } catch (\Exception $e) {
            return null;
        }
    }

    protected static function cep($data): ?string
    {
        return $data['estabelecimento']['cep'] ?? null;
    }

    protected static function address($data): ?string
    {
        return $data['estabelecimento']['logradouro'] ?? null;
    }

    protected static function number($data): ?string
    {
        return $data['estabelecimento']['numero'] ?? null;
    }

    protected static function complement($data): ?string
    {
        return $data['estabelecimento']['complemento'] ?? null;
    }

    protected static function district($data): ?string
    {
        return $data['estabelecimento']['bairro'] ?? null;
    }

    protected static function city($data): ?string
    {
        return $data['estabelecimento']['estado']['nome'] ?? null;
    }

    protected static function state($data, $country): ?string
    {
        return static::getIsoState($data['estabelecimento']['cidade']['nome'] ?? null, $country);
    }

    protected static function country($data): ?string
    {
        return static::getIsoCountry('Brasil');
    }

    protected static function cnpj($data): ?string
    {
        return $data['estabelecimento']['cnpj'] ?? null;
    }

    protected static function socialName($data): ?string
    {
        return $data['razao_social'] ?? null;
    }

    protected static function fantasyName($data): ?string
    {
        return $data['estabelecimento']['nome_fantasia'] ?? null;
    }

    protected static function size($data): ?string
    {
        return $data['porte']['descricao'] ?? null;
    }

    protected static function cnae($data): ?string
    {
        return $data['estabelecimento']['atividade_principal']['id'] ?? null;
    }

    protected static function cnaeDescription($data): ?string
    {
        return $data['estabelecimento']['atividade_principal']['descricao'] ?? null;
    }

    protected static function cnaeSecondary($data): ?array
    {
        return array_map(function ($item){
            return [
                'id' => $item['id'],
                'description' => $item['descricao']
            ];
        }, $data['estabelecimento']['atividades_secundarias'] ?? []);
    }

    protected static function socialCapital($data): ?string
    {
        return $data['capital_social'] ?? null;
    }

    protected static function legaNature($data): ?string
    {
        return $data['natureza_juridica']['descricao'] ?? null;
    }

    protected static function descriptionRegistrationStatus($data): ?string
    {
        return $data['estabelecimento']['situacao_cadastral'] ?? null;
    }

    protected static function creationDate($data): ?string
    {
        return $data['simples']['data_opcao_simples'] ?? null;
    }

    protected static function deletionDate($data): ?string
    {
        return $data['simples']['data_exclusao_simples'] ?? null;
    }

    protected static function reasonDelete($data): ?string
    {
        return $data['estabelecimento']['cnpj'] ?? null;
    }

    protected static function getType($data): ?string
    {
        return $data['estabelecimento']['tipo'] ?? null;
    }

    protected static function name($data): ?string
    {
        $name = $data['razao_social'] ?? null;
        return rtrim(ltrim(preg_replace('/[^a-zA-Z\s]/', '', $name))) ?? null;
    }

    protected static function cellphone($data): ?string
    {
        return $data['estabelecimento']['telefone1'] ?? null;
    }

    protected static function email($data): ?string
    {
        return $data['estabelecimento']['email'] ?? null;
    }
}
