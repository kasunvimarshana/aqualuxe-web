<?php
/**
 * Theme supports
 *
 * @package AquaLuxe
 */

/**
 * Register WooCommerce support
 */
function aqualuxe_woocommerce_support() {
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
}
add_action('after_setup_theme', 'aqualuxe_woocommerce_support');

/**
 * Add support for Gutenberg features
 */
function aqualuxe_gutenberg_support() {
    // Add support for editor color palette.
    add_theme_support('editor-color-palette', array(
        array(
            'name'  => esc_html__('Primary', 'aqualuxe'),
            'slug'  => 'primary',
            'color' => '#0073aa',
        ),
        array(
            'name'  => esc_html__('Secondary', 'aqualuxe'),
            'slug'  => 'secondary',
            'color' => '#005177',
        ),
        array(
            'name'  => esc_html__('Dark Blue', 'aqualuxe'),
            'slug'  => 'dark-blue',
            'color' => '#1e3a8a',
        ),
        array(
            'name'  => esc_html__('Light Blue', 'aqualuxe'),
            'slug'  => 'light-blue',
            'color' => '#bfdbfe',
        ),
        array(
            'name'  => esc_html__('Teal', 'aqualuxe'),
            'slug'  => 'teal',
            'color' => '#14b8a6',
        ),
        array(
            'name'  => esc_html__('Dark', 'aqualuxe'),
            'slug'  => 'dark',
            'color' => '#111827',
        ),
        array(
            'name'  => esc_html__('Light', 'aqualuxe'),
            'slug'  => 'light',
            'color' => '#f9fafb',
        ),
        array(
            'name'  => esc_html__('White', 'aqualuxe'),
            'slug'  => 'white',
            'color' => '#ffffff',
        ),
    ));

    // Add support for editor font sizes.
    add_theme_support('editor-font-sizes', array(
        array(
            'name'      => esc_html__('Small', 'aqualuxe'),
            'shortName' => esc_html__('S', 'aqualuxe'),
            'size'      => 14,
            'slug'      => 'small',
        ),
        array(
            'name'      => esc_html__('Normal', 'aqualuxe'),
            'shortName' => esc_html__('M', 'aqualuxe'),
            'size'      => 16,
            'slug'      => 'normal',
        ),
        array(
            'name'      => esc_html__('Large', 'aqualuxe'),
            'shortName' => esc_html__('L', 'aqualuxe'),
            'size'      => 20,
            'slug'      => 'large',
        ),
        array(
            'name'      => esc_html__('Huge', 'aqualuxe'),
            'shortName' => esc_html__('XL', 'aqualuxe'),
            'size'      => 24,
            'slug'      => 'huge',
        ),
    ));
}
add_action('after_setup_theme', 'aqualuxe_gutenberg_support');