<?php
/**
 * Performance Module
 *
 * Handles theme performance optimization
 *
 * @package AquaLuxe\Modules
 * @since 1.0.0
 */

namespace AquaLuxe\Modules;

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class Performance
 *
 * Implements performance optimization features
 *
 * @since 1.0.0
 */
class Performance {

    /**
     * Initialize the performance module
     *
     * @since 1.0.0
     */
    public function init() {
        add_action( 'wp_enqueue_scripts', array( $this, 'optimize_scripts' ), 999 );
        add_action( 'wp_head', array( $this, 'add_preload_hints' ), 1 );
        add_action( 'wp_head', array( $this, 'add_dns_prefetch' ), 1 );
        add_filter( 'script_loader_tag', array( $this, 'add_script_attributes' ), 10, 3 );
        add_filter( 'style_loader_tag', array( $this, 'add_style_attributes' ), 10, 4 );
        
        // Image optimization
        add_filter( 'wp_get_attachment_image_attributes', array( $this, 'add_lazy_loading' ), 10, 3 );
        add_filter( 'the_content', array( $this, 'add_lazy_loading_to_content' ) );
        
        // Database optimization
        add_action( 'wp_dashboard_setup', array( $this, 'add_performance_widget' ) );
        
        // Cache optimization
        add_action( 'init', array( $this, 'setup_caching' ) );
        
        // Remove unnecessary features
        add_action( 'init', array( $this, 'remove_unnecessary_features' ) );
        
        // Optimize WordPress queries
        add_action( 'pre_get_posts', array( $this, 'optimize_queries' ) );
    }

    /**
     * Optimize script loading
     *
     * @since 1.0.0
     */
    public function optimize_scripts() {
        // Remove jQuery migrate in production
        if ( ! is_admin() && ! WP_DEBUG ) {
            wp_deregister_script( 'jquery-migrate' );
        }

        // Remove emoji scripts
        remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
        remove_action( 'wp_print_styles', 'print_emoji_styles' );
        
        // Remove block library CSS if not needed
        if ( ! is_admin() && ! has_blocks() ) {
            wp_dequeue_style( 'wp-block-library' );
            wp_dequeue_style( 'wp-block-library-theme' );
            wp_dequeue_style( 'wc-blocks-style' );
        }
    }

    /**
     * Add preload hints for critical resources
     *
     * @since 1.0.0
     */
    public function add_preload_hints() {
        // Preload critical fonts
        $fonts = array(
            'https://fonts.gstatic.com/s/inter/v12/UcCO3FwrK3iLTeHuS_fvQtMwCp50KnMw2boKoduKmMEVuLyeMZhrib2Bg-4.woff2',
        );

        foreach ( $fonts as $font ) {
            echo '<link rel="preload" href="' . esc_url( $font ) . '" as="font" type="font/woff2" crossorigin>' . "\n";
        }

        // Preload critical CSS
        $critical_css = get_theme_mod( 'aqualuxe_critical_css' );
        if ( ! empty( $critical_css ) ) {
            echo '<style id="aqualuxe-critical-css">' . wp_strip_all_tags( $critical_css ) . '</style>' . "\n";
        }
    }

    /**
     * Add DNS prefetch hints
     *
     * @since 1.0.0
     */
    public function add_dns_prefetch() {
        $domains = array(
            'fonts.googleapis.com',
            'fonts.gstatic.com',
        );

        // Add custom domains from theme options
        $custom_domains = get_theme_mod( 'aqualuxe_dns_prefetch_domains', '' );
        if ( ! empty( $custom_domains ) ) {
            $custom_domains = explode( "\n", $custom_domains );
            $domains = array_merge( $domains, array_map( 'trim', $custom_domains ) );
        }

        foreach ( array_unique( $domains ) as $domain ) {
            if ( ! empty( $domain ) ) {
                echo '<link rel="dns-prefetch" href="//' . esc_attr( $domain ) . '">' . "\n";
            }
        }
    }

    /**
     * Add attributes to script tags
     *
     * @since 1.0.0
     * @param string $tag    Script tag
     * @param string $handle Script handle
     * @param string $src    Script source
     * @return string
     */
    public function add_script_attributes( $tag, $handle, $src ) {
        // Add defer to non-critical scripts
        $defer_scripts = array(
            'aqualuxe-script',
            'comment-reply',
        );

        if ( in_array( $handle, $defer_scripts, true ) ) {
            $tag = str_replace( '<script ', '<script defer ', $tag );
        }

        // Add async to specific scripts
        $async_scripts = array(
            'google-analytics',
        );

        if ( in_array( $handle, $async_scripts, true ) ) {
            $tag = str_replace( '<script ', '<script async ', $tag );
        }

        return $tag;
    }

    /**
     * Add attributes to style tags
     *
     * @since 1.0.0
     * @param string $html   Style tag HTML
     * @param string $handle Style handle
     * @param string $href   Style URL
     * @param string $media  Style media attribute
     * @return string
     */
    public function add_style_attributes( $html, $handle, $href, $media ) {
        // Load non-critical CSS asynchronously
        $async_styles = array(
            'aqualuxe-fonts',
        );

        if ( in_array( $handle, $async_styles, true ) ) {
            $html = str_replace( 'rel=\'stylesheet\'', 'rel=\'preload\' as=\'style\' onload=\'this.onload=null;this.rel="stylesheet"\'', $html );
            $html .= '<noscript>' . str_replace( 'rel=\'preload\' as=\'style\' onload=\'this.onload=null;this.rel="stylesheet"\'', 'rel=\'stylesheet\'', $html ) . '</noscript>';
        }

        return $html;
    }

    /**
     * Add lazy loading to images
     *
     * @since 1.0.0
     * @param array $attr       Image attributes
     * @param object $attachment Attachment object
     * @param string $size      Image size
     * @return array
     */
    public function add_lazy_loading( $attr, $attachment, $size ) {
        if ( ! is_admin() && ! wp_is_mobile() ) {
            $attr['loading'] = 'lazy';
            $attr['decoding'] = 'async';
        }

        return $attr;
    }

    /**
     * Add lazy loading to content images
     *
     * @since 1.0.0
     * @param string $content Post content
     * @return string
     */
    public function add_lazy_loading_to_content( $content ) {
        if ( ! is_admin() && ! is_feed() && ! wp_is_mobile() ) {
            $content = preg_replace( '/<img(.*?)src=/', '<img$1loading="lazy" decoding="async" src=', $content );
        }

        return $content;
    }

    /**
     * Setup caching headers
     *
     * @since 1.0.0
     */
    public function setup_caching() {
        if ( ! is_admin() ) {
            // Set cache headers for static assets
            add_action( 'send_headers', array( $this, 'send_cache_headers' ) );
        }
    }

    /**
     * Send cache headers
     *
     * @since 1.0.0
     */
    public function send_cache_headers() {
        if ( is_404() ) {
            return;
        }

        $cache_time = HOUR_IN_SECONDS;

        if ( is_singular() ) {
            $cache_time = DAY_IN_SECONDS;
        } elseif ( is_home() || is_front_page() ) {
            $cache_time = HOUR_IN_SECONDS * 2;
        }

        $cache_time = apply_filters( 'aqualuxe_cache_time', $cache_time );

        header( 'Cache-Control: public, max-age=' . $cache_time );
        header( 'Expires: ' . gmdate( 'D, d M Y H:i:s', time() + $cache_time ) . ' GMT' );
    }

    /**
     * Remove unnecessary WordPress features
     *
     * @since 1.0.0
     */
    public function remove_unnecessary_features() {
        // Remove emoji support
        remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
        remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
        remove_action( 'wp_print_styles', 'print_emoji_styles' );
        remove_action( 'admin_print_styles', 'print_emoji_styles' );
        remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
        remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
        remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );

        // Remove RSD link
        remove_action( 'wp_head', 'rsd_link' );

        // Remove Windows Live Writer link
        remove_action( 'wp_head', 'wlwmanifest_link' );

        // Remove shortlink
        remove_action( 'wp_head', 'wp_shortlink_wp_head' );

        // Remove REST API links
        remove_action( 'wp_head', 'rest_output_link_wp_head' );
        remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );

        // Disable XML-RPC
        add_filter( 'xmlrpc_enabled', '__return_false' );

        // Remove WordPress version from RSS
        add_filter( 'the_generator', '__return_empty_string' );

        // Disable pingbacks
        add_filter( 'xmlrpc_methods', array( $this, 'disable_xmlrpc_pingback' ) );
    }

    /**
     * Disable XML-RPC pingback
     *
     * @since 1.0.0
     * @param array $methods XML-RPC methods
     * @return array
     */
    public function disable_xmlrpc_pingback( $methods ) {
        unset( $methods['pingback.ping'] );
        return $methods;
    }

    /**
     * Optimize database queries
     *
     * @since 1.0.0
     * @param WP_Query $query Query object
     */
    public function optimize_queries( $query ) {
        if ( ! is_admin() && $query->is_main_query() ) {
            // Optimize home page query
            if ( $query->is_home() ) {
                $query->set( 'posts_per_page', get_theme_mod( 'aqualuxe_posts_per_page', 10 ) );
            }

            // Optimize search query
            if ( $query->is_search() ) {
                $query->set( 'posts_per_page', 5 );
                $query->set( 'post_type', array( 'post', 'page' ) );
            }
        }
    }

    /**
     * Add performance dashboard widget
     *
     * @since 1.0.0
     */
    public function add_performance_widget() {
        wp_add_dashboard_widget(
            'aqualuxe_performance',
            esc_html__( 'AquaLuxe Performance', 'aqualuxe' ),
            array( $this, 'performance_widget_content' )
        );
    }

    /**
     * Performance widget content
     *
     * @since 1.0.0
     */
    public function performance_widget_content() {
        // Get performance metrics
        $query_count = get_num_queries();
        $page_load_time = timer_stop( 0, 3 );
        $memory_usage = size_format( memory_get_peak_usage( true ) );

        echo '<div class="performance-metrics">';
        echo '<p><strong>' . esc_html__( 'Database Queries:', 'aqualuxe' ) . '</strong> ' . esc_html( $query_count ) . '</p>';
        echo '<p><strong>' . esc_html__( 'Page Load Time:', 'aqualuxe' ) . '</strong> ' . esc_html( $page_load_time ) . 's</p>';
        echo '<p><strong>' . esc_html__( 'Memory Usage:', 'aqualuxe' ) . '</strong> ' . esc_html( $memory_usage ) . '</p>';
        echo '</div>';

        // Performance tips
        echo '<div class="performance-tips">';
        echo '<h4>' . esc_html__( 'Performance Tips:', 'aqualuxe' ) . '</h4>';
        echo '<ul>';
        echo '<li>' . esc_html__( 'Enable object caching for better performance', 'aqualuxe' ) . '</li>';
        echo '<li>' . esc_html__( 'Optimize images and use WebP format', 'aqualuxe' ) . '</li>';
        echo '<li>' . esc_html__( 'Use a CDN for static assets', 'aqualuxe' ) . '</li>';
        echo '<li>' . esc_html__( 'Minify and combine CSS/JS files', 'aqualuxe' ) . '</li>';
        echo '</ul>';
        echo '</div>';
    }

    /**
     * Get cache statistics
     *
     * @since 1.0.0
     * @return array
     */
    public function get_cache_stats() {
        return array(
            'hits'   => 0, // Would integrate with caching plugin
            'misses' => 0,
            'ratio'  => 0,
        );
    }

    /**
     * Clear theme cache
     *
     * @since 1.0.0
     */
    public function clear_cache() {
        // Clear object cache
        if ( function_exists( 'wp_cache_flush' ) ) {
            wp_cache_flush();
        }

        // Clear theme transients
        global $wpdb;
        $wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_aqualuxe_%'" );
        $wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_timeout_aqualuxe_%'" );

        do_action( 'aqualuxe_cache_cleared' );
    }

    /**
     * Optimize images on upload
     *
     * @since 1.0.0
     * @param array $metadata Image metadata
     * @param int   $attachment_id Attachment ID
     * @return array
     */
    public function optimize_image_upload( $metadata, $attachment_id ) {
        // Add WebP generation logic here
        // This would typically integrate with an image optimization service
        
        return $metadata;
    }
}