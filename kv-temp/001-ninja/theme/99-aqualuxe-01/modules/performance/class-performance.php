<?php
/**
 * Performance Optimization for AquaLuxe.
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * The main performance optimization class.
 */
class AquaLuxe_Performance {

	/**
	 * Initialize performance optimizations.
	 */
	public static function init() {
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'dequeue_unnecessary_assets' ), 999 );
		add_filter( 'style_loader_src', array( __CLASS__, 'remove_query_strings' ), 15, 1 );
		add_filter( 'script_loader_src', array( __CLASS__, 'remove_query_strings' ), 15, 1 );
	}

	/**
	 * Dequeue unnecessary assets.
	 */
	public static function dequeue_unnecessary_assets() {
		// Dequeue block library styles if not using Gutenberg blocks.
		if ( ! use_block_editor_for_post_type( 'page' ) ) {
			wp_dequeue_style( 'wp-block-library' );
			wp_dequeue_style( 'wp-block-library-theme' );
			wp_dequeue_style( 'wc-block-style' );
		}
	}

	/**
	 * Remove query strings from static resources.
	 *
	 * @param string $src The source URL.
	 * @return string The modified source URL.
	 */
	public static function remove_query_strings( $src ) {
		if ( strpos( $src, '?ver=' ) ) {
			$src = remove_query_arg( 'ver', $src );
		}
		return $src;
	}
}
