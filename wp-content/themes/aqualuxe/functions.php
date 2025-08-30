<?php
/**
 * AquaLuxe Theme Functions
 * 
 * Main theme initialization file implementing modular architecture
 * with graceful WooCommerce fallbacks and SOLID principles.
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
define('AQUALUXE_PATH', get_template_directory());
define('AQUALUXE_URL', get_template_directory_uri());
define('AQUALUXE_ASSETS_URL', AQUALUXE_URL . '/assets/dist');
define('AQUALUXE_MODULES_PATH', AQUALUXE_PATH . '/modules');

/**
 * AquaLuxe Theme Main Class
 * 
 * Central theme controller implementing dependency injection
 * and modular architecture patterns.
 */
class AquaLuxe_Theme {
    
    private static $instance = null;
    private $modules = [];
    private $asset_manager;
    
    /**
     * Singleton pattern implementation
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Initialize theme
     */
    private function __construct() {
        $this->load_core_files();
        $this->init_asset_manager();
        $this->load_modules();
        $this->init_hooks();
    }
    
    /**
     * Load core theme files
     */
    private function load_core_files() {
        require_once AQUALUXE_PATH . '/inc/class-asset-manager.php';
        require_once AQUALUXE_PATH . '/inc/class-customizer.php';
        require_once AQUALUXE_PATH . '/inc/class-theme-setup.php';
        require_once AQUALUXE_PATH . '/inc/class-woocommerce-integration.php';
        require_once AQUALUXE_PATH . '/inc/class-security.php';
        require_once AQUALUXE_PATH . '/inc/class-performance.php';
        require_once AQUALUXE_PATH . '/inc/class-seo.php';
        require_once AQUALUXE_PATH . '/inc/functions-helpers.php';
        require_once AQUALUXE_PATH . '/inc/functions-template.php';
    }
    
    /**
     * Initialize asset manager
     */
    private function init_asset_manager() {
        $this->asset_manager = new AquaLuxe_Asset_Manager();
    }
    
    /**
     * Load modular features
     */
    private function load_modules() {
        $module_dirs = glob(AQUALUXE_MODULES_PATH . '/*', GLOB_ONLYDIR);
        
        foreach ($module_dirs as $module_dir) {
            $module_name = basename($module_dir);
            $module_file = $module_dir . '/module.php';
            
            if (file_exists($module_file)) {
                require_once $module_file;
                
                // Auto-instantiate module class if exists
                $class_name = 'AquaLuxe_Module_' . str_replace('-', '_', ucwords($module_name, '-'));
                if (class_exists($class_name)) {
                    $this->modules[$module_name] = new $class_name();
                }
            }
        }
    }
    
    /**
     * Initialize WordPress hooks
     */
    private function init_hooks() {
        add_action('after_setup_theme', [$this, 'theme_setup']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
        add_action('init', [$this, 'init_theme_features']);
        
        // Security hooks
        add_action('init', 'AquaLuxe_Security::init');
        
        // Performance hooks
        add_action('init', 'AquaLuxe_Performance::init');
        
        // SEO hooks
        add_action('init', 'AquaLuxe_SEO::init');
    }
    
    /**
     * Theme setup
     */
    public function theme_setup() {
        AquaLuxe_Theme_Setup::init();
    }
    
    /**
     * Enqueue theme assets
     */
    public function enqueue_assets() {
        $this->asset_manager->enqueue_assets();
    }
    
    /**
     * Initialize theme features
     */
    public function init_theme_features() {
        // Load text domain
        load_theme_textdomain('aqualuxe', AQUALUXE_PATH . '/languages');
        
        // Initialize WooCommerce integration if available
        if (class_exists('WooCommerce')) {
            AquaLuxe_WooCommerce_Integration::init();
        }
        
        // Initialize customizer
        AquaLuxe_Customizer::init();
    }
    
    /**
     * Get module instance
     */
    public function get_module($module_name) {
        return isset($this->modules[$module_name]) ? $this->modules[$module_name] : null;
    }
    
    /**
     * Check if module is active
     */
    public function is_module_active($module_name) {
        return isset($this->modules[$module_name]);
    }
}

// Initialize theme
AquaLuxe_Theme::getInstance();

/**
 * Theme activation hook
 */
function aqualuxe_after_switch_theme() {
    // Flush rewrite rules
    flush_rewrite_rules();
    
    // Set default customizer options
    AquaLuxe_Customizer::set_defaults();
    
    // Create necessary pages
    aqualuxe_create_default_pages();
}
add_action('after_switch_theme', 'aqualuxe_after_switch_theme');

/**
 * Create default pages on theme activation
 */
function aqualuxe_create_default_pages() {
    $pages = [
        'home' => [
            'title' => __('Home', 'aqualuxe'),
            'content' => aqualuxe_get_demo_content('home'),
            'template' => 'page-home.php'
        ],
        'about' => [
            'title' => __('About Us', 'aqualuxe'),
            'content' => aqualuxe_get_demo_content('about')
        ],
        'services' => [
            'title' => __('Services', 'aqualuxe'),
            'content' => aqualuxe_get_demo_content('services')
        ],
        'contact' => [
            'title' => __('Contact', 'aqualuxe'),
            'content' => aqualuxe_get_demo_content('contact')
        ],
        'privacy-policy' => [
            'title' => __('Privacy Policy', 'aqualuxe'),
            'content' => aqualuxe_get_demo_content('privacy-policy')
        ],
        'terms-conditions' => [
            'title' => __('Terms & Conditions', 'aqualuxe'),
            'content' => aqualuxe_get_demo_content('terms-conditions')
        ]
    ];
    
    foreach ($pages as $slug => $page_data) {
        if (!get_page_by_path($slug)) {
            $page_id = wp_insert_post([
                'post_title' => $page_data['title'],
                'post_content' => $page_data['content'],
                'post_status' => 'publish',
                'post_type' => 'page',
                'post_name' => $slug
            ]);
            
            if (isset($page_data['template']) && $page_id) {
                update_post_meta($page_id, '_wp_page_template', $page_data['template']);
            }
        }
    }
}

/**
 * Global theme functions for template use
 */

/**
 * Get theme option with fallback
 */
function aqualuxe_get_option($option, $default = '') {
    return get_theme_mod($option, $default);
}

/**
 * Check if WooCommerce is active
 */
function aqualuxe_is_woocommerce_active() {
    return class_exists('WooCommerce');
}

/**
 * Get theme instance
 */
function aqualuxe() {
    return AquaLuxe_Theme::getInstance();
}
