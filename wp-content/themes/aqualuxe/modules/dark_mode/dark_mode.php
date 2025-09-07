<?php
/**
 * Dark Mode Module
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue dark mode assets.
 */
function aqualuxe_dark_mode_assets() {
    // You would use the cache-busted version in a real scenario
    wp_enqueue_style( 'aqualuxe-dark-mode', AQUALUXE_THEME_URI . 'modules/dark_mode/assets/css/dark-mode.css', array(), AQUALUXE_VERSION );
    wp_enqueue_script( 'aqualuxe-dark-mode', AQUALUXE_THEME_URI . 'modules/dark_mode/assets/js/dark-mode.js', array('jquery'), AQUALUXE_VERSION, true );
}
add_action( 'wp_enqueue_scripts', 'aqualuxe_dark_mode_assets' );

/**
 * Add dark mode toggle to the footer.
 */
function aqualuxe_add_dark_mode_toggle() {
    echo '<button id="dark-mode-toggle" class="dark-mode-toggle">Toggle Dark Mode</button>';
}
add_action( 'wp_footer', 'aqualuxe_add_dark_mode_toggle' );
