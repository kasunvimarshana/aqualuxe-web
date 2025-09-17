<?php
/**
 * Performance Module
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

namespace AquaLuxe\Modules\Performance;

use AquaLuxe\Core\Abstracts\Abstract_Module;

defined('ABSPATH') || exit;

/**
 * Performance Module Class
 */
class Module extends Abstract_Module {

    /**
     * Module name
     *
     * @var string
     */
    protected $name = 'Performance';

    /**
     * Instance
     *
     * @var Module
     */
    private static $instance = null;

    /**
     * Get instance
     *
     * @return Module
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Initialize module
     */
    public function init() {
        // Asset optimization
        add_action('wp_enqueue_scripts', array($this, 'optimize_assets'));
        add_action('wp_enqueue_scripts', array($this, 'defer_non_critical_scripts'), 999);
        add_filter('script_loader_tag', array($this, 'add_async_defer_attributes'), 10, 3);
        add_filter('style_loader_tag', array($this, 'add_preload_for_styles'), 10, 4);
        
        // Image optimization
        add_filter('wp_get_attachment_image_attributes', array($this, 'add_lazy_loading'), 10, 3);
        add_filter('the_content', array($this, 'add_lazy_loading_to_content'));
        add_action('wp_head', array($this, 'add_preload_headers'));
        add_filter('wp_img_tag_add_loading_attr', array($this, 'disable_lazy_loading_for_hero'));
        
        // Caching and compression
        add_action('init', array($this, 'enable_gzip_compression'));
        add_action('wp_head', array($this, 'add_cache_headers'));
        add_filter('wp_headers', array($this, 'add_performance_headers'));
        
        // Database optimization
        add_action('wp_footer', array($this, 'optimize_database_queries'));
        add_filter('posts_request', array($this, 'log_slow_queries'), 10, 2);
        
        // Critical CSS inlining
        add_action('wp_head', array($this, 'inline_critical_css'), 1);
        add_action('wp_enqueue_scripts', array($this, 'defer_non_critical_css'));
        
        // Resource hints
        add_action('wp_head', array($this, 'add_resource_hints'), 2);
        
        // Third-party optimization
        add_action('wp_enqueue_scripts', array($this, 'optimize_third_party_scripts'));
        add_filter('oembed_result', array($this, 'optimize_embeds'), 10, 3);
        
        // Performance monitoring
        add_action('wp_footer', array($this, 'add_performance_monitoring'));
        add_action('shutdown', array($this, 'log_page_performance'));
        
        // Clean up and maintenance
        add_action('wp_scheduled_delete', array($this, 'cleanup_expired_transients'));
        add_action('wp_loaded', array($this, 'disable_unnecessary_features'));
    }

    /**
     * Optimize asset loading
     */
    public function optimize_assets() {
        // Remove unnecessary WordPress assets
        wp_dequeue_style('wp-block-library');
        wp_dequeue_style('wp-block-library-theme');
        wp_dequeue_style('wc-blocks-style');
        wp_dequeue_style('global-styles');
        
        // Remove emoji scripts and styles
        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('wp_print_styles', 'print_emoji_styles');
        remove_action('admin_print_scripts', 'print_emoji_detection_script');
        remove_action('admin_print_styles', 'print_emoji_styles');
        
        // Remove unnecessary meta tags
        remove_action('wp_head', 'wp_generator');
        remove_action('wp_head', 'wlwmanifest_link');
        remove_action('wp_head', 'rsd_link');
        remove_action('wp_head', 'wp_shortlink_wp_head');
        
        // Optimize jQuery loading
        if (!is_admin()) {
            wp_deregister_script('jquery');
            wp_register_script('jquery', 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js', false, null, true);
            wp_enqueue_script('jquery');
        }
    }

    /**
     * Add async and defer attributes to scripts
     */
    public function add_async_defer_attributes($tag, $handle, $src) {
        // Scripts to load asynchronously
        $async_scripts = array(
            'google-analytics',
            'gtag',
            'facebook-pixel',
            'aqualuxe-analytics'
        );
        
        // Scripts to defer
        $defer_scripts = array(
            'aqualuxe-app',
            'aqualuxe-modules',
            'contact-form-7',
            'wc-add-to-cart',
            'jquery-ui-core'
        );
        
        if (in_array($handle, $async_scripts)) {
            return str_replace('<script ', '<script async ', $tag);
        }
        
        if (in_array($handle, $defer_scripts)) {
            return str_replace('<script ', '<script defer ', $tag);
        }
        
        return $tag;
    }

    /**
     * Add preload for critical styles
     */
    public function add_preload_for_styles($html, $handle, $href, $media) {
        // Critical styles to preload
        $critical_styles = array('aqualuxe-critical', 'aqualuxe-app');
        
        if (in_array($handle, $critical_styles)) {
            $preload = '<link rel="preload" href="' . $href . '" as="style" onload="this.onload=null;this.rel=\'stylesheet\'">';
            $noscript = '<noscript><link rel="stylesheet" href="' . $href . '"></noscript>';
            return $preload . $noscript;
        }
        
        return $html;
    }

    /**
     * Add lazy loading to images
     */
    public function add_lazy_loading($attr, $attachment, $size) {
        // Don't add lazy loading to hero images or above-the-fold content
        if ($this->is_hero_image($attachment)) {
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
        // Add loading="lazy" to images in content
        $content = preg_replace('/<img(?![^>]*loading=)([^>]+)>/i', '<img loading="lazy" decoding="async"$1>', $content);
        
        return $content;
    }

    /**
     * Check if image is hero/above-the-fold
     */
    private function is_hero_image($attachment) {
        // Logic to determine if image is above the fold
        // This could be based on image size, post thumbnail, or custom meta
        $hero_images = get_theme_mod('aqualuxe_hero_images', array());
        return in_array($attachment->ID, $hero_images);
    }

    /**
     * Add preload headers for critical resources
     */
    public function add_preload_headers() {
        // Preload critical fonts
        $critical_fonts = array(
            get_template_directory_uri() . '/assets/dist/fonts/inter-variable.woff2',
            get_template_directory_uri() . '/assets/dist/fonts/playfair-display-variable.woff2'
        );
        
        foreach ($critical_fonts as $font) {
            echo '<link rel="preload" href="' . esc_url($font) . '" as="font" type="font/woff2" crossorigin>';
        }
        
        // Preload hero images
        if (is_front_page()) {
            $hero_image = get_theme_mod('aqualuxe_hero_image');
            if ($hero_image) {
                echo '<link rel="preload" href="' . esc_url($hero_image) . '" as="image">';
            }
        }
    }

    /**
     * Add resource hints
     */
    public function add_resource_hints() {
        // DNS prefetch for external domains
        $external_domains = array(
            'fonts.googleapis.com',
            'fonts.gstatic.com',
            'www.google-analytics.com',
            'connect.facebook.net',
            'images.unsplash.com'
        );
        
        foreach ($external_domains as $domain) {
            echo '<link rel="dns-prefetch" href="//' . esc_attr($domain) . '">';
        }
        
        // Preconnect to critical external resources
        echo '<link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>';
        echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>';
    }

    /**
     * Inline critical CSS
     */
    public function inline_critical_css() {
        $critical_css_file = get_template_directory() . '/assets/dist/css/critical.css';
        
        if (file_exists($critical_css_file)) {
            echo '<style id="aqualuxe-critical-css">';
            echo file_get_contents($critical_css_file);
            echo '</style>';
        }
    }

    /**
     * Enable GZIP compression
     */
    public function enable_gzip_compression() {
        if (!headers_sent() && !ob_get_level() && PHP_SAPI !== 'cli') {
            if (extension_loaded('zlib') && !ini_get('zlib.output_compression')) {
                if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) {
                    ob_start('ob_gzhandler');
                } else {
                    ob_start();
                }
            }
        }
    }

    /**
     * Add performance headers
     */
    public function add_performance_headers($headers) {
        // Cache control for static assets
        if (preg_match('/\.(css|js|png|jpg|jpeg|gif|webp|svg|woff|woff2|ttf|eot)$/i', $_SERVER['REQUEST_URI'])) {
            $headers['Cache-Control'] = 'public, max-age=31536000, immutable';
            $headers['Expires'] = gmdate('D, d M Y H:i:s T', time() + 31536000);
        }
        
        // Enable browser caching for HTML
        if (!is_admin() && !is_user_logged_in()) {
            $headers['Cache-Control'] = 'public, max-age=3600';
            $headers['Expires'] = gmdate('D, d M Y H:i:s T', time() + 3600);
        }
        
        // Add ETag for better caching
        $etag = md5($_SERVER['REQUEST_URI'] . filemtime(__FILE__));
        $headers['ETag'] = '"' . $etag . '"';
        
        return $headers;
    }

    /**
     * Optimize database queries
     */
    public function optimize_database_queries() {
        if (defined('SAVEQUERIES') && SAVEQUERIES) {
            global $wpdb;
            
            $slow_queries = array();
            foreach ($wpdb->queries as $query) {
                if ($query[1] > 0.1) { // Queries taking more than 100ms
                    $slow_queries[] = $query;
                }
            }
            
            if (!empty($slow_queries) && current_user_can('manage_options')) {
                error_log('AquaLuxe Performance: ' . count($slow_queries) . ' slow queries detected');
            }
        }
    }

    /**
     * Log slow queries
     */
    public function log_slow_queries($request, $query) {
        $start_time = microtime(true);
        
        return $request;
    }

    /**
     * Optimize third-party scripts
     */
    public function optimize_third_party_scripts() {
        // Defer Google Analytics
        add_action('wp_footer', function() {
            if ($ga_id = get_theme_mod('aqualuxe_google_analytics_id')) {
                ?>
                <script>
                window.dataLayer = window.dataLayer || [];
                function gtag(){dataLayer.push(arguments);}
                gtag('js', new Date());
                gtag('config', '<?php echo esc_js($ga_id); ?>', {
                    'send_page_view': false,
                    'transport_type': 'beacon'
                });
                
                // Send page view after page is fully loaded
                window.addEventListener('load', function() {
                    gtag('event', 'page_view');
                });
                </script>
                <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_attr($ga_id); ?>"></script>
                <?php
            }
        }, 999);
    }

    /**
     * Optimize embeds
     */
    public function optimize_embeds($data, $url, $attr) {
        // Add lazy loading to YouTube embeds
        if (strpos($url, 'youtube.com') !== false || strpos($url, 'youtu.be') !== false) {
            $data = str_replace('<iframe', '<iframe loading="lazy"', $data);
            $data = str_replace('src=', 'data-src=', $data);
            $data .= '<script>
                document.addEventListener("DOMContentLoaded", function() {
                    var lazyIframes = document.querySelectorAll("iframe[data-src]");
                    if ("IntersectionObserver" in window) {
                        var observer = new IntersectionObserver(function(entries) {
                            entries.forEach(function(entry) {
                                if (entry.isIntersecting) {
                                    entry.target.src = entry.target.dataset.src;
                                    observer.unobserve(entry.target);
                                }
                            });
                        });
                        lazyIframes.forEach(function(iframe) {
                            observer.observe(iframe);
                        });
                    }
                });
            </script>';
        }
        
        return $data;
    }

    /**
     * Add performance monitoring
     */
    public function add_performance_monitoring() {
        if (current_user_can('manage_options') && get_theme_mod('aqualuxe_performance_monitoring', false)) {
            ?>
            <script>
            // Performance monitoring
            window.addEventListener('load', function() {
                if ('performance' in window) {
                    var perfData = performance.getEntriesByType('navigation')[0];
                    var metrics = {
                        'load_time': perfData.loadEventEnd - perfData.navigationStart,
                        'dom_ready': perfData.domContentLoadedEventEnd - perfData.navigationStart,
                        'first_paint': performance.getEntriesByType('paint')[0]?.startTime || 0,
                        'largest_contentful_paint': 0
                    };
                    
                    // Measure LCP
                    if ('PerformanceObserver' in window) {
                        new PerformanceObserver(function(list) {
                            var entries = list.getEntries();
                            var lastEntry = entries[entries.length - 1];
                            metrics.largest_contentful_paint = lastEntry.startTime;
                        }).observe({entryTypes: ['largest-contentful-paint']});
                    }
                    
                    // Send metrics to server after 5 seconds
                    setTimeout(function() {
                        fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                            method: 'POST',
                            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                            body: 'action=aqualuxe_performance_log&metrics=' + 
                                  encodeURIComponent(JSON.stringify(metrics)) +
                                  '&nonce=<?php echo wp_create_nonce('aqualuxe_performance'); ?>'
                        });
                    }, 5000);
                }
            });
            </script>
            <?php
        }
    }

    /**
     * Log page performance
     */
    public function log_page_performance() {
        if (defined('WP_DEBUG') && WP_DEBUG && current_user_can('manage_options')) {
            $memory_usage = memory_get_peak_usage(true) / 1024 / 1024; // MB
            $execution_time = microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'];
            
            if ($memory_usage > 128 || $execution_time > 3) { // Log if memory > 128MB or time > 3s
                error_log(sprintf(
                    'AquaLuxe Performance: Page %s - Memory: %.2fMB, Time: %.3fs',
                    $_SERVER['REQUEST_URI'],
                    $memory_usage,
                    $execution_time
                ));
            }
        }
    }

    /**
     * Cleanup expired transients
     */
    public function cleanup_expired_transients() {
        global $wpdb;
        
        // Delete expired transients
        $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_timeout_%' AND option_value < UNIX_TIMESTAMP()");
        $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_%' AND option_name NOT LIKE '_transient_timeout_%' AND option_name NOT IN (SELECT REPLACE(option_name, '_timeout', '') FROM {$wpdb->options} WHERE option_name LIKE '_transient_timeout_%')");
    }

    /**
     * Disable unnecessary WordPress features
     */
    public function disable_unnecessary_features() {
        // Disable XML-RPC
        add_filter('xmlrpc_enabled', '__return_false');
        
        // Disable REST API for non-authenticated users (with exceptions)
        if (!is_user_logged_in()) {
            add_filter('rest_authentication_errors', function($result) {
                if (is_wp_error($result)) {
                    return $result;
                }
                
                // Allow specific public endpoints
                $allowed_routes = array(
                    '/wp-json/wp/v2/posts',
                    '/wp-json/wp/v2/pages',
                    '/wp-json/aqualuxe/v1/'
                );
                
                $current_route = $_SERVER['REQUEST_URI'];
                foreach ($allowed_routes as $route) {
                    if (strpos($current_route, $route) !== false) {
                        return $result;
                    }
                }
                
                return new WP_Error('rest_forbidden', __('REST API access restricted.'), array('status' => 401));
            });
        }
    }
        add_action('wp_head', array($this, 'preload_critical_assets'));
        add_action('wp_footer', array($this, 'lazy_load_scripts'));
        add_filter('script_loader_tag', array($this, 'defer_scripts'), 10, 3);
        add_filter('style_loader_tag', array($this, 'preload_styles'), 10, 4);
        add_action('init', array($this, 'enable_gzip'));
        add_filter('wp_calculate_image_srcset_meta', array($this, 'disable_srcset_on_demand'));
        
        // Image optimization
        add_filter('jpeg_quality', array($this, 'jpeg_quality'));
        add_filter('wp_editor_set_quality', array($this, 'editor_quality'));
        
        // Database optimization
        add_action('wp_loaded', array($this, 'cleanup_wp_head'));
        
        // Caching headers
        add_action('send_headers', array($this, 'set_caching_headers'));
    }

    /**
     * Optimize asset loading
     */
    public function optimize_assets() {
        // Remove unnecessary assets
        if (!is_admin()) {
            wp_deregister_script('wp-embed');
            wp_dequeue_style('wp-block-library');
            
            // Remove emoji scripts
            remove_action('wp_head', 'print_emoji_detection_script', 7);
            remove_action('wp_print_styles', 'print_emoji_styles');
        }
    }

    /**
     * Preload critical assets
     */
    public function preload_critical_assets() {
        // Preload critical CSS
        $manifest = $this->get_manifest();
        
        if (isset($manifest['/css/app.css'])) {
            echo '<link rel="preload" href="' . esc_url(get_theme_file_uri('assets/dist' . $manifest['/css/app.css'])) . '" as="style" onload="this.onload=null;this.rel=\'stylesheet\'">';
        }
        
        // Preload fonts
        $fonts = array(
            'inter-regular.woff2',
            'inter-medium.woff2',
            'inter-semibold.woff2',
        );
        
        foreach ($fonts as $font) {
            echo '<link rel="preload" href="' . esc_url(get_theme_file_uri('assets/dist/fonts/' . $font)) . '" as="font" type="font/woff2" crossorigin>';
        }
        
        // DNS prefetch for external domains
        $external_domains = array(
            'fonts.googleapis.com',
            'fonts.gstatic.com',
            'www.googletagmanager.com',
        );
        
        foreach ($external_domains as $domain) {
            echo '<link rel="dns-prefetch" href="//' . esc_attr($domain) . '">';
        }
    }

    /**
     * Lazy load non-critical scripts
     */
    public function lazy_load_scripts() {
        ?>
        <script>
        // Intersection Observer for lazy loading
        if ('IntersectionObserver' in window) {
            const lazyElements = document.querySelectorAll('[data-lazy]');
            const lazyObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const element = entry.target;
                        
                        if (element.dataset.src) {
                            element.src = element.dataset.src;
                            element.removeAttribute('data-src');
                        }
                        
                        if (element.dataset.background) {
                            element.style.backgroundImage = `url(${element.dataset.background})`;
                            element.removeAttribute('data-background');
                        }
                        
                        element.classList.add('loaded');
                        lazyObserver.unobserve(element);
                    }
                });
            });
            
            lazyElements.forEach(element => lazyObserver.observe(element));
        }
        
        // Service Worker registration
        if ('serviceWorker' in navigator && !navigator.serviceWorker.controller) {
            navigator.serviceWorker.register('<?php echo esc_url(get_theme_file_uri('sw.js')); ?>')
                .then(registration => console.log('SW registered'))
                .catch(error => console.log('SW registration failed'));
        }
        </script>
        <?php
    }

    /**
     * Defer non-critical scripts
     *
     * @param string $tag    Script tag
     * @param string $handle Script handle
     * @param string $src    Script source
     * @return string
     */
    public function defer_scripts($tag, $handle, $src) {
        // Don't defer admin scripts or scripts with dependencies
        if (is_admin() || strpos($tag, 'defer') !== false) {
            return $tag;
        }
        
        $defer_scripts = array(
            'aqualuxe-script',
            'contact-form-7',
            'wc-add-to-cart',
        );
        
        if (in_array($handle, $defer_scripts)) {
            return str_replace('<script ', '<script defer ', $tag);
        }
        
        return $tag;
    }

    /**
     * Preload styles
     *
     * @param string $html   Style tag
     * @param string $handle Style handle
     * @param string $href   Style href
     * @param string $media  Style media
     * @return string
     */
    public function preload_styles($html, $handle, $href, $media) {
        $preload_styles = array(
            'aqualuxe-style',
        );
        
        if (in_array($handle, $preload_styles)) {
            $html = '<link rel="preload" href="' . esc_url($href) . '" as="style" onload="this.onload=null;this.rel=\'stylesheet\'">' . $html;
        }
        
        return $html;
    }

    /**
     * Enable Gzip compression
     */
    public function enable_gzip() {
        if (!headers_sent() && !ob_get_contents() && function_exists('ob_gzhandler')) {
            ob_start('ob_gzhandler');
        }
    }

    /**
     * Disable srcset on demand
     *
     * @param array $image_meta Image metadata
     * @return array|bool
     */
    public function disable_srcset_on_demand($image_meta) {
        // Disable srcset for very large images to save bandwidth
        if (isset($image_meta['width']) && $image_meta['width'] > 2000) {
            return false;
        }
        
        return $image_meta;
    }

    /**
     * Set JPEG quality
     *
     * @param int $quality JPEG quality
     * @return int
     */
    public function jpeg_quality($quality) {
        return 85; // Balanced quality vs file size
    }

    /**
     * Set editor quality
     *
     * @param int $quality Editor quality
     * @return int
     */
    public function editor_quality($quality) {
        return 85;
    }

    /**
     * Clean up wp_head
     */
    public function cleanup_wp_head() {
        // Remove unnecessary head elements
        remove_action('wp_head', 'wp_generator');
        remove_action('wp_head', 'wlwmanifest_link');
        remove_action('wp_head', 'rsd_link');
        remove_action('wp_head', 'wp_shortlink_wp_head');
        remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10);
        
        // Remove REST API links
        remove_action('wp_head', 'rest_output_link_wp_head');
        remove_action('wp_head', 'wp_oembed_add_discovery_links');
        
        // Remove feed links
        remove_action('wp_head', 'feed_links_extra', 3);
    }

    /**
     * Set caching headers
     */
    public function set_caching_headers() {
        if (!is_admin() && !is_user_logged_in()) {
            $expires = 30 * DAY_IN_SECONDS; // 30 days
            
            header('Cache-Control: public, max-age=' . $expires);
            header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $expires) . ' GMT');
            header('Vary: Accept-Encoding');
        }
    }

    /**
     * Get webpack manifest
     *
     * @return array
     */
    private function get_manifest() {
        static $manifest = null;
        
        if (null === $manifest) {
            $manifest_path = get_template_directory() . '/assets/dist/mix-manifest.json';
            
            if (file_exists($manifest_path)) {
                $manifest = json_decode(file_get_contents($manifest_path), true);
            } else {
                $manifest = array();
            }
        }
        
        return $manifest;
    }
}