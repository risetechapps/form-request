<?php

namespace RiseTech\FormRequest\Contracts\Services\Drivers;


abstract class ServiceCnpj extends ServiceCep
{

    abstract public static function get($cnpj): ?array;

    /** Get CNPJ */
    abstract protected static function cnpj($data): ?string;

    /** Get Razão Social */
    abstract protected static function socialName($data): ?string;

    /** Get Nome Fantasia */
    abstract protected static function fantasyName($data): ?string;

    /** Get Porte = Microempresa|MEI|Empresa de Pequeno Porte */
    abstract protected static function size($data): ?string;

    /** Get Classificação Nacional de Atividades Econômicas = Codigo da atividade */
    abstract protected static function cnae($data): ?string;

    /** Get Classificação Nacional de Atividades Econômicas = Descrição */
    abstract protected static function cnaeDescription($data): ?string;

    /** Get Classificação Nacional de Atividades Econômicas Secundarias */
    abstract protected static function cnaeSecondary($data): ?array;

    /** Get Capital Social */
    abstract protected static function socialCapital($data): ?string;

    /** Get Natureza Juridica */
    abstract protected static function legaNature($data): ?string;

    /** Get Descricão Situacão Cadastral = ATIVA|BAIXADA */
    abstract protected static function descriptionRegistrationStatus($data): ?string;

    /** Get Data de Criação|Início de Atividade */
    abstract protected static function creationDate($data): ?string;

    /** Get Data de exclusão */
    abstract protected static function deletionDate($data): ?string;

    /** Get Motivo de exclusão */
    abstract protected static function reasonDelete($data): ?string;

    /** Get Matriz ou Filial  */
    abstract protected static function getType($data): ?string;

    /** Get Name Client */
    abstract protected static function name($data): ?string;

    /** Get Celular Client */
    abstract protected static function cellphone($data): ?string;

    /** Get Email Client */
    abstract protected static function email($data): ?string;

}
