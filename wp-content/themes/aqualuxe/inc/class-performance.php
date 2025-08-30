<?php
/**
 * AquaLuxe Performance Class
 * 
 * Handles performance optimizations and caching strategies.
 * 
 * @package AquaLuxe
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class AquaLuxe_Performance {
    
    /**
     * Initialize performance optimizations
     */
    public static function init() {
        add_action('wp_enqueue_scripts', [__CLASS__, 'optimize_scripts_styles'], 999);
        add_action('wp_head', [__CLASS__, 'add_preload_tags'], 1);
        add_action('wp_head', [__CLASS__, 'add_dns_prefetch'], 1);
        add_filter('script_loader_tag', [__CLASS__, 'add_async_defer'], 10, 3);
        add_filter('style_loader_tag', [__CLASS__, 'optimize_css_delivery'], 10, 4);
        
        // Image optimization
        add_filter('wp_get_attachment_image_attributes', [__CLASS__, 'add_lazy_loading'], 10, 3);
        add_filter('the_content', [__CLASS__, 'add_lazy_loading_to_content']);
        
        // Database optimization
        add_action('wp_loaded', [__CLASS__, 'optimize_database_queries']);
        
        // Cache headers
        add_action('send_headers', [__CLASS__, 'add_cache_headers']);
        
        // Cleanup
        add_action('init', [__CLASS__, 'cleanup_wp_head']);
        
        // Font optimization
        add_action('wp_head', [__CLASS__, 'preload_fonts'], 1);
        
        // Critical CSS
        add_action('wp_head', [__CLASS__, 'inline_critical_css'], 1);
    }
    
    /**
     * Optimize scripts and styles loading
     */
    public static function optimize_scripts_styles() {
        // Remove unused WordPress scripts/styles
        if (!is_admin()) {
            wp_deregister_script('wp-embed');
            wp_dequeue_style('wp-block-library');
            
            // Remove emoji scripts
            remove_action('wp_head', 'print_emoji_detection_script', 7);
            remove_action('wp_print_styles', 'print_emoji_styles');
        }
        
        // Combine and minify CSS (if not using webpack)
        if (!defined('WP_DEBUG') || !WP_DEBUG) {
            add_filter('style_loader_src', [__CLASS__, 'remove_version_from_assets'], 9999);
            add_filter('script_loader_src', [__CLASS__, 'remove_version_from_assets'], 9999);
        }
    }
    
    /**
     * Add preload tags for critical resources
     */
    public static function add_preload_tags() {
        // Preload critical CSS
        $critical_css = AQUALUXE_ASSETS_URL . '/css/critical.css';
        echo '<link rel="preload" href="' . esc_url($critical_css) . '" as="style" onload="this.onload=null;this.rel=\'stylesheet\'">' . "\n";
        
        // Preload main JavaScript
        $main_js = AQUALUXE_ASSETS_URL . '/js/main.js';
        echo '<link rel="preload" href="' . esc_url($main_js) . '" as="script">' . "\n";
        
        // Preload hero image
        if (is_front_page()) {
            $hero_image = get_theme_mod('aqualuxe_hero_image');
            if ($hero_image) {
                echo '<link rel="preload" href="' . esc_url($hero_image) . '" as="image">' . "\n";
            }
        }
    }
    
    /**
     * Add DNS prefetch for external domains
     */
    public static function add_dns_prefetch() {
        $domains = [
            '//fonts.googleapis.com',
            '//fonts.gstatic.com',
            '//www.google-analytics.com',
            '//www.googletagmanager.com'
        ];
        
        foreach ($domains as $domain) {
            echo '<link rel="dns-prefetch" href="' . esc_url($domain) . '">' . "\n";
        }
    }
    
    /**
     * Add async/defer attributes to scripts
     */
    public static function add_async_defer($tag, $handle, $src) {
        // Skip admin and login pages
        if (is_admin() || is_login()) {
            return $tag;
        }
        
        // Scripts to defer
        $defer_scripts = [
            'aqualuxe-main',
            'aqualuxe-woocommerce',
            'jquery'
        ];
        
        // Scripts to load async
        $async_scripts = [
            'google-analytics',
            'facebook-pixel'
        ];
        
        if (in_array($handle, $defer_scripts)) {
            return str_replace('<script ', '<script defer ', $tag);
        }
        
        if (in_array($handle, $async_scripts)) {
            return str_replace('<script ', '<script async ', $tag);
        }
        
        return $tag;
    }
    
    /**
     * Optimize CSS delivery
     */
    public static function optimize_css_delivery($html, $handle, $href, $media) {
        // Critical CSS should be inlined, others can be loaded asynchronously
        $non_critical_styles = [
            'aqualuxe-woocommerce',
            'aqualuxe-events',
            'aqualuxe-bookings'
        ];
        
        if (in_array($handle, $non_critical_styles)) {
            return '<link rel="preload" href="' . esc_url($href) . '" as="style" onload="this.onload=null;this.rel=\'stylesheet\'" media="' . esc_attr($media) . '">' . "\n";
        }
        
        return $html;
    }
    
    /**
     * Add lazy loading to images
     */
    public static function add_lazy_loading($attr, $attachment, $size) {
        if (!is_admin() && !is_feed()) {
            $attr['loading'] = 'lazy';
            $attr['decoding'] = 'async';
        }
        
        return $attr;
    }
    
    /**
     * Add lazy loading to content images
     */
    public static function add_lazy_loading_to_content($content) {
        if (is_admin() || is_feed()) {
            return $content;
        }
        
        // Add loading="lazy" to img tags
        $content = preg_replace('/<img((?![^>]*loading=)[^>]*)>/i', '<img$1 loading="lazy" decoding="async">', $content);
        
        return $content;
    }
    
    /**
     * Optimize database queries
     */
    public static function optimize_database_queries() {
        // Limit post revisions
        if (!defined('WP_POST_REVISIONS')) {
            define('WP_POST_REVISIONS', 3);
        }
        
        // Limit autosave interval
        if (!defined('AUTOSAVE_INTERVAL')) {
            define('AUTOSAVE_INTERVAL', 300); // 5 minutes
        }
        
        // Enable automatic database optimization
        if (!wp_next_scheduled('aqualuxe_optimize_database')) {
            wp_schedule_event(time(), 'weekly', 'aqualuxe_optimize_database');
        }
        
        add_action('aqualuxe_optimize_database', [__CLASS__, 'optimize_database']);
    }
    
    /**
     * Optimize database tables
     */
    public static function optimize_database() {
        global $wpdb;
        
        // Clean up spam comments
        $wpdb->query("DELETE FROM {$wpdb->comments} WHERE comment_approved = 'spam' AND comment_date < DATE_SUB(NOW(), INTERVAL 30 DAY)");
        
        // Clean up old transients
        $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_%' AND option_value < UNIX_TIMESTAMP()");
        
        // Clean up old post revisions
        $wpdb->query("DELETE FROM {$wpdb->posts} WHERE post_type = 'revision' AND post_date < DATE_SUB(NOW(), INTERVAL 30 DAY)");
        
        // Optimize tables
        $tables = $wpdb->get_results("SHOW TABLES", ARRAY_N);
        foreach ($tables as $table) {
            $wpdb->query("OPTIMIZE TABLE {$table[0]}");
        }
    }
    
    /**
     * Add cache headers
     */
    public static function add_cache_headers() {
        if (!is_admin() && !is_user_logged_in()) {
            $expires = 3600; // 1 hour
            
            // Longer cache for static assets
            if (strpos($_SERVER['REQUEST_URI'], '/assets/') !== false) {
                $expires = 31536000; // 1 year
            }
            
            header('Cache-Control: public, max-age=' . $expires);
            header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $expires) . ' GMT');
            header('Vary: Accept-Encoding');
        }
    }
    
    /**
     * Clean up WordPress head
     */
    public static function cleanup_wp_head() {
        // Remove unnecessary meta tags
        remove_action('wp_head', 'wp_generator');
        remove_action('wp_head', 'wlwmanifest_link');
        remove_action('wp_head', 'rsd_link');
        remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
        remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
        remove_action('wp_head', 'feed_links_extra', 3);
        
        // Remove REST API links
        remove_action('wp_head', 'rest_output_link_wp_head', 10);
        remove_action('wp_head', 'wp_oembed_add_discovery_links', 10);
        
        // Remove query strings from static resources
        if (!is_admin()) {
            add_filter('script_loader_src', [__CLASS__, 'remove_script_version'], 15, 1);
            add_filter('style_loader_src', [__CLASS__, 'remove_script_version'], 15, 1);
        }
    }
    
    /**
     * Remove version parameters from assets
     */
    public static function remove_version_from_assets($src) {
        if (strpos($src, 'ver=')) {
            $src = remove_query_arg('ver', $src);
        }
        return $src;
    }
    
    /**
     * Remove script version
     */
    public static function remove_script_version($src) {
        return self::remove_version_from_assets($src);
    }
    
    /**
     * Preload critical fonts
     */
    public static function preload_fonts() {
        $fonts = [
            AQUALUXE_ASSETS_URL . '/fonts/inter-var.woff2' => 'font/woff2',
            AQUALUXE_ASSETS_URL . '/fonts/playfair-display-var.woff2' => 'font/woff2'
        ];
        
        foreach ($fonts as $font_url => $type) {
            echo '<link rel="preload" href="' . esc_url($font_url) . '" as="font" type="' . esc_attr($type) . '" crossorigin>' . "\n";
        }
    }
    
    /**
     * Inline critical CSS
     */
    public static function inline_critical_css() {
        $critical_css_file = AQUALUXE_PATH . '/assets/dist/css/critical.css';
        
        if (file_exists($critical_css_file)) {
            $critical_css = file_get_contents($critical_css_file);
            if ($critical_css) {
                echo '<style id="aqualuxe-critical-css">' . $critical_css . '</style>' . "\n";
            }
        }
    }
    
    /**
     * Optimize images on upload
     */
    public static function optimize_uploaded_image($metadata, $attachment_id) {
        $file_path = get_attached_file($attachment_id);
        
        if (!$file_path || !file_exists($file_path)) {
            return $metadata;
        }
        
        $image_info = getimagesize($file_path);
        if (!$image_info) {
            return $metadata;
        }
        
        // Optimize based on image type
        switch ($image_info[2]) {
            case IMAGETYPE_JPEG:
                self::optimize_jpeg($file_path);
                break;
            case IMAGETYPE_PNG:
                self::optimize_png($file_path);
                break;
        }
        
        return $metadata;
    }
    
    /**
     * Optimize JPEG images
     */
    private static function optimize_jpeg($file_path) {
        if (function_exists('imagecreatefromjpeg')) {
            $image = imagecreatefromjpeg($file_path);
            if ($image) {
                imagejpeg($image, $file_path, 85); // 85% quality
                imagedestroy($image);
            }
        }
    }
    
    /**
     * Optimize PNG images
     */
    private static function optimize_png($file_path) {
        if (function_exists('imagecreatefrompng')) {
            $image = imagecreatefrompng($file_path);
            if ($image) {
                imagepng($image, $file_path, 6); // Compression level 6
                imagedestroy($image);
            }
        }
    }
    
    /**
     * Enable GZIP compression
     */
    public static function enable_gzip_compression() {
        if (!ob_get_level() && !headers_sent()) {
            if (extension_loaded('zlib') && !ini_get('zlib.output_compression')) {
                ob_start('ob_gzhandler');
            }
        }
    }
    
    /**
     * Get page load time
     */
    public static function get_page_load_time() {
        if (defined('AQUALUXE_START_TIME')) {
            return round((microtime(true) - AQUALUXE_START_TIME) * 1000, 2);
        }
        return 0;
    }
    
    /**
     * Display performance metrics in footer (debug mode only)
     */
    public static function display_performance_metrics() {
        if (defined('WP_DEBUG') && WP_DEBUG && current_user_can('manage_options')) {
            $load_time = self::get_page_load_time();
            $queries = get_num_queries();
            $memory = size_format(memory_get_peak_usage(true));
            
            echo "<!-- Performance Metrics: {$load_time}ms | {$queries} queries | {$memory} memory -->";
        }
    }
}

// Add performance tracking
if (!defined('AQUALUXE_START_TIME')) {
    define('AQUALUXE_START_TIME', microtime(true));
}

// Hook performance metrics display
add_action('wp_footer', ['AquaLuxe_Performance', 'display_performance_metrics']);
