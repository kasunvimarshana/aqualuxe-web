<?php
/**
 * Performance Optimization Class
 *
 * @package AquaLuxe\Core
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Performance optimization features
 */
class AquaLuxe_Performance {
    
    /**
     * Constructor
     */
    public function __construct() {
        add_action('init', array($this, 'init_performance_features'));
        add_action('wp_enqueue_scripts', array($this, 'optimize_scripts'), 100);
        add_filter('script_loader_tag', array($this, 'add_async_defer_attributes'), 10, 2);
        add_action('wp_head', array($this, 'add_preload_hints'), 1);
        add_filter('wp_resource_hints', array($this, 'add_dns_prefetch'), 10, 2);
        
        // Image optimization
        add_filter('wp_get_attachment_image_attributes', array($this, 'add_lazy_loading'), 10, 3);
        add_filter('the_content', array($this, 'add_lazy_loading_to_content'));
        
        // Cache optimization
        add_action('wp', array($this, 'setup_caching'));
    }
    
    /**
     * Initialize performance features
     */
    public function init_performance_features() {
        // Remove unnecessary WordPress features for performance
        remove_action('wp_head', 'wp_generator');
        remove_action('wp_head', 'wlwmanifest_link');
        remove_action('wp_head', 'rsd_link');
        remove_action('wp_head', 'wp_shortlink_wp_head');
        remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10);
        
        // Disable emoji scripts and styles
        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('admin_print_scripts', 'print_emoji_detection_script');
        remove_action('wp_print_styles', 'print_emoji_styles');
        remove_action('admin_print_styles', 'print_emoji_styles');
        remove_filter('the_content_feed', 'wp_staticize_emoji');
        remove_filter('comment_text_rss', 'wp_staticize_emoji');
        remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
        
        // Disable embeds
        add_filter('embed_oembed_discover', '__return_false');
        remove_action('wp_head', 'wp_oembed_add_discovery_links');
        remove_action('wp_head', 'wp_oembed_add_host_js');
        
        // Clean up head
        remove_action('wp_head', 'feed_links_extra', 3);
        remove_action('wp_head', 'feed_links', 2);
        remove_action('wp_head', 'index_rel_link');
        remove_action('wp_head', 'parent_post_rel_link', 10);
        remove_action('wp_head', 'start_post_rel_link', 10);
        
        // Optimize heartbeat
        add_filter('heartbeat_settings', array($this, 'optimize_heartbeat'));
    }
    
    /**
     * Optimize script loading
     */
    public function optimize_scripts() {
        // Dequeue unnecessary scripts
        if (!is_admin()) {
            wp_dequeue_script('wp-embed');
            
            // Conditionally load jQuery migrate
            if (!aqualuxe_get_option('enable_jquery_migrate', false)) {
                wp_deregister_script('jquery-migrate');
            }
            
            // Move jQuery to footer if enabled
            if (aqualuxe_get_option('jquery_to_footer', true)) {
                wp_scripts()->add_data('jquery', 'group', 1);
                wp_scripts()->add_data('jquery-core', 'group', 1);
                wp_scripts()->add_data('jquery-migrate', 'group', 1);
            }
        }
    }
    
    /**
     * Add async and defer attributes to scripts
     */
    public function add_async_defer_attributes($tag, $handle) {
        // Scripts to defer
        $defer_scripts = array(
            'aqualuxe-script',
            'aqualuxe-woocommerce',
            'contact-form-7'
        );
        
        // Scripts to load async
        $async_scripts = array(
            'google-analytics',
            'gtag'
        );
        
        if (in_array($handle, $defer_scripts)) {
            return str_replace('<script ', '<script defer ', $tag);
        }
        
        if (in_array($handle, $async_scripts)) {
            return str_replace('<script ', '<script async ', $tag);
        }
        
        return $tag;
    }
    
    /**
     * Add preload hints
     */
    public function add_preload_hints() {
        // Preload critical fonts
        $fonts_to_preload = array(
            'Inter-Regular.woff2',
            'Inter-Medium.woff2',
            'PlayfairDisplay-Regular.woff2'
        );
        
        foreach ($fonts_to_preload as $font) {
            $font_url = AQUALUXE_ASSETS_URI . '/fonts/' . $font;
            if (file_exists(AQUALUXE_THEME_DIR . '/assets/fonts/' . $font)) {
                echo '<link rel="preload" href="' . esc_url($font_url) . '" as="font" type="font/woff2" crossorigin>' . "\n";
            }
        }
        
        // Preload hero image on homepage
        if (is_front_page()) {
            $hero_image = get_theme_mod('hero_background_image');
            if ($hero_image) {
                echo '<link rel="preload" href="' . esc_url($hero_image) . '" as="image">' . "\n";
            }
        }
    }
    
    /**
     * Add DNS prefetch hints
     */
    public function add_dns_prefetch($urls, $relation_type) {
        if ('dns-prefetch' === $relation_type) {
            $urls[] = '//fonts.googleapis.com';
            $urls[] = '//fonts.gstatic.com';
            $urls[] = '//www.google-analytics.com';
            $urls[] = '//maps.googleapis.com';
        }
        
        return $urls;
    }
    
    /**
     * Add lazy loading to images
     */
    public function add_lazy_loading($attr, $attachment, $size) {
        if (is_admin() || is_feed() || is_preview()) {
            return $attr;
        }
        
        // Skip lazy loading for above-the-fold images
        if ($this->is_above_fold_image($attachment->ID)) {
            return $attr;
        }
        
        $attr['loading'] = 'lazy';
        $attr['decoding'] = 'async';
        
        return $attr;
    }
    
    /**
     * Add lazy loading to content images
     */
    public function add_lazy_loading_to_content($content) {
        if (is_admin() || is_feed() || is_preview()) {
            return $content;
        }
        
        // Add loading="lazy" to img tags that don't have it
        $content = preg_replace('/<img((?:(?!loading=)[^>])*)>/i', '<img$1 loading="lazy" decoding="async">', $content);
        
        return $content;
    }
    
    /**
     * Check if image is above the fold
     */
    private function is_above_fold_image($attachment_id) {
        // Consider first few images as above-the-fold
        static $image_count = 0;
        $image_count++;
        
        // First 2 images are considered above-the-fold
        return $image_count <= 2;
    }
    
    /**
     * Setup caching headers
     */
    public function setup_caching() {
        if (is_admin() || is_user_logged_in()) {
            return;
        }
        
        // Set cache headers for static content
        if (is_front_page() || is_page()) {
            header('Cache-Control: public, max-age=3600'); // 1 hour
        } elseif (is_single()) {
            header('Cache-Control: public, max-age=7200'); // 2 hours
        }
    }
    
    /**
     * Optimize WordPress heartbeat
     */
    public function optimize_heartbeat($settings) {
        // Slow down heartbeat on non-post pages
        if (!isset($_GET['post']) || !is_admin()) {
            $settings['interval'] = 60; // 60 seconds instead of 15
        }
        
        return $settings;
    }
    
    /**
     * Critical CSS inline injection
     */
    public function inject_critical_css() {
        $critical_css_file = AQUALUXE_THEME_DIR . '/assets/css/critical.css';
        
        if (file_exists($critical_css_file)) {
            $critical_css = file_get_contents($critical_css_file);
            echo '<style id="critical-css">' . wp_strip_all_tags($critical_css) . '</style>' . "\n";
        }
    }
    
    /**
     * Database query optimization
     */
    public function optimize_queries() {
        // Reduce the number of revisions
        if (!defined('WP_POST_REVISIONS')) {
            define('WP_POST_REVISIONS', 3);
        }
        
        // Optimize database queries
        add_filter('posts_clauses', array($this, 'optimize_search_query'), 10, 2);
    }
    
    /**
     * Optimize search queries
     */
    public function optimize_search_query($clauses, $query) {
        if (is_admin() || !$query->is_search() || !$query->is_main_query()) {
            return $clauses;
        }
        
        // Limit search to post_title and post_content only
        $clauses['where'] = preg_replace(
            '/\(\(\(\w+\.post_title LIKE \'[^\']+\'\) OR \(\w+\.post_excerpt LIKE \'[^\']+\'\) OR \(\w+\.post_content LIKE \'[^\']+\'\)\)\)/',
            '(($1.post_title LIKE \'%' . get_search_query() . '%\') OR ($1.post_content LIKE \'%' . get_search_query() . '%\'))',
            $clauses['where']
        );
        
        return $clauses;
    }
    
    /**
     * Minify HTML output
     */
    public function minify_html($buffer) {
        if (is_admin() || is_feed() || is_preview()) {
            return $buffer;
        }
        
        // Remove HTML comments (except IE conditionals)
        $buffer = preg_replace('/<!--(?!\s*(?:\[if [^\]]+]|<!|>))(?:(?!-->).)*-->/s', '', $buffer);
        
        // Remove whitespace
        $buffer = preg_replace('/>\s+</', '><', $buffer);
        $buffer = preg_replace('/\s+/', ' ', $buffer);
        
        return trim($buffer);
    }
    
    /**
     * Enable HTML minification
     */
    public function enable_html_minification() {
        if (aqualuxe_get_option('enable_html_minification', false)) {
            ob_start(array($this, 'minify_html'));
        }
    }
}

// Initialize performance optimization
new AquaLuxe_Performance();