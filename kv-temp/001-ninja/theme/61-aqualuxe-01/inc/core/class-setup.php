<?php
/**
 * Theme setup class
 *
 * @package AquaLuxe
 * @subpackage Core
 * @since 1.0.0
 */

namespace AquaLuxe\Core;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Setup class
 */
class Setup {
    /**
     * Constructor
     */
    public function __construct() {
        // Theme setup
        add_action( 'after_setup_theme', [ $this, 'theme_setup' ] );
        
        // Register widget areas
        add_action( 'widgets_init', [ $this, 'register_widget_areas' ] );
        
        // Set content width
        add_action( 'template_redirect', [ $this, 'content_width' ] );
        
        // Add body classes
        add_filter( 'body_class', [ $this, 'body_classes' ] );
        
        // Add pingback header
        add_action( 'wp_head', [ $this, 'pingback_header' ] );
    }

    /**
     * Theme setup
     */
    public function theme_setup() {
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
        add_image_size( 'aqualuxe-card', 600, 400, true );
        add_image_size( 'aqualuxe-thumbnail', 300, 300, true );

        // Register navigation menus
        register_nav_menus(
            [
                'primary'   => esc_html__( 'Primary Menu', 'aqualuxe' ),
                'secondary' => esc_html__( 'Secondary Menu', 'aqualuxe' ),
                'footer'    => esc_html__( 'Footer Menu', 'aqualuxe' ),
                'social'    => esc_html__( 'Social Menu', 'aqualuxe' ),
            ]
        );

        // Switch default core markup to output valid HTML5
        add_theme_support(
            'html5',
            [
                'search-form',
                'comment-form',
                'comment-list',
                'gallery',
                'caption',
                'style',
                'script',
            ]
        );

        // Add theme support for selective refresh for widgets
        add_theme_support( 'customize-selective-refresh-widgets' );

        // Add support for editor styles
        add_theme_support( 'editor-styles' );

        // Enqueue editor styles
        add_editor_style( 'assets/dist/css/editor-style.css' );

        // Add support for responsive embeds
        add_theme_support( 'responsive-embeds' );

        // Add support for full and wide align images
        add_theme_support( 'align-wide' );

        // Add support for custom line height controls
        add_theme_support( 'custom-line-height' );

        // Add support for experimental link color control
        add_theme_support( 'experimental-link-color' );

        // Add support for custom units
        add_theme_support( 'custom-units' );

        // Add support for custom spacing
        add_theme_support( 'custom-spacing' );

        // Add support for custom logo
        add_theme_support(
            'custom-logo',
            [
                'height'      => 100,
                'width'       => 350,
                'flex-width'  => true,
                'flex-height' => true,
            ]
        );

        // Add support for WooCommerce
        add_theme_support( 'woocommerce' );
        add_theme_support( 'wc-product-gallery-zoom' );
        add_theme_support( 'wc-product-gallery-lightbox' );
        add_theme_support( 'wc-product-gallery-slider' );
    }

    /**
     * Register widget areas
     */
    public function register_widget_areas() {
        register_sidebar(
            [
                'name'          => esc_html__( 'Sidebar', 'aqualuxe' ),
                'id'            => 'sidebar-1',
                'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'aqualuxe' ),
                'before_widget' => '<section id="%1$s" class="widget %2$s">',
                'after_widget'  => '</section>',
                'before_title'  => '<h2 class="widget-title">',
                'after_title'   => '</h2>',
            ]
        );

        register_sidebar(
            [
                'name'          => esc_html__( 'Footer 1', 'aqualuxe' ),
                'id'            => 'footer-1',
                'description'   => esc_html__( 'Add widgets here to appear in footer column 1.', 'aqualuxe' ),
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<h3 class="widget-title">',
                'after_title'   => '</h3>',
            ]
        );

        register_sidebar(
            [
                'name'          => esc_html__( 'Footer 2', 'aqualuxe' ),
                'id'            => 'footer-2',
                'description'   => esc_html__( 'Add widgets here to appear in footer column 2.', 'aqualuxe' ),
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<h3 class="widget-title">',
                'after_title'   => '</h3>',
            ]
        );

        register_sidebar(
            [
                'name'          => esc_html__( 'Footer 3', 'aqualuxe' ),
                'id'            => 'footer-3',
                'description'   => esc_html__( 'Add widgets here to appear in footer column 3.', 'aqualuxe' ),
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<h3 class="widget-title">',
                'after_title'   => '</h3>',
            ]
        );

        register_sidebar(
            [
                'name'          => esc_html__( 'Footer 4', 'aqualuxe' ),
                'id'            => 'footer-4',
                'description'   => esc_html__( 'Add widgets here to appear in footer column 4.', 'aqualuxe' ),
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<h3 class="widget-title">',
                'after_title'   => '</h3>',
            ]
        );

        // WooCommerce shop sidebar
        if ( class_exists( 'WooCommerce' ) ) {
            register_sidebar(
                [
                    'name'          => esc_html__( 'Shop Sidebar', 'aqualuxe' ),
                    'id'            => 'shop-sidebar',
                    'description'   => esc_html__( 'Add widgets here to appear in shop sidebar.', 'aqualuxe' ),
                    'before_widget' => '<section id="%1$s" class="widget %2$s">',
                    'after_widget'  => '</section>',
                    'before_title'  => '<h2 class="widget-title">',
                    'after_title'   => '</h2>',
                ]
            );
        }
    }

    /**
     * Set the content width
     */
    public function content_width() {
        $GLOBALS['content_width'] = apply_filters( 'aqualuxe_content_width', 1200 );
    }

    /**
     * Add custom classes to the body
     *
     * @param array $classes The body classes.
     * @return array The modified body classes.
     */
    public function body_classes( $classes ) {
        // Add a class if there is a custom header
        if ( has_custom_header() ) {
            $classes[] = 'has-custom-header';
        }

        // Add a class if there is a custom background
        if ( get_background_image() ) {
            $classes[] = 'has-custom-background';
        }

        // Add a class if the sidebar is active
        if ( is_active_sidebar( 'sidebar-1' ) && ! is_page_template( 'templates/full-width.php' ) ) {
            $classes[] = 'has-sidebar';
        } else {
            $classes[] = 'no-sidebar';
        }

        // Add a class for the layout
        $layout = get_theme_mod( 'aqualuxe_layout', 'right-sidebar' );
        $classes[] = 'layout-' . sanitize_html_class( $layout );

        // Add a class for the header layout
        $header_layout = get_theme_mod( 'aqualuxe_header_layout', 'default' );
        $classes[] = 'header-' . sanitize_html_class( $header_layout );

        // Add a class for the footer layout
        $footer_layout = get_theme_mod( 'aqualuxe_footer_layout', 'default' );
        $classes[] = 'footer-' . sanitize_html_class( $footer_layout );

        // Add a class for dark mode
        if ( get_theme_mod( 'aqualuxe_dark_mode_default', false ) ) {
            $classes[] = 'dark-mode';
        }

        // Add a class for RTL
        if ( is_rtl() ) {
            $classes[] = 'rtl';
        }

        // Add a class for WooCommerce
        if ( class_exists( 'WooCommerce' ) ) {
            $classes[] = 'woocommerce-active';

            // Add a class for the shop layout
            if ( is_shop() || is_product_category() || is_product_tag() ) {
                $shop_layout = get_theme_mod( 'aqualuxe_shop_layout', 'grid' );
                $classes[] = 'shop-layout-' . sanitize_html_class( $shop_layout );
            }
        }

        return $classes;
    }

    /**
     * Add a pingback url auto-discovery header for single posts, pages, or attachments
     */
    public function pingback_header() {
        if ( is_singular() && pings_open() ) {
            printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
        }
    }
}

// Initialize the class
new Setup();