<?php
/**
 * AquaLuxe functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Define theme constants
define('AQUALUXE_VERSION', '1.0.0');
define('AQUALUXE_DIR', trailingslashit(get_template_directory()));
define('AQUALUXE_URI', trailingslashit(get_template_directory_uri()));

/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
function aqualuxe_setup() {
    // Add default posts and comments RSS feed links to head.
    add_theme_support('automatic-feed-links');

    // Let WordPress manage the document title.
    add_theme_support('title-tag');

    // Enable support for Post Thumbnails on posts and pages.
    add_theme_support('post-thumbnails');

    // Add support for responsive embeds
    add_theme_support('responsive-embeds');

    // Add support for custom logo
    add_theme_support('custom-logo', array(
        'height'      => 100,
        'width'       => 350,
        'flex-width'  => true,
        'flex-height' => true,
    ));

    // Add support for full and wide align images.
    add_theme_support('align-wide');

    // Add support for editor styles.
    add_theme_support('editor-styles');

    // Add support for HTML5 features
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ));

    // Register navigation menus
    register_nav_menus(array(
        'primary'   => esc_html__('Primary Menu', 'aqualuxe'),
        'secondary' => esc_html__('Secondary Menu', 'aqualuxe'),
        'footer'    => esc_html__('Footer Menu', 'aqualuxe'),
        'mobile'    => esc_html__('Mobile Menu', 'aqualuxe'),
    ));

    // Set content width
    if (!isset($content_width)) {
        $content_width = 1200;
    }

    // Add theme support for selective refresh for widgets.
    add_theme_support('customize-selective-refresh-widgets');

    // Add support for WooCommerce
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
}
add_action('after_setup_theme', 'aqualuxe_setup');

/**
 * Enqueue scripts and styles.
 */
function aqualuxe_scripts() {
    // Enqueue main stylesheet
    wp_enqueue_style(
        'aqualuxe-style',
        AQUALUXE_URI . 'assets/css/main.css',
        array(),
        AQUALUXE_VERSION
    );

    // Enqueue main JavaScript file
    wp_enqueue_script(
        'aqualuxe-script',
        AQUALUXE_URI . 'assets/js/main.js',
        array('jquery'),
        AQUALUXE_VERSION,
        true
    );

    // Localize script for dynamic data
    wp_localize_script('aqualuxe-script', 'aqualuxeData', array(
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'themeUri' => AQUALUXE_URI,
        'nonce' => wp_create_nonce('aqualuxe-nonce'),
    ));

    // Add comment-reply script if needed
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'aqualuxe_scripts');

/**
 * Register widget areas.
 */
function aqualuxe_widgets_init() {
    register_sidebar(array(
        'name'          => esc_html__('Sidebar', 'aqualuxe'),
        'id'            => 'sidebar-1',
        'description'   => esc_html__('Add widgets here to appear in your sidebar.', 'aqualuxe'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));

    register_sidebar(array(
        'name'          => esc_html__('Footer 1', 'aqualuxe'),
        'id'            => 'footer-1',
        'description'   => esc_html__('Add widgets here to appear in footer column 1.', 'aqualuxe'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ));

    register_sidebar(array(
        'name'          => esc_html__('Footer 2', 'aqualuxe'),
        'id'            => 'footer-2',
        'description'   => esc_html__('Add widgets here to appear in footer column 2.', 'aqualuxe'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ));

    register_sidebar(array(
        'name'          => esc_html__('Footer 3', 'aqualuxe'),
        'id'            => 'footer-3',
        'description'   => esc_html__('Add widgets here to appear in footer column 3.', 'aqualuxe'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ));

    register_sidebar(array(
        'name'          => esc_html__('Footer 4', 'aqualuxe'),
        'id'            => 'footer-4',
        'description'   => esc_html__('Add widgets here to appear in footer column 4.', 'aqualuxe'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ));

    register_sidebar(array(
        'name'          => esc_html__('Shop Sidebar', 'aqualuxe'),
        'id'            => 'shop-sidebar',
        'description'   => esc_html__('Add widgets here to appear in shop sidebar.', 'aqualuxe'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
}
add_action('widgets_init', 'aqualuxe_widgets_init');

/**
 * Include required files
 */
// Helper functions
require_once AQUALUXE_DIR . 'inc/helpers/template-functions.php';
require_once AQUALUXE_DIR . 'inc/helpers/template-tags.php';
require_once AQUALUXE_DIR . 'inc/helpers/comment-callback.php';

// Customizer
require_once AQUALUXE_DIR . 'inc/customizer/customizer.php';

// WooCommerce integration
if (class_exists('WooCommerce')) {
    require_once AQUALUXE_DIR . 'inc/woocommerce.php';
}

// Custom post types
require_once AQUALUXE_DIR . 'inc/post-types.php';

// Custom widgets
require_once AQUALUXE_DIR . 'inc/widgets/class-aqualuxe-recent-posts-widget.php';

// Admin functions
require_once AQUALUXE_DIR . 'inc/admin/admin-functions.php';

/**
 * Filter the excerpt length
 */
function aqualuxe_excerpt_length($length) {
    return 20;
}
add_filter('excerpt_length', 'aqualuxe_excerpt_length');

/**
 * Filter the excerpt "read more" string
 */
function aqualuxe_excerpt_more($more) {
    return '&hellip; <a class="read-more" href="' . esc_url(get_permalink()) . '">' . esc_html__('Read More', 'aqualuxe') . '</a>';
}
add_filter('excerpt_more', 'aqualuxe_excerpt_more');

/**
 * Add custom body classes
 */
function aqualuxe_body_classes($classes) {
    // Add a class if there is a custom header.
    if (has_header_image()) {
        $classes[] = 'has-header-image';
    }

    // Add a class if sidebar is used.
    if (is_active_sidebar('sidebar-1') && !is_page_template('templates/full-width.php')) {
        $classes[] = 'has-sidebar';
    } else {
        $classes[] = 'no-sidebar';
    }

    // Add a class for the color scheme
    $color_scheme = get_theme_mod('aqualuxe_color_scheme', 'light');
    $classes[] = 'color-scheme-' . $color_scheme;

    return $classes;
}
add_filter('body_class', 'aqualuxe_body_classes');

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function aqualuxe_pingback_header() {
    if (is_singular() && pings_open()) {
        printf('<link rel="pingback" href="%s">', esc_url(get_bloginfo('pingback_url')));
    }
}
add_action('wp_head', 'aqualuxe_pingback_header');

/**
 * Register custom query vars
 */
function aqualuxe_register_query_vars($vars) {
    $vars[] = 'view';
    return $vars;
}
add_filter('query_vars', 'aqualuxe_register_query_vars');