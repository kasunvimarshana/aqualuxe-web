<?php
/**
 * AquaLuxe Performance Class
 *
 * @package AquaLuxe
 * @version 1.0.0
 * @since   1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Performance Optimization Class
 *
 * @class AquaLuxe_Performance
 */
class AquaLuxe_Performance {

    /**
     * Single instance of the class
     *
     * @var AquaLuxe_Performance
     */
    protected static $_instance = null;

    /**
     * Main AquaLuxe_Performance Instance
     *
     * @static
     * @return AquaLuxe_Performance - Main instance
     */
    public static function get_instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->init_hooks();
    }

    /**
     * Hook into actions and filters
     */
    private function init_hooks() {
        // Image optimization
        add_filter('wp_get_attachment_image_attributes', array($this, 'add_lazy_loading'), 10, 3);
        add_filter('the_content', array($this, 'add_lazy_loading_to_content'));
        
        // Resource optimization
        add_action('wp_head', array($this, 'add_resource_hints'), 1);
        add_action('wp_head', array($this, 'add_critical_css'), 2);
        add_action('wp_head', array($this, 'add_preload_fonts'), 3);
        
        // Script optimization
        add_filter('script_loader_tag', array($this, 'optimize_script_loading'), 10, 3);
        add_filter('style_loader_tag', array($this, 'optimize_style_loading'), 10, 4);
        
        // Remove unnecessary features
        add_action('init', array($this, 'remove_unnecessary_features'));
        
        // Database optimization
        add_action('wp_scheduled_delete', array($this, 'cleanup_database'));
        
        // Caching
        add_action('init', array($this, 'setup_caching'));
        
        // GZIP compression
        add_action('init', array($this, 'enable_gzip_compression'));
        
        // Remove query strings
        add_filter('script_loader_src', array($this, 'remove_query_strings'));
        add_filter('style_loader_src', array($this, 'remove_query_strings'));
    }

    /**
     * Add lazy loading to images
     */
    public function add_lazy_loading($attr, $attachment, $size) {
        if (!is_admin() && !is_feed()) {
            $attr['loading'] = 'lazy';
            $attr['decoding'] = 'async';
        }
        return $attr;
    }

    /**
     * Add lazy loading to content images
     */
    public function add_lazy_loading_to_content($content) {
        if (!is_admin() && !is_feed()) {
            // Add loading="lazy" to images that don't have it
            $content = preg_replace('/<img(?![^>]*loading=)([^>]+)>/i', '<img loading="lazy" decoding="async"$1>', $content);
        }
        return $content;
    }

    /**
     * Add resource hints
     */
    public function add_resource_hints() {
        // DNS prefetch for external domains
        echo '<link rel="dns-prefetch" href="//fonts.googleapis.com">' . "\n";
        echo '<link rel="dns-prefetch" href="//fonts.gstatic.com">' . "\n";
        
        // Preconnect to external domains
        echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
        echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
        
        // Prefetch next page for better UX
        if (is_single() || is_page()) {
            $next_post = get_next_post();
            if ($next_post) {
                echo '<link rel="prefetch" href="' . esc_url(get_permalink($next_post)) . '">' . "\n";
            }
        }
    }

    /**
     * Add critical CSS inline
     */
    public function add_critical_css() {
        $critical_css_file = AQUALUXE_THEME_DIR . '/assets/dist/css/critical.css';
        
        if (file_exists($critical_css_file)) {
            $critical_css = file_get_contents($critical_css_file);
            if ($critical_css) {
                echo '<style id="aqualuxe-critical-css">' . "\n";
                echo $critical_css;
                echo "\n" . '</style>' . "\n";
            }
        } else {
            // Basic critical CSS if file doesn't exist
            $basic_critical_css = '
                body { font-family: Inter, system-ui, sans-serif; }
                .site-header { background: #fff; }
                .btn-primary { background: #06b6d4; color: #fff; }
                .container { max-width: 1200px; margin: 0 auto; padding: 0 1rem; }
            ';
            echo '<style id="aqualuxe-basic-critical-css">' . $basic_critical_css . '</style>' . "\n";
        }
    }

    /**
     * Preload critical fonts
     */
    public function add_preload_fonts() {
        $fonts = array(
            AQUALUXE_ASSETS_URI . 'fonts/inter-v12-latin-regular.woff2',
            AQUALUXE_ASSETS_URI . 'fonts/playfair-display-v22-latin-regular.woff2'
        );
        
        foreach ($fonts as $font) {
            echo '<link rel="preload" href="' . esc_url($font) . '" as="font" type="font/woff2" crossorigin>' . "\n";
        }
    }

    /**
     * Optimize script loading
     */
    public function optimize_script_loading($tag, $handle, $src) {
        // Scripts to defer
        $defer_scripts = array(
            'aqualuxe-woocommerce',
            'aqualuxe-contact',
            'comment-reply'
        );
        
        // Scripts to load async
        $async_scripts = array(
            'aqualuxe-dark-mode',
            'aqualuxe-hero'
        );
        
        if (in_array($handle, $defer_scripts)) {
            return str_replace(' src', ' defer src', $tag);
        }
        
        if (in_array($handle, $async_scripts)) {
            return str_replace(' src', ' async src', $tag);
        }
        
        return $tag;
    }

    /**
     * Optimize style loading
     */
    public function optimize_style_loading($html, $handle, $href, $media) {
        // Non-critical CSS to load asynchronously
        $non_critical_styles = array(
            'aqualuxe-woocommerce',
            'aqualuxe-contact-form'
        );
        
        if (in_array($handle, $non_critical_styles)) {
            $html = str_replace("media='$media'", "media='print' onload=\"this.media='$media'\"", $html);
            $html .= '<noscript>' . str_replace(' onload="this.media=\'all\'"', '', $html) . '</noscript>';
        }
        
        return $html;
    }

    /**
     * Remove unnecessary WordPress features
     */
    public function remove_unnecessary_features() {
        // Remove emoji scripts and styles
        if (!get_theme_mod('aqualuxe_enable_emojis', false)) {
            remove_action('wp_head', 'print_emoji_detection_script', 7);
            remove_action('wp_print_styles', 'print_emoji_styles');
            remove_action('admin_print_scripts', 'print_emoji_detection_script');
            remove_action('admin_print_styles', 'print_emoji_styles');
            remove_filter('the_content_feed', 'wp_staticize_emoji');
            remove_filter('comment_text_rss', 'wp_staticize_emoji');
            remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
        }
        
        // Remove REST API links if not needed
        if (!get_theme_mod('aqualuxe_enable_rest_api', true)) {
            remove_action('wp_head', 'rest_output_link_wp_head');
            remove_action('wp_head', 'wp_oembed_add_discovery_links');
        }
        
        // Remove feed links if not needed
        if (!get_theme_mod('aqualuxe_enable_feeds', true)) {
            remove_action('wp_head', 'feed_links', 2);
            remove_action('wp_head', 'feed_links_extra', 3);
        }
        
        // Disable embeds if not needed
        if (!get_theme_mod('aqualuxe_enable_embeds', true)) {
            wp_deregister_script('wp-embed');
        }
        
        // Remove jQuery Migrate
        if (!is_admin()) {
            wp_deregister_script('jquery');
            wp_register_script('jquery', includes_url('/js/jquery/jquery.min.js'), false, null, true);
            wp_enqueue_script('jquery');
        }
    }

    /**
     * Database cleanup
     */
    public function cleanup_database() {
        global $wpdb;
        
        // Clean up expired transients
        $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_timeout_%' AND option_value < UNIX_TIMESTAMP()");
        $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_%' AND option_name NOT IN (SELECT CONCAT('_transient_', SUBSTRING(option_name, 20)) FROM {$wpdb->options} WHERE option_name LIKE '_transient_timeout_%')");
        
        // Clean up post revisions (keep last 3)
        $wpdb->query("DELETE p1 FROM {$wpdb->posts} p1 WHERE p1.post_type = 'revision' AND p1.ID NOT IN (SELECT p2.ID FROM (SELECT ID FROM {$wpdb->posts} WHERE post_type = 'revision' ORDER BY post_date DESC LIMIT 3) p2)");
        
        // Clean up spam and trash comments
        $wpdb->query("DELETE FROM {$wpdb->comments} WHERE comment_approved = 'spam' OR comment_approved = 'trash'");
        
        // Optimize database tables
        $tables = $wpdb->get_results('SHOW TABLES', ARRAY_N);
        foreach ($tables as $table) {
            $wpdb->query("OPTIMIZE TABLE {$table[0]}");
        }
    }

    /**
     * Setup caching
     */
    public function setup_caching() {
        // Add cache headers for static assets
        if (!is_admin()) {
            $cache_time = 31536000; // 1 year
            
            // Set cache headers for static files
            add_action('wp_loaded', function() use ($cache_time) {
                $request_uri = $_SERVER['REQUEST_URI'] ?? '';
                
                if (preg_match('/\.(css|js|jpg|jpeg|png|gif|webp|svg|ico|woff|woff2|ttf)$/i', $request_uri)) {
                    header('Cache-Control: public, max-age=' . $cache_time);
                    header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', time() + $cache_time));
                }
            });
        }
    }

    /**
     * Enable GZIP compression
     */
    public function enable_gzip_compression() {
        if (!is_admin() && !headers_sent()) {
            if (function_exists('gzencode') && !ob_get_level()) {
                ob_start('ob_gzhandler');
            }
        }
    }

    /**
     * Remove query strings from static resources
     */
    public function remove_query_strings($src) {
        if (!is_admin()) {
            $parts = explode('?ver', $src);
            return $parts[0];
        }
        return $src;
    }

    /**
     * Minify HTML output
     */
    public function minify_html($buffer) {
        if (!is_admin() && !is_feed()) {
            $search = array(
                '/\>[^\S ]+/s',     // Strip whitespace after tags, except space
                '/[^\S ]+\</s',     // Strip whitespace before tags, except space
                '/(\s)+/s',         // Shorten multiple whitespace sequences
                '/<!--(.|\s)*?-->/' // Remove HTML comments
            );
            
            $replace = array(
                '>',
                '<',
                '\\1',
                ''
            );
            
            $buffer = preg_replace($search, $replace, $buffer);
        }
        
        return $buffer;
    }

    /**
     * Enable HTML minification
     */
    public function enable_html_minification() {
        if (get_theme_mod('aqualuxe_minify_html', false)) {
            ob_start(array($this, 'minify_html'));
        }
    }

    /**
     * Implement WebP support
     */
    public function add_webp_support() {
        add_filter('wp_get_attachment_image_src', array($this, 'webp_support'), 10, 4);
    }

    /**
     * Convert images to WebP if supported
     */
    public function webp_support($image, $attachment_id, $size, $icon) {
        if (!empty($image[0])) {
            $pathinfo = pathinfo($image[0]);
            $webp_path = $pathinfo['dirname'] . '/' . $pathinfo['filename'] . '.webp';
            
            // Check if WebP version exists
            $upload_dir = wp_upload_dir();
            $webp_file_path = str_replace($upload_dir['baseurl'], $upload_dir['basedir'], $webp_path);
            
            if (file_exists($webp_file_path)) {
                // Check if browser supports WebP
                $accept = $_SERVER['HTTP_ACCEPT'] ?? '';
                if (strpos($accept, 'image/webp') !== false) {
                    $image[0] = $webp_path;
                }
            }
        }
        
        return $image;
    }

    /**
     * Add Service Worker for caching
     */
    public function add_service_worker() {
        if (get_theme_mod('aqualuxe_enable_service_worker', false)) {
            echo '<script>
                if ("serviceWorker" in navigator && window.location.protocol === "https:") {
                    navigator.serviceWorker.register("/sw.js")
                        .then(function(registration) {
                            console.log("Service Worker registered:", registration);
                        })
                        .catch(function(error) {
                            console.log("Service Worker registration failed:", error);
                        });
                }
            </script>' . "\n";
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
     * Display performance stats (for development)
     */
    public function show_performance_stats() {
        if (WP_DEBUG && current_user_can('manage_options')) {
            $load_time = self::get_page_load_time();
            $queries = get_num_queries();
            $memory = size_format(memory_get_peak_usage());
            
            echo '<!-- AquaLuxe Performance Stats: ' . $load_time . 'ms, ' . $queries . ' queries, ' . $memory . ' peak memory -->' . "\n";
        }
    }
}