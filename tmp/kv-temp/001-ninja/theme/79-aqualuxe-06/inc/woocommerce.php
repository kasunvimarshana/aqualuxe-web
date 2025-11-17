<?php
declare(strict_types=1);

namespace Aqualuxe\Inc;

// Declare WooCommerce support
add_action('after_setup_theme', static function (): void {
    add_theme_support('woocommerce');
    if (class_exists('WooCommerce')) {
        add_theme_support('wc-product-gallery-zoom');
        add_theme_support('wc-product-gallery-lightbox');
        add_theme_support('wc-product-gallery-slider');
    }
});

// Mini cart fragment example
add_filter('woocommerce_add_to_cart_fragments', static function ($fragments) {
    if (! function_exists('\\wc_get_cart_url')) {
        return $fragments;
    }
    ob_start();
    $count = 0;
    if (isset($GLOBALS['woocommerce']) && is_object($GLOBALS['woocommerce']) && isset($GLOBALS['woocommerce']->cart)) {
        $count = (int) $GLOBALS['woocommerce']->cart->get_cart_contents_count();
    }
    echo '<a class="mini-cart" href="' . esc_url(\wc_get_cart_url()) . '"><span class="count">' . esc_html((string) $count) . '</span></a>';
    $fragments['a.mini-cart'] = ob_get_clean();
    return $fragments;
});
