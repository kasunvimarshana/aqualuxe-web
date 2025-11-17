<?php
/** Security hardening */

// Disable file edit in admin
if (!defined('DISALLOW_FILE_EDIT')) {
    define('DISALLOW_FILE_EDIT', true);
}

// Content Security Policy header (adjust as needed)
add_action('send_headers', function () {
    header('X-Content-Type-Options: nosniff', true);
    header('X-Frame-Options: SAMEORIGIN', true);
    header('Referrer-Policy: strict-origin-when-cross-origin', true);
    header('X-XSS-Protection: 0', true); // modern browsers use CSP
    // Lightweight CSP default (adjust per project). Avoid blocking inline WP-admin scripts.
    if (!is_user_logged_in()) {
        $csp = "default-src 'self'; img-src 'self' data: https:; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline'; font-src 'self' data: https:";
        $csp = apply_filters('aqualuxe/security_csp', $csp);
        if (is_string($csp) && $csp !== '') {
            header('Content-Security-Policy: ' . $csp, true);
        }
    }
});

// Disable XML-RPC to reduce attack surface (keep WordPress.com if needed)
add_filter('xmlrpc_enabled', '__return_false');

// Hide WP version in headers
remove_action('wp_head', 'wp_generator');

// Sanitize allowed HTML globally helper
function aqualuxe_allowed_html() {
    return [
        'a' => [ 'href'=>[], 'title'=>[], 'class'=>[], 'rel'=>[], 'target'=>[] ],
        'span' => [ 'class'=>[] ],
        'strong' => [],
        'em' => [],
        'p' => [ 'class'=>[] ],
        'ul' => [ 'class'=>[] ],
        'ol' => [ 'class'=>[] ],
        'li' => [ 'class'=>[] ],
        'img' => [ 'src'=>[], 'alt'=>[], 'class'=>[], 'loading'=>[], 'width'=>[], 'height'=>[] ],
        'h1' => [ 'class'=>[] ],
        'h2' => [ 'class'=>[] ],
        'h3' => [ 'class'=>[] ],
        'h4' => [ 'class'=>[] ],
        'h5' => [ 'class'=>[] ],
        'h6' => [ 'class'=>[] ],
    ];
}

/** Return the default CSP string before filters */
function aqualuxe_default_csp(): string {
    return "default-src 'self'; img-src 'self' data: https:; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline'; font-src 'self' data: https:";
}
