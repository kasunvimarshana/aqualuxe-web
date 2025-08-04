<?php

/**
 * AquaLuxe Child Theme Functions
 *
 * @package aqualuxe
 */

/**
 * Enqueue styles
 */
function aqualuxe_enqueue_styles()
{
    // Parent theme stylesheet
    wp_enqueue_style('storefront-style', get_template_directory_uri() . '/style.css');

    // Child theme stylesheet
    wp_enqueue_style(
        'aqualuxe-style',
        get_stylesheet_directory_uri() . '/style.css',
        array('storefront-style'),
        wp_get_theme()->get('Version')
    );
}
add_action('wp_enqueue_scripts', 'aqualuxe_enqueue_styles');

/**
 * Add WooCommerce support
 */
function aqualuxe_add_woocommerce_support()
{
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
}
add_action('after_setup_theme', 'aqualuxe_add_woocommerce_support');

/**
 * Remove default WooCommerce styles
 */
add_filter('woocommerce_enqueue_styles', '__return_empty_array');

/**
 * Custom product loop thumbnail size
 */
function aqualuxe_product_thumbnail_size()
{
    return 'medium';
}
add_filter('single_product_archive_thumbnail_size', 'aqualuxe_product_thumbnail_size');

/**
 * Add custom body classes
 */
function aqualuxe_body_classes($classes)
{
    if (is_woocommerce() || is_cart() || is_checkout()) {
        $classes[] = 'woocommerce-page';
    }
    return $classes;
}
add_filter('body_class', 'aqualuxe_body_classes');
