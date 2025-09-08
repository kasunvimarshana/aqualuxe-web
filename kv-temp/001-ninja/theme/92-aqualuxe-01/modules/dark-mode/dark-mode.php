<?php
/**
 * Module: Dark Mode
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * AquaLuxe_Dark_Mode class.
 */
class AquaLuxe_Dark_Mode {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'wp_footer', array( $this, 'add_dark_mode_toggle' ) );
	}

	/**
	 * Enqueue scripts and styles for dark mode.
	 */
	public function enqueue_scripts() {
		wp_enqueue_style( 'aqualuxe-dark-mode', get_template_directory_uri() . '/modules/dark-mode/dark-mode.css', array(), AQUALUXE_VERSION );
		wp_enqueue_script( 'aqualuxe-dark-mode', get_template_directory_uri() . '/modules/dark-mode/dark-mode.js', array( 'jquery' ), AQUALUXE_VERSION, true );
	}

	/**
	 * Add dark mode toggle button to the footer.
	 */
	public function add_dark_mode_toggle() {
		echo '<button id="dark-mode-toggle" class="dark-mode-toggle">Toggle Dark Mode</button>';
	}
}

new AquaLuxe_Dark_Mode();
