<?php
/**
 * AquaLuxe Theme Functions
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define theme constants
define( 'AQUALUXE_VERSION', '1.0.0' );
define( 'AQUALUXE_THEME_DIR', get_template_directory() );
define( 'AQUALUXE_THEME_URL', get_template_directory_uri() );
define( 'AQUALUXE_CORE_DIR', AQUALUXE_THEME_DIR . '/core/' );
define( 'AQUALUXE_MODULES_DIR', AQUALUXE_THEME_DIR . '/modules/' );
define( 'AQUALUXE_ASSETS_DIR', AQUALUXE_THEME_DIR . '/assets/' );
define( 'AQUALUXE_ASSETS_URL', AQUALUXE_THEME_URL . '/assets/' );

/**
 * AquaLuxe Theme Class
 * 
 * Main theme class that orchestrates the entire theme functionality
 * following SOLID principles and modular architecture.
 */
class AquaLuxe_Theme {

    /**
     * Theme instance
     *
     * @var AquaLuxe_Theme
     */
    private static $instance = null;

    /**
     * Core modules
     *
     * @var array
     */
    private $core_modules = [];

    /**
     * Feature modules
     *
     * @var array
     */
    private $feature_modules = [];

    /**
     * Get theme instance (Singleton pattern)
     *
     * @return AquaLuxe_Theme
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
        $this->init();
    }

    /**
     * Initialize theme
     */
    private function init() {
        // Load core modules first
        $this->load_core_modules();
        
        // Initialize hooks
        $this->init_hooks();
        
        // Load feature modules
        $this->load_feature_modules();
    }

    /**
     * Initialize WordPress hooks
     */
    private function init_hooks() {
        add_action( 'after_setup_theme', array( $this, 'setup_theme' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
        add_action( 'init', array( $this, 'init_theme' ) );
    }

    /**
     * Load core modules
     */
    private function load_core_modules() {
        $core_files = array(
            'class-theme-setup.php',
            'class-assets.php',
            'class-helpers.php',
            'class-post-types.php',
            'class-taxonomies.php',
            'class-module-loader.php',
            'class-security.php',
            'class-performance.php'
        );

        foreach ( $core_files as $file ) {
            $file_path = AQUALUXE_CORE_DIR . $file;
            if ( file_exists( $file_path ) ) {
                require_once $file_path;
            }
        }
    }

    /**
     * Load feature modules
     */
    private function load_feature_modules() {
        // Module loader will handle loading modules dynamically
        if ( class_exists( 'AquaLuxe_Module_Loader' ) ) {
            $module_loader = new AquaLuxe_Module_Loader();
            $module_loader->load_modules();
        }
    }

    /**
     * Theme setup
     */
    public function setup_theme() {
        // Theme support
        add_theme_support( 'post-thumbnails' );
        add_theme_support( 'title-tag' );
        add_theme_support( 'custom-logo' );
        add_theme_support( 'custom-header' );
        add_theme_support( 'custom-background' );
        add_theme_support( 'html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
        ) );
        add_theme_support( 'post-formats', array(
            'aside',
            'image',
            'video',
            'quote',
            'link',
            'gallery',
            'audio',
        ) );

        // WooCommerce support (dual-state architecture)
        if ( $this->is_woocommerce_active() ) {
            add_theme_support( 'woocommerce' );
            add_theme_support( 'wc-product-gallery-zoom' );
            add_theme_support( 'wc-product-gallery-lightbox' );
            add_theme_support( 'wc-product-gallery-slider' );
        }

        // Gutenberg support
        add_theme_support( 'wp-block-styles' );
        add_theme_support( 'align-wide' );
        add_theme_support( 'editor-styles' );
        add_editor_style( 'assets/dist/css/editor-style.css' );

        // Translation support
        load_theme_textdomain( 'aqualuxe', AQUALUXE_THEME_DIR . '/languages' );

        // Navigation menus
        register_nav_menus( array(
            'primary'   => esc_html__( 'Primary Menu', 'aqualuxe' ),
            'secondary' => esc_html__( 'Secondary Menu', 'aqualuxe' ),
            'footer'    => esc_html__( 'Footer Menu', 'aqualuxe' ),
            'mobile'    => esc_html__( 'Mobile Menu', 'aqualuxe' ),
        ) );

        // Image sizes
        add_image_size( 'aqualuxe-hero', 1920, 800, true );
        add_image_size( 'aqualuxe-featured', 600, 400, true );
        add_image_size( 'aqualuxe-thumbnail', 300, 200, true );
        add_image_size( 'aqualuxe-product-large', 800, 600, true );
        add_image_size( 'aqualuxe-product-medium', 400, 300, true );
    }

    /**
     * Enqueue frontend scripts and styles
     */
    public function enqueue_scripts() {
        if ( class_exists( 'AquaLuxe_Assets' ) ) {
            $assets = new AquaLuxe_Assets();
            $assets->enqueue_frontend_assets();
        }
    }

    /**
     * Enqueue admin scripts and styles
     */
    public function admin_enqueue_scripts() {
        if ( class_exists( 'AquaLuxe_Assets' ) ) {
            $assets = new AquaLuxe_Assets();
            $assets->enqueue_admin_assets();
        }
    }

    /**
     * Initialize theme after WordPress init
     */
    public function init_theme() {
        // Register custom post types
        if ( class_exists( 'AquaLuxe_Post_Types' ) ) {
            $post_types = new AquaLuxe_Post_Types();
            $post_types->register();
        }

        // Register custom taxonomies
        if ( class_exists( 'AquaLuxe_Taxonomies' ) ) {
            $taxonomies = new AquaLuxe_Taxonomies();
            $taxonomies->register();
        }

        // Initialize security features
        if ( class_exists( 'AquaLuxe_Security' ) ) {
            $security = new AquaLuxe_Security();
            $security->init();
        }

        // Initialize performance optimizations
        if ( class_exists( 'AquaLuxe_Performance' ) ) {
            $performance = new AquaLuxe_Performance();
            $performance->init();
        }
    }

    /**
     * Check if WooCommerce is active
     *
     * @return bool
     */
    private function is_woocommerce_active() {
        return class_exists( 'WooCommerce' );
    }

    /**
     * Get theme instance for external access
     *
     * @return AquaLuxe_Theme
     */
    public static function instance() {
        return self::get_instance();
    }
}

/**
 * Initialize the theme
 */
function aqualuxe_theme() {
    return AquaLuxe_Theme::get_instance();
}

// Start the theme
aqualuxe_theme();

/**
 * Helper function to get theme instance
 */
if ( ! function_exists( 'aqualuxe' ) ) {
    function aqualuxe() {
        return aqualuxe_theme();
    }
}