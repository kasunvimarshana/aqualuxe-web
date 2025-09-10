<?php
/**
 * Theme Constants and Configuration
 *
 * Defines all theme constants and configuration settings for the AquaLuxe theme.
 * This file is loaded first and provides the foundation for the entire theme architecture.
 *
 * @package AquaLuxe
 * @subpackage Core
 * @since 2.0.0
 * @author Kasun Vimarshana <kasunv.com@gmail.com>
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Theme version constant
 *
 * @since 2.0.0
 */
if ( ! defined( 'AQUALUXE_VERSION' ) ) {
	define( 'AQUALUXE_VERSION', '2.0.0' );
}

/**
 * Theme text domain for translations
 *
 * @since 2.0.0
 */
if ( ! defined( 'AQUALUXE_TEXT_DOMAIN' ) ) {
	define( 'AQUALUXE_TEXT_DOMAIN', 'aqualuxe' );
}

/**
 * Theme directory path constants
 *
 * @since 2.0.0
 */
if ( ! defined( 'AQUALUXE_THEME_DIR' ) ) {
	define( 'AQUALUXE_THEME_DIR', get_template_directory() );
}

if ( ! defined( 'AQUALUXE_THEME_URI' ) ) {
	define( 'AQUALUXE_THEME_URI', get_template_directory_uri() );
}

if ( ! defined( 'AQUALUXE_INC_DIR' ) ) {
	define( 'AQUALUXE_INC_DIR', AQUALUXE_THEME_DIR . '/inc/' );
}

if ( ! defined( 'AQUALUXE_CORE_DIR' ) ) {
	define( 'AQUALUXE_CORE_DIR', AQUALUXE_INC_DIR . 'core/' );
}

if ( ! defined( 'AQUALUXE_MODULES_DIR' ) ) {
	define( 'AQUALUXE_MODULES_DIR', AQUALUXE_THEME_DIR . '/modules/' );
}

if ( ! defined( 'AQUALUXE_ASSETS_DIR' ) ) {
	define( 'AQUALUXE_ASSETS_DIR', AQUALUXE_THEME_DIR . '/assets/' );
}

if ( ! defined( 'AQUALUXE_ASSETS_URI' ) ) {
	define( 'AQUALUXE_ASSETS_URI', AQUALUXE_THEME_URI . '/assets/' );
}

if ( ! defined( 'AQUALUXE_TEMPLATES_DIR' ) ) {
	define( 'AQUALUXE_TEMPLATES_DIR', AQUALUXE_THEME_DIR . '/templates/' );
}

if ( ! defined( 'AQUALUXE_LANGUAGES_DIR' ) ) {
	define( 'AQUALUXE_LANGUAGES_DIR', AQUALUXE_THEME_DIR . '/languages/' );
}

/**
 * Asset distribution directory constants
 *
 * @since 2.0.0
 */
if ( ! defined( 'AQUALUXE_DIST_DIR' ) ) {
	define( 'AQUALUXE_DIST_DIR', AQUALUXE_ASSETS_DIR . 'dist/' );
}

if ( ! defined( 'AQUALUXE_DIST_URI' ) ) {
	define( 'AQUALUXE_DIST_URI', AQUALUXE_ASSETS_URI . 'dist/' );
}

if ( ! defined( 'AQUALUXE_SRC_DIR' ) ) {
	define( 'AQUALUXE_SRC_DIR', AQUALUXE_ASSETS_DIR . 'src/' );
}

/**
 * Development mode constant
 *
 * @since 2.0.0
 */
if ( ! defined( 'AQUALUXE_DEV_MODE' ) ) {
	define( 'AQUALUXE_DEV_MODE', defined( 'WP_DEBUG' ) && WP_DEBUG );
}

/**
 * Theme configuration defaults
 *
 * @since 2.0.0
 */
if ( ! defined( 'AQUALUXE_CONFIG' ) ) {
	define( 'AQUALUXE_CONFIG', [
		// Core module settings
		'core' => [
			'autoload_modules' => true,
			'enable_caching' => true,
			'enable_minification' => ! AQUALUXE_DEV_MODE,
		],
		
		// Module defaults - can be overridden via filters
		'modules' => [
			// Core modules (always enabled)
			'assets' => true,
			'customizer' => true,
			'template_engine' => true,
			'security' => true,
			'performance' => true,
			
			// Feature modules (configurable)
			'woocommerce' => class_exists( 'WooCommerce' ),
			'dark_mode' => true,
			'multilingual' => true,
			'seo' => true,
			'schema' => true,
			'accessibility' => true,
			
			// Business modules (optional)
			'multivendor' => false,
			'multicurrency' => false,
			'classified_ads' => false,
			'bookings' => false,
			'events' => false,
			'subscriptions' => false,
			'franchise' => false,
			'wholesale' => false,
			'auctions' => false,
			'affiliate' => false,
			'professional_services' => false,
			
			// Content modules
			'custom_post_types' => true,
			'demo_importer' => true,
			'portfolio' => false,
			'testimonials' => true,
			
			// Advanced modules
			'api_integrations' => false,
			'analytics' => false,
			'backup' => false,
			'cache' => false,
		],
		
		// Asset handling
		'assets' => [
			'version_strategy' => 'mix_manifest', // mix_manifest, file_time, or static
			'minify_css' => ! AQUALUXE_DEV_MODE,
			'minify_js' => ! AQUALUXE_DEV_MODE,
			'combine_css' => ! AQUALUXE_DEV_MODE,
			'combine_js' => ! AQUALUXE_DEV_MODE,
			'enable_critical_css' => true,
			'lazy_load_images' => true,
		],
		
		// Security settings
		'security' => [
			'disable_file_editing' => true,
			'hide_wp_version' => true,
			'disable_xml_rpc' => true,
			'secure_headers' => true,
			'sanitize_uploads' => true,
		],
		
		// Performance settings
		'performance' => [
			'enable_gzip' => true,
			'browser_caching' => true,
			'optimize_database' => false,
			'lazy_load_content' => true,
			'preload_critical_resources' => true,
		],
		
		// SEO settings
		'seo' => [
			'auto_meta_tags' => true,
			'open_graph' => true,
			'twitter_cards' => true,
			'schema_markup' => true,
			'xml_sitemap' => true,
		],
	] );
}
