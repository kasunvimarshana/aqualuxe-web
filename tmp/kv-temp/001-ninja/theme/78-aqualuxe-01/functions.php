<?php
/**
 * AquaLuxe functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define theme constants
define('AQUALUXE_VERSION', '1.0.0');
define('AQUALUXE_THEME_DIR', get_template_directory());
define('AQUALUXE_THEME_URI', get_template_directory_uri());
define('AQUALUXE_ASSETS_DIR', AQUALUXE_THEME_DIR . '/assets');
define('AQUALUXE_ASSETS_URI', AQUALUXE_THEME_URI . '/assets');
define('AQUALUXE_CORE_DIR', AQUALUXE_THEME_DIR . '/core');
define('AQUALUXE_MODULES_DIR', AQUALUXE_THEME_DIR . '/modules');

/**
 * AquaLuxe Theme Class
 */
class AquaLuxe_Theme {
    
    /**
     * Instance of this class
     */
    private static $instance = null;
    
    /**
     * Get instance
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
        $this->load_core();
        $this->load_modules();
        $this->init_hooks();
    }
    
    /**
     * Load core files
     */
    private function load_core() {
        $core_files = [
            'class-assets.php',
            'class-theme-setup.php',
            'class-customizer.php',
            'class-walker-nav-menu.php',
            'class-woocommerce.php',
            'class-demo-importer.php',
            'functions-helpers.php',
            'functions-template.php',
        ];
        
        foreach ($core_files as $file) {
            $file_path = AQUALUXE_CORE_DIR . '/' . $file;
            if (file_exists($file_path)) {
                require_once $file_path;
            }
        }
    }
    
    /**
     * Load modules
     */
    private function load_modules() {
        $modules = [
            'multilingual',
            'dark-mode',
            'performance',
            'seo',
            'security',
            'accessibility',
            'booking',
            'events',
            'subscriptions',
            'franchise',
            'wholesale',
            'auctions',
            'affiliates',
        ];
        
        foreach ($modules as $module) {
            $module_file = AQUALUXE_MODULES_DIR . '/' . $module . '/class-' . $module . '.php';
            if (file_exists($module_file) && $this->is_module_enabled($module)) {
                require_once $module_file;
            }
        }
    }
    
    /**
     * Check if module is enabled
     */
    private function is_module_enabled($module) {
        return get_theme_mod('aqualuxe_module_' . str_replace('-', '_', $module), true);
    }
    
    /**
     * Initialize hooks
     */
    private function init_hooks() {
        add_action('after_setup_theme', [$this, 'theme_setup']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
        add_action('admin_enqueue_scripts', [$this, 'admin_enqueue_scripts']);
        add_action('customize_preview_init', [$this, 'customizer_preview_scripts']);
        add_action('widgets_init', [$this, 'register_sidebars']);
        add_filter('body_class', [$this, 'body_classes']);
    }
    
    /**
     * Theme setup
     */
    public function theme_setup() {
        // Make theme available for translation
        load_theme_textdomain('aqualuxe', AQUALUXE_THEME_DIR . '/languages');
        
        // Add default posts and comments RSS feed links to head
        add_theme_support('automatic-feed-links');
        
        // Let WordPress manage the document title
        add_theme_support('title-tag');
        
        // Enable support for Post Thumbnails on posts and pages
        add_theme_support('post-thumbnails');
        
        // Set default thumbnail size
        set_post_thumbnail_size(1200, 800, true);
        
        // Add additional image sizes
        add_image_size('aqualuxe-hero', 1920, 1080, true);
        add_image_size('aqualuxe-featured', 800, 600, true);
        add_image_size('aqualuxe-product', 600, 600, true);
        add_image_size('aqualuxe-gallery', 400, 300, true);
        add_image_size('aqualuxe-thumbnail', 300, 300, true);
        
        // Register navigation menus
        register_nav_menus([
            'primary' => esc_html__('Primary Menu', 'aqualuxe'),
            'footer' => esc_html__('Footer Menu', 'aqualuxe'),
            'mobile' => esc_html__('Mobile Menu', 'aqualuxe'),
            'top' => esc_html__('Top Bar Menu', 'aqualuxe'),
        ]);
        
        // Switch default core markup for search form, comment form, and comments to output valid HTML5
        add_theme_support('html5', [
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
        ]);
        
        // Add theme support for selective refresh for widgets
        add_theme_support('customize-selective-refresh-widgets');
        
        // Add support for core custom logo
        add_theme_support('custom-logo', [
            'height' => 100,
            'width' => 300,
            'flex-width' => true,
            'flex-height' => true,
        ]);
        
        // Add support for custom header
        add_theme_support('custom-header', [
            'default-image' => '',
            'width' => 1920,
            'height' => 1080,
            'flex-height' => true,
            'flex-width' => true,
        ]);
        
        // Add support for custom background
        add_theme_support('custom-background', [
            'default-color' => 'ffffff',
        ]);
        
        // Add support for wide and full alignment
        add_theme_support('align-wide');
        
        // Add support for editor styles
        add_theme_support('editor-styles');
        add_editor_style('assets/dist/css/editor.css');
        
        // Add support for responsive embeds
        add_theme_support('responsive-embeds');
        
        // WooCommerce support
        add_theme_support('woocommerce');
        add_theme_support('wc-product-gallery-zoom');
        add_theme_support('wc-product-gallery-lightbox');
        add_theme_support('wc-product-gallery-slider');
    }
    
    /**
     * Enqueue scripts and styles
     */
    public function enqueue_scripts() {
        AquaLuxe_Assets::enqueue_frontend_assets();
    }
    
    /**
     * Enqueue admin scripts and styles
     */
    public function admin_enqueue_scripts() {
        AquaLuxe_Assets::enqueue_admin_assets();
    }
    
    /**
     * Enqueue customizer preview scripts
     */
    public function customizer_preview_scripts() {
        AquaLuxe_Assets::enqueue_customizer_assets();
    }
    
    /**
     * Register widget areas
     */
    public function register_sidebars() {
        // Main sidebar
        register_sidebar([
            'name' => esc_html__('Primary Sidebar', 'aqualuxe'),
            'id' => 'sidebar-1',
            'description' => esc_html__('Add widgets here to appear in your sidebar.', 'aqualuxe'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget' => '</section>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ]);
        
        // Shop sidebar
        register_sidebar([
            'name' => esc_html__('Shop Sidebar', 'aqualuxe'),
            'id' => 'sidebar-shop',
            'description' => esc_html__('Add widgets here to appear in the shop sidebar.', 'aqualuxe'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget' => '</section>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ]);
        
        // Footer widgets
        for ($i = 1; $i <= 4; $i++) {
            register_sidebar([
                'name' => sprintf(esc_html__('Footer Widget %d', 'aqualuxe'), $i),
                'id' => 'footer-' . $i,
                'description' => sprintf(esc_html__('Add widgets here to appear in footer column %d.', 'aqualuxe'), $i),
                'before_widget' => '<section id="%1$s" class="widget %2$s">',
                'after_widget' => '</section>',
                'before_title' => '<h3 class="widget-title">',
                'after_title' => '</h3>',
            ]);
        }
    }
    
    /**
     * Add custom classes to body
     */
    public function body_classes($classes) {
        // Add class for WooCommerce status
        if (class_exists('WooCommerce')) {
            $classes[] = 'woocommerce-enabled';
        } else {
            $classes[] = 'woocommerce-disabled';
        }
        
        // Add dark mode class
        if (get_theme_mod('aqualuxe_dark_mode_enabled', false)) {
            $classes[] = 'dark-mode-enabled';
        }
        
        // Add mobile detection class
        if (wp_is_mobile()) {
            $classes[] = 'is-mobile';
        }
        
        return $classes;
    }
}

// Initialize theme
AquaLuxe_Theme::get_instance();

/**
 * Helper function to get theme instance
 */
function aqualuxe() {
    return AquaLuxe_Theme::get_instance();
}
