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
        add_action('wp_enqueue_scripts', array($this, 'optimize_assets'));
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