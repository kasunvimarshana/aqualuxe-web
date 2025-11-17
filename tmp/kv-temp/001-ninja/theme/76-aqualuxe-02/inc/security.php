<?php
defined('ABSPATH') || exit;

// Disable XML-RPC by default
add_filter('xmlrpc_enabled', '__return_false');

// Remove WP version
add_filter('the_generator', '__return_empty_string');

// Sanitize comment author URL
add_filter('pre_option_default_comment_status', function ($value) {
    return 'closed';
});

// Content-Security-Policy header (adjust as needed)
add_action('send_headers', function () {
    header("X-Content-Type-Options: nosniff");
    header("X-Frame-Options: SAMEORIGIN");
    header("Referrer-Policy: strict-origin-when-cross-origin");
});
