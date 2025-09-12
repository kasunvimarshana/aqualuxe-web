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
 * Load core files and initialize autoloader
 */
require_once AQUALUXE_INC_DIR . '/interfaces/autoloader-interface.php';
require_once AQUALUXE_INC_DIR . '/interfaces/module-interface.php';
require_once AQUALUXE_INC_DIR . '/interfaces/service-interface.php';
require_once AQUALUXE_INC_DIR . '/core/autoloader.php';
require_once AQUALUXE_INC_DIR . '/core/service-container.php';
require_once AQUALUXE_INC_DIR . '/core/base-module.php';
require_once AQUALUXE_INC_DIR . '/core/base-service.php';
require_once AQUALUXE_INC_DIR . '/core/module-manager.php';

// Initialize autoloader
$autoloader = new \AquaLuxe\Core\Autoloader();
$autoloader->add_namespace('AquaLuxe\\', AQUALUXE_INC_DIR . '/');
$autoloader->add_namespace('AquaLuxe\\Modules\\', AQUALUXE_MODULES_DIR . '/');
$autoloader->register();

/**
 * Main AquaLuxe Theme Class
 */
class AquaLuxe_Theme {
    
    /**
     * Theme instance
     */
    private static $instance = null;
    
    /**
     * Service container
     */
    private $container;
    
    /**
     * Module manager
     */
    private $module_manager;
    
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
        // Initialize service container
        $this->container = \AquaLuxe\Core\Service_Container::get_instance();
        
        // Initialize module manager
        $this->module_manager = \AquaLuxe\Core\Module_Manager::get_instance();
        
        // Hook into WordPress
        add_action('after_setup_theme', [$this, 'setup_theme']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_conditional_assets']);
        add_action('init', [$this, 'load_textdomain']);
        add_action('init', [$this, 'init_modules']);
        add_action('customize_register', [$this, 'load_customizer']);
        
        // Load core services
        $this->register_core_services();
        
        // Initialize WooCommerce integration if available
        if (class_exists('WooCommerce')) {
            add_action('after_setup_theme', [$this, 'woocommerce_setup']);
        }
    }
    
    /**
     * Register core services
     */
    private function register_core_services() {
        // Register essential services
        $services = [
            'asset_manager' => '\\AquaLuxe\\Services\\Asset_Manager',
            'template_loader' => '\\AquaLuxe\\Services\\Template_Loader',
            'security' => '\\AquaLuxe\\Services\\Security',
            'performance' => '\\AquaLuxe\\Services\\Performance',
            'customizer' => '\\AquaLuxe\\Services\\Customizer',
            'woocommerce_integration' => '\\AquaLuxe\\Services\\WooCommerce_Integration',
        ];
        
        foreach ($services as $name => $class) {
            if (class_exists($class)) {
                $this->container->singleton($name, $class);
            }
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
            'debug'    => defined('WP_DEBUG') && WP_DEBUG,
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
     * Initialize modules
     */
    public function init_modules() {
        // Register default modules
        $this->module_manager->register_default_modules();
        
        // Allow themes/plugins to register additional modules
        do_action('aqualuxe_register_modules', $this->module_manager);
        
        // Load all modules
        $this->module_manager->load_modules();
        
        // Emit modules loaded event
        do_action('aqualuxe_modules_loaded', $this->module_manager);
    }
    
    /**
     * Load customizer
     */
    public function load_customizer($wp_customize) {
        if ($this->container->has('customizer')) {
            $customizer = $this->container->get('customizer');
            $customizer->register($wp_customize);
        }
    }
    
    /**
     * Get service container
     */
    public function get_container() {
        return $this->container;
    }
    
    /**
     * Get module manager
     */
    public function get_module_manager() {
        return $this->module_manager;
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

/**
 * Global helper function to access theme instance
 */
function aqualuxe() {
    return AquaLuxe_Theme::getInstance();
}

/**
 * Helper function to get a service from the container
 */
function aqualuxe_service($service_name) {
    $theme = aqualuxe();
    $container = $theme->get_container();
    
    if ($container->has($service_name)) {
        return $container->get($service_name);
    }
    
    return null;
}

/**
 * Helper function to get a module
 */
function aqualuxe_module($module_name) {
    $theme = aqualuxe();
    $module_manager = $theme->get_module_manager();
    
    return $module_manager->get_module($module_name);
}