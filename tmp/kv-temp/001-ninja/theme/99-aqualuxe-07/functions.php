<?php
/**
 * AquaLuxe Theme bootstrap
 *
 * @package AquaLuxe
 */

// Prevent direct access.
if (! defined('ABSPATH')) {
    exit;
}

define('AQUALUXE_VERSION', '1.0.0');
define('AQUALUXE_THEME_DIR', __DIR__);
define('AQUALUXE_INC_DIR', AQUALUXE_THEME_DIR . '/inc');
// Load WP stubs early for static analysis outside WP.
if (! function_exists('add_action')) { require AQUALUXE_INC_DIR . '/compat/wp_stubs.php'; }
define('AQUALUXE_THEME_URI', function_exists('get_template_directory_uri') ? get_template_directory_uri() : '');

// PSR-4 like autoloader for theme classes (inc/ namespaces only).
spl_autoload_register(function ($class) {
    if (strpos($class, 'AquaLuxe\\') !== 0) {
        return;
    }
    $relative = str_replace(['AquaLuxe\\', '\\'], ['', '/'], $class);
    $DS = '/';
    $EXT = '.php';
    // lowercase path candidate
    $candLower = strtolower($relative) . $EXT;
    // snake_case path candidate for modules (e.g., Modules/DarkMode/Module -> modules/dark_mode/module.php)
    $segments = explode('/', $relative);
    $segments = array_map(function($seg){
        $seg = preg_replace('/([a-z])([A-Z])/', '$1_$2', $seg);
        return strtolower($seg);
    }, $segments);
    $candSnake = implode($DS, $segments) . $EXT;
    $paths = [
        AQUALUXE_INC_DIR . '/' . $candLower,
        AQUALUXE_INC_DIR . '/' . $candSnake,
        AQUALUXE_THEME_DIR . '/modules/' . $candLower,
        AQUALUXE_THEME_DIR . '/modules/' . $candSnake,
    ];
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

// Require helpers that are not class-based.
require_once AQUALUXE_INC_DIR . '/helpers/safe_functions.php';

// Boot the theme core.
add_action('after_setup_theme', function () {
    (new AquaLuxe\Core\Theme())->boot();
});

// Register blocks of the theme that do not depend on Theme setup timing.
add_action('init', function () {
    // Custom post types & taxonomies.
    (new AquaLuxe\Core\Content())->register();

    // Shortcodes.
    (new AquaLuxe\Core\Shortcodes())->register();
});

// Admin-only features.
if (is_admin()) {
    (new AquaLuxe\Admin\Admin())->boot();
}

// WooCommerce optional support (dual-state architecture: works with or without WC).
if (class_exists('WooCommerce')) {
    (new AquaLuxe\Integrations\WooCommerceCompat())->boot();
}

// Modules bootstrap (feature toggles are filterable).
add_action('after_setup_theme', function () {
    $default_modules = [
        'dark_mode'   => true,
        'multilingual'=> true,
        'importer'    => true,
    ];
    $modules = apply_filters('aqualuxe/modules', $default_modules);

    foreach ($modules as $module => $enabled) {
        if (! $enabled) { continue; }
        $class = 'AquaLuxe\\Modules\\' . str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $module))) . '\\Module';
        if (class_exists($class)) {
            (new $class())->boot();
        }
    }
});

// Enqueue compiled assets with cache busting from mix-manifest.json. Never enqueue raw files.
add_action('wp_enqueue_scripts', function () {
    $manifest = AQUALUXE_THEME_DIR . '/assets/dist/mix-manifest.json';
    $map = [];
    if (file_exists($manifest)) {
        $json = file_get_contents($manifest);
        $map = json_decode($json, true) ?: [];
    }
    $ver = AQUALUXE_VERSION;
    $get = function ($file) use ($map, $ver) {
        $key = '/' . ltrim($file, '/');
        $rev = isset($map[$key]) ? $map[$key] : $key;
        return add_query_arg('ver', $ver, AQUALUXE_THEME_URI . '/assets/dist' . $rev);
    };

    // Main styles and scripts
    wp_enqueue_style('aqualuxe-main', $get('css/main.css'), [], null, 'all');
    wp_enqueue_script('aqualuxe-main', $get('js/main.js'), [], null, true);
    
    // Module scripts (conditional loading)
    if (apply_filters('aqualuxe/modules', ['dark_mode' => true])['dark_mode']) {
        wp_enqueue_script('aqualuxe-dark-mode', $get('js/dark-mode.js'), ['aqualuxe-main'], null, true);
    }
    
    if (apply_filters('aqualuxe/modules', ['multilingual' => true])['multilingual']) {
        wp_enqueue_script('aqualuxe-multilingual', $get('js/multilingual.js'), ['aqualuxe-main'], null, true);
    }
    
    // Load multicurrency module
    wp_enqueue_script('aqualuxe-multicurrency', $get('js/multicurrency.js'), ['aqualuxe-main'], null, true);

    // WooCommerce specific assets
    if (class_exists('WooCommerce')) {
        wp_enqueue_style('aqualuxe-woocommerce', $get('css/woocommerce.css'), ['aqualuxe-main'], null, 'all');
        wp_enqueue_script('aqualuxe-woocommerce', $get('js/woocommerce.js'), ['aqualuxe-main'], null, true);
        
        if (is_shop() || is_product_category() || is_product_tag()) {
            wp_enqueue_script('aqualuxe-shop', $get('js/shop.js'), ['aqualuxe-woocommerce'], null, true);
        }
    }

    // Localize data & nonce for AJAX.
    wp_localize_script('aqualuxe-main', 'AQUALUXE', [
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce'   => wp_create_nonce('aqualuxe_nonce'),
        'baseUrl' => home_url('/'),
        'themeUrl' => AQUALUXE_THEME_URI,
        'currencies' => apply_filters('aqualuxe/currencies', [
            'USD' => ['symbol' => '$', 'name' => 'US Dollar'],
            'EUR' => ['symbol' => '€', 'name' => 'Euro'],
            'GBP' => ['symbol' => '£', 'name' => 'British Pound'],
            'JPY' => ['symbol' => '¥', 'name' => 'Japanese Yen'],
        ]),
        'languages' => apply_filters('aqualuxe/languages', [
            'en' => ['name' => 'English', 'flag' => '🇺🇸'],
            'es' => ['name' => 'Español', 'flag' => '🇪🇸'],
            'fr' => ['name' => 'Français', 'flag' => '🇫🇷'],
        ]),
        'i18n'    => [
            'close' => esc_html__('Close', 'aqualuxe'),
            'loading' => esc_html__('Loading...', 'aqualuxe'),
            'error' => esc_html__('An error occurred', 'aqualuxe'),
        ],
    ]);
});

// Admin styles and scripts
add_action('admin_enqueue_scripts', function ($hook) {
    $manifest = AQUALUXE_THEME_DIR . '/assets/dist/mix-manifest.json';
    $map = [];
    if (file_exists($manifest)) {
        $json = file_get_contents($manifest);
        $map = json_decode($json, true) ?: [];
    }
    $ver = AQUALUXE_VERSION;
    $get = function ($file) use ($map, $ver) {
        $key = '/' . ltrim($file, '/');
        $rev = isset($map[$key]) ? $map[$key] : $key;
        return add_query_arg('ver', $ver, AQUALUXE_THEME_URI . '/assets/dist' . $rev);
    };

    // Load admin assets on theme pages
    if (strpos($hook, 'aqualuxe') !== false || $hook === 'themes.php') {
        wp_enqueue_style('aqualuxe-admin', $get('css/admin.css'), [], null, 'all');
        wp_enqueue_script('aqualuxe-admin', $get('js/admin.js'), ['jquery'], null, true);
        
        // Enqueue media uploader
        wp_enqueue_media();
        
        // Color picker
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker');
    }
});

// Customizer scripts
add_action('customize_preview_init', function () {
    $manifest = AQUALUXE_THEME_DIR . '/assets/dist/mix-manifest.json';
    $map = [];
    if (file_exists($manifest)) {
        $json = file_get_contents($manifest);
        $map = json_decode($json, true) ?: [];
    }
    $ver = AQUALUXE_VERSION;
    $get = function ($file) use ($map, $ver) {
        $key = '/' . ltrim($file, '/');
        $rev = isset($map[$key]) ? $map[$key] : $key;
        return add_query_arg('ver', $ver, AQUALUXE_THEME_URI . '/assets/dist' . $rev);
    };

    wp_enqueue_script('aqualuxe-customizer', $get('js/customizer.js'), ['jquery', 'customize-preview'], null, true);
});

// Editor styles
add_action('enqueue_block_editor_assets', function () {
    $manifest = AQUALUXE_THEME_DIR . '/assets/dist/mix-manifest.json';
    $map = [];
    if (file_exists($manifest)) {
        $json = file_get_contents($manifest);
        $map = json_decode($json, true) ?: [];
    }
    $ver = AQUALUXE_VERSION;
    $get = function ($file) use ($map, $ver) {
        $key = '/' . ltrim($file, '/');
        $rev = isset($map[$key]) ? $map[$key] : $key;
        return add_query_arg('ver', $ver, AQUALUXE_THEME_URI . '/assets/dist' . $rev);
    };

    wp_enqueue_style('aqualuxe-editor', $get('css/editor.css'), [], null, 'all');
});

// Register navigation menus and theme supports.
add_action('after_setup_theme', function () {
    load_theme_textdomain('aqualuxe', get_template_directory() . '/languages');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('automatic-feed-links');
    add_theme_support('responsive-embeds');
    add_theme_support('html5', ['search-form','comment-form','comment-list','gallery','caption','style','script']);
    add_theme_support('custom-logo');
    add_theme_support('customize-selective-refresh-widgets');
    add_theme_support('editor-styles');
    add_theme_support('wp-block-styles');
    register_nav_menus([
        'primary'   => esc_html__('Primary Menu', 'aqualuxe'),
        'footer'    => esc_html__('Footer Menu', 'aqualuxe'),
        'account'   => esc_html__('Account Menu', 'aqualuxe'),
    ]);
});

// Widgets.
add_action('widgets_init', function () {
    register_sidebar([
        'name'          => esc_html__('Sidebar', 'aqualuxe'),
        'id'            => 'sidebar-1',
        'description'   => esc_html__('Add widgets here.', 'aqualuxe'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ]);
});

// Security: set Content Security Policy headers for theme assets optionally (can be adjusted by server config).
add_action('send_headers', function () {
    // Example: header('Content-Security-Policy: default-src \'self\'');
});

// Helper function for social icons
function aqualuxe_get_social_icon($platform) {
    $icons = [
        'facebook' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>',
        'twitter' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>',
        'instagram' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 6.62 5.367 11.987 11.988 11.987s11.987-5.367 11.987-11.987C24.014 5.367 18.647.001 12.017.001zM8.449 16.988c-1.297 0-2.448-.529-3.285-1.382-.838-.854-1.367-2.025-1.367-3.337 0-1.312.529-2.483 1.367-3.337.837-.853 1.988-1.382 3.285-1.382s2.448.529 3.285 1.382c.838.854 1.367 2.025 1.367 3.337 0 1.312-.529 2.483-1.367 3.337-.837.853-1.988 1.382-3.285 1.382z"/></svg>',
        'youtube' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>',
    ];
    
    return $icons[$platform] ?? '';
}
