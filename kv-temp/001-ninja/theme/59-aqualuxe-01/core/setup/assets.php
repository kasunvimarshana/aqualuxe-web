<?php
/**
 * AquaLuxe Assets Setup
 *
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Enqueue scripts and styles
 */
function aqualuxe_assets() {
    // Enqueue Google Fonts
    wp_enqueue_style('aqualuxe-google-fonts', aqualuxe_get_google_fonts_url(), [], AQUALUXE_VERSION);

    // Enqueue main stylesheet
    aqualuxe_enqueue_style('aqualuxe-style', 'css/style.css');

    // Enqueue WooCommerce styles if active
    if (aqualuxe_is_woocommerce_active()) {
        aqualuxe_enqueue_style('aqualuxe-woocommerce', 'css/woocommerce.css', ['aqualuxe-style']);
    }

    // Enqueue main script
    aqualuxe_enqueue_script('aqualuxe-app', 'js/app.js', ['jquery']);

    // Localize script
    aqualuxe_localize_script('aqualuxe-app', 'aqualuxeData', [
        'ajaxUrl'    => admin_url('admin-ajax.php'),
        'homeUrl'    => home_url('/'),
        'themeUrl'   => AQUALUXE_URI,
        'assetsUrl'  => AQUALUXE_ASSETS_URI,
        'nonce'      => wp_create_nonce('aqualuxe-nonce'),
        'isLoggedIn' => is_user_logged_in(),
        'isWooCommerce' => aqualuxe_is_woocommerce_active(),
        'currency'   => aqualuxe_get_currency_symbol(),
        'language'   => aqualuxe_get_current_language(),
        'i18n'       => [
            'addToCart'    => esc_html__('Add to Cart', 'aqualuxe'),
            'viewCart'     => esc_html__('View Cart', 'aqualuxe'),
            'checkout'     => esc_html__('Checkout', 'aqualuxe'),
            'addToWishlist' => esc_html__('Add to Wishlist', 'aqualuxe'),
            'removeFromWishlist' => esc_html__('Remove from Wishlist', 'aqualuxe'),
            'quickView'    => esc_html__('Quick View', 'aqualuxe'),
            'compare'      => esc_html__('Compare', 'aqualuxe'),
            'loading'      => esc_html__('Loading...', 'aqualuxe'),
            'error'        => esc_html__('Error', 'aqualuxe'),
            'success'      => esc_html__('Success', 'aqualuxe'),
            'warning'      => esc_html__('Warning', 'aqualuxe'),
            'info'         => esc_html__('Info', 'aqualuxe'),
            'close'        => esc_html__('Close', 'aqualuxe'),
            'cancel'       => esc_html__('Cancel', 'aqualuxe'),
            'confirm'      => esc_html__('Confirm', 'aqualuxe'),
            'yes'          => esc_html__('Yes', 'aqualuxe'),
            'no'           => esc_html__('No', 'aqualuxe'),
            'ok'           => esc_html__('OK', 'aqualuxe'),
            'search'       => esc_html__('Search', 'aqualuxe'),
            'filter'       => esc_html__('Filter', 'aqualuxe'),
            'sort'         => esc_html__('Sort', 'aqualuxe'),
            'show'         => esc_html__('Show', 'aqualuxe'),
            'hide'         => esc_html__('Hide', 'aqualuxe'),
            'more'         => esc_html__('More', 'aqualuxe'),
            'less'         => esc_html__('Less', 'aqualuxe'),
            'all'          => esc_html__('All', 'aqualuxe'),
            'none'         => esc_html__('None', 'aqualuxe'),
            'next'         => esc_html__('Next', 'aqualuxe'),
            'prev'         => esc_html__('Previous', 'aqualuxe'),
            'darkMode'     => esc_html__('Dark Mode', 'aqualuxe'),
            'lightMode'    => esc_html__('Light Mode', 'aqualuxe'),
        ],
    ]);

    // Enqueue comment reply script
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }

    // Enqueue module scripts
    do_action('aqualuxe_enqueue_module_scripts');
}

/**
 * Enqueue admin scripts and styles
 */
function aqualuxe_admin_assets() {
    // Enqueue admin stylesheet
    aqualuxe_enqueue_style('aqualuxe-admin', 'css/admin.css');

    // Enqueue admin script
    aqualuxe_enqueue_script('aqualuxe-admin', 'js/admin.js', ['jquery']);

    // Localize admin script
    aqualuxe_localize_script('aqualuxe-admin', 'aqualuxeAdminData', [
        'ajaxUrl'    => admin_url('admin-ajax.php'),
        'homeUrl'    => home_url('/'),
        'themeUrl'   => AQUALUXE_URI,
        'assetsUrl'  => AQUALUXE_ASSETS_URI,
        'nonce'      => wp_create_nonce('aqualuxe-admin-nonce'),
        'i18n'       => [
            'error'        => esc_html__('Error', 'aqualuxe'),
            'success'      => esc_html__('Success', 'aqualuxe'),
            'warning'      => esc_html__('Warning', 'aqualuxe'),
            'info'         => esc_html__('Info', 'aqualuxe'),
            'close'        => esc_html__('Close', 'aqualuxe'),
            'cancel'       => esc_html__('Cancel', 'aqualuxe'),
            'confirm'      => esc_html__('Confirm', 'aqualuxe'),
            'yes'          => esc_html__('Yes', 'aqualuxe'),
            'no'           => esc_html__('No', 'aqualuxe'),
            'ok'           => esc_html__('OK', 'aqualuxe'),
        ],
    ]);

    // Enqueue module admin scripts
    do_action('aqualuxe_enqueue_module_admin_scripts');
}

/**
 * Enqueue editor scripts and styles
 */
function aqualuxe_editor_assets() {
    // Enqueue editor stylesheet
    aqualuxe_enqueue_style('aqualuxe-editor', 'css/editor.css');

    // Enqueue editor script
    aqualuxe_enqueue_script('aqualuxe-editor', 'js/editor.js', ['wp-blocks', 'wp-dom-ready', 'wp-edit-post']);
}

/**
 * Add preconnect for Google Fonts
 *
 * @param array $urls URLs to print for resource hints
 * @param string $relation_type The relation type the URLs are printed for
 * @return array $urls URLs to print for resource hints
 */
function aqualuxe_resource_hints($urls, $relation_type) {
    if ('preconnect' === $relation_type) {
        $urls[] = [
            'href' => 'https://fonts.gstatic.com',
            'crossorigin',
        ];
    }

    return $urls;
}
add_filter('wp_resource_hints', 'aqualuxe_resource_hints', 10, 2);

/**
 * Add async/defer attributes to enqueued scripts
 *
 * @param string $tag The script tag
 * @param string $handle The script handle
 * @return string
 */
function aqualuxe_script_loader_tag($tag, $handle) {
    // Add async attribute to specific scripts
    $async_scripts = [
        'aqualuxe-app',
    ];

    if (in_array($handle, $async_scripts, true)) {
        return str_replace(' src', ' async src', $tag);
    }

    // Add defer attribute to specific scripts
    $defer_scripts = [
        'aqualuxe-customizer',
    ];

    if (in_array($handle, $defer_scripts, true)) {
        return str_replace(' src', ' defer src', $tag);
    }

    return $tag;
}
add_filter('script_loader_tag', 'aqualuxe_script_loader_tag', 10, 2);

/**
 * Add preload for specific assets
 */
function aqualuxe_preload_assets() {
    // Preload fonts
    aqualuxe_preload_font(AQUALUXE_ASSETS_URI . 'fonts/playfair-display-v30-latin-regular.woff2');
    aqualuxe_preload_font(AQUALUXE_ASSETS_URI . 'fonts/montserrat-v25-latin-regular.woff2');

    // Preconnect to external domains
    aqualuxe_preconnect('https://fonts.googleapis.com');
    aqualuxe_preconnect('https://fonts.gstatic.com', true);
}
add_action('wp_head', 'aqualuxe_preload_assets', 1);

/**
 * Add custom inline CSS
 */
function aqualuxe_custom_css() {
    // Get theme mods
    $primary_color = aqualuxe_get_theme_mod('primary_color', '#0077b6');
    $secondary_color = aqualuxe_get_theme_mod('secondary_color', '#00b4d8');
    $accent_color = aqualuxe_get_theme_mod('accent_color', '#48cae4');
    $dark_color = aqualuxe_get_theme_mod('dark_color', '#03045e');
    $light_color = aqualuxe_get_theme_mod('light_color', '#caf0f8');
    $gold_color = aqualuxe_get_theme_mod('gold_color', '#d4af37');

    // Custom CSS
    $css = "
        :root {
            --aqualuxe-primary-color: {$primary_color};
            --aqualuxe-secondary-color: {$secondary_color};
            --aqualuxe-accent-color: {$accent_color};
            --aqualuxe-dark-color: {$dark_color};
            --aqualuxe-light-color: {$light_color};
            --aqualuxe-gold-color: {$gold_color};
        }
    ";

    // Add custom CSS
    wp_add_inline_style('aqualuxe-style', $css);
}
add_action('wp_enqueue_scripts', 'aqualuxe_custom_css', 20);

/**
 * Add custom inline JS
 */
function aqualuxe_custom_js() {
    // Custom JS
    $js = "
        // Custom JS
    ";

    // Add custom JS
    wp_add_inline_script('aqualuxe-app', $js);
}
add_action('wp_enqueue_scripts', 'aqualuxe_custom_js', 20);