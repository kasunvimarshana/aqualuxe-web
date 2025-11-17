<?php
/**
 * AquaLuxe WordPress Theme
 * 
 * @package AquaLuxe
 * @version 1.0.0
 * @author AquaLuxe Development Team
 * @link https://aqualuxe.com
 * @license GPL-2.0+
 * 
 * Functions and definitions for the AquaLuxe theme.
 * Implements modular architecture with SOLID principles.
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Define theme constants
 */
define('AQUALUXE_VERSION', '1.0.0');
define('AQUALUXE_THEME_DIR', get_template_directory());
define('AQUALUXE_THEME_URI', get_template_directory_uri());
define('AQUALUXE_ASSETS_DIR', AQUALUXE_THEME_DIR . '/assets');
define('AQUALUXE_ASSETS_URI', AQUALUXE_THEME_URI . '/assets');
define('AQUALUXE_INC_DIR', AQUALUXE_THEME_DIR . '/inc');
define('AQUALUXE_MODULES_DIR', AQUALUXE_THEME_DIR . '/modules');

/**
 * Autoloader for theme classes
 */
spl_autoload_register(function ($class_name) {
    // Convert class name to file path
    $class_file = str_replace(['AquaLuxe\\', '\\'], ['', '/'], $class_name);
    $class_file = strtolower(str_replace('_', '-', $class_file));
    
    // Check in inc/classes directory
    $file_path = AQUALUXE_INC_DIR . '/classes/' . $class_file . '.php';
    if (file_exists($file_path)) {
        require_once $file_path;
        return;
    }
    
    // Check in modules directory
    $module_path = AQUALUXE_MODULES_DIR . '/' . $class_file . '.php';
    if (file_exists($module_path)) {
        require_once $module_path;
        return;
    }
});

/**
 * Main AquaLuxe Theme Class
 */
class AquaLuxe_Theme {
    
    /**
     * Theme instance
     */
    private static $instance = null;
    
    /**
     * Get theme instance
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Constructor
     */
    private function __construct() {
        add_action('after_setup_theme', [$this, 'setup_theme']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_conditional_assets']);
        add_action('init', [$this, 'load_textdomain']);
        add_action('init', [$this, 'init_modules']);
        add_action('customize_register', [$this, 'load_customizer']);
        
        // Load core functionality
        $this->load_core_files();
        
        // Initialize WooCommerce integration if available
        if (class_exists('WooCommerce')) {
            add_action('after_setup_theme', [$this, 'woocommerce_setup']);
        }
    }
    
    /**
     * Theme setup
     */
    public function setup_theme() {
        // Add theme support
        add_theme_support('title-tag');
        add_theme_support('post-thumbnails');
        add_theme_support('automatic-feed-links');
        add_theme_support('html5', [
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script'
        ]);
        add_theme_support('custom-background');
        add_theme_support('custom-logo', [
            'height'      => 100,
            'width'       => 400,
            'flex-width'  => true,
            'flex-height' => true,
        ]);
        add_theme_support('custom-header', [
            'default-image'      => '',
            'width'              => 1920,
            'height'             => 1080,
            'flex-width'         => true,
            'flex-height'        => true,
            'uploads'            => true,
            'random-default'     => false,
            'header-text'        => true,
            'default-text-color' => '#000000',
        ]);
        add_theme_support('customize-selective-refresh-widgets');
        add_theme_support('responsive-embeds');
        add_theme_support('align-wide');
        add_theme_support('wp-block-styles');
        add_theme_support('editor-styles');
        
        // Register navigation menus
        register_nav_menus([
            'primary'   => esc_html__('Primary Menu', 'aqualuxe'),
            'secondary' => esc_html__('Secondary Menu', 'aqualuxe'),
            'footer'    => esc_html__('Footer Menu', 'aqualuxe'),
            'mobile'    => esc_html__('Mobile Menu', 'aqualuxe'),
        ]);
        
        // Set content width
        $GLOBALS['content_width'] = 1200;
        
        // Add image sizes
        add_image_size('aqualuxe-hero', 1920, 1080, true);
        add_image_size('aqualuxe-product-large', 800, 800, true);
        add_image_size('aqualuxe-product-medium', 400, 400, true);
        add_image_size('aqualuxe-product-small', 200, 200, true);
        add_image_size('aqualuxe-blog-large', 1200, 600, true);
        add_image_size('aqualuxe-blog-medium', 600, 400, true);
    }
    
    /**
     * Load text domain for translations
     */
    public function load_textdomain() {
        load_theme_textdomain('aqualuxe', AQUALUXE_THEME_DIR . '/languages');
    }
    
    /**
     * Enqueue theme assets
     */
    public function enqueue_assets() {
        // Get manifest for asset versioning
        $manifest_path = AQUALUXE_ASSETS_DIR . '/dist/mix-manifest.json';
        $manifest = [];
        
        if (file_exists($manifest_path)) {
            $manifest = json_decode(file_get_contents($manifest_path), true);
        }
        
        // Get versioned asset path
        $get_versioned_asset = function($asset) use ($manifest) {
            return isset($manifest[$asset]) ? $manifest[$asset] : $asset;
        };
        
        // Enqueue main stylesheet
        $main_css = $get_versioned_asset('/css/main.css');
        wp_enqueue_style(
            'aqualuxe-main',
            AQUALUXE_ASSETS_URI . '/dist' . $main_css,
            [],
            AQUALUXE_VERSION
        );
        
        // Enqueue main JavaScript
        $main_js = $get_versioned_asset('/js/main.js');
        wp_enqueue_script(
            'aqualuxe-main',
            AQUALUXE_ASSETS_URI . '/dist' . $main_js,
            ['jquery'],
            AQUALUXE_VERSION,
            true
        );
        
        // Localize script for AJAX
        wp_localize_script('aqualuxe-main', 'aqualuxe_ajax', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('aqualuxe_nonce'),
            'strings'  => [
                'loading' => esc_html__('Loading...', 'aqualuxe'),
                'error'   => esc_html__('An error occurred. Please try again.', 'aqualuxe'),
            ]
        ]);
        
        // Enqueue comment reply script
        if (is_singular() && comments_open() && get_option('thread_comments')) {
            wp_enqueue_script('comment-reply');
        }
    }
    
    /**
     * Enqueue conditional assets
     */
    public function enqueue_conditional_assets() {
        // WooCommerce specific assets
        if (class_exists('WooCommerce') && (is_woocommerce() || is_cart() || is_checkout() || is_account_page())) {
            $manifest_path = AQUALUXE_ASSETS_DIR . '/dist/mix-manifest.json';
            $manifest = [];
            
            if (file_exists($manifest_path)) {
                $manifest = json_decode(file_get_contents($manifest_path), true);
            }
            
            $wc_css = isset($manifest['/css/woocommerce.css']) ? $manifest['/css/woocommerce.css'] : '/css/woocommerce.css';
            $wc_js = isset($manifest['/js/woocommerce.js']) ? $manifest['/js/woocommerce.js'] : '/js/woocommerce.js';
            
            wp_enqueue_style(
                'aqualuxe-woocommerce',
                AQUALUXE_ASSETS_URI . '/dist' . $wc_css,
                ['aqualuxe-main'],
                AQUALUXE_VERSION
            );
            
            wp_enqueue_script(
                'aqualuxe-woocommerce',
                AQUALUXE_ASSETS_URI . '/dist' . $wc_js,
                ['aqualuxe-main', 'jquery'],
                AQUALUXE_VERSION,
                true
            );
        }
    }
    
    /**
     * WooCommerce setup
     */
    public function woocommerce_setup() {
        add_theme_support('woocommerce', [
            'thumbnail_image_width' => 300,
            'single_image_width'    => 600,
            'product_grid'          => [
                'default_rows'    => 4,
                'min_rows'        => 2,
                'max_rows'        => 8,
                'default_columns' => 3,
                'min_columns'     => 2,
                'max_columns'     => 5,
            ],
        ]);
        add_theme_support('wc-product-gallery-zoom');
        add_theme_support('wc-product-gallery-lightbox');
        add_theme_support('wc-product-gallery-slider');
    }
    
    /**
     * Load core files
     */
    private function load_core_files() {
        $core_files = [
            'inc/core/class-theme-setup.php',
            'inc/core/class-asset-manager.php',
            'inc/core/class-template-loader.php',
            'inc/core/class-customizer.php',
            'inc/core/class-security.php',
            'inc/core/class-performance.php',
            'inc/helpers/template-functions.php',
            'inc/helpers/utility-functions.php',
            'inc/helpers/woocommerce-functions.php',
        ];
        
        foreach ($core_files as $file) {
            $file_path = AQUALUXE_THEME_DIR . '/' . $file;
            if (file_exists($file_path)) {
                require_once $file_path;
            }
        }
    }
    
    /**
     * Initialize modules
     */
    public function init_modules() {
        $modules = [
            'multilingual',
            'dark-mode',
            'woocommerce-integration',
            'demo-importer',
            'booking-system',
            'events-ticketing',
            'subscriptions',
            'marketplace',
            'seo-optimization',
            'performance-optimizer',
        ];
        
        foreach ($modules as $module) {
            $module_file = AQUALUXE_MODULES_DIR . '/' . $module . '/' . $module . '.php';
            if (file_exists($module_file)) {
                require_once $module_file;
            }
        }
        
        do_action('aqualuxe_modules_loaded');
    }
    
    /**
     * Load customizer
     */
    public function load_customizer($wp_customize) {
        require_once AQUALUXE_INC_DIR . '/customizer/customizer.php';
    }
}

// Initialize theme
AquaLuxe_Theme::getInstance();

/**
 * Widget areas
 */
function aqualuxe_widgets_init() {
    // Primary sidebar
    register_sidebar([
        'name'          => esc_html__('Primary Sidebar', 'aqualuxe'),
        'id'            => 'sidebar-primary',
        'description'   => esc_html__('Main sidebar for blog and pages', 'aqualuxe'),
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget'  => '</aside>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ]);
    
    // Shop sidebar
    register_sidebar([
        'name'          => esc_html__('Shop Sidebar', 'aqualuxe'),
        'id'            => 'sidebar-shop',
        'description'   => esc_html__('Sidebar for WooCommerce shop and product pages', 'aqualuxe'),
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget'  => '</aside>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ]);
    
    // Footer widgets
    for ($i = 1; $i <= 4; $i++) {
        register_sidebar([
            'name'          => sprintf(esc_html__('Footer Widget %d', 'aqualuxe'), $i),
            'id'            => 'footer-' . $i,
            'description'   => sprintf(esc_html__('Footer widget area %d', 'aqualuxe'), $i),
            'before_widget' => '<aside id="%1$s" class="widget %2$s">',
            'after_widget'  => '</aside>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        ]);
    }
}
add_action('widgets_init', 'aqualuxe_widgets_init');

/**
 * Custom excerpt length
 */
function aqualuxe_excerpt_length($length) {
    return 25;
}
add_filter('excerpt_length', 'aqualuxe_excerpt_length');

/**
 * Custom excerpt more
 */
function aqualuxe_excerpt_more($more) {
    return '&hellip;';
}
add_filter('excerpt_more', 'aqualuxe_excerpt_more');