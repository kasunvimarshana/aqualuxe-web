<?php
namespace AquaLuxe\Core;

/**
 * Handles asset building and enqueueing via mix-manifest.json (cache-busted).
 */
final class Assets {
	public static function init(): void {
		\add_action( 'wp_enqueue_scripts', [ __CLASS__, 'enqueue_front' ], 20 );
		\add_action( 'admin_enqueue_scripts', [ __CLASS__, 'enqueue_admin' ], 20 );
	}

	private static function mix( string $path ): string {
		$manifest_path = AQUALUXE_PATH . 'assets/dist/mix-manifest.json';
		$uri_base      = AQUALUXE_URI . 'assets/dist';
		if ( file_exists( $manifest_path ) ) {
			$manifest = json_decode( (string) file_get_contents( $manifest_path ), true );
			if ( isset( $manifest[ $path ] ) ) {
				return $uri_base . $manifest[ $path ];
			}
		}
		return $uri_base . $path;
	}

	public static function enqueue_front(): void {
		// Styles
		\wp_enqueue_style( 'aqualuxe-app', self::mix( '/css/app.css' ), [], AQUALUXE_VERSION, 'all' );
		// Scripts
		\wp_enqueue_script( 'aqualuxe-app', self::mix( '/js/app.js' ), [], AQUALUXE_VERSION, true );

		// Pass data.
		\wp_add_inline_script( 'aqualuxe-app', 'window.AquaLuxe = ' . \wp_json_encode( [
			'ajaxUrl'   => \admin_url( 'admin-ajax.php' ),
			'nonce'     => \wp_create_nonce( 'aqualuxe_nonce' ),
			'isRTL'     => \is_rtl(),
			'texts'     => [ 'openMenu' => \__( 'Open menu', 'aqualuxe' ), 'closeMenu' => \__( 'Close menu', 'aqualuxe' ) ],
		] ) . ';', 'before' );
	}

	public static function enqueue_admin(): void {
		\wp_enqueue_style( 'aqualuxe-admin', self::mix( '/css/admin.css' ), [], AQUALUXE_VERSION );
		\wp_enqueue_script( 'aqualuxe-admin', self::mix( '/js/admin.js' ), [ 'jquery' ], AQUALUXE_VERSION, true );
	}
}
