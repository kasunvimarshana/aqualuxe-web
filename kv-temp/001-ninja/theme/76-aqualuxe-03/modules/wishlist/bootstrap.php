<?php
defined('ABSPATH') || exit;

// Add wishlist button in product loops and single product
if (class_exists('WooCommerce')) {
    $btn_cb = function () {
        global $product;
        if (!$product) return;
        echo '<button class="aqlx-wishlist-btn mt-2 text-sm" data-product-id="' . esc_attr($product->get_id()) . '" type="button">❤ ' . esc_html__('Wishlist', 'aqualuxe') . '</button>';
    };
    add_action('woocommerce_after_shop_loop_item', $btn_cb, 20);
    add_action('woocommerce_single_product_summary', $btn_cb, 45);
}

// Shortcode to render wishlist items
add_shortcode('aqlx_wishlist', function () {
    $ids = [];
    if (!empty($_COOKIE['aqlx_wl'])) {
        $ids = array_filter(array_map('absint', explode(',', $_COOKIE['aqlx_wl'])));
    }
    if (empty($ids)) return '<p>' . esc_html__('Your wishlist is empty.', 'aqualuxe') . '</p>';
    $q = new WP_Query([
        'post_type' => 'product',
        'post__in'  => $ids,
        'orderby'   => 'post__in',
        'posts_per_page' => -1,
    ]);
    ob_start();
    if ($q->have_posts()) {
        echo '<div class="grid md:grid-cols-3 gap-6">';
        while ($q->have_posts()) { $q->the_post(); wc_get_template_part('content', 'product'); }
        echo '</div>';
    }
    wp_reset_postdata();
    return ob_get_clean();
});
