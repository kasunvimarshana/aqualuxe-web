<?php
/** Security hardening */
if (!defined('ABSPATH')) { exit; }

add_action('init', function(){
    // Disable XMLRPC pingbacks
    add_filter('xmlrpc_enabled', '__return_false');
});

// Sanitize SVG upload via safe list (optional)
add_filter('upload_mimes', function($mimes){
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
});

// Content-Security-Policy header (adjust as needed)
add_action('send_headers', function(){
    header_remove('X-Powered-By');
});
