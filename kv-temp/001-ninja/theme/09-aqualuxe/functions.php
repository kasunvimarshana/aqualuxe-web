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

// Set content width
if (!isset($content_width)) {
    $content_width = 1200;
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
function aqualuxe_setup() {
    // Load text domain for translation
    load_theme_textdomain('aqualuxe', AQUALUXE_DIR . 'languages');

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
        'height'      => 250,
        'width'       => 250,
        'flex-width'  => true,
        'flex-height' => true,
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

    // Add theme support for selective refresh for widgets
    add_theme_support('customize-selective-refresh-widgets');

    // Register navigation menus
    register_nav_menus(array(
        'primary'   => esc_html__('Primary Menu', 'aqualuxe'),
        'secondary' => esc_html__('Secondary Menu', 'aqualuxe'),
        'footer'    => esc_html__('Footer Menu', 'aqualuxe'),
    ));

    // WooCommerce support
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
}
add_action('after_setup_theme', 'aqualuxe_setup');

/**
 * Register widget areas.
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
}
add_action('widgets_init', 'aqualuxe_widgets_init');

/**
 * Enqueue scripts and styles.
 */
function aqualuxe_scripts() {
    // Enqueue main stylesheet
    wp_enqueue_style('aqualuxe-style', get_stylesheet_uri(), array(), AQUALUXE_VERSION);
    
    // Enqueue main JS file
    wp_enqueue_script('aqualuxe-main', AQUALUXE_URI . 'assets/js/main.js', array('jquery'), AQUALUXE_VERSION, true);

    // Enqueue comment reply script
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'aqualuxe_scripts');

/**
 * Include required files
 */
// Core theme functions
require AQUALUXE_DIR . 'inc/template-functions.php';
require AQUALUXE_DIR . 'inc/template-tags.php';

// Customizer additions
require AQUALUXE_DIR . 'inc/customizer/customizer.php';

// Custom widgets
require AQUALUXE_DIR . 'inc/widgets/class-aqualuxe-recent-posts-widget.php';

// WooCommerce compatibility
if (class_exists('WooCommerce')) {
    require AQUALUXE_DIR . 'inc/woocommerce.php';
}

// Custom post types
require AQUALUXE_DIR . 'inc/post-types/services.php';
require AQUALUXE_DIR . 'inc/post-types/events.php';

// Admin functions
require AQUALUXE_DIR . 'inc/admin/admin.php';

/**
 * Implement the Custom Header feature.
 */
require AQUALUXE_DIR . 'inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require AQUALUXE_DIR . 'inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require AQUALUXE_DIR . 'inc/template-functions.php';

/**
 * Load Jetpack compatibility file.
 */
if (defined('JETPACK__VERSION')) {
    require AQUALUXE_DIR . 'inc/jetpack.php';
}