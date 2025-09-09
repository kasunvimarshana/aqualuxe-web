<?php
/**
 * Asset loader.
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Handles asset loading.
 */
class AquaLuxe_Asset_Loader {

	/**
	 * Enqueue scripts and styles.
	 */
	public static function enqueue_assets() {
		$manifest_path = AQUALUXE_THEME_DIR . '/assets/dist/mix-manifest.json';
		$manifest      = file_exists( $manifest_path ) ? json_decode( file_get_contents( $manifest_path ), true ) : [];

		// Enqueue main stylesheet.
		$css_path = isset( $manifest['/css/app.css'] ) ? $manifest['/css/app.css'] : '/css/app.css';
		wp_enqueue_style( 'aqualuxe-style', AQUALUXE_THEME_URI . '/assets/dist' . $css_path, array(), self::get_version( $manifest_path ) );

		// Enqueue main javascript file.
		$js_path = isset( $manifest['/js/app.js'] ) ? $manifest['/js/app.js'] : '/js/app.js';
		wp_enqueue_script( 'aqualuxe-script', AQUALUXE_THEME_URI . '/assets/dist' . $js_path, array(), self::get_version( $manifest_path ), true );

		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}

	/**
	 * Get file version for cache busting.
	 *
	 * @param string $file File path.
	 * @return string File version.
	 */
	private static function get_version( $file ) {
		return file_exists( $file ) ? filemtime( $file ) : AQUALUXE_VERSION;
	}
}
