<?php
defined('ABSPATH') || exit;

// WooCommerce Subscriptions compatibility: add subtle badge on subscription products
add_action('init', function () {
    if (!class_exists('WooCommerce') || !class_exists('WC_Subscriptions_Product')) return;

    $render_badge = function () {
        global $product;
        if (!is_object($product)) return;
        if (method_exists('WC_Subscriptions_Product', 'is_subscription') && WC_Subscriptions_Product::is_subscription($product)) {
            echo '<span class="inline-flex items-center text-xs font-medium text-emerald-700 bg-emerald-100 rounded px-2 py-0.5 ml-2">' . esc_html__('Subscription', 'aqualuxe') . '</span>';
        }
    };

    add_action('woocommerce_after_shop_loop_item_title', $render_badge, 15);
});
