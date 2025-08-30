<?php
/**
 * AquaLuxe Theme bootstrap
 *
 * @package aqualuxe
 */

defined('ABSPATH') || exit;

// Define theme constants.
define('AQUALUXE_VERSION', '1.0.0');
define('AQUALUXE_PATH', trailingslashit(get_template_directory()));
define('AQUALUXE_URI', trailingslashit(get_template_directory_uri()));
define('AQUALUXE_INC', AQUALUXE_PATH . 'inc/');
define('AQUALUXE_ASSETS', AQUALUXE_URI . 'assets/dist/');
define('AQUALUXE_TEXT', 'aqualuxe');

// PSR-4 like autoloader for theme classes under inc/.
spl_autoload_register(function ($class) {
    if (strpos($class, 'AquaLuxe\\') !== 0) {
        return;
    }
    $path = AQUALUXE_INC . 'classes/' . str_replace(['AquaLuxe\\', '\\'], ['', '/'], $class) . '.php';
    if (file_exists($path)) {
        require_once $path;
    }
});

require_once AQUALUXE_INC . 'helpers.php';
require_once AQUALUXE_INC . 'hooks.php';
require_once AQUALUXE_INC . 'setup.php';
require_once AQUALUXE_INC . 'customizer.php';
require_once AQUALUXE_INC . 'security.php';
require_once AQUALUXE_INC . 'template-tags.php';
require_once AQUALUXE_INC . 'woocommerce-compat.php';
require_once AQUALUXE_INC . 'modules.php';
require_once AQUALUXE_INC . 'rest.php';
require_once AQUALUXE_INC . 'demo.php';
require_once AQUALUXE_INC . 'modules-admin.php';

// Initialize theme immediately; Core will attach further hooks.
AquaLuxe\Core::init();

// Enqueue assets built via Mix and Tailwind.
add_action('wp_enqueue_scripts', function () {
    $manifest = AQUALUXE_PATH . 'assets/dist/mix-manifest.json';
    $version = AQUALUXE_VERSION;
    $get = function ($file) use ($manifest) {
        if (!file_exists($manifest)) return null;
        $map = json_decode(file_get_contents($manifest), true);
        return isset($map['/' . $file]) ? AQUALUXE_ASSETS . ltrim($map['/' . $file], '/') : null;
    };

    $css = $get('css/app.css');
    $js = $get('js/app.js');
    if ($css) wp_enqueue_style('aqualuxe-app', $css, [], null, 'all');
    if ($js) wp_enqueue_script('aqualuxe-app', $js, ['jquery'], null, true);

    // Dynamic CSS variables from Customizer
    $primary = get_theme_mod('aqualuxe_primary_color', '#0ea5e9');
    $accent  = get_theme_mod('aqualuxe_accent_color', '#14b8a6');
    $inline = ":root{--aqlx-primary: {$primary}; --aqlx-accent: {$accent};}";
    if ($css) wp_add_inline_style('aqualuxe-app', $inline);

    // Localize for JS features (i18n, nonce, dark mode, ajax URL, wc state).
    wp_localize_script('aqualuxe-app', 'AquaLuxe', [
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'restUrl' => esc_url_raw(rest_url('aqualuxe/v1')),
        'nonce'   => wp_create_nonce('wp_rest'),
        'isWoo'   => class_exists('WooCommerce'),
        'i18n'    => [
            'addedToCart' => esc_html__('Added to cart', AQUALUXE_TEXT),
        ],
    ]);
}, 20);

// Admin assets
add_action('admin_enqueue_scripts', function () {
    $manifest = AQUALUXE_PATH . 'assets/dist/mix-manifest.json';
    $get = function ($file) use ($manifest) {
        if (!file_exists($manifest)) return null;
        $map = json_decode(file_get_contents($manifest), true);
        return isset($map['/' . $file]) ? AQUALUXE_ASSETS . ltrim($map['/' . $file], '/') : null;
    };
    $css = $get('css/admin.css');
    $js = $get('js/admin.js');
    if ($css) wp_enqueue_style('aqualuxe-admin', $css, [], null, 'all');
    if ($js) wp_enqueue_script('aqualuxe-admin', $js, ['jquery'], null, true);
});

// Register nav menus and widget areas.
add_action('widgets_init', function () {
    register_sidebar([
        'name' => __('Sidebar', AQUALUXE_TEXT),
        'id' => 'sidebar-1',
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>',
    ]);

    register_sidebar([
        'name' => __('Shop Filters', AQUALUXE_TEXT),
        'id' => 'shop-filters',
        'description' => __('Add WooCommerce filtering widgets here (e.g., price filter, attributes).', AQUALUXE_TEXT),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>',
    ]);
});
