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
 * Main class that initializes the theme
 */
final class AquaLuxe_Theme {
    /**
     * Instance of this class
     *
     * @var AquaLuxe_Theme
     */
    private static $instance = null;

    /**
     * Get the singleton instance of this class
     *
     * @return AquaLuxe_Theme
     */
    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        // Load core files
        $this->load_core_files();
        
        // Setup theme
        add_action( 'after_setup_theme', array( $this, 'setup' ) );
        
        // Register assets
        add_action( 'wp_enqueue_scripts', array( $this, 'register_assets' ) );
        
        // Register widget areas
        add_action( 'widgets_init', array( $this, 'register_sidebars' ) );
        
        // WooCommerce integration
        $this->woocommerce_integration();
    }

    /**
     * Load core files
     */
    private function load_core_files() {
        // Load helper functions
        require_once AQUALUXE_DIR . 'inc/helpers/template-functions.php';
        require_once AQUALUXE_DIR . 'inc/helpers/template-tags.php';
        require_once AQUALUXE_DIR . 'inc/helpers/woocommerce-review-template.php';
        
        // Load classes
        require_once AQUALUXE_DIR . 'inc/classes/class-aqualuxe-walker-nav-menu.php';
        require_once AQUALUXE_DIR . 'inc/classes/class-aqualuxe-breadcrumb.php';
        
        // Load customizer
        require_once AQUALUXE_DIR . 'inc/customizer/customizer.php';
        
        // Load widgets
        require_once AQUALUXE_DIR . 'inc/widgets/class-aqualuxe-recent-products.php';
        require_once AQUALUXE_DIR . 'inc/widgets/class-aqualuxe-product-filter-widget.php';
        
        // Load admin files
        if ( is_admin() ) {
            require_once AQUALUXE_DIR . 'inc/admin/product-metaboxes.php';
        }
    }

    /**
     * Theme setup
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
        
        // Set up the WordPress core custom background feature
        add_theme_support(
            'custom-background',
            apply_filters(
                'aqualuxe_custom_background_args',
                array(
                    'default-color' => 'ffffff',
                    'default-image' => '',
                )
            )
        );
        
        // Add theme support for selective refresh for widgets
        add_theme_support( 'customize-selective-refresh-widgets' );
        
        // Add support for custom logo
        add_theme_support(
            'custom-logo',
            array(
                'height'      => 250,
                'width'       => 250,
                'flex-width'  => true,
                'flex-height' => true,
            )
        );
        
        // Add support for editor styles
        add_theme_support( 'editor-styles' );
        
        // Add support for responsive embeds
        add_theme_support( 'responsive-embeds' );
        
        // Add support for full and wide align images
        add_theme_support( 'align-wide' );
        
        // Add support for custom line height controls
        add_theme_support( 'custom-line-height' );
        
        // Add support for experimental link color control
        add_theme_support( 'experimental-link-color' );
        
        // Add support for custom spacing
        add_theme_support( 'custom-spacing' );
        
        // Add support for custom units
        add_theme_support( 'custom-units' );
    }

    /**
     * Register and enqueue assets
     */
    public function register_assets() {
        // Register styles
        wp_register_style(
            'aqualuxe-styles',
            AQUALUXE_URI . 'assets/css/main.css',
            array(),
            AQUALUXE_VERSION
        );
        
        // Register scripts
        wp_register_script(
            'aqualuxe-scripts',
            AQUALUXE_URI . 'assets/js/main.js',
            array( 'jquery' ),
            AQUALUXE_VERSION,
            true
        );
        
        // Enqueue styles
        wp_enqueue_style( 'aqualuxe-styles' );
        
        // Enqueue scripts
        wp_enqueue_script( 'aqualuxe-scripts' );
        
        // Add comment-reply script if needed
        if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
            wp_enqueue_script( 'comment-reply' );
        }
        
        // WooCommerce specific assets
        if ( class_exists( 'WooCommerce' ) ) {
            // Enqueue WooCommerce styles
            wp_enqueue_style(
                'aqualuxe-woocommerce-style',
                AQUALUXE_URI . 'assets/css/woocommerce.css',
                array(),
                AQUALUXE_VERSION
            );
            
            // Enqueue WooCommerce scripts
            wp_enqueue_script(
                'aqualuxe-woocommerce',
                AQUALUXE_URI . 'assets/js/woocommerce.js',
                array( 'jquery' ),
                AQUALUXE_VERSION,
                true
            );
            
            // Localize WooCommerce script
            wp_localize_script(
                'aqualuxe-woocommerce',
                'aqualuxe_wc_params',
                array(
                    'ajax_url'              => admin_url( 'admin-ajax.php' ),
                    'wc_ajax_url'           => WC_AJAX::get_endpoint( '%%endpoint%%' ),
                    'cart_url'              => wc_get_cart_url(),
                    'checkout_url'          => wc_get_checkout_url(),
                    'is_cart'               => is_cart(),
                    'is_checkout'           => is_checkout(),
                    'is_product'            => is_product(),
                    'is_shop'               => is_shop(),
                    'cart_redirect_after_add' => get_option( 'woocommerce_cart_redirect_after_add' ),
                    'i18n_view_cart'        => esc_html__( 'View Cart', 'aqualuxe' ),
                    'i18n_checkout'         => esc_html__( 'Checkout', 'aqualuxe' ),
                    'i18n_add_to_cart'      => esc_html__( 'Add to Cart', 'aqualuxe' ),
                    'i18n_added_to_cart'    => esc_html__( 'Added to Cart', 'aqualuxe' ),
                    'i18n_loading'          => esc_html__( 'Loading...', 'aqualuxe' ),
                    'i18n_send_inquiry'     => esc_html__( 'Send Inquiry', 'aqualuxe' ),
                    'i18n_inquiry_error'    => esc_html__( 'There was an error sending your inquiry. Please try again.', 'aqualuxe' ),
                    'nonce'                 => wp_create_nonce( 'aqualuxe-nonce' ),
                )
            );
            
            // Admin scripts
            if ( is_admin() ) {
                wp_enqueue_script(
                    'aqualuxe-woocommerce-admin',
                    AQUALUXE_URI . 'assets/js/woocommerce-admin.js',
                    array( 'jquery', 'jquery-ui-sortable' ),
                    AQUALUXE_VERSION,
                    true
                );
                
                wp_localize_script(
                    'aqualuxe-woocommerce-admin',
                    'aqualuxe_wc_admin_params',
                    array(
                        'i18n_remove'            => esc_html__( 'Remove', 'aqualuxe' ),
                        'i18n_add_360_images'    => esc_html__( 'Add 360° View Images', 'aqualuxe' ),
                        'i18n_add_to_gallery'    => esc_html__( 'Add to gallery', 'aqualuxe' ),
                        'i18n_delete_image'      => esc_html__( 'Delete image', 'aqualuxe' ),
                        'i18n_delete'            => esc_html__( 'Delete', 'aqualuxe' ),
                        'i18n_question'          => esc_html__( 'Question', 'aqualuxe' ),
                        'i18n_answer'            => esc_html__( 'Answer', 'aqualuxe' ),
                        'i18n_remove_faq'        => esc_html__( 'Remove FAQ', 'aqualuxe' ),
                        'i18n_tab_title'         => esc_html__( 'Tab Title', 'aqualuxe' ),
                        'i18n_tab_content'       => esc_html__( 'Tab Content', 'aqualuxe' ),
                        'i18n_remove_tab'        => esc_html__( 'Remove Tab', 'aqualuxe' ),
                        'i18n_upload_video'      => esc_html__( 'Upload Video', 'aqualuxe' ),
                        'i18n_use_video'         => esc_html__( 'Use Video', 'aqualuxe' ),
                        'i18n_highlight_placeholder' => esc_html__( 'Enter product highlight', 'aqualuxe' ),
                        'i18n_spec_name'         => esc_html__( 'Name', 'aqualuxe' ),
                        'i18n_spec_value'        => esc_html__( 'Value', 'aqualuxe' ),
                    )
                );
            }
        }
        
        // Localize script with theme data
        wp_localize_script(
            'aqualuxe-scripts',
            'aqualuxeData',
            array(
                'ajaxUrl' => admin_url( 'admin-ajax.php' ),
                'nonce'   => wp_create_nonce( 'aqualuxe-nonce' ),
            )
        );
    }

    /**
     * Register widget areas
     */
    public function register_sidebars() {
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
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<h3 class="widget-title">',
                'after_title'   => '</h3>',
            )
        );
        
        register_sidebar(
            array(
                'name'          => esc_html__( 'Footer 2', 'aqualuxe' ),
                'id'            => 'footer-2',
                'description'   => esc_html__( 'Add widgets here to appear in footer column 2.', 'aqualuxe' ),
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<h3 class="widget-title">',
                'after_title'   => '</h3>',
            )
        );
        
        register_sidebar(
            array(
                'name'          => esc_html__( 'Footer 3', 'aqualuxe' ),
                'id'            => 'footer-3',
                'description'   => esc_html__( 'Add widgets here to appear in footer column 3.', 'aqualuxe' ),
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<h3 class="widget-title">',
                'after_title'   => '</h3>',
            )
        );
        
        register_sidebar(
            array(
                'name'          => esc_html__( 'Footer 4', 'aqualuxe' ),
                'id'            => 'footer-4',
                'description'   => esc_html__( 'Add widgets here to appear in footer column 4.', 'aqualuxe' ),
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<h3 class="widget-title">',
                'after_title'   => '</h3>',
            )
        );
        
        register_sidebar(
            array(
                'name'          => esc_html__( 'Shop Sidebar', 'aqualuxe' ),
                'id'            => 'shop-sidebar',
                'description'   => esc_html__( 'Add widgets here to appear in shop sidebar.', 'aqualuxe' ),
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<h3 class="widget-title">',
                'after_title'   => '</h3>',
            )
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
            
            // Declare WooCommerce support
            add_theme_support( 'woocommerce' );
            
            // Add support for WooCommerce features
            add_theme_support( 'wc-product-gallery-zoom' );
            add_theme_support( 'wc-product-gallery-lightbox' );
            add_theme_support( 'wc-product-gallery-slider' );
        }
    }
}

// Initialize the theme
AquaLuxe_Theme::instance();