<?php
/**
 * AquaLuxe Performance Optimization Functions
 *
 * Functions for optimizing theme performance
 *
 * @package AquaLuxe
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Enqueue scripts and styles with proper dependencies and versioning
 */
function aqualuxe_enqueue_optimized_assets() {
    // Theme version for cache busting
    $theme_version = wp_get_theme()->get( 'Version' );
    
    // Main CSS file
    wp_enqueue_style(
        'aqualuxe-style',
        get_template_directory_uri() . '/assets/dist/css/app.css',
        array(),
        $theme_version
    );
    
    // Dark mode CSS (conditionally loaded)
    if ( get_theme_mod( 'aqualuxe_enable_dark_mode', true ) ) {
        wp_enqueue_style(
            'aqualuxe-dark-mode',
            get_template_directory_uri() . '/assets/dist/css/dark-mode.css',
            array( 'aqualuxe-style' ),
            $theme_version
        );
    }
    
    // WooCommerce styles (only if WooCommerce is active)
    if ( class_exists( 'WooCommerce' ) ) {
        wp_enqueue_style(
            'aqualuxe-woocommerce',
            get_template_directory_uri() . '/assets/dist/css/woocommerce.css',
            array( 'aqualuxe-style' ),
            $theme_version
        );
    }
    
    // Main JavaScript file with defer attribute
    wp_enqueue_script(
        'aqualuxe-scripts',
        get_template_directory_uri() . '/assets/dist/js/app.js',
        array( 'jquery' ),
        $theme_version,
        true
    );
    
    // Add defer attribute to script
    add_filter( 'script_loader_tag', function( $tag, $handle, $src ) {
        if ( 'aqualuxe-scripts' === $handle ) {
            return str_replace( ' src', ' defer src', $tag );
        }
        return $tag;
    }, 10, 3 );
    
    // Conditionally load polyfills for older browsers
    wp_script_add_data( 'aqualuxe-scripts', 'conditional', 'lt IE 11' );
    
    // Localize script with theme settings
    wp_localize_script( 'aqualuxe-scripts', 'aqualuxeSettings', array(
        'ajaxUrl' => admin_url( 'admin-ajax.php' ),
        'themeUrl' => get_template_directory_uri(),
        'darkModeEnabled' => get_theme_mod( 'aqualuxe_enable_dark_mode', true ),
        'isRtl' => is_rtl(),
    ) );
    
    // Remove unnecessary scripts
    wp_dequeue_style( 'wp-block-library' );
    wp_dequeue_style( 'wp-block-library-theme' );
    
    // Only load comment-reply script on single posts with comments open
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}
add_action( 'wp_enqueue_scripts', 'aqualuxe_enqueue_optimized_assets' );

/**
 * Remove unnecessary meta tags from wp_head
 */
function aqualuxe_remove_unnecessary_head_tags() {
    // Remove WordPress version
    remove_action( 'wp_head', 'wp_generator' );
    
    // Remove wlwmanifest link
    remove_action( 'wp_head', 'wlwmanifest_link' );
    
    // Remove RSD link
    remove_action( 'wp_head', 'rsd_link' );
    
    // Remove shortlink
    remove_action( 'wp_head', 'wp_shortlink_wp_head' );
    
    // Remove feed links
    remove_action( 'wp_head', 'feed_links_extra', 3 );
    
    // Remove REST API link
    remove_action( 'wp_head', 'rest_output_link_wp_head' );
    
    // Remove oEmbed links
    remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
    remove_action( 'wp_head', 'wp_oembed_add_host_js' );
    
    // Remove emoji scripts
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' );
    remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
    remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
    remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
    
    // Remove jQuery migrate
    add_filter( 'wp_default_scripts', function( $scripts ) {
        if ( ! is_admin() && isset( $scripts->registered['jquery'] ) ) {
            $script = $scripts->registered['jquery'];
            
            if ( $script->deps ) {
                $script->deps = array_diff( $script->deps, array( 'jquery-migrate' ) );
            }
        }
    } );
}
add_action( 'init', 'aqualuxe_remove_unnecessary_head_tags' );

/**
 * Add preload for critical assets
 */
function aqualuxe_preload_assets() {
    // Preload main CSS file
    echo '<link rel="preload" href="' . esc_url( get_template_directory_uri() . '/assets/dist/css/app.css' ) . '" as="style">';
    
    // Preload main font files
    echo '<link rel="preload" href="' . esc_url( get_template_directory_uri() . '/assets/dist/fonts/primary-font.woff2' ) . '" as="font" type="font/woff2" crossorigin>';
    
    // Preload logo
    if ( has_custom_logo() ) {
        $custom_logo_id = get_theme_mod( 'custom_logo' );
        $logo_url = wp_get_attachment_image_url( $custom_logo_id, 'full' );
        if ( $logo_url ) {
            echo '<link rel="preload" href="' . esc_url( $logo_url ) . '" as="image">';
        }
    }
}
add_action( 'wp_head', 'aqualuxe_preload_assets', 1 );

/**
 * Add resource hints for faster loading
 */
function aqualuxe_resource_hints( $urls, $relation_type ) {
    if ( 'dns-prefetch' === $relation_type ) {
        // Add Google Fonts domain
        $urls[] = '//fonts.googleapis.com';
        $urls[] = '//fonts.gstatic.com';
        
        // Add Google Maps domain if using maps
        if ( get_theme_mod( 'aqualuxe_google_maps_api_key', '' ) ) {
            $urls[] = '//maps.googleapis.com';
        }
        
        // Add analytics domains
        $urls[] = '//www.google-analytics.com';
        $urls[] = '//stats.g.doubleclick.net';
    }
    
    if ( 'preconnect' === $relation_type ) {
        // Preconnect to Google Fonts
        $urls[] = array(
            'href' => 'https://fonts.googleapis.com',
            'crossorigin',
        );
        $urls[] = array(
            'href' => 'https://fonts.gstatic.com',
            'crossorigin',
        );
    }
    
    return $urls;
}
add_filter( 'wp_resource_hints', 'aqualuxe_resource_hints', 10, 2 );

/**
 * Optimize images with responsive srcset
 */
function aqualuxe_optimize_images() {
    // Add theme support for post thumbnails
    add_theme_support( 'post-thumbnails' );
    
    // Add image sizes optimized for different devices
    add_image_size( 'aqualuxe-small', 400, 300, true );
    add_image_size( 'aqualuxe-medium', 800, 600, true );
    add_image_size( 'aqualuxe-large', 1200, 900, true );
    add_image_size( 'aqualuxe-xlarge', 1600, 1200, true );
    
    // Add image sizes for product thumbnails
    if ( class_exists( 'WooCommerce' ) ) {
        add_image_size( 'aqualuxe-product-thumbnail', 300, 300, true );
        add_image_size( 'aqualuxe-product-gallery', 600, 600, true );
    }
    
    // Add image sizes for blog featured images
    add_image_size( 'aqualuxe-blog-thumbnail', 400, 250, true );
    add_image_size( 'aqualuxe-blog-featured', 1200, 600, true );
    
    // Enable responsive images
    add_theme_support( 'responsive-embeds' );
    
    // Add srcset and sizes attributes to images
    add_filter( 'wp_calculate_image_srcset', 'aqualuxe_calculate_image_srcset', 10, 5 );
    add_filter( 'wp_calculate_image_sizes', 'aqualuxe_calculate_image_sizes', 10, 5 );
}
add_action( 'after_setup_theme', 'aqualuxe_optimize_images' );

/**
 * Calculate image srcset
 */
function aqualuxe_calculate_image_srcset( $sources, $size_array, $image_src, $image_meta, $attachment_id ) {
    // Customize srcset based on image context
    return $sources;
}

/**
 * Calculate image sizes
 */
function aqualuxe_calculate_image_sizes( $sizes, $size, $image_src, $image_meta, $attachment_id ) {
    // Customize sizes based on image context
    if ( 'aqualuxe-blog-featured' === $size ) {
        $sizes = '(max-width: 600px) 100vw, (max-width: 1200px) 1200px, 1200px';
    } elseif ( 'aqualuxe-product-gallery' === $size ) {
        $sizes = '(max-width: 600px) 100vw, 600px';
    }
    
    return $sizes;
}

/**
 * Lazy load images
 */
function aqualuxe_lazy_load_images( $content ) {
    if ( is_admin() || is_feed() ) {
        return $content;
    }
    
    // Replace src with data-src and add loading="lazy"
    $content = preg_replace( '/<img(.*?)src=[\'"](.*?)[\'"](.*)>/i', '<img$1src="data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 1 1\'%3E%3C/svg%3E" data-src="$2" loading="lazy"$3>', $content );
    
    // Add lazy load script
    add_action( 'wp_footer', 'aqualuxe_lazy_load_script' );
    
    return $content;
}
add_filter( 'the_content', 'aqualuxe_lazy_load_images', 99 );
add_filter( 'post_thumbnail_html', 'aqualuxe_lazy_load_images', 99 );
add_filter( 'woocommerce_product_get_image', 'aqualuxe_lazy_load_images', 99 );

/**
 * Add lazy load script
 */
function aqualuxe_lazy_load_script() {
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var lazyImages = [].slice.call(document.querySelectorAll('img[data-src]'));
        
        if ('IntersectionObserver' in window) {
            let lazyImageObserver = new IntersectionObserver(function(entries, observer) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        let lazyImage = entry.target;
                        lazyImage.src = lazyImage.dataset.src;
                        lazyImage.removeAttribute('data-src');
                        lazyImageObserver.unobserve(lazyImage);
                    }
                });
            });
            
            lazyImages.forEach(function(lazyImage) {
                lazyImageObserver.observe(lazyImage);
            });
        } else {
            // Fallback for browsers without IntersectionObserver support
            let active = false;
            
            const lazyLoad = function() {
                if (active === false) {
                    active = true;
                    
                    setTimeout(function() {
                        lazyImages.forEach(function(lazyImage) {
                            if ((lazyImage.getBoundingClientRect().top <= window.innerHeight && lazyImage.getBoundingClientRect().bottom >= 0) && getComputedStyle(lazyImage).display !== 'none') {
                                lazyImage.src = lazyImage.dataset.src;
                                lazyImage.removeAttribute('data-src');
                                
                                lazyImages = lazyImages.filter(function(image) {
                                    return image !== lazyImage;
                                });
                                
                                if (lazyImages.length === 0) {
                                    document.removeEventListener('scroll', lazyLoad);
                                    window.removeEventListener('resize', lazyLoad);
                                    window.removeEventListener('orientationchange', lazyLoad);
                                }
                            }
                        });
                        
                        active = false;
                    }, 200);
                }
            };
            
            document.addEventListener('scroll', lazyLoad);
            window.addEventListener('resize', lazyLoad);
            window.addEventListener('orientationchange', lazyLoad);
            lazyLoad();
        }
    });
    </script>
    <?php
}

/**
 * Add browser caching headers
 */
function aqualuxe_browser_caching_headers() {
    // Check if the server is Apache
    if ( function_exists( 'apache_get_modules' ) && in_array( 'mod_expires', apache_get_modules() ) ) {
        // Create .htaccess file with caching rules
        $htaccess_file = ABSPATH . '.htaccess';
        
        if ( is_writable( $htaccess_file ) ) {
            $htaccess_content = file_get_contents( $htaccess_file );
            
            // Check if caching rules already exist
            if ( strpos( $htaccess_content, '# AquaLuxe Browser Caching' ) === false ) {
                $caching_rules = "
# AquaLuxe Browser Caching
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType image/jpg &quot;access plus 1 year&quot;
    ExpiresByType image/jpeg &quot;access plus 1 year&quot;
    ExpiresByType image/gif &quot;access plus 1 year&quot;
    ExpiresByType image/png &quot;access plus 1 year&quot;
    ExpiresByType image/webp &quot;access plus 1 year&quot;
    ExpiresByType image/svg+xml &quot;access plus 1 year&quot;
    ExpiresByType image/x-icon &quot;access plus 1 year&quot;
    ExpiresByType video/mp4 &quot;access plus 1 year&quot;
    ExpiresByType video/webm &quot;access plus 1 year&quot;
    ExpiresByType text/css &quot;access plus 1 month&quot;
    ExpiresByType text/javascript &quot;access plus 1 month&quot;
    ExpiresByType application/javascript &quot;access plus 1 month&quot;
    ExpiresByType application/x-javascript &quot;access plus 1 month&quot;
    ExpiresByType application/pdf &quot;access plus 1 month&quot;
    ExpiresByType application/x-font-woff &quot;access plus 1 year&quot;
    ExpiresByType application/font-woff2 &quot;access plus 1 year&quot;
    ExpiresByType font/woff &quot;access plus 1 year&quot;
    ExpiresByType font/woff2 &quot;access plus 1 year&quot;
</IfModule>

<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/plain
    AddOutputFilterByType DEFLATE text/html
    AddOutputFilterByType DEFLATE text/xml
    AddOutputFilterByType DEFLATE text/css
    AddOutputFilterByType DEFLATE text/javascript
    AddOutputFilterByType DEFLATE application/xml
    AddOutputFilterByType DEFLATE application/xhtml+xml
    AddOutputFilterByType DEFLATE application/rss+xml
    AddOutputFilterByType DEFLATE application/javascript
    AddOutputFilterByType DEFLATE application/x-javascript
    AddOutputFilterByType DEFLATE application/x-font-ttf
    AddOutputFilterByType DEFLATE application/vnd.ms-fontobject
    AddOutputFilterByType DEFLATE font/opentype font/ttf font/eot font/otf
</IfModule>
# End AquaLuxe Browser Caching
";
                
                // Add caching rules to .htaccess
                file_put_contents( $htaccess_file, $htaccess_content . $caching_rules );
            }
        }
    }
}
add_action( 'admin_init', 'aqualuxe_browser_caching_headers' );

/**
 * Minify HTML output
 */
function aqualuxe_minify_html( $buffer ) {
    // Skip minification in admin or if the page is not HTML
    if ( is_admin() || strpos( $buffer, '<html' ) === false ) {
        return $buffer;
    }
    
    // Skip minification if the page contains <pre> or <textarea> tags
    if ( strpos( $buffer, '<pre' ) !== false || strpos( $buffer, '<textarea' ) !== false ) {
        return $buffer;
    }
    
    // Remove comments (except IE conditional comments)
    $buffer = preg_replace( '/<!--(?!\s*(?:\[if [^\]]+]|<!|>))(?:(?!-->).)*-->/s', '', $buffer );
    
    // Remove whitespace
    $buffer = preg_replace( '/\s+/', ' ', $buffer );
    $buffer = preg_replace( '/\s*(<\/?(div|p|br|tr|td|th|table|thead|tbody|tfoot|h[1-6]|li|ul|ol|option|form|input|select|textarea|label|fieldset|legend|button|a)[^>]*>)\s*/i', '$1', $buffer );
    $buffer = preg_replace( '/\s*>\s*<\s*/', '><', $buffer );
    
    return $buffer;
}

/**
 * Enable HTML minification
 */
function aqualuxe_enable_html_minification() {
    // Only minify HTML if the option is enabled
    if ( get_theme_mod( 'aqualuxe_minify_html', false ) ) {
        ob_start( 'aqualuxe_minify_html' );
    }
}
add_action( 'template_redirect', 'aqualuxe_enable_html_minification', 1 );

/**
 * Add customizer options for performance
 */
function aqualuxe_performance_customizer_options( $wp_customize ) {
    // Add performance section
    $wp_customize->add_section( 'aqualuxe_performance', array(
        'title'    => __( 'Performance', 'aqualuxe' ),
        'priority' => 120,
    ) );
    
    // Minify HTML
    $wp_customize->add_setting( 'aqualuxe_minify_html', array(
        'default'           => false,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_minify_html', array(
        'label'    => __( 'Minify HTML', 'aqualuxe' ),
        'description' => __( 'Removes whitespace and comments from HTML output', 'aqualuxe' ),
        'section'  => 'aqualuxe_performance',
        'type'     => 'checkbox',
    ) );
    
    // Lazy load images
    $wp_customize->add_setting( 'aqualuxe_lazy_load_images', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_lazy_load_images', array(
        'label'    => __( 'Lazy Load Images', 'aqualuxe' ),
        'description' => __( 'Loads images only when they enter the viewport', 'aqualuxe' ),
        'section'  => 'aqualuxe_performance',
        'type'     => 'checkbox',
    ) );
    
    // Disable emoji scripts
    $wp_customize->add_setting( 'aqualuxe_disable_emojis', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_disable_emojis', array(
        'label'    => __( 'Disable Emoji Scripts', 'aqualuxe' ),
        'description' => __( 'Removes emoji scripts and styles from WordPress', 'aqualuxe' ),
        'section'  => 'aqualuxe_performance',
        'type'     => 'checkbox',
    ) );
    
    // Disable jQuery Migrate
    $wp_customize->add_setting( 'aqualuxe_disable_jquery_migrate', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_disable_jquery_migrate', array(
        'label'    => __( 'Disable jQuery Migrate', 'aqualuxe' ),
        'description' => __( 'Removes jQuery Migrate script from WordPress', 'aqualuxe' ),
        'section'  => 'aqualuxe_performance',
        'type'     => 'checkbox',
    ) );
    
    // Preload critical assets
    $wp_customize->add_setting( 'aqualuxe_preload_assets', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_preload_assets', array(
        'label'    => __( 'Preload Critical Assets', 'aqualuxe' ),
        'description' => __( 'Preloads critical CSS, fonts, and images', 'aqualuxe' ),
        'section'  => 'aqualuxe_performance',
        'type'     => 'checkbox',
    ) );
}
add_action( 'customize_register', 'aqualuxe_performance_customizer_options' );

/**
 * Sanitize checkbox
 */
function aqualuxe_sanitize_checkbox( $input ) {
    return ( isset( $input ) && true == $input ) ? true : false;
}

/**
 * Optimize WooCommerce if active
 */
function aqualuxe_optimize_woocommerce() {
    // Only run if WooCommerce is active
    if ( ! class_exists( 'WooCommerce' ) ) {
        return;
    }
    
    // Disable WooCommerce scripts and styles on non-WooCommerce pages
    add_action( 'wp_enqueue_scripts', function() {
        if ( ! is_woocommerce() && ! is_cart() && ! is_checkout() && ! is_account_page() ) {
            // Dequeue WooCommerce styles
            wp_dequeue_style( 'woocommerce-general' );
            wp_dequeue_style( 'woocommerce-layout' );
            wp_dequeue_style( 'woocommerce-smallscreen' );
            wp_dequeue_style( 'woocommerce_frontend_styles' );
            wp_dequeue_style( 'woocommerce_fancybox_styles' );
            wp_dequeue_style( 'woocommerce_chosen_styles' );
            wp_dequeue_style( 'woocommerce_prettyPhoto_css' );
            
            // Dequeue WooCommerce scripts
            wp_dequeue_script( 'wc-add-to-cart' );
            wp_dequeue_script( 'wc-cart-fragments' );
            wp_dequeue_script( 'woocommerce' );
            wp_dequeue_script( 'wc-checkout' );
            wp_dequeue_script( 'wc-add-to-cart-variation' );
            wp_dequeue_script( 'wc-single-product' );
            wp_dequeue_script( 'wc-cart' );
            wp_dequeue_script( 'wc-chosen' );
            wp_dequeue_script( 'prettyPhoto' );
            wp_dequeue_script( 'prettyPhoto-init' );
            wp_dequeue_script( 'jquery-blockui' );
            wp_dequeue_script( 'jquery-placeholder' );
            wp_dequeue_script( 'fancybox' );
            wp_dequeue_script( 'jqueryui' );
        }
    }, 99 );
    
    // Disable WooCommerce cart fragments on non-cart/checkout pages
    add_action( 'wp_enqueue_scripts', function() {
        if ( ! is_cart() && ! is_checkout() ) {
            wp_dequeue_script( 'wc-cart-fragments' );
        }
    }, 99 );
    
    // Optimize WooCommerce database queries
    add_filter( 'woocommerce_product_query_tax_query', function( $tax_query ) {
        // Add index hint to tax query
        $tax_query['index_hint'] = 'USE INDEX (term_id)';
        return $tax_query;
    } );
    
    // Disable WooCommerce status dashboard widget
    add_filter( 'woocommerce_dashboard_status_widget_sales_query', '__return_false' );
}
add_action( 'init', 'aqualuxe_optimize_woocommerce' );

/**
 * Add performance monitoring
 */
function aqualuxe_performance_monitoring() {
    // Only run for administrators
    if ( ! current_user_can( 'administrator' ) ) {
        return;
    }
    
    // Start timing
    $start_time = microtime( true );
    
    // Add timing to footer
    add_action( 'wp_footer', function() use ( $start_time ) {
        $end_time = microtime( true );
        $execution_time = round( ( $end_time - $start_time ) * 1000, 2 );
        
        echo '<!-- Page generated in ' . $execution_time . ' ms -->';
        
        // Get memory usage
        $memory_usage = round( memory_get_peak_usage() / 1024 / 1024, 2 );
        echo '<!-- Memory used: ' . $memory_usage . ' MB -->';
        
        // Get number of database queries
        global $wpdb;
        echo '<!-- Database queries: ' . $wpdb->num_queries . ' -->';
    }, 999 );
}
add_action( 'init', 'aqualuxe_performance_monitoring' );

/**
 * Add critical CSS inline
 */
function aqualuxe_add_critical_css() {
    // Only add critical CSS if the option is enabled
    if ( get_theme_mod( 'aqualuxe_critical_css', true ) ) {
        // Get critical CSS file
        $critical_css_file = get_template_directory() . '/assets/dist/css/critical.css';
        
        if ( file_exists( $critical_css_file ) ) {
            $critical_css = file_get_contents( $critical_css_file );
            
            if ( $critical_css ) {
                echo '<style id="aqualuxe-critical-css">' . $critical_css . '</style>';
            }
        }
    }
}
add_action( 'wp_head', 'aqualuxe_add_critical_css', 1 );

/**
 * Add defer attribute to non-critical scripts
 */
function aqualuxe_defer_scripts( $tag, $handle, $src ) {
    // List of scripts to defer
    $defer_scripts = array(
        'aqualuxe-scripts',
        'aqualuxe-slider',
        'aqualuxe-lightbox',
    );
    
    if ( in_array( $handle, $defer_scripts ) ) {
        return str_replace( ' src', ' defer src', $tag );
    }
    
    return $tag;
}
add_filter( 'script_loader_tag', 'aqualuxe_defer_scripts', 10, 3 );

/**
 * Add async attribute to analytics scripts
 */
function aqualuxe_async_scripts( $tag, $handle, $src ) {
    // List of scripts to async
    $async_scripts = array(
        'google-analytics',
        'facebook-pixel',
        'hotjar',
    );
    
    if ( in_array( $handle, $async_scripts ) ) {
        return str_replace( ' src', ' async src', $tag );
    }
    
    return $tag;
}
add_filter( 'script_loader_tag', 'aqualuxe_async_scripts', 10, 3 );

/**
 * Optimize database queries
 */
function aqualuxe_optimize_database_queries() {
    // Disable post revisions
    if ( ! defined( 'WP_POST_REVISIONS' ) ) {
        define( 'WP_POST_REVISIONS', 5 );
    }
    
    // Disable auto-save
    if ( ! defined( 'AUTOSAVE_INTERVAL' ) ) {
        define( 'AUTOSAVE_INTERVAL', 160 );
    }
    
    // Optimize get_posts queries
    add_filter( 'posts_where', function( $where ) {
        global $wpdb;
        
        if ( is_admin() ) {
            return $where;
        }
        
        if ( strpos( $where, $wpdb->posts . '.post_status = \'publish\'' ) !== false ) {
            $where = str_replace( $wpdb->posts . '.post_status = \'publish\'', $wpdb->posts . '.post_status = \'publish\' AND ' . $wpdb->posts . '.post_type != \'revision\'', $where );
        }
        
        return $where;
    } );
}
add_action( 'init', 'aqualuxe_optimize_database_queries' );