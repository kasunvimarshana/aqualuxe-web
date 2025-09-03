<?php
/**
 * REST routes and external API integrations.
 */

namespace Aqualuxe\Providers;

use Aqualuxe\Support\Container;
use WP_REST_Server;

class REST_Service_Provider
{
    public function register(Container $c): void
    {
        add_action('rest_api_init', [$this, 'register_routes']);
    }

    public function boot(Container $c): void {}

    public function register_routes(): void
    {
        register_rest_route('aqualuxe/v1', '/health', [
            'methods'  => WP_REST_Server::READABLE,
            'callback' => function () {
                return [
                    'status' => 'ok',
                    'time'   => time(),
                ];
            },
            'permission_callback' => '__return_true',
        ]);
    }
}
