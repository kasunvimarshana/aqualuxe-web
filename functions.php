<?php
/**
 * AquaLuxe Theme Functions
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

// Define theme constants
define('AQUALUXE_VERSION', '1.0.0');
define('AQUALUXE_THEME_DIR', get_template_directory());
define('AQUALUXE_THEME_URI', get_template_directory_uri());

/**
 * Autoloader for theme classes
 */
spl_autoload_register(function ($class) {
    // Check if it's an AquaLuxe class
    if (strpos($class, 'AquaLuxe\\') !== 0) {
        return;
    }

    // Convert namespace to file path
    $class_file = str_replace('AquaLuxe\\', '', $class);
    $class_file = str_replace('\\', '/', $class_file);
    
    // Convert to kebab-case for directories and add class prefix for files
    $parts = explode('/', $class_file);
    $filename = 'class-' . strtolower(str_replace('_', '-', array_pop($parts)));
    $path_parts = array_map(function($part) {
        return strtolower(str_replace('_', '-', $part));
    }, $parts);
    
    $file_path = AQUALUXE_THEME_DIR . '/' . implode('/', $path_parts) . '/' . $filename . '.php';
    
    if (file_exists($file_path)) {
        require_once $file_path;
    }
});

/**
 * Theme setup
 */
function aqualuxe_theme_setup() {
    // Initialize core theme setup
    \AquaLuxe\Core\Theme_Setup::get_instance();
}
add_action('after_setup_theme', 'aqualuxe_theme_setup', 0);

// Load theme components
require_once AQUALUXE_THEME_DIR . '/inc/customizer.php';
require_once AQUALUXE_THEME_DIR . '/inc/custom-post-types.php';
require_once AQUALUXE_THEME_DIR . '/inc/custom-taxonomies.php';
require_once AQUALUXE_THEME_DIR . '/inc/meta-fields.php';
require_once AQUALUXE_THEME_DIR . '/inc/template-hooks.php';
require_once AQUALUXE_THEME_DIR . '/inc/template-functions.php';
require_once AQUALUXE_THEME_DIR . '/inc/admin/admin-init.php';

// Load WooCommerce integration if WooCommerce is active
if (class_exists('WooCommerce')) {
    require_once AQUALUXE_THEME_DIR . '/inc/woocommerce/wc-integration.php';
}

/**
 * Get theme version
 *
 * @return string
 */
function aqualuxe_get_version() {
    return AQUALUXE_VERSION;
}

/**
 * Check if WooCommerce is active
 *
 * @return bool
 */
function aqualuxe_is_woocommerce_activated() {
    return class_exists('WooCommerce');
}

/**
 * Display SVG icon
 *
 * @param string $icon Icon name
 * @param array  $args Icon arguments
 * @return string
 */
function aqualuxe_get_svg_icon($icon, $args = array()) {
    $defaults = array(
        'size'  => 24,
        'class' => '',
    );
    
    $args = wp_parse_args($args, $defaults);
    
    $icons = array(
        'menu' => '<svg class="' . esc_attr($args['class']) . '" width="' . esc_attr($args['size']) . '" height="' . esc_attr($args['size']) . '" fill="currentColor" viewBox="0 0 24 24"><path d="M3 12h18M3 6h18M3 18h18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>',
        'close' => '<svg class="' . esc_attr($args['class']) . '" width="' . esc_attr($args['size']) . '" height="' . esc_attr($args['size']) . '" fill="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>',
        'search' => '<svg class="' . esc_attr($args['class']) . '" width="' . esc_attr($args['size']) . '" height="' . esc_attr($args['size']) . '" fill="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>',
        'cart' => '<svg class="' . esc_attr($args['class']) . '" width="' . esc_attr($args['size']) . '" height="' . esc_attr($args['size']) . '" fill="currentColor" viewBox="0 0 24 24"><path d="M3 3h2l.4 2M7 13h10l4-8H5.4m1.6 8L6 5H3m4 8v6a1 1 0 001 1h9a1 1 0 001-1v-6M8 19h.01M20 19h.01" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
        'user' => '<svg class="' . esc_attr($args['class']) . '" width="' . esc_attr($args['size']) . '" height="' . esc_attr($args['size']) . '" fill="currentColor" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
        'heart' => '<svg class="' . esc_attr($args['class']) . '" width="' . esc_attr($args['size']) . '" height="' . esc_attr($args['size']) . '" fill="currentColor" viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
        'star' => '<svg class="' . esc_attr($args['class']) . '" width="' . esc_attr($args['size']) . '" height="' . esc_attr($args['size']) . '" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>',
    );
    
    return isset($icons[$icon]) ? $icons[$icon] : '';
}

/**
 * Display SVG icon
 *
 * @param string $icon Icon name
 * @param array  $args Icon arguments
 */
function aqualuxe_svg_icon($icon, $args = array()) {
    echo aqualuxe_get_svg_icon($icon, $args);
}

/**
 * Custom excerpt length
 *
 * @param int $length Excerpt length
 * @return int
 */
function aqualuxe_excerpt_length($length) {
    return get_theme_mod('aqualuxe_excerpt_length', 25);
}
add_filter('excerpt_length', 'aqualuxe_excerpt_length');

/**
 * Custom excerpt more
 *
 * @param string $more Excerpt more text
 * @return string
 */
function aqualuxe_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'aqualuxe_excerpt_more');

/**
 * Add theme support for WooCommerce
 */
if (aqualuxe_is_woocommerce_activated()) {
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
}
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