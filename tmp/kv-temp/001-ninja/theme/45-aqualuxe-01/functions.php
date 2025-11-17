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
define('AQUALUXE_DIR', trailingslashit(get_template_directory()));
define('AQUALUXE_URI', trailingslashit(get_template_directory_uri()));
define('AQUALUXE_ASSETS_URI', trailingslashit(AQUALUXE_URI . 'assets/dist'));

// Prevent direct access to this file
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Include required files
 */
$aqualuxe_includes = array(
    'inc/setup.php',               // Theme setup and support
    'inc/assets.php',              // Asset management
    'inc/template-functions.php',  // Template functions
    'inc/template-tags.php',       // Template tags
    'inc/customizer.php',          // Customizer settings
    'inc/hooks.php',               // Theme hooks
    'inc/helpers.php',             // Helper functions
    'inc/widgets.php',             // Widget areas
    'inc/nav-menus.php',           // Navigation menus
    'inc/custom-post-types.php',   // Custom post types
    'inc/custom-taxonomies.php',   // Custom taxonomies
    'inc/ajax-handlers.php',       // AJAX handlers
    'inc/multilingual.php',        // Multilingual support
    'inc/multi-currency.php',      // Multi-currency support
);

// Include WooCommerce compatibility file if WooCommerce is active
if (class_exists('WooCommerce')) {
    $aqualuxe_includes[] = 'inc/woocommerce.php';
}

// Load required files
foreach ($aqualuxe_includes as $file) {
    if (file_exists(AQUALUXE_DIR . $file)) {
        require_once AQUALUXE_DIR . $file;
    }
}

/**
 * Check if WooCommerce is active
 *
 * @return bool True if WooCommerce is active, false otherwise
 */
function aqualuxe_is_woocommerce_active() {
    return class_exists('WooCommerce');
}

/**
 * Check if WPML is active
 *
 * @return bool True if WPML is active, false otherwise
 */
function aqualuxe_is_wpml_active() {
    return class_exists('SitePress');
}

/**
 * Check if Polylang is active
 *
 * @return bool True if Polylang is active, false otherwise
 */
function aqualuxe_is_polylang_active() {
    return function_exists('pll_current_language');
}

/**
 * Check if any multilingual plugin is active
 *
 * @return bool True if any multilingual plugin is active, false otherwise
 */
function aqualuxe_is_multilingual_active() {
    return aqualuxe_is_wpml_active() || aqualuxe_is_polylang_active();
}