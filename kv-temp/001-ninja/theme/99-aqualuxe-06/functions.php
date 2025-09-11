<?php
/**
 * AquaLuxe Theme Functions
 *
 * Premium modular WordPress theme for luxury aquatic solutions
 * Multitenant, multivendor, multilingual, multicurrency architecture
 * "Bringing elegance to aquatic life – globally"
 *
 * @package AquaLuxe
 * @version 2.0.0
 * @author AquaLuxe Development Team
 * @since 1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct access forbidden.' );
}

/**
 * AquaLuxe Theme Constants
 * Define core theme configuration constants
 */
if ( ! defined( 'AQUALUXE_VERSION' ) ) {
	define( 'AQUALUXE_VERSION', '2.0.0' );
}

define( 'AQUALUXE_THEME_PATH', get_template_directory() );
define( 'AQUALUXE_THEME_URL', get_template_directory_uri() );
define( 'AQUALUXE_THEME_INC_PATH', AQUALUXE_THEME_PATH . '/inc' );
define( 'AQUALUXE_THEME_CORE_PATH', AQUALUXE_THEME_PATH . '/core' );
define( 'AQUALUXE_MODULES', AQUALUXE_THEME_PATH . '/modules' );
define( 'AQUALUXE_TEMPLATES', AQUALUXE_THEME_PATH . '/templates' );
define( 'AQUALUXE_ASSETS_PATH', AQUALUXE_THEME_PATH . '/assets' );
define( 'AQUALUXE_ASSETS_URL', AQUALUXE_THEME_URL . '/assets' );
define( 'AQUALUXE_TEXTDOMAIN', 'aqualuxe' );

/**
 * Minimum Requirements Check
 */
function aqualuxe_check_requirements() {
	$min_php = '8.1';
	$min_wp = '6.0';
	
	if ( version_compare( PHP_VERSION, $min_php, '<' ) ) {
		add_action( 'admin_notices', function() use ( $min_php ) {
			echo '<div class="notice notice-error"><p>';
			printf(
				/* translators: %1$s: minimum PHP version, %2$s: current PHP version */
				esc_html__( 'AquaLuxe theme requires PHP version %1$s or higher. You are running version %2$s.', 'aqualuxe' ),
				esc_html( $min_php ),
				esc_html( PHP_VERSION )
			);
			echo '</p></div>';
		} );
		return false;
	}
	
	if ( version_compare( get_bloginfo( 'version' ), $min_wp, '<' ) ) {
		add_action( 'admin_notices', function() use ( $min_wp ) {
			echo '<div class="notice notice-error"><p>';
			printf(
				/* translators: %1$s: minimum WordPress version, %2$s: current WordPress version */
				esc_html__( 'AquaLuxe theme requires WordPress version %1$s or higher. You are running version %2$s.', 'aqualuxe' ),
				esc_html( $min_wp ),
				esc_html( get_bloginfo( 'version' ) )
			);
			echo '</p></div>';
		} );
		return false;
	}
	
	return true;
}

// Early requirements check
if ( ! aqualuxe_check_requirements() ) {
	return;
}

/**
 * Autoloader for AquaLuxe Classes
 * Implements PSR-4 autoloading standard
 */
spl_autoload_register( function( $class_name ) {
	// Only autoload AquaLuxe classes
	if ( strpos( $class_name, 'AquaLuxe\\' ) !== 0 ) {
		return;
	}
	
	// Convert namespace to file path
	$class_file = str_replace( 'AquaLuxe\\', '', $class_name );
	$class_file = str_replace( '\\', DIRECTORY_SEPARATOR, $class_file );
	$class_file = AQUALUXE_THEME_INC_PATH . '/classes/' . $class_file . '.php';
	
	if ( file_exists( $class_file ) ) {
		require_once $class_file;
	}
} );

/**
 * Include Core Files
 * Load core functionality in proper order
 */
$core_files = [
	// Core setup and utilities
	'inc/functions/helpers.php',              // Helper functions
	'inc/functions/security.php',             // Security hardening
	'inc/functions/performance.php',          // Performance optimization
	'inc/functions/accessibility.php',        // Accessibility features
	'inc/functions/seo.php',                  // SEO optimization
	
	// Theme setup
	'inc/setup/theme-setup.php',             // Basic theme setup
	'inc/setup/assets.php',                  // Asset management
	'inc/setup/menus.php',                   // Navigation menus
	'inc/setup/sidebars.php',                // Widget areas
	'inc/setup/customizer.php',              // Theme Customizer
	
	// Custom post types and taxonomies
	'inc/setup/post-types.php',              // Custom post types
	'inc/setup/taxonomies.php',              // Custom taxonomies
	'inc/setup/meta-fields.php',             // Custom meta fields
	
	// Core features
	'inc/features/multilingual.php',         // Multilingual support
	'inc/features/multicurrency.php',        // Multi-currency support
	'inc/features/dark-mode.php',            // Dark mode functionality
	'inc/features/user-roles.php',           // User role management
	
	// Components and shortcodes
	'inc/components/shortcodes.php',         // Shortcode system
	'inc/components/widgets.php',            // Custom widgets
	
	// Admin functionality
	'inc/admin/dashboard.php',               // Custom dashboard
	'inc/admin/demo-importer.php',           // Demo content importer
	'inc/admin/theme-options.php',           // Theme options panel
	
	// Template functions
	'inc/template-functions.php',            // Template helper functions
	'inc/template-hooks.php',                // Action and filter hooks
];

// WooCommerce integration (conditional)
if ( class_exists( 'WooCommerce' ) ) {
	$core_files[] = 'inc/woocommerce/woocommerce-setup.php';
	$core_files[] = 'inc/woocommerce/woocommerce-functions.php';
	$core_files[] = 'inc/woocommerce/woocommerce-hooks.php';
}

// Load core files with error handling
foreach ( $core_files as $file ) {
	$file_path = AQUALUXE_THEME_PATH . '/' . $file;
	if ( file_exists( $file_path ) ) {
		require_once $file_path;
	} else {
		// Log missing file for debugging
		if ( WP_DEBUG && WP_DEBUG_LOG ) {
			error_log( "AquaLuxe: Core file not found: {$file_path}" );
		}
	}
}

/**
 * Initialize Theme
 * Boot up the theme after all core files are loaded
 */
function aqualuxe_init() {
	/**
	 * Load text domain for internationalization
	 */
	load_theme_textdomain( AQUALUXE_TEXTDOMAIN, get_template_directory() . '/languages' );
	
	/**
	 * Initialize core theme classes
	 */
	if ( class_exists( 'AquaLuxe\\Core\\Theme' ) ) {
		AquaLuxe\Core\Theme::get_instance();
	}
	
	/**
	 * Load and initialize modules
	 */
	aqualuxe_load_modules();
	
	/**
	 * Hook: aqualuxe_after_theme_init
	 * 
	 * Allows plugins and child themes to hook after theme initialization
	 */
	do_action( 'aqualuxe_after_theme_init' );
}

/**
 * Module System
 * Load modular components for extensibility
 */
function aqualuxe_load_modules() {
	$modules_dir = AQUALUXE_MODULES;
	
	if ( ! is_dir( $modules_dir ) ) {
		return;
	}
	
	$active_modules = aqualuxe_get_active_modules();
	
	foreach ( $active_modules as $module_slug ) {
		$module_file = $modules_dir . '/' . $module_slug . '/module.php';
		
		if ( file_exists( $module_file ) ) {
			require_once $module_file;
			
			/**
			 * Hook: aqualuxe_module_loaded
			 * 
			 * @param string $module_slug The module slug
			 */
			do_action( 'aqualuxe_module_loaded', $module_slug );
		}
	}
}

/**
 * Get Active Modules
 * Returns array of active module slugs
 * 
 * @return array Active module slugs
 */
function aqualuxe_get_active_modules() {
	$default_modules = [
		'dark-mode',
		'multilingual',
		'multicurrency',
		'custom-post-types',
		'demo-importer',
		'performance-optimizer',
		'seo-enhancer',
	];
	
	/**
	 * Filter: aqualuxe_active_modules
	 * 
	 * Allow filtering of active modules
	 * 
	 * @param array $modules Active modules
	 */
	return apply_filters( 'aqualuxe_active_modules', $default_modules );
}

/**
 * Theme Activation Hook
 * Runs when theme is activated
 */
function aqualuxe_activation() {
	// Set default theme options
	aqualuxe_set_default_options();
	
	// Create necessary database tables if needed
	aqualuxe_create_tables();
	
	// Schedule cron events
	aqualuxe_schedule_events();
	
	// Flush rewrite rules
	flush_rewrite_rules();
	
	/**
	 * Hook: aqualuxe_after_activation
	 */
	do_action( 'aqualuxe_after_activation' );
}

/**
 * Theme Deactivation Hook
 * Cleanup when theme is deactivated
 */
function aqualuxe_deactivation() {
	// Clear scheduled events
	aqualuxe_clear_scheduled_events();
	
	// Clean up temporary data
	aqualuxe_cleanup_temp_data();
	
	/**
	 * Hook: aqualuxe_after_deactivation
	 */
	do_action( 'aqualuxe_after_deactivation' );
}

/**
 * Set Default Theme Options
 */
function aqualuxe_set_default_options() {
	$defaults = [
		'aqualuxe_logo' => '',
		'aqualuxe_primary_color' => '#1e40af',
		'aqualuxe_secondary_color' => '#06b6d4',
		'aqualuxe_accent_color' => '#f59e0b',
		'aqualuxe_dark_mode' => 'auto',
		'aqualuxe_enable_animations' => true,
		'aqualuxe_enable_lazy_loading' => true,
		'aqualuxe_enable_caching' => true,
	];
	
	foreach ( $defaults as $option => $value ) {
		if ( get_theme_mod( $option ) === false ) {
			set_theme_mod( $option, $value );
		}
	}
}

/**
 * Create Database Tables
 * Create custom tables if needed for enterprise features
 */
function aqualuxe_create_tables() {
	// This would create custom tables for multi-tenancy, analytics, etc.
	// Implementation depends on specific enterprise requirements
}

/**
 * Schedule Cron Events
 */
function aqualuxe_schedule_events() {
	if ( ! wp_next_scheduled( 'aqualuxe_daily_maintenance' ) ) {
		wp_schedule_event( time(), 'daily', 'aqualuxe_daily_maintenance' );
	}
}

/**
 * Clear Scheduled Events
 */
function aqualuxe_clear_scheduled_events() {
	wp_clear_scheduled_hook( 'aqualuxe_daily_maintenance' );
}

/**
 * Cleanup Temporary Data
 */
function aqualuxe_cleanup_temp_data() {
	// Clean up any temporary files or cached data
}

/**
 * Daily Maintenance Task
 */
function aqualuxe_daily_maintenance() {
	// Perform daily maintenance tasks
	aqualuxe_cleanup_temp_data();
	
	// Clear expired transients
	delete_expired_transients();
	
	/**
	 * Hook: aqualuxe_daily_maintenance
	 */
	do_action( 'aqualuxe_daily_maintenance' );
}
add_action( 'aqualuxe_daily_maintenance', 'aqualuxe_daily_maintenance' );

// Hook theme activation/deactivation
add_action( 'after_switch_theme', 'aqualuxe_activation' );
add_action( 'switch_theme', 'aqualuxe_deactivation' );

// Initialize theme after WordPress is fully loaded
add_action( 'after_setup_theme', 'aqualuxe_init', 10 );

/**
 * Emergency Mode
 * Fallback functionality if core files fail to load
 */
if ( ! function_exists( 'aqualuxe_emergency_mode' ) ) {
	function aqualuxe_emergency_mode() {
		// Basic WordPress theme support
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );
		
		// Basic styles
		wp_enqueue_style( 'aqualuxe-style', get_stylesheet_uri(), array(), AQUALUXE_VERSION );
		
		// Emergency notice
		if ( is_admin() ) {
			add_action( 'admin_notices', function() {
				echo '<div class="notice notice-warning"><p>' . esc_html__( 'AquaLuxe theme is running in emergency mode. Some features may not be available.', 'aqualuxe' ) . '</p></div>';
			} );
		}
	}
	
	// Register emergency mode hook
	add_action( 'after_setup_theme', 'aqualuxe_emergency_mode', 999 );
}