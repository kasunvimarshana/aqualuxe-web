<?php
/**
 * Performance Optimization Class
 *
 * Handles performance optimizations and caching
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * AquaLuxe Performance Class
 */
class AquaLuxe_Performance {

    /**
     * Constructor
     */
    public function __construct() {
        // Class is auto-initialized when loaded
    }

    /**
     * Initialize performance optimizations
     */
    public function init() {
        add_action( 'wp_enqueue_scripts', array( $this, 'optimize_scripts' ) );
        add_action( 'wp_head', array( $this, 'add_dns_prefetch' ), 1 );
        add_action( 'wp_head', array( $this, 'add_preload_links' ), 2 );
        add_filter( 'script_loader_tag', array( $this, 'add_async_defer' ), 10, 3 );
        add_filter( 'style_loader_tag', array( $this, 'add_preload_css' ), 10, 4 );
        add_action( 'wp_footer', array( $this, 'lazy_load_images' ) );
        
        // Database optimizations
        add_action( 'init', array( $this, 'optimize_database_queries' ) );
        
        // Caching
        add_action( 'init', array( $this, 'setup_caching' ) );
        
        // Image optimizations
        add_filter( 'wp_get_attachment_image_attributes', array( $this, 'add_lazy_loading' ), 10, 3 );
        
        // Remove unnecessary features
        $this->cleanup_wordpress();
    }

    /**
     * Optimize script loading
     */
    public function optimize_scripts() {
        // Remove jQuery migrate in production
        if ( ! is_admin() && ! wp_script_is( 'customize-preview' ) ) {
            wp_deregister_script( 'jquery-migrate' );
        }
        
        // Dequeue unused scripts
        wp_dequeue_script( 'wp-embed' );
        
        // Remove block library CSS for non-block themes
        if ( ! current_theme_supports( 'wp-block-styles' ) ) {
            wp_dequeue_style( 'wp-block-library' );
            wp_dequeue_style( 'wp-block-library-theme' );
            wp_dequeue_style( 'wc-blocks-style' );
        }
    }

    /**
     * Add DNS prefetch links
     */
    public function add_dns_prefetch() {
        $domains = array(
            '//fonts.googleapis.com',
            '//fonts.gstatic.com',
            '//www.google-analytics.com',
            '//www.googletagmanager.com',
        );
        
        foreach ( $domains as $domain ) {
            echo '<link rel="dns-prefetch" href="' . esc_url( $domain ) . '">' . "\n";
        }
    }

    /**
     * Add preload links for critical resources
     */
    public function add_preload_links() {
        // Preload critical CSS
        $critical_css = AQUALUXE_ASSETS_URL . 'dist/css/main.css';
        echo '<link rel="preload" href="' . esc_url( $critical_css ) . '" as="style" onload="this.onload=null;this.rel=\'stylesheet\'">' . "\n";
        
        // Preload critical JavaScript
        $critical_js = AQUALUXE_ASSETS_URL . 'dist/js/main.js';
        echo '<link rel="preload" href="' . esc_url( $critical_js ) . '" as="script">' . "\n";
        
        // Preload fonts
        $fonts = array(
            'inter-400.woff2',
            'inter-600.woff2',
            'playfair-700.woff2',
        );
        
        foreach ( $fonts as $font ) {
            $font_url = AQUALUXE_ASSETS_URL . 'dist/fonts/' . $font;
            echo '<link rel="preload" href="' . esc_url( $font_url ) . '" as="font" type="font/woff2" crossorigin>' . "\n";
        }
    }

    /**
     * Add async/defer attributes to scripts
     *
     * @param string $tag
     * @param string $handle
     * @param string $src
     * @return string
     */
    public function add_async_defer( $tag, $handle, $src ) {
        // Scripts to defer
        $defer_scripts = array(
            'aqualuxe-navigation',
            'aqualuxe-slider',
            'aqualuxe-woocommerce',
            'aqualuxe-quick-view',
            'aqualuxe-wishlist',
        );
        
        // Scripts to async
        $async_scripts = array(
            'aqualuxe-dark-mode',
        );
        
        if ( in_array( $handle, $defer_scripts ) ) {
            $tag = str_replace( ' src', ' defer src', $tag );
        }
        
        if ( in_array( $handle, $async_scripts ) ) {
            $tag = str_replace( ' src', ' async src', $tag );
        }
        
        return $tag;
    }

    /**
     * Add preload for CSS files
     *
     * @param string $html
     * @param string $handle
     * @param string $href
     * @param string $media
     * @return string
     */
    public function add_preload_css( $html, $handle, $href, $media ) {
        // Non-critical CSS files to preload
        $preload_styles = array(
            'aqualuxe-woocommerce',
            'aqualuxe-admin',
        );
        
        if ( in_array( $handle, $preload_styles ) ) {
            $html = '<link rel="preload" href="' . esc_url( $href ) . '" as="style" onload="this.onload=null;this.rel=\'stylesheet\'">' . "\n" . $html;
        }
        
        return $html;
    }

    /**
     * Add lazy loading to images
     */
    public function lazy_load_images() {
        if ( ! wp_script_is( 'aqualuxe-main', 'enqueued' ) ) {
            return;
        }
        
        ?>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            if ('IntersectionObserver' in window) {
                const imageObserver = new IntersectionObserver((entries, observer) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const img = entry.target;
                            img.src = img.dataset.src;
                            img.classList.remove('lazy');
                            img.classList.add('lazy-loaded');
                            imageObserver.unobserve(img);
                        }
                    });
                });

                document.querySelectorAll('img[data-src]').forEach(img => {
                    imageObserver.observe(img);
                });
            }
        });
        </script>
        <?php
    }

    /**
     * Add lazy loading attributes to images
     *
     * @param array $attr
     * @param WP_Post $attachment
     * @param string $size
     * @return array
     */
    public function add_lazy_loading( $attr, $attachment, $size ) {
        if ( is_admin() || is_feed() || wp_is_mobile() ) {
            return $attr;
        }
        
        // Don't lazy load images above the fold
        if ( ! isset( $attr['class'] ) || strpos( $attr['class'], 'no-lazy' ) !== false ) {
            return $attr;
        }
        
        $attr['data-src'] = $attr['src'];
        $attr['src'] = $this->get_placeholder_image();
        $attr['class'] = ( isset( $attr['class'] ) ? $attr['class'] . ' ' : '' ) . 'lazy';
        
        return $attr;
    }

    /**
     * Get placeholder image for lazy loading
     *
     * @return string
     */
    private function get_placeholder_image() {
        // 1x1 transparent GIF
        return 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';
    }

    /**
     * Optimize database queries
     */
    public function optimize_database_queries() {
        // Remove unnecessary queries
        remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head' );
        
        // Limit post revisions
        if ( ! defined( 'WP_POST_REVISIONS' ) ) {
            define( 'WP_POST_REVISIONS', 3 );
        }
        
        // Increase autosave interval
        if ( ! defined( 'AUTOSAVE_INTERVAL' ) ) {
            define( 'AUTOSAVE_INTERVAL', 300 ); // 5 minutes
        }
    }

    /**
     * Setup caching
     */
    public function setup_caching() {
        // Enable WordPress object caching if available
        if ( function_exists( 'wp_cache_add' ) ) {
            add_action( 'init', array( $this, 'setup_object_cache' ) );
        }
        
        // Fragment caching for expensive operations
        add_action( 'init', array( $this, 'setup_fragment_cache' ) );
    }

    /**
     * Setup object caching
     */
    public function setup_object_cache() {
        // Cache expensive queries
        add_action( 'pre_get_posts', array( $this, 'cache_expensive_queries' ) );
    }

    /**
     * Setup fragment caching
     */
    public function setup_fragment_cache() {
        // Cache template parts
        add_action( 'get_template_part', array( $this, 'cache_template_part' ), 10, 2 );
    }

    /**
     * Cache expensive queries
     *
     * @param WP_Query $query
     */
    public function cache_expensive_queries( $query ) {
        if ( ! is_admin() && $query->is_main_query() ) {
            $query->set( 'cache_results', true );
            $query->set( 'update_post_meta_cache', false );
            $query->set( 'update_post_term_cache', false );
        }
    }

    /**
     * Cache template part
     *
     * @param string $slug
     * @param string $name
     */
    public function cache_template_part( $slug, $name ) {
        $cache_key = 'template_part_' . $slug . '_' . $name;
        $cached = wp_cache_get( $cache_key, 'aqualuxe_templates' );
        
        if ( false === $cached ) {
            ob_start();
            get_template_part( $slug, $name );
            $cached = ob_get_clean();
            wp_cache_set( $cache_key, $cached, 'aqualuxe_templates', HOUR_IN_SECONDS );
        }
        
        echo $cached;
    }

    /**
     * Cleanup WordPress features
     */
    private function cleanup_wordpress() {
        // Remove emoji support
        remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
        remove_action( 'wp_print_styles', 'print_emoji_styles' );
        remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
        remove_action( 'admin_print_styles', 'print_emoji_styles' );
        remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
        remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
        remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
        
        // Remove oEmbed
        remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
        remove_action( 'wp_head', 'wp_oembed_add_host_js' );
        
        // Remove REST API links
        remove_action( 'wp_head', 'rest_output_link_wp_head' );
        remove_action( 'template_redirect', 'rest_output_link_header', 11 );
        
        // Disable XML-RPC
        add_filter( 'xmlrpc_enabled', '__return_false' );
        
        // Remove pingback header
        add_filter( 'wp_headers', array( $this, 'remove_pingback_header' ) );
        
        // Disable self pingbacks
        add_action( 'pre_ping', array( $this, 'disable_self_pingback' ) );
    }

    /**
     * Remove pingback header
     *
     * @param array $headers
     * @return array
     */
    public function remove_pingback_header( $headers ) {
        unset( $headers['X-Pingback'] );
        return $headers;
    }

    /**
     * Disable self pingbacks
     *
     * @param array $links
     */
    public function disable_self_pingback( &$links ) {
        $home = get_option( 'home' );
        foreach ( $links as $l => $link ) {
            if ( 0 === strpos( $link, $home ) ) {
                unset( $links[ $l ] );
            }
        }
    }

    /**
     * Get performance metrics
     *
     * @return array
     */
    public static function get_performance_metrics() {
        $metrics = array();
        
        // Memory usage
        $metrics['memory_usage'] = memory_get_usage( true );
        $metrics['memory_peak'] = memory_get_peak_usage( true );
        $metrics['memory_limit'] = ini_get( 'memory_limit' );
        
        // Database queries
        if ( defined( 'SAVEQUERIES' ) && SAVEQUERIES ) {
            global $wpdb;
            $metrics['db_queries'] = count( $wpdb->queries );
            $metrics['db_query_time'] = array_sum( array_column( $wpdb->queries, 1 ) );
        }
        
        // Page generation time
        if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
            $metrics['generation_time'] = timer_stop( 0, 8 );
        }
        
        return $metrics;
    }

    /**
     * Clear cache
     *
     * @param string $type
     */
    public static function clear_cache( $type = 'all' ) {
        switch ( $type ) {
            case 'object':
                wp_cache_flush();
                break;
                
            case 'templates':
                wp_cache_flush_group( 'aqualuxe_templates' );
                break;
                
            case 'transients':
                global $wpdb;
                $wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_aqualuxe_%'" );
                $wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_timeout_aqualuxe_%'" );
                break;
                
            case 'all':
            default:
                wp_cache_flush();
                self::clear_cache( 'transients' );
                break;
        }
    }
}