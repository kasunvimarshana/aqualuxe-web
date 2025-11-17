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
define('AQUALUXE_VERSION', '1.0.0');
define('AQUALUXE_DIR', trailingslashit(get_template_directory()));
define('AQUALUXE_URI', trailingslashit(get_template_directory_uri()));

// Prevent direct access to this file
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AquaLuxe Theme Class
 * 
 * Main class for the theme that handles all functionality
 */
final class AquaLuxe_Theme {
    /**
     * Instance
     * 
     * @var AquaLuxe_Theme The single instance of the class.
     */
    private static $_instance = null;

    /**
     * Main AquaLuxe_Theme Instance
     * 
     * Ensures only one instance of AquaLuxe_Theme is loaded or can be loaded.
     * 
     * @return AquaLuxe_Theme - Main instance
     */
    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->includes();
        $this->init_hooks();
    }

    /**
     * Include required files
     */
    private function includes() {
        // Core functionality
        require_once AQUALUXE_DIR . 'inc/class-aqualuxe-setup.php';
        require_once AQUALUXE_DIR . 'inc/class-aqualuxe-assets.php';
        require_once AQUALUXE_DIR . 'inc/class-aqualuxe-customizer.php';
        require_once AQUALUXE_DIR . 'inc/class-aqualuxe-template-hooks.php';
        require_once AQUALUXE_DIR . 'inc/class-aqualuxe-template-functions.php';
        
        // Custom post types and taxonomies
        require_once AQUALUXE_DIR . 'inc/class-aqualuxe-post-types.php';
        
        // WooCommerce integration
        if (class_exists('WooCommerce')) {
            require_once AQUALUXE_DIR . 'inc/woocommerce/class-aqualuxe-woocommerce.php';
        }
        
        // Admin functionality
        if (is_admin()) {
            require_once AQUALUXE_DIR . 'inc/admin/class-aqualuxe-admin.php';
        }
    }

    /**
     * Initialize hooks
     */
    private function init_hooks() {
        // Theme setup
        add_action('after_setup_theme', array($this, 'setup'));
        
        // Register sidebars
        add_action('widgets_init', array($this, 'widgets_init'));
        
        // Register menus
        add_action('init', array($this, 'register_menus'));
    }

    /**
     * Theme setup
     */
    public function setup() {
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

        // Set up the WordPress core custom background feature.
        add_theme_support('custom-background', apply_filters('aqualuxe_custom_background_args', array(
            'default-color' => 'ffffff',
            'default-image' => '',
        )));

        /*
         * Switch default core markup for search form, comment form, and comments
         * to output valid HTML5.
         */
        add_theme_support('html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
        ));

        // Add theme support for selective refresh for widgets.
        add_theme_support('customize-selective-refresh-widgets');

        /**
         * Add support for core custom logo.
         *
         * @link https://codex.wordpress.org/Theme_Logo
         */
        add_theme_support('custom-logo', array(
            'height'      => 250,
            'width'       => 250,
            'flex-width'  => true,
            'flex-height' => true,
        ));

        // WooCommerce support
        add_theme_support('woocommerce');
        add_theme_support('wc-product-gallery-zoom');
        add_theme_support('wc-product-gallery-lightbox');
        add_theme_support('wc-product-gallery-slider');
        
        // Add image sizes
        add_image_size('aqualuxe-featured', 1200, 600, true);
        add_image_size('aqualuxe-square', 600, 600, true);
        add_image_size('aqualuxe-portrait', 600, 900, true);
    }

    /**
     * Register widget areas
     */
    public function widgets_init() {
        register_sidebar(array(
            'name'          => esc_html__('Sidebar', 'aqualuxe'),
            'id'            => 'sidebar-1',
            'description'   => esc_html__('Add widgets here.', 'aqualuxe'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        ));
        
        register_sidebar(array(
            'name'          => esc_html__('Footer 1', 'aqualuxe'),
            'id'            => 'footer-1',
            'description'   => esc_html__('First footer widget area', 'aqualuxe'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        ));
        
        register_sidebar(array(
            'name'          => esc_html__('Footer 2', 'aqualuxe'),
            'id'            => 'footer-2',
            'description'   => esc_html__('Second footer widget area', 'aqualuxe'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        ));
        
        register_sidebar(array(
            'name'          => esc_html__('Footer 3', 'aqualuxe'),
            'id'            => 'footer-3',
            'description'   => esc_html__('Third footer widget area', 'aqualuxe'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        ));
        
        register_sidebar(array(
            'name'          => esc_html__('Footer 4', 'aqualuxe'),
            'id'            => 'footer-4',
            'description'   => esc_html__('Fourth footer widget area', 'aqualuxe'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        ));
        
        // Shop sidebar
        if (class_exists('WooCommerce')) {
            register_sidebar(array(
                'name'          => esc_html__('Shop Sidebar', 'aqualuxe'),
                'id'            => 'shop-sidebar',
                'description'   => esc_html__('Add widgets here for the shop sidebar.', 'aqualuxe'),
                'before_widget' => '<section id="%1$s" class="widget %2$s">',
                'after_widget'  => '</section>',
                'before_title'  => '<h2 class="widget-title">',
                'after_title'   => '</h2>',
            ));
        }
    }

    /**
     * Register navigation menus
     */
    public function register_menus() {
        register_nav_menus(array(
            'primary'   => esc_html__('Primary Menu', 'aqualuxe'),
            'secondary' => esc_html__('Secondary Menu', 'aqualuxe'),
            'footer'    => esc_html__('Footer Menu', 'aqualuxe'),
            'mobile'    => esc_html__('Mobile Menu', 'aqualuxe'),
        ));
    }
}

// Initialize the theme
AquaLuxe_Theme::instance();

/**
 * Helper function to get theme options
 */
function aqualuxe_get_option($option_name, $default = '') {
    $options = get_option('aqualuxe_options');
    return isset($options[$option_name]) ? $options[$option_name] : $default;
}