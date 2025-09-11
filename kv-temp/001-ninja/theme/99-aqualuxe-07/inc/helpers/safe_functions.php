<?php
// Global helper functions (no namespace) for simplicity and broad availability.

if (! defined('ABSPATH')) { exit; }

/** Sanitize a text field safely. */
function aqualuxe_safe_text($value): string {
    return is_string($value) ? sanitize_text_field($value) : '';
}

/** Quick request helper with whitelist and sanitization. */
function aqualuxe_request(string $key, $default = null) {
    if (isset($_POST[$key])) { return wp_unslash($_POST[$key]); }
    if (isset($_GET[$key])) { return wp_unslash($_GET[$key]); }
    return $default;
}

/** Verify a nonce value sent from client. */
function aqualuxe_verify_nonce(string $action, string $field = '_ajax_nonce'): bool {
    $nonce = aqualuxe_request($field);
    return is_string($nonce) && wp_verify_nonce($nonce, $action);
}
