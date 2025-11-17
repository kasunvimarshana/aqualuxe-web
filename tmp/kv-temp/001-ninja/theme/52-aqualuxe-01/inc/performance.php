<?php
/**
 * Performance Optimization Features
 *
 * @package AquaLuxe
 */

/**
 * AquaLuxe Performance Optimization Class
 */
class AquaLuxe_Performance {
    /**
     * Instance of this class
     *
     * @var AquaLuxe_Performance
     */
    private static $instance;

    /**
     * Get the singleton instance of this class
     *
     * @return AquaLuxe_Performance
     */
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        // Initialize performance optimizations
        $this->init();
    }

    /**
     * Initialize performance optimizations
     */
    private function init() {
        // Asset optimization
        add_action( 'wp_enqueue_scripts', array( $this, 'optimize_assets' ), 999 );
        
        // Image optimization
        add_filter( 'wp_calculate_image_srcset', array( $this, 'optimize_image_srcset' ), 10, 5 );
        add_filter( 'wp_get_attachment_image_attributes', array( $this, 'add_image_loading_attribute' ), 10, 3 );
        
        // Database optimization
        add_action( 'wp_dashboard_setup', array( $this, 'add_performance_dashboard_widget' ) );
        
        // Cache optimization
        add_action( 'template_redirect', array( $this, 'setup_page_cache' ) );
        
        // HTTP headers optimization
        add_action( 'send_headers', array( $this, 'optimize_http_headers' ) );
        
        // Emoji removal (optional)
        if ( get_theme_mod( 'aqualuxe_disable_emoji', false ) ) {
            $this->disable_emoji();
        }
        
        // Disable embeds (optional)
        if ( get_theme_mod( 'aqualuxe_disable_embeds', false ) ) {
            $this->disable_embeds();
        }
        
        // Preload critical assets
        add_action( 'wp_head', array( $this, 'preload_critical_assets' ), 1 );
        
        // Add theme support for responsive embeds
        add_theme_support( 'responsive-embeds' );
        
        // Add theme support for HTML5
        add_theme_support( 'html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script',
        ) );
        
        // Defer non-critical JavaScript
        if ( get_theme_mod( 'aqualuxe_defer_js', true ) ) {
            add_filter( 'script_loader_tag', array( $this, 'defer_js' ), 10, 3 );
        }
        
        // Add resource hints
        add_filter( 'wp_resource_hints', array( $this, 'resource_hints' ), 10, 2 );
        
        // WooCommerce specific optimizations
        if ( class_exists( 'WooCommerce' ) ) {
            $this->woocommerce_optimizations();
        }
    }

    /**
     * Optimize assets
     */
    public function optimize_assets() {
        // Remove unnecessary assets
        wp_dequeue_style( 'wp-block-library-theme' );
        
        // Only load WooCommerce styles on WooCommerce pages
        if ( class_exists( 'WooCommerce' ) && ! is_woocommerce() && ! is_cart() && ! is_checkout() && ! is_account_page() ) {
            wp_dequeue_style( 'woocommerce-general' );
            wp_dequeue_style( 'woocommerce-layout' );
            wp_dequeue_style( 'woocommerce-smallscreen' );
            wp_dequeue_style( 'woocommerce_frontend_styles' );
            wp_dequeue_style( 'woocommerce_fancybox_styles' );
            wp_dequeue_style( 'woocommerce_chosen_styles' );
            wp_dequeue_style( 'woocommerce_prettyPhoto_css' );
        }
        
        // Only load contact form 7 styles on pages with the form
        if ( class_exists( 'WPCF7' ) ) {
            wp_dequeue_style( 'contact-form-7' );
            
            if ( is_singular() ) {
                global $post;
                if ( $post && has_shortcode( $post->post_content, 'contact-form-7' ) ) {
                    wp_enqueue_style( 'contact-form-7' );
                }
            }
        }
    }

    /**
     * Optimize image srcset
     *
     * @param array  $sources       Sources for the image srcset.
     * @param array  $size_array    Array of width and height values.
     * @param string $image_src     URL to the image file.
     * @param array  $image_meta    Meta data for the image.
     * @param int    $attachment_id Attachment ID.
     * @return array
     */
    public function optimize_image_srcset( $sources, $size_array, $image_src, $image_meta, $attachment_id ) {
        // Add WebP versions if available
        if ( function_exists( 'imagewebp' ) && ! empty( $sources ) ) {
            foreach ( $sources as $width => $source ) {
                $webp_url = $this->get_webp_url( $source['url'] );
                
                if ( $webp_url && $this->webp_exists( $webp_url ) ) {
                    $sources[ $width ]['url'] = $webp_url;
                }
            }
        }
        
        return $sources;
    }

    /**
     * Get WebP URL
     *
     * @param string $url Image URL.
     * @return string|bool
     */
    private function get_webp_url( $url ) {
        $ext = pathinfo( $url, PATHINFO_EXTENSION );
        
        if ( in_array( $ext, array( 'jpg', 'jpeg', 'png' ), true ) ) {
            return str_replace( '.' . $ext, '.webp', $url );
        }
        
        return false;
    }

    /**
     * Check if WebP image exists
     *
     * @param string $url WebP image URL.
     * @return bool
     */
    private function webp_exists( $url ) {
        $webp_path = str_replace( site_url( '/' ), ABSPATH, $url );
        return file_exists( $webp_path );
    }

    /**
     * Add image loading attribute
     *
     * @param array   $attr       Attributes for the image markup.
     * @param WP_Post $attachment Image attachment post.
     * @param string  $size       Requested size.
     * @return array
     */
    public function add_image_loading_attribute( $attr, $attachment, $size ) {
        if ( ! isset( $attr['loading'] ) ) {
            $attr['loading'] = 'lazy';
        }
        
        return $attr;
    }

    /**
     * Add performance dashboard widget
     */
    public function add_performance_dashboard_widget() {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }
        
        wp_add_dashboard_widget(
            'aqualuxe_performance_widget',
            __( 'AquaLuxe Performance', 'aqualuxe' ),
            array( $this, 'performance_dashboard_widget_content' )
        );
    }

    /**
     * Performance dashboard widget content
     */
    public function performance_dashboard_widget_content() {
        global $wpdb;
        
        // Get database size
        $db_size = $wpdb->get_var( "SELECT SUM(data_length + index_length) FROM information_schema.TABLES WHERE table_schema = '" . DB_NAME . "'" );
        $db_size = size_format( $db_size, 2 );
        
        // Get number of transients
        $transient_count = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->options WHERE option_name LIKE '%_transient_%'" );
        
        // Get number of autoloaded options
        $autoloaded_size = $wpdb->get_var( "SELECT SUM(LENGTH(option_value)) FROM $wpdb->options WHERE autoload = 'yes'" );
        $autoloaded_size = size_format( $autoloaded_size, 2 );
        
        // Get number of posts
        $post_count = wp_count_posts();
        $post_count_total = $post_count->publish + $post_count->draft + $post_count->pending + $post_count->private + $post_count->trash;
        
        // Get number of revisions
        $revision_count = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->posts WHERE post_type = 'revision'" );
        
        // Get number of attachments
        $attachment_count = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->posts WHERE post_type = 'attachment'" );
        
        // Get total attachment size
        $upload_dir = wp_upload_dir();
        $upload_dir_path = $upload_dir['basedir'];
        $attachment_size = $this->get_directory_size( $upload_dir_path );
        $attachment_size = size_format( $attachment_size, 2 );
        
        ?>
        <div class="aqualuxe-performance-stats">
            <h3><?php esc_html_e( 'Database Statistics', 'aqualuxe' ); ?></h3>
            <ul>
                <li><?php printf( __( 'Database Size: %s', 'aqualuxe' ), $db_size ); ?></li>
                <li><?php printf( __( 'Transients: %s', 'aqualuxe' ), $transient_count ); ?></li>
                <li><?php printf( __( 'Autoloaded Data: %s', 'aqualuxe' ), $autoloaded_size ); ?></li>
            </ul>
            
            <h3><?php esc_html_e( 'Content Statistics', 'aqualuxe' ); ?></h3>
            <ul>
                <li><?php printf( __( 'Total Posts: %s', 'aqualuxe' ), $post_count_total ); ?></li>
                <li><?php printf( __( 'Revisions: %s', 'aqualuxe' ), $revision_count ); ?></li>
                <li><?php printf( __( 'Attachments: %s', 'aqualuxe' ), $attachment_count ); ?></li>
                <li><?php printf( __( 'Upload Directory Size: %s', 'aqualuxe' ), $attachment_size ); ?></li>
            </ul>
            
            <h3><?php esc_html_e( 'Optimization Actions', 'aqualuxe' ); ?></h3>
            <p>
                <a href="<?php echo esc_url( admin_url( 'admin.php?page=aqualuxe-performance&action=clear_transients' ) ); ?>" class="button button-secondary"><?php esc_html_e( 'Clear Transients', 'aqualuxe' ); ?></a>
                <a href="<?php echo esc_url( admin_url( 'admin.php?page=aqualuxe-performance&action=delete_revisions' ) ); ?>" class="button button-secondary"><?php esc_html_e( 'Delete Revisions', 'aqualuxe' ); ?></a>
            </p>
            
            <h3><?php esc_html_e( 'Performance Tips', 'aqualuxe' ); ?></h3>
            <ul>
                <li><?php esc_html_e( 'Use a caching plugin for better performance', 'aqualuxe' ); ?></li>
                <li><?php esc_html_e( 'Optimize images before uploading', 'aqualuxe' ); ?></li>
                <li><?php esc_html_e( 'Minimize the use of plugins', 'aqualuxe' ); ?></li>
                <li><?php esc_html_e( 'Use a CDN for static assets', 'aqualuxe' ); ?></li>
            </ul>
        </div>
        <?php
    }

    /**
     * Get directory size
     *
     * @param string $path Directory path.
     * @return int
     */
    private function get_directory_size( $path ) {
        $total_size = 0;
        $files = scandir( $path );
        $cleanPath = rtrim( $path, '/' ) . '/';
        
        foreach ( $files as $file ) {
            if ( '.' === $file || '..' === $file ) {
                continue;
            }
            
            $currentPath = $cleanPath . $file;
            
            if ( is_dir( $currentPath ) ) {
                $total_size += $this->get_directory_size( $currentPath );
            } else {
                $total_size += filesize( $currentPath );
            }
        }
        
        return $total_size;
    }

    /**
     * Setup page cache
     */
    public function setup_page_cache() {
        // Only cache for non-logged in users
        if ( is_user_logged_in() ) {
            return;
        }
        
        // Don't cache cart, checkout, or account pages
        if ( function_exists( 'is_cart' ) && ( is_cart() || is_checkout() || is_account_page() ) ) {
            return;
        }
        
        // Set cache headers
        $cache_time = 3600; // 1 hour
        
        header( 'Cache-Control: public, max-age=' . $cache_time );
        header( 'Expires: ' . gmdate( 'D, d M Y H:i:s', time() + $cache_time ) . ' GMT' );
    }

    /**
     * Optimize HTTP headers
     */
    public function optimize_http_headers() {
        // Add security headers
        header( 'X-Content-Type-Options: nosniff' );
        header( 'X-XSS-Protection: 1; mode=block' );
        header( 'Referrer-Policy: strict-origin-when-cross-origin' );
        
        // Add performance headers
        header( 'Connection: keep-alive' );
    }

    /**
     * Disable emoji
     */
    private function disable_emoji() {
        remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
        remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
        remove_action( 'wp_print_styles', 'print_emoji_styles' );
        remove_action( 'admin_print_styles', 'print_emoji_styles' );
        remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
        remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
        remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
        
        add_filter( 'tiny_mce_plugins', array( $this, 'disable_emoji_tinymce' ) );
        add_filter( 'wp_resource_hints', array( $this, 'disable_emoji_dns_prefetch' ), 10, 2 );
    }

    /**
     * Disable emoji in TinyMCE
     *
     * @param array $plugins TinyMCE plugins.
     * @return array
     */
    public function disable_emoji_tinymce( $plugins ) {
        if ( is_array( $plugins ) ) {
            return array_diff( $plugins, array( 'wpemoji' ) );
        }
        
        return array();
    }

    /**
     * Disable emoji DNS prefetch
     *
     * @param array  $urls URLs to print for resource hints.
     * @param string $relation_type The relation type the URLs are printed for.
     * @return array
     */
    public function disable_emoji_dns_prefetch( $urls, $relation_type ) {
        if ( 'dns-prefetch' === $relation_type ) {
            $emoji_svg_url = apply_filters( 'emoji_svg_url', 'https://s.w.org/images/core/emoji/12.0.0-1/svg/' );
            $urls = array_diff( $urls, array( $emoji_svg_url ) );
        }
        
        return $urls;
    }

    /**
     * Disable embeds
     */
    private function disable_embeds() {
        global $wp;
        
        // Remove the embed query var.
        $wp->public_query_vars = array_diff( $wp->public_query_vars, array( 'embed' ) );
        
        // Remove the REST API endpoint.
        remove_action( 'rest_api_init', 'wp_oembed_register_route' );
        
        // Turn off oEmbed auto discovery.
        add_filter( 'embed_oembed_discover', '__return_false' );
        
        // Don't filter oEmbed results.
        remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );
        
        // Remove oEmbed discovery links.
        remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
        
        // Remove oEmbed-specific JavaScript from the front-end and back-end.
        remove_action( 'wp_head', 'wp_oembed_add_host_js' );
        
        // Remove all embeds rewrite rules.
        add_filter( 'rewrite_rules_array', array( $this, 'disable_embeds_rewrites' ) );
    }

    /**
     * Disable embeds rewrite rules
     *
     * @param array $rules WordPress rewrite rules.
     * @return array
     */
    public function disable_embeds_rewrites( $rules ) {
        foreach ( $rules as $rule => $rewrite ) {
            if ( false !== strpos( $rewrite, 'embed=true' ) ) {
                unset( $rules[ $rule ] );
            }
        }
        
        return $rules;
    }

    /**
     * Preload critical assets
     */
    public function preload_critical_assets() {
        // Preload fonts
        $fonts = array(
            get_template_directory_uri() . '/assets/dist/fonts/inter-var.woff2',
        );
        
        foreach ( $fonts as $font ) {
            echo '<link rel="preload" href="' . esc_url( $font ) . '" as="font" type="font/woff2" crossorigin>';
        }
        
        // Preload critical CSS
        echo '<link rel="preload" href="' . esc_url( get_template_directory_uri() . '/assets/dist/css/critical.css' ) . '" as="style">';
        
        // Preload logo
        $custom_logo_id = get_theme_mod( 'custom_logo' );
        if ( $custom_logo_id ) {
            $logo_url = wp_get_attachment_image_url( $custom_logo_id, 'full' );
            if ( $logo_url ) {
                echo '<link rel="preload" href="' . esc_url( $logo_url ) . '" as="image">';
            }
        }
    }

    /**
     * Defer JavaScript
     *
     * @param string $tag    The script tag.
     * @param string $handle The script handle.
     * @param string $src    The script source.
     * @return string
     */
    public function defer_js( $tag, $handle, $src ) {
        // Do not defer these scripts
        $no_defer = array(
            'jquery',
            'jquery-core',
            'jquery-migrate',
            'aqualuxe-critical',
        );
        
        if ( in_array( $handle, $no_defer, true ) ) {
            return $tag;
        }
        
        // Add defer attribute
        if ( false === strpos( $tag, 'defer' ) ) {
            $tag = str_replace( ' src', ' defer src', $tag );
        }
        
        return $tag;
    }

    /**
     * Add resource hints
     *
     * @param array  $urls URLs to print for resource hints.
     * @param string $relation_type The relation type the URLs are printed for.
     * @return array
     */
    public function resource_hints( $urls, $relation_type ) {
        if ( 'preconnect' === $relation_type ) {
            // Add Google Fonts domain
            $urls[] = array(
                'href' => 'https://fonts.gstatic.com',
                'crossorigin',
            );
            
            // Add CDN domain if using one
            if ( defined( 'AQUALUXE_CDN_URL' ) && AQUALUXE_CDN_URL ) {
                $cdn_url = parse_url( AQUALUXE_CDN_URL, PHP_URL_HOST );
                
                if ( $cdn_url ) {
                    $urls[] = array(
                        'href' => 'https://' . $cdn_url,
                        'crossorigin',
                    );
                }
            }
        }
        
        return $urls;
    }

    /**
     * WooCommerce specific optimizations
     */
    private function woocommerce_optimizations() {
        // Disable WooCommerce scripts and styles on non-WooCommerce pages
        add_action( 'wp_enqueue_scripts', array( $this, 'woocommerce_script_optimization' ), 99 );
        
        // Disable cart fragments on homepage and non-cart/checkout pages
        if ( get_theme_mod( 'aqualuxe_disable_cart_fragments', false ) ) {
            add_action( 'wp_enqueue_scripts', array( $this, 'disable_cart_fragments' ), 999 );
        }
        
        // Optimize WooCommerce database queries
        add_filter( 'woocommerce_product_query_tax_query', array( $this, 'optimize_product_query' ) );
        
        // Optimize WooCommerce cart
        add_filter( 'woocommerce_cart_load_persistent_cart', array( $this, 'optimize_persistent_cart' ), 10, 2 );
    }

    /**
     * WooCommerce script optimization
     */
    public function woocommerce_script_optimization() {
        if ( ! is_woocommerce() && ! is_cart() && ! is_checkout() && ! is_account_page() ) {
            // Dequeue WooCommerce scripts
            wp_dequeue_script( 'wc-cart-fragments' );
            wp_dequeue_script( 'woocommerce' );
            wp_dequeue_script( 'wc-add-to-cart' );
            wp_dequeue_script( 'wc-add-to-cart-variation' );
            wp_dequeue_script( 'wc-cart' );
            wp_dequeue_script( 'wc-checkout' );
            wp_dequeue_script( 'wc-single-product' );
            wp_dequeue_script( 'wc-credit-card-form' );
            wp_dequeue_script( 'wc-lost-password' );
            wp_dequeue_script( 'wc-password-strength-meter' );
            
            // Dequeue WooCommerce styles
            wp_dequeue_style( 'woocommerce-general' );
            wp_dequeue_style( 'woocommerce-layout' );
            wp_dequeue_style( 'woocommerce-smallscreen' );
        }
    }

    /**
     * Disable cart fragments
     */
    public function disable_cart_fragments() {
        if ( is_front_page() || ( ! is_cart() && ! is_checkout() ) ) {
            wp_dequeue_script( 'wc-cart-fragments' );
        }
    }

    /**
     * Optimize product query
     *
     * @param array $tax_query Tax query.
     * @return array
     */
    public function optimize_product_query( $tax_query ) {
        // Add index hint to tax query
        if ( is_array( $tax_query ) ) {
            $tax_query['index_hint'] = 'USE INDEX (term_id)';
        }
        
        return $tax_query;
    }

    /**
     * Optimize persistent cart
     *
     * @param bool $load Whether to load the persistent cart.
     * @param int  $user_id User ID.
     * @return bool
     */
    public function optimize_persistent_cart( $load, $user_id ) {
        // Only load persistent cart on cart and checkout pages
        if ( ! is_cart() && ! is_checkout() ) {
            return false;
        }
        
        return $load;
    }
}

// Initialize performance optimizations
AquaLuxe_Performance::get_instance();