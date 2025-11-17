<?php
/**
 * AquaLuxe functions and definitions
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Define theme constants
define('AQUALUXE_VERSION', '1.0.0');
define('AQUALUXE_DIR', get_template_directory());
define('AQUALUXE_URI', get_template_directory_uri());
define('AQUALUXE_ASSETS_URI', AQUALUXE_URI . '/assets/dist');

// Set content width
if (!isset($content_width)) {
    $content_width = 1200;
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
function aqualuxe_setup() {
    // Load text domain for translation
    load_theme_textdomain('aqualuxe', AQUALUXE_DIR . '/languages');

    // Add default posts and comments RSS feed links to head
    add_theme_support('automatic-feed-links');

    // Let WordPress manage the document title
    add_theme_support('title-tag');

    // Enable support for Post Thumbnails on posts and pages
    add_theme_support('post-thumbnails');

    // Add support for responsive embeds
    add_theme_support('responsive-embeds');

    // Add support for custom logo
    add_theme_support('custom-logo', array(
        'height'      => 100,
        'width'       => 350,
        'flex-height' => true,
        'flex-width'  => true,
    ));

    // Add support for editor styles
    add_theme_support('editor-styles');

    // Add support for HTML5 markup
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ));

    // Add support for custom background
    add_theme_support('custom-background', array(
        'default-color' => 'ffffff',
    ));

    // Add support for selective refresh for widgets
    add_theme_support('customize-selective-refresh-widgets');

    // Add support for full and wide align images
    add_theme_support('align-wide');

    // Register navigation menus
    register_nav_menus(array(
        'primary'   => esc_html__('Primary Menu', 'aqualuxe'),
        'secondary' => esc_html__('Secondary Menu', 'aqualuxe'),
        'footer'    => esc_html__('Footer Menu', 'aqualuxe'),
        'mobile'    => esc_html__('Mobile Menu', 'aqualuxe'),
    ));

    // Add theme support for WooCommerce if it's active
    if (class_exists('WooCommerce')) {
        add_theme_support('woocommerce');
        add_theme_support('wc-product-gallery-zoom');
        add_theme_support('wc-product-gallery-lightbox');
        add_theme_support('wc-product-gallery-slider');
    }
}
add_action('after_setup_theme', 'aqualuxe_setup');

/**
 * Include required files
 */
$aqualuxe_includes = array(
    '/inc/helpers.php',                // Helper functions
    '/inc/template-functions.php',     // Template functions
    '/inc/template-tags.php',          // Template tags
    '/inc/customizer.php',             // Customizer settings
    '/inc/widgets.php',                // Custom widgets
    '/inc/enqueue.php',                // Enqueue scripts and styles
    '/inc/hooks.php',                  // Theme hooks
    '/inc/custom-post-types.php',      // Custom post types
    '/inc/custom-taxonomies.php',      // Custom taxonomies
    '/inc/shortcodes.php',             // Shortcodes
    '/inc/ajax.php',                   // AJAX handlers
    '/inc/multilingual.php',           // Multilingual support
    '/inc/multi-currency.php',         // Multi-currency support
    '/inc/dark-mode.php',              // Dark mode functionality
    '/inc/demo-importer.php',          // Demo content importer
    '/inc/contact-form.php',           // Contact form handler
);

// Include WooCommerce specific files if WooCommerce is active
if (class_exists('WooCommerce')) {
    $aqualuxe_includes[] = '/inc/woocommerce/woocommerce-setup.php';
    $aqualuxe_includes[] = '/inc/woocommerce/woocommerce-functions.php';
    $aqualuxe_includes[] = '/inc/woocommerce/woocommerce-hooks.php';
    $aqualuxe_includes[] = '/inc/woocommerce/woocommerce-template-functions.php';
    $aqualuxe_includes[] = '/inc/woocommerce/order-tracking.php';
    $aqualuxe_includes[] = '/inc/demo-importer.php';
    $aqualuxe_includes[] = '/inc/seo.php';
    $aqualuxe_includes[] = '/inc/accessibility.php';
    $aqualuxe_includes[] = '/inc/performance.php';
    $aqualuxe_includes[] = '/inc/security.php';
}

// Include files
foreach ($aqualuxe_includes as $file) {
    if (file_exists(AQUALUXE_DIR . $file)) {
        require_once AQUALUXE_DIR . $file;
    }
}

/**
 * Register widget area.
 */
function aqualuxe_widgets_init() {
    register_sidebar(array(
        'name'          => esc_html__('Sidebar', 'aqualuxe'),
        'id'            => 'sidebar-1',
        'description'   => esc_html__('Add widgets here.', 'aqualuxe'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));

    register_sidebar(array(
        'name'          => esc_html__('Footer 1', 'aqualuxe'),
        'id'            => 'footer-1',
        'description'   => esc_html__('Add footer widgets here.', 'aqualuxe'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));

    register_sidebar(array(
        'name'          => esc_html__('Footer 2', 'aqualuxe'),
        'id'            => 'footer-2',
        'description'   => esc_html__('Add footer widgets here.', 'aqualuxe'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));

    register_sidebar(array(
        'name'          => esc_html__('Footer 3', 'aqualuxe'),
        'id'            => 'footer-3',
        'description'   => esc_html__('Add footer widgets here.', 'aqualuxe'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));

    register_sidebar(array(
        'name'          => esc_html__('Footer 4', 'aqualuxe'),
        'id'            => 'footer-4',
        'description'   => esc_html__('Add footer widgets here.', 'aqualuxe'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));

    // Register WooCommerce shop sidebar if WooCommerce is active
    if (class_exists('WooCommerce')) {
        register_sidebar(array(
            'name'          => esc_html__('Shop Sidebar', 'aqualuxe'),
            'id'            => 'shop-sidebar',
            'description'   => esc_html__('Add shop widgets here.', 'aqualuxe'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        ));
    }
}
add_action('widgets_init', 'aqualuxe_widgets_init');

/**
 * Fallback function for WooCommerce features when WooCommerce is not active
 */
function aqualuxe_woocommerce_fallback() {
    if (!class_exists('WooCommerce')) {
        // Include fallback functions
        require_once AQUALUXE_DIR . '/inc/woocommerce/woocommerce-fallback.php';
    }
}
add_action('after_setup_theme', 'aqualuxe_woocommerce_fallback');