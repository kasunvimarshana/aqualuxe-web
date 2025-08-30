<?php
/**
 * AquaLuxe Implementation Bridge
 *
 * This file serves as a bridge between the existing theme structure and the new unified classes.
 * It provides functions to initialize the new classes and register them with the theme's service container.
 *
 * @package AquaLuxe
 * @since 1.2.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Initialize the unified asset loading system.
 *
 * @param \AquaLuxe\Core\Theme $theme Theme instance.
 * @return void
 */
function aqualuxe_init_unified_assets( $theme ) {
	// Register the Assets class with the service container.
	$theme->register_service( 'assets', '\AquaLuxe\Core\Assets' );
	
	// Get the Assets instance.
	$assets = \AquaLuxe\Core\Assets::get_instance();
	
	// Remove legacy asset loading hooks.
	remove_action( 'wp_enqueue_scripts', 'aqualuxe_legacy_scripts' );
	remove_action( 'admin_enqueue_scripts', 'aqualuxe_admin_styles' );
	remove_action( 'admin_init', 'aqualuxe_add_editor_styles' );
}

/**
 * Initialize the unified template system.
 *
 * @param \AquaLuxe\Core\Theme $theme Theme instance.
 * @return void
 */
function aqualuxe_init_unified_template( $theme ) {
	// Register the Template class with the service container.
	$theme->register_service( 'template', '\AquaLuxe\Core\Template' );
	
	// Get the Template instance.
	$template = \AquaLuxe\Core\Template::get_instance();
	
	// Remove legacy template hooks.
	remove_filter( 'body_class', 'aqualuxe_body_classes' );
	remove_action( 'wp_head', 'aqualuxe_pingback_header' );
}

/**
 * Initialize the unified WooCommerce integration.
 *
 * @param \AquaLuxe\Core\Theme $theme Theme instance.
 * @return void
 */
function aqualuxe_init_unified_woocommerce( $theme ) {
	// Check if WooCommerce is active.
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}
	
	// Register the WooCommerce class with the service container.
	$theme->register_service( 'woocommerce', '\AquaLuxe\WooCommerce\WooCommerce' );
	
	// Get the WooCommerce instance.
	$woocommerce = \AquaLuxe\WooCommerce\WooCommerce::get_instance();
}

/**
 * Initialize all unified systems.
 *
 * @return void
 */
function aqualuxe_init_unified_systems() {
	// Get the theme instance.
	$theme = \AquaLuxe\Core\Theme::get_instance();
	
	// Initialize the unified asset loading system.
	aqualuxe_init_unified_assets( $theme );
	
	// Initialize the unified template system.
	aqualuxe_init_unified_template( $theme );
	
	// Initialize the unified WooCommerce integration.
	aqualuxe_init_unified_woocommerce( $theme );
}

/**
 * Hook the initialization function.
 */
add_action( 'after_setup_theme', 'aqualuxe_init_unified_systems', 5 );

/**
 * Legacy function wrappers for backward compatibility.
 */

if ( ! function_exists( 'aqualuxe_enqueue_scripts' ) ) {
	/**
	 * Wrapper for the enqueue_scripts method of the Assets class.
	 *
	 * @return void
	 */
	function aqualuxe_enqueue_scripts() {
		$assets = \AquaLuxe\Core\Assets::get_instance();
		$assets->enqueue_scripts();
	}
}

if ( ! function_exists( 'aqualuxe_enqueue_styles' ) ) {
	/**
	 * Wrapper for the enqueue_styles method of the Assets class.
	 *
	 * @return void
	 */
	function aqualuxe_enqueue_styles() {
		$assets = \AquaLuxe\Core\Assets::get_instance();
		$assets->enqueue_styles();
	}
}

if ( ! function_exists( 'aqualuxe_body_classes_wrapper' ) ) {
	/**
	 * Wrapper for the body_classes method of the Template class.
	 *
	 * @param array $classes Body classes.
	 * @return array
	 */
	function aqualuxe_body_classes_wrapper( $classes ) {
		$template = \AquaLuxe\Core\Template::get_instance();
		return $template->body_classes( $classes );
	}
}

if ( ! function_exists( 'aqualuxe_pingback_header_wrapper' ) ) {
	/**
	 * Wrapper for the pingback_header method of the Template class.
	 *
	 * @return void
	 */
	function aqualuxe_pingback_header_wrapper() {
		$template = \AquaLuxe\Core\Template::get_instance();
		$template->pingback_header();
	}
}

/**
 * Register legacy wrappers with WordPress hooks.
 */
add_action( 'wp_enqueue_scripts', 'aqualuxe_enqueue_scripts', 10 );
add_action( 'wp_enqueue_scripts', 'aqualuxe_enqueue_styles', 10 );
add_filter( 'body_class', 'aqualuxe_body_classes_wrapper', 10 );
add_action( 'wp_head', 'aqualuxe_pingback_header_wrapper', 10 );