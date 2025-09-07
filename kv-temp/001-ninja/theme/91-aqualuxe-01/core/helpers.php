<?php
/**
 * Helper functions.
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get asset path with cache busting.
 *
 * @param string $path The path to the asset.
 * @return string The asset URL.
 */
function aqualuxe_asset( $path ) {
    static $manifest;

    if ( ! $manifest ) {
        $manifest_path = AQUALUXE_THEME_DIR . 'assets/dist/mix-manifest.json';
        if ( file_exists( $manifest_path ) ) {
            $manifest = json_decode( file_get_contents( $manifest_path ), true );
        } else {
            $manifest = [];
        }
    }

    $path = '/' . ltrim( $path, '/' );

    if ( array_key_exists( $path, $manifest ) ) {
        return AQUALUXE_THEME_URI . 'assets/dist' . $manifest[ $path ];
    }

    return AQUALUXE_THEME_URI . 'assets/dist' . $path;
}
