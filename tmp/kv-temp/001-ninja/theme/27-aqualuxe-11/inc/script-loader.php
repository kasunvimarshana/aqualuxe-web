<?php
/**
 * Script and Style Loader for AquaLuxe Theme
 *
 * Handles proper loading of scripts and styles with fallbacks
 *
 * @package AquaLuxe
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enqueue scripts and styles for the theme
 */
function aqualuxe_scripts() {
    // Register and enqueue styles
    wp_enqueue_style('aqualuxe-style', get_stylesheet_uri(), array(), AQUALUXE_VERSION);
    wp_enqueue_style('aqualuxe-main', get_template_directory_uri() . '/assets/css/main.css', array(), AQUALUXE_VERSION);
    
    // Register and enqueue Swiper.js
    wp_register_style('swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css', array(), '8.4.7');
    wp_register_script('swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js', array(), '8.4.7', true);
    
    // Local fallback for Swiper.js
    wp_add_inline_script('swiper-js', aqualuxe_swiper_fallback_script());
    
    // Register and enqueue Chart.js
    wp_register_script('chart-js', 'https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js', array(), '3.9.1', true);
    
    // Local fallback for Chart.js
    wp_add_inline_script('chart-js', aqualuxe_chart_fallback_script());
    
    // Main theme scripts
    wp_enqueue_script('aqualuxe-navigation', get_template_directory_uri() . '/assets/js/navigation.js', array(), AQUALUXE_VERSION, true);
    wp_enqueue_script('aqualuxe-main', get_template_directory_uri() . '/assets/js/main.js', array('jquery', 'swiper-js'), AQUALUXE_VERSION, true);
    
    // Localize script with theme data
    wp_localize_script('aqualuxe-main', 'aqualuxeData', array(
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('aqualuxe-nonce'),
        'themeUri' => get_template_directory_uri(),
        'siteUrl' => site_url(),
        'isWooCommerceActive' => aqualuxe_is_woocommerce_active() ? 'yes' : 'no',
    ));
    
    // Conditionally load WooCommerce scripts
    if (aqualuxe_is_woocommerce_active()) {
        wp_enqueue_style('aqualuxe-woocommerce', get_template_directory_uri() . '/assets/css/woocommerce.css', array(), AQUALUXE_VERSION);
        wp_enqueue_script('aqualuxe-woocommerce', get_template_directory_uri() . '/assets/js/woocommerce.js', array('jquery'), AQUALUXE_VERSION, true);
    } else {
        // Load fallback styles for WooCommerce elements
        wp_enqueue_style('aqualuxe-woocommerce-fallback', get_template_directory_uri() . '/assets/css/woocommerce-fallback.css', array(), AQUALUXE_VERSION);
    }
    
    // Load dark mode if enabled
    $enable_dark_mode = get_theme_mod('aqualuxe_enable_dark_mode', true);
    if ($enable_dark_mode) {
        wp_enqueue_style('aqualuxe-dark-mode', get_template_directory_uri() . '/assets/css/dark-mode.css', array(), AQUALUXE_VERSION);
        wp_enqueue_script('aqualuxe-dark-mode', get_template_directory_uri() . '/assets/js/dark-mode.js', array('jquery'), AQUALUXE_VERSION, true);
    }
    
    // Load comment reply script if needed
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
    
    // Load Gutenberg block styles for front-end
    wp_enqueue_style('aqualuxe-blocks', get_template_directory_uri() . '/assets/css/blocks.css', array(), AQUALUXE_VERSION);
}
add_action('wp_enqueue_scripts', 'aqualuxe_scripts');

/**
 * Enqueue admin scripts and styles
 */
function aqualuxe_admin_scripts() {
    // Admin styles
    wp_enqueue_style('aqualuxe-admin-style', get_template_directory_uri() . '/inc/admin/css/aqualuxe-api-admin.css', array(), AQUALUXE_VERSION);
    
    // Admin scripts
    wp_enqueue_script('aqualuxe-admin-script', get_template_directory_uri() . '/inc/admin/js/aqualuxe-api-admin.js', array('jquery'), AQUALUXE_VERSION, true);
    
    // Localize admin script
    wp_localize_script('aqualuxe-admin-script', 'aqualuxeAdminData', array(
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('aqualuxe-admin-nonce'),
        'isWooCommerceActive' => aqualuxe_is_woocommerce_active() ? 'yes' : 'no',
    ));
}
add_action('admin_enqueue_scripts', 'aqualuxe_admin_scripts');

/**
 * Enqueue block editor assets
 */
function aqualuxe_block_editor_assets() {
    // Block editor styles
    wp_enqueue_style('aqualuxe-blocks-editor', get_template_directory_uri() . '/assets/css/blocks-editor.css', array('wp-edit-blocks'), AQUALUXE_VERSION);
    
    // Block editor scripts
    wp_enqueue_script('aqualuxe-blocks-editor', get_template_directory_uri() . '/assets/js/blocks-editor.js', array('wp-blocks', 'wp-element', 'wp-editor', 'wp-components', 'wp-i18n', 'wp-data'), AQUALUXE_VERSION, true);
    
    // Localize block editor script
    wp_localize_script('aqualuxe-blocks-editor', 'aqualuxeEditorData', array(
        'isWooCommerceActive' => aqualuxe_is_woocommerce_active() ? 'yes' : 'no',
    ));
}
add_action('enqueue_block_editor_assets', 'aqualuxe_block_editor_assets');

/**
 * Generate fallback script for Swiper.js
 */
function aqualuxe_swiper_fallback_script() {
    return "
        (function() {
            var swiper = window.Swiper;
            if (typeof swiper === 'undefined') {
                var script = document.createElement('script');
                script.src = '" . get_template_directory_uri() . "/assets/js/vendor/swiper-bundle.min.js';
                script.async = true;
                document.head.appendChild(script);
                
                var link = document.createElement('link');
                link.rel = 'stylesheet';
                link.href = '" . get_template_directory_uri() . "/assets/css/vendor/swiper-bundle.min.css';
                document.head.appendChild(link);
                
                console.log('Loaded local fallback for Swiper.js');
            }
        })();
    ";
}

/**
 * Generate fallback script for Chart.js
 */
function aqualuxe_chart_fallback_script() {
    return "
        (function() {
            var Chart = window.Chart;
            if (typeof Chart === 'undefined') {
                var script = document.createElement('script');
                script.src = '" . get_template_directory_uri() . "/assets/js/vendor/chart.min.js';
                script.async = true;
                document.head.appendChild(script);
                console.log('Loaded local fallback for Chart.js');
            }
        })();
    ";
}

/**
 * Add preconnect for CDN domains
 */
function aqualuxe_resource_hints_for_cdn($urls, $relation_type) {
    if ('preconnect' === $relation_type) {
        // Add CDN domains
        $urls[] = array(
            'href' => 'https://cdn.jsdelivr.net',
            'crossorigin',
        );
    }
    
    return $urls;
}
add_filter('wp_resource_hints', 'aqualuxe_resource_hints_for_cdn', 10, 2);

/**
 * Create local copies of CDN libraries
 * This function runs on theme activation to download local copies of CDN libraries
 */
function aqualuxe_download_cdn_libraries() {
    // Create vendor directories if they don't exist
    $js_vendor_dir = get_template_directory() . '/assets/js/vendor';
    $css_vendor_dir = get_template_directory() . '/assets/css/vendor';
    
    if (!is_dir($js_vendor_dir)) {
        wp_mkdir_p($js_vendor_dir);
    }
    
    if (!is_dir($css_vendor_dir)) {
        wp_mkdir_p($css_vendor_dir);
    }
    
    // Download Swiper.js
    if (!file_exists($js_vendor_dir . '/swiper-bundle.min.js')) {
        $swiper_js = wp_remote_get('https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js');
        if (!is_wp_error($swiper_js) && 200 === wp_remote_retrieve_response_code($swiper_js)) {
            file_put_contents($js_vendor_dir . '/swiper-bundle.min.js', wp_remote_retrieve_body($swiper_js));
        }
    }
    
    // Download Swiper.css
    if (!file_exists($css_vendor_dir . '/swiper-bundle.min.css')) {
        $swiper_css = wp_remote_get('https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css');
        if (!is_wp_error($swiper_css) && 200 === wp_remote_retrieve_response_code($swiper_css)) {
            file_put_contents($css_vendor_dir . '/swiper-bundle.min.css', wp_remote_retrieve_body($swiper_css));
        }
    }
    
    // Download Chart.js
    if (!file_exists($js_vendor_dir . '/chart.min.js')) {
        $chart_js = wp_remote_get('https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js');
        if (!is_wp_error($chart_js) && 200 === wp_remote_retrieve_response_code($chart_js)) {
            file_put_contents($js_vendor_dir . '/chart.min.js', wp_remote_retrieve_body($chart_js));
        }
    }
}
add_action('after_switch_theme', 'aqualuxe_download_cdn_libraries');

/**
 * Add async/defer attributes to scripts
 */
function aqualuxe_script_loader_tag($tag, $handle, $src) {
    // Add async attribute to non-critical scripts
    $async_scripts = array('aqualuxe-dark-mode');
    if (in_array($handle, $async_scripts)) {
        return str_replace(' src', ' async src', $tag);
    }
    
    // Add defer attribute to non-critical scripts
    $defer_scripts = array('aqualuxe-main');
    if (in_array($handle, $defer_scripts)) {
        return str_replace(' src', ' defer src', $tag);
    }
    
    return $tag;
}
add_filter('script_loader_tag', 'aqualuxe_script_loader_tag', 10, 3);

/**
 * Create vendor directories for local fallbacks
 */
function aqualuxe_create_vendor_directories() {
    // Create vendor directories if they don't exist
    $js_vendor_dir = get_template_directory() . '/assets/js/vendor';
    $css_vendor_dir = get_template_directory() . '/assets/css/vendor';
    
    if (!is_dir($js_vendor_dir)) {
        wp_mkdir_p($js_vendor_dir);
    }
    
    if (!is_dir($css_vendor_dir)) {
        wp_mkdir_p($css_vendor_dir);
    }
}
add_action('after_setup_theme', 'aqualuxe_create_vendor_directories');

/**
 * Add WooCommerce body class based on WooCommerce status
 */
function aqualuxe_woocommerce_body_class($classes) {
    if (aqualuxe_is_woocommerce_active()) {
        $classes[] = 'woocommerce-active';
    } else {
        $classes[] = 'woocommerce-inactive';
    }
    
    return $classes;
}
add_filter('body_class', 'aqualuxe_woocommerce_body_class');