<?php
/**
 * AquaLuxe Child Theme Functions
 *
 * @package AquaLuxe
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define theme constants
define('AQUALUXE_VERSION', '1.0.0');

if (!function_exists('aqualuxe_setup')) :
    /**
     * Sets up theme defaults and registers support for various WordPress features.
     */
    function aqualuxe_setup()
    {
        /*
         * Make theme available for translation.
         */
        load_child_theme_textdomain('aqualuxe', get_stylesheet_directory() . '/languages');

        // Add default posts and comments RSS feed links to head.
        add_theme_support('automatic-feed-links');

        // Let WordPress manage the document title.
        add_theme_support('title-tag');

        // Enable support for Post Thumbnails on posts and pages.
        add_theme_support('post-thumbnails');

        // This theme uses wp_nav_menu() in one location.
        register_nav_menus(
            array(
                'menu-1' => esc_html__('Primary', 'aqualuxe'),
                'menu-2' => esc_html__('Secondary', 'aqualuxe'),
            )
        );

        // Switch default core markup for search form, comment form, and comments
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

        // Add support for core custom logo.
        add_theme_support(
            'custom-logo',
            array(
                'height'      => 100,
                'width'       => 400,
                'flex-width'  => true,
                'flex-height' => true,
            )
        );

        // Add support for WooCommerce
        add_theme_support('woocommerce');
        add_theme_support('wc-product-gallery-zoom');
        add_theme_support('wc-product-gallery-lightbox');
        add_theme_support('wc-product-gallery-slider');
    }
endif;
add_action('after_setup_theme', 'aqualuxe_setup');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 */
function aqualuxe_content_width()
{
    $GLOBALS['content_width'] = apply_filters('aqualuxe_content_width', 1200);
}
add_action('after_setup_theme', 'aqualuxe_content_width', 0);

/**
 * Enqueue scripts and styles.
 */
function aqualuxe_scripts()
{
    // Enqueue Storefront parent theme styles
    wp_enqueue_style('storefront-style', get_template_directory_uri() . '/style.css');

    // Enqueue child theme styles
    wp_enqueue_style('aqualuxe-style', get_stylesheet_uri(), array('storefront-style'), AQUALUXE_VERSION);

    // Enqueue navigation script
    wp_enqueue_script('aqualuxe-navigation', get_stylesheet_directory_uri() . '/assets/js/navigation.js', array('jquery'), AQUALUXE_VERSION, true);

    // Enqueue custom scripts
    wp_enqueue_script('aqualuxe-scripts', get_stylesheet_directory_uri() . '/assets/js/aqualuxe-scripts.js', array('jquery'), AQUALUXE_VERSION, true);

    // Localize script for AJAX
    wp_localize_script('aqualuxe-scripts', 'aqualuxe_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('aqualuxe_nonce'),
    ));

    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'aqualuxe_scripts');

/**
 * Implement the Custom Header feature.
 */
require get_stylesheet_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_stylesheet_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_stylesheet_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_stylesheet_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if (defined('JETPACK__VERSION')) {
    require get_stylesheet_directory() . '/inc/jetpack.php';
}

/**
 * Load WooCommerce compatibility file.
 */
if (class_exists('WooCommerce')) {
    require get_stylesheet_directory() . '/inc/woocommerce.php';
}

/**
 * Load customizer options.
 */
require get_stylesheet_directory() . '/inc/customizer-options.php';

/**
 * Load template hooks.
 */
require get_stylesheet_directory() . '/inc/template-hooks.php';