<?php
/**
 * Assets loader using mix-manifest.json
 *
 * @package AquaLuxe\Core
 */

namespace AquaLuxe\Core;

if ( ! defined( 'ABSPATH' ) ) { exit; }

\add_action( 'wp_enqueue_scripts', function () {
	$style  = asset_uri( '/css/app.css' );
	$script = asset_uri( '/js/app.js' );

	\wp_enqueue_style( 'aqualuxe-app', $style, [], null, 'all' );

	\wp_register_script( 'aqualuxe-app', $script, [], null, true );
	\wp_add_inline_script( 'aqualuxe-app', 'window.__AQUALUXE__ = ' . \wp_json_encode( [
		'version'     => AQUALUXE_VERSION,
		'ajax_url'    => \admin_url( 'admin-ajax.php' ),
		'rest_url'    => \esc_url_raw( \rest_url( 'aqualuxe/v1' ) ),
		'nonce'       => \wp_create_nonce( 'wp_rest' ),
		'is_woo'      => has_woocommerce(),
	] ) . ';', 'before' );
	\wp_enqueue_script( 'aqualuxe-app' );

	// Defer non-critical assets via attributes for performance.
	add_filter( 'script_loader_tag', function ( $tag, $handle ) {
		if ( 'aqualuxe-app' === $handle ) {
			$tag = str_replace( '<script ', '<script defer ', $tag );
		}
		return $tag;
	}, 10, 2 );
} );

// Editor styles
\add_action( 'after_setup_theme', function () {
	\add_editor_style( 'assets/dist/css/app.css' );
} );
