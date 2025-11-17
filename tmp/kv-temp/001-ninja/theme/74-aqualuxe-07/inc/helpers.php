<?php
namespace AquaLuxe;

function is_wc_active(): bool {
    return \class_exists('WooCommerce');
}

function mix(string $path): string {
    $path = '/' . ltrim($path, '/');
    $theme_dir = \get_template_directory();
    $dist_dir = $theme_dir . '/assets/dist';
    $manifest_path = $dist_dir . '/mix-manifest.json';
    if (file_exists($manifest_path)) {
        $manifest = json_decode(file_get_contents($manifest_path), true) ?: [];
        if (isset($manifest[$path])) {
            return \get_template_directory_uri() . '/assets/dist' . $manifest[$path];
        }
    }
    // Fallback to non-versioned if it exists; otherwise return empty to avoid 404s
    if (file_exists($dist_dir . $path)) {
        return \get_template_directory_uri() . '/assets/dist' . $path;
    }
    return '';
}

function e($text): string {
    return \esc_html($text);
}

function attr(array $attrs): string {
    $out = '';
    foreach ($attrs as $k => $v) {
        if ($v === true) { $out .= ' ' . \esc_attr($k); }
        elseif ($v !== false && $v !== null) { $out .= ' ' . \esc_attr($k) . '="' . \esc_attr((string)$v) . '"'; }
    }
    return $out;
}
