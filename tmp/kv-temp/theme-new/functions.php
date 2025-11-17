<?php

/**
 * AquaLuxe Child Theme Functions
 *
 * @package AquaLuxe
 */

defined('ABSPATH') || exit;

/**
 * Load child theme textdomain
 */
function aqualuxe_load_textdomain()
{
    load_child_theme_textdomain('aqualuxe', get_stylesheet_directory() . '/languages');
}
add_action('after_setup_theme', 'aqualuxe_load_textdomain');

/**
 * Enqueue styles and scripts
 */
function aqualuxe_enqueue_scripts()
{
    // Parent theme stylesheet
    wp_enqueue_style('storefront-style', get_template_directory_uri() . '/style.css');

    // Child theme stylesheet
    wp_enqueue_style('aqualuxe-style', get_stylesheet_directory_uri() . '/assets/css/main.css', array('storefront-style'), '1.0.0');

    // Responsive stylesheet
    wp_enqueue_style('aqualuxe-responsive', get_stylesheet_directory_uri() . '/assets/css/responsive.css', array('aqualuxe-style'), '1.0.0');

    // Custom script
    wp_enqueue_script('aqualuxe-script', get_stylesheet_directory_uri() . '/assets/js/main.js', array('jquery'), '1.0.0', true);

    // Localize script
    wp_localize_script('aqualuxe-script', 'aqualuxe_vars', array(
        'ajax_url' => admin_url('admin-ajax.php'),
    ));
}
add_action('wp_enqueue_scripts', 'aqualuxe_enqueue_scripts');

/**
 * Theme setup
 */
require_once get_stylesheet_directory() . '/inc/setup.php';

/**
 * Theme hooks
 */
require_once get_stylesheet_directory() . '/inc/hooks.php';

/**
 * WooCommerce functions
 */
require_once get_stylesheet_directory() . '/inc/woocommerce.php';
