<?php
/**
 * AquaLuxe Theme bootstrap
 */

if (!defined('AQUALUXE_PATH')) {
    define('AQUALUXE_PATH', trailingslashit(get_template_directory()));
}
if (!defined('AQUALUXE_URI')) {
    define('AQUALUXE_URI', trailingslashit(get_template_directory_uri()));
}
if (!defined('AQUALUXE_VERSION')) {
    define('AQUALUXE_VERSION', '1.0.1');
}

// Autoload core and modules
require_once AQUALUXE_PATH . 'inc/helpers.php';
require_once AQUALUXE_PATH . 'inc/security.php';
require_once AQUALUXE_PATH . 'inc/customizer.php';
require_once AQUALUXE_PATH . 'inc/customizer-output.php';
require_once AQUALUXE_PATH . 'inc/hooks.php';
require_once AQUALUXE_PATH . 'inc/seo.php';
require_once AQUALUXE_PATH . 'inc/compat-woocommerce.php';
require_once AQUALUXE_PATH . 'inc/enqueue.php';
require_once AQUALUXE_PATH . 'inc/demo-importer.php';
require_once AQUALUXE_PATH . 'inc/modules.php';
require_once AQUALUXE_PATH . 'inc/admin.php';
require_once AQUALUXE_PATH . 'inc/diagnostics.php';
require_once AQUALUXE_PATH . 'inc/forms.php';
require_once AQUALUXE_PATH . 'inc/newsletter-admin.php';

// Theme setup
add_action('after_setup_theme', function () {
    load_theme_textdomain('aqualuxe', AQUALUXE_PATH . 'languages');

    add_theme_support('automatic-feed-links');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('responsive-embeds');
    add_theme_support('custom-logo');
    add_theme_support('html5', ['search-form','comment-form','comment-list','gallery','caption','style','script']);
    add_theme_support('customize-selective-refresh-widgets');

    // WooCommerce (conditionally)
    if (class_exists('WooCommerce')) {
        add_theme_support('woocommerce');
        add_theme_support('wc-product-gallery-zoom');
        add_theme_support('wc-product-gallery-lightbox');
        add_theme_support('wc-product-gallery-slider');
    }

    register_nav_menus([
        'primary' => __('Primary Menu', 'aqualuxe'),
        'footer' => __('Footer Menu', 'aqualuxe'),
        'utility' => __('Utility Menu', 'aqualuxe'),
    ]);
});

// Register sidebars
add_action('widgets_init', function () {
    register_sidebar([
        'name' => __('Primary Sidebar', 'aqualuxe'),
        'id' => 'sidebar-1',
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ]);
    register_sidebar([
        'name' => __('Footer Widgets', 'aqualuxe'),
        'id' => 'footer-1',
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ]);
});
