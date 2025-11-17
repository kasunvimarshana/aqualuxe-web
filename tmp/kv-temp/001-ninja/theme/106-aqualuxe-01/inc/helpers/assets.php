<?php
/**
 * Asset helper functions.
 *
 * @package AquaLuxe
 */

declare( strict_types=1 );

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Resolve built asset path from mix-manifest.json (cache-busted).
 * Falls back gracefully if manifest or file missing.
 */
function aqualuxe_asset( string $filename ): ?string {
	$manifest_path = AQUALUXE_PATH . 'assets/dist/mix-manifest.json';
	$base_uri      = AQUALUXE_URI . 'assets/dist';
	if ( ! file_exists( $manifest_path ) ) {
		return null; // Build not yet run.
	}
	$manifest = json_decode( (string) file_get_contents( $manifest_path ), true );
	if ( ! is_array( $manifest ) ) { return null; }
	$key = '/' . ltrim( $filename, '/' );
	if ( isset( $manifest[ $key ] ) ) {
		return $base_uri . $manifest[ $key ];
	}
	return null;
}
