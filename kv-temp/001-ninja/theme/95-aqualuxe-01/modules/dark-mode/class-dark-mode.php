<?php
/**
 * Dark Mode Module.
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Handles dark mode functionality.
 */
class AquaLuxe_Dark_Mode {

	/**
	 * Initialize the module.
	 */
	public static function init() {
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_assets' ) );
		add_action( 'wp_footer', array( __CLASS__, 'add_toggle_button' ) );
	}

	/**
	 * Enqueue assets for the dark mode module.
	 */
	public static function enqueue_assets() {
		// The main app.js and app.css will handle this module's assets.
		// We just need to make sure they are included in the build process.
	}

	/**
	 * Add the dark mode toggle button to the footer.
	 */
	public static function add_toggle_button() {
		?>
		<button id="dark-mode-toggle" class="fixed bottom-4 right-4 p-2 rounded-full bg-gray-200 dark:bg-gray-800 text-gray-800 dark:text-gray-200">
			<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
				<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
			</svg>
		</button>
		<?php
	}
}

AquaLuxe_Dark_Mode::init();
