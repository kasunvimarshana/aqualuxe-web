<?php
/**
 * Enqueue scripts and styles
 *
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Enqueue scripts and styles.
 */
function aqualuxe_scripts() {
    // Enqueue Google Fonts
    wp_enqueue_style(
        'aqualuxe-fonts',
        'https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap',
        array(),
        AQUALUXE_VERSION
    );

    // Main stylesheet
    wp_enqueue_style(
        'aqualuxe-style',
        aqualuxe_asset_path('css/main.css'),
        array(),
        null
    );

    // WooCommerce styles (only if WooCommerce is active)
    if (aqualuxe_is_woocommerce_active()) {
        wp_enqueue_style(
            'aqualuxe-woocommerce',
            aqualuxe_asset_path('css/woocommerce.css'),
            array(),
            null
        );
    }

    // Main JavaScript
    wp_enqueue_script(
        'aqualuxe-main',
        aqualuxe_asset_path('js/main.js'),
        array('jquery'),
        null,
        true
    );

    // WooCommerce scripts (only if WooCommerce is active)
    if (aqualuxe_is_woocommerce_active()) {
        wp_enqueue_script(
            'aqualuxe-woocommerce',
            aqualuxe_asset_path('js/woocommerce.js'),
            array('jquery'),
            null,
            true
        );
    }

    // Localize script with theme data
    wp_localize_script(
        'aqualuxe-main',
        'aqualuxeData',
        array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'themeUri' => AQUALUXE_URI,
            'isWooCommerceActive' => aqualuxe_is_woocommerce_active(),
            'isMobile' => wp_is_mobile(),
            'nonce' => wp_create_nonce('aqualuxe-nonce'),
        )
    );

    // Comment reply script
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'aqualuxe_scripts');

/**
 * Enqueue admin scripts and styles.
 */
function aqualuxe_admin_scripts() {
    // Admin styles
    wp_enqueue_style(
        'aqualuxe-admin-style',
        AQUALUXE_URI . 'assets/admin/css/admin.css',
        array(),
        AQUALUXE_VERSION
    );

    // Admin scripts
    wp_enqueue_script(
        'aqualuxe-admin-script',
        AQUALUXE_URI . 'assets/admin/js/admin.js',
        array('jquery'),
        AQUALUXE_VERSION,
        true
    );
}
add_action('admin_enqueue_scripts', 'aqualuxe_admin_scripts');

/**
 * Enqueue block editor assets.
 */
function aqualuxe_block_editor_assets() {
    // Editor styles
    wp_enqueue_style(
        'aqualuxe-editor-style',
        aqualuxe_asset_path('css/editor-style.css'),
        array(),
        null
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
        $urls[] = array(
            'href' => 'https://fonts.gstatic.com',
            'crossorigin',
        );
    }

    return $urls;
}
add_filter('wp_resource_hints', 'aqualuxe_resource_hints', 10, 2);