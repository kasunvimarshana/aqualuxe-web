<?php
/**
 * Theme scripts and styles.
 *
 * @package AquaLuxe
 * @since   1.1.0
 */

namespace AquaLuxe\Core;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Theme_Scripts
 */
class Theme_Scripts {

	/**
	 * Theme_Scripts constructor.
	 */
	public function __construct() {
		\add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_styles' ] );
		\add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
	}

	/**
	 * Enqueue styles.
	 */
	public function enqueue_styles(): void {
		// Get the asset manifest.
		$manifest_path = AQUALUXE_THEME_DIR . 'assets/dist/mix-manifest.json';
		$manifest      = \file_exists( $manifest_path ) ? \json_decode( \file_get_contents( $manifest_path ), true ) : [];

		// Enqueue main stylesheet.
		$css_path = isset( $manifest['/css/app.css'] ) ? $manifest['/css/app.css'] : '/css/app.css';
		\wp_enqueue_style( 'aqualuxe-main-style', AQUALUXE_THEME_URI . 'assets/dist' . $css_path, [], AQUALUXE_VERSION );

		// Enqueue custom css.
		$custom_css_path = isset( $manifest['/css/custom.css'] ) ? $manifest['/css/custom.css'] : '/css/custom.css';
		\wp_enqueue_style( 'aqualuxe-custom-style', AQUALUXE_THEME_URI . 'assets/dist' . $custom_css_path, ['aqualuxe-main-style'], AQUALUXE_VERSION );
	}

	/**
	 * Enqueue scripts.
	 */
	public function enqueue_scripts(): void {
		// Get the asset manifest.
		$manifest_path = AQUALUXE_THEME_DIR . 'assets/dist/mix-manifest.json';
		$manifest      = \file_exists( $manifest_path ) ? \json_decode( \file_get_contents( $manifest_path ), true ) : [];

		// Enqueue main javascript.
		$js_path = isset( $manifest['/js/main.js'] ) ? $manifest['/js/main.js'] : '/js/main.js';
		\wp_enqueue_script( 'aqualuxe-main-script', AQUALUXE_THEME_URI . 'assets/dist' . $js_path, [ 'jquery' ], AQUALUXE_VERSION, true );

		// Enqueue comment reply script.
		if ( \is_singular() && \comments_open() && \get_option( 'thread_comments' ) ) {
			\wp_enqueue_script( 'comment-reply' );
		}
	}
}
