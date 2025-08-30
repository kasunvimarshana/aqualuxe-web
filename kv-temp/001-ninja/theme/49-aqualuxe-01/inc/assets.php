<?php
/**
 * Asset management functions
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Enqueue scripts and styles.
 */
function aqualuxe_scripts() {
	// Get the mix-manifest.json file
	$manifest_path = get_template_directory() . '/assets/dist/mix-manifest.json';
	$manifest = file_exists( $manifest_path ) ? json_decode( file_get_contents( $manifest_path ), true ) : [];

	// Helper function to get versioned asset path
	$get_asset = function( $path ) use ( $manifest ) {
		$versioned_path = isset( $manifest[ $path ] ) ? $manifest[ $path ] : $path;
		return get_template_directory_uri() . '/assets/dist' . $versioned_path;
	};

	// Enqueue main stylesheet
	wp_enqueue_style( 
		'aqualuxe-style', 
		$get_asset( '/css/main.css' ), 
		array(), 
		AQUALUXE_VERSION 
	);

	// Enqueue main JavaScript
	wp_enqueue_script( 
		'aqualuxe-script', 
		$get_asset( '/js/main.js' ), 
		array( 'jquery' ), 
		AQUALUXE_VERSION, 
		true 
	);

	// Enqueue dark mode script
	wp_enqueue_script( 
		'aqualuxe-dark-mode', 
		$get_asset( '/js/dark-mode.js' ), 
		array( 'jquery' ), 
		AQUALUXE_VERSION, 
		true 
	);

	// Localize script for dark mode
	wp_localize_script(
		'aqualuxe-dark-mode',
		'aqualuxeDarkMode',
		array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'aqualuxe-dark-mode-nonce' ),
		)
	);

	// Conditionally load WooCommerce assets
	if ( class_exists( 'WooCommerce' ) ) {
		wp_enqueue_style( 
			'aqualuxe-woocommerce', 
			$get_asset( '/css/woocommerce.css' ), 
			array( 'aqualuxe-style' ), 
			AQUALUXE_VERSION 
		);

		wp_enqueue_script( 
			'aqualuxe-woocommerce', 
			$get_asset( '/js/woocommerce.js' ), 
			array( 'jquery', 'aqualuxe-script' ), 
			AQUALUXE_VERSION, 
			true 
		);

		// Localize WooCommerce script
		wp_localize_script(
			'aqualuxe-woocommerce',
			'aqualuxeWooCommerce',
			array(
				'ajaxUrl'       => admin_url( 'admin-ajax.php' ),
				'nonce'         => wp_create_nonce( 'aqualuxe-woocommerce-nonce' ),
				'isWooCommerce' => true,
			)
		);
	} else {
		// Load fallback script when WooCommerce is not active
		wp_enqueue_script( 
			'aqualuxe-woocommerce-fallback', 
			$get_asset( '/js/woocommerce-fallback.js' ), 
			array( 'jquery', 'aqualuxe-script' ), 
			AQUALUXE_VERSION, 
			true 
		);

		// Localize fallback script
		wp_localize_script(
			'aqualuxe-woocommerce-fallback',
			'aqualuxeWooCommerce',
			array(
				'ajaxUrl'       => admin_url( 'admin-ajax.php' ),
				'nonce'         => wp_create_nonce( 'aqualuxe-woocommerce-nonce' ),
				'isWooCommerce' => false,
			)
		);
	}

	// Add comment-reply script
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'aqualuxe_scripts' );

/**
 * Enqueue admin scripts and styles.
 */
function aqualuxe_admin_scripts() {
	// Get the mix-manifest.json file
	$manifest_path = get_template_directory() . '/assets/dist/mix-manifest.json';
	$manifest = file_exists( $manifest_path ) ? json_decode( file_get_contents( $manifest_path ), true ) : [];

	// Helper function to get versioned asset path
	$get_asset = function( $path ) use ( $manifest ) {
		$versioned_path = isset( $manifest[ $path ] ) ? $manifest[ $path ] : $path;
		return get_template_directory_uri() . '/assets/dist' . $versioned_path;
	};

	// Enqueue admin stylesheet
	wp_enqueue_style( 
		'aqualuxe-admin-style', 
		$get_asset( '/css/admin.css' ), 
		array(), 
		AQUALUXE_VERSION 
	);

	// Enqueue admin JavaScript
	wp_enqueue_script( 
		'aqualuxe-admin-script', 
		$get_asset( '/js/admin.js' ), 
		array( 'jquery' ), 
		AQUALUXE_VERSION, 
		true 
	);
}
add_action( 'admin_enqueue_scripts', 'aqualuxe_admin_scripts' );

/**
 * Add preconnect for Google Fonts.
 *
 * @param array  $urls           URLs to print for resource hints.
 * @param string $relation_type  The relation type the URLs are printed.
 * @return array $urls           URLs to print for resource hints.
 */
function aqualuxe_resource_hints( $urls, $relation_type ) {
	if ( wp_style_is( 'aqualuxe-fonts', 'queue' ) && 'preconnect' === $relation_type ) {
		$urls[] = array(
			'href' => 'https://fonts.gstatic.com',
			'crossorigin',
		);
	}

	return $urls;
}
add_filter( 'wp_resource_hints', 'aqualuxe_resource_hints', 10, 2 );