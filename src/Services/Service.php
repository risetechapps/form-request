<?php

namespace RiseTech\FormRequest\Services;

abstract class Service
{
    protected static array $drivers = [];

    protected static int $tries = 0;

    public static function erro($msg): array
    {
        return ['success' => false, 'data' => $msg];
    }

    public static function sucess($data): array
    {
        return ['success' => false, 'data' => $data];
    }

    protected static function getDriver(string $driver)
    {
        if (array_key_exists($driver, self::$drivers)) {
            $count = count(self::$drivers[$driver]);

            if ($count <= self::$tries) {
                return self::$drivers[$driver][self::$tries];
            }

        }
        return null;
    }
}
