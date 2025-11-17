<?php

/**
 * WooCommerce functions
 *
 * @package AquaLuxe
 */

defined('ABSPATH') || exit;

/**
 * WooCommerce setup
 */
function aqualuxe_woocommerce_setup()
{
    // Declare WooCommerce support
    add_theme_support('woocommerce');

    // Add product gallery support
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');

    // Remove default WooCommerce styles
    add_filter('woocommerce_enqueue_styles', '__return_empty_array');
}
add_action('after_setup_theme', 'aqualuxe_woocommerce_setup');

/**
 * Products per page
 */
function aqualuxe_products_per_page($products_per_page)
{
    return 12;
}
add_filter('loop_shop_per_page', 'aqualuxe_products_per_page');

/**
 * Product columns
 */
function aqualuxe_loop_columns()
{
    return 4;
}
add_filter('loop_shop_columns', 'aqualuxe_loop_columns');

/**
 * Related products
 */
function aqualuxe_related_products_args($args)
{
    $args['posts_per_page'] = 4;
    $args['columns'] = 4;
    return $args;
}
add_filter('woocommerce_output_related_products_args', 'aqualuxe_related_products_args');

/**
 * Product thumbnails
 */
function aqualuxe_woocommerce_template_loop_product_thumbnail()
{
    echo woocommerce_get_product_thumbnail();
}
remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
add_action('woocommerce_before_shop_loop_item_title', 'aqualuxe_woocommerce_template_loop_product_thumbnail', 10);

/**
 * Product title
 */
function aqualuxe_woocommerce_template_loop_product_title()
{
    echo '<h3 class="woocommerce-loop-product__title">' . get_the_title() . '</h3>';
}
remove_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10);
add_action('woocommerce_shop_loop_item_title', 'aqualuxe_woocommerce_template_loop_product_title', 10);

/**
 * AJAX product filter
 */
function aqualuxe_filter_products()
{
    ob_start();

    $filters = $_POST['filters'];

    $args = array(
        'post_type' => 'product',
        'posts_per_page' => 12,
        'tax_query' => array(
            array(
                'taxonomy' => 'product_cat',
                'field'    => 'term_id',
                'terms'    => $filters,
            ),
        ),
    );

    $loop = new WP_Query($args);

    if ($loop->have_posts()) {
        woocommerce_product_loop_start();

        while ($loop->have_posts()) : $loop->the_post();
            wc_get_template_part('content', 'product');
        endwhile;

        woocommerce_product_loop_end();
    } else {
        echo '<p>' . __('No products found', 'aqualuxe') . '</p>';
    }

    wp_reset_postdata();

    $response = ob_get_contents();
    ob_end_clean();

    echo $response;

    wp_die();
}
add_action('wp_ajax_aqualuxe_filter_products', 'aqualuxe_filter_products');
add_action('wp_ajax_nopriv_aqualuxe_filter_products', 'aqualuxe_filter_products');
