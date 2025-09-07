<?php
/**
 * Theme Helpers
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
 * Class Theme_Helpers
 */
class Theme_Helpers {

	/**
	 * The manifest data.
	 *
	 * @var array|null
	 */
	private static ?array $manifest = null;

	/**
	 * Get asset path with cache busting.
	 *
	 * @param string $path The path to the asset.
	 * @return string The asset URL.
	 */
	public static function get_asset( string $path ): string {
		if ( is_null( self::$manifest ) ) {
			$manifest_path = AQUALUXE_THEME_DIR . 'assets/dist/mix-manifest.json';
			if ( file_exists( $manifest_path ) ) {
				self::$manifest = json_decode( file_get_contents( $manifest_path ), true );
			} else {
				self::$manifest = [];
			}
		}

		$path = '/' . ltrim( $path, '/' );

		if ( array_key_exists( $path, self::$manifest ) ) {
			return AQUALUXE_THEME_URI . 'assets/dist' . self::$manifest[ $path ];
		}

		return AQUALUXE_THEME_URI . 'assets/dist' . $path;
	}
}
