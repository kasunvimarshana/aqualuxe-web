<?php
/**
 * Dark Mode Module.
 *
 * @package AquaLuxe
 * @since   1.1.0
 */

namespace AquaLuxe\Modules\Dark_Mode;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Dark_Mode
 */
class Dark_Mode {

	/**
	 * Dark_Mode constructor.
	 */
	public function __construct() {
		\add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		\add_action( 'wp_footer', [ $this, 'add_dark_mode_toggle' ] );
	}

	/**
	 * Enqueue scripts and styles.
	 */
	public function enqueue_scripts(): void {
		\wp_enqueue_style( 'aqualuxe-dark-mode-style', AQUALUXE_THEME_URI . 'modules/dark_mode/css/dark-mode.css', [], AQUALUXE_VERSION );
		\wp_enqueue_script( 'aqualuxe-dark-mode-script', AQUALUXE_THEME_URI . 'modules/dark_mode/js/dark-mode.js', [ 'jquery' ], AQUALUXE_VERSION, true );
		\wp_localize_script(
			'aqualuxe-dark-mode-script',
			'darkMode',
			[
				'nonce' => \wp_create_nonce( 'dark_mode_nonce' ),
			]
		);
	}

	/**
	 * Add dark mode toggle to the footer.
	 */
	public function add_dark_mode_toggle(): void {
		echo '<div class="dark-mode-toggle-container"><button id="dark-mode-toggle" class="dark-mode-toggle">Toggle Dark Mode</button></div>';
	}
}
