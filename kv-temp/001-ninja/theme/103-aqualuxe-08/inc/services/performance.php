<?php
/**
 * Performance Service
 * 
 * Handles performance optimization, caching, and speed improvements
 * following SOLID principles and WordPress best practices.
 *
 * @package AquaLuxe
 * @subpackage Services
 * @since 1.0.0
 * @author AquaLuxe Development Team
 */

namespace AquaLuxe\Services;

use AquaLuxe\Core\Base_Service;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Performance Service Class
 *
 * Responsible for:
 * - Database query optimization
 * - Image optimization and lazy loading
 * - Caching strategies
 * - Critical CSS and JavaScript optimization
 * - Preloading and prefetching
 * - Performance monitoring
 *
 * @since 1.0.0
 */
class Performance extends Base_Service {

    /**
     * Performance metrics
     *
     * @var array
     */
    private $metrics = array();

    /**
     * Cache groups
     *
     * @var array
     */
    private $cache_groups = array();

    /**
     * Initialize the service.
     *
     * @return void
     */
    public function init(): void {
        $this->setup_cache_groups();
        $this->setup_hooks();
        $this->optimize_database();
    }

    /**
     * Setup cache groups.
     *
     * @return void
     */
    private function setup_cache_groups(): void {
        $this->cache_groups = array(
            'aqualuxe_templates',
            'aqualuxe_queries',
            'aqualuxe_assets',
            'aqualuxe_modules',
        );

        // Register cache groups for object caching
        foreach ( $this->cache_groups as $group ) {
            wp_cache_add_global_groups( $group );
        }
    }

    /**
     * Setup WordPress hooks.
     *
     * @return void
     */
    private function setup_hooks(): void {
        // Image optimization
        add_filter( 'wp_get_attachment_image_attributes', array( $this, 'add_lazy_loading' ), 10, 3 );
        add_filter( 'the_content', array( $this, 'add_lazy_loading_to_content' ) );
        
        // Script optimization
        add_filter( 'script_loader_tag', array( $this, 'optimize_script_loading' ), 10, 3 );
        add_filter( 'style_loader_tag', array( $this, 'optimize_style_loading' ), 10, 4 );

        // Database optimization
        add_action( 'pre_get_posts', array( $this, 'optimize_queries' ) );
        add_filter( 'posts_pre_query', array( $this, 'cache_query_results' ), 10, 2 );

        // Remove query strings from static resources
        add_filter( 'script_loader_src', array( $this, 'remove_query_strings' ) );
        add_filter( 'style_loader_src', array( $this, 'remove_query_strings' ) );

        // Optimize heartbeat
        add_filter( 'heartbeat_settings', array( $this, 'optimize_heartbeat' ) );

        // Performance monitoring
        add_action( 'wp_footer', array( $this, 'performance_monitor' ) );

        // Clean up WordPress head
        $this->cleanup_wp_head();
    }

    /**
     * Add lazy loading to images.
     *
     * @param array        $attr Image attributes.
     * @param \WP_Post     $attachment Image attachment post.
     * @param string|array $size Image size.
     * @return array Modified attributes.
     */
    public function add_lazy_loading( array $attr, $attachment, $size ): array {
        // Skip if loading attribute already set
        if ( isset( $attr['loading'] ) ) {
            return $attr;
        }

        // Skip for critical images (above the fold)
        if ( $this->is_critical_image( $attachment, $size ) ) {
            $attr['loading'] = 'eager';
        } else {
            $attr['loading'] = 'lazy';
            $attr['decoding'] = 'async';
        }

        return $attr;
    }

    /**
     * Add lazy loading to content images.
     *
     * @param string $content Post content.
     * @return string Modified content.
     */
    public function add_lazy_loading_to_content( string $content ): string {
        // Only process if not already processed
        if ( strpos( $content, 'loading=' ) !== false ) {
            return $content;
        }

        // Add loading="lazy" to images in content
        $content = preg_replace_callback(
            '/<img([^>]+)>/i',
            function( $matches ) {
                $img_tag = $matches[0];
                
                // Skip if already has loading attribute
                if ( strpos( $img_tag, 'loading=' ) !== false ) {
                    return $img_tag;
                }

                // Add lazy loading attributes
                $img_tag = str_replace( '<img', '<img loading="lazy" decoding="async"', $img_tag );
                
                return $img_tag;
            },
            $content
        );

        return $content;
    }

    /**
     * Check if image is critical (above the fold).
     *
     * @param \WP_Post     $attachment Image attachment.
     * @param string|array $size Image size.
     * @return bool True if critical image.
     */
    private function is_critical_image( $attachment, $size ): bool {
        // Hero images, logos, and featured images are critical
        $critical_contexts = array(
            'hero',
            'logo',
            'custom-logo',
            'featured-image',
        );

        // Check if size indicates critical image
        if ( is_string( $size ) && in_array( $size, $critical_contexts, true ) ) {
            return true;
        }

        // Check if in header or hero section
        $backtrace = debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS, 10 );
        foreach ( $backtrace as $trace ) {
            if ( isset( $trace['function'] ) ) {
                $function = $trace['function'];
                if ( strpos( $function, 'header' ) !== false || 
                     strpos( $function, 'hero' ) !== false ||
                     strpos( $function, 'logo' ) !== false ) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Optimize script loading.
     *
     * @param string $tag Script tag.
     * @param string $handle Script handle.
     * @param string $src Script source.
     * @return string Modified script tag.
     */
    public function optimize_script_loading( string $tag, string $handle, string $src ): string {
        // Scripts that should be deferred
        $defer_scripts = array(
            'aqualuxe-main',
            'comment-reply',
        );

        // Scripts that should be async
        $async_scripts = array(
            'google-analytics',
            'gtag',
            'facebook-pixel',
        );

        if ( in_array( $handle, $defer_scripts, true ) ) {
            $tag = str_replace( ' src', ' defer src', $tag );
        } elseif ( in_array( $handle, $async_scripts, true ) ) {
            $tag = str_replace( ' src', ' async src', $tag );
        }

        return $tag;
    }

    /**
     * Optimize style loading.
     *
     * @param string $html Link tag HTML.
     * @param string $handle Style handle.
     * @param string $href Style URL.
     * @param string $media Media attribute.
     * @return string Modified link tag.
     */
    public function optimize_style_loading( string $html, string $handle, string $href, string $media ): string {
        // Non-critical stylesheets to preload
        $preload_styles = array(
            'aqualuxe-woocommerce',
            'aqualuxe-archive',
        );

        if ( in_array( $handle, $preload_styles, true ) ) {
            // Convert to preload link
            $html = '<link rel="preload" href="' . esc_url( $href ) . '" as="style" onload="this.onload=null;this.rel=\'stylesheet\'">';
            $html .= '<noscript><link rel="stylesheet" href="' . esc_url( $href ) . '"></noscript>';
        }

        return $html;
    }

    /**
     * Optimize database queries.
     *
     * @param \WP_Query $query WordPress query object.
     * @return void
     */
    public function optimize_queries( \WP_Query $query ): void {
        if ( is_admin() || ! $query->is_main_query() ) {
            return;
        }

        // Limit posts per page on archives
        if ( $query->is_archive() || $query->is_home() ) {
            $posts_per_page = get_option( 'posts_per_page', 10 );
            $query->set( 'posts_per_page', min( $posts_per_page, 20 ) );
        }

        // Optimize search queries
        if ( $query->is_search() ) {
            // Exclude certain post types from search
            $excluded_post_types = array( 'attachment', 'revision', 'nav_menu_item' );
            $post_types = get_post_types( array( 'public' => true ) );
            $post_types = array_diff( $post_types, $excluded_post_types );
            $query->set( 'post_type', $post_types );
        }
    }

    /**
     * Cache query results.
     *
     * @param array|null $posts Posts array or null.
     * @param \WP_Query  $query Query object.
     * @return array|null Posts array or null.
     */
    public function cache_query_results( $posts, \WP_Query $query ) {
        // Only cache public queries
        if ( is_admin() || $query->is_preview() || $query->is_singular() ) {
            return $posts;
        }

        // Generate cache key based on query vars
        $cache_key = 'query_' . md5( serialize( $query->query_vars ) );
        
        // Try to get from cache
        $cached_posts = wp_cache_get( $cache_key, 'aqualuxe_queries' );
        
        if ( false !== $cached_posts ) {
            return $cached_posts;
        }

        // Cache will be set by WordPress after query execution
        add_action( 'the_posts', function( $posts ) use ( $cache_key ) {
            wp_cache_set( $cache_key, $posts, 'aqualuxe_queries', HOUR_IN_SECONDS );
            return $posts;
        });

        return $posts;
    }

    /**
     * Remove query strings from static resources.
     *
     * @param string $src Resource URL.
     * @return string Clean URL.
     */
    public function remove_query_strings( string $src ): string {
        if ( strpos( $src, 'ver=' ) !== false ) {
            $src = remove_query_arg( 'ver', $src );
        }
        
        return $src;
    }

    /**
     * Optimize WordPress heartbeat.
     *
     * @param array $settings Heartbeat settings.
     * @return array Modified settings.
     */
    public function optimize_heartbeat( array $settings ): array {
        // Slow down heartbeat on frontend
        if ( ! is_admin() ) {
            $settings['interval'] = 60; // 60 seconds instead of 15
        } else {
            $settings['interval'] = 30; // 30 seconds in admin
        }

        return $settings;
    }

    /**
     * Clean up WordPress head.
     *
     * @return void
     */
    private function cleanup_wp_head(): void {
        // Remove unnecessary head items
        remove_action( 'wp_head', 'rsd_link' );
        remove_action( 'wp_head', 'wlwmanifest_link' );
        remove_action( 'wp_head', 'wp_shortlink_wp_head' );
        remove_action( 'wp_head', 'wp_generator' );

        // Remove emoji scripts
        remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
        remove_action( 'wp_print_styles', 'print_emoji_styles' );
        remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
        remove_action( 'admin_print_styles', 'print_emoji_styles' );
        
        // Remove feed links if not needed
        remove_action( 'wp_head', 'feed_links', 2 );
        remove_action( 'wp_head', 'feed_links_extra', 3 );
    }

    /**
     * Cache template output.
     *
     * @param string $template Template name.
     * @param array  $context Template context.
     * @param int    $expiration Cache expiration time.
     * @return string|false Cached output or false.
     */
    public function cache_template( string $template, array $context = array(), int $expiration = HOUR_IN_SECONDS ) {
        $cache_key = 'template_' . md5( $template . serialize( $context ) );
        
        $cached_output = wp_cache_get( $cache_key, 'aqualuxe_templates' );
        
        if ( false !== $cached_output ) {
            return $cached_output;
        }

        return false;
    }

    /**
     * Set template cache.
     *
     * @param string $template Template name.
     * @param array  $context Template context.
     * @param string $output Template output.
     * @param int    $expiration Cache expiration time.
     * @return bool Success status.
     */
    public function set_template_cache( string $template, array $context, string $output, int $expiration = HOUR_IN_SECONDS ): bool {
        $cache_key = 'template_' . md5( $template . serialize( $context ) );
        
        return wp_cache_set( $cache_key, $output, 'aqualuxe_templates', $expiration );
    }

    /**
     * Performance monitoring.
     *
     * @return void
     */
    public function performance_monitor(): void {
        if ( ! defined( 'WP_DEBUG' ) || ! WP_DEBUG ) {
            return;
        }

        $this->metrics['memory_usage'] = memory_get_peak_usage( true );
        $this->metrics['memory_limit'] = ini_get( 'memory_limit' );
        $this->metrics['query_count'] = get_num_queries();
        $this->metrics['load_time'] = timer_stop();

        // Log performance metrics
        $this->log( sprintf(
            'Performance: Memory: %s/%s, Queries: %d, Load time: %ss',
            size_format( $this->metrics['memory_usage'] ),
            $this->metrics['memory_limit'],
            $this->metrics['query_count'],
            $this->metrics['load_time']
        ) );

        // Show debug info for admins
        if ( current_user_can( 'manage_options' ) && isset( $_GET['debug_performance'] ) ) {
            echo '<!-- Performance Debug: ' . wp_json_encode( $this->metrics ) . ' -->';
        }
    }

    /**
     * Preload critical resources.
     *
     * @return void
     */
    public function preload_critical_resources(): void {
        $critical_resources = array(
            'css/main.css' => 'style',
            'js/main.js' => 'script',
            'fonts/inter-var.woff2' => 'font',
        );

        foreach ( $critical_resources as $resource => $type ) {
            $url = AQUALUXE_ASSETS_URI . '/dist/' . $resource;
            
            echo '<link rel="preload" href="' . esc_url( $url ) . '" as="' . esc_attr( $type ) . '"';
            
            if ( $type === 'font' ) {
                echo ' type="font/woff2" crossorigin';
            }
            
            echo '>' . "\n";
        }
    }

    /**
     * Get performance metrics.
     *
     * @return array Performance metrics.
     */
    public function get_metrics(): array {
        return $this->metrics;
    }

    /**
     * Clear all caches.
     *
     * @return void
     */
    public function clear_cache(): void {
        foreach ( $this->cache_groups as $group ) {
            wp_cache_flush_group( $group );
        }
        
        $this->log( 'Performance caches cleared' );
    }

    /**
     * Get service name for dependency injection.
     *
     * @return string Service name.
     */
    public function get_service_name(): string {
        return 'performance';
    }
}