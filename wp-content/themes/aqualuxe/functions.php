<?php
/**
 * AquaLuxe Theme Functions
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
define('AQUALUXE_ASSETS_URI', AQUALUXE_THEME_URI . '/assets');

/**
 * AquaLuxe Theme Setup
 */
class AquaLuxe_Theme {
    
    /**
     * Constructor
     */
    public function __construct() {
        add_action('after_setup_theme', array($this, 'setup'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'));
        add_action('init', array($this, 'load_textdomain'));
        add_action('widgets_init', array($this, 'register_sidebars'));
        
        // Load core files
        $this->load_core_files();
        
        // Load modules
        $this->load_modules();
    }
    
    /**
     * Theme setup
     */
    public function setup() {
        // Add theme support
        add_theme_support('post-thumbnails');
        add_theme_support('automatic-feed-links');
        add_theme_support('title-tag');
        add_theme_support('html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script'
        ));
        add_theme_support('customize-selective-refresh-widgets');
        add_theme_support('custom-logo');
        add_theme_support('custom-header');
        add_theme_support('custom-background');
        add_theme_support('post-formats', array(
            'aside',
            'gallery',
            'link',
            'image',
            'quote',
            'status',
            'video',
            'audio',
            'chat'
        ));
        
        // WooCommerce support
        add_theme_support('woocommerce');
        add_theme_support('wc-product-gallery-zoom');
        add_theme_support('wc-product-gallery-lightbox');
        add_theme_support('wc-product-gallery-slider');
        
        // Gutenberg support
        add_theme_support('wp-block-styles');
        add_theme_support('align-wide');
        add_theme_support('editor-styles');
        add_editor_style('assets/css/editor-style.css');
        
        // Register menus
        register_nav_menus(array(
            'primary' => esc_html__('Primary Menu', 'aqualuxe'),
            'footer' => esc_html__('Footer Menu', 'aqualuxe'),
            'mobile' => esc_html__('Mobile Menu', 'aqualuxe'),
        ));
        
        // Image sizes
        add_image_size('aqualuxe-featured', 1200, 675, true);
        add_image_size('aqualuxe-thumbnail', 300, 300, true);
        add_image_size('aqualuxe-medium', 600, 400, true);
    }
    
    /**
     * Load core files
     */
    private function load_core_files() {
        $core_files = array(
            'core/class-theme-setup.php',
            'core/class-assets.php',
            'core/class-customizer.php',
            'core/class-demo-importer.php',
            'core/class-performance.php',
            'core/class-security.php',
            'core/class-seo.php',
            'core/helpers.php',
            'core/hooks.php',
        );
        
        foreach ($core_files as $file) {
            $file_path = AQUALUXE_THEME_DIR . '/inc/' . $file;
            if (file_exists($file_path)) {
                require_once $file_path;
            }
        }
    }
    
    /**
     * Load modules
     */
    private function load_modules() {
        $enabled_modules = $this->get_enabled_modules();
        
        foreach ($enabled_modules as $module) {
            $module_path = AQUALUXE_THEME_DIR . '/modules/' . $module . '/bootstrap.php';
            if (file_exists($module_path)) {
                require_once $module_path;
            }
        }
    }
    
    /**
     * Get enabled modules
     */
    private function get_enabled_modules() {
        $default_modules = array(
            'dark-mode',
            'multilingual',
            'multicurrency',
            'demo-importer',
            'wishlist',
            'quick-view',
            'advanced-filters',
            'marketplace',
            'classifieds',
            'booking',
            'events',
            'subscriptions',
            'affiliate',
            'wholesale'
        );
        
        $enabled_modules = get_option('aqualuxe_enabled_modules', $default_modules);
        return apply_filters('aqualuxe_enabled_modules', $enabled_modules);
    }
    
    /**
     * Enqueue scripts
     */
    public function enqueue_scripts() {
        $assets = $this->get_asset_manifest();
        
        // Main app script
        if (isset($assets['js/app.js'])) {
            wp_enqueue_script(
                'aqualuxe-app',
                AQUALUXE_ASSETS_URI . '/' . $assets['js/app.js'],
                array('jquery'),
                AQUALUXE_VERSION,
                true
            );
        }
        
        // WooCommerce scripts
        if (class_exists('WooCommerce') && isset($assets['js/woocommerce.js'])) {
            wp_enqueue_script(
                'aqualuxe-woocommerce',
                AQUALUXE_ASSETS_URI . '/' . $assets['js/woocommerce.js'],
                array('jquery', 'wc-add-to-cart'),
                AQUALUXE_VERSION,
                true
            );
        }
        
        // Localize script
        wp_localize_script('aqualuxe-app', 'aqualuxe_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe_ajax_nonce'),
            'is_rtl' => is_rtl(),
            'language' => get_locale(),
        ));
    }
    
    /**
     * Enqueue styles
     */
    public function enqueue_styles() {
        $assets = $this->get_asset_manifest();
        
        // Main stylesheet
        if (isset($assets['css/app.css'])) {
            wp_enqueue_style(
                'aqualuxe-style',
                AQUALUXE_ASSETS_URI . '/' . $assets['css/app.css'],
                array(),
                AQUALUXE_VERSION
            );
        }
        
        // WooCommerce styles
        if (class_exists('WooCommerce') && isset($assets['css/woocommerce.css'])) {
            wp_enqueue_style(
                'aqualuxe-woocommerce',
                AQUALUXE_ASSETS_URI . '/' . $assets['css/woocommerce.css'],
                array('aqualuxe-style'),
                AQUALUXE_VERSION
            );
        }
    }
    
    /**
     * Get asset manifest
     */
    private function get_asset_manifest() {
        static $manifest = null;
        
        if ($manifest === null) {
            $manifest_path = AQUALUXE_THEME_DIR . '/assets/mix-manifest.json';
            if (file_exists($manifest_path)) {
                $manifest = json_decode(file_get_contents($manifest_path), true);
                // Remove leading slash from manifest keys
                $manifest = array_combine(
                    array_map(function($key) { return ltrim($key, '/'); }, array_keys($manifest)),
                    array_values($manifest)
                );
            } else {
                $manifest = array();
            }
        }
        
        return $manifest;
    }
    
    /**
     * Load textdomain
     */
    public function load_textdomain() {
        load_theme_textdomain('aqualuxe', AQUALUXE_THEME_DIR . '/languages');
    }
    
    /**
     * Register sidebars
     */
    public function register_sidebars() {
        register_sidebar(array(
            'name' => esc_html__('Primary Sidebar', 'aqualuxe'),
            'id' => 'sidebar-primary',
            'description' => esc_html__('Main sidebar for blog and pages', 'aqualuxe'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ));
        
        register_sidebar(array(
            'name' => esc_html__('Shop Sidebar', 'aqualuxe'),
            'id' => 'sidebar-shop',
            'description' => esc_html__('Sidebar for WooCommerce shop pages', 'aqualuxe'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ));
        
        register_sidebar(array(
            'name' => esc_html__('Footer Widget Area 1', 'aqualuxe'),
            'id' => 'footer-1',
            'description' => esc_html__('First footer widget area', 'aqualuxe'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h4 class="widget-title">',
            'after_title' => '</h4>',
        ));
        
        register_sidebar(array(
            'name' => esc_html__('Footer Widget Area 2', 'aqualuxe'),
            'id' => 'footer-2',
            'description' => esc_html__('Second footer widget area', 'aqualuxe'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h4 class="widget-title">',
            'after_title' => '</h4>',
        ));
        
        register_sidebar(array(
            'name' => esc_html__('Footer Widget Area 3', 'aqualuxe'),
            'id' => 'footer-3',
            'description' => esc_html__('Third footer widget area', 'aqualuxe'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h4 class="widget-title">',
            'after_title' => '</h4>',
        ));
        
        register_sidebar(array(
            'name' => esc_html__('Footer Widget Area 4', 'aqualuxe'),
            'id' => 'footer-4',
            'description' => esc_html__('Fourth footer widget area', 'aqualuxe'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h4 class="widget-title">',
            'after_title' => '</h4>',
        ));
    }
}

// Initialize theme
new AquaLuxe_Theme();

/**
 * Helper function to get theme option
 */
function aqualuxe_get_option($option, $default = '') {
    return get_option('aqualuxe_' . $option, $default);
}

/**
 * Helper function to check if WooCommerce is active
 */
function aqualuxe_is_woocommerce_active() {
    return class_exists('WooCommerce');
}

/**
 * Helper function to get asset URL with cache busting
 */
function aqualuxe_asset($path) {
    static $manifest = null;
    
    if ($manifest === null) {
        $manifest_path = AQUALUXE_THEME_DIR . '/assets/mix-manifest.json';
        if (file_exists($manifest_path)) {
            $manifest = json_decode(file_get_contents($manifest_path), true);
        } else {
            $manifest = array();
        }
    }
    
    $path = '/' . ltrim($path, '/');
    $versioned_path = isset($manifest[$path]) ? $manifest[$path] : $path;
    
    return AQUALUXE_ASSETS_URI . $versioned_path;
}