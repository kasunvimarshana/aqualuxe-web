<?php
/**
 * Performance Optimization Functions
 * 
 * Performance optimization techniques including lazy loading, caching, and minification
 * 
 * @package KV_Enterprise
 * @version 1.0.0
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit('Direct access forbidden.');
}

/**
 * Performance Manager Class
 * Centralized performance optimization management
 */
class KV_Performance_Manager {
    
    /**
     * Initialize performance optimizations
     * 
     * @return void
     */
    public static function init() {
        // Enable WordPress native lazy loading
        add_filter('wp_lazy_loading_enabled', '__return_true');
        
        // Optimize database queries
        add_action('init', [__CLASS__, 'optimize_database_queries']);
        
        // Remove unnecessary scripts and styles
        add_action('wp_enqueue_scripts', [__CLASS__, 'remove_unnecessary_scripts'], 100);
        add_action('wp_enqueue_scripts', [__CLASS__, 'optimize_script_loading'], 100);
        
        // Optimize images
        add_filter('wp_get_attachment_image_attributes', [__CLASS__, 'add_lazy_loading_to_images'], 10, 3);
        
        // Enable Gzip compression
        add_action('init', [__CLASS__, 'enable_gzip_compression']);
        
        // Cache control headers
        add_action('send_headers', [__CLASS__, 'add_cache_headers']);
        
        // Optimize feeds
        add_action('init', [__CLASS__, 'optimize_feeds']);
        
        // Reduce heartbeat frequency
        add_filter('heartbeat_settings', [__CLASS__, 'optimize_heartbeat']);
        
        // Optimize admin
        if (is_admin()) {
            add_action('admin_init', [__CLASS__, 'optimize_admin']);
        }
        
        // Resource hints
        add_action('wp_head', [__CLASS__, 'add_resource_hints'], 1);
        
        // Critical CSS
        add_action('wp_head', [__CLASS__, 'add_critical_css'], 5);
        
        // Defer non-critical CSS
        add_filter('style_loader_tag', [__CLASS__, 'defer_non_critical_css'], 10, 4);
        
        // Defer JavaScript
        add_filter('script_loader_tag', [__CLASS__, 'defer_javascript'], 10, 3);
        
        // Object caching
        add_action('init', [__CLASS__, 'init_object_caching']);
        
        // CDN support
        add_filter('wp_get_attachment_url', [__CLASS__, 'use_cdn_for_assets']);
        
        // Database optimization
        add_action('wp_loaded', [__CLASS__, 'optimize_database_tables']);
    }
    
    /**
     * Optimize database queries
     * 
     * @return void
     */
    public static function optimize_database_queries() {
        // Remove unnecessary meta queries
        remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
        
        // Limit post revisions
        if (!defined('WP_POST_REVISIONS')) {
            define('WP_POST_REVISIONS', 3);
        }
        
        // Optimize query vars
        add_filter('query_vars', [__CLASS__, 'optimize_query_vars']);
        
        // Use object cache for expensive queries
        add_filter('posts_pre_query', [__CLASS__, 'cache_expensive_queries'], 10, 2);
    }
    
    /**
     * Remove unnecessary scripts and styles
     * 
     * @return void
     */
    public static function remove_unnecessary_scripts() {
        if (!is_admin()) {
            // Remove jQuery Migrate if not needed
            wp_deregister_script('jquery-migrate');
            
            // Remove embed script if not needed
            if (!is_single()) {
                wp_deregister_script('wp-embed');
            }
            
            // Remove emoji scripts
            remove_action('wp_head', 'print_emoji_detection_script', 7);
            remove_action('wp_print_styles', 'print_emoji_styles');
            remove_action('admin_print_scripts', 'print_emoji_detection_script');
            remove_action('admin_print_styles', 'print_emoji_styles');
            remove_filter('the_content_feed', 'wp_staticize_emoji');
            remove_filter('comment_text_rss', 'wp_staticize_emoji');
            remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
            
            // Remove block library CSS if not using blocks
            if (!kv_get_theme_option('use_gutenberg_blocks', true)) {
                wp_dequeue_style('wp-block-library');
                wp_dequeue_style('wp-block-library-theme');
                wp_dequeue_style('wc-blocks-style');
            }
        }
    }
    
    /**
     * Optimize script loading
     * 
     * @return void
     */
    public static function optimize_script_loading() {
        // Combine and minify scripts in production
        if (!WP_DEBUG) {
            add_filter('script_loader_src', [__CLASS__, 'add_version_to_scripts']);
        }
        
        // Move scripts to footer
        if (!is_admin()) {
            wp_scripts()->add_data('jquery', 'group', 1);
            wp_scripts()->add_data('jquery-core', 'group', 1);
        }
    }
    
    /**
     * Add lazy loading to images
     * 
     * @param array $attr       Image attributes
     * @param WP_Post $attachment Attachment object
     * @param string $size      Image size
     * @return array Modified attributes
     */
    public static function add_lazy_loading_to_images($attr, $attachment, $size) {
        // Skip if already has loading attribute
        if (isset($attr['loading'])) {
            return $attr;
        }
        
        // Skip critical images (first few images)
        static $image_count = 0;
        $image_count++;
        
        if ($image_count <= 3) {
            $attr['loading'] = 'eager';
        } else {
            $attr['loading'] = 'lazy';
        }
        
        return $attr;
    }
    
    /**
     * Enable Gzip compression
     * 
     * @return void
     */
    public static function enable_gzip_compression() {
        if (!ob_get_level() && !headers_sent()) {
            if (function_exists('ob_gzhandler') && !ini_get('zlib.output_compression')) {
                ob_start('ob_gzhandler');
            }
        }
    }
    
    /**
     * Add cache headers
     * 
     * @return void
     */
    public static function add_cache_headers() {
        if (!is_admin() && !is_user_logged_in()) {
            $expires = 3600; // 1 hour
            
            if (is_front_page() || is_home()) {
                $expires = 1800; // 30 minutes for dynamic pages
            } elseif (is_single() || is_page()) {
                $expires = 7200; // 2 hours for posts/pages
            }
            
            header("Cache-Control: public, max-age={$expires}");
            header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $expires) . ' GMT');
            header('Vary: Accept-Encoding, Cookie');
        }
    }
    
    /**
     * Optimize feeds
     * 
     * @return void
     */
    public static function optimize_feeds() {
        // Remove unnecessary feed links
        remove_action('wp_head', 'feed_links_extra', 3);
        
        // Optimize feed content
        add_filter('the_excerpt_rss', 'wp_trim_words', 10, 1);
        add_filter('the_content_feed', [__CLASS__, 'optimize_feed_content']);
    }
    
    /**
     * Optimize feed content
     * 
     * @param string $content Feed content
     * @return string Optimized content
     */
    public static function optimize_feed_content($content) {
        // Remove unnecessary HTML from feeds
        $content = strip_tags($content, '<p><br><strong><em><a><ul><ol><li>');
        
        // Limit content length
        $content = wp_trim_words($content, 50);
        
        return $content;
    }
    
    /**
     * Optimize heartbeat
     * 
     * @param array $settings Heartbeat settings
     * @return array Modified settings
     */
    public static function optimize_heartbeat($settings) {
        // Slow down heartbeat to every 60 seconds
        $settings['interval'] = 60;
        
        return $settings;
    }
    
    /**
     * Optimize admin area
     * 
     * @return void
     */
    public static function optimize_admin() {
        // Disable heartbeat in admin (except post editor)
        global $pagenow;
        if ($pagenow !== 'post.php' && $pagenow !== 'post-new.php') {
            wp_deregister_script('heartbeat');
        }
        
        // Remove admin bar for non-admins
        if (!current_user_can('administrator')) {
            show_admin_bar(false);
        }
        
        // Limit admin notices
        add_action('admin_print_scripts', [__CLASS__, 'limit_admin_notices']);
    }
    
    /**
     * Limit admin notices
     * 
     * @return void
     */
    public static function limit_admin_notices() {
        // Hide update notices for non-admins
        if (!current_user_can('update_core')) {
            remove_action('admin_notices', 'update_nag', 3);
            remove_action('admin_notices', 'maintenance_nag', 10);
        }
    }
    
    /**
     * Add resource hints
     * 
     * @return void
     */
    public static function add_resource_hints() {
        // DNS prefetch for external domains
        $external_domains = [
            'fonts.googleapis.com',
            'fonts.gstatic.com',
            'www.google-analytics.com',
            'www.googletagmanager.com',
        ];
        
        foreach ($external_domains as $domain) {
            echo "<link rel='dns-prefetch' href='//{$domain}'>\n";
        }
        
        // Preload critical assets
        $critical_assets = apply_filters('kv_critical_assets', []);
        foreach ($critical_assets as $asset) {
            $type = pathinfo($asset, PATHINFO_EXTENSION) === 'css' ? 'style' : 'script';
            echo "<link rel='preload' href='{$asset}' as='{$type}'>\n";
        }
    }
    
    /**
     * Add critical CSS
     * 
     * @return void
     */
    public static function add_critical_css() {
        $critical_css = kv_get_theme_option('critical_css', '');
        
        if (!empty($critical_css)) {
            echo "<style id='critical-css'>{$critical_css}</style>\n";
        }
    }
    
    /**
     * Defer non-critical CSS
     * 
     * @param string $html   Style tag HTML
     * @param string $handle Style handle
     * @param string $href   Style URL
     * @param string $media  Media type
     * @return string Modified HTML
     */
    public static function defer_non_critical_css($html, $handle, $href, $media) {
        // Critical styles that should load immediately
        $critical_styles = ['kv-enterprise-style', 'critical-css'];
        
        if (!in_array($handle, $critical_styles)) {
            $html = str_replace('rel="stylesheet"', 'rel="preload" as="style" onload="this.onload=null;this.rel=\'stylesheet\'"', $html);
            $html .= '<noscript><link rel="stylesheet" href="' . $href . '"></noscript>';
        }
        
        return $html;
    }
    
    /**
     * Defer JavaScript
     * 
     * @param string $tag    Script tag HTML
     * @param string $handle Script handle
     * @param string $src    Script URL
     * @return string Modified HTML
     */
    public static function defer_javascript($tag, $handle, $src) {
        // Skip admin and login pages
        if (is_admin() || $GLOBALS['pagenow'] === 'wp-login.php') {
            return $tag;
        }
        
        // Critical scripts that should not be deferred
        $critical_scripts = ['jquery', 'jquery-core'];
        
        if (!in_array($handle, $critical_scripts)) {
            return str_replace('<script ', '<script defer ', $tag);
        }
        
        return $tag;
    }
    
    /**
     * Initialize object caching
     * 
     * @return void
     */
    public static function init_object_caching() {
        // Use transients for caching when object cache is not available
        if (!wp_using_ext_object_cache()) {
            add_filter('pre_get_posts', [__CLASS__, 'cache_query_results']);
        }
    }
    
    /**
     * Cache expensive query results
     * 
     * @param WP_Query $query Query object
     * @return void
     */
    public static function cache_query_results($query) {
        if (!$query->is_main_query() || is_admin()) {
            return;
        }
        
        $cache_key = 'main_query_' . md5(serialize($query->query_vars));
        $cached_posts = get_transient($cache_key);
        
        if (false === $cached_posts) {
            // Cache will be set after query runs
            add_action('wp', function() use ($cache_key, $query) {
                if (!empty($query->posts)) {
                    set_transient($cache_key, $query->posts, 3600);
                }
            });
        }
    }
    
    /**
     * Cache expensive queries
     * 
     * @param array|null $posts Posts array
     * @param WP_Query   $query Query object
     * @return array|null Posts array or null
     */
    public static function cache_expensive_queries($posts, $query) {
        // Only cache main queries
        if (!$query->is_main_query() || is_admin()) {
            return $posts;
        }
        
        $cache_key = 'query_' . md5(serialize($query->query_vars));
        $cached_result = wp_cache_get($cache_key, 'kv_enterprise');
        
        if (false !== $cached_result) {
            return $cached_result;
        }
        
        return $posts;
    }
    
    /**
     * Use CDN for assets
     * 
     * @param string $url Asset URL
     * @return string Modified URL
     */
    public static function use_cdn_for_assets($url) {
        $cdn_url = kv_get_theme_option('cdn_url', '');
        
        if (!empty($cdn_url) && strpos($url, home_url()) === 0) {
            $url = str_replace(home_url(), rtrim($cdn_url, '/'), $url);
        }
        
        return $url;
    }
    
    /**
     * Add version to scripts for cache busting
     * 
     * @param string $src Script URL
     * @return string Modified URL
     */
    public static function add_version_to_scripts($src) {
        if (strpos($src, home_url()) === 0) {
            $src = add_query_arg('v', KV_THEME_VERSION, $src);
        }
        
        return $src;
    }
    
    /**
     * Optimize query vars
     * 
     * @param array $vars Query vars
     * @return array Modified vars
     */
    public static function optimize_query_vars($vars) {
        // Remove unnecessary query vars
        $unnecessary_vars = ['customize_theme', 'customized'];
        
        return array_diff($vars, $unnecessary_vars);
    }
    
    /**
     * Optimize database tables
     * 
     * @return void
     */
    public static function optimize_database_tables() {
        // Only run optimization once per week
        $last_optimization = get_option('kv_last_db_optimization', 0);
        if (time() - $last_optimization < WEEK_IN_SECONDS) {
            return;
        }
        
        global $wpdb;
        
        // Clean up unnecessary data
        $wpdb->query("DELETE FROM {$wpdb->posts} WHERE post_type = 'revision' AND post_date < DATE_SUB(NOW(), INTERVAL 30 DAY)");
        $wpdb->query("DELETE FROM {$wpdb->comments} WHERE comment_approved = 'spam' AND comment_date < DATE_SUB(NOW(), INTERVAL 30 DAY)");
        $wpdb->query("DELETE FROM {$wpdb->commentmeta} WHERE comment_id NOT IN (SELECT comment_ID FROM {$wpdb->comments})");
        $wpdb->query("DELETE FROM {$wpdb->postmeta} WHERE post_id NOT IN (SELECT ID FROM {$wpdb->posts})");
        
        // Optimize tables
        $tables = $wpdb->get_results('SHOW TABLES', ARRAY_N);
        foreach ($tables as $table) {
            $wpdb->query("OPTIMIZE TABLE {$table[0]}");
        }
        
        update_option('kv_last_db_optimization', time());
    }
    
    /**
     * Minify HTML output
     * 
     * @param string $html HTML content
     * @return string Minified HTML
     */
    public static function minify_html($html) {
        if (is_admin() || WP_DEBUG) {
            return $html;
        }
        
        // Remove comments
        $html = preg_replace('/<!--(?!<!)[^\[>].*?-->/s', '', $html);
        
        // Remove extra whitespace
        $html = preg_replace('/\s+/', ' ', $html);
        
        // Remove whitespace around block elements
        $html = preg_replace('/>\s+</', '><', $html);
        
        return trim($html);
    }
    
    /**
     * Enable HTML minification
     * 
     * @return void
     */
    public static function enable_html_minification() {
        if (!is_admin() && !WP_DEBUG) {
            ob_start([__CLASS__, 'minify_html']);
        }
    }
}

// Initialize performance optimizations
add_action('init', ['KV_Performance_Manager', 'init']);
add_action('template_redirect', ['KV_Performance_Manager', 'enable_html_minification']);
