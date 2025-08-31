<?php
/**
 * Helper functions
 */

function aqualuxe_is_woocommerce_active(): bool {
    return class_exists('WooCommerce');
}

function aqualuxe_mix(string $path): string {
    $map = aqualuxe_manifest_map();
    $val = aqualuxe_manifest_get($path, $map);
    return AQUALUXE_URI . 'assets/dist' . $val;
}

function aqualuxe_view(string $template, array $vars = []): void {
    $file = AQUALUXE_PATH . 'templates/' . $template . '.php';
    if (!file_exists($file)) {
        return;
    }
    extract(array_map('wp_kses_post', $vars));
    include $file;
}

function aqualuxe_nonce_field(string $action, string $name = '_aqualuxe_nonce') {
    wp_nonce_field($action, $name);
}

/**
 * Normalize badges array to unique, non-empty strings.
 * @param array $badges
 * @return array
 */
function aqualuxe_normalize_badges(array $badges): array {
    $badges = array_filter(array_map(function($v){ return is_string($v) ? trim($v) : ''; }, $badges));
    return array_values(array_unique($badges));
}

/**
 * Sanitize an asset path from mix-manifest: collapse duplicate slashes
 * and accidental repeated segments like /js/js/ or /css/css/.
 */
function aqualuxe_sanitize_asset_path(string $p): string {
    // Collapse only multiple slashes; preserve segments (some builds may legitimately emit js/js)
    return preg_replace('#/{2,}#', '/', $p);
}

/**
 * Read and decode mix-manifest.json into an array map.
 */
function aqualuxe_manifest_map(): array {
    $manifest_file = AQUALUXE_PATH . 'assets/dist/mix-manifest.json';
    if (file_exists($manifest_file)) {
        $map = json_decode(file_get_contents($manifest_file), true);
        return is_array($map) ? $map : [];
    }
    return [];
}

/**
 * Get a sanitized manifest value by key with fuzzy filename lookup.
 * Falls back to returning the sanitized key (so callers can detect default).
 */
function aqualuxe_manifest_get(string $key, ?array $map = null): string {
    $map = is_array($map) ? $map : aqualuxe_manifest_map();
    $val = null;
    if (!empty($map[$key])) {
        $val = $map[$key];
    } else {
        $needle = basename($key);
        foreach ($map as $k => $v) {
            if (substr($k, -strlen($needle)) === $needle) { $val = $v; break; }
        }
    }
    if ($val === null) $val = $key;

    // Build candidate list: raw and sanitized; pick the one that actually exists on disk.
    $candidates = [];
    $raw = $val;
    $san = aqualuxe_sanitize_asset_path($val);
    $candidates[] = $raw;
    if ($san !== $raw) $candidates[] = $san;

    foreach ($candidates as $cand) {
        $path = $cand;
        // strip query string for file_exists check
        $qpos = strpos($path, '?');
        if ($qpos !== false) $path = substr($path, 0, $qpos);
        if (file_exists(AQUALUXE_PATH . 'assets/dist' . $path)) {
            return $cand; // return with original query if present
        }
    }

    // Fall back to sanitized value
    return $san;
}
