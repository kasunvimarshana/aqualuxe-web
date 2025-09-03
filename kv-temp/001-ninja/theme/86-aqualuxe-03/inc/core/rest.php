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

        // Quick View endpoint (dual-state). If WooCommerce, return product snippet; else post snippet.
        \register_rest_route('aqualuxe/v1', '/quickview/(?P<id>\d+)', [
            'methods' => 'GET',
            'permission_callback' => '__return_true',
            'args' => [
                'id' => [ 'validate_callback' => 'is_numeric' ],
            ],
            'callback' => function ($request) {
                $id = (int) $request['id'];
                if ($id <= 0) {
                    return new \WP_REST_Response(['error' => 'invalid_id'], 400);
                }
                ob_start();
                $context = [];
                if (class_exists('WooCommerce') && function_exists('wc_get_product')) {
                    $product = \wc_get_product($id);
                    if (!$product) {
                        return new \WP_REST_Response(['error' => 'not_found'], 404);
                    }
                    $context = [ 'type' => 'product', 'product' => $product ];
                } else {
                    $post = \get_post($id);
                    if (!$post) {
                        return new \WP_REST_Response(['error' => 'not_found'], 404);
                    }
                    $context = [ 'type' => 'post', 'post' => $post ];
                }
                // Load partial template
                $tpl = AQUALUXE_TEMPLATES . '/parts/quick-view.php';
                if (file_exists($tpl)) {
                    include $tpl;
                } else {
                    echo '<div class="p-6">' . \esc_html__('Preview unavailable.', 'aqualuxe') . '</div>';
                }
                $html = (string) ob_get_clean();
                return new \WP_REST_Response(['html' => $html], 200);
            }
        ]);
    }
}
