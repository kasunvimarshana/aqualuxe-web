<?php
namespace AquaLuxe\Core;

class RestApi {
    private string $ns = 'aqlx/v1';

    public function __construct() {
        \add_action( 'rest_api_init', [ $this, 'routes' ] );
    }

    public function routes(): void {
    if ( ! \function_exists( 'register_rest_route' ) ) {
            return;
        }
    \call_user_func( 'register_rest_route', $this->ns, '/health', [
            'methods'  => 'GET',
            'callback' => function () {
                return [ 'ok' => true, 'time' => time() ];
            },
            'permission_callback' => '__return_true',
    ] );
    }
}
