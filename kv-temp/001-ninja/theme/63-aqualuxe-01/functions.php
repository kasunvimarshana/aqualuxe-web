<?php
/**
 * AquaLuxe functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package AquaLuxe
 */

// Define theme constants
define('AQUALUXE_VERSION', '1.0.0');
define('AQUALUXE_DIR', get_template_directory());
define('AQUALUXE_URI', get_template_directory_uri());
define('AQUALUXE_ASSETS_URI', AQUALUXE_URI . '/assets/dist');

// Prevent direct access to this file
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Core theme setup and initialization
 */
require_once AQUALUXE_DIR . '/inc/core/setup.php';
require_once AQUALUXE_DIR . '/inc/core/assets.php';
require_once AQUALUXE_DIR . '/inc/core/template-functions.php';
require_once AQUALUXE_DIR . '/inc/core/template-hooks.php';
require_once AQUALUXE_DIR . '/inc/core/customizer.php';
require_once AQUALUXE_DIR . '/inc/core/helpers.php';
require_once AQUALUXE_DIR . '/inc/core/widgets.php';
require_once AQUALUXE_DIR . '/inc/core/nav-menus.php';

/**
 * Module loader - handles loading and configuration of theme modules
 */
require_once AQUALUXE_DIR . '/inc/core/module-loader.php';

/**
 * WooCommerce integration - only loaded if WooCommerce is active
 */
if (class_exists('WooCommerce')) {
    require_once AQUALUXE_DIR . '/inc/woocommerce/woocommerce-setup.php';
    require_once AQUALUXE_DIR . '/inc/woocommerce/woocommerce-functions.php';
    require_once AQUALUXE_DIR . '/inc/woocommerce/woocommerce-hooks.php';
    require_once AQUALUXE_DIR . '/inc/woocommerce/woocommerce-template-functions.php';
}

/**
 * Demo content importer
 */
require_once AQUALUXE_DIR . '/inc/core/demo-importer.php';

/**
 * Load theme modules based on configuration
 */
add_action('after_setup_theme', 'aqualuxe_load_modules', 20);