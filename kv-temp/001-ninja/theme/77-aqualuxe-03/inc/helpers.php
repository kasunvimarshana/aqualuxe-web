<?php
if (!defined('ABSPATH')) exit;

function aqualuxe_mix(string $path): string {
    $manifest_path = get_template_directory() . '/assets/dist/mix-manifest.json';
    $base_dir = get_template_directory() . '/assets/dist';
    $base_uri = get_template_directory_uri() . '/assets/dist';
    $path = '/' . ltrim($path, '/');
    if (file_exists($manifest_path)) {
        $manifest = json_decode(file_get_contents($manifest_path), true);
        if (json_last_error() === JSON_ERROR_NONE && isset($manifest[$path])) {
            $mapped = $manifest[$path];
            // Strip query string to check file existence on disk.
            $file_only = strtok($mapped, '?');
            if (file_exists($base_dir . $file_only)) {
                return esc_url($base_uri . $mapped);
            }
        }
    }
    // Fallback to non-hashed file for dev.
    return esc_url($base_uri . $path);
}

function aqualuxe_is_wc_active(): bool {
    return apply_filters('aqualuxe/is_woocommerce_active', class_exists('WooCommerce'));
}

function aqualuxe_get_partial(string $slug, array $vars = []) {
    $file = locate_template("templates/{$slug}.php");
    if ($file) {
        extract($vars, EXTR_SKIP);
        include $file;
    }
}

function aqualuxe_bool_option($value, $default = false): bool {
    if (is_null($value)) return (bool)$default;
    if (is_bool($value)) return $value;
    $value = strtolower((string)$value);
    return in_array($value, ['1','true','yes','on'], true);
}
