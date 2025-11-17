<?php
/**
 * Theme Setup Class
 *
 * Handles the basic theme setup and configuration
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * AquaLuxe Theme Setup Class
 */
class AquaLuxe_Theme_Setup {

    /**
     * Constructor
     */
    public function __construct() {
        $this->init();
    }

    /**
     * Initialize the class
     */
    public function init() {
        add_action( 'after_setup_theme', array( $this, 'theme_setup' ) );
        add_action( 'widgets_init', array( $this, 'widgets_init' ) );
        add_action( 'wp_head', array( $this, 'add_meta_tags' ) );
        add_filter( 'body_class', array( $this, 'body_classes' ) );
        add_filter( 'wp_nav_menu_args', array( $this, 'nav_menu_args' ) );
    }

    /**
     * Set up theme defaults and register support for various WordPress features
     */
    public function theme_setup() {
        // Make theme available for translation
        load_theme_textdomain( 'aqualuxe', get_template_directory() . '/languages' );

        // Add default posts and comments RSS feed links to head
        add_theme_support( 'automatic-feed-links' );

        // Let WordPress manage the document title
        add_theme_support( 'title-tag' );

        // Enable support for Post Thumbnails on posts and pages
        add_theme_support( 'post-thumbnails' );

        // Set default thumbnail size
        set_post_thumbnail_size( 300, 200, true );

        // Custom image sizes
        add_image_size( 'aqualuxe-hero', 1920, 800, true );
        add_image_size( 'aqualuxe-featured', 600, 400, true );
        add_image_size( 'aqualuxe-thumbnail', 300, 200, true );
        add_image_size( 'aqualuxe-small', 150, 100, true );
        add_image_size( 'aqualuxe-product-large', 800, 600, true );
        add_image_size( 'aqualuxe-product-medium', 400, 300, true );
        add_image_size( 'aqualuxe-product-small', 200, 150, true );

        // Navigation menus
        register_nav_menus( array(
            'primary'   => esc_html__( 'Primary Menu', 'aqualuxe' ),
            'secondary' => esc_html__( 'Secondary Menu', 'aqualuxe' ),
            'footer'    => esc_html__( 'Footer Menu', 'aqualuxe' ),
            'mobile'    => esc_html__( 'Mobile Menu', 'aqualuxe' ),
            'shop'      => esc_html__( 'Shop Menu', 'aqualuxe' ),
            'account'   => esc_html__( 'Account Menu', 'aqualuxe' ),
        ) );

        // HTML5 support
        add_theme_support( 'html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script',
        ) );

        // Set up the WordPress core custom background feature
        add_theme_support( 'custom-background', apply_filters( 'aqualuxe_custom_background_args', array(
            'default-color' => 'ffffff',
            'default-image' => '',
        ) ) );

        // Add theme support for selective refresh for widgets
        add_theme_support( 'customize-selective-refresh-widgets' );

        // Custom logo support
        add_theme_support( 'custom-logo', array(
            'height'      => 250,
            'width'       => 250,
            'flex-width'  => true,
            'flex-height' => true,
        ) );

        // Custom header support
        add_theme_support( 'custom-header', array(
            'default-image'      => '',
            'default-text-color' => '000000',
            'width'              => 1920,
            'height'             => 500,
            'flex-height'        => true,
            'flex-width'         => true,
        ) );

        // Post formats support
        add_theme_support( 'post-formats', array(
            'aside',
            'image',
            'video',
            'quote',
            'link',
            'gallery',
            'status',
            'audio',
            'chat',
        ) );

        // Block editor support
        add_theme_support( 'wp-block-styles' );
        add_theme_support( 'align-wide' );
        add_theme_support( 'editor-styles' );
        add_editor_style( 'assets/dist/css/editor-style.css' );
        add_theme_support( 'responsive-embeds' );

        // WooCommerce support
        if ( class_exists( 'WooCommerce' ) ) {
            add_theme_support( 'woocommerce' );
            add_theme_support( 'wc-product-gallery-zoom' );
            add_theme_support( 'wc-product-gallery-lightbox' );
            add_theme_support( 'wc-product-gallery-slider' );
        }

        // Content width
        if ( ! isset( $content_width ) ) {
            $content_width = 1200;
        }
    }

    /**
     * Register widget area
     */
    public function widgets_init() {
        register_sidebar( array(
            'name'          => esc_html__( 'Primary Sidebar', 'aqualuxe' ),
            'id'            => 'sidebar-1',
            'description'   => esc_html__( 'Add widgets here.', 'aqualuxe' ),
            'before_widget' => '<section id="%1$s" class="widget %2$s bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">',
            'after_widget'  => '</section>',
            'before_title'  => '<h3 class="widget-title text-lg font-semibold text-gray-900 dark:text-white mb-4">',
            'after_title'   => '</h3>',
        ) );

        register_sidebar( array(
            'name'          => esc_html__( 'Footer Widget Area 1', 'aqualuxe' ),
            'id'            => 'footer-1',
            'description'   => esc_html__( 'Add widgets here to appear in your footer.', 'aqualuxe' ),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title text-lg font-semibold text-white mb-4">',
            'after_title'   => '</h3>',
        ) );

        register_sidebar( array(
            'name'          => esc_html__( 'Footer Widget Area 2', 'aqualuxe' ),
            'id'            => 'footer-2',
            'description'   => esc_html__( 'Add widgets here to appear in your footer.', 'aqualuxe' ),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title text-lg font-semibold text-white mb-4">',
            'after_title'   => '</h3>',
        ) );

        register_sidebar( array(
            'name'          => esc_html__( 'Footer Widget Area 3', 'aqualuxe' ),
            'id'            => 'footer-3',
            'description'   => esc_html__( 'Add widgets here to appear in your footer.', 'aqualuxe' ),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title text-lg font-semibold text-white mb-4">',
            'after_title'   => '</h3>',
        ) );

        register_sidebar( array(
            'name'          => esc_html__( 'Footer Widget Area 4', 'aqualuxe' ),
            'id'            => 'footer-4',
            'description'   => esc_html__( 'Add widgets here to appear in your footer.', 'aqualuxe' ),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title text-lg font-semibold text-white mb-4">',
            'after_title'   => '</h3>',
        ) );

        register_sidebar( array(
            'name'          => esc_html__( 'Shop Sidebar', 'aqualuxe' ),
            'id'            => 'shop-sidebar',
            'description'   => esc_html__( 'Add widgets here to appear in the shop sidebar.', 'aqualuxe' ),
            'before_widget' => '<section id="%1$s" class="widget %2$s bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">',
            'after_widget'  => '</section>',
            'before_title'  => '<h3 class="widget-title text-lg font-semibold text-gray-900 dark:text-white mb-4">',
            'after_title'   => '</h3>',
        ) );
    }

    /**
     * Add meta tags to head
     */
    public function add_meta_tags() {
        // Mobile viewport meta tag
        echo '<meta name="viewport" content="width=device-width, initial-scale=1">' . "\n";
        
        // Theme color for mobile browsers
        echo '<meta name="theme-color" content="#14b8a6">' . "\n";
        
        // Apple touch icon
        echo '<meta name="apple-mobile-web-app-capable" content="yes">' . "\n";
        echo '<meta name="apple-mobile-web-app-status-bar-style" content="default">' . "\n";
        
        // Microsoft tile color
        echo '<meta name="msapplication-TileColor" content="#14b8a6">' . "\n";
    }

    /**
     * Add custom body classes
     *
     * @param array $classes Classes for the body element.
     * @return array
     */
    public function body_classes( $classes ) {
        // Add a class of hfeed to non-singular pages
        if ( ! is_singular() ) {
            $classes[] = 'hfeed';
        }

        // Add a class if we have a custom logo
        if ( has_custom_logo() ) {
            $classes[] = 'has-custom-logo';
        }

        // Add dark mode class if set
        if ( get_theme_mod( 'dark_mode_enabled', false ) ) {
            $classes[] = 'dark-mode';
        }

        // Add WooCommerce classes
        if ( class_exists( 'WooCommerce' ) ) {
            $classes[] = 'woocommerce-active';
            
            if ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ) {
                $classes[] = 'woocommerce-page';
            }
        }

        return $classes;
    }

    /**
     * Modify navigation menu arguments
     *
     * @param array $args Nav menu arguments.
     * @return array
     */
    public function nav_menu_args( $args ) {
        // Add default classes to navigation menus
        if ( ! isset( $args['menu_class'] ) ) {
            $args['menu_class'] = 'nav-menu';
        }

        // Add container class for mobile responsiveness
        if ( ! isset( $args['container_class'] ) ) {
            $args['container_class'] = 'nav-container';
        }

        return $args;
    }
}