<?php
/**
 * AquaLuxe Application Configuration
 *
 * @package AquaLuxe
 * @since   1.2.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

return [
	/*
	|--------------------------------------------------------------------------
	| Application Name & Version
	|--------------------------------------------------------------------------
	*/
	'name' => 'AquaLuxe',
	'version' => '1.2.0',
	'description' => 'Premium modular WordPress theme with multitenancy support',

	/*
	|--------------------------------------------------------------------------
	| Debug Configuration
	|--------------------------------------------------------------------------
	*/
	'debug' => defined( 'WP_DEBUG' ) && WP_DEBUG,
	'log_errors' => defined( 'WP_DEBUG_LOG' ) && WP_DEBUG_LOG,

	/*
	|--------------------------------------------------------------------------
	| Feature Flags
	|--------------------------------------------------------------------------
	*/
	'features' => [
		'multitenancy' => true,
		'multilingual' => true,
		'multicurrency' => true,
		'multivendor' => true,
		'dark_mode' => true,
		'demo_importer' => true,
		'wholesale' => true,
		'classified_ads' => true,
		'advanced_ui' => true,
		'performance_optimization' => true,
	],

	/*
	|--------------------------------------------------------------------------
	| Module Configuration
	|--------------------------------------------------------------------------
	*/
	'modules' => [
		'enabled' => [
			'ui_ux',
			'dark_mode',
			'demo_importer',
			'woocommerce',
			'wholesale',
			'custom_post_types',
			'custom_taxonomies',
		],
		'autoload' => true,
	],

	/*
	|--------------------------------------------------------------------------
	| Cache Configuration
	|--------------------------------------------------------------------------
	*/
	'cache' => [
		'enabled' => ! ( defined( 'WP_DEBUG' ) && WP_DEBUG ),
		'prefix' => 'aqualuxe_',
		'ttl' => 3600, // 1 hour
		'object_cache' => function_exists( 'wp_cache_set' ),
	],

	/*
	|--------------------------------------------------------------------------
	| Security Configuration
	|--------------------------------------------------------------------------
	*/
	'security' => [
		'nonce_verification' => true,
		'capability_checks' => true,
		'sanitization' => true,
		'csrf_protection' => true,
	],

	/*
	|--------------------------------------------------------------------------
	| Performance Configuration
	|--------------------------------------------------------------------------
	*/
	'performance' => [
		'asset_optimization' => true,
		'lazy_loading' => true,
		'critical_css' => true,
		'preload_assets' => true,
		'defer_scripts' => true,
		'minify_assets' => ! ( defined( 'WP_DEBUG' ) && WP_DEBUG ),
	],

	/*
	|--------------------------------------------------------------------------
	| Localization Configuration
	|--------------------------------------------------------------------------
	*/
	'localization' => [
		'textdomain' => 'aqualuxe',
		'languages_dir' => '/languages',
		'fallback_locale' => 'en_US',
		'rtl_support' => true,
	],

	/*
	|--------------------------------------------------------------------------
	| API Configuration
	|--------------------------------------------------------------------------
	*/
	'api' => [
		'rest_namespace' => 'aqualuxe/v1',
		'enable_rest_api' => true,
		'enable_graphql' => false,
		'rate_limiting' => true,
	],

	/*
	|--------------------------------------------------------------------------
	| Third-party Integrations
	|--------------------------------------------------------------------------
	*/
	'integrations' => [
		'google_analytics' => [
			'enabled' => false,
			'tracking_id' => '',
		],
		'facebook_pixel' => [
			'enabled' => false,
			'pixel_id' => '',
		],
		'mailchimp' => [
			'enabled' => false,
			'api_key' => '',
		],
	],
];
