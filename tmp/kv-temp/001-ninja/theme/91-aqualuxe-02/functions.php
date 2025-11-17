<?php
/**
 * AquaLuxe Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define theme constants.
define( 'AQUALUXE_VERSION', '1.0.0' );
define( 'AQUALUXE_THEME_DIR', trailingslashit( get_template_directory() ) );
define( 'AQUALUXE_THEME_URI', trailingslashit( esc_url( get_template_directory_uri() ) ) );

// Core theme setup.
require_once AQUALUXE_THEME_DIR . 'core/init.php';

/**
 * Theme activation hooks.
 */
function aqualuxe_theme_activation() {
    // Add wholesale roles and capabilities.
    aqualuxe_add_wholesale_roles();

    // Flush rewrite rules to register CPTs and taxonomies.
    flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'aqualuxe_theme_activation' );

// Load modules.
require_once AQUALUXE_THEME_DIR . 'modules/loader.php';
