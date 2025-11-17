<?php
/**
 * Simple test for AquaLuxe Demo Importer Media Import functionality
 * 
 * This is a basic functional test to verify the media import works correctly.
 * Run this from WordPress admin or via WP-CLI to test functionality.
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Test the media import functionality
 */
function aqualuxe_test_media_import() {
    // Check if demo importer class exists
    if ( ! class_exists( 'AquaLuxe_Demo_Importer' ) ) {
        return array(
            'success' => false,
            'message' => 'AquaLuxe_Demo_Importer class not found'
        );
    }

    $results = array();
    
    // Test 1: Check if required WordPress functions exist
    $required_functions = array( 'download_url', 'media_handle_sideload', 'wp_check_filetype' );
    foreach ( $required_functions as $function ) {
        if ( ! function_exists( $function ) ) {
            $results[] = array(
                'test' => "Function {$function} exists",
                'status' => 'FAIL',
                'message' => "Required function {$function} is not available"
            );
        } else {
            $results[] = array(
                'test' => "Function {$function} exists", 
                'status' => 'PASS',
                'message' => "Function {$function} is available"
            );
        }
    }
    
    // Test 2: Check if media upload directory is writable
    $upload_dir = wp_upload_dir();
    if ( ! wp_is_writable( $upload_dir['path'] ) ) {
        $results[] = array(
            'test' => 'Upload directory writable',
            'status' => 'FAIL', 
            'message' => 'Upload directory is not writable: ' . $upload_dir['path']
        );
    } else {
        $results[] = array(
            'test' => 'Upload directory writable',
            'status' => 'PASS',
            'message' => 'Upload directory is writable: ' . $upload_dir['path']
        );
    }
    
    // Test 3: Check if media_category taxonomy exists
    if ( ! taxonomy_exists( 'media_category' ) ) {
        $results[] = array(
            'test' => 'Media category taxonomy exists',
            'status' => 'FAIL',
            'message' => 'media_category taxonomy is not registered'
        );
    } else {
        $results[] = array(
            'test' => 'Media category taxonomy exists',
            'status' => 'PASS',
            'message' => 'media_category taxonomy is registered'
        );
    }
    
    // Test 4: Check URL accessibility (test with a small image)
    $test_url = 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80';
    $response = wp_remote_head( $test_url, array( 'timeout' => 10 ) );
    
    if ( is_wp_error( $response ) ) {
        $results[] = array(
            'test' => 'External URL accessibility',
            'status' => 'FAIL',
            'message' => 'Cannot access external URLs: ' . $response->get_error_message()
        );
    } else {
        $status_code = wp_remote_retrieve_response_code( $response );
        if ( $status_code === 200 ) {
            $results[] = array(
                'test' => 'External URL accessibility',
                'status' => 'PASS',
                'message' => 'External URLs are accessible'
            );
        } else {
            $results[] = array(
                'test' => 'External URL accessibility',
                'status' => 'FAIL',
                'message' => 'External URL returned status code: ' . $status_code
            );
        }
    }
    
    // Test 5: Check if demo importer module is loaded
    $theme_instance = AquaLuxe_Theme::get_instance();
    $demo_importer = $theme_instance->get_module( 'demo-importer' );
    
    if ( ! $demo_importer ) {
        $results[] = array(
            'test' => 'Demo importer module loaded',
            'status' => 'FAIL',
            'message' => 'Demo importer module is not loaded'
        );
    } else {
        $results[] = array(
            'test' => 'Demo importer module loaded',
            'status' => 'PASS',
            'message' => 'Demo importer module is loaded'
        );
    }
    
    // Test 6: Check JavaScript file exists
    $js_file = AQUALUXE_THEME_DIR . '/assets/dist/js/demo-importer.js';
    if ( ! file_exists( $js_file ) ) {
        $results[] = array(
            'test' => 'Demo importer JavaScript exists',
            'status' => 'FAIL',
            'message' => 'Demo importer JavaScript file not found'
        );
    } else {
        $results[] = array(
            'test' => 'Demo importer JavaScript exists',
            'status' => 'PASS',
            'message' => 'Demo importer JavaScript file exists'
        );
    }
    
    // Summary
    $passed = count( array_filter( $results, function( $test ) { return $test['status'] === 'PASS'; } ) );
    $total = count( $results );
    $failed = $total - $passed;
    
    return array(
        'success' => $failed === 0,
        'summary' => array(
            'total' => $total,
            'passed' => $passed,
            'failed' => $failed
        ),
        'results' => $results
    );
}

// Hook for admin AJAX testing
add_action( 'wp_ajax_aqualuxe_test_media_import', function() {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( 'Permission denied' );
    }
    
    $test_results = aqualuxe_test_media_import();
    wp_send_json_success( $test_results );
} );

// Hook for CLI testing
if ( defined( 'WP_CLI' ) && WP_CLI ) {
    WP_CLI::add_command( 'aqualuxe test-media-import', function() {
        $results = aqualuxe_test_media_import();
        
        WP_CLI::line( 'AquaLuxe Media Import Test Results:' );
        WP_CLI::line( '====================================' );
        
        foreach ( $results['results'] as $test ) {
            $status_color = $test['status'] === 'PASS' ? '%G' : '%R';
            WP_CLI::line( sprintf( 
                '%s[%s]%s %s - %s', 
                $status_color, 
                $test['status'], 
                '%n', 
                $test['test'], 
                $test['message'] 
            ) );
        }
        
        WP_CLI::line( '' );
        WP_CLI::line( sprintf( 
            'Summary: %d/%d tests passed (%d failed)', 
            $results['summary']['passed'], 
            $results['summary']['total'], 
            $results['summary']['failed'] 
        ) );
        
        if ( ! $results['success'] ) {
            WP_CLI::error( 'Some tests failed!' );
        } else {
            WP_CLI::success( 'All tests passed!' );
        }
    } );
}