<?php
namespace AquaLuxe;

function is_wc_active(): bool {
    return \class_exists('WooCommerce');
}

function mix(string $path): string {
    $manifest_path = \get_template_directory() . '/assets/dist/mix-manifest.json';
    $path = '/' . ltrim($path, '/');
    if (file_exists($manifest_path)) {
        $manifest = json_decode(file_get_contents($manifest_path), true);
        if (isset($manifest[$path])) {
            return \get_template_directory_uri() . '/assets/dist' . $manifest[$path];
        }
    }
    return \get_template_directory_uri() . '/assets/dist' . $path;
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
