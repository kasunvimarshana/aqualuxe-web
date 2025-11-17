<?php

/**
 * AquaLuxe functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

/**
 * Define Constants
 */
define('AQUALUXE_VERSION', '1.0.0');
define('AQUALUXE_THEME_DIR', trailingslashit(get_template_directory()));
define('AQUALUXE_THEME_URI', trailingslashit(get_template_directory_uri()));

/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
function aqualuxe_setup()
{
    /*
     * Make theme available for translation.
     * Translations can be filed in the /languages/ directory.
     */
    load_theme_textdomain('aqualuxe', AQUALUXE_THEME_DIR . 'languages');

    // Add default posts and comments RSS feed links to head.
    add_theme_support('automatic-feed-links');

    /*
     * Let WordPress manage the document title.
     * By adding theme support, we declare that this theme does not use a
     * hard-coded <title> tag in the document head, and expect WordPress to
     * provide it for us.
     */
    add_theme_support('title-tag');

    /*
     * Enable support for Post Thumbnails on posts and pages.
     *
     * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
     */
    add_theme_support('post-thumbnails');

    // Set default thumbnail size.
    set_post_thumbnail_size(1200, 9999);

    // Add custom image sizes.
    add_image_size('aqualuxe-featured', 1200, 600, true);
    add_image_size('aqualuxe-medium', 600, 400, true);
    add_image_size('aqualuxe-small', 300, 200, true);

    // This theme uses wp_nav_menu() in multiple locations.
    register_nav_menus(
        array(
            'primary' => esc_html__('Primary Menu', 'aqualuxe'),
            'footer'  => esc_html__('Footer Menu', 'aqualuxe'),
            'social'  => esc_html__('Social Menu', 'aqualuxe'),
        )
    );

    /*
     * Switch default core markup for search form, comment form, and comments
     * to output valid HTML5.
     */
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

    // Set up the WordPress core custom background feature.
    add_theme_support(
        'custom-background',
        apply_filters(
            'aqualuxe_custom_background_args',
            array(
                'default-color' => 'ffffff',
                'default-image' => '',
            )
        )
    );

    // Add theme support for selective refresh for widgets.
    add_theme_support('customize-selective-refresh-widgets');

    /**
     * Add support for core custom logo.
     *
     * @link https://codex.wordpress.org/Theme_Logo
     */
    add_theme_support(
        'custom-logo',
        array(
            'height'      => 250,
            'width'       => 250,
            'flex-width'  => true,
            'flex-height' => true,
        )
    );

    // Add support for full and wide align images.
    add_theme_support('align-wide');

    // Add support for responsive embeds.
    add_theme_support('responsive-embeds');

    // Add support for custom line height controls.
    add_theme_support('custom-line-height');

    // Add support for experimental link color control.
    add_theme_support('experimental-link-color');

    // Add support for custom units.
    add_theme_support('custom-units');

    // Add support for editor styles.
    add_theme_support('editor-styles');

    // Enqueue editor styles.
    add_editor_style('assets/dist/css/editor-style.css');
}
add_action('after_setup_theme', 'aqualuxe_setup');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
if (! function_exists('aqualuxe_content_width')) :
    function aqualuxe_content_width()
    {
        $GLOBALS['content_width'] = apply_filters('aqualuxe_content_width', 1200);
    }
endif;
add_action('after_setup_theme', 'aqualuxe_content_width', 0);

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function aqualuxe_widgets_init()
{
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
function aqualuxe_scripts()
{
    // Enqueue styles.
    wp_enqueue_style('aqualuxe-style', get_stylesheet_uri(), array(), AQUALUXE_VERSION);

    // Add main stylesheet.
    wp_enqueue_style('aqualuxe-main', AQUALUXE_THEME_URI . 'assets/dist/css/main.css', array(), AQUALUXE_VERSION);

    // Add print stylesheet.
    wp_enqueue_style('aqualuxe-print', AQUALUXE_THEME_URI . 'assets/dist/css/print.css', array(), AQUALUXE_VERSION, 'print');

    // Enqueue scripts.
    wp_enqueue_script('aqualuxe-navigation', AQUALUXE_THEME_URI . 'assets/dist/js/main.js', array(), AQUALUXE_VERSION, true);

    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'aqualuxe_scripts');

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require AQUALUXE_THEME_DIR . 'inc/hooks/hooks.php';

/**
 * Customizer additions.
 */
require AQUALUXE_THEME_DIR . 'inc/customizer/sanitize.php';
require AQUALUXE_THEME_DIR . 'inc/customizer/register.php';


/**
 * Theme Setup.
 */
require AQUALUXE_THEME_DIR . 'inc/setup/theme-setup.php';

/**
 * Asset Enqueuing.
 */
require AQUALUXE_THEME_DIR . 'inc/assets/enqueue.php';

/**
 * Security Hardening.
 */
require AQUALUXE_THEME_DIR . 'inc/security/hardening.php';

/**
 * SEO Schema.
 */
require AQUALUXE_THEME_DIR . 'inc/seo/schema.php';

/**
 * Layout Components.
 */
require AQUALUXE_THEME_DIR . 'inc/layout/layout-options.php';
require AQUALUXE_THEME_DIR . 'inc/layout/header-variations.php';
require AQUALUXE_THEME_DIR . 'inc/layout/footer-widgets.php';
require AQUALUXE_THEME_DIR . 'inc/layout/top-bar.php';
require AQUALUXE_THEME_DIR . 'inc/layout/breadcrumbs.php';
require AQUALUXE_THEME_DIR . 'inc/layout/back-to-top.php';

/**
 * Social Media Integration.
 */
require AQUALUXE_THEME_DIR . 'inc/social/social-media.php';

/**
 * Classes.
 */
require AQUALUXE_THEME_DIR . 'inc/classes/class-aqualuxe-split-menu-walker.php';

/**
 * WooCommerce Integration.
 */
if (class_exists('WooCommerce')) {
    require AQUALUXE_THEME_DIR . 'inc/integrations/woocommerce.php';
} else {
    require AQUALUXE_THEME_DIR . 'inc/integrations/woocommerce-fallback.php';
}

/**
 * Custom template tags for this theme.
 */
require AQUALUXE_THEME_DIR . 'inc/utils/template-tags.php';
