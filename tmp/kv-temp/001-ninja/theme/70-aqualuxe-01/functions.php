<?php
/**
 * AquaLuxe Theme Functions
 * 
 * @package AquaLuxe
 * @version 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define theme constants
define('AQUALUXE_VERSION', '1.0.0');
define('AQUALUXE_THEME_DIR', get_template_directory());
define('AQUALUXE_THEME_URL', get_template_directory_uri());
define('AQUALUXE_THEME_PATH', get_template_directory());
define('AQUALUXE_THEME_INC', AQUALUXE_THEME_DIR . '/inc');

/**
 * Theme Setup and Core Functionality
 */
require_once AQUALUXE_THEME_INC . '/class-aqualuxe-theme.php';
require_once AQUALUXE_THEME_INC . '/class-aqualuxe-assets.php';
require_once AQUALUXE_THEME_INC . '/class-aqualuxe-customizer.php';
require_once AQUALUXE_THEME_INC . '/class-aqualuxe-modules.php';

// Initialize theme
if (class_exists('AquaLuxe_Theme')) {
    new AquaLuxe_Theme();
}

// Initialize assets manager
if (class_exists('AquaLuxe_Assets')) {
    new AquaLuxe_Assets();
}

// Initialize customizer
if (class_exists('AquaLuxe_Customizer')) {
    new AquaLuxe_Customizer();
}

// Initialize modules system
if (class_exists('AquaLuxe_Modules')) {
    new AquaLuxe_Modules();
}

/**
 * Theme activation hook
 */
function aqualuxe_theme_activation() {
    // Flush rewrite rules
    flush_rewrite_rules();
    
    // Set default customizer options
    set_theme_mod('aqualuxe_enable_dark_mode', true);
    set_theme_mod('aqualuxe_enable_multilingual', true);
    set_theme_mod('aqualuxe_enable_woocommerce_integration', true);
}
add_action('after_switch_theme', 'aqualuxe_theme_activation');

/**
 * Theme deactivation hook
 */
function aqualuxe_theme_deactivation() {
    // Flush rewrite rules
    flush_rewrite_rules();
}
add_action('switch_theme', 'aqualuxe_theme_deactivation');
