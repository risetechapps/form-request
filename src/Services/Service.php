<?php

namespace RiseTech\FormRequest\Services;

abstract class Service
{
    protected static array $drivers = [];

    protected static int $tries = 0;

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
