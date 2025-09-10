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
 * Handles dark mode functionality.
 */
class AquaLuxe_Dark_Mode {

	/**
	 * Register hooks.
	 */
	public function register() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'wp_footer', array( $this, 'add_dark_mode_toggle' ) );
	}

	/**
	 * Enqueue scripts for dark mode.
	 */
	public function enqueue_scripts() {
		// You would create a JS file to handle the toggle and saving the preference.
		// wp_enqueue_script( 'aqualuxe-dark-mode', get_template_directory_uri() . '/modules/dark-mode/dark-mode.js', array(), '1.0.0', true );
	}

	/**
	 * Add dark mode toggle to the footer.
	 */
	public function add_dark_mode_toggle() {
		echo '<button id="dark-mode-toggle">Toggle Dark Mode</button>';
	}
}

$dark_mode = new AquaLuxe_Dark_Mode();
$dark_mode->register();
