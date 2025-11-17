<?php
/**
 * AquaLuxe Theme Quick Test
 * 
 * This script tests if the theme loads without fatal errors
 * 
 * @package AquaLuxe
 * @since 1.2.0
 */

// Output headers for JSON response
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Initialize basic WordPress if not already loaded
if (!defined('ABSPATH')) {
    // Attempt to find wp-config.php
    $wp_config_paths = [
        dirname(__FILE__) . '/../../../../wp-config.php',
        dirname(__FILE__) . '/../../../../../wp-config.php',
    ];
    
    foreach ($wp_config_paths as $config_path) {
        if (file_exists($config_path)) {
            define('WP_USE_THEMES', false);
            require_once($config_path);
            break;
        }
    }
}

$response = [
    'status' => 'success',
    'timestamp' => date('Y-m-d H:i:s'),
    'theme_name' => 'AquaLuxe',
    'version' => '1.2.0',
    'tests' => []
];

try {
    // Test 1: Basic WordPress functions
    $response['tests']['wordpress_loaded'] = function_exists('wp_head') ? 'PASS' : 'FAIL';
    
    // Test 2: Theme constants
    $response['tests']['theme_constants'] = defined('AQUALUXE_THEME_VERSION') ? 'PASS' : 'FAIL';
    
    // Test 3: Core classes
    $core_classes = [
        'Application' => '\AquaLuxe\Core\Application',
        'Core_Loader' => '\AquaLuxe\Core\Core_Loader',
        'Theme_Setup' => '\AquaLuxe\Core\Theme_Setup',
        'Theme_Scripts' => '\AquaLuxe\Core\Theme_Scripts',
    ];
    
    foreach ($core_classes as $name => $class) {
        $response['tests']["class_{$name}"] = class_exists($class) ? 'PASS' : 'FAIL';
    }
    
    // Test 4: Application instance
    if (class_exists('\AquaLuxe\Core\Application')) {
        try {
            $app = \AquaLuxe\Core\Application::get_instance();
            $response['tests']['application_instance'] = 'PASS';
        } catch (Exception $e) {
            $response['tests']['application_instance'] = 'FAIL: ' . $e->getMessage();
        }
    } else {
        $response['tests']['application_instance'] = 'FAIL: Application class not found';
    }
    
    // Test 5: Asset files
    $theme_dir = get_template_directory();
    $asset_files = [
        'main_css' => 'assets/dist/css/main.css',
        'app_js' => 'assets/dist/js/app.js',
        'style_css' => 'style.css',
    ];
    
    foreach ($asset_files as $name => $path) {
        $full_path = $theme_dir . '/' . $path;
        $response['tests']["asset_{$name}"] = file_exists($full_path) ? 'PASS' : 'FAIL';
    }
    
    // Test 6: WooCommerce integration (conditional)
    if (class_exists('WooCommerce')) {
        $response['tests']['woocommerce_available'] = 'PASS';
        $response['tests']['woocommerce_functions'] = function_exists('is_shop') ? 'PASS' : 'FAIL';
    } else {
        $response['tests']['woocommerce_available'] = 'SKIP';
        $response['tests']['woocommerce_functions'] = 'SKIP';
    }
    
    // Count results
    $passes = 0;
    $fails = 0;
    $skips = 0;
    
    foreach ($response['tests'] as $result) {
        if ($result === 'PASS') $passes++;
        elseif (strpos($result, 'FAIL') === 0) $fails++;
        elseif ($result === 'SKIP') $skips++;
    }
    
    $response['summary'] = [
        'total_tests' => count($response['tests']),
        'passed' => $passes,
        'failed' => $fails,
        'skipped' => $skips,
        'success_rate' => $passes / (count($response['tests']) - $skips) * 100
    ];
    
    if ($fails > 0) {
        $response['status'] = 'warning';
        $response['message'] = "Theme loaded with {$fails} failed tests";
    } else {
        $response['message'] = "Theme loaded successfully - all tests passed!";
    }
    
} catch (Exception $e) {
    $response['status'] = 'error';
    $response['message'] = 'Fatal error during testing: ' . $e->getMessage();
    $response['error'] = [
        'message' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
        'trace' => $e->getTraceAsString()
    ];
}

// Output the response
echo json_encode($response, JSON_PRETTY_PRINT);
exit;
?>
