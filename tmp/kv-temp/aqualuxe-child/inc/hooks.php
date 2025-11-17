<?php

/**
 * Theme hooks
 */

// Example: Modify WooCommerce product loop title
add_filter('woocommerce_product_title', function ($title) {
    return '<span class="aqualuxe-title">' . $title . '</span>';
});
