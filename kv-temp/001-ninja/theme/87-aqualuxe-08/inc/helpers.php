<?php
namespace AquaLuxe;

/** General helper functions used across the theme. */

function is_woocommerce_active(): bool
{
    return class_exists('WooCommerce');
}

function esc_attr_bool($val): string
{
    return $val ? 'true' : 'false';
}

function mix(string $asset): string
{
    $manifestFile = AQUALUXE_ASSETS_DIST . '/mix-manifest.json';
    if (file_exists($manifestFile)) {
        $manifest = json_decode((string)file_get_contents($manifestFile), true) ?: [];
        $key = '/' . ltrim($asset, '/');
        if (isset($manifest[$key])) {
            return AQUALUXE_ASSETS_URI . $manifest[$key];
        }
    }
    return '';
}

/**
 * Small fragment cache helper. Returns cached HTML if present; otherwise executes
 * the callback, stores the result in a transient, and returns it. Safe no-op when
 * WordPress transients are unavailable (e.g., during CLI unit tests).
 *
 * @param string   $key Cache key (should be unique per variation; keep <= 172 chars).
 * @param int      $ttl Seconds to keep the cached fragment.
 * @param callable $cb  Callback that returns string HTML.
 * @return string
 */
function fragment_cache(string $key, int $ttl, callable $cb): string
{
    try {
        if (\function_exists('get_transient') && \function_exists('set_transient')) {
            $cached = \call_user_func('get_transient', $key);
            if (is_string($cached) && $cached !== '') {
                return $cached;
            }
            $out = (string) \call_user_func($cb);
            // Best-effort store; ignore failures
            \call_user_func('set_transient', $key, $out, $ttl);
            return $out;
        }
    } catch (\Throwable $e) {
        // Fall through to callback
    }
    return (string) \call_user_func($cb);
}
