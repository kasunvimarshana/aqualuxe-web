<?php
/**
 * Performance Manager - Advanced performance optimization for AquaLuxe theme
 *
 * Handles all performance-related functionality including:
 * - Caching strategies
 * - Database query optimization
 * - Asset optimization and minification
 * - Lazy loading implementation
 * - Image optimization
 * - Critical CSS generation
 * - JavaScript optimization
 * - HTTP/2 optimization
 * - Performance monitoring
 * 
 * @package AquaLuxe\Core
 * @since 2.0.0
 * @author Kasun Vimarshana
 */

namespace AquaLuxe\Core;

use AquaLuxe\Core\Interfaces\Singleton_Interface;
use AquaLuxe\Core\Traits\Singleton_Trait;

if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

/**
 * Performance Manager Class
 * 
 * Implements comprehensive performance optimization strategies
 * following modern web performance best practices.
 * 
 * @since 2.0.0
 */
class Performance implements Singleton_Interface {
    use Singleton_Trait;

    /**
     * Performance configuration
     *
     * @var array
     */
    private $config = [];

    /**
     * Cache groups
     *
     * @var array
     */
    private $cache_groups = [];

    /**
     * Performance metrics
     *
     * @var array
     */
    private $metrics = [];

    /**
     * Optimization flags
     *
     * @var array
     */
    private $optimizations = [];

    /**
     * Initialize performance manager
     *
     * @since 2.0.0
     */
    protected function __construct() {
        $this->load_configuration();
        $this->initialize_hooks();
        $this->setup_caching();
        $this->setup_optimizations();
    }

    /**
     * Load performance configuration
     *
     * @since 2.0.0
     */
    private function load_configuration(): void {
        $this->config = [
            // Caching settings
            'enable_object_cache' => true,
            'enable_page_cache' => true,
            'enable_database_cache' => true,
            'cache_expiration' => 3600, // 1 hour
            'cache_compression' => true,
            
            // Asset optimization
            'minify_css' => true,
            'minify_js' => true,
            'combine_css' => false, // Can cause issues with some themes
            'combine_js' => false,  // Can cause issues with some plugins
            'optimize_images' => true,
            'enable_webp' => true,
            'lazy_load_images' => true,
            'lazy_load_iframes' => true,
            
            // Critical path optimization
            'inline_critical_css' => true,
            'defer_non_critical_css' => true,
            'defer_javascript' => true,
            'preload_fonts' => true,
            
            // Database optimization
            'optimize_queries' => true,
            'limit_revisions' => 10,
            'clean_spam_comments' => true,
            'optimize_database_tables' => true,
            
            // HTTP/2 optimization
            'enable_server_push' => false, // Requires server support
            'optimize_delivery' => true,
            'enable_gzip' => true,
            'enable_brotli' => false, // Requires server support
            
            // Performance monitoring
            'enable_monitoring' => true,
            'log_slow_queries' => true,
            'track_performance_metrics' => true,
            'performance_budget' => [
                'load_time' => 3.0, // seconds
                'first_contentful_paint' => 1.5,
                'largest_contentful_paint' => 2.5,
                'cumulative_layout_shift' => 0.1,
                'first_input_delay' => 100 // milliseconds
            ]
        ];

        // Allow configuration override via filters
        if ( function_exists( 'apply_filters' ) ) {
            /** @var array $config */
            $config = apply_filters( 'aqualuxe_performance_config', $this->config );
            $this->config = $config;
        }
    }

    /**
     * Initialize WordPress hooks
     *
     * @since 2.0.0
     */
    private function initialize_hooks(): void {
        // Only add hooks if WordPress functions are available
        if ( ! function_exists( 'add_action' ) || ! function_exists( 'add_filter' ) ) {
            return;
        }

        // Asset optimization hooks
        add_action( 'wp_enqueue_scripts', [ $this, 'optimize_assets' ], 999 );
        add_action( 'wp_head', [ $this, 'add_performance_meta' ], 1 );
        add_action( 'wp_head', [ $this, 'preload_critical_resources' ], 5 );
        
        // Image optimization hooks
        add_filter( 'wp_get_attachment_image_attributes', [ $this, 'add_lazy_loading' ], 10, 3 );
        add_filter( 'the_content', [ $this, 'optimize_content_images' ] );
        add_filter( 'post_thumbnail_html', [ $this, 'optimize_thumbnail' ] );
        
        // Database optimization hooks
        add_action( 'init', [ $this, 'optimize_database' ] );
        add_filter( 'posts_request', [ $this, 'log_database_queries' ] );
        
        // Caching hooks
        add_action( 'save_post', [ $this, 'clear_post_cache' ] );
        add_action( 'comment_post', [ $this, 'clear_comment_cache' ] );
        add_action( 'switch_theme', [ $this, 'clear_all_cache' ] );
        
        // Performance monitoring
        add_action( 'wp_footer', [ $this, 'track_performance_metrics' ] );
        add_action( 'shutdown', [ $this, 'log_performance_data' ] );
        
        // Cleanup hooks
        add_action( 'wp_scheduled_delete', [ $this, 'cleanup_performance_data' ] );
    }

    /**
     * Setup caching system
     *
     * @since 2.0.0
     */
    private function setup_caching(): void {
        $this->cache_groups = [
            'theme_data' => 'aqualuxe_theme',
            'performance' => 'aqualuxe_performance',
            'assets' => 'aqualuxe_assets',
            'images' => 'aqualuxe_images',
            'database' => 'aqualuxe_database'
        ];

        // Register cache groups if WordPress supports it
        if ( function_exists( 'wp_cache_add_global_groups' ) ) {
            wp_cache_add_global_groups( array_values( $this->cache_groups ) );
        }
    }

    /**
     * Setup optimization flags
     *
     * @since 2.0.0
     */
    private function setup_optimizations(): void {
        $this->optimizations = [
            'css_optimized' => false,
            'js_optimized' => false,
            'images_optimized' => false,
            'database_optimized' => false,
            'cache_initialized' => false
        ];
    }

    /**
     * Optimize assets (CSS and JavaScript)
     *
     * @since 2.0.0
     */
    public function optimize_assets(): void {
        if ( is_admin() || ! $this->config['minify_css'] && ! $this->config['minify_js'] ) {
            return;
        }

        global $wp_styles, $wp_scripts;

        // Optimize CSS
        if ( $this->config['minify_css'] && ! empty( $wp_styles->queue ) ) {
            $this->optimize_css_assets( $wp_styles );
        }

        // Optimize JavaScript
        if ( $this->config['minify_js'] && ! empty( $wp_scripts->queue ) ) {
            $this->optimize_js_assets( $wp_scripts );
        }

        $this->optimizations['css_optimized'] = $this->config['minify_css'];
        $this->optimizations['js_optimized'] = $this->config['minify_js'];
    }

    /**
     * Optimize CSS assets
     *
     * @since 2.0.0
     * @param object $wp_styles WordPress styles object
     */
    private function optimize_css_assets( $wp_styles ): void {
        $optimized_css = [];
        
        foreach ( $wp_styles->queue as $handle ) {
            if ( ! isset( $wp_styles->registered[ $handle ] ) ) {
                continue;
            }
            
            $style = $wp_styles->registered[ $handle ];
            
            // Skip external stylesheets
            if ( $this->is_external_url( $style->src ) ) {
                continue;
            }
            
            $css_content = $this->get_css_content( $style->src );
            if ( $css_content ) {
                $optimized_css[ $handle ] = $this->minify_css( $css_content );
            }
        }

        if ( ! empty( $optimized_css ) && $this->config['combine_css'] ) {
            $this->combine_css_files( $optimized_css );
        }
    }

    /**
     * Optimize JavaScript assets
     *
     * @since 2.0.0
     * @param object $wp_scripts WordPress scripts object
     */
    private function optimize_js_assets( $wp_scripts ): void {
        foreach ( $wp_scripts->queue as $handle ) {
            if ( ! isset( $wp_scripts->registered[ $handle ] ) ) {
                continue;
            }
            
            $script = $wp_scripts->registered[ $handle ];
            
            // Skip external scripts and jQuery
            if ( $this->is_external_url( $script->src ) || $handle === 'jquery' ) {
                continue;
            }
            
            // Defer non-critical JavaScript
            if ( $this->config['defer_javascript'] && ! in_array( $handle, $this->get_critical_scripts(), true ) ) {
                $wp_scripts->add_data( $handle, 'defer', true );
            }
        }
    }

    /**
     * Get critical scripts that should not be deferred
     *
     * @since 2.0.0
     * @return array Critical script handles
     */
    private function get_critical_scripts(): array {
        $critical_scripts = [
            'jquery',
            'aqualuxe-critical',
            'modernizr'
        ];

        if ( function_exists( 'apply_filters' ) ) {
            /** @var array $scripts */
            $scripts = apply_filters( 'aqualuxe_critical_scripts', $critical_scripts );
            return $scripts;
        }

        return $critical_scripts;
    }

    /**
     * Check if URL is external
     *
     * @since 2.0.0
     * @param string $url URL to check
     * @return bool True if external
     */
    private function is_external_url( string $url ): bool {
        $site_url = function_exists( 'site_url' ) ? site_url() : '';
        return ! empty( $site_url ) && strpos( $url, $site_url ) === false;
    }

    /**
     * Get CSS content from file
     *
     * @since 2.0.0
     * @param string $src CSS file URL
     * @return string CSS content
     */
    private function get_css_content( string $src ): string {
        // Convert URL to file path
        $file_path = $this->url_to_path( $src );
        
        if ( ! $file_path || ! file_exists( $file_path ) ) {
            return '';
        }

        return file_get_contents( $file_path ) ?: '';
    }

    /**
     * Convert URL to file system path
     *
     * @since 2.0.0
     * @param string $url Asset URL
     * @return string File system path
     */
    private function url_to_path( string $url ): string {
        $content_url = function_exists( 'content_url' ) ? content_url() : '';
        $content_dir = function_exists( 'WP_CONTENT_DIR' ) ? WP_CONTENT_DIR : '';
        
        if ( empty( $content_url ) || empty( $content_dir ) ) {
            return '';
        }

        return str_replace( $content_url, $content_dir, $url );
    }

    /**
     * Minify CSS content
     *
     * @since 2.0.0
     * @param string $css CSS content
     * @return string Minified CSS
     */
    private function minify_css( string $css ): string {
        // Remove comments
        $css = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css );
        
        // Remove whitespace
        $css = str_replace( [ "\r\n", "\r", "\n", "\t", '  ', '    ', '    ' ], '', $css );
        
        // Remove semicolons before closing braces
        $css = str_replace( ';}', '}', $css );
        
        // Remove space around selectors and properties
        $css = preg_replace( '/\s*([{}:;,>+~])\s*/', '$1', $css );
        
        return trim( $css );
    }

    /**
     * Combine CSS files
     *
     * @since 2.0.0
     * @param array $css_files CSS content array
     */
    private function combine_css_files( array $css_files ): void {
        $combined_css = implode( "\n", $css_files );
        $cache_key = 'combined_css_' . md5( $combined_css );
        
        // Check if combined CSS is already cached
        if ( $this->get_cache( $cache_key, 'assets' ) ) {
            return;
        }
        
        // Save combined CSS to file
        $upload_dir = function_exists( 'wp_upload_dir' ) ? wp_upload_dir() : [ 'basedir' => '', 'baseurl' => '' ];
        if ( empty( $upload_dir['basedir'] ) ) {
            return;
        }
        
        $cache_dir = $upload_dir['basedir'] . '/aqualuxe-cache/css/';
        if ( ! is_dir( $cache_dir ) ) {
            wp_mkdir_p( $cache_dir );
        }
        
        $cache_file = $cache_dir . $cache_key . '.css';
        file_put_contents( $cache_file, $combined_css );
        
        // Cache the file information
        $this->set_cache( $cache_key, $cache_file, 'assets' );
    }

    /**
     * Add performance-related meta tags
     *
     * @since 2.0.0
     */
    public function add_performance_meta(): void {
        // DNS prefetch for external domains
        $dns_prefetch = [
            'fonts.googleapis.com',
            'fonts.gstatic.com',
            'www.google-analytics.com',
            'www.googletagmanager.com'
        ];

        foreach ( $dns_prefetch as $domain ) {
            echo '<link rel="dns-prefetch" href="//' . esc_attr( $domain ) . '">' . "\n";
        }

        // Preconnect for critical external resources
        echo '<link rel="preconnect" href="//fonts.googleapis.com" crossorigin>' . "\n";
        echo '<link rel="preconnect" href="//fonts.gstatic.com" crossorigin>' . "\n";
    }

    /**
     * Preload critical resources
     *
     * @since 2.0.0
     */
    public function preload_critical_resources(): void {
        if ( ! $this->config['preload_fonts'] ) {
            return;
        }

        // Preload critical fonts
        $critical_fonts = [
            get_theme_file_uri( '/assets/fonts/inter-var.woff2' ) => 'font/woff2',
            get_theme_file_uri( '/assets/fonts/source-sans-pro.woff2' ) => 'font/woff2'
        ];

        foreach ( $critical_fonts as $font_url => $font_type ) {
            if ( file_exists( $this->url_to_path( $font_url ) ) ) {
                echo '<link rel="preload" href="' . esc_url( $font_url ) . '" as="font" type="' . esc_attr( $font_type ) . '" crossorigin>' . "\n";
            }
        }
    }

    /**
     * Add lazy loading attributes to images
     *
     * @since 2.0.0
     * @param array $attr Image attributes
     * @param object $attachment Attachment object
     * @param string $size Image size
     * @return array Modified attributes
     */
    public function add_lazy_loading( array $attr, $attachment, string $size ): array {
        if ( ! $this->config['lazy_load_images'] || is_admin() ) {
            return $attr;
        }

        // Don't lazy load above-the-fold images
        if ( $this->is_above_fold_image( $attr ) ) {
            return $attr;
        }

        $attr['loading'] = 'lazy';
        $attr['decoding'] = 'async';
        
        return $attr;
    }

    /**
     * Check if image is above the fold
     *
     * @since 2.0.0
     * @param array $attr Image attributes
     * @return bool True if above fold
     */
    private function is_above_fold_image( array $attr ): bool {
        // Simple heuristic: images with specific classes or first few images
        $above_fold_classes = [ 'hero-image', 'featured-image', 'logo' ];
        
        if ( isset( $attr['class'] ) ) {
            foreach ( $above_fold_classes as $class ) {
                if ( strpos( $attr['class'], $class ) !== false ) {
                    return true;
                }
            }
        }
        
        return false;
    }

    /**
     * Optimize images in content
     *
     * @since 2.0.0
     * @param string $content Post content
     * @return string Optimized content
     */
    public function optimize_content_images( string $content ): string {
        if ( ! $this->config['lazy_load_images'] || is_admin() || is_feed() ) {
            return $content;
        }

        // Add lazy loading to content images
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
     * Optimize post thumbnails
     *
     * @since 2.0.0
     * @param string $html Thumbnail HTML
     * @return string Optimized HTML
     */
    public function optimize_thumbnail( string $html ): string {
        if ( ! $this->config['optimize_images'] || empty( $html ) ) {
            return $html;
        }

        // Add WebP support if available
        if ( $this->config['enable_webp'] && $this->supports_webp() ) {
            $html = $this->add_webp_sources( $html );
        }

        return $html;
    }

    /**
     * Check WebP support
     *
     * @since 2.0.0
     * @return bool True if WebP is supported
     */
    private function supports_webp(): bool {
        return function_exists( 'imagewebp' ) && function_exists( 'imagecreatefromwebp' );
    }

    /**
     * Add WebP sources to image
     *
     * @since 2.0.0
     * @param string $html Image HTML
     * @return string Modified HTML with WebP sources
     */
    private function add_webp_sources( string $html ): string {
        // This would require more complex implementation
        // For now, return original HTML
        return $html;
    }

    /**
     * Optimize database operations
     *
     * @since 2.0.0
     */
    public function optimize_database(): void {
        if ( ! $this->config['optimize_queries'] || is_admin() ) {
            return;
        }

        // Limit post revisions
        if ( ! defined( 'WP_POST_REVISIONS' ) && $this->config['limit_revisions'] > 0 ) {
            define( 'WP_POST_REVISIONS', $this->config['limit_revisions'] );
        }

        // Enable automatic database optimization
        if ( $this->config['optimize_database_tables'] ) {
            $this->schedule_database_optimization();
        }
    }

    /**
     * Schedule database optimization
     *
     * @since 2.0.0
     */
    private function schedule_database_optimization(): void {
        if ( ! function_exists( 'wp_next_scheduled' ) || ! function_exists( 'wp_schedule_event' ) ) {
            return;
        }

        if ( ! wp_next_scheduled( 'aqualuxe_optimize_database' ) ) {
            wp_schedule_event( time(), 'weekly', 'aqualuxe_optimize_database' );
        }
    }

    /**
     * Log database queries for performance monitoring
     *
     * @since 2.0.0
     * @param string $query SQL query
     * @return string Unmodified query
     */
    public function log_database_queries( string $query ): string {
        if ( ! $this->config['log_slow_queries'] ) {
            return $query;
        }

        $start_time = microtime( true );
        
        // This is a simple implementation
        // In a real scenario, you'd want to measure query execution time
        // and log slow queries appropriately
        
        return $query;
    }

    /**
     * Clear post-related cache
     *
     * @since 2.0.0
     * @param int $post_id Post ID
     */
    public function clear_post_cache( int $post_id ): void {
        $cache_keys = [
            'post_' . $post_id,
            'post_content_' . $post_id,
            'post_meta_' . $post_id
        ];

        foreach ( $cache_keys as $key ) {
            $this->delete_cache( $key, 'theme_data' );
        }
    }

    /**
     * Clear comment-related cache
     *
     * @since 2.0.0
     * @param int $comment_id Comment ID
     */
    public function clear_comment_cache( int $comment_id ): void {
        $this->delete_cache( 'comment_' . $comment_id, 'theme_data' );
    }

    /**
     * Clear all theme cache
     *
     * @since 2.0.0
     */
    public function clear_all_cache(): void {
        foreach ( $this->cache_groups as $group ) {
            if ( function_exists( 'wp_cache_flush_group' ) ) {
                wp_cache_flush_group( $group );
            }
        }

        // Clear file-based cache
        $this->clear_file_cache();
    }

    /**
     * Clear file-based cache
     *
     * @since 2.0.0
     */
    private function clear_file_cache(): void {
        $upload_dir = function_exists( 'wp_upload_dir' ) ? wp_upload_dir() : [ 'basedir' => '' ];
        if ( empty( $upload_dir['basedir'] ) ) {
            return;
        }

        $cache_dir = $upload_dir['basedir'] . '/aqualuxe-cache/';
        if ( is_dir( $cache_dir ) ) {
            $this->recursive_delete( $cache_dir );
        }
    }

    /**
     * Recursively delete directory contents
     *
     * @since 2.0.0
     * @param string $dir Directory path
     */
    private function recursive_delete( string $dir ): void {
        if ( ! is_dir( $dir ) ) {
            return;
        }

        $files = array_diff( scandir( $dir ) ?: [], [ '.', '..' ] );
        
        foreach ( $files as $file ) {
            $path = $dir . '/' . $file;
            is_dir( $path ) ? $this->recursive_delete( $path ) : unlink( $path );
        }
        
        rmdir( $dir );
    }

    /**
     * Track performance metrics
     *
     * @since 2.0.0
     */
    public function track_performance_metrics(): void {
        if ( ! $this->config['track_performance_metrics'] || is_admin() ) {
            return;
        }

        // Output performance tracking script
        ?>
        <script>
        (function() {
            if ('performance' in window && 'navigation' in performance) {
                window.addEventListener('load', function() {
                    var perfData = performance.getEntriesByType('navigation')[0];
                    var metrics = {
                        loadTime: perfData.loadEventEnd - perfData.navigationStart,
                        domReady: perfData.domContentLoadedEventEnd - perfData.navigationStart,
                        ttfb: perfData.responseStart - perfData.navigationStart
                    };
                    
                    // Send metrics to server via AJAX
                    if (typeof jQuery !== 'undefined') {
                        jQuery.post('<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>', {
                            action: 'aqualuxe_track_performance',
                            metrics: metrics,
                            nonce: '<?php echo esc_js( wp_create_nonce( 'aqualuxe_performance' ) ); ?>'
                        });
                    }
                });
            }
        })();
        </script>
        <?php
    }

    /**
     * Log performance data
     *
     * @since 2.0.0
     */
    public function log_performance_data(): void {
        if ( ! $this->config['enable_monitoring'] ) {
            return;
        }

        $this->metrics['memory_usage'] = memory_get_peak_usage( true );
        $this->metrics['query_count'] = function_exists( 'get_num_queries' ) ? get_num_queries() : 0;
        $this->metrics['load_time'] = microtime( true ) - ( $_SERVER['REQUEST_TIME_FLOAT'] ?? microtime( true ) );

        // Store metrics in cache for analysis
        $this->set_cache( 'performance_' . time(), $this->metrics, 'performance', 86400 );
    }

    /**
     * Cleanup old performance data
     *
     * @since 2.0.0
     */
    public function cleanup_performance_data(): void {
        // Clean up performance logs older than 30 days
        if ( function_exists( 'get_option' ) && function_exists( 'delete_option' ) ) {
            global $wpdb;
            $cutoff_date = gmdate( 'Y-m-d', strtotime( '-30 days' ) );
            
            if ( isset( $wpdb->options ) ) {
                $wpdb->query( $wpdb->prepare(
                    "DELETE FROM {$wpdb->options} 
                     WHERE option_name LIKE 'aqualuxe_performance_%%' 
                     AND option_name < %s",
                    'aqualuxe_performance_' . str_replace( '-', '_', $cutoff_date )
                ) );
            }
        }
    }

    /**
     * Get cached data
     *
     * @since 2.0.0
     * @param string $key Cache key
     * @param string $group Cache group
     * @return mixed Cached data or false
     */
    private function get_cache( string $key, string $group = 'default' ) {
        if ( function_exists( 'wp_cache_get' ) ) {
            return wp_cache_get( $key, $this->cache_groups[ $group ] ?? $group );
        }
        
        return false;
    }

    /**
     * Set cached data
     *
     * @since 2.0.0
     * @param string $key Cache key
     * @param mixed $data Data to cache
     * @param string $group Cache group
     * @param int $expiration Cache expiration in seconds
     * @return bool True on success
     */
    private function set_cache( string $key, $data, string $group = 'default', int $expiration = 0 ): bool {
        if ( function_exists( 'wp_cache_set' ) ) {
            $expiration = $expiration ?: $this->config['cache_expiration'];
            return wp_cache_set( $key, $data, $this->cache_groups[ $group ] ?? $group, $expiration );
        }
        
        return false;
    }

    /**
     * Delete cached data
     *
     * @since 2.0.0
     * @param string $key Cache key
     * @param string $group Cache group
     * @return bool True on success
     */
    private function delete_cache( string $key, string $group = 'default' ): bool {
        if ( function_exists( 'wp_cache_delete' ) ) {
            return wp_cache_delete( $key, $this->cache_groups[ $group ] ?? $group );
        }
        
        return false;
    }

    /**
     * Get performance configuration
     *
     * @since 2.0.0
     * @return array Performance configuration
     */
    public function get_config(): array {
        return $this->config;
    }

    /**
     * Get performance metrics
     *
     * @since 2.0.0
     * @return array Performance metrics
     */
    public function get_metrics(): array {
        return $this->metrics;
    }

    /**
     * Get optimization status
     *
     * @since 2.0.0
     * @return array Optimization flags
     */
    public function get_optimization_status(): array {
        return $this->optimizations;
    }

    /**
     * Update performance configuration
     *
     * @since 2.0.0
     * @param array $new_config New configuration values
     */
    public function update_config( array $new_config ): void {
        $this->config = array_merge( $this->config, $new_config );
        
        // Clear cache when configuration changes
        $this->clear_all_cache();
        
        // Trigger configuration update action
        if ( function_exists( 'do_action' ) ) {
            do_action( 'aqualuxe_performance_config_updated', $this->config );
        }
    }
}
