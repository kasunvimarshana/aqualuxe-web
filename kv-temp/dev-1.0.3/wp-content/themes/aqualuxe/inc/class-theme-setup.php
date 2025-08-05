<?php

/**
 * AquaLuxe Theme Setup
 *
 * @package AquaLuxe
 */

if (! defined('ABSPATH')) {
    exit;
}

class AquaLuxe_Theme_Setup
{

    public function __construct()
    {
        add_action('after_setup_theme', [$this, 'setup']);
        add_action('widgets_init', [$this, 'register_sidebars']);
    }

    /**
     * Theme setup
     */
    public function setup()
    {
        // Translation
        load_theme_textdomain('aqualuxe', get_template_directory() . '/languages');

        // Theme supports
        add_theme_support('title-tag');
        add_theme_support('post-thumbnails');
        add_theme_support('automatic-feed-links');
        add_theme_support('custom-logo', [
            'height'      => 60,
            'width'       => 200,
            'flex-width'  => true,
            'flex-height' => true,
        ]);
        add_theme_support('customize-selective-refresh-widgets');
        add_theme_support('woocommerce');

        // Menus
        register_nav_menus([
            'primary' => __('Primary Menu', 'aqualuxe'),
            'footer'  => __('Footer Menu', 'aqualuxe'),
        ]);
    }

    /**
     * Register widget areas
     */
    public function register_sidebars()
    {
        register_sidebar([
            'name'          => __('Sidebar', 'aqualuxe'),
            'id'            => 'sidebar-1',
            'description'   => __('Main sidebar area', 'aqualuxe'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        ]);

        register_sidebar([
            'name'          => __('Footer Widgets', 'aqualuxe'),
            'id'            => 'footer-widgets',
            'description'   => __('Footer widget area', 'aqualuxe'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        ]);
    }
}
