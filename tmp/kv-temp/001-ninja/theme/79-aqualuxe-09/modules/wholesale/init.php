<?php
declare(strict_types=1);

// Wholesale price display hook (placeholder)
add_filter('woocommerce_get_price_html', static function ($price, $product) {
    if (! is_user_logged_in() || ! current_user_can('read')) return $price;
    if (current_user_can('manage_woocommerce')) return $price;
    // Future: apply wholesale role-based pricing
    return $price;
}, 10, 2);
