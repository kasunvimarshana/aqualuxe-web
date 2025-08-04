<?php

/**
 * AquaLuxe Theme Functions
 *
 * @package aqualuxe
 */

if (! defined('ABSPATH')) {
    exit;
}

/**
 * Enqueue scripts and styles.
 */
function aqualuxe_scripts()
{
    // Enqueue parent theme stylesheet.
    wp_enqueue_style('storefront-style', get_template_directory_uri() . '/style.css');

    // Enqueue child theme stylesheet.
    wp_enqueue_style('aqualuxe-style', get_stylesheet_directory_uri() . '/assets/css/frontend/style.css', array('storefront-style'), AQUALUXE_VERSION);

    // Enqueue custom scripts.
    wp_enqueue_script('aqualuxe-navigation', get_stylesheet_directory_uri() . '/assets/js/frontend/navigation.js', array(), AQUALUXE_VERSION, true);
    wp_enqueue_script('aqualuxe-site', get_stylesheet_directory_uri() . '/assets/js/frontend/site.js', array('jquery'), AQUALUXE_VERSION, true);

    // WooCommerce scripts.
    if (class_exists('WooCommerce')) {
        wp_enqueue_style('aqualuxe-woocommerce-style', get_stylesheet_directory_uri() . '/assets/css/woocommerce.css', array(), AQUALUXE_VERSION);
        wp_enqueue_script('aqualuxe-woocommerce', get_stylesheet_directory_uri() . '/assets/js/woocommerce.js', array('jquery'), AQUALUXE_VERSION, true);
    }

    // Localize scripts.
    wp_localize_script('aqualuxe-navigation', 'aqualuxeScreenReaderText', array(
        'expand'   => __('Expand child menu', 'aqualuxe'),
        'collapse' => __('Collapse child menu', 'aqualuxe'),
    ));

    // aqualuxe_scripts
    wp_enqueue_script('aqualuxe-faq', get_stylesheet_directory_uri() . '/assets/js/frontend/faq.js', array(), AQUALUXE_VERSION, true);
}
add_action('wp_enqueue_scripts', 'aqualuxe_scripts');

/**
 * Setup theme.
 */
function aqualuxe_setup()
{
    // Add theme support.
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
    add_theme_support('automatic-feed-links');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));
    add_theme_support('customize-selective-refresh-widgets');
    add_theme_support('woocommerce');

    // Register menu locations.
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'aqualuxe'),
        'secondary' => __('Secondary Menu', 'aqualuxe'),
        'footer' => __('Footer Menu', 'aqualuxe'),
    ));

    // Load text domain.
    load_child_theme_textdomain('aqualuxe', get_stylesheet_directory() . '/languages');
}
add_action('after_setup_theme', 'aqualuxe_setup');

/**
 * Register widget areas.
 */
function aqualuxe_widgets_init()
{
    register_sidebar(array(
        'name'          => __('Sidebar', 'aqualuxe'),
        'id'            => 'sidebar-1',
        'description'   => __('Add widgets here.', 'aqualuxe'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));

    register_sidebar(array(
        'name'          => __('Shop Sidebar', 'aqualuxe'),
        'id'            => 'shop-sidebar',
        'description'   => __('Add widgets here to appear in your sidebar on shop pages.', 'aqualuxe'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));
}
add_action('widgets_init', 'aqualuxe_widgets_init');
