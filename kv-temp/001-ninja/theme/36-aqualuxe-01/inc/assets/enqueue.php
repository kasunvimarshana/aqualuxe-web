<?php
/**
 * Asset enqueuing functions
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get asset path.
 *
 * @param string $path Path to asset.
 * @return string
 */
function aqualuxe_asset_path( $path ) {
	// Check if the file exists in the dist directory.
	if ( file_exists( AQUALUXE_DIR . 'assets/dist/' . $path ) ) {
		return AQUALUXE_URI . 'assets/dist/' . $path;
	}

	// Check if the file exists in the regular assets directory.
	if ( file_exists( AQUALUXE_DIR . 'assets/' . $path ) ) {
		return AQUALUXE_URI . 'assets/' . $path;
	}

	// Return the path as is.
	return AQUALUXE_URI . 'assets/' . $path;
}

/**
 * Get asset version.
 *
 * @param string $path Path to asset.
 * @return string
 */
function aqualuxe_asset_version( $path ) {
	// Check if the file exists in the dist directory.
	if ( file_exists( AQUALUXE_DIR . 'assets/dist/' . $path ) ) {
		return filemtime( AQUALUXE_DIR . 'assets/dist/' . $path );
	}

	// Return the theme version.
	return AQUALUXE_VERSION;
}

/**
 * Enqueue scripts and styles.
 */
function aqualuxe_scripts() {
	// Register and enqueue styles.
	wp_enqueue_style(
		'aqualuxe-styles',
		aqualuxe_asset_path( 'css/tailwind.css' ),
		[],
		AQUALUXE_VERSION
	);
	
	// Add WooCommerce styles if active.
	if ( class_exists( 'WooCommerce' ) ) {
		wp_enqueue_style(
			'aqualuxe-woocommerce',
			aqualuxe_asset_path( 'css/woocommerce.css' ),
			[ 'aqualuxe-styles' ],
			AQUALUXE_VERSION
		);
	}
	
	// Add RTL styles if needed.
	if ( is_rtl() ) {
		wp_enqueue_style(
			'aqualuxe-rtl',
			aqualuxe_asset_path( 'css/rtl.css' ),
			[ 'aqualuxe-styles' ],
			AQUALUXE_VERSION
		);
	}
	
	// Register and enqueue scripts.
	wp_enqueue_script(
		'aqualuxe-scripts',
		aqualuxe_asset_path( 'js/app.js' ),
		[ 'jquery' ],
		AQUALUXE_VERSION,
		true
	);

	// Add comment reply script if needed.
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	// Localize script.
	wp_localize_script(
		'aqualuxe-scripts',
		'aqualuxeData',
		[
			'ajaxUrl'            => admin_url( 'admin-ajax.php' ),
			'themeUri'          => AQUALUXE_URI,
			'assetsUri'         => AQUALUXE_ASSETS_URI,
			'isWooCommerceActive' => class_exists( 'WooCommerce' ),
			'nonce'             => wp_create_nonce( 'aqualuxe-nonce' ),
			'i18n'              => [
				'searchPlaceholder' => esc_html__( 'Search...', 'aqualuxe' ),
				'menuToggle'        => esc_html__( 'Menu', 'aqualuxe' ),
				'closeMenu'         => esc_html__( 'Close Menu', 'aqualuxe' ),
				'darkModeToggle'    => esc_html__( 'Toggle Dark Mode', 'aqualuxe' ),
				'loading'           => esc_html__( 'Loading...', 'aqualuxe' ),
				'addToCart'         => esc_html__( 'Add to Cart', 'aqualuxe' ),
				'adding'            => esc_html__( 'Adding...', 'aqualuxe' ),
				'added'             => esc_html__( 'Added!', 'aqualuxe' ),
				'viewCart'          => esc_html__( 'View Cart', 'aqualuxe' ),
			],
		]
	);
}
add_action( 'wp_enqueue_scripts', 'aqualuxe_scripts' );

/**
 * Enqueue admin scripts and styles.
 */
function aqualuxe_admin_scripts() {
	// Register and enqueue admin styles.
	wp_enqueue_style(
		'aqualuxe-admin-styles',
		aqualuxe_asset_path( 'css/admin.css' ),
		[],
		AQUALUXE_VERSION
	);

	// Register and enqueue admin scripts.
	wp_enqueue_script(
		'aqualuxe-admin-scripts',
		aqualuxe_asset_path( 'js/admin.js' ),
		[ 'jquery' ],
		AQUALUXE_VERSION,
		true
	);
}
add_action( 'admin_enqueue_scripts', 'aqualuxe_admin_scripts' );

/**
 * Enqueue block editor assets.
 */
function aqualuxe_block_editor_assets() {
	// Register and enqueue editor styles.
	wp_enqueue_style(
		'aqualuxe-editor-styles',
		aqualuxe_asset_path( 'css/editor-style.css' ),
		[],
		AQUALUXE_VERSION
	);
}
add_action( 'enqueue_block_editor_assets', 'aqualuxe_block_editor_assets' );

/**
 * Add preconnect for Google Fonts.
 *
 * @param array  $urls           URLs to print for resource hints.
 * @param string $relation_type  The relation type the URLs are printed.
 * @return array $urls           URLs to print for resource hints.
 */
function aqualuxe_resource_hints( $urls, $relation_type ) {
	if ( wp_style_is( 'aqualuxe-fonts', 'queue' ) && 'preconnect' === $relation_type ) {
		$urls[] = [
			'href' => 'https://fonts.gstatic.com',
			'crossorigin',
		];
	}

	return $urls;
}
add_filter( 'wp_resource_hints', 'aqualuxe_resource_hints', 10, 2 );