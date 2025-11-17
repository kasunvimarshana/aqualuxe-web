<?php
defined('ABSPATH') || exit;

// Simple role-based price multiplier for WooCommerce
// Configure via filter: aqlx_wholesale_multipliers => ['wholesale_customer' => 0.8, 'subscriber' => 0.9]

if (class_exists('WooCommerce')) {
    add_filter('woocommerce_product_get_price', 'aqlx_wholesale_price', 20, 2);
    add_filter('woocommerce_product_variation_get_price', 'aqlx_wholesale_price', 20, 2);
}

if (!function_exists('aqlx_wholesale_price')) {
    function aqlx_wholesale_price($price, $product)
    {
        if (!is_user_logged_in()) return $price;
        $user = wp_get_current_user();
        $map = apply_filters('aqlx_wholesale_multipliers', [
            'wholesale_customer' => 0.8,
            'shop_manager' => 0.9,
        ]);
        foreach ((array) $user->roles as $role) {
            if (isset($map[$role])) {
                $mult = (float) $map[$role];
                if ($mult > 0) {
                    return wc_get_price_to_display($product, ['price' => (float) $price * $mult]);
                }
            }
        }
        return $price;
    }
}
