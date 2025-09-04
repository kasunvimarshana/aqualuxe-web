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
                    'ok'      => true,
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
    \call_user_func(
            'register_rest_route',
            'aqualuxe/v1',
            '/import/start',
            [
                'methods' => 'POST',
                'permission_callback' => function () { return \call_user_func('current_user_can', 'manage_options'); },
                'callback' => function ($request) {
                    $entities = (array) ($request->get_param('entities') ?: []);
                    $reset = (bool) $request->get_param('reset');
                    $volume = (int) ($request->get_param('volume') ?: 10);
                    $policy = (string) ($request->get_param('policy') ?: 'skip');
                    $locale = (string) ($request->get_param('locale') ?: 'en_US');
                    $range = (array) ($request->get_param('range') ?: []);
                    $assets = (array) ($request->get_param('assets') ?: []);
                    $currency = (string) ($request->get_param('currency') ?: 'USD');
                    $localesExtra = (array) ($request->get_param('localesExtra') ?: []);
                    return \AquaLuxe\Admin\Importer::start($entities, $reset, $volume, $policy, $locale, $range, $assets, $currency, $localesExtra);
                },
            ]
        );
        \call_user_func('register_rest_route', 'aqualuxe/v1', '/import/step', [
            'methods' => 'POST',
            'permission_callback' => function () { return \call_user_func('current_user_can','manage_options'); },
            'callback' => function () {
                return \AquaLuxe\Admin\Importer::step();
            }
        ]);
        \call_user_func('register_rest_route', 'aqualuxe/v1', '/import/export', [
            'methods' => 'POST',
            'permission_callback' => function () { return \call_user_func('current_user_can','manage_options'); },
            'callback' => function ($request) {
                $entities = (array) ($request->get_param('entities') ?: []);
                return \AquaLuxe\Admin\Importer::export($entities);
            }
        ]);

        // Preview endpoint (admin only)
        \call_user_func('register_rest_route', 'aqualuxe/v1', '/import/preview', [
            'methods' => 'POST',
            'permission_callback' => function () { return \call_user_func('current_user_can','manage_options'); },
            'callback' => function ($request) {
                $entities = (array) ($request->get_param('entities') ?: []);
                $volume = (int) ($request->get_param('volume') ?: 10);
                return \AquaLuxe\Admin\Importer::preview($entities, $volume);
            }
        ]);

        // Scheduling endpoints
        \call_user_func('register_rest_route', 'aqualuxe/v1', '/import/schedule', [
            'methods' => 'POST',
            'permission_callback' => function () { return \call_user_func('current_user_can','manage_options'); },
            'callback' => function ($request) {
                $entities = (array) ($request->get_param('entities') ?: []);
                $reset = (bool) $request->get_param('reset');
                $volume = (int) ($request->get_param('volume') ?: 10);
                $recurrence = (string) ($request->get_param('recurrence') ?: 'daily');
                return \AquaLuxe\Admin\Importer::schedule($entities, $reset, $volume, $recurrence);
            }
        ]);
        \call_user_func('register_rest_route', 'aqualuxe/v1', '/import/schedule/state', [
            'methods' => 'GET',
            'permission_callback' => function () { return \call_user_func('current_user_can','manage_options'); },
            'callback' => function () {
                $cfg = (array) (\function_exists('get_option') ? \call_user_func('get_option', 'aqlx_import_schedule', []) : []);
                $nextTs = \function_exists('wp_next_scheduled') ? \call_user_func('wp_next_scheduled', 'aqlx_scheduled_reinit') : 0;
                return [ 'ok' => true, 'schedule' => $cfg, 'next' => $nextTs, 'next_iso' => $nextTs ? gmdate('c', (int) $nextTs) : null ];
            }
        ]);
        \call_user_func('register_rest_route', 'aqualuxe/v1', '/import/schedule/clear', [
            'methods' => 'POST',
            'permission_callback' => function () { return \call_user_func('current_user_can','manage_options'); },
            'callback' => function () {
                \wp_clear_scheduled_hook('aqlx_scheduled_reinit');
                \delete_option('aqlx_import_schedule');
                return ['ok' => true];
            }
        ]);

        // Flush all demo content and reset importer state
        \call_user_func('register_rest_route', 'aqualuxe/v1', '/import/flush', [
            'methods' => 'POST',
            'permission_callback' => function () { return \call_user_func('current_user_can','manage_options'); },
            'callback' => function () {
                return \AquaLuxe\Admin\Importer::flush();
            }
        ]);

        // Importer state
        \call_user_func('register_rest_route', 'aqualuxe/v1', '/import/state', [
            'methods' => 'GET',
            'permission_callback' => function () { return \call_user_func('current_user_can','manage_options'); },
            'callback' => function () {
                return \AquaLuxe\Admin\Importer::state();
            }
        ]);

        // List recent audit logs
        \call_user_func('register_rest_route', 'aqualuxe/v1', '/import/audits', [
            'methods' => 'GET',
            'permission_callback' => function () { return \call_user_func('current_user_can','manage_options'); },
            'callback' => function () {
                $upload = \wp_upload_dir();
                $dir = trailingslashit($upload['basedir']) . 'aqualuxe-import-logs/';
                if (!file_exists($dir) || !is_dir($dir)) {
                    return ['ok' => true, 'items' => []];
                }
                $paths = glob($dir . '*.jsonl');
                if (!$paths) { return ['ok' => true, 'items' => []]; }
                usort($paths, function ($a, $b) { return (int) filemtime($b) <=> (int) filemtime($a); });
                $items = [];
                $paths = array_slice($paths, 0, 25);
                foreach ($paths as $p) {
                    $base = basename($p);
                    $mtime = file_exists($p) ? (int) filemtime($p) : 0;
                    $url = trailingslashit($upload['baseurl']) . 'aqualuxe-import-logs/' . $base;
                    $items[] = [
                        'name' => $base,
                        'url' => $url,
                        'mtime' => $mtime,
                        'mtime_iso' => $mtime ? gmdate('c', (int) $mtime) : null,
                        'size' => file_exists($p) ? (int) filesize($p) : null,
                    ];
                }
                return ['ok' => true, 'items' => $items];
            }
        ]);

        // Pause/resume/cancel importer
        \call_user_func('register_rest_route', 'aqualuxe/v1', '/import/pause', [
            'methods' => 'POST',
            'permission_callback' => function () { return \call_user_func('current_user_can','manage_options'); },
            'callback' => function () { return \AquaLuxe\Admin\Importer::pause(); }
        ]);
        \call_user_func('register_rest_route', 'aqualuxe/v1', '/import/resume', [
            'methods' => 'POST',
            'permission_callback' => function () { return \call_user_func('current_user_can','manage_options'); },
            'callback' => function () { return \AquaLuxe\Admin\Importer::resume(); }
        ]);
        \call_user_func('register_rest_route', 'aqualuxe/v1', '/import/cancel', [
            'methods' => 'POST',
            'permission_callback' => function () { return \call_user_func('current_user_can','manage_options'); },
            'callback' => function () { return \AquaLuxe\Admin\Importer::cancel(); }
        ]);
    }
}
