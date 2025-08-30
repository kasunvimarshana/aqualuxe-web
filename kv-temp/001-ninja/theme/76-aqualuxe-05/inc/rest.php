<?php
defined('ABSPATH') || exit;

add_action('rest_api_init', function () {
    register_rest_route('aqualuxe/v1', '/settings', [
        'methods' => 'GET',
        'callback' => function () {
            return [
                'primary' => get_theme_mod('aqualuxe_primary_color', '#0ea5e9'),
                'accent'  => get_theme_mod('aqualuxe_accent_color', '#14b8a6'),
                'darkDefault' => (bool) get_theme_mod('aqualuxe_dark_mode_default', false),
            ];
        },
        'permission_callback' => '__return_true',
    ]);

    register_rest_route('aqualuxe/v1', '/dark-mode', [
        'methods' => 'POST',
        'callback' => function ($req) {
            $enabled = (bool) (is_object($req) && method_exists($req, 'get_param') ? $req->get_param('enabled') : false);
            // Cookie for 1 year
            $ttl = 31536000; // 1 year
            $path = defined('COOKIEPATH') && COOKIEPATH ? COOKIEPATH : '/';
            $domain = defined('COOKIE_DOMAIN') ? COOKIE_DOMAIN : '';
            setcookie('aqlx_dark', $enabled ? '1' : '0', time() + $ttl, $path, $domain, function_exists('is_ssl') ? is_ssl() : false, true);
            return ['ok' => true, 'enabled' => $enabled];
        },
        'permission_callback' => '__return_true',
    ]);

    // Wishlist endpoints
    register_rest_route('aqualuxe/v1', '/wishlist', [
        'methods' => 'GET',
        'callback' => function () {
            $list = [];
            if (!empty($_COOKIE['aqlx_wl'])) {
                $ids = array_filter(array_map('absint', explode(',', $_COOKIE['aqlx_wl'])));
                $list = array_values(array_unique($ids));
            }
            return ['items' => $list];
        },
        'permission_callback' => '__return_true',
    ]);

    register_rest_route('aqualuxe/v1', '/wishlist', [
        'methods' => 'POST',
        'callback' => function ($req) {
            $id = (int) (is_object($req) && method_exists($req, 'get_param') ? $req->get_param('id') : 0);
            $act = (string) (is_object($req) && method_exists($req, 'get_param') ? $req->get_param('action') : 'toggle');
            $ids = [];
            if (!empty($_COOKIE['aqlx_wl'])) {
                $ids = array_filter(array_map('absint', explode(',', $_COOKIE['aqlx_wl'])));
            }
            if ($id > 0) {
                if ($act === 'remove') {
                    $ids = array_values(array_diff($ids, [$id]));
                } else {
                    $ids[] = $id;
                    $ids = array_values(array_unique($ids));
                }
            }
            $ttl = 31536000; // 1 year
            $path = defined('COOKIEPATH') && COOKIEPATH ? COOKIEPATH : '/';
            $domain = defined('COOKIE_DOMAIN') ? COOKIE_DOMAIN : '';
            setcookie('aqlx_wl', implode(',', $ids), time() + $ttl, $path, $domain, function_exists('is_ssl') ? is_ssl() : false, true);
            return ['ok' => true, 'items' => $ids];
        },
        'permission_callback' => '__return_true',
    ]);

    // WooCommerce quick view endpoint
    register_rest_route('aqualuxe/v1', '/quick-view', [
        'methods' => 'GET',
        'callback' => function ($req) {
            if (!class_exists('WooCommerce')) return new WP_REST_Response(['error' => 'woocommerce_inactive'], 400);
            $id = isset($_GET['id']) ? absint($_GET['id']) : 0;
            if (!$id) return new WP_REST_Response(['error' => 'invalid_id'], 400);
            $post = get_post($id);
            if (!$post || $post->post_type !== 'product') return new WP_REST_Response(['error' => 'not_found'], 404);
            setup_postdata($post);
            ob_start();
            echo '<div class="grid md:grid-cols-2 gap-6">';
            echo '<div class="">';
            woocommerce_show_product_sale_flash();
            woocommerce_show_product_images();
            echo '</div><div class="p-2">';
            woocommerce_template_single_title();
            woocommerce_template_single_price();
            woocommerce_template_single_excerpt();
            woocommerce_template_single_add_to_cart();
            echo '</div></div>';
            wp_reset_postdata();
            return ['html' => ob_get_clean()];
        },
        'permission_callback' => '__return_true',
    ]);

    // Referral code
    register_rest_route('aqualuxe/v1', '/referral', [
        'methods' => 'GET',
        'callback' => function () {
            $code = isset($_COOKIE['aqlx_ref']) ? sanitize_text_field(wp_unslash($_COOKIE['aqlx_ref'])) : '';
            return ['code' => $code];
        },
        'permission_callback' => '__return_true',
    ]);
});
