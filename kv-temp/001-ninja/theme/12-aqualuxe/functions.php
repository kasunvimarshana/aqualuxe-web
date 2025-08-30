<?php
/**
 * AquaLuxe functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Define constants
define( 'AQUALUXE_VERSION', '1.0.0' );
define( 'AQUALUXE_DIR', trailingslashit( get_template_directory() ) );
define( 'AQUALUXE_URI', trailingslashit( get_template_directory_uri() ) );

// Prevent direct access to this file
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * AquaLuxe Theme Class
 * 
 * Main theme class using OOP approach for better organization
 */
final class AquaLuxe_Theme {
    /**
     * Instance
     *
     * @access private
     * @var object Class object.
     * @since 1.0.0
     */
    private static $instance;

    /**
     * Initiator
     *
     * @return object initialized object of class.
     * @since 1.0.0
     */
    public static function get_instance() {
        if ( ! isset( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        // Initialize theme
        $this->init();
    }

    /**
     * Initialize the theme
     */
    private function init() {
        // Load theme files
        $this->load_files();
        
        // Setup theme
        add_action( 'after_setup_theme', array( $this, 'setup' ) );
        
        // Register widget areas
        add_action( 'widgets_init', array( $this, 'widgets_init' ) );
        
        // Enqueue scripts and styles
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
        
        // Add admin scripts and styles
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
        
        // Add editor styles
        add_action( 'enqueue_block_editor_assets', array( $this, 'block_editor_styles' ) );
        
        // WooCommerce integration
        $this->woocommerce_integration();
    }

    /**
     * Load required files
     */
    private function load_files() {
        // Core functionality
        require_once AQUALUXE_DIR . 'inc/class-aqualuxe-core.php';
        
        // Customizer
        require_once AQUALUXE_DIR . 'inc/customizer/class-aqualuxe-customizer.php';
        
        // Template functions
        require_once AQUALUXE_DIR . 'inc/template-functions.php';
        
        // Template hooks
        require_once AQUALUXE_DIR . 'inc/template-hooks.php';
        
        // Custom post types
        require_once AQUALUXE_DIR . 'inc/post-types.php';
        
        // Widgets
        require_once AQUALUXE_DIR . 'inc/widgets.php';
        
        // Helper functions
        require_once AQUALUXE_DIR . 'inc/helpers.php';
        
        // Comment template
        require_once AQUALUXE_DIR . 'inc/comment-template.php';
        
        // Demo import
        require_once AQUALUXE_DIR . 'inc/demo-import.php';
    }

    /**
     * Sets up theme defaults and registers support for various WordPress features.
     */
    public function setup() {
        // Load text domain
        load_theme_textdomain( 'aqualuxe', AQUALUXE_DIR . 'languages' );

        // Add default posts and comments RSS feed links to head
        add_theme_support( 'automatic-feed-links' );

        // Let WordPress manage the document title
        add_theme_support( 'title-tag' );

        // Enable support for Post Thumbnails on posts and pages
        add_theme_support( 'post-thumbnails' );

        // Set post thumbnail size
        set_post_thumbnail_size( 1200, 9999 );

        // Add custom image sizes
        add_image_size( 'aqualuxe-featured', 1600, 900, true );
        add_image_size( 'aqualuxe-product-thumbnail', 600, 600, true );
        add_image_size( 'aqualuxe-blog-thumbnail', 800, 450, true );

        // Register navigation menus
        register_nav_menus(
            array(
                'primary'   => esc_html__( 'Primary Menu', 'aqualuxe' ),
                'secondary' => esc_html__( 'Secondary Menu', 'aqualuxe' ),
                'footer'    => esc_html__( 'Footer Menu', 'aqualuxe' ),
            )
        );

        // Switch default core markup to output valid HTML5
        add_theme_support(
            'html5',
            array(
                'search-form',
                'comment-form',
                'comment-list',
                'gallery',
                'caption',
                'style',
                'script',
            )
        );

        // Add theme support for selective refresh for widgets
        add_theme_support( 'customize-selective-refresh-widgets' );

        // Add support for editor styles
        add_theme_support( 'editor-styles' );

        // Enqueue editor styles
        add_editor_style( 'assets/css/editor-style.css' );

        // Add support for responsive embeds
        add_theme_support( 'responsive-embeds' );

        // Add support for full and wide align images
        add_theme_support( 'align-wide' );

        // Add support for custom logo
        add_theme_support(
            'custom-logo',
            array(
                'height'      => 100,
                'width'       => 350,
                'flex-width'  => true,
                'flex-height' => true,
            )
        );

        // Add support for custom background
        add_theme_support(
            'custom-background',
            array(
                'default-color' => 'ffffff',
            )
        );

        // Add support for custom header
        add_theme_support(
            'custom-header',
            array(
                'default-image'      => '',
                'default-text-color' => '000000',
                'width'              => 1600,
                'height'             => 500,
                'flex-width'         => true,
                'flex-height'        => true,
            )
        );

        // Add support for block styles
        add_theme_support( 'wp-block-styles' );
    }

    /**
     * Register widget areas
     */
    public function widgets_init() {
        register_sidebar(
            array(
                'name'          => esc_html__( 'Sidebar', 'aqualuxe' ),
                'id'            => 'sidebar-1',
                'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'aqualuxe' ),
                'before_widget' => '<section id="%1$s" class="widget %2$s">',
                'after_widget'  => '</section>',
                'before_title'  => '<h2 class="widget-title">',
                'after_title'   => '</h2>',
            )
        );

        register_sidebar(
            array(
                'name'          => esc_html__( 'Footer 1', 'aqualuxe' ),
                'id'            => 'footer-1',
                'description'   => esc_html__( 'Add widgets here to appear in footer column 1.', 'aqualuxe' ),
                'before_widget' => '<section id="%1$s" class="widget %2$s">',
                'after_widget'  => '</section>',
                'before_title'  => '<h2 class="widget-title">',
                'after_title'   => '</h2>',
            )
        );

        register_sidebar(
            array(
                'name'          => esc_html__( 'Footer 2', 'aqualuxe' ),
                'id'            => 'footer-2',
                'description'   => esc_html__( 'Add widgets here to appear in footer column 2.', 'aqualuxe' ),
                'before_widget' => '<section id="%1$s" class="widget %2$s">',
                'after_widget'  => '</section>',
                'before_title'  => '<h2 class="widget-title">',
                'after_title'   => '</h2>',
            )
        );

        register_sidebar(
            array(
                'name'          => esc_html__( 'Footer 3', 'aqualuxe' ),
                'id'            => 'footer-3',
                'description'   => esc_html__( 'Add widgets here to appear in footer column 3.', 'aqualuxe' ),
                'before_widget' => '<section id="%1$s" class="widget %2$s">',
                'after_widget'  => '</section>',
                'before_title'  => '<h2 class="widget-title">',
                'after_title'   => '</h2>',
            )
        );

        register_sidebar(
            array(
                'name'          => esc_html__( 'Footer 4', 'aqualuxe' ),
                'id'            => 'footer-4',
                'description'   => esc_html__( 'Add widgets here to appear in footer column 4.', 'aqualuxe' ),
                'before_widget' => '<section id="%1$s" class="widget %2$s">',
                'after_widget'  => '</section>',
                'before_title'  => '<h2 class="widget-title">',
                'after_title'   => '</h2>',
            )
        );

        register_sidebar(
            array(
                'name'          => esc_html__( 'Shop Sidebar', 'aqualuxe' ),
                'id'            => 'shop-sidebar',
                'description'   => esc_html__( 'Add widgets here to appear in shop sidebar.', 'aqualuxe' ),
                'before_widget' => '<section id="%1$s" class="widget %2$s">',
                'after_widget'  => '</section>',
                'before_title'  => '<h2 class="widget-title">',
                'after_title'   => '</h2>',
            )
        );
    }

    /**
     * Enqueue scripts and styles
     */
    public function enqueue_scripts() {
        // Enqueue Google Fonts
        wp_enqueue_style(
            'aqualuxe-fonts',
            'https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap',
            array(),
            AQUALUXE_VERSION
        );

        // Enqueue main stylesheet
        wp_enqueue_style(
            'aqualuxe-style',
            AQUALUXE_URI . 'assets/css/main.css',
            array(),
            AQUALUXE_VERSION
        );

        // Enqueue theme script
        wp_enqueue_script(
            'aqualuxe-script',
            AQUALUXE_URI . 'assets/js/main.js',
            array( 'jquery' ),
            AQUALUXE_VERSION,
            true
        );

        // Localize script
        wp_localize_script(
            'aqualuxe-script',
            'aqualuxeVars',
            array(
                'ajaxurl' => admin_url( 'admin-ajax.php' ),
                'nonce'   => wp_create_nonce( 'aqualuxe-nonce' ),
            )
        );

        // Comment reply script
        if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
            wp_enqueue_script( 'comment-reply' );
        }
    }

    /**
     * Enqueue admin scripts and styles
     */
    public function admin_scripts() {
        wp_enqueue_style(
            'aqualuxe-admin-style',
            AQUALUXE_URI . 'assets/css/admin.css',
            array(),
            AQUALUXE_VERSION
        );

        wp_enqueue_script(
            'aqualuxe-admin-script',
            AQUALUXE_URI . 'assets/js/admin.js',
            array( 'jquery' ),
            AQUALUXE_VERSION,
            true
        );
    }

    /**
     * Enqueue block editor assets
     */
    public function block_editor_styles() {
        wp_enqueue_style(
            'aqualuxe-editor-style',
            AQUALUXE_URI . 'assets/css/editor-style.css',
            array(),
            AQUALUXE_VERSION
        );
    }

    /**
     * WooCommerce integration
     */
    private function woocommerce_integration() {
        // Check if WooCommerce is active
        if ( class_exists( 'WooCommerce' ) ) {
            // Include WooCommerce compatibility file
            require_once AQUALUXE_DIR . 'inc/woocommerce.php';
        }
    }
}

// Initialize the theme
AquaLuxe_Theme::get_instance();