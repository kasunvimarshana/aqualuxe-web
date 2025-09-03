<?php
/**
 * Theme bootstrap: service container, providers, hooks.
 *
 * @package Aqualuxe
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// PSR-4 like autoloader for theme namespace.
spl_autoload_register(function ($class) {
    $prefix = 'Aqualuxe\\';
    $base_dir = __DIR__ . '/inc/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    $relative_class = substr($class, $len);
    // Normalize namespace separators to directory separators
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

// Minimal DI Container.
require_once __DIR__ . '/inc/Support/Container.php';

add_action('after_setup_theme', function () {
    // Load text domain for i18n.
    load_theme_textdomain('aqualuxe', get_template_directory() . '/languages');

    // Theme supports.
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'script', 'style']);
    add_theme_support('customize-selective-refresh-widgets');
    add_theme_support('responsive-embeds');

    // Register nav menus.
    register_nav_menus([
        'primary' => __('Primary Menu', 'aqualuxe'),
        'footer' => __('Footer Menu', 'aqualuxe'),
    ]);
});

// Register service providers.
add_action('init', function () {
    $container = Aqualuxe\Support\Container::getInstance();

    $container->register(new Aqualuxe\Providers\Assets_Service_Provider());
    $container->register(new Aqualuxe\Providers\Security_Service_Provider());
    $container->register(new Aqualuxe\Providers\REST_Service_Provider());
    $container->register(new Aqualuxe\Providers\SEO_Service_Provider());
    $container->register(new Aqualuxe\Providers\A11y_Service_Provider());
    $container->register(new Aqualuxe\Providers\Performance_Service_Provider());
    $container->register(new Aqualuxe\Providers\Tenancy_Service_Provider());
    $container->register(new Aqualuxe\Providers\Multilingual_Service_Provider());
    $container->register(new Aqualuxe\Providers\Currency_Service_Provider());
    $container->register(new Aqualuxe\Providers\Roles_Service_Provider());
    $container->register(new Aqualuxe\Providers\Skins_Service_Provider());
    $container->register(new Aqualuxe\Shortcodes\Shortcodes_Service_Provider());
    $container->register(new Aqualuxe\Providers\Admin_Service_Provider());
    $container->register(new Aqualuxe\Providers\Vendors_Service_Provider());

    // Example: CPT and Taxonomies providers (extensible).
    $container->register(new Aqualuxe\Providers\Content_Types_Service_Provider());
});

// Boot all registered providers after theme setup.
add_action('after_setup_theme', function () {
    Aqualuxe\Support\Container::getInstance()->boot();
});

// Fallback: enqueue minimal CSS when assets not built.
add_action('wp_enqueue_scripts', function () {
    $path = get_template_directory_uri() . '/assets/dist';
    $style = $path . '/theme.css';
    $script = $path . '/theme.js';

    if (!wp_style_is('aqualuxe-theme', 'enqueued') && @get_headers($style)) {
        wp_enqueue_style('aqualuxe-theme', $style, [], null, 'all');
    }
    if (!wp_script_is('aqualuxe-theme', 'enqueued') && @get_headers($script)) {
        wp_enqueue_script('aqualuxe-theme', $script, [], null, true);
    }
});
