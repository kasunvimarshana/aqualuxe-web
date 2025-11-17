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

// Disable emojis
add_action('init', static function (): void {
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
});

// Remove oEmbed and REST links from head for non-admin views
add_action('init', static function (): void {
    if (is_admin()) { return; }
    remove_action('wp_head', 'rest_output_link_wp_head', 10);
    remove_action('wp_head', 'wp_oembed_add_discovery_links', 10);
    remove_action('wp_head', 'wp_oembed_add_host_js');
});
