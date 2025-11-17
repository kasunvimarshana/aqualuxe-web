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

// The functionality of this file has been moved to core/class-theme-helpers.php
// This file will be removed in a future version.

if ( ! function_exists( 'aqualuxe_asset' ) ) {
	/**
	 * Get asset path with cache busting.
	 *
	 * @param string $path The path to the asset.
	 * @return string The asset URL.
	 * @deprecated 1.1.0 Use Theme_Helpers::get_asset() instead.
	 */
	function aqualuxe_asset( $path ) {
		return Theme_Helpers::get_asset( $path );
	}
}
