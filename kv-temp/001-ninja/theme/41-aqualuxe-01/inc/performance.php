<?php
/**
 * Performance Optimization Functions
 *
 * Functions for optimizing theme performance including asset minification,
 * cache busting, lazy loading, and critical CSS.
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Class AquaLuxe_Performance
 */
class AquaLuxe_Performance {

    /**
     * Constructor
     */
    public function __construct() {
        // Add version to stylesheets and scripts for cache busting
        add_filter( 'style_loader_src', array( $this, 'add_cache_busting' ), 10, 2 );
        add_filter( 'script_loader_src', array( $this, 'add_cache_busting' ), 10, 2 );
        
        // Add async/defer attributes to scripts
        add_filter( 'script_loader_tag', array( $this, 'add_async_defer_attributes' ), 10, 3 );
        
        // Add preload for critical assets
        add_action( 'wp_head', array( $this, 'add_preload_tags' ), 1 );
        
        // Add lazy loading for images and iframes
        add_filter( 'the_content', array( $this, 'add_lazy_loading' ) );
        add_filter( 'post_thumbnail_html', array( $this, 'add_lazy_loading_for_thumbnails' ) );
        add_filter( 'woocommerce_product_get_image', array( $this, 'add_lazy_loading_for_thumbnails' ) );
        
        // Add critical CSS
        add_action( 'wp_head', array( $this, 'add_critical_css' ), 1 );
        
        // Disable emoji scripts
        add_action( 'init', array( $this, 'disable_emojis' ) );
        
        // Disable jQuery migrate
        add_action( 'wp_default_scripts', array( $this, 'remove_jquery_migrate' ) );
        
        // Optimize Google Fonts
        add_filter( 'wp_resource_hints', array( $this, 'resource_hints' ), 10, 2 );
        
        // Remove query strings from static resources
        add_filter( 'style_loader_src', array( $this, 'remove_query_strings' ), 15 );
        add_filter( 'script_loader_src', array( $this, 'remove_query_strings' ), 15 );
        
        // Optimize WooCommerce scripts
        add_action( 'wp_enqueue_scripts', array( $this, 'optimize_woocommerce_scripts' ), 99 );
    }

    /**
     * Add cache busting to stylesheets and scripts
     *
     * @param string $src    The source URL of the resource.
     * @param string $handle The resource handle.
     * @return string Modified source URL.
     */
    public function add_cache_busting( $src, $handle ) {
        if ( strpos( $handle, 'aqualuxe-' ) === 0 ) {
            // Only apply to theme assets
            $theme_version = wp_get_theme()->get( 'Version' );
            
            // Check if the file exists and get its modification time
            $file_path = str_replace( get_template_directory_uri(), get_template_directory(), $src );
            $file_path = preg_replace( '/\?.*/', '', $file_path );
            
            if ( file_exists( $file_path ) ) {
                $version = filemtime( $file_path );
            } else {
                $version = $theme_version;
            }
            
            // Remove existing version and add our version
            $src = remove_query_arg( 'ver', $src );
            $src = add_query_arg( 'ver', $version, $src );
        }
        
        return $src;
    }

    /**
     * Add async/defer attributes to scripts
     *
     * @param string $tag    The script tag.
     * @param string $handle The script handle.
     * @param string $src    The script source.
     * @return string Modified script tag.
     */
    public function add_async_defer_attributes( $tag, $handle, $src ) {
        // List of scripts to load asynchronously
        $async_scripts = array(
            'aqualuxe-darkmode',
            'aqualuxe-navigation',
        );
        
        // List of scripts to defer
        $defer_scripts = array(
            'aqualuxe-dropdown',
            'aqualuxe-modal',
            'aqualuxe-tabs',
            'aqualuxe-accordion',
        );
        
        if ( in_array( $handle, $async_scripts, true ) ) {
            return str_replace( ' src', ' async src', $tag );
        }
        
        if ( in_array( $handle, $defer_scripts, true ) ) {
            return str_replace( ' src', ' defer src', $tag );
        }
        
        return $tag;
    }

    /**
     * Add preload tags for critical assets
     */
    public function add_preload_tags() {
        // Preload fonts
        echo '<link rel="preload" href="' . esc_url( get_template_directory_uri() . '/assets/dist/fonts/inter-var.woff2' ) . '" as="font" type="font/woff2" crossorigin>' . "\n";
        
        // Preload critical CSS
        echo '<link rel="preload" href="' . esc_url( get_template_directory_uri() . '/assets/dist/css/critical.css' ) . '" as="style">' . "\n";
        
        // Preload logo
        $custom_logo_id = get_theme_mod( 'custom_logo' );
        if ( $custom_logo_id ) {
            $logo_url = wp_get_attachment_image_url( $custom_logo_id, 'full' );
            if ( $logo_url ) {
                echo '<link rel="preload" href="' . esc_url( $logo_url ) . '" as="image">' . "\n";
            }
        }
    }

    /**
     * Add lazy loading to images and iframes in content
     *
     * @param string $content The content.
     * @return string Modified content.
     */
    public function add_lazy_loading( $content ) {
        // Skip if content is empty
        if ( empty( $content ) ) {
            return $content;
        }
        
        // Don't lazy load in admin or feeds
        if ( is_admin() || is_feed() ) {
            return $content;
        }
        
        // Don't lazy load AMP pages
        if ( function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() ) {
            return $content;
        }
        
        // Add loading="lazy" to images
        $content = preg_replace( '/<img(.*?)>/i', '<img$1 loading="lazy">', $content );
        
        // Add loading="lazy" to iframes
        $content = preg_replace( '/<iframe(.*?)>/i', '<iframe$1 loading="lazy">', $content );
        
        return $content;
    }

    /**
     * Add lazy loading to post thumbnails
     *
     * @param string $html The thumbnail HTML.
     * @return string Modified HTML.
     */
    public function add_lazy_loading_for_thumbnails( $html ) {
        // Skip if HTML is empty
        if ( empty( $html ) ) {
            return $html;
        }
        
        // Don't lazy load in admin or feeds
        if ( is_admin() || is_feed() ) {
            return $html;
        }
        
        // Don't lazy load AMP pages
        if ( function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() ) {
            return $html;
        }
        
        // Add loading="lazy" to images
        $html = preg_replace( '/<img(.*?)>/i', '<img$1 loading="lazy">', $html );
        
        return $html;
    }

    /**
     * Add critical CSS
     */
    public function add_critical_css() {
        $critical_css_path = get_template_directory() . '/assets/dist/css/critical.css';
        
        if ( file_exists( $critical_css_path ) ) {
            echo '<style id="aqualuxe-critical-css">';
            // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
            echo file_get_contents( $critical_css_path );
            echo '</style>' . "\n";
        }
    }

    /**
     * Disable emoji scripts
     */
    public function disable_emojis() {
        remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
        remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
        remove_action( 'wp_print_styles', 'print_emoji_styles' );
        remove_action( 'admin_print_styles', 'print_emoji_styles' );
        remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
        remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
        remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
        
        // Remove TinyMCE emojis
        add_filter( 'tiny_mce_plugins', array( $this, 'disable_emojis_tinymce' ) );
        add_filter( 'wp_resource_hints', array( $this, 'disable_emojis_dns_prefetch' ), 10, 2 );
    }

    /**
     * Filter function to remove the emoji plugin from TinyMCE
     *
     * @param array $plugins TinyMCE plugins.
     * @return array Modified plugins.
     */
    public function disable_emojis_tinymce( $plugins ) {
        if ( is_array( $plugins ) ) {
            return array_diff( $plugins, array( 'wpemoji' ) );
        }
        
        return array();
    }

    /**
     * Remove emoji CDN hostname from DNS prefetching hints
     *
     * @param array  $urls          URLs to print for resource hints.
     * @param string $relation_type The relation type the URLs are printed for.
     * @return array Modified URLs.
     */
    public function disable_emojis_dns_prefetch( $urls, $relation_type ) {
        if ( 'dns-prefetch' === $relation_type ) {
            $emoji_svg_url = apply_filters( 'emoji_svg_url', 'https://s.w.org/images/core/emoji/12.0.0-1/svg/' );
            $urls = array_diff( $urls, array( $emoji_svg_url ) );
        }
        
        return $urls;
    }

    /**
     * Remove jQuery migrate
     *
     * @param WP_Scripts $scripts WP_Scripts object.
     */
    public function remove_jquery_migrate( $scripts ) {
        if ( ! is_admin() && isset( $scripts->registered['jquery'] ) ) {
            $script = $scripts->registered['jquery'];
            
            if ( $script->deps ) {
                $script->deps = array_diff( $script->deps, array( 'jquery-migrate' ) );
            }
        }
    }

    /**
     * Add preconnect for Google Fonts
     *
     * @param array  $urls          URLs to print for resource hints.
     * @param string $relation_type The relation type the URLs are printed for.
     * @return array Modified URLs.
     */
    public function resource_hints( $urls, $relation_type ) {
        if ( 'preconnect' === $relation_type ) {
            $urls[] = array(
                'href' => 'https://fonts.gstatic.com',
                'crossorigin',
            );
        }
        
        return $urls;
    }

    /**
     * Remove query strings from static resources
     *
     * @param string $src The source URL of the resource.
     * @return string Modified source URL.
     */
    public function remove_query_strings( $src ) {
        if ( strpos( $src, 'ver=' ) && ! strpos( $src, 'aqualuxe-' ) ) {
            $src = remove_query_arg( 'ver', $src );
        }
        
        return $src;
    }

    /**
     * Optimize WooCommerce scripts
     */
    public function optimize_woocommerce_scripts() {
        // Remove WooCommerce scripts and styles from non-WooCommerce pages
        if ( function_exists( 'is_woocommerce' ) && ! is_woocommerce() && ! is_cart() && ! is_checkout() && ! is_account_page() ) {
            // Remove WooCommerce styles
            wp_dequeue_style( 'woocommerce-general' );
            wp_dequeue_style( 'woocommerce-layout' );
            wp_dequeue_style( 'woocommerce-smallscreen' );
            wp_dequeue_style( 'woocommerce_frontend_styles' );
            wp_dequeue_style( 'woocommerce_fancybox_styles' );
            wp_dequeue_style( 'woocommerce_chosen_styles' );
            wp_dequeue_style( 'woocommerce_prettyPhoto_css' );
            
            // Remove WooCommerce scripts
            wp_dequeue_script( 'wc_price_slider' );
            wp_dequeue_script( 'wc-single-product' );
            wp_dequeue_script( 'wc-add-to-cart' );
            wp_dequeue_script( 'wc-cart-fragments' );
            wp_dequeue_script( 'wc-checkout' );
            wp_dequeue_script( 'wc-add-to-cart-variation' );
            wp_dequeue_script( 'wc-single-product' );
            wp_dequeue_script( 'wc-cart' );
            wp_dequeue_script( 'wc-chosen' );
            wp_dequeue_script( 'woocommerce' );
            wp_dequeue_script( 'prettyPhoto' );
            wp_dequeue_script( 'prettyPhoto-init' );
            wp_dequeue_script( 'jquery-blockui' );
            wp_dequeue_script( 'jquery-placeholder' );
            wp_dequeue_script( 'fancybox' );
            wp_dequeue_script( 'jqueryui' );
        }
    }
}

// Initialize the class
new AquaLuxe_Performance();

/**
 * Generate critical CSS
 * 
 * This function is used to generate critical CSS for the theme.
 * It should be called manually during development or build process.
 */
function aqualuxe_generate_critical_css() {
    // Check if we're in a development environment
    if ( ! defined( 'WP_DEBUG' ) || ! WP_DEBUG ) {
        return;
    }
    
    // Path to critical CSS file
    $critical_css_path = get_template_directory() . '/assets/dist/css/critical.css';
    
    // CSS rules to include in critical CSS
    $critical_css = '
    /* Base styles */
    :root {
        --color-primary-50: #f0f9ff;
        --color-primary-100: #e0f2fe;
        --color-primary-200: #bae6fd;
        --color-primary-300: #7dd3fc;
        --color-primary-400: #38bdf8;
        --color-primary-500: #0ea5e9;
        --color-primary-600: #0284c7;
        --color-primary-700: #0369a1;
        --color-primary-800: #075985;
        --color-primary-900: #0c4a6e;
        --color-primary-950: #082f49;
        
        --color-secondary-50: #f0fdfa;
        --color-secondary-100: #ccfbf1;
        --color-secondary-200: #99f6e4;
        --color-secondary-300: #5eead4;
        --color-secondary-400: #2dd4bf;
        --color-secondary-500: #14b8a6;
        --color-secondary-600: #0d9488;
        --color-secondary-700: #0f766e;
        --color-secondary-800: #115e59;
        --color-secondary-900: #134e4a;
        --color-secondary-950: #042f2e;
        
        --color-dark-50: #f8fafc;
        --color-dark-100: #f1f5f9;
        --color-dark-200: #e2e8f0;
        --color-dark-300: #cbd5e1;
        --color-dark-400: #94a3b8;
        --color-dark-500: #64748b;
        --color-dark-600: #475569;
        --color-dark-700: #334155;
        --color-dark-800: #1e293b;
        --color-dark-900: #0f172a;
        --color-dark-950: #020617;
    }
    
    /* Dark mode */
    .dark {
        --bg-color: var(--color-dark-900);
        --text-color: var(--color-dark-100);
    }
    
    /* Typography */
    body {
        font-family: "Inter", system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        color: var(--color-dark-900);
        line-height: 1.5;
    }
    
    .dark body {
        color: var(--color-dark-100);
        background-color: var(--color-dark-900);
    }
    
    /* Layout */
    .container {
        width: 100%;
        max-width: 1280px;
        margin-left: auto;
        margin-right: auto;
        padding-left: 1rem;
        padding-right: 1rem;
    }
    
    /* Header */
    .site-header {
        background-color: #fff;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
    }
    
    .dark .site-header {
        background-color: var(--color-dark-900);
    }
    
    /* Skip link */
    .screen-reader-text {
        border: 0;
        clip: rect(1px, 1px, 1px, 1px);
        clip-path: inset(50%);
        height: 1px;
        margin: -1px;
        overflow: hidden;
        padding: 0;
        position: absolute;
        width: 1px;
        word-wrap: normal !important;
    }
    
    .screen-reader-text:focus {
        background-color: #f1f1f1;
        border-radius: 3px;
        box-shadow: 0 0 2px 2px rgba(0, 0, 0, 0.6);
        clip: auto !important;
        clip-path: none;
        color: #21759b;
        display: block;
        font-size: 0.875rem;
        font-weight: 700;
        height: auto;
        left: 5px;
        line-height: normal;
        padding: 15px 23px 14px;
        text-decoration: none;
        top: 5px;
        width: auto;
        z-index: 100000;
    }
    ';
    
    // Write critical CSS to file
    file_put_contents( $critical_css_path, $critical_css );
}

/**
 * Add image dimensions to img tags
 *
 * @param string $content The content.
 * @return string Modified content.
 */
function aqualuxe_add_image_dimensions( $content ) {
    if ( empty( $content ) ) {
        return $content;
    }
    
    // Don't process in admin or feeds
    if ( is_admin() || is_feed() ) {
        return $content;
    }
    
    // Don't process AMP pages
    if ( function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() ) {
        return $content;
    }
    
    // Find all img tags without width and height attributes
    preg_match_all( '/<img[^>]+>/i', $content, $matches );
    
    foreach ( $matches[0] as $img_tag ) {
        // Skip if already has width and height
        if ( preg_match( '/width=["\']([^"\']+)["\']/', $img_tag ) && preg_match( '/height=["\']([^"\']+)["\']/', $img_tag ) ) {
            continue;
        }
        
        // Get src attribute
        preg_match( '/src=["\']([^"\']+)["\']/', $img_tag, $src_match );
        
        if ( ! isset( $src_match[1] ) ) {
            continue;
        }
        
        $src = $src_match[1];
        
        // Get image dimensions
        $dimensions = aqualuxe_get_image_dimensions( $src );
        
        if ( ! $dimensions ) {
            continue;
        }
        
        // Create new img tag with dimensions
        $new_img_tag = str_replace( '<img', '<img width="' . esc_attr( $dimensions['width'] ) . '" height="' . esc_attr( $dimensions['height'] ) . '"', $img_tag );
        
        // Replace old img tag with new one
        $content = str_replace( $img_tag, $new_img_tag, $content );
    }
    
    return $content;
}
add_filter( 'the_content', 'aqualuxe_add_image_dimensions' );
add_filter( 'post_thumbnail_html', 'aqualuxe_add_image_dimensions' );
add_filter( 'woocommerce_product_get_image', 'aqualuxe_add_image_dimensions' );

/**
 * Get image dimensions
 *
 * @param string $src Image source URL.
 * @return array|false Image dimensions or false on failure.
 */
function aqualuxe_get_image_dimensions( $src ) {
    // Check if URL is local
    $upload_dir = wp_upload_dir();
    $base_url = $upload_dir['baseurl'];
    
    if ( strpos( $src, $base_url ) === 0 ) {
        // Convert URL to file path
        $file_path = str_replace( $base_url, $upload_dir['basedir'], $src );
        
        // Get image size
        $size = @getimagesize( $file_path );
        
        if ( $size ) {
            return array(
                'width'  => $size[0],
                'height' => $size[1],
            );
        }
    }
    
    // For external images, try to get attachment ID
    $attachment_id = attachment_url_to_postid( $src );
    
    if ( $attachment_id ) {
        $metadata = wp_get_attachment_metadata( $attachment_id );
        
        if ( isset( $metadata['width'] ) && isset( $metadata['height'] ) ) {
            return array(
                'width'  => $metadata['width'],
                'height' => $metadata['height'],
            );
        }
    }
    
    return false;
}