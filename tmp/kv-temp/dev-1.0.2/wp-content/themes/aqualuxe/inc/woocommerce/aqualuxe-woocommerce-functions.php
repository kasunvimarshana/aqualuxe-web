<?php

/**
 * AquaLuxe WooCommerce Functions
 *
 * @package aqualuxe
 */

if (! defined('ABSPATH')) {
    exit;
}

/**
 * Remove default WooCommerce styles.
 */
add_filter('woocommerce_enqueue_styles', '__return_empty_array');

/**
 * WooCommerce setup.
 */
function aqualuxe_woocommerce_setup()
{
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
}
add_action('after_setup_theme', 'aqualuxe_woocommerce_setup');

/**
 * WooCommerce specific scripts & stylesheets.
 */
function aqualuxe_woocommerce_scripts()
{
    wp_enqueue_style('aqualuxe-woocommerce-style', get_stylesheet_directory_uri() . '/assets/css/woocommerce.css');
    wp_enqueue_script('aqualuxe-woocommerce', get_stylesheet_directory_uri() . '/assets/js/woocommerce.js', array('jquery'), AQUALUXE_VERSION, true);
}
add_action('wp_enqueue_scripts', 'aqualuxe_woocommerce_scripts');

/**
 * Add 'woocommerce-active' class to the body tag.
 */
function aqualuxe_woocommerce_active_body_class($classes)
{
    $classes[] = 'woocommerce-active';
    return $classes;
}
add_filter('body_class', 'aqualuxe_woocommerce_active_body_class');

/**
 * Products per page.
 */
function aqualuxe_products_per_page($products_per_page)
{
    return 12;
}
add_filter('loop_shop_per_page', 'aqualuxe_products_per_page');

/**
 * Product columns.
 */
function aqualuxe_loop_columns()
{
    return 4;
}
add_filter('loop_shop_columns', 'aqualuxe_loop_columns');

/**
 * Related products args.
 */
function aqualuxe_related_products_args($args)
{
    $args['posts_per_page'] = 4;
    $args['columns'] = 4;
    return $args;
}
add_filter('woocommerce_output_related_products_args', 'aqualuxe_related_products_args');

/**
 * Product gallery thumnbail columns.
 */
function aqualuxe_product_thumbnail_columns()
{
    return 4;
}
add_filter('woocommerce_product_thumbnails_columns', 'aqualuxe_product_thumbnail_columns');

/**
 * Default loop columns on product archives.
 */
function aqualuxe_loop_columns_default()
{
    return 4;
}
add_filter('loop_shop_columns', 'aqualuxe_loop_columns_default');
