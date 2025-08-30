<?php
/**
 * Plugin Name: Disable SSL Verification
 * Description: Disables SSL verification for WordPress HTTP requests
 * Version: 1.0
 */

// Disable SSL verification for all HTTP requests
add_filter('https_ssl_verify', '__return_false');
add_filter('https_local_ssl_verify', '__return_false');

// Also disable SSL verification for wp_remote_get and wp_remote_post
add_filter('http_request_args', function($args, $url) {
    $args['sslverify'] = false;
    return $args;
}, 10, 2);

// Disable SSL verification for cron jobs
add_filter('cron_request', function($cron_request_array) {
    $cron_request_array['args']['sslverify'] = false;
    return $cron_request_array;
});
