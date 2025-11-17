<?php
/**
 * Asset management for AquaLuxe theme
 *
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Enqueue scripts and styles for the front end.
 */
function aqualuxe_scripts() {
    // Get the mix-manifest.json file
    $manifest_path = get_template_directory() . '/assets/dist/mix-manifest.json';
    $manifest = file_exists($manifest_path) ? json_decode(file_get_contents($manifest_path), true) : [];

    // Helper function to get versioned asset URL
    $get_asset = function ($path) use ($manifest) {
        $versioned_path = isset($manifest[$path]) ? $manifest[$path] : $path;
        return get_template_directory_uri() . '/assets/dist' . str_replace('/assets/dist', '', $versioned_path);
    };

    // Enqueue main stylesheet
    wp_enqueue_style(
        'aqualuxe-style',
        $get_asset('/css/main.css'),
        [],
        AQUALUXE_VERSION
    );

    // Enqueue main JavaScript file
    wp_enqueue_script(
        'aqualuxe-script',
        $get_asset('/js/main.js'),
        ['jquery'],
        AQUALUXE_VERSION,
        true
    );

    // Enqueue dark mode script
    wp_enqueue_script(
        'aqualuxe-dark-mode',
        $get_asset('/js/dark-mode.js'),
        ['aqualuxe-script'],
        AQUALUXE_VERSION,
        true
    );

    // Localize script for dark mode
    wp_localize_script(
        'aqualuxe-dark-mode',
        'aqualuxeDarkMode',
        [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe_dark_mode_nonce'),
        ]
    );

    // Conditionally load WooCommerce scripts
    if (class_exists('WooCommerce') && (is_woocommerce() || is_cart() || is_checkout() || is_account_page())) {
        wp_enqueue_script(
            'aqualuxe-woocommerce',
            $get_asset('/js/woocommerce.js'),
            ['aqualuxe-script', 'jquery'],
            AQUALUXE_VERSION,
            true
        );

        // Localize WooCommerce script
        wp_localize_script(
            'aqualuxe-woocommerce',
            'aqualuxeWooCommerce',
            [
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('aqualuxe_woocommerce_nonce'),
                'isCart' => is_cart(),
                'isCheckout' => is_checkout(),
                'isAccount' => is_account_page(),
                'currency' => get_woocommerce_currency_symbol(),
            ]
        );
    }

    // Add comment reply script on single posts
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }

    // Localize main script with theme data
    wp_localize_script(
        'aqualuxe-script',
        'aqualuxeData',
        [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe_nonce'),
            'homeUrl' => esc_url(home_url('/')),
            'themeUrl' => get_template_directory_uri(),
            'isLoggedIn' => is_user_logged_in(),
            'isMobile' => wp_is_mobile(),
            'isWooCommerce' => class_exists('WooCommerce'),
        ]
    );
}
add_action('wp_enqueue_scripts', 'aqualuxe_scripts');

/**
 * Enqueue admin scripts and styles.
 */
function aqualuxe_admin_scripts() {
    // Get the mix-manifest.json file
    $manifest_path = get_template_directory() . '/assets/dist/mix-manifest.json';
    $manifest = file_exists($manifest_path) ? json_decode(file_get_contents($manifest_path), true) : [];

    // Helper function to get versioned asset URL
    $get_asset = function ($path) use ($manifest) {
        $versioned_path = isset($manifest[$path]) ? $manifest[$path] : $path;
        return get_template_directory_uri() . '/assets/dist' . str_replace('/assets/dist', '', $versioned_path);
    };

    // Enqueue admin stylesheet
    wp_enqueue_style(
        'aqualuxe-admin-style',
        $get_asset('/css/admin.css'),
        [],
        AQUALUXE_VERSION
    );

    // Enqueue admin JavaScript
    wp_enqueue_script(
        'aqualuxe-admin-script',
        $get_asset('/js/admin.js'),
        ['jquery'],
        AQUALUXE_VERSION,
        true
    );

    // Localize admin script
    wp_localize_script(
        'aqualuxe-admin-script',
        'aqualuxeAdmin',
        [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe_admin_nonce'),
        ]
    );
}
add_action('admin_enqueue_scripts', 'aqualuxe_admin_scripts');

/**
 * Enqueue block editor assets.
 */
function aqualuxe_block_editor_assets() {
    // Get the mix-manifest.json file
    $manifest_path = get_template_directory() . '/assets/dist/mix-manifest.json';
    $manifest = file_exists($manifest_path) ? json_decode(file_get_contents($manifest_path), true) : [];

    // Helper function to get versioned asset URL
    $get_asset = function ($path) use ($manifest) {
        $versioned_path = isset($manifest[$path]) ? $manifest[$path] : $path;
        return get_template_directory_uri() . '/assets/dist' . str_replace('/assets/dist', '', $versioned_path);
    };

    // Enqueue editor stylesheet
    wp_enqueue_style(
        'aqualuxe-editor-style',
        $get_asset('/css/editor.css'),
        [],
        AQUALUXE_VERSION
    );

    // Enqueue editor JavaScript
    wp_enqueue_script(
        'aqualuxe-editor-script',
        $get_asset('/js/editor.js'),
        ['wp-blocks', 'wp-dom-ready', 'wp-edit-post'],
        AQUALUXE_VERSION,
        true
    );
}
add_action('enqueue_block_editor_assets', 'aqualuxe_block_editor_assets');

/**
 * Add preconnect for Google Fonts.
 *
 * @param array  $urls           URLs to print for resource hints.
 * @param string $relation_type  The relation type the URLs are printed.
 * @return array $urls           URLs to print for resource hints.
 */
function aqualuxe_resource_hints($urls, $relation_type) {
    if ('preconnect' === $relation_type) {
        // Add Google Fonts domain for preconnect
        $urls[] = [
            'href' => 'https://fonts.gstatic.com',
            'crossorigin',
        ];
    }

    return $urls;
}
add_filter('wp_resource_hints', 'aqualuxe_resource_hints', 10, 2);

/**
 * Add async/defer attributes to enqueued scripts where needed.
 *
 * @param string $tag    The script tag.
 * @param string $handle The script handle.
 * @return string Script HTML string.
 */
function aqualuxe_script_loader_tag($tag, $handle) {
    // Add async attribute to specific scripts
    $scripts_to_async = ['aqualuxe-dark-mode'];
    
    foreach ($scripts_to_async as $async_script) {
        if ($async_script === $handle) {
            return str_replace(' src', ' async src', $tag);
        }
    }

    // Add defer attribute to specific scripts
    $scripts_to_defer = [];
    
    foreach ($scripts_to_defer as $defer_script) {
        if ($defer_script === $handle) {
            return str_replace(' src', ' defer src', $tag);
        }
    }

    return $tag;
}
add_filter('script_loader_tag', 'aqualuxe_script_loader_tag', 10, 2);