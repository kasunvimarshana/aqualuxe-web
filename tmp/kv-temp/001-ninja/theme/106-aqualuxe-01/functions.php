<?php
/**
 * AquaLuxe Theme bootstrap.
 *
 * @package AquaLuxe
 */

declare( strict_types=1 );

// Abort direct access.
if ( ! defined( 'ABSPATH' ) ) { exit; }

// -----------------------------------------------------------------------------
// Constants.
// -----------------------------------------------------------------------------
if ( ! defined( 'AQUALUXE_VERSION' ) ) {
	define( 'AQUALUXE_VERSION', '0.1.0' );
}
if ( ! defined( 'AQUALUXE_PATH' ) ) {
	define( 'AQUALUXE_PATH', trailingslashit( get_template_directory() ) );
}
if ( ! defined( 'AQUALUXE_URI' ) ) {
	define( 'AQUALUXE_URI', trailingslashit( get_template_directory_uri() ) );
}
if ( ! defined( 'AQUALUXE_ENV' ) ) {
	// Simple env toggle (prod|dev).
	define( 'AQUALUXE_ENV', defined( 'WP_DEBUG' ) && WP_DEBUG ? 'dev' : 'prod' );
}

// -----------------------------------------------------------------------------
// Autoloader (PSR-4 like for theme namespaces: AquaLuxe\Core, AquaLuxe\Modules).
// -----------------------------------------------------------------------------
spl_autoload_register( function ( $class ) {
	if ( 0 !== strpos( $class, 'AquaLuxe\\' ) ) { return; }
	$path = str_replace( [ 'AquaLuxe\\', '\\' ], [ '', '/' ], $class );
	$file = AQUALUXE_PATH . 'inc/' . strtolower( $path ) . '.php';
	if ( file_exists( $file ) ) {
		require_once $file;
	}
} );

// -----------------------------------------------------------------------------
// Helpers loader (non-class PHP includes kept minimal & SRP oriented).
// -----------------------------------------------------------------------------
foreach ( [ 'helpers/assets', 'helpers/security', 'helpers/template' ] as $helper ) {
	$file = AQUALUXE_PATH . 'inc/' . $helper . '.php';
	if ( file_exists( $file ) ) { require_once $file; }
}

// -----------------------------------------------------------------------------
// Theme Setup.
// -----------------------------------------------------------------------------
add_action( 'after_setup_theme', function (): void {
	load_theme_textdomain( 'aqualuxe', AQUALUXE_PATH . 'languages' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', [ 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'script', 'style' ] );
	add_theme_support( 'custom-logo', [ 'height' => 60, 'flex-width' => true, 'flex-height' => true ] );
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'woocommerce' ); // Safe if inactive.

	register_nav_menus( [
		'primary'   => __( 'Primary Menu', 'aqualuxe' ),
		'footer'    => __( 'Footer Menu', 'aqualuxe' ),
		'account'   => __( 'Account Menu', 'aqualuxe' ),
	] );
} );

// -----------------------------------------------------------------------------
// Cleanups & performance micro-optimizations.
// -----------------------------------------------------------------------------
add_action( 'init', function (): void {
	// Disable emoji scripts/styles.
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
} );

// -----------------------------------------------------------------------------
// Module Manager bootstrap (deferred until plugins loaded for WC detection).
// -----------------------------------------------------------------------------
add_action( 'plugins_loaded', function (): void {
	$manager = AquaLuxe\Core\Module_Manager::instance();
	$manager->register_core();
	$manager->discover_and_register();
	$manager->boot();
} );

// -----------------------------------------------------------------------------
// Enqueue assets (built via mix + hashed names) - keep minimal here.
// -----------------------------------------------------------------------------
add_action( 'wp_enqueue_scripts', function (): void {
	$css = aqualuxe_asset( 'theme.css' );
	$js  = aqualuxe_asset( 'theme.js' );
	if ( $css ) { wp_enqueue_style( 'aqualuxe-theme', $css, [], AQUALUXE_VERSION ); }
	if ( $js ) {
		wp_enqueue_script( 'aqualuxe-theme', $js, [ 'jquery' ], AQUALUXE_VERSION, true );
		wp_localize_script( 'aqualuxe-theme', 'AquaLuxeSettings', [
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'rest_url' => esc_url_raw( rest_url() ),
			'nonce'    => wp_create_nonce( 'aqualuxe_nonce' ),
			'env'      => AQUALUXE_ENV,
		] );
	}
}, 20 );

// -----------------------------------------------------------------------------
// Admin assets (future custom pages / importer UI).
// -----------------------------------------------------------------------------
add_action( 'admin_enqueue_scripts', function (): void {
	$css = aqualuxe_asset( 'admin.css' );
	$js  = aqualuxe_asset( 'admin.js' );
	if ( $css ) { wp_enqueue_style( 'aqualuxe-admin', $css, [], AQUALUXE_VERSION ); }
	if ( $js ) { wp_enqueue_script( 'aqualuxe-admin', $js, [ 'jquery' ], AQUALUXE_VERSION, true ); }
} );
