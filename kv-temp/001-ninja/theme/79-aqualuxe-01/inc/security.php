<?php
declare(strict_types=1);

namespace Aqualuxe\Inc;

// Remove WP version
add_filter('the_generator', '__return_empty_string');

// Security headers
add_action('send_headers', static function (): void {
    header('X-Frame-Options: SAMEORIGIN');
    header('X-Content-Type-Options: nosniff');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('X-XSS-Protection: 1; mode=block');
});

// Disable XML-RPC if not needed
add_filter('xmlrpc_enabled', '__return_false');
