<?php
/**
 * Theme setup and initialization
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AquaLuxe Setup Class
 */
class AquaLuxe_Setup {
    /**
     * Constructor
     */
    public function __construct() {
        // Theme setup
        add_action('after_setup_theme', array($this, 'setup_theme'));
        
        // Register menus
        add_action('after_setup_theme', array($this, 'register_menus'));
        
        // Add theme support
        add_action('after_setup_theme', array($this, 'add_theme_support'));
        
        // Register sidebars
        add_action('widgets_init', array($this, 'register_sidebars'));
        
        // Add body classes
        add_filter('body_class', array($this, 'body_classes'));
        
        // Add pingback header
        add_action('wp_head', array($this, 'pingback_header'));
    }

    /**
     * Sets up theme defaults and registers support for various WordPress features.
     */
    public function setup_theme() {
        /*
         * Make theme available for translation.
         * Translations can be filed in the /languages/ directory.
         */
        load_theme_textdomain('aqualuxe', get_template_directory() . '/languages');

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

        // Set default thumbnail size
        set_post_thumbnail_size(1200, 9999);

        // Add custom image sizes
        add_image_size('aqualuxe-featured', 1200, 600, true);
        add_image_size('aqualuxe-product', 600, 600, true);
        add_image_size('aqualuxe-thumbnail', 350, 250, true);
    }

    /**
     * Register navigation menus
     */
    public function register_menus() {
        register_nav_menus(
            array(
                'primary' => esc_html__('Primary Menu', 'aqualuxe'),
                'footer' => esc_html__('Footer Menu', 'aqualuxe'),
                'mobile' => esc_html__('Mobile Menu', 'aqualuxe'),
            )
        );
    }

    /**
     * Add theme support
     */
    public function add_theme_support() {
        /*
         * Switch default core markup for search form, comment form, and comments
         * to output valid HTML5.
         */
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

        // Add theme support for selective refresh for widgets.
        add_theme_support('customize-selective-refresh-widgets');

        // Add support for Block Styles.
        add_theme_support('wp-block-styles');

        // Add support for full and wide align images.
        add_theme_support('align-wide');

        // Add support for responsive embedded content.
        add_theme_support('responsive-embeds');

        // Add support for custom line height controls.
        add_theme_support('custom-line-height');

        // Add support for custom units.
        add_theme_support('custom-units');

        // Add support for experimental link color control.
        add_theme_support('experimental-link-color');

        // Add support for custom logo
        add_theme_support(
            'custom-logo',
            array(
                'height' => 100,
                'width' => 350,
                'flex-width' => true,
                'flex-height' => true,
            )
        );

        // Add support for WooCommerce
        add_theme_support('woocommerce');
        add_theme_support('wc-product-gallery-zoom');
        add_theme_support('wc-product-gallery-lightbox');
        add_theme_support('wc-product-gallery-slider');
    }

    /**
     * Register widget areas
     */
    public function register_sidebars() {
        // Main sidebar
        register_sidebar(
            array(
                'name' => esc_html__('Sidebar', 'aqualuxe'),
                'id' => 'sidebar-1',
                'description' => esc_html__('Add widgets here to appear in your sidebar.', 'aqualuxe'),
                'before_widget' => '<div id="%1$s" class="widget %2$s mb-8">',
                'after_widget' => '</div>',
                'before_title' => '<h3 class="widget-title text-lg font-bold mb-4">',
                'after_title' => '</h3>',
            )
        );

        // Footer widgets
        for ($i = 1; $i <= 4; $i++) {
            register_sidebar(
                array(
                    'name' => sprintf(esc_html__('Footer %d', 'aqualuxe'), $i),
                    'id' => 'footer-' . $i,
                    'description' => esc_html__('Add widgets here to appear in your footer.', 'aqualuxe'),
                    'before_widget' => '<div id="%1$s" class="widget %2$s mb-6">',
                    'after_widget' => '</div>',
                    'before_title' => '<h3 class="widget-title text-lg font-bold mb-4">',
                    'after_title' => '</h3>',
                )
            );
        }

        // Shop sidebar
        register_sidebar(
            array(
                'name' => esc_html__('Shop Sidebar', 'aqualuxe'),
                'id' => 'shop-sidebar',
                'description' => esc_html__('Add widgets here to appear in your shop sidebar.', 'aqualuxe'),
                'before_widget' => '<div id="%1$s" class="widget %2$s mb-8">',
                'after_widget' => '</div>',
                'before_title' => '<h3 class="widget-title text-lg font-bold mb-4">',
                'after_title' => '</h3>',
            )
        );
    }

    /**
     * Adds custom classes to the array of body classes.
     *
     * @param array $classes Classes for the body element.
     * @return array
     */
    public function body_classes($classes) {
        // Add a class if there is a custom header.
        if (has_header_image()) {
            $classes[] = 'has-header-image';
        }

        // Add a class if there is a custom background.
        if (get_background_image()) {
            $classes[] = 'has-background-image';
        }

        // Add a class if the site is using a sidebar.
        if (is_active_sidebar('sidebar-1') && !is_page_template('templates/template-full-width.php')) {
            $classes[] = 'has-sidebar';
        }

        return $classes;
    }

    /**
     * Add a pingback url auto-discovery header for single posts, pages, or attachments.
     */
    public function pingback_header() {
        if (is_singular() && pings_open()) {
            printf('<link rel="pingback" href="%s">', esc_url(get_bloginfo('pingback_url')));
        }
    }
}

// Initialize the class
new AquaLuxe_Setup();