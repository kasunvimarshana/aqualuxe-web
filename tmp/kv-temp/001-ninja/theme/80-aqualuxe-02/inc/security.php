<?php
/** Security hardening */

// Disable file edit in admin
if (!defined('DISALLOW_FILE_EDIT')) {
    define('DISALLOW_FILE_EDIT', true);
}

// Content Security Policy header (adjust as needed)
add_action('send_headers', function () {
    header("X-Content-Type-Options: nosniff");
    header("X-Frame-Options: SAMEORIGIN");
    header("Referrer-Policy: strict-origin-when-cross-origin");
});

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
