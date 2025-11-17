<?php
/**
 * AquaLuxe Performance Optimizations
 * 
 * @package AquaLuxe
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class AquaLuxe_Performance {
    
    /**
     * Initialize performance features
     */
    public static function init() {
        // Optimize images
        add_filter( 'wp_generate_attachment_metadata', array( __CLASS__, 'optimize_image_metadata' ), 10, 2 );
        
        // Lazy load images
        add_filter( 'wp_get_attachment_image_attributes', array( __CLASS__, 'add_lazy_load_to_images' ), 10, 3 );
        add_filter( 'the_content', array( __CLASS__, 'add_lazy_load_to_content_images' ) );
        
        // Defer non-critical JavaScript
        add_filter( 'script_loader_tag', array( __CLASS__, 'defer_scripts' ), 10, 2 );
        
        // Preload critical resources
        add_action( 'wp_head', array( __CLASS__, 'preload_critical_resources' ), 5 );
        
        // Optimize database queries
        add_action( 'wp_loaded', array( __CLASS__, 'optimize_database_queries' ) );
        
        // Enable compression
        add_action( 'init', array( __CLASS__, 'enable_compression' ) );
        
        // Optimize WooCommerce performance
        add_action( 'init', array( __CLASS__, 'optimize_woocommerce' ) );
        
        // Remove unnecessary WordPress features
        add_action( 'init', array( __CLASS__, 'remove_unnecessary_features' ) );
        
        // Optimize emojis
        add_action( 'init', array( __CLASS__, 'disable_emojis' ) );
        
        // Remove query strings from static resources
        add_filter( 'script_loader_src', array( __CLASS__, 'remove_query_strings' ), 15, 1 );
        add_filter( 'style_loader_src', array( __CLASS__, 'remove_query_strings' ), 15, 1 );
        
        // Minify HTML output
        add_action( 'get_header', array( __CLASS__, 'start_html_minification' ) );
        add_action( 'wp_footer', array( __CLASS__, 'end_html_minification' ) );
    }
    
    /**
     * Optimize image metadata
     */
    public static function optimize_image_metadata( $metadata, $attachment_id ) {
        // Generate WebP versions of images
        if ( function_exists( 'imagewebp' ) ) {
            $file_path = get_attached_file( $attachment_id );
            $file_info = pathinfo( $file_path );
            
            // Only process JPEG and PNG files
            if ( in_array( $file_info['extension'], array( 'jpg', 'jpeg', 'png' ) ) ) {
                $webp_path = $file_info['dirname'] . '/' . $file_info['filename'] . '.webp';
                
                // Create WebP image
                if ( $file_info['extension'] === 'png' ) {
                    $image = imagecreatefrompng( $file_path );
                } else {
                    $image = imagecreatefromjpeg( $file_path );
                }
                
                if ( $image ) {
                    imagewebp( $image, $webp_path, 80 );
                    imagedestroy( $image );
                }
            }
        }
        
        return $metadata;
    }
    
    /**
     * Add lazy load to images
     */
    public static function add_lazy_load_to_images( $attributes, $attachment, $size ) {
        // Only apply to frontend
        if ( is_admin() ) {
            return $attributes;
        }
        
        // Add loading attribute
        $attributes['loading'] = 'lazy';
        
        // Add native lazy loading
        $attributes['data-src'] = $attributes['src'];
        unset( $attributes['src'] );
        
        return $attributes;
    }
    
    /**
     * Add lazy load to content images
     */
    public static function add_lazy_load_to_content_images( $content ) {
        // Only apply to frontend
        if ( is_admin() ) {
            return $content;
        }
        
        // Replace img tags with lazy loading attributes
        return preg_replace( '/<img(.*?)(src=)(.*?)(\s*\/?>)/i', '<img$1data-src=$3 loading="lazy"$4', $content );
    }
    
    /**
     * Defer non-critical scripts
     */
    public static function defer_scripts( $tag, $handle ) {
        // List of scripts to defer
        $defer_scripts = array(
            'aqualuxe-theme',
            'aqualuxe-woocommerce',
            'comment-reply',
        );
        
        // Defer scripts
        if ( in_array( $handle, $defer_scripts ) ) {
            return str_replace( ' src', ' defer src', $tag );
        }
        
        return $tag;
    }
    
    /**
     * Preload critical resources
     */
    public static function preload_critical_resources() {
        // Preload logo
        $custom_logo_id = get_theme_mod( 'custom_logo' );
        if ( $custom_logo_id ) {
            $logo_url = wp_get_attachment_image_url( $custom_logo_id, 'full' );
            echo '<link rel="preload" href="' . esc_url( $logo_url ) . '" as="image">' . "\n";
        }
        
        // Preload primary font
        echo '<link rel="preload" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" as="style" onload="this.onload=null;this.rel=\'stylesheet\'">' . "\n";
        
        // Preload secondary font
        echo '<link rel="preload" href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&display=swap" as="style" onload="this.onload=null;this.rel=\'stylesheet\'">' . "\n";
        
        // Preload critical CSS
        echo '<link rel="preload" href="' . get_stylesheet_uri() . '" as="style">' . "\n";
    }
    
    /**
     * Optimize database queries
     */
    public static function optimize_database_queries() {
        // Increase WordPress memory limit
        if ( ! defined( 'WP_MEMORY_LIMIT' ) ) {
            define( 'WP_MEMORY_LIMIT', '256M' );
        }
        
        // Increase maximum memory limit
        if ( ! defined( 'WP_MAX_MEMORY_LIMIT' ) ) {
            define( 'WP_MAX_MEMORY_LIMIT', '512M' );
        }
        
        // Disable post revisions (optional)
        if ( ! defined( 'WP_POST_REVISIONS' ) ) {
            define( 'WP_POST_REVISIONS', 3 ); // Keep only 3 revisions
        }
        
        // Set autosave interval
        if ( ! defined( 'AUTOSAVE_INTERVAL' ) ) {
            define( 'AUTOSAVE_INTERVAL', 120 ); // 2 minutes
        }
    }
    
    /**
     * Enable compression
     */
    public static function enable_compression() {
        // Enable compression if not already enabled
        if ( ! is_admin() && ! headers_sent() ) {
            if ( ! ob_start( 'ob_gzhandler' ) ) {
                ob_start();
            }
        }
    }
    
    /**
     * Optimize WooCommerce performance
     */
    public static function optimize_woocommerce() {
        if ( ! class_exists( 'WooCommerce' ) ) {
            return;
        }
        
        // Reduce product query results
        add_filter( 'loop_shop_per_page', function() {
            return 12; // Display 12 products per page
        }, 20 );
        
        // Optimize cart fragments
        add_filter( 'woocommerce_cart_fragment_name', function( $name ) {
            return 'wc_fragments_' . md5( home_url() );
        } );
        
        // Disable WooCommerce styles
        add_filter( 'woocommerce_enqueue_styles', '__return_false' );
        
        // Optimize product gallery
        add_filter( 'woocommerce_product_thumbnails_columns', function() {
            return 4; // Display 4 thumbnails
        } );
    }
    
    /**
     * Remove unnecessary WordPress features
     */
    public static function remove_unnecessary_features() {
        // Remove global styles
        remove_action( 'wp_enqueue_scripts', 'wp_enqueue_global_styles' );
        remove_action( 'wp_body_open', 'wp_global_styles_render_svg_filters' );
        
        // Remove generator tag
        remove_action( 'wp_head', 'wp_generator' );
        
        // Remove RSD link
        remove_action( 'wp_head', 'rsd_link' );
        
        // Remove WLW manifest
        remove_action( 'wp_head', 'wlwmanifest_link' );
        
        // Remove shortlink
        remove_action( 'wp_head', 'wp_shortlink_wp_head' );
        
        // Remove adjacent posts links
        remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head' );
    }
    
    /**
     * Disable emojis
     */
    public static function disable_emojis() {
        remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
        remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
        remove_action( 'wp_print_styles', 'print_emoji_styles' );
        remove_action( 'admin_print_styles', 'print_emoji_styles' );
        remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
        remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
        remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
        
        add_filter( 'tiny_mce_plugins', array( __CLASS__, 'disable_emojis_tinymce' ) );
        add_filter( 'wp_resource_hints', array( __CLASS__, 'disable_emojis_remove_dns_prefetch' ), 10, 2 );
    }
    
    /**
     * Disable emojis in TinyMCE
     */
    public static function disable_emojis_tinymce( $plugins ) {
        if ( is_array( $plugins ) ) {
            return array_diff( $plugins, array( 'wpemoji' ) );
        } else {
            return array();
        }
    }
    
    /**
     * Remove emojis DNS prefetch
     */
    public static function disable_emojis_remove_dns_prefetch( $urls, $relation_type ) {
        if ( 'dns-prefetch' == $relation_type ) {
            $emoji_svg_url = apply_filters( 'emoji_svg_url', 'https://s.w.org/images/core/emoji/2/svg/' );
            $urls = array_diff( $urls, array( $emoji_svg_url ) );
        }
        
        return $urls;
    }
    
    /**
     * Remove query strings from static resources
     */
    public static function remove_query_strings( $src ) {
        if ( strpos( $src, '?ver=' ) ) {
            $src = remove_query_arg( 'ver', $src );
        }
        return $src;
    }
    
    /**
     * Start HTML minification
     */
    public static function start_html_minification() {
        if ( ! is_admin() ) {
            ob_start( array( __CLASS__, 'minify_html' ) );
        }
    }
    
    /**
     * End HTML minification
     */
    public static function end_html_minification() {
        if ( ! is_admin() ) {
            ob_end_flush();
        }
    }
    
    /**
     * Minify HTML output
     */
    public static function minify_html( $html ) {
        // Only minify on frontend
        if ( is_admin() ) {
            return $html;
        }
        
        // Remove HTML comments
        $html = preg_replace( '/<!--(.|s)*?-->/', '', $html );
        
        // Remove whitespace
        $html = preg_replace( '/s+/', ' ', $html );
        
        // Remove spaces around tags
        $html = preg_replace( '/>s+</', '><', $html );
        
        return $html;
    }
}

// Initialize
AquaLuxe_Performance::init();