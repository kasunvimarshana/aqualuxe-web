<?php
/**
 * AquaLuxe Theme Functions
 * 
 * Main theme setup and configuration file implementing modular architecture
 * with SOLID principles and WordPress best practices.
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
define('AQUALUXE_THEME_URL', get_template_directory_uri());
define('AQUALUXE_ASSETS_URL', AQUALUXE_THEME_URL . '/assets/dist');
define('AQUALUXE_INCLUDES_DIR', AQUALUXE_THEME_DIR . '/inc');
define('AQUALUXE_MODULES_DIR', AQUALUXE_THEME_DIR . '/modules');

/**
 * Autoloader for theme classes
 */
spl_autoload_register(function ($class_name) {
    // Only autoload our theme classes
    if (strpos($class_name, 'AquaLuxe\\') !== 0) {
        return;
    }

    // Convert namespace to file path
    $class_path = str_replace(['AquaLuxe\\', '\\'], ['', '/'], $class_name);
    $file_path = AQUALUXE_INCLUDES_DIR . '/classes/' . $class_path . '.php';

    if (file_exists($file_path)) {
        require_once $file_path;
    }
});

/**
 * Theme Setup Class
 */
class AquaLuxe_Theme_Setup
{
    /**
     * Initialize theme
     */
    public static function init()
    {
        add_action('after_setup_theme', [__CLASS__, 'setup']);
        add_action('wp_enqueue_scripts', [__CLASS__, 'enqueue_assets']);
        add_action('admin_enqueue_scripts', [__CLASS__, 'enqueue_admin_assets']);
        add_action('init', [__CLASS__, 'load_textdomain']);
        
        // Load core functionality
        self::load_core_includes();
        
        // Initialize modules
        self::init_modules();
    }

    /**
     * Theme setup
     */
    public static function setup()
    {
        // Add theme support
        add_theme_support('post-thumbnails');
        add_theme_support('title-tag');
        add_theme_support('html5', [
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script'
        ]);
        add_theme_support('customize-selective-refresh-widgets');
        add_theme_support('custom-logo');
        add_theme_support('custom-background');
        add_theme_support('custom-header');
        add_theme_support('responsive-embeds');
        add_theme_support('align-wide');
        add_theme_support('editor-styles');
        add_theme_support('wp-block-styles');
        
        // WooCommerce support
        add_theme_support('woocommerce');
        add_theme_support('wc-product-gallery-zoom');
        add_theme_support('wc-product-gallery-lightbox');
        add_theme_support('wc-product-gallery-slider');

        // Register navigation menus
        register_nav_menus([
            'primary' => esc_html__('Primary Menu', 'aqualuxe'),
            'footer' => esc_html__('Footer Menu', 'aqualuxe'),
            'mobile' => esc_html__('Mobile Menu', 'aqualuxe'),
        ]);

        // Set content width
        if (!isset($content_width)) {
            $content_width = 1200;
        }
    }

    /**
     * Load text domain for translations
     */
    public static function load_textdomain()
    {
        load_theme_textdomain('aqualuxe', AQUALUXE_THEME_DIR . '/languages');
    }

    /**
     * Enqueue front-end assets
     */
    public static function enqueue_assets()
    {
        $manifest = self::get_asset_manifest();
        
        // Main stylesheet
        if (isset($manifest['css/app.css'])) {
            wp_enqueue_style(
                'aqualuxe-main',
                AQUALUXE_ASSETS_URL . '/' . $manifest['css/app.css'],
                [],
                AQUALUXE_VERSION
            );
        }

        // Main JavaScript
        if (isset($manifest['js/app.js'])) {
            wp_enqueue_script(
                'aqualuxe-main',
                AQUALUXE_ASSETS_URL . '/' . $manifest['js/app.js'],
                ['jquery'],
                AQUALUXE_VERSION,
                true
            );
        }

        // Localize script
        wp_localize_script('aqualuxe-main', 'aqualuxe_ajax', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe_nonce'),
            'strings' => [
                'loading' => esc_html__('Loading...', 'aqualuxe'),
                'error' => esc_html__('An error occurred. Please try again.', 'aqualuxe'),
            ]
        ]);
    }

    /**
     * Enqueue admin assets
     */
    public static function enqueue_admin_assets($hook)
    {
        $manifest = self::get_asset_manifest();
        
        if (isset($manifest['css/admin.css'])) {
            wp_enqueue_style(
                'aqualuxe-admin',
                AQUALUXE_ASSETS_URL . '/' . $manifest['css/admin.css'],
                [],
                AQUALUXE_VERSION
            );
        }

        if (isset($manifest['js/admin.js'])) {
            wp_enqueue_script(
                'aqualuxe-admin',
                AQUALUXE_ASSETS_URL . '/' . $manifest['js/admin.js'],
                ['jquery'],
                AQUALUXE_VERSION,
                true
            );
        }
    }

    /**
     * Get asset manifest for cache busting
     */
    private static function get_asset_manifest()
    {
        static $manifest = null;
        
        if ($manifest === null) {
            $manifest_path = AQUALUXE_THEME_DIR . '/assets/dist/mix-manifest.json';
            if (file_exists($manifest_path)) {
                $manifest = json_decode(file_get_contents($manifest_path), true);
                // Remove leading slash from paths
                $manifest = array_map(function($path) {
                    return ltrim($path, '/');
                }, $manifest);
            } else {
                $manifest = [];
            }
        }
        
        return $manifest;
    }

    /**
     * Load core includes
     */
    private static function load_core_includes()
    {
        $includes = [
            'customizer.php',
            'template-functions.php',
            'template-hooks.php',
            'custom-post-types.php',
            'custom-taxonomies.php',
            'meta-fields.php',
            'admin/admin-init.php',
            'woocommerce/wc-integration.php',
        ];

        foreach ($includes as $file) {
            $path = AQUALUXE_INCLUDES_DIR . '/' . $file;
            if (file_exists($path)) {
                require_once $path;
            }
        }
    }

    /**
     * Initialize modules
     */
    private static function init_modules()
    {
        if (!is_dir(AQUALUXE_MODULES_DIR)) {
            return;
        }

        $modules = scandir(AQUALUXE_MODULES_DIR);
        foreach ($modules as $module) {
            if ($module === '.' || $module === '..') {
                continue;
            }

            $module_init = AQUALUXE_MODULES_DIR . '/' . $module . '/init.php';
            if (file_exists($module_init)) {
                require_once $module_init;
            }
        }
    }
}

// Initialize theme
AquaLuxe_Theme_Setup::init();