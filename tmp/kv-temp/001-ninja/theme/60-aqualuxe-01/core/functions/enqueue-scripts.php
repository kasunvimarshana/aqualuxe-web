<?php
/**
 * AquaLuxe Enqueue Scripts
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Enqueue scripts and styles
 */
function aqualuxe_enqueue_scripts() {
    // Enqueue Google Fonts
    $body_font = get_theme_mod('aqualuxe_body_font', 'Roboto');
    $heading_font = get_theme_mod('aqualuxe_heading_font', 'Playfair Display');
    
    $google_fonts_url = aqualuxe_get_google_fonts_url([
        $body_font => [400, 500, 700],
        $heading_font => [400, 500, 700],
    ]);
    
    if ($google_fonts_url) {
        wp_enqueue_style('aqualuxe-google-fonts', $google_fonts_url, [], AQUALUXE_VERSION);
    }
    
    // Enqueue Font Awesome
    wp_enqueue_style('font-awesome', AQUALUXE_ASSETS_URI . 'css/font-awesome.css', [], '5.15.4');
    
    // Enqueue main stylesheet
    wp_enqueue_style('aqualuxe-style', AQUALUXE_ASSETS_URI . 'css/main.css', [], AQUALUXE_VERSION);
    
    // Enqueue main script
    wp_enqueue_script('aqualuxe-script', AQUALUXE_ASSETS_URI . 'js/main.js', ['jquery'], AQUALUXE_VERSION, true);
    
    // Localize script
    wp_localize_script('aqualuxe-script', 'aqualuxeData', [
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('aqualuxe-nonce'),
        'homeUrl' => home_url('/'),
        'isRtl' => is_rtl(),
        'themeUrl' => AQUALUXE_URI,
        'assetsUrl' => AQUALUXE_ASSETS_URI,
        'colorMode' => aqualuxe_get_color_mode(),
    ]);
    
    // Enqueue comment reply script
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
    
    // Enqueue WooCommerce styles and scripts if WooCommerce is active
    if (aqualuxe_is_woocommerce_active()) {
        if (is_woocommerce() || is_cart() || is_checkout() || is_account_page()) {
            wp_enqueue_style('aqualuxe-woocommerce', AQUALUXE_ASSETS_URI . 'css/woocommerce.css', ['aqualuxe-style'], AQUALUXE_VERSION);
            wp_enqueue_script('aqualuxe-woocommerce', AQUALUXE_ASSETS_URI . 'js/woocommerce.js', ['jquery', 'aqualuxe-script'], AQUALUXE_VERSION, true);
        }
    }
}
add_action('wp_enqueue_scripts', 'aqualuxe_enqueue_scripts');

/**
 * Enqueue admin scripts and styles
 */
function aqualuxe_admin_enqueue_scripts() {
    wp_enqueue_style('aqualuxe-admin', AQUALUXE_ASSETS_URI . 'css/admin.css', [], AQUALUXE_VERSION);
    wp_enqueue_script('aqualuxe-admin', AQUALUXE_ASSETS_URI . 'js/admin.js', ['jquery'], AQUALUXE_VERSION, true);
    
    // Localize admin script
    wp_localize_script('aqualuxe-admin', 'aqualuxeAdminData', [
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('aqualuxe-admin-nonce'),
    ]);
}
add_action('admin_enqueue_scripts', 'aqualuxe_admin_enqueue_scripts');

/**
 * Enqueue block editor assets
 */
function aqualuxe_block_editor_assets() {
    wp_enqueue_style('aqualuxe-editor-style', AQUALUXE_ASSETS_URI . 'css/editor-style.css', [], AQUALUXE_VERSION);
    wp_enqueue_script('aqualuxe-editor-script', AQUALUXE_ASSETS_URI . 'js/editor.js', ['wp-blocks', 'wp-dom-ready', 'wp-edit-post'], AQUALUXE_VERSION, true);
}
add_action('enqueue_block_editor_assets', 'aqualuxe_block_editor_assets');

/**
 * Get Google Fonts URL
 *
 * @param array $fonts Fonts to include
 * @return string Google Fonts URL
 */
function aqualuxe_get_google_fonts_url($fonts) {
    $font_families = [];
    
    foreach ($fonts as $font => $weights) {
        $font_families[] = $font . ':' . implode(',', $weights);
    }
    
    if (empty($font_families)) {
        return '';
    }
    
    $query_args = [
        'family' => urlencode(implode('|', $font_families)),
        'display' => 'swap',
    ];
    
    return add_query_arg($query_args, 'https://fonts.googleapis.com/css');
}

/**
 * Add preload for critical assets
 */
function aqualuxe_preload_assets() {
    // Preload main stylesheet
    echo '<link rel="preload" href="' . esc_url(AQUALUXE_ASSETS_URI . 'css/main.css') . '" as="style">';
    
    // Preload main script
    echo '<link rel="preload" href="' . esc_url(AQUALUXE_ASSETS_URI . 'js/main.js') . '" as="script">';
    
    // Preload Font Awesome
    echo '<link rel="preload" href="' . esc_url(AQUALUXE_ASSETS_URI . 'css/font-awesome.css') . '" as="style">';
    
    // Preload WooCommerce assets if needed
    if (aqualuxe_is_woocommerce_active() && (is_woocommerce() || is_cart() || is_checkout() || is_account_page())) {
        echo '<link rel="preload" href="' . esc_url(AQUALUXE_ASSETS_URI . 'css/woocommerce.css') . '" as="style">';
        echo '<link rel="preload" href="' . esc_url(AQUALUXE_ASSETS_URI . 'js/woocommerce.js') . '" as="script">';
    }
}
add_action('wp_head', 'aqualuxe_preload_assets', 1);

/**
 * Add defer attribute to scripts
 *
 * @param string $tag    Script tag
 * @param string $handle Script handle
 * @return string Modified script tag
 */
function aqualuxe_defer_scripts($tag, $handle) {
    $scripts_to_defer = [
        'aqualuxe-script',
        'aqualuxe-woocommerce',
    ];
    
    if (in_array($handle, $scripts_to_defer, true)) {
        return str_replace(' src', ' defer src', $tag);
    }
    
    return $tag;
}
add_filter('script_loader_tag', 'aqualuxe_defer_scripts', 10, 2);

/**
 * Add async attribute to scripts
 *
 * @param string $tag    Script tag
 * @param string $handle Script handle
 * @return string Modified script tag
 */
function aqualuxe_async_scripts($tag, $handle) {
    $scripts_to_async = [
        // Add script handles here
    ];
    
    if (in_array($handle, $scripts_to_async, true)) {
        return str_replace(' src', ' async src', $tag);
    }
    
    return $tag;
}
add_filter('script_loader_tag', 'aqualuxe_async_scripts', 10, 2);

/**
 * Add resource hints
 *
 * @param array  $urls          URLs to print for resource hints
 * @param string $relation_type The relation type the URLs are printed
 * @return array URLs to print for resource hints
 */
function aqualuxe_resource_hints($urls, $relation_type) {
    if ('preconnect' === $relation_type) {
        // Add Google Fonts domain
        $urls[] = [
            'href' => 'https://fonts.googleapis.com',
            'crossorigin',
        ];
        $urls[] = [
            'href' => 'https://fonts.gstatic.com',
            'crossorigin',
        ];
    }
    
    return $urls;
}
add_filter('wp_resource_hints', 'aqualuxe_resource_hints', 10, 2);

/**
 * Add custom inline CSS
 */
function aqualuxe_custom_inline_css() {
    $custom_css = get_theme_mod('aqualuxe_custom_css', '');
    
    if ($custom_css) {
        wp_add_inline_style('aqualuxe-style', $custom_css);
    }
}
add_action('wp_enqueue_scripts', 'aqualuxe_custom_inline_css', 20);

/**
 * Add custom inline JavaScript
 */
function aqualuxe_custom_inline_js() {
    $custom_js = get_theme_mod('aqualuxe_custom_js', '');
    
    if ($custom_js) {
        wp_add_inline_script('aqualuxe-script', $custom_js);
    }
}
add_action('wp_enqueue_scripts', 'aqualuxe_custom_inline_js', 20);

/**
 * Remove emoji scripts
 */
function aqualuxe_disable_emojis() {
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
    
    add_filter('tiny_mce_plugins', 'aqualuxe_disable_emojis_tinymce');
    add_filter('wp_resource_hints', 'aqualuxe_disable_emojis_remove_dns_prefetch', 10, 2);
}
add_action('init', 'aqualuxe_disable_emojis');

/**
 * Filter function used to remove the tinymce emoji plugin
 *
 * @param array $plugins TinyMCE plugins
 * @return array Modified TinyMCE plugins
 */
function aqualuxe_disable_emojis_tinymce($plugins) {
    if (is_array($plugins)) {
        return array_diff($plugins, ['wpemoji']);
    }
    
    return [];
}

/**
 * Remove emoji CDN hostname from DNS prefetching hints
 *
 * @param array  $urls          URLs to print for resource hints
 * @param string $relation_type The relation type the URLs are printed
 * @return array URLs to print for resource hints
 */
function aqualuxe_disable_emojis_remove_dns_prefetch($urls, $relation_type) {
    if ('dns-prefetch' === $relation_type) {
        $emoji_svg_url = apply_filters('emoji_svg_url', 'https://s.w.org/images/core/emoji/2.2.1/svg/');
        $urls = array_diff($urls, [$emoji_svg_url]);
    }
    
    return $urls;
}

/**
 * Remove jQuery migrate
 *
 * @param WP_Scripts $scripts WP_Scripts object
 */
function aqualuxe_remove_jquery_migrate($scripts) {
    if (!is_admin() && isset($scripts->registered['jquery'])) {
        $script = $scripts->registered['jquery'];
        
        if ($script->deps) {
            $script->deps = array_diff($script->deps, ['jquery-migrate']);
        }
    }
}
add_action('wp_default_scripts', 'aqualuxe_remove_jquery_migrate');

/**
 * Remove unnecessary WordPress meta tags
 */
function aqualuxe_remove_unnecessary_meta() {
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wp_shortlink_wp_head');
    remove_action('wp_head', 'adjacent_posts_rel_link_wp_head');
    remove_action('wp_head', 'rest_output_link_wp_head');
    remove_action('wp_head', 'wp_oembed_add_discovery_links');
    remove_action('wp_head', 'wp_oembed_add_host_js');
}
add_action('init', 'aqualuxe_remove_unnecessary_meta');

/**
 * Add DNS prefetch for external resources
 */
function aqualuxe_dns_prefetch() {
    echo '<link rel="dns-prefetch" href="//fonts.googleapis.com">';
    echo '<link rel="dns-prefetch" href="//fonts.gstatic.com">';
    echo '<link rel="dns-prefetch" href="//ajax.googleapis.com">';
    echo '<link rel="dns-prefetch" href="//s.w.org">';
    
    if (aqualuxe_is_woocommerce_active()) {
        echo '<link rel="dns-prefetch" href="//stats.wp.com">';
        echo '<link rel="dns-prefetch" href="//c0.wp.com">';
    }
}
add_action('wp_head', 'aqualuxe_dns_prefetch', 0);

/**
 * Add theme color meta tag
 */
function aqualuxe_theme_color_meta() {
    $primary_color = get_theme_mod('aqualuxe_primary_color', '#0073aa');
    echo '<meta name="theme-color" content="' . esc_attr($primary_color) . '">';
}
add_action('wp_head', 'aqualuxe_theme_color_meta');

/**
 * Add mobile viewport meta tag
 */
function aqualuxe_mobile_viewport_meta() {
    echo '<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">';
}
add_action('wp_head', 'aqualuxe_mobile_viewport_meta', 0);

/**
 * Add pingback header
 */
function aqualuxe_pingback_header() {
    if (is_singular() && pings_open()) {
        echo '<link rel="pingback" href="' . esc_url(get_bloginfo('pingback_url')) . '">';
    }
}
add_action('wp_head', 'aqualuxe_pingback_header');