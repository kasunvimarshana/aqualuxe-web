<?php
/**
 * AquaLuxe Theme Functions
 *
 * Main theme file that bootstraps the theme and loads all required components.
 * Implements a modular architecture following SOLID principles.
 *
 * @package AquaLuxe
 * @version 1.0.0
 * @author  AquaLuxe Development Team
 * @license GPL-2.0-or-later
 * @link    https://github.com/kasunvimarshana/aqualuxe-web
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define theme constants
define('AQUALUXE_VERSION', '1.0.0');
define('AQUALUXE_PATH', get_template_directory());
define('AQUALUXE_URI', get_template_directory_uri());
define('AQUALUXE_ASSETS_URI', AQUALUXE_URI . '/assets/dist');
define('AQUALUXE_MIN_PHP', '8.0');
define('AQUALUXE_MIN_WP', '6.0');

/**
 * Check PHP and WordPress version compatibility
 */
if (version_compare(PHP_VERSION, AQUALUXE_MIN_PHP, '<')) {
    add_action('admin_notices', function() {
        echo '<div class="notice notice-error"><p>';
        printf(
            esc_html__('AquaLuxe theme requires PHP %s or higher. You are running %s.', 'aqualuxe'),
            AQUALUXE_MIN_PHP,
            PHP_VERSION
        );
        echo '</p></div>';
    });
    return;
}

if (version_compare(get_bloginfo('version'), AQUALUXE_MIN_WP, '<')) {
    add_action('admin_notices', function() {
        echo '<div class="notice notice-error"><p>';
        printf(
            esc_html__('AquaLuxe theme requires WordPress %s or higher. You are running %s.', 'aqualuxe'),
            AQUALUXE_MIN_WP,
            get_bloginfo('version')
        );
        echo '</p></div>';
    });
    return;
}

/**
 * Autoloader for theme classes
 */
spl_autoload_register(function ($class) {
    // Only autoload our classes
    if (strpos($class, 'AquaLuxe\\') !== 0) {
        return;
    }

    // Convert namespace to file path
    $relative_class = str_replace('AquaLuxe\\', '', $class);
    $relative_class = str_replace('\\', DIRECTORY_SEPARATOR, $relative_class);
    
    // Build the full file path
    $file = AQUALUXE_PATH . DIRECTORY_SEPARATOR . strtolower($relative_class) . '.php';
    
    if (file_exists($file)) {
        require_once $file;
        return;
    }
    
    // Fallback: try without lowercase conversion for specific cases
    $file = AQUALUXE_PATH . DIRECTORY_SEPARATOR . $relative_class . '.php';
    
    if (file_exists($file)) {
        require_once $file;
        return;
    }
});

/**
 * Initialize the theme
 */
require_once AQUALUXE_PATH . '/core/Theme.php';

/**
 * Bootstrap the theme
 */
function aqualuxe_init() {
    try {
        $theme = AquaLuxe\Core\Theme::getInstance();
        $theme->init();
    } catch (Exception $e) {
        // Log error and show admin notice in development mode
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log('AquaLuxe Theme Error: ' . $e->getMessage());
            
            add_action('admin_notices', function() use ($e) {
                echo '<div class="notice notice-error"><p>';
                echo esc_html__('AquaLuxe Theme Error: ', 'aqualuxe') . esc_html($e->getMessage());
                echo '</p></div>';
            });
        }
    }
}

// Initialize after plugins are loaded to check for WooCommerce
add_action('after_setup_theme', 'aqualuxe_init');

/**
 * Emergency fallback template loader
 * Ensures basic functionality if main theme classes fail
 */
if (!function_exists('aqualuxe_emergency_fallback')) {
    function aqualuxe_emergency_fallback() {
        if (!did_action('after_setup_theme')) {
            return;
        }

        // Basic theme support
        add_theme_support('title-tag');
        add_theme_support('post-thumbnails');
        add_theme_support('html5', [
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
        ]);

        // Enqueue basic styles
        wp_enqueue_style(
            'aqualuxe-emergency',
            AQUALUXE_ASSETS_URI . '/css/app.css',
            [],
            AQUALUXE_VERSION
        );
    }
}

// Fallback activation if theme bootstrap fails
add_action('wp_loaded', function() {
    if (!class_exists('AquaLuxe\\Core\\Theme')) {
        aqualuxe_emergency_fallback();
    }
});

/**
 * Global utility functions
 */

/**
 * Get asset URL with cache busting
 */
function aqualuxe_asset($path) {
    static $manifest = null;
    
    if ($manifest === null) {
        $manifest_path = AQUALUXE_PATH . '/assets/dist/mix-manifest.json';
        $manifest = file_exists($manifest_path) ? json_decode(file_get_contents($manifest_path), true) : [];
    }
    
    $path = '/' . ltrim($path, '/');
    $versioned_path = $manifest[$path] ?? $path;
    
    return AQUALUXE_ASSETS_URI . $versioned_path;
}

/**
 * Check if WooCommerce is active
 */
function aqualuxe_is_woocommerce_active() {
    return class_exists('WooCommerce');
}

/**
 * Get theme option with fallback
 */
function aqualuxe_get_option($option, $default = '') {
    return get_theme_mod($option, $default);
}

/**
 * Render template part with data
 */
function aqualuxe_get_template_part($slug, $name = null, $args = []) {
    if (!empty($args)) {
        extract($args, EXTR_SKIP);
    }
    
    $templates = [];
    if ($name) {
        $templates[] = "{$slug}-{$name}.php";
    }
    $templates[] = "{$slug}.php";
    
    $template = locate_template($templates);
    if ($template) {
        include $template;
    }
}