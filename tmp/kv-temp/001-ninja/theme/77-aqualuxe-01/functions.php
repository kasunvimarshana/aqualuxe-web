<?php
/**
 * AquaLuxe Theme bootstrap
 *
 * @package aqualuxe
 */

if (!defined('ABSPATH')) {
    exit;
}

// Define constants.
if (!defined('AQUALUXE_VERSION')) {
    define('AQUALUXE_VERSION', '1.0.0');
}
if (!defined('AQUALUXE_PATH')) {
    define('AQUALUXE_PATH', trailingslashit(get_template_directory()));
}
if (!defined('AQUALUXE_URI')) {
    define('AQUALUXE_URI', trailingslashit(get_template_directory_uri()));
}

// PSR-4-like autoloader for inc/ and modules/ namespaces.
spl_autoload_register(function ($class) {
    $prefixes = [
        'AquaLuxe\\Core\\' => AQUALUXE_PATH . 'inc/core/',
        'AquaLuxe\\Modules\\' => AQUALUXE_PATH . 'modules/',
    ];
    foreach ($prefixes as $prefix => $dir) {
        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) !== 0) {
            continue;
        }
        $relative = substr($class, $len);
        $file = $dir . str_replace('\\', '/', $relative) . '.php';
        if (file_exists($file)) {
            require_once $file;
        }
    }
});

// Load helpers ASAP.
require_once AQUALUXE_PATH . 'inc/helpers.php';

// Core boot.
add_action('after_setup_theme', function () {
    \AquaLuxe\Core\Setup::boot();
});

add_action('init', function () {
    \AquaLuxe\Core\I18n::boot();
    \AquaLuxe\Core\Customizer::boot();
    \AquaLuxe\Core\Assets::boot();
    \AquaLuxe\Core\Menus::boot();
    \AquaLuxe\Core\Blocks::boot();
    \AquaLuxe\Core\SEO::boot();
    \AquaLuxe\Core\Admin::boot();
});

// Load modules based on configuration.
add_action('init', function () {
    $config = \AquaLuxe\Core\Config::instance();
    foreach ($config->get_enabled_modules() as $module_class) {
        if (class_exists($module_class) && method_exists($module_class, 'boot')) {
            call_user_func([$module_class, 'boot']);
        }
    }
}, 20);

// WooCommerce compatibility (optional / dual-state).
add_action('after_setup_theme', function () {
    if (class_exists('WooCommerce')) {
        add_theme_support('woocommerce');
        add_theme_support('wc-product-gallery-zoom');
        add_theme_support('wc-product-gallery-lightbox');
        add_theme_support('wc-product-gallery-slider');
    }
});

// Graceful fallbacks when WC inactive.
add_filter('aqualuxe/is_woocommerce_active', function ($active) {
    return class_exists('WooCommerce');
});

// Register sidebars.
add_action('widgets_init', function () {
    register_sidebar([
        'name' => __('Primary Sidebar', 'aqualuxe'),
        'id' => 'sidebar-1',
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ]);
});

// Security headers and hardening (basic theme-level).
add_action('send_headers', function () {
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: SAMEORIGIN');
    header('Referrer-Policy: strict-origin-when-cross-origin');
});

// Include template tags.
require_once AQUALUXE_PATH . 'inc/template-tags.php';

