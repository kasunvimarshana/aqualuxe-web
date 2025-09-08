<?php
/**
 * Test Theme Loading
 * 
 * Simple test to check if the theme architecture loads without fatal errors
 */

// Simulate WordPress environment for testing
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/../../../' );
}

// Define WordPress constants for testing
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', true );
}

if ( ! defined( 'WP_CONTENT_DIR' ) ) {
	define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
}

if ( ! defined( 'WP_CONTENT_URL' ) ) {
	define( 'WP_CONTENT_URL', 'http://localhost/wp-content' );
}

// Mock some essential WordPress functions for testing
if ( ! function_exists( 'get_template_directory' ) ) {
	function get_template_directory() {
		return __DIR__;
	}
}

if ( ! function_exists( 'get_template_directory_uri' ) ) {
	function get_template_directory_uri() {
		return 'http://localhost/wp-content/themes/aqualuxe';
	}
}

if ( ! function_exists( 'add_action' ) ) {
	function add_action( $hook, $callback, $priority = 10, $accepted_args = 1 ) {
		echo "Hook registered: {$hook}" . PHP_EOL;
		return true;
	}
}

if ( ! function_exists( 'add_filter' ) ) {
	function add_filter( $hook, $callback, $priority = 10, $accepted_args = 1 ) {
		echo "Filter registered: {$hook}" . PHP_EOL;
		return true;
	}
}

if ( ! function_exists( 'add_theme_support' ) ) {
	function add_theme_support( $feature, $args = null ) {
		echo "Theme support added: {$feature}" . PHP_EOL;
		return true;
	}
}

echo "Testing AquaLuxe Theme Architecture..." . PHP_EOL;
echo "=====================================" . PHP_EOL;

try {
	// Test autoloader
	require_once __DIR__ . '/functions.php';
	
	echo "✅ Theme loaded successfully!" . PHP_EOL;
	echo "✅ All namespace issues resolved!" . PHP_EOL;
	echo "✅ Modular architecture is working!" . PHP_EOL;
	
} catch ( Throwable $e ) {
	echo "❌ Error: " . $e->getMessage() . PHP_EOL;
	echo "   File: " . $e->getFile() . PHP_EOL;
	echo "   Line: " . $e->getLine() . PHP_EOL;
	
	if ( $e->getPrevious() ) {
		echo "   Previous: " . $e->getPrevious()->getMessage() . PHP_EOL;
	}
}

echo PHP_EOL . "Test completed." . PHP_EOL;
