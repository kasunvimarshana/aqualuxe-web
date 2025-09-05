<?php

declare(strict_types=1);

namespace AquaLuxe\Core;

class REST
{
    public static function register_routes(): void
    {
        Helpers::wp('register_rest_route', ['aqualuxe/v1', '/ping', [
            'methods'  => 'GET',
            'callback' => static fn() => [ 'pong' => true, 'version' => AQUALUXE_VERSION ],
            'permission_callback' => '__return_true',
        ]]);
    }
}
