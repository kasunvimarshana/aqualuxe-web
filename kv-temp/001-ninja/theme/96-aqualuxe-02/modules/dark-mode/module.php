<?php
/**
 * Module: Dark Mode
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * AquaLuxe_Module_Dark_Mode class.
 */
class AquaLuxe_Module_Dark_Mode {

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		add_action( 'wp_footer', [ $this, 'add_dark_mode_toggle' ] );
	}

	/**
	 * Enqueue scripts.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( 'aqualuxe-dark-mode', get_template_directory_uri() . '/modules/dark-mode/assets/js/dark-mode.js', [], AQUALUXE_VERSION, true );
		wp_enqueue_style( 'aqualuxe-dark-mode', get_template_directory_uri() . '/modules/dark-mode/assets/css/dark-mode.css', [], AQUALUXE_VERSION );
	}

	/**
	 * Add dark mode toggle.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function add_dark_mode_toggle() {
		?>
		<div class="dark-mode-toggle">
			<input type="checkbox" id="dark-mode-switch" name="dark-mode-switch">
			<label for="dark-mode-switch"></label>
		</div>
		<?php
	}
}
