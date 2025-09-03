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
