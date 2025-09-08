<?php
/**
 * AquaLuxe Architecture Test
 *
 * Simple test script to verify the modular architecture
 *
 * @package AquaLuxe
 * @since   1.2.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Test the modular architecture
 *
 * @return array Test results
 */
function aqualuxe_test_architecture() {
	$results = [];

	// Test 1: Application instance
	try {
		$app = \AquaLuxe\Core\Application::get_instance();
		$results['application'] = [
			'status' => 'success',
			'message' => 'Application instance created successfully',
			'version' => $app->version(),
		];
	} catch ( Exception $e ) {
		$results['application'] = [
			'status' => 'error',
			'message' => 'Failed to create application instance: ' . $e->getMessage(),
		];
	}

	// Test 2: Container instance
	try {
		$container = \AquaLuxe\Core\Container::get_instance();
		$results['container'] = [
			'status' => 'success',
			'message' => 'Container instance created successfully',
		];
	} catch ( Exception $e ) {
		$results['container'] = [
			'status' => 'error',
			'message' => 'Failed to create container instance: ' . $e->getMessage(),
		];
	}

	// Test 3: Service binding and resolution
	try {
		$container = \AquaLuxe\Core\Container::get_instance();
		
		// Test binding a simple service
		$container->bind( 'test_service', function() {
			return 'Test service works!';
		} );
		
		$resolved = $container->resolve( 'test_service' );
		
		$results['service_resolution'] = [
			'status' => 'success',
			'message' => 'Service binding and resolution working',
			'resolved_value' => $resolved,
		];
	} catch ( Exception $e ) {
		$results['service_resolution'] = [
			'status' => 'error',
			'message' => 'Service resolution failed: ' . $e->getMessage(),
		];
	}

	// Test 4: Autoloader
	try {
		// Test if autoloader can find classes
		$class_exists = [
			'Application' => class_exists( '\\AquaLuxe\\Core\\Application' ),
			'Container' => class_exists( '\\AquaLuxe\\Core\\Container' ),
			'Service_Provider_Interface' => interface_exists( '\\AquaLuxe\\Core\\Contracts\\Service_Provider_Interface' ),
			'Abstract_Service_Provider' => class_exists( '\\AquaLuxe\\Core\\Abstract_Service_Provider' ),
		];
		
		$results['autoloader'] = [
			'status' => 'success',
			'message' => 'Autoloader working correctly',
			'classes_found' => $class_exists,
		];
	} catch ( Exception $e ) {
		$results['autoloader'] = [
			'status' => 'error',
			'message' => 'Autoloader test failed: ' . $e->getMessage(),
		];
	}

	return $results;
}

/**
 * Display test results in admin
 */
function aqualuxe_display_architecture_test() {
	if ( ! is_admin() || ! current_user_can( 'manage_options' ) ) {
		return;
	}

	if ( isset( $_GET['aqualuxe_test'] ) && $_GET['aqualuxe_test'] === 'architecture' ) {
		$results = aqualuxe_test_architecture();
		
		echo '<div class="notice notice-info">';
		echo '<h2>AquaLuxe Architecture Test Results</h2>';
		
		foreach ( $results as $test_name => $result ) {
			$status_class = $result['status'] === 'success' ? 'notice-success' : 'notice-error';
			echo "<div class='notice {$status_class}'>";
			echo "<h3>" . ucfirst( str_replace( '_', ' ', $test_name ) ) . "</h3>";
			echo "<p>{$result['message']}</p>";
			
			if ( isset( $result['version'] ) ) {
				echo "<p><strong>Version:</strong> {$result['version']}</p>";
			}
			
			if ( isset( $result['resolved_value'] ) ) {
				echo "<p><strong>Resolved Value:</strong> {$result['resolved_value']}</p>";
			}
			
			if ( isset( $result['classes_found'] ) ) {
				echo "<p><strong>Classes Found:</strong></p><ul>";
				foreach ( $result['classes_found'] as $class => $found ) {
					$status = $found ? '✅' : '❌';
					echo "<li>{$status} {$class}</li>";
				}
				echo "</ul>";
			}
			
			echo "</div>";
		}
		
		echo '</div>';
	}
}

// Hook the test display to admin notices
add_action( 'admin_notices', 'aqualuxe_display_architecture_test' );

/**
 * Add a test link to admin bar
 */
function aqualuxe_add_test_link( $wp_admin_bar ) {
	if ( ! is_admin() || ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$wp_admin_bar->add_node( [
		'id' => 'aqualuxe-test',
		'title' => 'Test AquaLuxe Architecture',
		'href' => admin_url( '?aqualuxe_test=architecture' ),
	] );
}

add_action( 'admin_bar_menu', 'aqualuxe_add_test_link', 100 );
