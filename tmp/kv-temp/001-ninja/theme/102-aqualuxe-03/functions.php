<?php
/**
 * AquaLuxe Theme Functions
 * 
 * Main theme functionality and feature registration.
 * Implements modular architecture with clear separation of concerns.
 *
 * @package AquaLuxe
 * @author Kasun Vimarshana
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
define('AQUALUXE_THEME_PATH', get_template_directory() . '/');
define('AQUALUXE_CORE_PATH', AQUALUXE_THEME_PATH . 'core/');
define('AQUALUXE_MODULES_PATH', AQUALUXE_THEME_PATH . 'modules/');
define('AQUALUXE_ASSETS_URI', AQUALUXE_THEME_URI . '/assets/dist/');

/**
 * Theme Setup and Initialization
 */
class AquaLuxe_Theme {
    
    /**
     * Theme instance
     */
    private static $instance = null;
    
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
        // Load core functionality
        $this->load_core();
        
        // Load modules
        $this->load_modules();
        
        // Theme setup
        add_action('after_setup_theme', array($this, 'theme_setup'));
        
        // Enqueue assets
        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
        
        // Theme supports
        add_action('after_setup_theme', array($this, 'theme_supports'));
        
        // Load textdomain
        add_action('after_setup_theme', array($this, 'load_textdomain'));
    }
    
    /**
     * Load core functionality
     */
    private function load_core() {
        $core_files = array(
            'Setup/class-theme-setup.php',
            'Setup/class-post-types.php',
            'Setup/class-taxonomies.php',
            'Admin/class-admin.php',
            'Customizer/class-customizer.php',
            'Security/class-security.php',
            'SEO/class-seo.php',
            'Performance/class-performance.php',
        );
        
        foreach ($core_files as $file) {
            $file_path = AQUALUXE_CORE_PATH . $file;
            if (file_exists($file_path)) {
                require_once $file_path;
            }
        }
    }
    
    /**
     * Load modules
     */
    private function load_modules() {
        $modules = array(
            'dark-mode',
            'multilingual',
            'woocommerce',
            'demo-importer',
            'subscriptions',
            'events',
            'services',
            'auctions'
        );
        
        foreach ($modules as $module) {
            $module_file = AQUALUXE_MODULES_PATH . $module . '/class-' . $module . '.php';
            if (file_exists($module_file)) {
                require_once $module_file;
            }
        }
    }
    
    /**
     * Theme setup
     */
    public function theme_setup() {
        // Add theme support for various features
        add_theme_support('post-thumbnails');
        add_theme_support('title-tag');
        add_theme_support('custom-logo');
        add_theme_support('custom-header');
        add_theme_support('custom-background');
        add_theme_support('html5', array(
            'comment-list',
            'comment-form',
            'search-form',
            'gallery',
            'caption',
            'style',
            'script'
        ));
        
        // Add support for editor styles
        add_theme_support('editor-styles');
        add_editor_style('assets/dist/css/editor-style.css');
        
        // Add support for responsive embeds
        add_theme_support('responsive-embeds');
        
        // Add support for block styles
        add_theme_support('wp-block-styles');
        
        // Add support for wide blocks
        add_theme_support('align-wide');
        
        // Register navigation menus
        register_nav_menus(array(
            'primary' => esc_html__('Primary Menu', 'aqualuxe'),
            'secondary' => esc_html__('Secondary Menu', 'aqualuxe'),
            'footer' => esc_html__('Footer Menu', 'aqualuxe'),
            'mobile' => esc_html__('Mobile Menu', 'aqualuxe'),
        ));
        
        // Set content width
        $GLOBALS['content_width'] = 1200;
        
        // Image sizes
        add_image_size('aqualuxe-hero', 1920, 1080, true);
        add_image_size('aqualuxe-featured', 800, 600, true);
        add_image_size('aqualuxe-thumbnail', 400, 300, true);
        add_image_size('aqualuxe-gallery', 600, 400, true);
    }
    
    /**
     * Theme supports
     */
    public function theme_supports() {
        // WooCommerce support
        if (class_exists('WooCommerce')) {
            add_theme_support('woocommerce');
            add_theme_support('wc-product-gallery-zoom');
            add_theme_support('wc-product-gallery-lightbox');
            add_theme_support('wc-product-gallery-slider');
        }
        
        // Gutenberg features
        add_theme_support('editor-color-palette', array(
            array(
                'name'  => esc_html__('Primary', 'aqualuxe'),
                'slug'  => 'primary',
                'color' => '#14b8a6',
            ),
            array(
                'name'  => esc_html__('Secondary', 'aqualuxe'),
                'slug'  => 'secondary',
                'color' => '#64748b',
            ),
            array(
                'name'  => esc_html__('Accent', 'aqualuxe'),
                'slug'  => 'accent',
                'color' => '#d946ef',
            ),
        ));
    }
    
    /**
     * Enqueue assets
     */
    public function enqueue_assets() {
        // Get manifest for cache busting
        $manifest_path = AQUALUXE_THEME_PATH . 'assets/dist/mix-manifest.json';
        $manifest = file_exists($manifest_path) ? json_decode(file_get_contents($manifest_path), true) : array();
        
        // Main stylesheet
        $main_css = isset($manifest['/css/main.css']) ? $manifest['/css/main.css'] : '/css/main.css';
        wp_enqueue_style(
            'aqualuxe-main',
            AQUALUXE_ASSETS_URI . ltrim($main_css, '/'),
            array(),
            AQUALUXE_VERSION
        );
        
        // WooCommerce styles
        if (class_exists('WooCommerce')) {
            $woocommerce_css = isset($manifest['/css/woocommerce.css']) ? $manifest['/css/woocommerce.css'] : '/css/woocommerce.css';
            wp_enqueue_style(
                'aqualuxe-woocommerce',
                AQUALUXE_ASSETS_URI . ltrim($woocommerce_css, '/'),
                array('aqualuxe-main'),
                AQUALUXE_VERSION
            );
        }
        
        // Dark mode styles
        $dark_mode_css = isset($manifest['/css/dark-mode.css']) ? $manifest['/css/dark-mode.css'] : '/css/dark-mode.css';
        wp_enqueue_style(
            'aqualuxe-dark-mode',
            AQUALUXE_ASSETS_URI . ltrim($dark_mode_css, '/'),
            array('aqualuxe-main'),
            AQUALUXE_VERSION
        );
        
        // Main JavaScript
        $main_js = isset($manifest['/js/main.js']) ? $manifest['/js/main.js'] : '/js/main.js';
        wp_enqueue_script(
            'aqualuxe-main',
            AQUALUXE_ASSETS_URI . ltrim($main_js, '/'),
            array('jquery'),
            AQUALUXE_VERSION,
            true
        );
        
        // Localize script
        wp_localize_script('aqualuxe-main', 'aqualuxe_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe_nonce'),
            'theme_uri' => AQUALUXE_THEME_URI,
            'is_rtl' => is_rtl(),
            'is_woocommerce_active' => class_exists('WooCommerce'),
        ));
        
        // Comment reply script
        if (is_singular() && comments_open() && get_option('thread_comments')) {
            wp_enqueue_script('comment-reply');
        }
    }
    
    /**
     * Enqueue admin assets
     */
    public function enqueue_admin_assets($hook) {
        // Get manifest for cache busting
        $manifest_path = AQUALUXE_THEME_PATH . 'assets/dist/mix-manifest.json';
        $manifest = file_exists($manifest_path) ? json_decode(file_get_contents($manifest_path), true) : array();
        
        // Admin styles
        $admin_css = isset($manifest['/css/admin.css']) ? $manifest['/css/admin.css'] : '/css/admin.css';
        wp_enqueue_style(
            'aqualuxe-admin',
            AQUALUXE_ASSETS_URI . ltrim($admin_css, '/'),
            array(),
            AQUALUXE_VERSION
        );
        
        // Admin JavaScript
        $admin_js = isset($manifest['/js/admin.js']) ? $manifest['/js/admin.js'] : '/js/admin.js';
        wp_enqueue_script(
            'aqualuxe-admin',
            AQUALUXE_ASSETS_URI . ltrim($admin_js, '/'),
            array('jquery'),
            AQUALUXE_VERSION,
            true
        );
    }
    
    /**
     * Load textdomain
     */
    public function load_textdomain() {
        load_theme_textdomain('aqualuxe', AQUALUXE_THEME_PATH . 'languages');
    }
}

// Initialize theme
AquaLuxe_Theme::get_instance();

// Include additional functions
require_once AQUALUXE_THEME_PATH . 'inc/functions/template-tags.php';
require_once AQUALUXE_THEME_PATH . 'inc/functions/utility-functions.php';
require_once AQUALUXE_THEME_PATH . 'inc/hooks/actions.php';
require_once AQUALUXE_THEME_PATH . 'inc/hooks/filters.php';