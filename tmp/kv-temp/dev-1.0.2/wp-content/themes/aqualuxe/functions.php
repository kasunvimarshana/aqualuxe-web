<?php

/**
 * AquaLuxe Child Theme Functions
 *
 * @package aqualuxe
 */

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Define constants
 */
define('AQUALUXE_VERSION', '1.0.0');
define('AQUALUXE_THEME_DIR', get_stylesheet_directory());
define('AQUALUXE_THEME_URI', get_stylesheet_directory_uri());

/**
 * Include required files
 */
require_once AQUALUXE_THEME_DIR . '/inc/aqualuxe-functions.php';
require_once AQUALUXE_THEME_DIR . '/inc/aqualuxe-template-functions.php';
require_once AQUALUXE_THEME_DIR . '/inc/aqualuxe-template-hooks.php';

// WooCommerce specific functions
if (class_exists('WooCommerce')) {
    require_once AQUALUXE_THEME_DIR . '/inc/woocommerce/aqualuxe-woocommerce-functions.php';
    require_once AQUALUXE_THEME_DIR . '/inc/woocommerce/aqualuxe-woocommerce-hooks.php';
    require_once AQUALUXE_THEME_DIR . '/inc/woocommerce/class-aqualuxe-woocommerce.php';
}

// Customizer functions
require_once AQUALUXE_THEME_DIR . '/inc/customizer/aqualuxe-customizer-functions.php';
require_once AQUALUXE_THEME_DIR . '/inc/customizer/aqualuxe-customizer-options.php';
