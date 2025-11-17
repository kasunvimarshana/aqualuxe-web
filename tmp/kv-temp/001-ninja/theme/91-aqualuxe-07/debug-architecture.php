<?php
/**
 * Debug Architecture Status
 *
 * Simple script to test if the new architecture is working
 * Access via: yoursite.com/wp-content/themes/aqualuxe/debug-architecture.php
 *
 * @package AquaLuxe
 * @since   1.2.0
 */

// Load WordPress
$wp_load_path = '../../../wp-load.php';
if ( file_exists( $wp_load_path ) ) {
	require_once $wp_load_path;
} else {
	die( 'WordPress not found. Please check the path.' );
}

// Only allow in debug mode
if ( ! defined( 'WP_DEBUG' ) || ! WP_DEBUG ) {
	die( 'Debug mode is not enabled.' );
}

// Set content type
header( 'Content-Type: text/html; charset=utf-8' );

?>
<!DOCTYPE html>
<html>
<head>
	<title>AquaLuxe Architecture Debug</title>
	<style>
		body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; margin: 40px; }
		.status { padding: 10px; margin: 10px 0; border-radius: 4px; }
		.success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
		.error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
		.info { background: #cce7ff; color: #004085; border: 1px solid #bee5eb; }
		pre { background: #f8f9fa; padding: 15px; border-radius: 4px; overflow: auto; }
		.test-section { margin: 20px 0; border: 1px solid #dee2e6; padding: 20px; border-radius: 8px; }
	</style>
</head>
<body>
	<h1>AquaLuxe Architecture Debug Report</h1>
	<p>Generated: <?php echo date( 'Y-m-d H:i:s' ); ?></p>

	<div class="test-section">
		<h2>1. Class Autoloader Test</h2>
		<?php
		$classes_to_test = [
			'AquaLuxe\\Core\\Application',
			'AquaLuxe\\Core\\Container',
			'AquaLuxe\\Core\\Contracts\\Service_Provider_Interface',
			'AquaLuxe\\Core\\Abstract_Service_Provider',
			'AquaLuxe\\Services\\Theme_Service',
			'AquaLuxe\\Services\\Asset_Service',
			'AquaLuxe\\Providers\\Theme_Service_Provider',
		];

		foreach ( $classes_to_test as $class ) {
			if ( class_exists( $class ) || interface_exists( $class ) ) {
				echo "<div class='status success'>✅ {$class} - Found</div>";
			} else {
				echo "<div class='status error'>❌ {$class} - Not Found</div>";
			}
		}
		?>
	</div>

	<div class="test-section">
		<h2>2. Application Instance Test</h2>
		<?php
		try {
			$app = \AquaLuxe\Core\Application::get_instance();
			echo "<div class='status success'>✅ Application instance created successfully</div>";
			echo "<div class='status info'>Version: " . $app->version() . "</div>";
			echo "<div class='status info'>Debug Mode: " . ( $app->is_debug() ? 'Yes' : 'No' ) . "</div>";
		} catch ( Exception $e ) {
			echo "<div class='status error'>❌ Application instance failed: " . $e->getMessage() . "</div>";
		}
		?>
	</div>

	<div class="test-section">
		<h2>3. Container Test</h2>
		<?php
		try {
			$container = \AquaLuxe\Core\Container::get_instance();
			echo "<div class='status success'>✅ Container instance created successfully</div>";
			
			// Test service binding
			$container->bind( 'test_service', function() {
				return 'Hello from the container!';
			} );
			
			$result = $container->resolve( 'test_service' );
			echo "<div class='status success'>✅ Service binding/resolution works: {$result}</div>";
		} catch ( Exception $e ) {
			echo "<div class='status error'>❌ Container test failed: " . $e->getMessage() . "</div>";
		}
		?>
	</div>

	<div class="test-section">
		<h2>4. Service Provider Test</h2>
		<?php
		try {
			$app = \AquaLuxe\Core\Application::get_instance();
			
			// Try to resolve a service that should be registered
			$theme_service = $app->resolve( 'theme' );
			if ( $theme_service instanceof \AquaLuxe\Services\Theme_Service ) {
				echo "<div class='status success'>✅ Theme service resolved successfully</div>";
			} else {
				echo "<div class='status error'>❌ Theme service not properly registered</div>";
			}
		} catch ( Exception $e ) {
			echo "<div class='status error'>❌ Service provider test failed: " . $e->getMessage() . "</div>";
		}
		?>
	</div>

	<div class="test-section">
		<h2>5. Configuration Test</h2>
		<?php
		try {
			$app = \AquaLuxe\Core\Application::get_instance();
			$debug_config = $app->config( 'debug' );
			$theme_name = $app->config( 'name', 'Unknown' );
			
			echo "<div class='status success'>✅ Configuration system working</div>";
			echo "<div class='status info'>Debug mode: " . ( $debug_config ? 'Yes' : 'No' ) . "</div>";
			echo "<div class='status info'>Theme name: {$theme_name}</div>";
		} catch ( Exception $e ) {
			echo "<div class='status error'>❌ Configuration test failed: " . $e->getMessage() . "</div>";
		}
		?>
	</div>

	<div class="test-section">
		<h2>6. Helper Functions Test</h2>
		<?php
		if ( function_exists( 'aqualuxe' ) ) {
			try {
				$app_helper = aqualuxe();
				echo "<div class='status success'>✅ aqualuxe() helper function works</div>";
			} catch ( Exception $e ) {
				echo "<div class='status error'>❌ aqualuxe() helper failed: " . $e->getMessage() . "</div>";
			}
		} else {
			echo "<div class='status error'>❌ aqualuxe() helper function not found</div>";
		}

		if ( function_exists( 'aqualuxe_resolve' ) ) {
			echo "<div class='status success'>✅ aqualuxe_resolve() helper function found</div>";
		} else {
			echo "<div class='status error'>❌ aqualuxe_resolve() helper function not found</div>";
		}

		if ( function_exists( 'aqualuxe_config' ) ) {
			echo "<div class='status success'>✅ aqualuxe_config() helper function found</div>";
		} else {
			echo "<div class='status error'>❌ aqualuxe_config() helper function not found</div>";
		}
		?>
	</div>

	<div class="test-section">
		<h2>7. WordPress Integration Test</h2>
		<?php
		// Check if theme is active
		$current_theme = wp_get_theme();
		if ( $current_theme->get( 'Name' ) === 'AquaLuxe' || $current_theme->get_template() === 'aqualuxe' ) {
			echo "<div class='status success'>✅ AquaLuxe theme is active</div>";
		} else {
			echo "<div class='status info'>ℹ️ Current theme: " . $current_theme->get( 'Name' ) . "</div>";
		}

		// Check WordPress version
		echo "<div class='status info'>WordPress Version: " . get_bloginfo( 'version' ) . "</div>";
		echo "<div class='status info'>PHP Version: " . PHP_VERSION . "</div>";
		?>
	</div>

	<div class="test-section">
		<h2>8. File Structure Check</h2>
		<?php
		$required_files = [
			'functions.php',
			'core/class_application.php',
			'core/class_container.php',
			'core/contracts/interface_service_provider.php',
			'services/class_theme_service.php',
			'providers/class_theme_service_provider.php',
			'config/app.php',
			'config/theme.php',
		];

		$theme_dir = get_template_directory();
		foreach ( $required_files as $file ) {
			$file_path = $theme_dir . '/' . $file;
			if ( file_exists( $file_path ) ) {
				echo "<div class='status success'>✅ {$file} - exists</div>";
			} else {
				echo "<div class='status error'>❌ {$file} - missing</div>";
			}
		}
		?>
	</div>

	<div class="test-section">
		<h2>9. Error Log Check</h2>
		<?php
		$log_file = ABSPATH . 'wp-content/debug.log';
		if ( file_exists( $log_file ) ) {
			$log_content = file_get_contents( $log_file );
			$aqualuxe_errors = array_filter( 
				explode( "\n", $log_content ), 
				function( $line ) {
					return strpos( $line, 'AquaLuxe' ) !== false || strpos( $line, 'aqualuxe' ) !== false;
				}
			);

			if ( empty( $aqualuxe_errors ) ) {
				echo "<div class='status success'>✅ No AquaLuxe-related errors in log</div>";
			} else {
				echo "<div class='status error'>❌ Found " . count( $aqualuxe_errors ) . " AquaLuxe-related errors</div>";
				echo "<pre>" . implode( "\n", array_slice( $aqualuxe_errors, -5 ) ) . "</pre>";
			}
		} else {
			echo "<div class='status info'>ℹ️ Debug log file not found</div>";
		}
		?>
	</div>

	<p><strong>Debug completed.</strong> If all tests show green checkmarks, your AquaLuxe modular architecture is working correctly!</p>
</body>
</html>
