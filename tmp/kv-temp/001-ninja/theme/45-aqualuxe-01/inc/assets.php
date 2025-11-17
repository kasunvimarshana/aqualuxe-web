<?php
/**
 * Asset management functions
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
    // Get the mix-manifest.json file
    $manifest_path = get_template_directory() . '/assets/dist/mix-manifest.json';
    $manifest = file_exists($manifest_path) ? json_decode(file_get_contents($manifest_path), true) : [];

    // Helper function to get versioned asset path
    $get_asset_path = function ($path) use ($manifest) {
        $manifest_key = '/' . $path;
        return isset($manifest[$manifest_key]) ? $manifest[$manifest_key] : $path;
    };

    // Main stylesheet
    $style_path = 'css/main.css';
    $versioned_style_path = $get_asset_path($style_path);
    wp_enqueue_style(
        'aqualuxe-style',
        AQUALUXE_ASSETS_URI . $style_path,
        array(),
        strpos($versioned_style_path, '?id=') !== false ? null : AQUALUXE_VERSION
    );

    // Main JavaScript file
    $script_path = 'js/main.js';
    $versioned_script_path = $get_asset_path($script_path);
    wp_enqueue_script(
        'aqualuxe-script',
        AQUALUXE_ASSETS_URI . $script_path,
        array('jquery'),
        strpos($versioned_script_path, '?id=') !== false ? null : AQUALUXE_VERSION,
        true
    );

    // Conditional scripts and styles
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }

    // Dark mode script
    if (get_theme_mod('aqualuxe_dark_mode_enable', true)) {
        $dark_mode_script_path = 'js/dark-mode.js';
        $versioned_dark_mode_script_path = $get_asset_path($dark_mode_script_path);
        wp_enqueue_script(
            'aqualuxe-dark-mode',
            AQUALUXE_ASSETS_URI . $dark_mode_script_path,
            array('jquery'),
            strpos($versioned_dark_mode_script_path, '?id=') !== false ? null : AQUALUXE_VERSION,
            true
        );
    }

    // WooCommerce specific styles and scripts
    if (aqualuxe_is_woocommerce_active()) {
        // WooCommerce styles
        $woocommerce_style_path = 'css/woocommerce.css';
        $versioned_woocommerce_style_path = $get_asset_path($woocommerce_style_path);
        wp_enqueue_style(
            'aqualuxe-woocommerce',
            AQUALUXE_ASSETS_URI . $woocommerce_style_path,
            array('aqualuxe-style'),
            strpos($versioned_woocommerce_style_path, '?id=') !== false ? null : AQUALUXE_VERSION
        );

        // WooCommerce scripts
        $woocommerce_script_path = 'js/woocommerce.js';
        $versioned_woocommerce_script_path = $get_asset_path($woocommerce_script_path);
        wp_enqueue_script(
            'aqualuxe-woocommerce',
            AQUALUXE_ASSETS_URI . $woocommerce_script_path,
            array('jquery'),
            strpos($versioned_woocommerce_script_path, '?id=') !== false ? null : AQUALUXE_VERSION,
            true
        );

        // Quick view script
        if (is_shop() || is_product_category() || is_product_tag()) {
            $quickview_script_path = 'js/quickview.js';
            $versioned_quickview_script_path = $get_asset_path($quickview_script_path);
            wp_enqueue_script(
                'aqualuxe-quickview',
                AQUALUXE_ASSETS_URI . $quickview_script_path,
                array('jquery'),
                strpos($versioned_quickview_script_path, '?id=') !== false ? null : AQUALUXE_VERSION,
                true
            );

            // Localize the script with necessary data
            wp_localize_script(
                'aqualuxe-quickview',
                'aqualuxeQuickView',
                array(
                    'ajaxurl' => admin_url('admin-ajax.php'),
                    'nonce' => wp_create_nonce('aqualuxe-quickview-nonce'),
                    'loading_text' => esc_html__('Loading...', 'aqualuxe'),
                )
            );
        }

        // Wishlist script
        if (get_theme_mod('aqualuxe_wishlist_enable', true)) {
            $wishlist_script_path = 'js/wishlist.js';
            $versioned_wishlist_script_path = $get_asset_path($wishlist_script_path);
            wp_enqueue_script(
                'aqualuxe-wishlist',
                AQUALUXE_ASSETS_URI . $wishlist_script_path,
                array('jquery'),
                strpos($versioned_wishlist_script_path, '?id=') !== false ? null : AQUALUXE_VERSION,
                true
            );

            // Localize the script with necessary data
            wp_localize_script(
                'aqualuxe-wishlist',
                'aqualuxeWishlist',
                array(
                    'ajaxurl' => admin_url('admin-ajax.php'),
                    'nonce' => wp_create_nonce('aqualuxe-wishlist-nonce'),
                    'add_to_wishlist_text' => esc_html__('Add to Wishlist', 'aqualuxe'),
                    'remove_from_wishlist_text' => esc_html__('Remove from Wishlist', 'aqualuxe'),
                )
            );
        }
    }

    // Localize the main script with necessary data
    wp_localize_script(
        'aqualuxe-script',
        'aqualuxeData',
        array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe-nonce'),
            'is_rtl' => is_rtl(),
            'is_user_logged_in' => is_user_logged_in(),
            'currency_symbol' => aqualuxe_get_currency_symbol(),
            'currency_position' => aqualuxe_get_currency_position(),
            'dark_mode' => get_theme_mod('aqualuxe_dark_mode_enable', true),
            'sticky_header' => get_theme_mod('aqualuxe_sticky_header_enable', true),
            'back_to_top' => get_theme_mod('aqualuxe_back_to_top_enable', true),
            'cookie_notice' => get_theme_mod('aqualuxe_cookie_notice_enable', true),
            'cookie_notice_text' => get_theme_mod('aqualuxe_cookie_notice_text', esc_html__('We use cookies to enhance your experience. By continuing to visit this site you agree to our use of cookies.', 'aqualuxe')),
            'cookie_notice_button_text' => get_theme_mod('aqualuxe_cookie_notice_button_text', esc_html__('Accept', 'aqualuxe')),
        )
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

    // Helper function to get versioned asset path
    $get_asset_path = function ($path) use ($manifest) {
        $manifest_key = '/' . $path;
        return isset($manifest[$manifest_key]) ? $manifest[$manifest_key] : $path;
    };

    // Admin stylesheet
    $admin_style_path = 'css/admin.css';
    $versioned_admin_style_path = $get_asset_path($admin_style_path);
    wp_enqueue_style(
        'aqualuxe-admin-style',
        AQUALUXE_ASSETS_URI . $admin_style_path,
        array(),
        strpos($versioned_admin_style_path, '?id=') !== false ? null : AQUALUXE_VERSION
    );

    // Admin JavaScript file
    $admin_script_path = 'js/admin.js';
    $versioned_admin_script_path = $get_asset_path($admin_script_path);
    wp_enqueue_script(
        'aqualuxe-admin-script',
        AQUALUXE_ASSETS_URI . $admin_script_path,
        array('jquery'),
        strpos($versioned_admin_script_path, '?id=') !== false ? null : AQUALUXE_VERSION,
        true
    );
}
add_action('admin_enqueue_scripts', 'aqualuxe_admin_scripts');

/**
 * Get the currency symbol.
 *
 * @return string Currency symbol.
 */
function aqualuxe_get_currency_symbol() {
    if (aqualuxe_is_woocommerce_active()) {
        return get_woocommerce_currency_symbol();
    }
    
    return '$'; // Default currency symbol
}

/**
 * Get the currency position.
 *
 * @return string Currency position.
 */
function aqualuxe_get_currency_position() {
    if (aqualuxe_is_woocommerce_active()) {
        return get_option('woocommerce_currency_pos');
    }
    
    return 'left'; // Default currency position
}

/**
 * Add preconnect for Google Fonts.
 *
 * @param array  $urls           URLs to print for resource hints.
 * @param string $relation_type  The relation type the URLs are printed.
 * @return array $urls           URLs to print for resource hints.
 */
function aqualuxe_resource_hints($urls, $relation_type) {
    if ('preconnect' === $relation_type) {
        // Add Google Fonts preconnect
        $urls[] = array(
            'href' => 'https://fonts.gstatic.com',
            'crossorigin',
        );
    }

    return $urls;
}
add_filter('wp_resource_hints', 'aqualuxe_resource_hints', 10, 2);