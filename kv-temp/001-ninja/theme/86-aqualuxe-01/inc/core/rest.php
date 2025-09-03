<?php
namespace AquaLuxe\Core;

class REST
{
    public static function register_routes(): void
    {
        \register_rest_route('aqualuxe/v1', '/status', [
            'methods' => 'GET',
            'permission_callback' => '__return_true',
            'callback' => function () {
                return new \WP_REST_Response([
                    'ok' => true,
                    'version' => AQUALUXE_VERSION,
                ], 200);
            },
        ]);
    }
}
