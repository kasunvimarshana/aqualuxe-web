<?php

/**
 * Theme setup functions
 *
 * @package AquaLuxe
 */

defined('ABSPATH') || exit;

/**
 * Theme setup
 */
function aqualuxe_setup()
{
    // Add theme support
    add_theme_support('automatic-feed-links');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption'));
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');

    // Register navigation menus
    register_nav_menus(array(
        'primary' => esc_html__('Primary Menu', 'aqualuxe'),
        'footer'  => esc_html__('Footer Menu', 'aqualuxe'),
    ));

    // Add custom image sizes
    add_image_size('aqualuxe-product-thumbnail', 300, 300, true);
    add_image_size('aqualuxe-product-gallery', 600, 600, true);
}
add_action('after_setup_theme', 'aqualuxe_setup');

/**
 * Register widget areas
 */
function aqualuxe_widgets_init()
{
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
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));

    register_sidebar(array(
        'name'          => esc_html__('Footer 2', 'aqualuxe'),
        'id'            => 'footer-2',
        'description'   => esc_html__('Add footer widgets here.', 'aqualuxe'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));

    register_sidebar(array(
        'name'          => esc_html__('Footer 3', 'aqualuxe'),
        'id'            => 'footer-3',
        'description'   => esc_html__('Add footer widgets here.', 'aqualuxe'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
}
add_action('widgets_init', 'aqualuxe_widgets_init');
