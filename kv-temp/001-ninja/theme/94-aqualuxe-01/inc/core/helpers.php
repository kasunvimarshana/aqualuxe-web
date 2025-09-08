<?php
/**
 * Helper functions
 *
 * @package AquaLuxe
 */

namespace AquaLuxe\Core;

if (!defined('ABSPATH')) { exit; }

/**
 * Get versioned asset URL from mix-manifest.
 */
function asset_uri(string $path): string {
    $manifest_path = AQUALUXE_PATH . 'assets/dist/mix-manifest.json';
    $uri_base = AQUALUXE_ASSETS_DIST_URI;

    $path = '/' . ltrim($path, '/');
    if (file_exists($manifest_path)) {
        $manifest = json_decode((string) file_get_contents($manifest_path), true);
        if (is_array($manifest) && isset($manifest[$path])) {
            return esc_url($uri_base . ltrim($manifest[$path], '/'));
        }
    }
    // Fallback: serve from dist without hash
    return esc_url($uri_base . ltrim($path, '/'));
}

/** Sanitize boolean input */
function sanitize_bool($value): bool {
    return filter_var($value, FILTER_VALIDATE_BOOLEAN);
}

/** Output esc attr helper */
function e_attr($value): void { echo esc_attr($value); }
/** Output esc html helper */
function e_html($value): void { echo esc_html($value); }

