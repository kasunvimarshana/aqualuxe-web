<?php
/**
 * AquaLuxe Theme Functions
 *
 * Main functions file that orchestrates the theme's modular architecture.
 * This file follows SOLID principles and implements dependency injection
 * for clean, maintainable, and extensible code.
 *
 * @package AquaLuxe
 * @version 1.0.0
 * @since 1.0.0
 * @author AquaLuxe Development Team
 * @license GPL-2.0-or-later
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Define theme constants for paths and configuration
 */
define('AQUALUXE_VERSION', '1.0.0');
define('AQUALUXE_THEME_DIR', get_template_directory());
define('AQUALUXE_THEME_URI', get_template_directory_uri());
define('AQUALUXE_ASSETS_DIR', AQUALUXE_THEME_DIR . '/assets');
define('AQUALUXE_ASSETS_URI', AQUALUXE_THEME_URI . '/assets');
define('AQUALUXE_INC_DIR', AQUALUXE_THEME_DIR . '/inc');
define('AQUALUXE_MODULES_DIR', AQUALUXE_THEME_DIR . '/modules');
define('AQUALUXE_TEMPLATES_DIR', AQUALUXE_THEME_DIR . '/templates');

/**
 * Autoloader for theme classes
 * Implements PSR-4 autoloading standards
 */
spl_autoload_register(function ($class) {
    // Project-specific namespace prefix
    $prefix = 'AquaLuxe\\';
    
    // Base directory for the namespace prefix
    $base_dir = AQUALUXE_INC_DIR . '/classes/';
    
    // Does the class use the namespace prefix?
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    
    // Get the relative class name
    $relative_class = substr($class, $len);
    
    // Replace the namespace prefix with the base directory,
    // replace namespace separators with directory separators
    // in the relative class name, append with .php
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    
    // If the file exists, require it
    if (file_exists($file)) {
        require $file;
    }
});

/**
 * Main Theme Class - Singleton Pattern
 * Orchestrates all theme functionality and module loading
 */
class AquaLuxe_Theme {
    
    /**
     * Singleton instance
     * @var AquaLuxe_Theme|null
     */
    private static $instance = null;
    
    /**
     * Theme modules container
     * @var array
     */
    private $modules = [];
    
    /**
     * Theme configuration
     * @var array
     */
    private $config = [];
    
    /**
     * Get singleton instance
     * @return AquaLuxe_Theme
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Private constructor to prevent multiple instances
     */
    private function __construct() {
        $this->init();
    }
    
    /**
     * Initialize the theme
     */
    private function init() {
        // Load core configuration
        $this->load_config();
        
        // Set up WordPress hooks
        add_action('after_setup_theme', [$this, 'setup_theme']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_styles']);
        add_action('init', [$this, 'load_textdomain']);
        add_action('init', [$this, 'init_modules']);
        add_action('wp_head', [$this, 'add_meta_tags']);
        add_action('wp_footer', [$this, 'add_schema_markup']);
        
        // Security hardening
        add_action('init', [$this, 'security_headers']);
        
        // Performance optimizations
        add_action('init', [$this, 'performance_optimizations']);
        
        // Load core functionality
        $this->load_core_files();
    }
    
    /**
     * Load theme configuration
     */
    private function load_config() {
        $config_file = AQUALUXE_INC_DIR . '/config/theme-config.php';
        if (file_exists($config_file)) {
            $this->config = require $config_file;
        }
    }
    
    /**
     * Set up theme features and support
     */
    public function setup_theme() {
        // Enable theme features
        add_theme_support('post-thumbnails');
        add_theme_support('custom-logo');
        add_theme_support('custom-header');
        add_theme_support('custom-background');
        add_theme_support('html5', [
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script'
        ]);
        add_theme_support('title-tag');
        add_theme_support('automatic-feed-links');
        add_theme_support('post-formats', [
            'aside',
            'gallery',
            'video',
            'quote',
            'link'
        ]);
        
        // Enable WooCommerce support with custom features
        add_theme_support('woocommerce');
        add_theme_support('wc-product-gallery-zoom');
        add_theme_support('wc-product-gallery-lightbox');
        add_theme_support('wc-product-gallery-slider');
        
        // Custom image sizes
        add_image_size('aqualuxe-hero', 1920, 1080, true);
        add_image_size('aqualuxe-product', 600, 600, true);
        add_image_size('aqualuxe-thumbnail', 300, 300, true);
        add_image_size('aqualuxe-gallery', 800, 600, true);
        
        // Register navigation menus
        register_nav_menus([
            'primary' => __('Primary Menu', 'aqualuxe'),
            'footer' => __('Footer Menu', 'aqualuxe'),
            'mobile' => __('Mobile Menu', 'aqualuxe'),
            'account' => __('Account Menu', 'aqualuxe')
        ]);
        
        // Content width
        if (!isset($GLOBALS['content_width'])) {
            $GLOBALS['content_width'] = 1200;
        }
    }
    
    /**
     * Load theme text domain for translations
     */
    public function load_textdomain() {
        load_theme_textdomain('aqualuxe', AQUALUXE_THEME_DIR . '/languages');
    }
    
    /**
     * Enqueue theme styles with proper dependency management
     */
    public function enqueue_styles() {
        // Get manifest for cache busting
        $manifest = $this->get_mix_manifest();
        
        // Main theme stylesheet
        wp_enqueue_style(
            'aqualuxe-style',
            AQUALUXE_ASSETS_URI . '/dist/css/main.css',
            [],
            $manifest['css/main.css'] ?? AQUALUXE_VERSION
        );
        
        // Dark mode styles
        wp_enqueue_style(
            'aqualuxe-dark-mode',
            AQUALUXE_ASSETS_URI . '/dist/css/dark-mode.css',
            ['aqualuxe-style'],
            $manifest['css/dark-mode.css'] ?? AQUALUXE_VERSION
        );
        
        // RTL support
        if (is_rtl()) {
            wp_enqueue_style(
                'aqualuxe-rtl',
                AQUALUXE_ASSETS_URI . '/dist/css/rtl.css',
                ['aqualuxe-style'],
                $manifest['css/rtl.css'] ?? AQUALUXE_VERSION
            );
        }
        
        // Print styles
        wp_enqueue_style(
            'aqualuxe-print',
            AQUALUXE_ASSETS_URI . '/dist/css/print.css',
            ['aqualuxe-style'],
            $manifest['css/print.css'] ?? AQUALUXE_VERSION,
            'print'
        );
    }
    
    /**
     * Enqueue theme scripts with proper dependency management
     */
    public function enqueue_scripts() {
        // Get manifest for cache busting
        $manifest = $this->get_mix_manifest();
        
        // Main theme script
        wp_enqueue_script(
            'aqualuxe-main',
            AQUALUXE_ASSETS_URI . '/dist/js/main.js',
            ['jquery'],
            $manifest['js/main.js'] ?? AQUALUXE_VERSION,
            true
        );
        
        // Localize script for AJAX and translations
        wp_localize_script('aqualuxe-main', 'aqualuxe_ajax', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe_nonce'),
            'strings' => [
                'loading' => __('Loading...', 'aqualuxe'),
                'error' => __('An error occurred. Please try again.', 'aqualuxe'),
                'success' => __('Success!', 'aqualuxe')
            ]
        ]);
        
        // Progressive enhancement scripts
        wp_enqueue_script(
            'aqualuxe-progressive',
            AQUALUXE_ASSETS_URI . '/dist/js/progressive.js',
            ['aqualuxe-main'],
            $manifest['js/progressive.js'] ?? AQUALUXE_VERSION,
            true
        );
        
        // Conditional WooCommerce scripts
        if (class_exists('WooCommerce')) {
            wp_enqueue_script(
                'aqualuxe-woocommerce',
                AQUALUXE_ASSETS_URI . '/dist/js/woocommerce.js',
                ['aqualuxe-main', 'wc-add-to-cart'],
                $manifest['js/woocommerce.js'] ?? AQUALUXE_VERSION,
                true
            );
        }
        
        // Comment reply script
        if (is_singular() && comments_open() && get_option('thread_comments')) {
            wp_enqueue_script('comment-reply');
        }
    }
    
    /**
     * Get Mix manifest for cache busting
     * @return array
     */
    private function get_mix_manifest() {
        $manifest_path = AQUALUXE_ASSETS_DIR . '/dist/mix-manifest.json';
        
        if (file_exists($manifest_path)) {
            $manifest = json_decode(file_get_contents($manifest_path), true);
            return $manifest ?: [];
        }
        
        return [];
    }
    
    /**
     * Load core theme files
     */
    private function load_core_files() {
        $core_files = [
            'inc/core/class-theme-setup.php',
            'inc/core/class-assets-manager.php',
            'inc/core/class-customizer.php',
            'inc/core/class-security.php',
            'inc/core/class-performance.php',
            'inc/core/class-seo.php',
            'inc/admin/class-admin-interface.php',
            'inc/compatibility/class-woocommerce-compatibility.php',
            'inc/compatibility/class-plugin-compatibility.php',
            'inc/helpers/class-template-helper.php',
            'inc/helpers/class-utility-helper.php'
        ];
        
        foreach ($core_files as $file) {
            $file_path = AQUALUXE_THEME_DIR . '/' . $file;
            if (file_exists($file_path)) {
                require_once $file_path;
            }
        }
    }
    
    /**
     * Initialize theme modules
     */
    public function init_modules() {
        $modules_config = $this->config['modules'] ?? [];
        
        foreach ($modules_config as $module_name => $module_config) {
            if ($module_config['enabled'] ?? false) {
                $this->load_module($module_name, $module_config);
            }
        }
        
        // Hook for modules initialization
        do_action('aqualuxe_modules_loaded', $this->modules);
    }
    
    /**
     * Load a specific module
     * @param string $module_name
     * @param array $module_config
     */
    private function load_module($module_name, $module_config) {
        $module_file = AQUALUXE_MODULES_DIR . '/' . $module_name . '/module.php';
        
        if (file_exists($module_file)) {
            require_once $module_file;
            
            $module_class = 'AquaLuxe\\Modules\\' . ucfirst($module_name) . '\\Module';
            
            if (class_exists($module_class)) {
                $this->modules[$module_name] = new $module_class($module_config);
            }
        }
    }
    
    /**
     * Add meta tags for SEO and social sharing
     */
    public function add_meta_tags() {
        echo '<meta name="viewport" content="width=device-width, initial-scale=1">' . "\n";
        echo '<meta name="theme-color" content="#1e40af">' . "\n";
        echo '<meta name="msapplication-navbutton-color" content="#1e40af">' . "\n";
        echo '<meta name="apple-mobile-web-app-status-bar-style" content="#1e40af">' . "\n";
    }
    
    /**
     * Add structured data markup
     */
    public function add_schema_markup() {
        if (is_front_page()) {
            $schema = [
                '@context' => 'https://schema.org',
                '@type' => 'Organization',
                'name' => get_bloginfo('name'),
                'url' => home_url(),
                'description' => get_bloginfo('description'),
                'sameAs' => []
            ];
            
            echo '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>' . "\n";
        }
    }
    
    /**
     * Implement security headers
     */
    public function security_headers() {
        if (!is_admin()) {
            header('X-Content-Type-Options: nosniff');
            header('X-Frame-Options: SAMEORIGIN');
            header('X-XSS-Protection: 1; mode=block');
            header('Referrer-Policy: strict-origin-when-cross-origin');
        }
    }
    
    /**
     * Performance optimizations
     */
    public function performance_optimizations() {
        // Remove unnecessary WordPress features for performance
        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('wp_print_styles', 'print_emoji_styles');
        remove_action('admin_print_scripts', 'print_emoji_detection_script');
        remove_action('admin_print_styles', 'print_emoji_styles');
        
        // Remove WordPress version info
        remove_action('wp_head', 'wp_generator');
        
        // Disable unnecessary REST API endpoints
        add_filter('rest_endpoints', function($endpoints) {
            unset($endpoints['/wp/v2/users']);
            return $endpoints;
        });
    }
    
    /**
     * Get module instance
     * @param string $module_name
     * @return mixed|null
     */
    public function get_module($module_name) {
        return $this->modules[$module_name] ?? null;
    }
    
    /**
     * Get theme configuration
     * @param string $key
     * @return mixed
     */
    public function get_config($key = null) {
        if ($key) {
            return $this->config[$key] ?? null;
        }
        return $this->config;
    }
}

/**
 * Initialize the theme
 */
function aqualuxe_init() {
    return AquaLuxe_Theme::get_instance();
}

// Start the theme
aqualuxe_init();

/**
 * Helper function to get theme instance
 * @return AquaLuxe_Theme
 */
function aqualuxe() {
    return AquaLuxe_Theme::get_instance();
}

/**
 * Template function to check if WooCommerce is active
 * @return bool
 */
function aqualuxe_is_woocommerce_active() {
    return class_exists('WooCommerce');
}

/**
 * Template function to get module instance
 * @param string $module_name
 * @return mixed|null
 */
function aqualuxe_get_module($module_name) {
    return aqualuxe()->get_module($module_name);
}

/**
 * Template function for safe output
 * @param string $string
 * @param string $context
 * @return string
 */
function aqualuxe_esc($string, $context = 'html') {
    switch ($context) {
        case 'attr':
            return esc_attr($string);
        case 'url':
            return esc_url($string);
        case 'js':
            return esc_js($string);
        case 'textarea':
            return esc_textarea($string);
        default:
            return esc_html($string);
    }
}

/**
 * Hook for theme customization by child themes and plugins
 */
do_action('aqualuxe_theme_loaded');
