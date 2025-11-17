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
    $nonce = isset($_POST[$name]) ? sanitize_text_field(wp_unslash($_POST[$name])) : '';
    return (bool) wp_verify_nonce($nonce, $action);
}
