<?php

declare(strict_types=1);

namespace AquaLuxe\Core;

class Config
{
    private static array $config = [
        'modules' => [
            'DarkMode'   => true,
            'Importer'   => true,
            'WooFallback'=> true,
            'CPT'        => true,
            'WooUX'      => true,
        ],
    ];

    public static function get(string $key, $default = null)
    {
        return self::$config[$key] ?? $default;
    }

    public static function set(string $key, $value): void
    {
        self::$config[$key] = $value;
    }
}
