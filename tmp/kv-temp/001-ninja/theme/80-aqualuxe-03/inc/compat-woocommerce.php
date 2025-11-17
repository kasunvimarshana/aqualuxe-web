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

// Lightweight product badges rendered in loop items
add_action('init', function(){
    if (!aqualuxe_wc_active()) return;

    $render_badges = function() {
        $ui = get_option('aqlx_ui', []);
        if (isset($ui['badges']) && !$ui['badges']) return; // disabled
        global $product; if (!$product) return;
        $badges = [];
        // Allow modules to contribute badges via filter
        $badges = apply_filters('aqualuxe/product_badges', $badges, $product);
        if (function_exists('aqualuxe_normalize_badges')) {
            $badges = aqualuxe_normalize_badges($badges);
        }

        if (!empty($badges)) {
            echo '<div class="aqlx-badges" aria-label="' . esc_attr__('Product badges','aqualuxe') . '">';
            foreach ($badges as $label) {
                echo '<span class="aqlx-badge">' . esc_html($label) . '</span>';
            }
            echo '</div>';
        }
    };

    // Before item title is a safe spot; allow customization via filters
    $hook = apply_filters('aqualuxe/product_badges_hook', 'woocommerce_before_shop_loop_item_title');
    $prio = (int) apply_filters('aqualuxe/product_badges_priority', 9);
    if (is_string($hook) && $hook) {
        add_action($hook, $render_badges, $prio);
    }
});

// Optional: under-title free shipping hint
add_action('aqualuxe/after_product_title', function(){
    $threshold = trim((string) get_theme_mod('aqualuxe_free_shipping_threshold', ''));
    if ($threshold === '') return;
    $amount = (float) $threshold;
    $formatted = function_exists('wc_price') ? call_user_func('wc_price', $amount) : ('$' . number_format($amount, 2));
    echo '<div class="aqlx-free-ship" aria-label="' . esc_attr__('Shipping hint','aqualuxe') . '">' . sprintf(esc_html__('Free shipping over %s','aqualuxe'), esc_html($formatted)) . '</div>';
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
