<?php
/** Helper functions and template tags */
if (!defined('ABSPATH')) { exit; }

function aqualuxe_is_woocommerce_active() {
    return class_exists('WooCommerce');
}

function aqualuxe_get_option($key, $default = null) {
    $opts = get_option('aqualuxe_options', []);
    return isset($opts[$key]) ? $opts[$key] : $default;
}

function aqualuxe_svg($name, $attrs = []) {
    $file = AQUALUXE_DIR . 'assets/dist/svg/' . sanitize_file_name($name) . '.svg';
    if (!file_exists($file)) return '';
    $svg = file_get_contents($file);
    return $svg;
}

function aqualuxe_body_classes($classes){
    if (aqualuxe_is_woocommerce_active()) $classes[] = 'has-woocommerce';
    if (get_theme_mod('aqualuxe_dark_mode_default', false)) $classes[] = 'dark';
    return $classes;
}
if (function_exists('add_filter')) {
    add_filter('body_class', 'aqualuxe_body_classes');
}

// Schema.org helpers
function aqualuxe_schema_attr($type){
    return ' itemscope itemtype="https://schema.org/' . esc_attr($type) . '"';
}
