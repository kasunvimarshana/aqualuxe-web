<?php
/**
 * Enqueue scripts and styles.
 *
 * @package AquaLuxe
 */

/**
 * Enqueue scripts and styles.
 */
function aqualuxe_scripts() {
    // Register and enqueue main stylesheet
    wp_enqueue_style(
        'aqualuxe-style',
        AQUALUXE_URI . 'assets/css/main.css',
        array(),
        AQUALUXE_VERSION
    );

    // Register and enqueue main script
    wp_enqueue_script(
        'aqualuxe-script',
        AQUALUXE_URI . 'assets/js/main.js',
        array('jquery'),
        AQUALUXE_VERSION,
        true
    );

    // Register and enqueue dark mode script
    wp_enqueue_script(
        'aqualuxe-dark-mode',
        AQUALUXE_URI . 'assets/js/dark-mode.js',
        array('jquery'),
        AQUALUXE_VERSION,
        true
    );

    // Localize the script with new data
    wp_localize_script(
        'aqualuxe-script',
        'aqualuxe_vars',
        array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe-nonce'),
            'is_user_logged_in' => is_user_logged_in(),
            'is_dark_mode' => aqualuxe_is_dark_mode_active(),
            'i18n' => array(
                'add_to_cart' => esc_html__('Add to cart', 'aqualuxe'),
                'added_to_cart' => esc_html__('Added to cart', 'aqualuxe'),
                'add_to_wishlist' => esc_html__('Add to wishlist', 'aqualuxe'),
                'added_to_wishlist' => esc_html__('Added to wishlist', 'aqualuxe'),
                'loading' => esc_html__('Loading...', 'aqualuxe'),
                'error' => esc_html__('Error', 'aqualuxe'),
                'success' => esc_html__('Success', 'aqualuxe'),
            ),
        )
    );

    // If WooCommerce is active, enqueue WooCommerce scripts
    if (class_exists('WooCommerce')) {
        wp_enqueue_script(
            'aqualuxe-woocommerce',
            AQUALUXE_URI . 'assets/js/woocommerce.js',
            array('jquery'),
            AQUALUXE_VERSION,
            true
        );

        // Localize the WooCommerce script
        wp_localize_script(
            'aqualuxe-woocommerce',
            'aqualuxe_wc',
            array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'wc_ajax_url' => WC_AJAX::get_endpoint('%%endpoint%%'),
                'nonce' => wp_create_nonce('aqualuxe-wc-nonce'),
                'i18n' => array(
                    'quick_view' => esc_html__('Quick View', 'aqualuxe'),
                    'add_to_cart' => esc_html__('Add to cart', 'aqualuxe'),
                    'added_to_cart' => esc_html__('Added to cart', 'aqualuxe'),
                    'view_cart' => esc_html__('View cart', 'aqualuxe'),
                    'checkout' => esc_html__('Checkout', 'aqualuxe'),
                ),
            )
        );
    }

    // If single product page, enqueue product gallery scripts
    if (is_product()) {
        wp_enqueue_script(
            'aqualuxe-product-gallery',
            AQUALUXE_URI . 'assets/js/product-gallery.js',
            array('jquery'),
            AQUALUXE_VERSION,
            true
        );
    }

    // If comments are open, enqueue the comment-reply script
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
        AQUALUXE_URI . 'assets/css/admin.css',
        array(),
        AQUALUXE_VERSION
    );

    // Admin scripts
    wp_enqueue_script(
        'aqualuxe-admin-script',
        AQUALUXE_URI . 'assets/js/admin.js',
        array('jquery'),
        AQUALUXE_VERSION,
        true
    );

    // Localize the admin script
    wp_localize_script(
        'aqualuxe-admin-script',
        'aqualuxe_admin',
        array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe-admin-nonce'),
            'i18n' => array(
                'confirm_delete' => esc_html__('Are you sure you want to delete this item?', 'aqualuxe'),
                'confirm_reset' => esc_html__('Are you sure you want to reset to default settings? This cannot be undone.', 'aqualuxe'),
                'success' => esc_html__('Success', 'aqualuxe'),
                'error' => esc_html__('Error', 'aqualuxe'),
            ),
        )
    );
}
add_action('admin_enqueue_scripts', 'aqualuxe_admin_scripts');

/**
 * Enqueue block editor assets.
 */
function aqualuxe_block_editor_assets() {
    // Block editor styles
    wp_enqueue_style(
        'aqualuxe-block-editor-style',
        AQUALUXE_URI . 'assets/css/editor.css',
        array('wp-edit-blocks'),
        AQUALUXE_VERSION
    );

    // Block editor scripts
    wp_enqueue_script(
        'aqualuxe-block-editor-script',
        AQUALUXE_URI . 'assets/js/editor.js',
        array('wp-blocks', 'wp-dom-ready', 'wp-edit-post'),
        AQUALUXE_VERSION,
        true
    );
}
add_action('enqueue_block_editor_assets', 'aqualuxe_block_editor_assets');

/**
 * Enqueue customizer scripts and styles.
 */
function aqualuxe_customizer_scripts() {
    // Customizer styles
    wp_enqueue_style(
        'aqualuxe-customizer-style',
        AQUALUXE_URI . 'assets/css/customizer.css',
        array(),
        AQUALUXE_VERSION
    );

    // Customizer scripts
    wp_enqueue_script(
        'aqualuxe-customizer-script',
        AQUALUXE_URI . 'assets/js/customizer.js',
        array('jquery', 'customize-preview'),
        AQUALUXE_VERSION,
        true
    );

    // Localize the customizer script
    wp_localize_script(
        'aqualuxe-customizer-script',
        'aqualuxe_customizer',
        array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe-customizer-nonce'),
        )
    );
}
add_action('customize_preview_init', 'aqualuxe_customizer_scripts');

/**
 * Enqueue login scripts and styles.
 */
function aqualuxe_login_scripts() {
    // Login styles
    wp_enqueue_style(
        'aqualuxe-login-style',
        AQUALUXE_URI . 'assets/css/login.css',
        array(),
        AQUALUXE_VERSION
    );
}
add_action('login_enqueue_scripts', 'aqualuxe_login_scripts');

/**
 * Add preconnect for Google Fonts.
 *
 * @param array  $urls           URLs to print for resource hints.
 * @param string $relation_type  The relation type the URLs are printed.
 * @return array $urls           URLs to print for resource hints.
 */
function aqualuxe_resource_hints($urls, $relation_type) {
    if ('preconnect' === $relation_type) {
        // Add Google Fonts domain
        $urls[] = array(
            'href' => 'https://fonts.googleapis.com',
            'crossorigin',
        );
        $urls[] = array(
            'href' => 'https://fonts.gstatic.com',
            'crossorigin',
        );
    }
    
    return $urls;
}
add_filter('wp_resource_hints', 'aqualuxe_resource_hints', 10, 2);

/**
 * Add async/defer attributes to enqueued scripts when needed.
 *
 * @param string $tag    The script tag.
 * @param string $handle The script handle.
 * @return string Script HTML string.
 */
function aqualuxe_script_loader_tag($tag, $handle) {
    // Add async attribute to specific scripts
    $scripts_to_async = array('aqualuxe-dark-mode');
    
    foreach ($scripts_to_async as $async_script) {
        if ($async_script === $handle) {
            return str_replace(' src', ' async src', $tag);
        }
    }
    
    // Add defer attribute to specific scripts
    $scripts_to_defer = array('aqualuxe-script');
    
    foreach ($scripts_to_defer as $defer_script) {
        if ($defer_script === $handle) {
            return str_replace(' src', ' defer src', $tag);
        }
    }
    
    return $tag;
}
add_filter('script_loader_tag', 'aqualuxe_script_loader_tag', 10, 2);

/**
 * Add preload for critical assets.
 */
function aqualuxe_preload_assets() {
    // Preload main stylesheet
    echo '<link rel="preload" href="' . esc_url(AQUALUXE_URI . 'assets/css/main.css') . '" as="style">';
    
    // Preload main script
    echo '<link rel="preload" href="' . esc_url(AQUALUXE_URI . 'assets/js/main.js') . '" as="script">';
    
    // Preload logo
    $custom_logo_id = get_theme_mod('custom_logo');
    if ($custom_logo_id) {
        $logo_url = wp_get_attachment_image_url($custom_logo_id, 'full');
        if ($logo_url) {
            echo '<link rel="preload" href="' . esc_url($logo_url) . '" as="image">';
        }
    }
}
add_action('wp_head', 'aqualuxe_preload_assets', 1);