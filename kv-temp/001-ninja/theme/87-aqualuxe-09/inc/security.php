<?php
/** Security hardening for theme. */

// Disable file editing through WP admin
if (!defined('DISALLOW_FILE_EDIT')) {
    define('DISALLOW_FILE_EDIT', true);
}

// Sanitize array recursively
function aqualuxe_recursive_sanitize($data)
{
    if (is_array($data)) {
        return array_map('aqualuxe_recursive_sanitize', $data);
    }
    return is_scalar($data) ? sanitize_text_field((string) $data) : '';
}

// Output nonce hidden field
function aqualuxe_nonce_field(string $action, string $name = '_aqualuxe_nonce'): void
{
    wp_nonce_field($action, $name);
}

// Verify nonce
function aqualuxe_verify_nonce(string $action, string $name = '_aqualuxe_nonce'): bool
{
    $nonce = isset($_POST[$name])
        ? (function_exists('wp_unslash') ? sanitize_text_field(call_user_func('wp_unslash', $_POST[$name])) : sanitize_text_field((string) $_POST[$name]))
        : '';
    return function_exists('wp_verify_nonce') ? (bool) call_user_func('wp_verify_nonce', $nonce, $action) : ($nonce !== '' && $action !== '');
}

/**
 * Send conservative security headers for front-end pages.
 * Avoids breaking admin or third-party plugins by scoping to non-admin and GET requests.
 */
if (function_exists('add_action')) {
    add_action('send_headers', function () {
    if (function_exists('is_admin') && call_user_func('is_admin')) { return; }
        if (strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'GET') { return; }
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: SAMEORIGIN');
        header('Referrer-Policy: strict-origin-when-cross-origin');
        // Opt-in basic CSP; allow inline styles due to WordPress core/admin-bar; adjust as needed.
        header("Content-Security-Policy: default-src 'self' 'unsafe-inline' 'unsafe-eval' data: blob: https:; img-src 'self' data: https:; style-src 'self' 'unsafe-inline' https:; script-src 'self' 'unsafe-inline' 'unsafe-eval' https:; connect-src 'self' https:");
    });
}
