<?php
if (!class_exists('WooCommerce')) return;
// Dequeue default WooCommerce styles to avoid duplication
add_filter('woocommerce_enqueue_styles', '__return_empty_array');
// Wrap product thumbnails with lazy loading
add_filter('wp_get_attachment_image_attributes', function ($attr, $attachment, $size) {
    if (is_product() || is_shop() || is_product_category()) {
        $attr['loading'] = $attr['loading'] ?? 'lazy';
        $attr['decoding'] = 'async';
    }
    return $attr;
}, 10, 3);
