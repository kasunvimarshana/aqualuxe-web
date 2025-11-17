<?php
/** WooCommerce conditional integration */

if (!function_exists('aqualuxe_wc_active')) {
    function aqualuxe_wc_active() { return class_exists('WooCommerce'); }
}

// Enqueue WC only if active
add_action('wp', function(){
    if (!aqualuxe_wc_active()) {
        return;
    }
    // Example: remove default breadcrumbs and use theme's
    remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
});

// Quick view endpoint: if viewing single product with quickview param, render partial and exit
add_action('template_redirect', function(){
    if (!aqualuxe_wc_active()) return;
    if (!is_singular('product')) return;
    if (empty($_GET['quickview'])) return;
    status_header(200);
    nocache_headers();
    global $product, $post;
    if (!$product && function_exists('wc_get_product')) { $product = call_user_func('wc_get_product', get_the_ID()); }
    echo '<article class="grid gap-4 md:grid-cols-2">';
    // Images
    if (function_exists('do_action')) do_action('woocommerce_before_single_product_summary');
    echo '<div class="summary entry-summary">';
    if (function_exists('woocommerce_template_single_title')) { call_user_func('woocommerce_template_single_title'); }
    if (function_exists('woocommerce_template_single_rating')) { call_user_func('woocommerce_template_single_rating'); }
    if (function_exists('woocommerce_template_single_price')) { call_user_func('woocommerce_template_single_price'); }
    if (function_exists('woocommerce_template_single_excerpt')) { call_user_func('woocommerce_template_single_excerpt'); }
    if (function_exists('woocommerce_template_single_add_to_cart')) { call_user_func('woocommerce_template_single_add_to_cart'); }
    echo '</div>';
    echo '</article>';
    exit;
});
