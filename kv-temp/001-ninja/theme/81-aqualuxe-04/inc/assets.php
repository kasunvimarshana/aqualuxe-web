<?php
/** Assets enqueue from mix-manifest */
if (!defined('ABSPATH')) { exit; }

function aqualuxe_mix($path) {
    $manifest_path = AQUALUXE_DIR . 'assets/dist/mix-manifest.json';
    $uri_base = AQUALUXE_ASSETS_URI;
    if (!file_exists($manifest_path)) return $uri_base . ltrim($path, '/');
    $manifest = json_decode(file_get_contents($manifest_path), true);
    if (isset($manifest[$path])) return $uri_base . ltrim($manifest[$path], '/');
    return $uri_base . ltrim($path, '/');
}

add_action('wp_enqueue_scripts', function(){
    // Tailwind + theme styles
    wp_enqueue_style('aqualuxe-main', aqualuxe_mix('/css/app.css'), [], AQUALUXE_VERSION);

    // Main JS
    wp_enqueue_script('aqualuxe-vendor', aqualuxe_mix('/js/vendor.js'), [], AQUALUXE_VERSION, true);
    wp_enqueue_script('aqualuxe-app', aqualuxe_mix('/js/app.js'), ['aqualuxe-vendor'], AQUALUXE_VERSION, true);

    // Localized config
    wp_localize_script('aqualuxe-app', 'AQUALUXE', [
        'ajax' => admin_url('admin-ajax.php'),
        'rest' => esc_url_raw(trailingslashit(rest_url('aqualuxe/v1'))),
        'nonce' => wp_create_nonce('wp_rest'),
    'loggedIn' => is_user_logged_in(),
        'i18n' => [
            'add_to_cart' => __('Add to cart', 'aqualuxe'),
            'in_stock' => __('In stock', 'aqualuxe'),
            'out_of_stock' => __('Out of stock', 'aqualuxe'),
            'price' => __('Price', 'aqualuxe'),
            'remove' => __('Remove', 'aqualuxe'),
        ]
    ]);
});

// Admin assets
add_action('admin_enqueue_scripts', function(){
    wp_enqueue_style('aqualuxe-admin', aqualuxe_mix('/css/admin.css'), [], AQUALUXE_VERSION);
    wp_enqueue_script('aqualuxe-admin', aqualuxe_mix('/js/admin.js'), ['jquery'], AQUALUXE_VERSION, true);
});

// Inline CSS variables from Customizer
add_action('wp_head', function(){
    $color = get_theme_mod('aqualuxe_primary_color', '#0ea5e9');
    $font = get_theme_mod('aqualuxe_font_family', 'ui-sans-serif');
    echo '<style id="aqualuxe-vars">:root{--ax-primary:' . esc_attr($color) . '; --ax-font:' . esc_attr($font) . ';} body{font-family:var(--ax-font);} .ax-btn{background-color:var(--ax-primary);} .ax-btn:hover{filter:brightness(0.95);}</style>';
}, 20);
