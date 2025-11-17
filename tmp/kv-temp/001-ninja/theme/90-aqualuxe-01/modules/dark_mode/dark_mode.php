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
 * Enqueue dark mode scripts.
 */
function aqualuxe_dark_mode_scripts() {
    wp_enqueue_script( 'aqualuxe-dark-mode', get_template_directory_uri() . '/modules/dark_mode/dark-mode.js', array( 'jquery' ), AQUALUXE_VERSION, true );
    wp_enqueue_style( 'aqualuxe-dark-mode', get_template_directory_uri() . '/modules/dark_mode/dark-mode.css', array(), AQUALUXE_VERSION );
}
add_action( 'wp_enqueue_scripts', 'aqualuxe_dark_mode_scripts' );

/**
 * Add dark mode toggle to the footer.
 */
function aqualuxe_add_dark_mode_toggle() {
    echo '<div class="dark-mode-toggle-container"><button id="dark-mode-toggle" class="dark-mode-toggle">Toggle Dark Mode</button></div>';
}
add_action( 'wp_footer', 'aqualuxe_add_dark_mode_toggle' );
