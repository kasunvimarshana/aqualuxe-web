<?php
/**
 * Asset Service Provider
 *
 * @package AquaLuxe
 */

namespace App\Providers;

use App\Core\ServiceProvider;

class AssetServiceProvider extends ServiceProvider {
	public function register() {
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_assets' ] );
	}

	public function enqueue_assets() {
		$asset_file = AQUALUXE_DIR . '/assets/dist/mix-manifest.json';
		$assets     = file_exists( $asset_file ) ? json_decode( file_get_contents( $asset_file ), true ) : [];

		$css_path = isset( $assets['/css/app.css'] ) ? $assets['/css/app.css'] : '/css/app.css';
		$js_path  = isset( $assets['/js/app.js'] ) ? $assets['/js/app.js'] : '/js/app.js';

		wp_enqueue_style( 'aqualuxe-style', AQUALUXE_URL . '/assets/dist' . $css_path, [], AQUALUXE_VERSION );
		wp_enqueue_script( 'aqualuxe-script', AQUALUXE_URL . '/assets/dist' . $js_path, [ 'jquery' ], AQUALUXE_VERSION, true );

		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}
}
