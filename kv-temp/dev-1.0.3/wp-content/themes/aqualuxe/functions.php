<?php

/**
 * AquaLuxe Theme Functions
 *
 * @package AquaLuxe
 */

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Require classes
require get_template_directory() . '/inc/class-theme-setup.php';
require get_template_directory() . '/inc/class-enqueue.php';
require get_template_directory() . '/inc/class-customizer.php';
require get_template_directory() . '/inc/class-breadcrumbs.php';
require get_template_directory() . '/inc/helpers.php';
require get_template_directory() . '/inc/class-aqualuxe-woocommerce.php'; // WooCommerce Integration
require get_template_directory() . '/inc/class-aqualuxe-ajax-cart.php';
require get_template_directory() . '/inc/class-aqualuxe-quick-view.php';
require get_template_directory() . '/inc/class-aqualuxe-mega-menu.php';
require get_template_directory() . '/inc/class-aqualuxe-schema.php';


// Initialize
new AquaLuxe_Theme_Setup();
new AquaLuxe_Enqueue();
new AquaLuxe_Customizer();
new AquaLuxe_Ajax_Cart();
new AquaLuxe_Quick_View();
new AquaLuxe_Mega_Menu();
new AquaLuxe_Schema();

/**
 * Theme Setup
 */
function aqualuxe_setup()
{
    // Make theme available for translation
    load_theme_textdomain('aqualuxe', get_template_directory() . '/languages');

    // Add support for various theme features
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('automatic-feed-links');
    add_theme_support('custom-logo', [
        'height'      => 60,
        'width'       => 200,
        'flex-width'  => true,
        'flex-height' => true,
    ]);
    add_theme_support('customize-selective-refresh-widgets');
    add_theme_support('woocommerce');

    // Register navigation menus
    register_nav_menus([
        'primary'   => __('Primary Menu', 'aqualuxe'),
        'footer'    => __('Footer Menu', 'aqualuxe'),
    ]);
}
add_action('after_setup_theme', 'aqualuxe_setup');

/**
 * Enqueue Scripts and Styles
 */
function aqualuxe_enqueue_assets()
{
    $theme_version = wp_get_theme()->get('Version');

    // Styles
    wp_enqueue_style('aqualuxe-style', get_stylesheet_uri(), [], $theme_version);
    wp_enqueue_style('aqualuxe-main', get_template_directory_uri() . '/assets/css/main.css', [], $theme_version);
    wp_enqueue_style('aqualuxe-responsive', get_template_directory_uri() . '/assets/css/responsive.css', [], $theme_version);

    // WooCommerce styles (if active)
    if (class_exists('WooCommerce')) {
        wp_enqueue_style('aqualuxe-woocommerce', get_template_directory_uri() . '/assets/css/woocommerce.css', [], $theme_version);
    }

    // Scripts
    wp_enqueue_script('aqualuxe-theme', get_template_directory_uri() . '/assets/js/theme.js', ['jquery'], $theme_version, true);

    if (class_exists('WooCommerce')) {
        wp_enqueue_script('aqualuxe-ajax-cart', get_template_directory_uri() . '/assets/js/ajax-cart.js', ['jquery'], $theme_version, true);
        wp_enqueue_script('aqualuxe-quick-view', get_template_directory_uri() . '/assets/js/quick-view.js', ['jquery'], $theme_version, true);

        wp_localize_script('aqualuxe-quick-view', 'aqualuxe_quickview', [
            'ajax_url' => admin_url('admin-ajax.php')
        ]);
    }
}
add_action('wp_enqueue_scripts', 'aqualuxe_enqueue_assets');


/**
 * Set Content Width
 */
if (! isset($content_width)) {
    $content_width = 1200;
}
