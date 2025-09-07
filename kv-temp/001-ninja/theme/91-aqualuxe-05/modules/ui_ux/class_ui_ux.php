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
		// Enqueue Three.js, GSAP, and D3.js from node_modules
		\wp_enqueue_script( 'three-js', AQUALUXE_THEME_URI . 'node_modules/three/build/three.min.js', [], '0.144.0', true );
		\wp_enqueue_script( 'gsap-js', AQUALUXE_THEME_URI . 'node_modules/gsap/dist/gsap.min.js', [], '3.11.0', true );
		\wp_enqueue_script( 'd3-js', AQUALUXE_THEME_URI . 'node_modules/d3/dist/d3.min.js', [], '7.6.1', true );

		// Enqueue module-specific scripts
		\wp_enqueue_script( 'aqualuxe-ui-ux-script', AQUALUXE_THEME_URI . 'modules/ui_ux/js/ui-ux.js', [ 'jquery', 'three-js', 'gsap-js', 'd3-js' ], AQUALUXE_VERSION, true );
	}
}
