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
define('AQUALUXE_INC_DIR', trailingslashit(AQUALUXE_DIR . 'inc'));
define('AQUALUXE_TEMPLATES_DIR', trailingslashit(AQUALUXE_DIR . 'templates'));

/**
 * Load the theme's required files
 */
$aqualuxe_includes = array(
    'inc/setup.php',               // Theme setup and support
    'inc/helpers.php',             // Helper functions
    'inc/template-functions.php',  // Functions for templates
    'inc/template-tags.php',       // Custom template tags
    'inc/hooks.php',               // Theme hooks
    'inc/enqueue.php',             // Enqueue scripts and styles
    'inc/customizer.php',          // Customizer functionality
    'inc/widgets.php',             // Widget areas
    'inc/multilingual.php',        // Multilingual support
    'inc/multivendor.php',         // Multivendor functionality
    'inc/multitenant.php',         // Multitenant architecture
);

// Load WooCommerce compatibility file if WooCommerce is active
if (class_exists('WooCommerce')) {
    $aqualuxe_includes[] = 'inc/woocommerce.php';
}

// Include files
foreach ($aqualuxe_includes as $file) {
    if (file_exists(AQUALUXE_DIR . $file)) {
        require_once AQUALUXE_DIR . $file;
    }
}

/**
 * Check if WooCommerce is active
 *
 * @return bool
 */
function aqualuxe_is_woocommerce_active() {
    return class_exists('WooCommerce');
}

/**
 * Get asset path with version
 *
 * @param string $path Path to asset
 * @return string Versioned path
 */
function aqualuxe_asset_path($path) {
    // Check if mix-manifest.json exists
    $manifest_path = get_template_directory() . '/assets/dist/mix-manifest.json';
    
    if (file_exists($manifest_path)) {
        $manifest = json_decode(file_get_contents($manifest_path), true);
        $path_key = '/' . $path;
        
        if (isset($manifest[$path_key])) {
            return AQUALUXE_ASSETS_URI . ltrim($manifest[$path_key], '/');
        }
    }
    
    // Fallback to regular path with theme version
    return AQUALUXE_ASSETS_URI . $path . '?ver=' . AQUALUXE_VERSION;
}