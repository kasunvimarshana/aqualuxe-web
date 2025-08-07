<?php
/**
 * Performance optimization features for AquaLuxe theme
 *
 * @package AquaLuxe
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Defer non-critical JavaScript
 */
function aqualuxe_defer_parsing_of_js($url) {
    // Don't defer jQuery and other critical scripts
    $defer_scripts = array('jquery.js', 'jquery.min.js');
    
    foreach ($defer_scripts as $script) {
        if (strpos($url, $script) !== false) {
            return $url;
        }
    }
    
    // Defer all other scripts
    return str_replace(' src', ' defer src', $url);
}
add_filter('script_loader_tag', 'aqualuxe_defer_parsing_of_js', 10, 2);

/**
 * Preconnect to external domains
 */
function aqualuxe_preconnect_resources() {
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
}
add_action('wp_head', 'aqualuxe_preconnect_resources', 1);

/**
 * Remove query strings from static resources
 */
function aqualuxe_remove_query_strings($src) {
    if (strpos($src, '?ver=')) {
        $src = remove_query_arg('ver', $src);
    }
    return $src;
}
add_filter('script_loader_src', 'aqualuxe_remove_query_strings', 15, 1);
add_filter('style_loader_src', 'aqualuxe_remove_query_strings', 15, 1);

/**
 * Optimize images
 */
function aqualuxe_optimize_images($content) {
    // Add width and height attributes to images
    $content = preg_replace_callback('/<img[^>]+src=["\']([^"\']+)["\'][^>]*>/i', function($matches) {
        $img = $matches[0];
        
        // Skip if already has width/height
        if (strpos($img, 'width=') !== false && strpos($img, 'height=') !== false) {
            return $img;
        }
        
        // Get image dimensions
        $src = $matches[1];
        $image_path = str_replace(home_url(), ABSPATH, $src);
        
        if (file_exists($image_path)) {
            $size = getimagesize($image_path);
            if ($size) {
                $img = str_replace('<img', '<img width="' . $size[0] . '" height="' . $size[1] . '"', $img);
            }
        }
        
        return $img;
    }, $content);
    
    return $content;
}
add_filter('the_content', 'aqualuxe_optimize_images');
add_filter('post_thumbnail_html', 'aqualuxe_optimize_images');

/**
 * Enable compression
 */
function aqualuxe_enable_compression() {
    if (!is_admin() && !defined('DOING_AJAX') && !defined('DOING_CRON')) {
        if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) && strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== false) {
            ob_start('ob_gzhandler');
        }
    }
}
add_action('init', 'aqualuxe_enable_compression');

/**
 * Remove unnecessary WordPress features for performance
 */
function aqualuxe_remove_unnecessary_features() {
    // Remove emoji support
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
    
    // Remove REST API links
    remove_action('wp_head', 'rest_output_link_wp_head');
    remove_action('wp_head', 'wp_oembed_add_discovery_links');
    
    // Remove generator tag
    remove_action('wp_head', 'wp_generator');
    
    // Remove wlwmanifest and EditURI links
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'index_rel_link');
    remove_action('wp_head', 'parent_post_rel_link');
    remove_action('wp_head', 'start_post_rel_link');
    remove_action('wp_head', 'adjacent_posts_rel_link_wp_head');
    remove_action('wp_head', 'wp_shortlink_wp_head');
}
add_action('init', 'aqualuxe_remove_unnecessary_features');

/**
 * Optimize WooCommerce performance
 */
function aqualuxe_optimize_woocommerce() {
    // Remove generator tag
    add_filter('woocommerce_generator_tag', '__return_false');
    
    // Optimize product image sizes
    add_filter('woocommerce_get_image_size_thumbnail', function($size) {
        return array(
            'width' => 300,
            'height' => 300,
            'crop' => 1,
        );
    });
    
    add_filter('woocommerce_get_image_size_single', function($size) {
        return array(
            'width' => 600,
            'height' => 600,
            'crop' => 1,
        );
    });
    
    add_filter('woocommerce_get_image_size_gallery_thumbnail', function($size) {
        return array(
            'width' => 150,
            'height' => 150,
            'crop' => 1,
        );
    });
}
add_action('init', 'aqualuxe_optimize_woocommerce');