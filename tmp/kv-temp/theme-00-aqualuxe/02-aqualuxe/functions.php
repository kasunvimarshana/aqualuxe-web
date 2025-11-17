<?php
/**
 * AquaLuxe Theme Functions
 * 
 * @package AquaLuxe
 * @author Kasun Vimarshana
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Theme setup and initialization
 */
class AquaLuxe_Theme {
    
    /**
     * Theme version
     */
    const VERSION = '1.0.0';
    
    /**
     * Single instance of the class
     */
    private static $instance = null;
    
    /**
     * Get single instance
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
        $this->setup_constants();
        $this->includes();
        $this->hooks();
    }
    
    /**
     * Setup theme constants
     */
    private function setup_constants() {
        define( 'AQUALUXE_VERSION', self::VERSION );
        define( 'AQUALUXE_THEME_DIR', get_template_directory() );
        define( 'AQUALUXE_THEME_URI', get_template_directory_uri() );
        define( 'AQUALUXE_INC_DIR', AQUALUXE_THEME_DIR . '/inc' );
        define( 'AQUALUXE_ASSETS_URI', AQUALUXE_THEME_URI . '/assets' );
    }
    
    /**
     * Include required files
     */
    private function includes() {
        // Core functionality
        require_once AQUALUXE_INC_DIR . '/class-aqualuxe-setup.php';
        require_once AQUALUXE_INC_DIR . '/class-aqualuxe-woocommerce.php';
        require_once AQUALUXE_INC_DIR . '/class-aqualuxe-customizer.php';
        require_once AQUALUXE_INC_DIR . '/class-aqualuxe-assets.php';
        require_once AQUALUXE_INC_DIR . '/class-aqualuxe-demo-content.php';
        require_once AQUALUXE_INC_DIR . '/class-aqualuxe-seo.php';
        require_once AQUALUXE_INC_DIR . '/class-aqualuxe-security.php';
        require_once AQUALUXE_INC_DIR . '/class-aqualuxe-performance.php';
        
        // Template tags
        require_once AQUALUXE_INC_DIR . '/template-tags.php';
        
        // Hooks
        require_once AQUALUXE_INC_DIR . '/hooks.php';
    }
    
    /**
     * Setup theme hooks
     */
    private function hooks() {
        add_action( 'after_setup_theme', array( $this, 'theme_setup' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
    }
    
    /**
     * Theme setup
     */
    public function theme_setup() {
        // Load child theme textdomain
        load_child_theme_textdomain( 'aqualuxe', get_stylesheet_directory() . '/languages' );
        
        // Add theme support
        add_theme_support( 'title-tag' );
        add_theme_support( 'post-thumbnails' );
        add_theme_support( 'html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script',
        ) );
        
        // Add image sizes
        add_image_size( 'aqualuxe-product-card', 400, 400, true );
        add_image_size( 'aqualuxe-product-gallery', 800, 800, true );
        add_image_size( 'aqualuxe-blog-thumbnail', 600, 400, true );
        
        // Register navigation menus
        register_nav_menus( array(
            'primary' => __( 'Primary Menu', 'aqualuxe' ),
            'mobile' => __( 'Mobile Menu', 'aqualuxe' ),
            'footer' => __( 'Footer Menu', 'aqualuxe' ),
        ) );
        
        // Add theme support for WooCommerce
        add_theme_support( 'woocommerce' );
        add_theme_support( 'wc-product-gallery-zoom' );
        add_theme_support( 'wc-product-gallery-lightbox' );
        add_theme_support( 'wc-product-gallery-slider' );
        
        // Add theme support for custom logo
        add_theme_support( 'custom-logo', array(
            'height'      => 60,
            'width'       => 200,
            'flex-height' => true,
            'flex-width'  => true,
            'header-text' => array( 'site-title', 'site-description' ),
        ) );
        
        // Add theme support for custom background
        add_theme_support( 'custom-background', array(
            'default-color' => 'ffffff',
            'default-image' => '',
        ) );
    }
    
    /**
     * Enqueue scripts and styles
     */
    public function enqueue_scripts() {
        // Main stylesheet
        wp_enqueue_style(
            'aqualuxe-style',
            get_stylesheet_uri(),
            array( 'storefront-style' ),
            AQUALUXE_VERSION
        );
        
        // Custom styles
        wp_enqueue_style(
            'aqualuxe-custom',
            AQUALUXE_ASSETS_URI . '/css/custom.css',
            array(),
            AQUALUXE_VERSION
        );
        
        // Theme JavaScript
        wp_enqueue_script(
            'aqualuxe-script',
            AQUALUXE_ASSETS_URI . '/js/theme.js',
            array( 'jquery' ),
            AQUALUXE_VERSION,
            true
        );
        
        // Localize script
        wp_localize_script( 'aqualuxe-script', 'aqualuxe_vars', array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce'    => wp_create_nonce( 'aqualuxe_nonce' ),
        ) );
        
        // WooCommerce enhancements
        if ( class_exists( 'WooCommerce' ) ) {
            wp_enqueue_script(
                'aqualuxe-woocommerce',
                AQUALUXE_ASSETS_URI . '/js/woocommerce.js',
                array( 'wc-add-to-cart', 'wc-cart-fragments' ),
                AQUALUXE_VERSION,
                true
            );
        }
    }
    
    /**
     * Admin scripts
     */
    public function admin_scripts( $hook ) {
        if ( 'appearance_page_aqualuxe' === $hook ) {
            wp_enqueue_style(
                'aqualuxe-admin',
                AQUALUXE_ASSETS_URI . '/css/admin.css',
                array(),
                AQUALUXE_VERSION
            );
            
            wp_enqueue_script(
                'aqualuxe-admin',
                AQUALUXE_ASSETS_URI . '/js/admin.js',
                array( 'jquery' ),
                AQUALUXE_VERSION,
                true
            );
        }
    }
}

/**
 * Initialize the theme
 */
AquaLuxe_Theme::get_instance();

/**
 * Helper function to get theme instance
 */
function aqualuxe() {
    return AquaLuxe_Theme::get_instance();
}

/**
 * Theme activation hook
 */
function aqualuxe_theme_activation() {
    // Set default options
    update_option( 'aqualuxe_version', AQUALUXE_VERSION );
    
    // Flush rewrite rules
    flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'aqualuxe_theme_activation' );

/**
 * Theme deactivation hook
 */
function aqualuxe_theme_deactivation() {
    // Clean up options
    delete_option( 'aqualuxe_version' );
    
    // Flush rewrite rules
    flush_rewrite_rules();
}
add_action( 'switch_theme', 'aqualuxe_theme_deactivation' );
