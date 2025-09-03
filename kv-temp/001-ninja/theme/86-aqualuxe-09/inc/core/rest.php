<?php
namespace AquaLuxe\Core;

class REST
{
    public static function register_routes(): void
    {
    \call_user_func('register_rest_route', 'aqualuxe/v1', '/status', [
            'methods' => 'GET',
            'permission_callback' => '__return_true',
            'callback' => function () {
        return [
                    'ok' => true,
                    'version' => AQUALUXE_VERSION,
        ];
            },
    ]);

        // Quick View endpoint (dual-state). If WooCommerce, return product snippet; else post snippet.
        \call_user_func('register_rest_route', 'aqualuxe/v1', '/quickview/(?P<id>\\d+)', [
            'methods' => 'GET',
            'permission_callback' => '__return_true',
            'args' => [
                'id' => [ 'validate_callback' => 'is_numeric' ],
            ],
            'callback' => function ($request) {
                $id = (int) $request['id'];
                if ($id <= 0) {
                    return ['error' => 'invalid_id'];
                }
                ob_start();
                $context = [];
                if (class_exists('WooCommerce') && function_exists('wc_get_product')) {
                    $product = \wc_get_product($id);
                    if (!$product) {
                        return ['error' => 'not_found'];
                    }
                    $context = [ 'type' => 'product', 'product' => $product ];
                } else {
                    $post = \get_post($id);
                    if (!$post) {
                        return ['error' => 'not_found'];
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
                return ['html' => $html];
            }
        ]);

        // Importer endpoints (admin only)
        \call_user_func('register_rest_route', 'aqualuxe/v1', '/import/start', [
            'methods' => 'POST',
            'permission_callback' => function(){ return \call_user_func('current_user_can','manage_options'); },
            'callback' => function ($request) {
                $entities = (array) ($request->get_param('entities') ?: []);
                $reset = (bool) $request->get_param('reset');
                $volume = (int) ($request->get_param('volume') ?: 10);
                return \AquaLuxe\Admin\Importer::start($entities, $reset, $volume);
            }
        ]);
        \call_user_func('register_rest_route', 'aqualuxe/v1', '/import/step', [
            'methods' => 'POST',
            'permission_callback' => function(){ return \call_user_func('current_user_can','manage_options'); },
            'callback' => function () {
                return \AquaLuxe\Admin\Importer::step();
            }
        ]);
        \call_user_func('register_rest_route', 'aqualuxe/v1', '/import/export', [
            'methods' => 'POST',
            'permission_callback' => function(){ return \call_user_func('current_user_can','manage_options'); },
            'callback' => function ($request) {
                $entities = (array) ($request->get_param('entities') ?: []);
                return \AquaLuxe\Admin\Importer::export($entities);
            }
        ]);

        // Scheduling endpoints
        \call_user_func('register_rest_route', 'aqualuxe/v1', '/import/schedule', [
            'methods' => 'POST',
            'permission_callback' => function(){ return \call_user_func('current_user_can','manage_options'); },
            'callback' => function ($request) {
                $entities = (array) ($request->get_param('entities') ?: []);
                $reset = (bool) $request->get_param('reset');
                $volume = (int) ($request->get_param('volume') ?: 10);
                $recurrence = (string) ($request->get_param('recurrence') ?: 'daily');
                return \AquaLuxe\Admin\Importer::schedule($entities, $reset, $volume, $recurrence);
            }
        ]);
        \call_user_func('register_rest_route', 'aqualuxe/v1', '/import/schedule/clear', [
            'methods' => 'POST',
            'permission_callback' => function(){ return \call_user_func('current_user_can','manage_options'); },
            'callback' => function () {
                \wp_clear_scheduled_hook('aqlx_scheduled_reinit');
                \delete_option('aqlx_import_schedule');
                return ['ok' => true];
            }
        ]);
    }
}
