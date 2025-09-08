<?php
/**
 * AquaLuxe Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package AquaLuxe
 * @since   1.2.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define theme constants
define( 'AQUALUXE_THEME_VERSION', '1.2.0' );
define( 'AQUALUXE_VERSION', '1.2.0' );
define( 'AQUALUXE_THEME_DIR', get_template_directory() );
define( 'AQUALUXE_THEME_URL', get_template_directory_uri() );
define( 'AQUALUXE_THEME_URI', trailingslashit( get_template_directory_uri() ) );

// Autoloader for new architecture
spl_autoload_register( function( $class ) {
	// Check if it's an AquaLuxe class
	if ( strpos( $class, 'AquaLuxe\\' ) !== 0 ) {
		return;
	}

	// Convert namespace to file path
	$class_path = str_replace( 'AquaLuxe\\', '', $class );
	$class_path = str_replace( '\\', '/', $class_path );
	
	// Get the base class name
	$base_name = basename( $class_path );
	$directory = dirname( $class_path );
	
	// Map namespace directories to actual directories
	$namespace_map = [
		'Core' => 'core',
		'Core/Contracts' => 'core/contracts',
		'Services' => 'services',
		'Providers' => 'providers',
		'Modules' => 'modules',
	];
	
	$mapped_dir = $namespace_map[ $directory ] ?? strtolower( $directory );
	
	// Determine file name based on type (interface, abstract class, or regular class)
	if ( strpos( $base_name, '_Interface' ) !== false ) {
		// Interface file
		$file_name = 'interface_' . strtolower( preg_replace( '/([a-z])([A-Z])/', '$1_$2', str_replace( '_Interface', '', $base_name ) ) ) . '.php';
	} elseif ( strpos( $base_name, 'Abstract_' ) === 0 ) {
		// Abstract class file
		$file_name = 'class_' . strtolower( preg_replace( '/([a-z])([A-Z])/', '$1_$2', $base_name ) ) . '.php';
	} else {
		// Regular class file
		$file_name = 'class_' . strtolower( preg_replace( '/([a-z])([A-Z])/', '$1_$2', $base_name ) ) . '.php';
	}
	
	$file_path = AQUALUXE_THEME_DIR . '/' . $mapped_dir . '/' . $file_name;
	
	if ( file_exists( $file_path ) ) {
		require_once $file_path;
	}
} );

// Load core dependencies first
require_once AQUALUXE_THEME_DIR . '/core/contracts/interface_service_provider.php';
require_once AQUALUXE_THEME_DIR . '/core/class_container.php';
require_once AQUALUXE_THEME_DIR . '/core/class_abstract_service_provider.php';
require_once AQUALUXE_THEME_DIR . '/core/class_application.php';

// Load legacy core loader for backward compatibility
require_once AQUALUXE_THEME_DIR . '/core/class_core_loader.php';

// Load architecture test (development only)
if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
	require_once AQUALUXE_THEME_DIR . '/core/class_architecture_test.php';
}

/**
 * Initialize the new modular application
 *
 * @since 1.2.0
 */
function aqualuxe_init_application() {
	try {
		// Get application instance
		$app = \AquaLuxe\Core\Application::get_instance();
		
		// Initialize with configuration
		$app->initialize( [
			'debug' => defined( 'WP_DEBUG' ) && WP_DEBUG,
			'version' => AQUALUXE_THEME_VERSION,
			'multisite' => is_multisite(),
		] );
		
		// Boot the application
		$app->boot();
		
		// Initialize legacy loader for compatibility
		new \AquaLuxe\Core\Core_Loader();
		
	} catch ( Exception $e ) {
		// Fallback to legacy theme initialization
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			error_log( 'AquaLuxe Application Error: ' . $e->getMessage() );
		}
		
		// Load legacy theme class as fallback
		if ( file_exists( AQUALUXE_THEME_DIR . '/core/class_aqualuxe_theme.php' ) ) {
			require_once AQUALUXE_THEME_DIR . '/core/class_aqualuxe_theme.php';
			\AquaLuxe\Core\AquaLuxe_Theme::instance();
		}
	}
}

// Initialize after WordPress is fully loaded
add_action( 'after_setup_theme', 'aqualuxe_init_application', 5 );

/**
 * Helper function to get application instance
 *
 * @since 1.2.0
 * @return \AquaLuxe\Core\Application
 */
function aqualuxe() {
	return \AquaLuxe\Core\Application::get_instance();
}

/**
 * Helper function to resolve services from container
 *
 * @since 1.2.0
 * @param string $service Service identifier
 * @return mixed
 */
function aqualuxe_resolve( $service ) {
	return aqualuxe()->resolve( $service );
}

/**
 * Helper function to get theme configuration
 *
 * @since 1.2.0
 * @param string $key Configuration key
 * @param mixed $default Default value
 * @return mixed
 */
function aqualuxe_config( $key, $default = null ) {
	return aqualuxe()->config( $key, $default );
}
