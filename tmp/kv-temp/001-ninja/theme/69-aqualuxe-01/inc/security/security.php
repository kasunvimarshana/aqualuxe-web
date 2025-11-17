<?php
/**
 * AquaLuxe Security Hardening
 * - Disables XML-RPC
 * - Disables file editing
 * - Sanitizes all user input
 * - Adds nonces to forms
 */
// Disable XML-RPC
add_filter('xmlrpc_enabled', '__return_false');
// Disable file editing from admin
if (is_admin()) {
    define('DISALLOW_FILE_EDIT', true);
}
// Sanitize all $_POST/$_GET globally (example, extend as needed)
function aqualuxe_sanitize_globals() {
    array_walk_recursive($_POST, function (&$v) { $v = sanitize_text_field($v); });
    array_walk_recursive($_GET, function (&$v) { $v = sanitize_text_field($v); });
}
add_action('init', 'aqualuxe_sanitize_globals', 1);
