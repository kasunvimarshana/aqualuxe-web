<?php
namespace AquaLuxe\Core;

class Security {
    public static function init() : void {
        // Disable file editing
        if (!defined('DISALLOW_FILE_EDIT')) {
            define('DISALLOW_FILE_EDIT', true);
        }

        // Remove x-pingback header
        add_filter('wp_headers', function ($headers) {
            unset($headers['X-Pingback']);
            return $headers;
        });

        // Sanitize comment cookies
        add_filter('comment_cookie_lifetime', function ($l) { return 0; });

        // Content Security Policy basic (can be extended in server config)
        add_action('send_headers', function () {
            header("Permissions-Policy: interest-cohort=()");
            header("X-Content-Type-Options: nosniff");
            header("Referrer-Policy: strict-origin-when-cross-origin");
        });
    }

    public static function sanitize_text($val) : string {
        return sanitize_text_field($val);
    }

    public static function esc_html($val) : string {
        return esc_html($val);
    }
}
