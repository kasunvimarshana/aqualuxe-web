<?php
/**
 * AquaLuxe Assets Management
 * 
 * @package AquaLuxe
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class AquaLuxe_Assets {
    
    /**
     * Initialize assets
     */
    public static function init() {
        add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_styles' ) );
        add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_scripts' ) );
        add_action( 'admin_enqueue_scripts', array( __CLASS__, 'admin_enqueue_scripts' ) );
        add_action( 'wp_head', array( __CLASS__, 'preload_assets' ), 1 );
    }
    
    /**
     * Enqueue styles
     */
    public static function enqueue_styles() {
        // Main theme stylesheet
        wp_enqueue_style(
            'aqualuxe-style',
            get_stylesheet_uri(),
            array( 'storefront-style' ),
            AQUALUXE_VERSION
        );
        
        // Google Fonts
        wp_enqueue_style(
            'aqualuxe-google-fonts',
            self::get_google_fonts_url(),
            array(),
            null
        );
        
        // Custom CSS
        wp_enqueue_style(
            'aqualuxe-custom',
            get_stylesheet_directory_uri() . '/assets/css/custom.css',
            array(),
            AQUALUXE_VERSION
        );
    }
    
    /**
     * Enqueue scripts
     */
    public static function enqueue_scripts() {
        // Theme JavaScript
        wp_enqueue_script(
            'aqualuxe-theme',
            get_stylesheet_directory_uri() . '/assets/js/theme.js',
            array( 'jquery' ),
            AQUALUXE_VERSION,
            true
        );
        
        // Localize script
        wp_localize_script( 'aqualuxe-theme', 'aqualuxe_vars', array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce'    => wp_create_nonce( 'aqualuxe_nonce' ),
            'ajax_cart_enabled' => get_option( 'woocommerce_enable_ajax_add_to_cart' ) === 'yes' ? 'yes' : 'no',
        ) );
        
        // WooCommerce specific scripts
        if ( class_exists( 'WooCommerce' ) ) {
            wp_enqueue_script(
                'aqualuxe-woocommerce',
                get_stylesheet_directory_uri() . '/assets/js/woocommerce.js',
                array( 'jquery', 'wc-add-to-cart', 'wc-cart-fragments' ),
                AQUALUXE_VERSION,
                true
            );
        }
        
        // Comments reply script
        if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
            wp_enqueue_script( 'comment-reply' );
        }
    }
    
    /**
     * Admin enqueue scripts
     */
    public static function admin_enqueue_scripts( $hook ) {
        // Admin CSS
        wp_enqueue_style(
            'aqualuxe-admin',
            get_stylesheet_directory_uri() . '/assets/css/admin.css',
            array(),
            AQUALUXE_VERSION
        );
        
        // Admin JS
        wp_enqueue_script(
            'aqualuxe-admin',
            get_stylesheet_directory_uri() . '/assets/js/admin.js',
            array( 'jquery' ),
            AQUALUXE_VERSION,
            true
        );
    }
    
    /**
     * Preload critical assets
     */
    public static function preload_assets() {
        // Preload logo if set
        $custom_logo_id = get_theme_mod( 'custom_logo' );
        if ( $custom_logo_id ) {
            $logo_url = wp_get_attachment_image_url( $custom_logo_id, 'full' );
            echo '<link rel="preload" href="' . esc_url( $logo_url ) . '" as="image">' . "\n";
        }
    }
    
    /**
     * Get Google Fonts URL
     */
    public static function get_google_fonts_url() {
        $fonts_url = '';
        $fonts     = array();
        $subsets   = 'latin,latin-ext';
        
        // Primary font
        $fonts[] = 'Inter:400,500,600,700';
        
        // Secondary font
        $fonts[] = 'Playfair+Display:400,500,600,700';
        
        if ( $fonts ) {
            $fonts_url = add_query_arg( array(
                'family' => urlencode( implode( '|', $fonts ) ),
                'subset' => urlencode( $subsets ),
            ), 'https://fonts.googleapis.com/css' );
        }
        
        return $fonts_url;
    }
}

// Initialize
AquaLuxe_Assets::init();