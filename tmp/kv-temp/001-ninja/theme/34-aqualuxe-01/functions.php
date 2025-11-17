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
define('AQUALUXE_ASSETS_URI', AQUALUXE_URI . 'assets/dist/');

// Prevent direct access to this file
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
function aqualuxe_setup() {
    // Make theme available for translation
    load_theme_textdomain('aqualuxe', AQUALUXE_DIR . 'languages');

    // Add default posts and comments RSS feed links to head
    add_theme_support('automatic-feed-links');

    // Let WordPress manage the document title
    add_theme_support('title-tag');

    // Enable support for Post Thumbnails on posts and pages
    add_theme_support('post-thumbnails');

    // Set post thumbnail size
    set_post_thumbnail_size(1200, 9999);

    // Register navigation menus
    register_nav_menus(
        array(
            'primary' => esc_html__('Primary Menu', 'aqualuxe'),
            'footer' => esc_html__('Footer Menu', 'aqualuxe'),
            'social' => esc_html__('Social Links Menu', 'aqualuxe'),
        )
    );

    // Switch default core markup to output valid HTML5
    add_theme_support(
        'html5',
        array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script',
        )
    );

    // Add theme support for selective refresh for widgets
    add_theme_support('customize-selective-refresh-widgets');

    // Add support for custom logo
    add_theme_support(
        'custom-logo',
        array(
            'height' => 250,
            'width' => 250,
            'flex-width' => true,
            'flex-height' => true,
        )
    );

    // Add support for full and wide align images
    add_theme_support('align-wide');

    // Add support for editor styles
    add_theme_support('editor-styles');

    // Add support for responsive embeds
    add_theme_support('responsive-embeds');

    // Add support for custom color scheme
    add_theme_support(
        'editor-color-palette',
        array(
            array(
                'name' => esc_html__('Primary', 'aqualuxe'),
                'slug' => 'primary',
                'color' => '#0073aa',
            ),
            array(
                'name' => esc_html__('Secondary', 'aqualuxe'),
                'slug' => 'secondary',
                'color' => '#005177',
            ),
            array(
                'name' => esc_html__('Dark Blue', 'aqualuxe'),
                'slug' => 'dark-blue',
                'color' => '#1e73be',
            ),
            array(
                'name' => esc_html__('Light Blue', 'aqualuxe'),
                'slug' => 'light-blue',
                'color' => '#9ecce8',
            ),
            array(
                'name' => esc_html__('Dark', 'aqualuxe'),
                'slug' => 'dark',
                'color' => '#333333',
            ),
            array(
                'name' => esc_html__('Light', 'aqualuxe'),
                'slug' => 'light',
                'color' => '#f5f5f5',
            ),
            array(
                'name' => esc_html__('White', 'aqualuxe'),
                'slug' => 'white',
                'color' => '#ffffff',
            ),
        )
    );
}
add_action('after_setup_theme', 'aqualuxe_setup');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 */
function aqualuxe_content_width() {
    $GLOBALS['content_width'] = apply_filters('aqualuxe_content_width', 1200);
}
add_action('after_setup_theme', 'aqualuxe_content_width', 0);

/**
 * Register widget areas.
 */
function aqualuxe_widgets_init() {
    register_sidebar(
        array(
            'name'          => esc_html__('Sidebar', 'aqualuxe'),
            'id'            => 'sidebar-1',
            'description'   => esc_html__('Add widgets here to appear in your sidebar.', 'aqualuxe'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        )
    );

    register_sidebar(
        array(
            'name'          => esc_html__('Footer 1', 'aqualuxe'),
            'id'            => 'footer-1',
            'description'   => esc_html__('Add widgets here to appear in footer column 1.', 'aqualuxe'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        )
    );

    register_sidebar(
        array(
            'name'          => esc_html__('Footer 2', 'aqualuxe'),
            'id'            => 'footer-2',
            'description'   => esc_html__('Add widgets here to appear in footer column 2.', 'aqualuxe'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        )
    );

    register_sidebar(
        array(
            'name'          => esc_html__('Footer 3', 'aqualuxe'),
            'id'            => 'footer-3',
            'description'   => esc_html__('Add widgets here to appear in footer column 3.', 'aqualuxe'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        )
    );

    register_sidebar(
        array(
            'name'          => esc_html__('Footer 4', 'aqualuxe'),
            'id'            => 'footer-4',
            'description'   => esc_html__('Add widgets here to appear in footer column 4.', 'aqualuxe'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        )
    );
}
add_action('widgets_init', 'aqualuxe_widgets_init');

/**
 * Enqueue scripts and styles.
 */
function aqualuxe_scripts() {
    // Enqueue main stylesheet
    wp_enqueue_style('aqualuxe-style', get_stylesheet_uri(), array(), AQUALUXE_VERSION);
    
    // Enqueue compiled CSS
    wp_enqueue_style('aqualuxe-main', AQUALUXE_ASSETS_URI . 'css/main.css', array(), AQUALUXE_VERSION);

    // Enqueue compiled JavaScript
    wp_enqueue_script('aqualuxe-main', AQUALUXE_ASSETS_URI . 'js/main.js', array('jquery'), AQUALUXE_VERSION, true);

    // Add comment-reply script if needed
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }

    // Localize script for JavaScript variables
    wp_localize_script(
        'aqualuxe-main',
        'aqualuxeData',
        array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'themeUri' => AQUALUXE_URI,
            'nonce' => wp_create_nonce('aqualuxe-nonce'),
        )
    );
}
add_action('wp_enqueue_scripts', 'aqualuxe_scripts');

/**
 * Include required files
 */

// Core functionality
require AQUALUXE_DIR . 'inc/core.php';

// Theme customizer
require AQUALUXE_DIR . 'inc/customizer.php';

// Template functions
require AQUALUXE_DIR . 'inc/template-functions.php';

// Template tags
require AQUALUXE_DIR . 'inc/template-tags.php';

// WooCommerce integration (conditionally loaded)
if (class_exists('WooCommerce')) {
    require AQUALUXE_DIR . 'inc/woocommerce.php';
}

// Custom post types and taxonomies
require AQUALUXE_DIR . 'inc/post-types.php';

// Custom widgets
require AQUALUXE_DIR . 'inc/widgets.php';

// Custom shortcodes
require AQUALUXE_DIR . 'inc/shortcodes.php';

// Custom blocks
require AQUALUXE_DIR . 'inc/blocks.php';

// AJAX handlers
require AQUALUXE_DIR . 'inc/ajax.php';

// Schema.org markup
require AQUALUXE_DIR . 'inc/schema.php';

// Open Graph metadata
require AQUALUXE_DIR . 'inc/open-graph.php';

// Multilingual support
require AQUALUXE_DIR . 'inc/multilingual.php';

// Dark mode
require AQUALUXE_DIR . 'inc/dark-mode.php';

// Demo content importer
require AQUALUXE_DIR . 'inc/demo-importer.php';