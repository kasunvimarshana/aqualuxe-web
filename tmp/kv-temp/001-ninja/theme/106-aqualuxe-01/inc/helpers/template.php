<?php
/**
 * Template helpers.
 *
 * @package AquaLuxe
 */

declare( strict_types=1 );

if ( ! defined( 'ABSPATH' ) ) { exit; }

/** Get partial template with safe variables extraction. */
function aqualuxe_get_partial( string $slug, array $vars = [] ): void {
	$path = AQUALUXE_PATH . 'templates/partials/' . $slug . '.php';
	if ( file_exists( $path ) ) {
		extract( $vars, EXTR_SKIP ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract
		include $path; // phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingVariable
	}
}
