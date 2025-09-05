<?php

declare(strict_types=1);

namespace AquaLuxe\Core\Modules\WooUX;

use AquaLuxe\Core\Helpers;

class Bootstrap
{
    public static function init(): void
    {
        if (! class_exists('WooCommerce')) {
            return;
        }

        // Frontend buttons on product cards.
        Helpers::wp('add_action', ['woocommerce_after_shop_loop_item', [self::class, 'buttons'], 20]);

        // REST endpoints for quick view and wishlist.
        Helpers::wp('add_action', ['rest_api_init', [self::class, 'routes']]);
    }

    public static function buttons(): void
    {
        global $product;
        if (! $product) { return; }
        $pid = (int) $product->get_id();
        echo '<div class="alx-wc-buttons mt-2 flex gap-2">';
        echo '<button type="button" class="alx-quickview button" data-product="' . esc_attr((string) $pid) . '">' . esc_html__('Quick view', 'aqualuxe') . '</button>';
        echo '<button type="button" class="alx-wishlist button" data-product="' . esc_attr((string) $pid) . '">' . esc_html__('Wishlist', 'aqualuxe') . '</button>';
        echo '</div>';
    }

    public static function routes(): void
    {
        Helpers::wp('register_rest_route', ['aqualuxe/v1', '/quickview', [
            'methods' => 'GET',
            'callback' => [self::class, 'quickview'],
            'permission_callback' => '__return_true',
        ]]);
        Helpers::wp('register_rest_route', ['aqualuxe/v1', '/wishlist', [
            'methods' => 'POST',
            'callback' => [self::class, 'wishlist'],
            'permission_callback' => '__return_true',
        ]]);
    }

    public static function quickview($request)
    {
        $id = (int) ($request['id'] ?? 0);
        if (! $id) { return ['ok' => false]; }
    if (! function_exists('wc_get_product')) { return ['ok' => false]; }
    /** @var \WC_Product|null $p */
    $p = function_exists('wc_get_product') ? \wc_get_product($id) : null;
        if (! $p) { return ['ok' => false]; }
        ob_start();
        echo '<div class="alx-quickview-modal p-4">';
    $name = \is_object($p) && \method_exists($p, 'get_name') ? (string) \call_user_func([$p, 'get_name']) : '';
    $price = \is_object($p) && \method_exists($p, 'get_price_html') ? (string) \call_user_func([$p, 'get_price_html']) : '';
    $permalink = (string) (Helpers::wp('get_permalink', [$id]) ?? '#');
    echo '<h3 class="text-lg font-semibold">' . esc_html($name) . '</h3>';
    echo '<div class="price">' . wp_kses_post($price) . '</div>';
    echo '<a class="button" href="' . esc_url($permalink) . '">' . esc_html__('View product', 'aqualuxe') . '</a>';
        echo '</div>';
        return ['ok' => true, 'html' => (string) ob_get_clean()];
    }

    public static function wishlist($request)
    {
        $id = (int) (($request->get_json_params()['id'] ?? 0));
        if (! $id) { return ['ok' => false]; }
        if (Helpers::wp('is_user_logged_in')) {
            $uid = (int) (Helpers::wp('get_current_user_id') ?? 0);
            $list = Helpers::wp('get_user_meta', [$uid, 'alx_wishlist', true]) ?? [];
            $list = is_array($list) ? $list : [];
            if (in_array($id, $list, true)) {
                $list = array_values(array_diff($list, [$id]));
                Helpers::wp('update_user_meta', [$uid, 'alx_wishlist', $list]);
                return ['ok' => true, 'action' => 'removed'];
            }
            $list[] = $id;
            Helpers::wp('update_user_meta', [$uid, 'alx_wishlist', array_values(array_unique($list))]);
            return ['ok' => true, 'action' => 'added'];
        }
        // Guest via cookie
        $cookie = $_COOKIE['alx_wishlist'] ?? '';
        $arr = array_filter(array_map('intval', explode(',', (string) $cookie)));
        if (in_array($id, $arr, true)) {
            $arr = array_values(array_diff($arr, [$id]));
            \setcookie('alx_wishlist', implode(',', $arr), time() + 31536000, '/', '', (bool) (Helpers::wp('is_ssl') ?? false), true);
            return ['ok' => true, 'action' => 'removed'];
        }
        $arr[] = $id;
        $arr = array_values(array_unique($arr));
        \setcookie('alx_wishlist', implode(',', $arr), time() + 31536000, '/', '', (bool) (Helpers::wp('is_ssl') ?? false), true);
        return ['ok' => true, 'action' => 'added'];
    }
}
