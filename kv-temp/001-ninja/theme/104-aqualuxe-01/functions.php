<?php
/**
 * AquaLuxe Theme Functions
 * 
 * Main theme functionality and initialization
 * 
 * @package AquaLuxe
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define theme constants
define('AQUALUXE_VERSION', '1.0.0');
define('AQUALUXE_THEME_DIR', get_template_directory());
define('AQUALUXE_THEME_URI', get_template_directory_uri());
define('AQUALUXE_INC_DIR', AQUALUXE_THEME_DIR . '/inc');
define('AQUALUXE_CORE_DIR', AQUALUXE_THEME_DIR . '/core');
define('AQUALUXE_MODULES_DIR', AQUALUXE_THEME_DIR . '/modules');
define('AQUALUXE_ASSETS_URI', AQUALUXE_THEME_URI . '/assets/dist');

/**
 * Theme autoloader
 */
require_once AQUALUXE_INC_DIR . '/autoloader.php';

/**
 * Template parts helpers
 */
require_once AQUALUXE_INC_DIR . '/template-parts-helpers.php';

/**
 * Core theme class
 */
class AquaLuxe_Theme {
    
    /**
     * Theme instance
     */
    private static $instance = null;
    
    /**
     * Module registry
     */
    private $modules = [];
    
    /**
     * Get theme instance
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
        $this->init();
    }
    
    /**
     * Initialize theme
     */
    private function init() {
        // Load core framework
        $this->load_core();
        
        // Load modules
        $this->load_modules();
        
        // Setup theme hooks
        $this->setup_hooks();
    }
    
    /**
     * Load core framework files
     */
    private function load_core() {
        $core_files = [
            'class-asset-manager.php',
            'class-template-loader.php',
            'class-theme-setup.php',
            'class-customizer.php',
            'class-security.php',
            'class-performance.php',
            'class-seo.php',
        ];
        
        foreach ($core_files as $file) {
            $file_path = AQUALUXE_CORE_DIR . '/' . $file;
            if (file_exists($file_path)) {
                require_once $file_path;
            }
        }
    }
    
    /**
     * Load theme modules
     */
    private function load_modules() {
        // Core essential modules
        $core_modules = [
            'multilingual',
            'dark-mode',
            'woocommerce',
            'custom-post-types',
            'demo-importer'
        ];
        
        // Optional feature modules (can be enabled via admin settings)
        $optional_modules = [
            'bookings',
            'events',
            'subscriptions',
            'auctions',
            'wholesale',
            'franchise',
            'sustainability',
            'affiliates',
            'services',
            'multivendor'
        ];
        
        // Load core modules
        foreach ($core_modules as $module) {
            $this->load_module($module);
        }
        
        // Load optional modules if enabled
        foreach ($optional_modules as $module) {
            if ($this->is_module_enabled($module)) {
                $this->load_module($module);
            }
        }
    }
    
    /**
     * Load individual module
     */
    private function load_module($module_name) {
        $module_path = AQUALUXE_MODULES_DIR . '/' . $module_name;
        $module_file = $module_path . '/class-' . $module_name . '-module.php';
        
        if (file_exists($module_file)) {
            require_once $module_file;
            
            // Initialize module if class exists
            $class_name = 'AquaLuxe_' . str_replace('-', '_', ucwords($module_name, '-')) . '_Module';
            if (class_exists($class_name)) {
                $this->modules[$module_name] = new $class_name();
            }
        }
    }
    
    /**
     * Setup theme hooks
     */
    private function setup_hooks() {
        add_action('after_setup_theme', [$this, 'theme_setup']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);
        add_action('customize_register', [$this, 'customize_register']);
        add_action('widgets_init', [$this, 'widgets_init']);
        add_action('init', [$this, 'load_textdomain']);
    }
    
    /**
     * Theme setup
     */
    public function theme_setup() {
        // Theme supports
        add_theme_support('post-thumbnails');
        add_theme_support('title-tag');
        add_theme_support('custom-logo');
        add_theme_support('custom-background');
        add_theme_support('customize-selective-refresh-widgets');
        add_theme_support('html5', [
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script'
        ]);
        add_theme_support('responsive-embeds');
        add_theme_support('align-wide');
        add_theme_support('editor-styles');
        
        // WooCommerce support
        add_theme_support('woocommerce');
        add_theme_support('wc-product-gallery-zoom');
        add_theme_support('wc-product-gallery-lightbox');
        add_theme_support('wc-product-gallery-slider');
        
        // Navigation menus
        register_nav_menus([
            'primary' => esc_html__('Primary Menu', 'aqualuxe'),
            'footer' => esc_html__('Footer Menu', 'aqualuxe'),
            'mobile' => esc_html__('Mobile Menu', 'aqualuxe'),
        ]);
        
        // Image sizes
        add_image_size('aqualuxe-hero', 1920, 1080, true);
        add_image_size('aqualuxe-featured', 800, 600, true);
        add_image_size('aqualuxe-product', 600, 600, true);
        add_image_size('aqualuxe-thumbnail', 300, 300, true);
    }
    
    /**
     * Enqueue frontend assets
     */
    public function enqueue_assets() {
        $asset_manager = new AquaLuxe_Asset_Manager();
        $asset_manager->enqueue_frontend_assets();
    }
    
    /**
     * Enqueue admin assets
     */
    public function enqueue_admin_assets($hook) {
        $asset_manager = new AquaLuxe_Asset_Manager();
        $asset_manager->enqueue_admin_assets($hook);
    }
    
    /**
     * Customizer setup
     */
    public function customize_register($wp_customize) {
        $customizer = new AquaLuxe_Customizer();
        $customizer->register($wp_customize);
    }
    
    /**
     * Register widgets
     */
    public function widgets_init() {
        register_sidebar([
            'name' => esc_html__('Primary Sidebar', 'aqualuxe'),
            'id' => 'sidebar-primary',
            'description' => esc_html__('Main sidebar area', 'aqualuxe'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget' => '</section>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ]);
        
        register_sidebar([
            'name' => esc_html__('Footer Widgets', 'aqualuxe'),
            'id' => 'sidebar-footer',
            'description' => esc_html__('Footer widget area', 'aqualuxe'),
            'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h4 class="footer-widget-title">',
            'after_title' => '</h4>',
        ]);
    }
    
    /**
     * Load theme textdomain
     */
    public function load_textdomain() {
        load_theme_textdomain('aqualuxe', AQUALUXE_THEME_DIR . '/languages');
    }
    
    /**
     * Get module instance
     */
    public function get_module($module_name) {
        return isset($this->modules[$module_name]) ? $this->modules[$module_name] : null;
    }
    
    /**
     * Check if module is enabled in settings
     */
    private function is_module_enabled($module_name) {
        $enabled_modules = get_option('aqualuxe_enabled_modules', []);
        
        // Default enabled modules if option doesn't exist
        if (empty($enabled_modules)) {
            $default_enabled = ['bookings', 'events', 'services'];
            return in_array($module_name, $default_enabled);
        }
        
        return in_array($module_name, $enabled_modules);
    }
    
    /**
     * Check if module is active
     */
    public function is_module_active($module_name) {
        return isset($this->modules[$module_name]);
    }
}

/**
 * Initialize theme
 */
function aqualuxe_init() {
    return AquaLuxe_Theme::get_instance();
}

// Initialize theme
aqualuxe_init();

/**
 * Helper functions
 */

/**
 * Get theme option
 */
function aqualuxe_get_option($option, $default = '') {
    return get_theme_mod('aqualuxe_' . $option, $default);
}

/**
 * Check if WooCommerce is active
 */
function aqualuxe_is_woocommerce_active() {
    return class_exists('WooCommerce');
}

/**
 * Get asset URL with cache busting
 */
function aqualuxe_asset($file) {
    $asset_manager = new AquaLuxe_Asset_Manager();
    return $asset_manager->get_asset_url($file);
}

/**
 * Template part loader
 */
function aqualuxe_get_template_part($slug, $name = null, $args = []) {
    $template_loader = new AquaLuxe_Template_Loader();
    return $template_loader->get_template_part($slug, $name, $args);
}