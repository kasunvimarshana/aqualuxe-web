<?php
/**
 * AquaLuxe Theme Functions
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Define theme constants
 */
define('AQUALUXE_VERSION', '1.0.0');
define('AQUALUXE_DIR', get_stylesheet_directory());
define('AQUALUXE_URI', get_stylesheet_directory_uri());
define('AQUALUXE_ASSETS_URI', AQUALUXE_URI . '/assets');

/**
 * AquaLuxe Theme Class
 * 
 * Main theme class using OOP approach for better organization and maintainability
 */
class AquaLuxe_Theme {
    /**
     * Singleton instance
     *
     * @var AquaLuxe_Theme
     */
    private static $instance = null;

    /**
     * Get singleton instance
     *
     * @return AquaLuxe_Theme
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        $this->includes();
        $this->init_hooks();
    }

    /**
     * Include required files
     */
    private function includes() {
        // Include core files
        require_once AQUALUXE_DIR . '/inc/class-aqualuxe-assets.php';
        require_once AQUALUXE_DIR . '/inc/class-aqualuxe-customizer.php';
        require_once AQUALUXE_DIR . '/inc/class-aqualuxe-woocommerce.php';
        require_once AQUALUXE_DIR . '/inc/class-aqualuxe-hooks.php';
        require_once AQUALUXE_DIR . '/inc/class-aqualuxe-widgets.php';
        require_once AQUALUXE_DIR . '/inc/class-aqualuxe-seo.php';
        require_once AQUALUXE_DIR . '/inc/class-aqualuxe-demo-content.php';
        require_once AQUALUXE_DIR . '/inc/class-aqualuxe-fish-cpt.php';
        require_once AQUALUXE_DIR . '/inc/class-aqualuxe-water-calculator.php';
        require_once AQUALUXE_DIR . '/inc/class-aqualuxe-compatibility-checker.php';
        require_once AQUALUXE_DIR . '/inc/class-aqualuxe-cache.php';
        require_once AQUALUXE_DIR . '/inc/class-aqualuxe-query-optimization.php';
        require_once AQUALUXE_DIR . '/inc/class-aqualuxe-shortcodes.php';
        require_once AQUALUXE_DIR . '/inc/template-functions.php';
        require_once AQUALUXE_DIR . '/inc/template-hooks.php';
    }

    /**
     * Initialize hooks
     */
    private function init_hooks() {
        // Theme setup
        add_action('after_setup_theme', array($this, 'theme_setup'));
        
        // Register sidebars
        add_action('widgets_init', array($this, 'register_sidebars'));
        
        // Initialize classes
        add_action('after_setup_theme', array($this, 'init_classes'));
    }

    /**
     * Theme setup
     */
    public function theme_setup() {
        // Add theme support
        add_theme_support('title-tag');
        add_theme_support('post-thumbnails');
        add_theme_support('automatic-feed-links');
        add_theme_support('html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script',
        ));
        add_theme_support('custom-logo', array(
            'height'      => 100,
            'width'       => 350,
            'flex-width'  => true,
            'flex-height' => true,
        ));
        add_theme_support('custom-header');
        add_theme_support('custom-background');
        add_theme_support('woocommerce');
        add_theme_support('wc-product-gallery-zoom');
        add_theme_support('wc-product-gallery-lightbox');
        add_theme_support('wc-product-gallery-slider');
        
        // Register nav menus
        register_nav_menus(array(
            'primary'   => esc_html__('Primary Menu', 'aqualuxe'),
            'secondary' => esc_html__('Secondary Menu', 'aqualuxe'),
            'footer'    => esc_html__('Footer Menu', 'aqualuxe'),
        ));
        
        // Set content width
        if (!isset($content_width)) {
            $content_width = 1140;
        }
        
        // Load translations
        load_child_theme_textdomain('aqualuxe', AQUALUXE_DIR . '/languages');
    }

    /**
     * Register sidebars
     */
    public function register_sidebars() {
        register_sidebar(array(
            'name'          => esc_html__('Sidebar', 'aqualuxe'),
            'id'            => 'sidebar-1',
            'description'   => esc_html__('Add widgets here to appear in your sidebar.', 'aqualuxe'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        ));
        
        register_sidebar(array(
            'name'          => esc_html__('Footer Column 1', 'aqualuxe'),
            'id'            => 'footer-1',
            'description'   => esc_html__('Add widgets here to appear in footer column 1.', 'aqualuxe'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        ));
        
        register_sidebar(array(
            'name'          => esc_html__('Footer Column 2', 'aqualuxe'),
            'id'            => 'footer-2',
            'description'   => esc_html__('Add widgets here to appear in footer column 2.', 'aqualuxe'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        ));
        
        register_sidebar(array(
            'name'          => esc_html__('Footer Column 3', 'aqualuxe'),
            'id'            => 'footer-3',
            'description'   => esc_html__('Add widgets here to appear in footer column 3.', 'aqualuxe'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        ));
        
        register_sidebar(array(
            'name'          => esc_html__('Footer Column 4', 'aqualuxe'),
            'id'            => 'footer-4',
            'description'   => esc_html__('Add widgets here to appear in footer column 4.', 'aqualuxe'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        ));
        
        register_sidebar(array(
            'name'          => esc_html__('Shop Sidebar', 'aqualuxe'),
            'id'            => 'shop-sidebar',
            'description'   => esc_html__('Add widgets here to appear in shop sidebar.', 'aqualuxe'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        ));
    }

    /**
     * Initialize classes
     */
    public function init_classes() {
        // Initialize assets
        AquaLuxe_Assets::get_instance();
        
        // Initialize customizer
        AquaLuxe_Customizer::get_instance();
        
        // Initialize WooCommerce customizations
        AquaLuxe_WooCommerce::get_instance();
        
        // Initialize hooks
        AquaLuxe_Hooks::get_instance();
        
        // Initialize widgets
        AquaLuxe_Widgets::get_instance();
        
        // Initialize SEO
        AquaLuxe_SEO::get_instance();
        
        // Initialize demo content
        AquaLuxe_Demo_Content::get_instance();
        
        // Initialize fish custom post types and taxonomies
        AquaLuxe_Fish_CPT::get_instance();
        
        // Initialize water parameter calculator
        AquaLuxe_Water_Calculator::get_instance();
        
        // Initialize fish compatibility checker
        AquaLuxe_Compatibility_Checker::get_instance();
        
        // Initialize cache system
        AquaLuxe_Cache::get_instance();
        
        // Initialize query optimization
        AquaLuxe_Query_Optimization::get_instance();
        
        // Initialize shortcodes
        AquaLuxe_Shortcodes::get_instance();
    }
}

// Initialize the theme
AquaLuxe_Theme::get_instance();

/**
 * Compatibility function for older PHP versions
 * Polyfill for is_countable() function added in PHP 7.3
 */
if (!function_exists('is_countable')) {
    function is_countable($var) {
        return (is_array($var) || $var instanceof Countable);
    }
}