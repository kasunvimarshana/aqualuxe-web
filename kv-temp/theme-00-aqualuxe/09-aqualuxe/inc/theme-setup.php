<?php
/**
 * Theme Setup
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('aqualuxe_theme_setup')) {
    /**
     * Sets up theme defaults and registers support for various WordPress features.
     *
     * @since 1.0.0
     */
    function aqualuxe_theme_setup() {
        // Load text domain
        load_child_theme_textdomain('aqualuxe', get_stylesheet_directory() . '/languages');
        
        // Add theme support
        add_theme_support('automatic-feed-links');
        add_theme_support('title-tag');
        add_theme_support('post-thumbnails');
        add_theme_support('html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script',
        ));
        add_theme_support('customize-selective-refresh-widgets');
        add_theme_support('wc-product-gallery-zoom');
        add_theme_support('wc-product-gallery-lightbox');
        add_theme_support('wc-product-gallery-slider');
        
        // Add support for full and wide align images
        add_theme_support('align-wide');
        
        // Add support for responsive embedded content
        add_theme_support('responsive-embeds');
        
        // Add support for custom line height
        add_theme_support('custom-line-height');
        
        // Add support for experimental link color control
        add_theme_support('experimental-link-color');
    }
}
add_action('after_setup_theme', 'aqualuxe_theme_setup');

if (!function_exists('aqualuxe_content_width')) {
    /**
     * Set the content width in pixels, based on the theme's design and stylesheet.
     *
     * @since 1.0.0
     */
    function aqualuxe_content_width() {
        $GLOBALS['content_width'] = apply_filters('aqualuxe_content_width', 1200);
    }
}
add_action('after_setup_theme', 'aqualuxe_content_width', 0);

if (!function_exists('aqualuxe_register_menus')) {
    /**
     * Register navigation menus
     *
     * @since 1.0.0
     */
    function aqualuxe_register_menus() {
        register_nav_menus(array(
            'primary' => esc_html__('Primary Menu', 'aqualuxe'),
            'secondary' => esc_html__('Secondary Menu', 'aqualuxe'),
            'handheld' => esc_html__('Handheld Menu', 'aqualuxe'),
        ));
    }
}
add_action('init', 'aqualuxe_register_menus');

if (!function_exists('aqualuxe_widgets_init')) {
    /**
     * Register widget area
     *
     * @since 1.0.0
     */
    function aqualuxe_widgets_init() {
        register_sidebar(array(
            'name' => esc_html__('Sidebar', 'aqualuxe'),
            'id' => 'sidebar-1',
            'description' => esc_html__('Add widgets here.', 'aqualuxe'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget' => '</section>',
            'before_title' => '<h2 class="widget-title">',
            'after_title' => '</h2>',
        ));
        
        register_sidebar(array(
            'name' => esc_html__('Footer 1', 'aqualuxe'),
            'id' => 'footer-1',
            'description' => esc_html__('Add widgets here to appear in footer column 1.', 'aqualuxe'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget' => '</section>',
            'before_title' => '<h2 class="widget-title">',
            'after_title' => '</h2>',
        ));
        
        register_sidebar(array(
            'name' => esc_html__('Footer 2', 'aqualuxe'),
            'id' => 'footer-2',
            'description' => esc_html__('Add widgets here to appear in footer column 2.', 'aqualuxe'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget' => '</section>',
            'before_title' => '<h2 class="widget-title">',
            'after_title' => '</h2>',
        ));
        
        register_sidebar(array(
            'name' => esc_html__('Footer 3', 'aqualuxe'),
            'id' => 'footer-3',
            'description' => esc_html__('Add widgets here to appear in footer column 3.', 'aqualuxe'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget' => '</section>',
            'before_title' => '<h2 class="widget-title">',
            'after_title' => '</h2>',
        ));
    }
}
add_action('widgets_init', 'aqualuxe_widgets_init');