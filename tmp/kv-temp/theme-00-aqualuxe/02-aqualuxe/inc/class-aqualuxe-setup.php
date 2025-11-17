<?php
/**
 * AquaLuxe Theme Setup Class
 * 
 * @package AquaLuxe
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class AquaLuxe_Setup {
    
    /**
     * Initialize the setup
     */
    public static function init() {
        add_action( 'after_setup_theme', array( __CLASS__, 'theme_setup' ) );
        add_action( 'widgets_init', array( __CLASS__, 'register_sidebars' ) );
        add_filter( 'image_size_names_choose', array( __CLASS__, 'custom_image_sizes' ) );
    }
    
    /**
     * Theme setup
     */
    public static function theme_setup() {
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
        add_image_size( 'aqualuxe-banner', 1920, 600, true );
        
        // Register navigation menus
        register_nav_menus( array(
            'primary' => __( 'Primary Menu', 'aqualuxe' ),
            'mobile' => __( 'Mobile Menu', 'aqualuxe' ),
            'footer' => __( 'Footer Menu', 'aqualuxe' ),
            'top-bar' => __( 'Top Bar Menu', 'aqualuxe' ),
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
        
        // Add theme support for selective refresh
        add_theme_support( 'customize-selective-refresh-widgets' );
        
        // Add theme support for starter content
        add_theme_support( 'starter-content', array(
            'posts' => array(
                'home' => array(
                    'post_type' => 'page',
                    'post_title' => __( 'Home', 'aqualuxe' ),
                    'template' => 'templates/homepage.php',
                ),
                'about' => array(
                    'post_type' => 'page',
                    'post_title' => __( 'About Us', 'aqualuxe' ),
                ),
                'contact' => array(
                    'post_type' => 'page',
                    'post_title' => __( 'Contact', 'aqualuxe' ),
                    'template' => 'templates/contact.php',
                ),
            ),
            'nav_menus' => array(
                'primary' => array(
                    'name' => __( 'Primary Menu', 'aqualuxe' ),
                    'items' => array(
                        'page_home' => array(
                            'type' => 'post_type',
                            'object' => 'page',
                            'object_id' => '{{home}}',
                        ),
                        'page_about' => array(
                            'type' => 'post_type',
                            'object' => 'page',
                            'object_id' => '{{about}}',
                        ),
                        'page_contact' => array(
                            'type' => 'post_type',
                            'object' => 'page',
                            'object_id' => '{{contact}}',
                        ),
                    ),
                ),
            ),
        ) );
    }
    
    /**
     * Register widget areas
     */
    public static function register_sidebars() {
        // Main sidebar
        register_sidebar( array(
            'name'          => __( 'Main Sidebar', 'aqualuxe' ),
            'id'            => 'main-sidebar',
            'description'   => __( 'Widgets in this area will be shown in the sidebar.', 'aqualuxe' ),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        ) );
        
        // Shop sidebar
        register_sidebar( array(
            'name'          => __( 'Shop Sidebar', 'aqualuxe' ),
            'id'            => 'shop-sidebar',
            'description'   => __( 'Widgets in this area will be shown on shop pages.', 'aqualuxe' ),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        ) );
        
        // Footer widgets
        register_sidebar( array(
            'name'          => __( 'Footer Column 1', 'aqualuxe' ),
            'id'            => 'footer-1',
            'description'   => __( 'Widgets in this area will be shown in the first footer column.', 'aqualuxe' ),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        ) );
        
        register_sidebar( array(
            'name'          => __( 'Footer Column 2', 'aqualuxe' ),
            'id'            => 'footer-2',
            'description'   => __( 'Widgets in this area will be shown in the second footer column.', 'aqualuxe' ),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        ) );
        
        register_sidebar( array(
            'name'          => __( 'Footer Column 3', 'aqualuxe' ),
            'id'            => 'footer-3',
            'description'   => __( 'Widgets in this area will be shown in the third footer column.', 'aqualuxe' ),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        ) );
        
        register_sidebar( array(
            'name'          => __( 'Footer Column 4', 'aqualuxe' ),
            'id'            => 'footer-4',
            'description'   => __( 'Widgets in this area will be shown in the fourth footer column.', 'aqualuxe' ),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        ) );
        
        // Top bar widgets
        register_sidebar( array(
            'name'          => __( 'Top Bar Left', 'aqualuxe' ),
            'id'            => 'top-bar-left',
            'description'   => __( 'Widgets in this area will be shown in the top bar left.', 'aqualuxe' ),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        ) );
        
        register_sidebar( array(
            'name'          => __( 'Top Bar Right', 'aqualuxe' ),
            'id'            => 'top-bar-right',
            'description'   => __( 'Widgets in this area will be shown in the top bar right.', 'aqualuxe' ),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        ) );
    }
    
    /**
     * Add custom image sizes to media library
     */
    public static function custom_image_sizes( $sizes ) {
        return array_merge( $sizes, array(
            'aqualuxe-product-card' => __( 'Product Card', 'aqualuxe' ),
            'aqualuxe-product-gallery' => __( 'Product Gallery', 'aqualuxe' ),
            'aqualuxe-blog-thumbnail' => __( 'Blog Thumbnail', 'aqualuxe' ),
            'aqualuxe-banner' => __( 'Banner', 'aqualuxe' ),
        ) );
    }
}

// Initialize
AquaLuxe_Setup::init();
