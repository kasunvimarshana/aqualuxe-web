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

// Invalidate fragment caches when Services/Events change (best-effort, shared hosting friendly)
add_action('save_post_service', function(){
    global $wpdb; if (!isset($wpdb->options)) { return; }
    $like = $wpdb->esc_like('_transient_aqlx_sc_services_') . '%';
    // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
    $wpdb->query($wpdb->prepare("DELETE FROM {$wpdb->options} WHERE option_name LIKE %s", $like));
});
add_action('save_post_event', function(){
    global $wpdb; if (!isset($wpdb->options)) { return; }
    $like = $wpdb->esc_like('_transient_aqlx_sc_events_') . '%';
    // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
    $wpdb->query($wpdb->prepare("DELETE FROM {$wpdb->options} WHERE option_name LIKE %s", $like));
});
