<?php
/**
 * UI/UX Module.
 *
 * @package AquaLuxe
 * @since   1.1.0
 */

namespace AquaLuxe\Modules\Ui_Ux;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Ui_Ux
 */
class Ui_Ux {

	/**
	 * Ui_Ux constructor.
	 */
	public function __construct() {
		\add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
	}

	/**
	 * Enqueue scripts.
	 */
	public function enqueue_scripts(): void {
		// Get the asset manifest for cache busting
		$manifest_path = AQUALUXE_THEME_DIR . 'assets/dist/mix-manifest.json';
		$manifest      = \file_exists( $manifest_path ) ? \json_decode( \file_get_contents( $manifest_path ), true ) : [];

		// Use compiled assets from dist directory with cache busting
		$js_path = isset( $manifest['/js/ui-ux.js'] ) ? $manifest['/js/ui-ux.js'] : '/js/ui-ux.js';
		\wp_enqueue_script( 'aqualuxe-ui-ux-script', AQUALUXE_THEME_URI . 'assets/dist' . $js_path, [ 'jquery' ], AQUALUXE_VERSION, true );
	}
}
