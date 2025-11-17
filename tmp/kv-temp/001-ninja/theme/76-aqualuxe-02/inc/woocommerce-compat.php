<?php
defined('ABSPATH') || exit;

if (!class_exists('WooCommerce')) {
    // WooCommerce not active: provide stubs where used to avoid fatal errors.
    if (!function_exists('is_product')) {
        function is_product() { return false; }
    }
    if (!function_exists('is_shop')) {
        function is_shop() { return false; }
    }
    return;
}

// WooCommerce active: tweaks and optimizations
add_filter('woocommerce_enqueue_styles', function ($styles) {
    // Disable Woo default styles; theme provides its own.
    return [];
});

add_action('after_setup_theme', function () {
    add_theme_support('woocommerce');
});

// Mini cart fragment
add_filter('woocommerce_add_to_cart_fragments', function ($fragments) {
    ob_start();
    echo '<span class="aqlx-cart-count">' . WC()->cart->get_cart_contents_count() . '</span>';
    $fragments['span.aqlx-cart-count'] = ob_get_clean();
    return $fragments;
});
