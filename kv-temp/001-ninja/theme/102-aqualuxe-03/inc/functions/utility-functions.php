<?php
/**
 * Utility Functions
 *
 * Custom utility functions for this theme.
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('aqualuxe_is_woocommerce_activated')) :
    /**
     * Check if WooCommerce is activated
     */
    function aqualuxe_is_woocommerce_activated() {
        return class_exists('WooCommerce');
    }
endif;

if (!function_exists('aqualuxe_is_woocommerce_page')) :
    /**
     * Check if current page is a WooCommerce page
     */
    function aqualuxe_is_woocommerce_page() {
        if (!aqualuxe_is_woocommerce_activated()) {
            return false;
        }
        
        return is_woocommerce() || is_cart() || is_checkout() || is_account_page();
    }
endif;

if (!function_exists('aqualuxe_get_theme_option')) :
    /**
     * Get theme option with fallback
     */
    function aqualuxe_get_theme_option($option_name, $default = '') {
        return get_theme_mod($option_name, $default);
    }
endif;

if (!function_exists('aqualuxe_get_attachment_id_by_url')) :
    /**
     * Get attachment ID by URL
     */
    function aqualuxe_get_attachment_id_by_url($url) {
        $attachment_id = 0;
        $dir = wp_upload_dir();
        
        if (false !== strpos($url, $dir['baseurl'] . '/')) {
            $file = basename($url);
            $query_args = array(
                'post_type'   => 'attachment',
                'post_status' => 'inherit',
                'fields'      => 'ids',
                'meta_query'  => array(
                    array(
                        'value'   => $file,
                        'compare' => 'LIKE',
                        'key'     => '_wp_attachment_metadata',
                    ),
                ),
            );
            
            $query = new WP_Query($query_args);
            
            if ($query->have_posts()) {
                foreach ($query->posts as $post_id) {
                    $meta = wp_get_attachment_metadata($post_id);
                    $original_file = basename($meta['file']);
                    $cropped_image_files = wp_list_pluck($meta['sizes'], 'file');
                    
                    if ($original_file === $file || in_array($file, $cropped_image_files)) {
                        $attachment_id = $post_id;
                        break;
                    }
                }
            }
        }
        
        return $attachment_id;
    }
endif;

if (!function_exists('aqualuxe_sanitize_html_class')) :
    /**
     * Sanitize HTML class names
     */
    function aqualuxe_sanitize_html_class($class, $fallback = '') {
        // Strip out any % encoded characters
        $sanitized = preg_replace('|%[a-fA-F0-9][a-fA-F0-9]|', '', $class);
        
        // Remove any non-alphanumeric characters except dashes and underscores
        $sanitized = preg_replace('/[^A-Za-z0-9_-]/', '', $sanitized);
        
        if ('' === $sanitized && $fallback) {
            return aqualuxe_sanitize_html_class($fallback);
        }
        
        return $sanitized;
    }
endif;

if (!function_exists('aqualuxe_get_post_meta')) :
    /**
     * Get post meta with sanitization
     */
    function aqualuxe_get_post_meta($post_id, $meta_key, $single = true, $sanitize_callback = 'sanitize_text_field') {
        $meta_value = get_post_meta($post_id, $meta_key, $single);
        
        if ($sanitize_callback && is_callable($sanitize_callback)) {
            if (is_array($meta_value)) {
                $meta_value = array_map($sanitize_callback, $meta_value);
            } else {
                $meta_value = call_user_func($sanitize_callback, $meta_value);
            }
        }
        
        return $meta_value;
    }
endif;

if (!function_exists('aqualuxe_get_reading_time')) :
    /**
     * Calculate estimated reading time for post content
     */
    function aqualuxe_get_reading_time($post_id = null) {
        if (!$post_id) {
            $post_id = get_the_ID();
        }
        
        $content = get_post_field('post_content', $post_id);
        $word_count = str_word_count(strip_tags($content));
        $reading_time = ceil($word_count / 200); // Average reading speed: 200 words per minute
        
        return $reading_time;
    }
endif;

if (!function_exists('aqualuxe_get_estimated_reading_time')) :
    /**
     * Get formatted reading time string
     */
    function aqualuxe_get_estimated_reading_time($post_id = null) {
        $minutes = aqualuxe_get_reading_time($post_id);
        
        if ($minutes < 1) {
            return __('Less than a minute', 'aqualuxe');
        } elseif ($minutes === 1) {
            return __('1 minute read', 'aqualuxe');
        } else {
            return sprintf(_n('%s minute read', '%s minutes read', $minutes, 'aqualuxe'), $minutes);
        }
    }
endif;

if (!function_exists('aqualuxe_get_social_share_links')) :
    /**
     * Get social share links for current post
     */
    function aqualuxe_get_social_share_links($post_id = null) {
        if (!$post_id) {
            $post_id = get_the_ID();
        }
        
        $post_url = get_permalink($post_id);
        $post_title = get_the_title($post_id);
        $post_excerpt = get_the_excerpt($post_id);
        
        $share_links = array(
            'facebook' => array(
                'url' => 'https://www.facebook.com/sharer/sharer.php?u=' . urlencode($post_url),
                'label' => __('Share on Facebook', 'aqualuxe'),
                'icon' => 'facebook'
            ),
            'twitter' => array(
                'url' => 'https://twitter.com/intent/tweet?url=' . urlencode($post_url) . '&text=' . urlencode($post_title),
                'label' => __('Share on Twitter', 'aqualuxe'),
                'icon' => 'twitter'
            ),
            'linkedin' => array(
                'url' => 'https://www.linkedin.com/sharing/share-offsite/?url=' . urlencode($post_url),
                'label' => __('Share on LinkedIn', 'aqualuxe'),
                'icon' => 'linkedin'
            ),
            'pinterest' => array(
                'url' => 'https://pinterest.com/pin/create/button/?url=' . urlencode($post_url) . '&description=' . urlencode($post_title),
                'label' => __('Share on Pinterest', 'aqualuxe'),
                'icon' => 'pinterest'
            ),
            'email' => array(
                'url' => 'mailto:?subject=' . urlencode($post_title) . '&body=' . urlencode($post_excerpt . ' ' . $post_url),
                'label' => __('Share via Email', 'aqualuxe'),
                'icon' => 'email'
            )
        );
        
        return apply_filters('aqualuxe_social_share_links', $share_links, $post_id);
    }
endif;

if (!function_exists('aqualuxe_get_theme_colors')) :
    /**
     * Get theme color palette
     */
    function aqualuxe_get_theme_colors() {
        $colors = array(
            'primary' => aqualuxe_get_theme_option('primary_color', '#14b8a6'),
            'secondary' => aqualuxe_get_theme_option('secondary_color', '#64748b'),
            'accent' => aqualuxe_get_theme_option('accent_color', '#d946ef'),
            'aqua' => aqualuxe_get_theme_option('aqua_color', '#06b6d4'),
            'luxury' => aqualuxe_get_theme_option('luxury_color', '#eab308'),
        );
        
        return apply_filters('aqualuxe_theme_colors', $colors);
    }
endif;

if (!function_exists('aqualuxe_hex_to_rgb')) :
    /**
     * Convert hex color to RGB
     */
    function aqualuxe_hex_to_rgb($hex) {
        $hex = str_replace('#', '', $hex);
        
        if (strlen($hex) === 3) {
            $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
            $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
            $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
        } else {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        }
        
        return array($r, $g, $b);
    }
endif;

if (!function_exists('aqualuxe_get_contrast_color')) :
    /**
     * Get contrasting color (black or white) for given color
     */
    function aqualuxe_get_contrast_color($hex_color) {
        $rgb = aqualuxe_hex_to_rgb($hex_color);
        $brightness = (($rgb[0] * 299) + ($rgb[1] * 587) + ($rgb[2] * 114)) / 1000;
        
        return ($brightness > 155) ? '#000000' : '#ffffff';
    }
endif;

if (!function_exists('aqualuxe_minify_css')) :
    /**
     * Minify CSS string
     */
    function aqualuxe_minify_css($css) {
        // Remove comments
        $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
        
        // Remove space after colons
        $css = str_replace(': ', ':', $css);
        
        // Remove whitespace
        $css = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $css);
        
        return $css;
    }
endif;

if (!function_exists('aqualuxe_is_mobile')) :
    /**
     * Check if current request is from mobile device
     */
    function aqualuxe_is_mobile() {
        return wp_is_mobile();
    }
endif;

if (!function_exists('aqualuxe_get_current_url')) :
    /**
     * Get current page URL
     */
    function aqualuxe_get_current_url() {
        global $wp;
        return home_url(add_query_arg(array(), $wp->request));
    }
endif;

if (!function_exists('aqualuxe_limit_text')) :
    /**
     * Limit text to specified number of characters
     */
    function aqualuxe_limit_text($text, $limit = 100, $ending = '...') {
        if (strlen($text) > $limit) {
            $text = substr($text, 0, $limit);
            $text = substr($text, 0, strrpos($text, ' ')) . $ending;
        }
        
        return $text;
    }
endif;

if (!function_exists('aqualuxe_get_related_posts')) :
    /**
     * Get related posts based on categories or tags
     */
    function aqualuxe_get_related_posts($post_id = null, $number = 3) {
        if (!$post_id) {
            $post_id = get_the_ID();
        }
        
        $categories = get_the_category($post_id);
        $category_ids = array();
        
        if ($categories) {
            foreach ($categories as $category) {
                $category_ids[] = $category->term_id;
            }
        }
        
        $args = array(
            'post_type' => 'post',
            'posts_per_page' => $number,
            'post__not_in' => array($post_id),
            'category__in' => $category_ids,
            'orderby' => 'rand'
        );
        
        $related_posts = get_posts($args);
        
        // If no related posts found by category, try by tags
        if (empty($related_posts)) {
            $tags = get_the_tags($post_id);
            $tag_ids = array();
            
            if ($tags) {
                foreach ($tags as $tag) {
                    $tag_ids[] = $tag->term_id;
                }
                
                $args['category__in'] = null;
                $args['tag__in'] = $tag_ids;
                
                $related_posts = get_posts($args);
            }
        }
        
        return $related_posts;
    }
endif;