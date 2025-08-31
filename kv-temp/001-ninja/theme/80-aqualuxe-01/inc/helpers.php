<?php
/**
 * Helper functions
 */

function aqualuxe_is_woocommerce_active(): bool {
    return class_exists('WooCommerce');
}

function aqualuxe_mix(string $path): string {
    $manifest_path = AQUALUXE_PATH . 'assets/dist/mix-manifest.json';
    $asset_path = '/assets/dist' . $path;
    if (file_exists($manifest_path)) {
        $manifest = json_decode(file_get_contents($manifest_path), true);
        if (isset($manifest[$path])) {
            return AQUALUXE_URI . 'assets/dist' . $manifest[$path];
        }
    }
    return AQUALUXE_URI . ltrim($asset_path, '/');
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
