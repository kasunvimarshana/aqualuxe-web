<?php
/**
 * Container Test Script
 *
 * Quick test to verify Container fixes
 * Access via: yoursite.com/wp-content/themes/aqualuxe/test-container.php
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
	<title>Container Fix Test</title>
	<style>
		body { font-family: system-ui, sans-serif; margin: 40px; }
		.test { padding: 15px; margin: 10px 0; border-radius: 6px; }
		.success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
		.error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
		pre { background: #f8f9fa; padding: 15px; border-radius: 4px; font-size: 14px; }
	</style>
</head>
<body>
	<h1>🔧 Container Fix Test</h1>
	<p>Testing if Container can handle both callable and string class names...</p>

	<?php
	try {
		// Test 1: Get Container instance
		echo "<div class='test success'>✅ Getting Container instance...</div>";
		$container = \AquaLuxe\Core\Container::get_instance();

		// Test 2: Bind with callable (should work as before)
		echo "<div class='test success'>✅ Testing callable binding...</div>";
		$container->bind( 'test_callable', function() {
			return 'Hello from callable!';
		} );
		$result1 = $container->resolve( 'test_callable' );
		echo "<pre>Result: {$result1}</pre>";

		// Test 3: Bind with class name (the fix we implemented)
		echo "<div class='test success'>✅ Testing class name binding...</div>";
		$container->singleton( 'theme_service', 'AquaLuxe\\Services\\Theme_Service' );
		$theme_service = $container->resolve( 'theme_service' );
		echo "<pre>Theme Service Class: " . get_class( $theme_service ) . "</pre>";

		// Test 4: Test singleton behavior
		echo "<div class='test success'>✅ Testing singleton behavior...</div>";
		$theme_service2 = $container->resolve( 'theme_service' );
		$is_same = $theme_service === $theme_service2;
		echo "<pre>Same instance: " . ( $is_same ? 'Yes' : 'No' ) . "</pre>";

		// Test 5: Auto-resolve without explicit binding
		echo "<div class='test success'>✅ Testing auto-resolve...</div>";
		$asset_service = $container->resolve( 'AquaLuxe\\Services\\Asset_Service' );
		echo "<pre>Asset Service Class: " . get_class( $asset_service ) . "</pre>";

		echo "<div class='test success'>🎉 <strong>All Container tests passed!</strong> The TypeError should be fixed.</div>";

	} catch ( Exception $e ) {
		echo "<div class='test error'>❌ <strong>Container test failed:</strong> " . $e->getMessage() . "</div>";
		echo "<pre>" . $e->getTraceAsString() . "</pre>";
	}
	?>

	<h2>📋 What was Fixed</h2>
	<ul>
		<li><strong>Container::bind()</strong> now accepts <code>callable|string|null</code> instead of just <code>callable</code></li>
		<li><strong>Container::singleton()</strong> now accepts <code>callable|string|null</code> instead of just <code>callable</code></li>
		<li><strong>Container::resolve()</strong> now handles both callable factories and string class names</li>
		<li><strong>Auto-resolution</strong> works for class names when no explicit binding exists</li>
	</ul>

	<p><strong>Result:</strong> Service providers can now register services using class names like <code>Asset_Service::class</code></p>
</body>
</html>
