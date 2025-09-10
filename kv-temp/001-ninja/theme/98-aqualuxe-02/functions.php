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

    // Styles.
    wp_enqueue_style('aqualuxe-app', $get('css/app.css'), [], null, 'all');
    // Scripts (deferred).
    wp_enqueue_script('aqualuxe-app', $get('js/app.js'), [], null, true);

    // Localize data & nonce for AJAX.
    wp_localize_script('aqualuxe-app', 'AQUALUXE', [
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce'   => wp_create_nonce('aqualuxe_nonce'),
        'i18n'    => [
            'close' => esc_html__('Close', 'aqualuxe'),
        ],
    ]);
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
