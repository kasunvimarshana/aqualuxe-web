<?php
/**
 * AquaLuxe Theme Setup
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Theme Setup Class
 */
class AquaLuxe_Setup {
    /**
     * Constructor
     */
    public function __construct() {
        // Theme setup
        add_action('after_setup_theme', [$this, 'setup_theme']);
        
        // Register sidebars
        add_action('widgets_init', [$this, 'register_sidebars']);
        
        // Register nav menus
        add_action('after_setup_theme', [$this, 'register_nav_menus']);
        
        // Content width
        add_action('after_setup_theme', [$this, 'content_width'], 0);
    }

    /**
     * Setup theme
     */
    public function setup_theme() {
        /*
         * Make theme available for translation.
         * Translations can be filed in the /languages/ directory.
         */
        load_theme_textdomain('aqualuxe', AQUALUXE_DIR . 'languages');

        // Add default posts and comments RSS feed links to head.
        add_theme_support('automatic-feed-links');

        /*
         * Let WordPress manage the document title.
         * By adding theme support, we declare that this theme does not use a
         * hard-coded <title> tag in the document head, and expect WordPress to
         * provide it for us.
         */
        add_theme_support('title-tag');

        /*
         * Enable support for Post Thumbnails on posts and pages.
         *
         * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
         */
        add_theme_support('post-thumbnails');

        // Set up image sizes
        add_image_size('aqualuxe-featured', 1200, 675, true);
        add_image_size('aqualuxe-square', 600, 600, true);
        add_image_size('aqualuxe-portrait', 600, 900, true);
        add_image_size('aqualuxe-gallery', 800, 600, true);

        /*
         * Switch default core markup for search form, comment form, and comments
         * to output valid HTML5.
         */
        add_theme_support('html5', [
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script',
        ]);

        // Add theme support for selective refresh for widgets.
        add_theme_support('customize-selective-refresh-widgets');

        /**
         * Add support for core custom logo.
         *
         * @link https://codex.wordpress.org/Theme_Logo
         */
        add_theme_support('custom-logo', [
            'height'      => 250,
            'width'       => 250,
            'flex-width'  => true,
            'flex-height' => true,
        ]);

        /**
         * Add support for WooCommerce.
         */
        add_theme_support('woocommerce');
        add_theme_support('wc-product-gallery-zoom');
        add_theme_support('wc-product-gallery-lightbox');
        add_theme_support('wc-product-gallery-slider');

        /**
         * Add support for editor styles.
         */
        add_theme_support('editor-styles');
        add_editor_style('assets/dist/css/editor-style.css');

        /**
         * Add support for responsive embeds.
         */
        add_theme_support('responsive-embeds');

        /**
         * Add support for full and wide align images.
         */
        add_theme_support('align-wide');

        /**
         * Add support for custom color palette.
         */
        add_theme_support('editor-color-palette', [
            [
                'name'  => __('Primary', 'aqualuxe'),
                'slug'  => 'primary',
                'color' => '#0073aa',
            ],
            [
                'name'  => __('Secondary', 'aqualuxe'),
                'slug'  => 'secondary',
                'color' => '#23282d',
            ],
            [
                'name'  => __('Accent', 'aqualuxe'),
                'slug'  => 'accent',
                'color' => '#00a0d2',
            ],
            [
                'name'  => __('Dark', 'aqualuxe'),
                'slug'  => 'dark',
                'color' => '#121212',
            ],
            [
                'name'  => __('Light', 'aqualuxe'),
                'slug'  => 'light',
                'color' => '#f8f9fa',
            ],
        ]);
    }

    /**
     * Register sidebars
     */
    public function register_sidebars() {
        register_sidebar([
            'name'          => __('Main Sidebar', 'aqualuxe'),
            'id'            => 'sidebar-main',
            'description'   => __('Add widgets here to appear in your main sidebar.', 'aqualuxe'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        ]);

        register_sidebar([
            'name'          => __('Footer Widget Area 1', 'aqualuxe'),
            'id'            => 'footer-1',
            'description'   => __('Add widgets here to appear in footer area 1.', 'aqualuxe'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h4 class="widget-title">',
            'after_title'   => '</h4>',
        ]);

        register_sidebar([
            'name'          => __('Footer Widget Area 2', 'aqualuxe'),
            'id'            => 'footer-2',
            'description'   => __('Add widgets here to appear in footer area 2.', 'aqualuxe'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h4 class="widget-title">',
            'after_title'   => '</h4>',
        ]);

        register_sidebar([
            'name'          => __('Footer Widget Area 3', 'aqualuxe'),
            'id'            => 'footer-3',
            'description'   => __('Add widgets here to appear in footer area 3.', 'aqualuxe'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h4 class="widget-title">',
            'after_title'   => '</h4>',
        ]);

        register_sidebar([
            'name'          => __('Footer Widget Area 4', 'aqualuxe'),
            'id'            => 'footer-4',
            'description'   => __('Add widgets here to appear in footer area 4.', 'aqualuxe'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h4 class="widget-title">',
            'after_title'   => '</h4>',
        ]);

        // Shop sidebar
        if (aqualuxe_is_woocommerce_active()) {
            register_sidebar([
                'name'          => __('Shop Sidebar', 'aqualuxe'),
                'id'            => 'sidebar-shop',
                'description'   => __('Add widgets here to appear in your shop sidebar.', 'aqualuxe'),
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<h3 class="widget-title">',
                'after_title'   => '</h3>',
            ]);
        }
    }

    /**
     * Register nav menus
     */
    public function register_nav_menus() {
        register_nav_menus([
            'primary'   => __('Primary Menu', 'aqualuxe'),
            'secondary' => __('Secondary Menu', 'aqualuxe'),
            'footer'    => __('Footer Menu', 'aqualuxe'),
            'mobile'    => __('Mobile Menu', 'aqualuxe'),
        ]);

        // Register WooCommerce specific menus if WooCommerce is active
        if (aqualuxe_is_woocommerce_active()) {
            register_nav_menus([
                'shop'      => __('Shop Menu', 'aqualuxe'),
                'wholesale' => __('Wholesale Menu', 'aqualuxe'),
                'account'   => __('Account Menu', 'aqualuxe'),
            ]);
        }
    }

    /**
     * Set the content width in pixels
     */
    public function content_width() {
        $GLOBALS['content_width'] = apply_filters('aqualuxe_content_width', 1200);
    }
}