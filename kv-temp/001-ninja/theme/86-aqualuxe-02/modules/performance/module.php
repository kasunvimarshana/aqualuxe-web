<?php
// Add small performance hints
\add_action('wp_head', function(){
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
});
// Security headers compatibility (sent via PHP for non-proxy envs)
\add_action('send_headers', function(){
    if (!headers_sent()) {
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: SAMEORIGIN');
        header('Referrer-Policy: no-referrer-when-downgrade');
    }
});
