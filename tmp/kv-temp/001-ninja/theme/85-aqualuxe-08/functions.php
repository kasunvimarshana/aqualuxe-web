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
    add_theme_support('editor-styles');
    add_theme_support('wp-block-styles');
    add_theme_support('align-wide');

    // Register nav menus.
    register_nav_menus([
        'primary' => __('Primary Menu', 'aqualuxe'),
        'footer' => __('Footer Menu', 'aqualuxe'),
    ]);
});

// Register service providers using config/modules.php
add_action('init', function () {
    $container = Aqualuxe\Support\Container::getInstance();
    $config_file = __DIR__ . '/config/modules.php';
    $modules = file_exists($config_file) ? (array) require $config_file : [];
    if (!$modules) {
        $modules = [
            Aqualuxe\Providers\Assets_Service_Provider::class => true,
            Aqualuxe\Providers\Security_Service_Provider::class => true,
            Aqualuxe\Providers\REST_Service_Provider::class => true,
            Aqualuxe\Providers\SEO_Service_Provider::class => true,
            Aqualuxe\Providers\A11y_Service_Provider::class => true,
            Aqualuxe\Providers\Performance_Service_Provider::class => true,
            Aqualuxe\Providers\Customizer_Service_Provider::class => true,
            Aqualuxe\Providers\Admin_Service_Provider::class => true,
            Aqualuxe\Providers\Tenancy_Service_Provider::class => true,
            Aqualuxe\Providers\Multilingual_Service_Provider::class => true,
            Aqualuxe\Providers\Currency_Service_Provider::class => true,
            Aqualuxe\Providers\Roles_Service_Provider::class => true,
            Aqualuxe\Providers\Skins_Service_Provider::class => true,
            Aqualuxe\Providers\Vendors_Service_Provider::class => true,
            Aqualuxe\Providers\Content_Types_Service_Provider::class => true,
            Aqualuxe\Shortcodes\Shortcodes_Service_Provider::class => true,
            Aqualuxe\Providers\Importer_Service_Provider::class => true,
        ];
    }

    // Allow programmatic overrides
    $modules = apply_filters('aqualuxe_modules', $modules);
    foreach ($modules as $provider => $enabled) {
        if ($enabled && class_exists($provider)) {
            $container->register(new $provider());
        }
    }

    // WooCommerce dual-state provider
    if (class_exists('WooCommerce') && class_exists('Aqualuxe\\Providers\\Woo_Service_Provider')) {
        $container->register(new Aqualuxe\Providers\Woo_Service_Provider());
    }
});

// Boot all registered providers after they are registered on init.
add_action('init', function () {
    Aqualuxe\Support\Container::getInstance()->boot();
}, 20);

// Fallback: enqueue minimal CSS when assets not built.
// No raw asset fallback: assets are enqueued via Assets_Service_Provider when mix-manifest exists.
