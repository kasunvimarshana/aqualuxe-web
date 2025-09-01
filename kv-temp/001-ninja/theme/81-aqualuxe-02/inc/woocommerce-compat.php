<?php
/** WooCommerce compatibility and graceful fallbacks */
if (!defined('ABSPATH')) { exit; }

add_action('after_setup_theme', function(){
    add_theme_support('woocommerce');
});

// Defer Woo hooks to plugins_loaded to ensure availability
add_action('plugins_loaded', function(){
    if (!class_exists('WooCommerce')) return;

    add_filter('woocommerce_enqueue_styles', '__return_empty_array'); // We'll style via theme CSS

    // Mini cart fragment hook example
    add_filter('woocommerce_add_to_cart_fragments', function($fragments){
        $count = 0;
        if (function_exists('WC')) {
            $wc = call_user_func('WC');
            if ($wc && isset($wc->cart)) {
                $count = (int) $wc->cart->get_cart_contents_count();
            }
        }
        $html = '<span class="ax-cart-count" aria-label="Cart items count">' . esc_html($count) . '</span>';
        $fragments['span.ax-cart-count'] = $html;
        return $fragments;
    });
});
