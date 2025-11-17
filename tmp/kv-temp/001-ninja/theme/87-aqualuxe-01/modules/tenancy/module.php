<?php
// Tenancy helpers: derive a tenant key from host and filter options per tenant.
add_filter('aqualuxe/tenant/key', function ($key) {
    if ($key) return $key;
    $host = isset($_SERVER['HTTP_HOST']) ? strtolower(sanitize_text_field(wp_unslash($_SERVER['HTTP_HOST']))) : '';
    $map = apply_filters('aqualuxe/tenant/hosts', []); // e.g., ['us.example.com' => 'us', 'eu.example.com' => 'eu']
    return $map[$host] ?? '';
}, 10, 1);

add_filter('pre_option_blogname', function ($val) {
    $tenant = apply_filters('aqualuxe/tenant/key', '');
    if (!$tenant) return $val;
    $map = apply_filters('aqualuxe/tenant/option/blogname', []); // e.g., ['us' => 'AquaLuxe US']
    return $map[$tenant] ?? $val;
});
