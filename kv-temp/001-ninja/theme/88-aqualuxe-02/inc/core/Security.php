<?php

declare(strict_types=1);

namespace AquaLuxe\Core;

class Security
{
    public static function init(): void
    {
        Helpers::wp('add_action', ['send_headers', [self::class, 'headers']]);
        Helpers::wp('add_filter', ['xmlrpc_enabled', '__return_false']);
    }

    public static function headers(): void
    {
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: SAMEORIGIN');
        header('Referrer-Policy: no-referrer-when-downgrade');
    }
}
