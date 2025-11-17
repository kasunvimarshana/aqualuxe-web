<?php
/**
 * AquaLuxe Theme - Bootstrap Entry Point
 *
 * Modern, modular, and enterprise-grade WordPress theme for aquatic businesses.
 * Features a loosely coupled architecture following SOLID principles with
 * support for multivendor, multilingual, multicurrency functionality.
 *
 * @package AquaLuxe
 * @subpackage Core
 * @since 2.0.0
 * @author Kasun Vimarshana <kasunv.com@gmail.com>
 * @link https://github.com/kasunvimarshana/aqualuxe-web
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Theme constants and configuration
 * 
 * Load theme constants first before any other includes.
 * This ensures all paths and configuration are available
 * throughout the theme initialization process.
 */
require_once get_template_directory() . '/inc/constants.php';

/**
 * Load the PSR-4 autoloader
 * 
 * Initialize the theme's autoloader to enable automatic
 * class loading following PSR-4 conventions.
 */
require_once AQUALUXE_CORE_DIR . 'autoloader.php';

/**
 * Bootstrap the theme
 * 
 * Initialize the theme bootstrap class which orchestrates
 * the loading of all core components and modules.
 * 
 * The bootstrap class implements the Singleton pattern
 * to ensure single initialization and provides a central
 * registry for all theme components.
 */
function aqualuxe_bootstrap_theme() {
	try {
		// Initialize the core bootstrap
		$bootstrap = \AquaLuxe\Core\Bootstrap::get_instance();
		
		/**
		 * Hook: aqualuxe_theme_bootstrapped
		 * 
		 * Fires after the theme has been successfully bootstrapped.
		 * This allows other plugins or theme components to hook
		 * into the theme initialization process.
		 * 
		 * @since 2.0.0
		 * @param \AquaLuxe\Core\Bootstrap $bootstrap The bootstrap instance.
		 */
		do_action( 'aqualuxe_theme_bootstrapped', $bootstrap );
		
	} catch ( Exception $e ) {
		// Log critical errors in development mode
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			error_log( 'AquaLuxe Theme Bootstrap Error: ' . $e->getMessage() );
		}
		
		// Fallback to basic theme functionality
		aqualuxe_fallback_mode();
	}
}

/**
 * Fallback mode for theme errors
 * 
 * Provides basic theme functionality when the main
 * bootstrap process fails. This ensures the site
 * remains functional even if there are critical errors.
 */
function aqualuxe_fallback_mode() {
	// Basic theme support
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	
	// Basic styles
	add_action( 'wp_enqueue_scripts', function() {
		wp_enqueue_style( 
			'aqualuxe-fallback', 
			get_template_directory_uri() . '/style.css',
			[],
			wp_get_theme()->get( 'Version' )
		);
	} );
	
	// Log fallback mode activation
	if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
		error_log( 'AquaLuxe Theme: Running in fallback mode due to bootstrap failure.' );
	}
}

/**
 * Initialize theme on WordPress init
 * 
 * Hook the theme bootstrap to the 'after_setup_theme' action
 * to ensure WordPress is properly initialized before our
 * theme begins its setup process.
 */
add_action( 'after_setup_theme', 'aqualuxe_bootstrap_theme', 1 );

/**
 * Theme utility functions
 * 
 * Global utility functions that can be used throughout
 * the theme and by other plugins or child themes.
 */

/**
 * Get the theme bootstrap instance
 * 
 * Provides global access to the theme bootstrap instance
 * for accessing core components and modules.
 * 
 * @since 2.0.0
 * @return \AquaLuxe\Core\Bootstrap|null The bootstrap instance or null if not initialized.
 */
function aqualuxe() {
	try {
		return \AquaLuxe\Core\Bootstrap::get_instance();
	} catch ( Exception $e ) {
		return null;
	}
}

/**
 * Get a theme module instance
 * 
 * Convenience function for accessing loaded theme modules.
 * 
 * @since 2.0.0
 * @param string $module_key The module key/identifier.
 * @return object|null The module instance or null if not loaded.
 */
function aqualuxe_get_module( $module_key ) {
	$bootstrap = aqualuxe();
	return $bootstrap ? $bootstrap->get_module( $module_key ) : null;
}

/**
 * Check if a theme module is enabled
 * 
 * @since 2.0.0
 * @param string $module_key The module key/identifier.
 * @return bool True if enabled, false otherwise.
 */
function aqualuxe_is_module_enabled( $module_key ) {
	$bootstrap = aqualuxe();
	return $bootstrap ? $bootstrap->is_module_enabled( $module_key ) : false;
}

/**
 * Get theme configuration value
 * 
 * @since 2.0.0
 * @param string $key The configuration key (supports dot notation).
 * @param mixed  $default Default value if key not found.
 * @return mixed The configuration value.
 */
function aqualuxe_get_config( $key, $default = null ) {
	$bootstrap = aqualuxe();
	if ( ! $bootstrap ) {
		return $default;
	}
	
	$config = $bootstrap->get_config();
	
	// Support dot notation (e.g., 'modules.woocommerce')
	$keys = explode( '.', $key );
	$value = $config;
	
	foreach ( $keys as $k ) {
		if ( ! is_array( $value ) || ! isset( $value[ $k ] ) ) {
			return $default;
		}
		$value = $value[ $k ];
	}
	
	return $value;
}

/**
 * Get theme version
 * 
 * @since 2.0.0
 * @return string The theme version.
 */
function aqualuxe_get_version() {
	return defined( 'AQUALUXE_VERSION' ) ? AQUALUXE_VERSION : '2.0.0';
}

/**
 * Check if development mode is enabled
 * 
 * @since 2.0.0
 * @return bool True if in development mode.
 */
function aqualuxe_is_dev_mode() {
	return defined( 'AQUALUXE_DEV_MODE' ) && AQUALUXE_DEV_MODE;
}

/**
 * Compatibility and legacy support
 * 
 * Ensure backward compatibility with existing functionality
 * while the refactoring process is completed.
 */

// Prevent direct access to legacy theme class
if ( class_exists( 'AquaLuxe_Theme' ) ) {
	add_action( 'init', function() {
		if ( aqualuxe_is_dev_mode() ) {
			trigger_error( 
				'Legacy AquaLuxe_Theme class detected. Please update to use the new modular architecture.', 
				E_USER_DEPRECATED 
			);
		}
	} );
}
