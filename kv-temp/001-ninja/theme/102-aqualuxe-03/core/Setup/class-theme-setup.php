<?php
/**
 * Theme Setup Class
 *
 * Handles theme initialization and configuration.
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Theme Setup Class
 */
class AquaLuxe_Theme_Setup {
    
    /**
     * Constructor
     */
    public function __construct() {
        add_action('after_setup_theme', array($this, 'setup'));
        add_action('widgets_init', array($this, 'widgets_init'));
        add_action('init', array($this, 'content_width'), 0);
    }
    
    /**
     * Theme setup
     */
    public function setup() {
        // Make theme available for translation
        load_theme_textdomain('aqualuxe', get_template_directory() . '/languages');
        
        // Add default posts and comments RSS feed links to head
        add_theme_support('automatic-feed-links');
        
        // Let WordPress manage the document title
        add_theme_support('title-tag');
        
        // Enable support for Post Thumbnails on posts and pages
        add_theme_support('post-thumbnails');
        
        // Switch default core markup for search form, comment form, and comments
        // to output valid HTML5
        add_theme_support('html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script',
        ));
        
        // Set up the WordPress core custom background feature
        add_theme_support('custom-background', apply_filters('aqualuxe_custom_background_args', array(
            'default-color' => 'ffffff',
            'default-image' => '',
        )));
        
        // Add theme support for selective refresh for widgets
        add_theme_support('customize-selective-refresh-widgets');
        
        // Add support for core custom logo
        add_theme_support('custom-logo', array(
            'height'      => 250,
            'width'       => 250,
            'flex-width'  => true,
            'flex-height' => true,
        ));
        
        // Add support for editor styles
        add_theme_support('editor-styles');
        
        // Add support for responsive embeds
        add_theme_support('responsive-embeds');
        
        // Add support for block styles
        add_theme_support('wp-block-styles');
        
        // Add support for full and wide align images
        add_theme_support('align-wide');
        
        // Custom image sizes
        add_image_size('aqualuxe-hero-large', 1920, 1080, true);
        add_image_size('aqualuxe-hero-medium', 1200, 675, true);
        add_image_size('aqualuxe-featured-large', 800, 600, true);
        add_image_size('aqualuxe-featured-medium', 600, 450, true);
        add_image_size('aqualuxe-thumbnail-large', 400, 300, true);
        add_image_size('aqualuxe-gallery', 500, 500, true);
        add_image_size('aqualuxe-product-single', 800, 800, true);
        add_image_size('aqualuxe-product-gallery', 600, 600, true);
        add_image_size('aqualuxe-product-thumbnail', 300, 300, true);
    }
    
    /**
     * Register widget area
     */
    public function widgets_init() {
        // Primary sidebar
        register_sidebar(array(
            'name'          => esc_html__('Primary Sidebar', 'aqualuxe'),
            'id'            => 'sidebar-1',
            'description'   => esc_html__('Add widgets here.', 'aqualuxe'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        ));
        
        // Secondary sidebar
        register_sidebar(array(
            'name'          => esc_html__('Secondary Sidebar', 'aqualuxe'),
            'id'            => 'sidebar-2',
            'description'   => esc_html__('Secondary sidebar for additional widgets.', 'aqualuxe'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        ));
        
        // Shop sidebar (WooCommerce)
        if (class_exists('WooCommerce')) {
            register_sidebar(array(
                'name'          => esc_html__('Shop Sidebar', 'aqualuxe'),
                'id'            => 'shop-sidebar',
                'description'   => esc_html__('Sidebar for shop pages.', 'aqualuxe'),
                'before_widget' => '<section id="%1$s" class="widget %2$s">',
                'after_widget'  => '</section>',
                'before_title'  => '<h2 class="widget-title">',
                'after_title'   => '</h2>',
            ));
        }
        
        // Footer widgets
        for ($i = 1; $i <= 4; $i++) {
            register_sidebar(array(
                'name'          => sprintf(esc_html__('Footer %d', 'aqualuxe'), $i),
                'id'            => 'footer-' . $i,
                'description'   => sprintf(esc_html__('Footer widget area %d.', 'aqualuxe'), $i),
                'before_widget' => '<section id="%1$s" class="widget %2$s">',
                'after_widget'  => '</section>',
                'before_title'  => '<h3 class="widget-title">',
                'after_title'   => '</h3>',
            ));
        }
        
        // Header widgets
        register_sidebar(array(
            'name'          => esc_html__('Header Widget Area', 'aqualuxe'),
            'id'            => 'header-widgets',
            'description'   => esc_html__('Widget area in the header.', 'aqualuxe'),
            'before_widget' => '<div id="%1$s" class="header-widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="header-widget-title">',
            'after_title'   => '</h3>',
        ));
        
        // Homepage widgets
        register_sidebar(array(
            'name'          => esc_html__('Homepage Widget Area', 'aqualuxe'),
            'id'            => 'homepage-widgets',
            'description'   => esc_html__('Widget area for the homepage.', 'aqualuxe'),
            'before_widget' => '<section id="%1$s" class="homepage-widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="homepage-widget-title">',
            'after_title'   => '</h2>',
        ));
    }
    
    /**
     * Set the content width in pixels, based on the theme's design and stylesheet
     */
    public function content_width() {
        $GLOBALS['content_width'] = apply_filters('aqualuxe_content_width', 1200);
    }
    
    /**
     * Register navigation menus
     */
    public static function register_nav_menus() {
        register_nav_menus(array(
            'primary'   => esc_html__('Primary Menu', 'aqualuxe'),
            'secondary' => esc_html__('Secondary Menu', 'aqualuxe'),
            'footer'    => esc_html__('Footer Menu', 'aqualuxe'),
            'mobile'    => esc_html__('Mobile Menu', 'aqualuxe'),
            'social'    => esc_html__('Social Menu', 'aqualuxe'),
        ));
    }
    
    /**
     * Add editor styles
     */
    public static function add_editor_styles() {
        add_editor_style(array(
            'assets/dist/css/editor-style.css',
            aqualuxe_fonts_url(),
        ));
    }
    
    /**
     * Configure custom header
     */
    public static function custom_header_setup() {
        add_theme_support('custom-header', apply_filters('aqualuxe_custom_header_args', array(
            'default-image'          => '',
            'default-text-color'     => '000000',
            'width'                  => 1920,
            'height'                 => 1080,
            'flex-height'            => true,
            'wp-head-callback'       => 'aqualuxe_header_style',
        )));
    }
}

// Initialize theme setup
new AquaLuxe_Theme_Setup();