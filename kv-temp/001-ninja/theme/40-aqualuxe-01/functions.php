<?php
/**
 * AquaLuxe functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package AquaLuxe
 */

// Define theme constants
define( 'AQUALUXE_VERSION', '1.0.0' );
define( 'AQUALUXE_DIR', trailingslashit( get_template_directory() ) );
define( 'AQUALUXE_URI', trailingslashit( get_template_directory_uri() ) );

/**
 * AquaLuxe setup and core functionality
 */
require_once AQUALUXE_DIR . 'inc/core/theme-setup.php';
require_once AQUALUXE_DIR . 'inc/core/assets.php';
require_once AQUALUXE_DIR . 'inc/core/template-functions.php';
require_once AQUALUXE_DIR . 'inc/core/template-tags.php';

/**
 * Helper functions
 */
require_once AQUALUXE_DIR . 'inc/helpers/conditional-tags.php';
require_once AQUALUXE_DIR . 'inc/helpers/markup.php';

/**
 * Customizer additions
 */
require_once AQUALUXE_DIR . 'inc/customizer/customizer.php';

/**
 * WooCommerce integration
 */
if ( aqualuxe_is_woocommerce_active() ) {
    require_once AQUALUXE_DIR . 'inc/woocommerce/woocommerce-setup.php';
    require_once AQUALUXE_DIR . 'inc/woocommerce/woocommerce-functions.php';
    require_once AQUALUXE_DIR . 'inc/woocommerce/woocommerce-hooks.php';
    require_once AQUALUXE_DIR . 'inc/woocommerce/woocommerce-ajax.php';
}

/**
 * Check if WooCommerce is active
 *
 * @return bool True if WooCommerce is active, false otherwise
 */
function aqualuxe_is_woocommerce_active() {
    return class_exists( 'WooCommerce' );
}

/**
 * Check if WPML is active for multilingual support
 *
 * @return bool True if WPML is active, false otherwise
 */
function aqualuxe_is_wpml_active() {
    return class_exists( 'SitePress' );
}

/**
 * Check if theme is in debug mode
 *
 * @return bool True if WP_DEBUG is enabled, false otherwise
 */
function aqualuxe_is_debug() {
    return defined( 'WP_DEBUG' ) && WP_DEBUG;
}