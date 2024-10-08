<?php

namespace RiseTech\FormRequest\Services\Drivers\CPF;

use Carbon\Carbon;
use GuzzleHttp\Client;
use RiseTech\FormRequest\Contracts\Services\Drivers\ServiceCpf;

class HubDesenvolvedorCPF extends ServiceCpf
{


    public static function get($cpf, $date): ?array
    {
        //format Y-m-d
        try {
            $date = Carbon::createFromFormat('Y-m-d', $date)->format('d/m/Y');
        } catch (\Exception $e) {
            $date = Carbon::now()->format('d/m/Y');
        }

        $client = new Client();

        $token = env("TOKEN_CPF", "");

        try {
            $response = $client->get("https://ws.hubdodesenvolvedor.com.br/v2/cpf/?cpf={$cpf}&data=${date}&token=${token}");

            if ($response->getStatusCode() !== 200) {
                return null;
            }

            $dados = json_decode($response->getBody(), true);

            if ($dados['status'] === false) {
                return null;
            }

            return [
                'cpf' => self::cpf($dados),
                'name' => self::name($dados),
                'status' => self::status($dados),
                'birth_date' => self::dateBirth($dados),
            ];

        } catch (\Exception $e) {

            return null;
        }
    }

    public static function cpf($data): ?string
    {
        return $data['result']['numero_de_cpf'] ?? null;
    }

    public static function name($data): ?string
    {
        return $data['result']['nome_da_pf'] ?? null;
    }

    public static function status($data): ?string
    {
        return $data['result']['situacao_cadastral'] ?? null;
    }

    public static function dateBirth($data): ?string
    {
        $date = $data['result']['data_nascimento'];

        try {
            return Carbon::createFromFormat('d/m/Y', $date)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }
}
