<?php
// Performance tweaks kept minimal and local
// No external preconnects/CDNs to comply with policy
// Security headers compatibility (sent via PHP for non-proxy envs)
\add_action('send_headers', function(){
    if (!headers_sent()) {
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: SAMEORIGIN');
        header('Referrer-Policy: no-referrer-when-downgrade');
    }
});
