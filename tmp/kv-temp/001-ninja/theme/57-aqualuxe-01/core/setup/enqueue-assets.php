<?php
/**
 * Enqueue scripts and styles
 *
 * @package AquaLuxe
 */

/**
 * Get the asset version from mix-manifest.json
 *
 * @param string $path The path to the asset.
 * @return string The versioned path or original path if not found.
 */
function aqualuxe_asset_path($path) {
    $manifest_path = get_template_directory() . '/assets/dist/mix-manifest.json';
    
    if (file_exists($manifest_path)) {
        $manifest = json_decode(file_get_contents($manifest_path), true);
        $path_key = '/' . ltrim($path, '/');
        
        if (isset($manifest[$path_key])) {
            return $manifest[$path_key];
        }
    }
    
    return $path;
}

/**
 * Enqueue scripts and styles.
 */
function aqualuxe_scripts() {
    // Register and enqueue styles
    wp_enqueue_style(
        'aqualuxe-style',
        AQUALUXE_ASSETS_URI . 'css/app.css',
        array(),
        filemtime(get_template_directory() . '/assets/dist/css/app.css')
    );

    // Register and enqueue scripts
    wp_enqueue_script(
        'aqualuxe-script',
        AQUALUXE_ASSETS_URI . 'js/app.js',
        array('jquery'),
        filemtime(get_template_directory() . '/assets/dist/js/app.js'),
        true
    );

    // Localize script with theme data
    wp_localize_script(
        'aqualuxe-script',
        'aqualuxeData',
        array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'themeUri' => get_template_directory_uri(),
            'nonce' => wp_create_nonce('aqualuxe-nonce'),
            'isWooCommerce' => class_exists('WooCommerce'),
            'isDarkMode' => aqualuxe_is_dark_mode(),
            'i18n' => array(
                'addToCart' => esc_html__('Add to cart', 'aqualuxe'),
                'viewCart' => esc_html__('View cart', 'aqualuxe'),
                'searchPlaceholder' => esc_html__('Search...', 'aqualuxe'),
                'loadMore' => esc_html__('Load more', 'aqualuxe'),
                'noMoreItems' => esc_html__('No more items to load', 'aqualuxe'),
            ),
        )
    );

    // Add comment reply script if needed
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }

    // Conditionally load WooCommerce specific scripts
    if (class_exists('WooCommerce') && (is_woocommerce() || is_cart() || is_checkout() || is_account_page())) {
        wp_enqueue_script(
            'aqualuxe-woocommerce',
            AQUALUXE_ASSETS_URI . 'js/woocommerce.js',
            array('jquery', 'aqualuxe-script'),
            filemtime(get_template_directory() . '/assets/dist/js/woocommerce.js'),
            true
        );
    }
}
add_action('wp_enqueue_scripts', 'aqualuxe_scripts');

/**
 * Enqueue admin scripts and styles.
 */
function aqualuxe_admin_scripts() {
    wp_enqueue_style(
        'aqualuxe-admin-style',
        AQUALUXE_ASSETS_URI . 'css/admin.css',
        array(),
        filemtime(get_template_directory() . '/assets/dist/css/admin.css')
    );

    wp_enqueue_script(
        'aqualuxe-admin-script',
        AQUALUXE_ASSETS_URI . 'js/admin.js',
        array('jquery'),
        filemtime(get_template_directory() . '/assets/dist/js/admin.js'),
        true
    );
}
add_action('admin_enqueue_scripts', 'aqualuxe_admin_scripts');

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
        $urls[] = array(
            'href' => 'https://fonts.gstatic.com',
            'crossorigin',
        );
    }
    
    return $urls;
}
add_filter('wp_resource_hints', 'aqualuxe_resource_hints', 10, 2);