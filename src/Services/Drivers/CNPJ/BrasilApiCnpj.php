<?php

namespace RiseTech\FormRequest\Services\Drivers\CNPJ;

use GuzzleHttp\Client;
use RiseTech\FormRequest\Contracts\Services\Drivers\ServiceCnpj;

class BrasilApiCnpj extends ServiceCnpj
{
    public static function get($cnpj): ?array
    {
        $client = new Client();

        try {
            $response = $client->get("https://brasilapi.com.br/api/cnpj/v1/{$cnpj}");

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
                    'zip_code' => static::address($dados),
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
        return $data['cep'] ?? null;
    }

    protected static function address($data): ?string
    {
        return $data['logradouro'] ?? null;
    }

    protected static function number($data): ?string
    {
        return $data['numero'] ?? null;
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
        return $data['municipio'] ?? null;
    }

    protected static function state($data, $country): ?string
    {
        return static::getIsoState($data['uf'] ?? null, $country);
    }

    protected static function country($data): ?string
    {
        return static::getIsoCountry('Brasil');
    }

    protected static function cnpj($data): ?string
    {
        return $data['cnpj'] ?? null;
    }

    protected static function socialName($data): ?string
    {
        return $data['razao_social'] ?? null;
    }

    protected static function fantasyName($data): ?string
    {
        return $data['nome_fantasia'] ?? null;
    }

    protected static function size($data): ?string
    {
        return $data['porte'] ?? null;
    }

    protected static function cnae($data): ?string
    {
        return $data['cnae_fiscal'] ?? null;
    }

    protected static function cnaeDescription($data): ?string
    {
        return $data['cnae_fiscal_descricao'] ?? null;
    }

    protected static function cnaeSecondary($data): ?array
    {
        return array_map(function ($item) {
            return [
                'id' => $item['codigo'],
                'description' => $item['descricao']
            ];
        }, $data['cnaes_secundarios'] ?? []);
    }

    protected static function socialCapital($data): ?string
    {
        return $data['capital_social'] ?? null;
    }

    protected static function legaNature($data): ?string
    {
        return $data['natureza_juridica'] ?? null;
    }

    protected static function descriptionRegistrationStatus($data): ?string
    {
        return $data['descricao_situacao_cadastral'] ?? null;
    }

    protected static function creationDate($data): ?string
    {
        return $data['data_inicio_atividade'] ?? null;
    }

    protected static function deletionDate($data): ?string
    {
        return $data['data_exclusao_do_simples'] ?? null;
    }

    protected static function reasonDelete($data): ?string
    {
        return $data['descricao_motivo_situacao_cadastral'] ?? null;
    }

    protected static function getType($data): ?string
    {
        return $data['descricao_identificador_matriz_filial'] ?? null;
    }

    protected static function name($data): ?string
    {
        $name = $data['razao_social'] ?? null;
        return rtrim(ltrim(preg_replace('/[^a-zA-Z\s]/', '', $name))) ?? null;
    }

    protected static function email($data): ?string
    {
        return $data['email'] ?? null;
    }

    protected static function cellphone($data): ?string
    {
        return $data['ddd_telefone_1'] ?? null;
    }
}
