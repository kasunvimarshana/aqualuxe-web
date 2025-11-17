<?php
// Minimal PHPUnit bootstrap for theme utility tests that don't need full WP.
// We avoid bootstrapping WordPress; keep tests pure PHP where possible.

declare(strict_types=1);

// Define minimal constants to satisfy includes.
if (! defined('ABSPATH')) {
    define('ABSPATH', __DIR__);
}

$themeRoot = dirname(dirname(__DIR__));

if (! defined('AQUALUXE_DIR')) {
    define('AQUALUXE_DIR', rtrim($themeRoot, '/\\') . '/');
}
if (! defined('AQUALUXE_URI')) {
    // For tests we don't need real URIs; return relative paths.
    define('AQUALUXE_URI', '/');
}

// Shim add_action to avoid fatal when including files that register hooks.
if (! function_exists('add_action')) {
    function add_action($hook, $callback, $priority = 10, $accepted_args = 1) {
        // no-op in tests
    }
}

// Load only files with pure functions and guards.
require_once $themeRoot . '/inc/enqueue.php';
