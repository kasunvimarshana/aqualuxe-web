<?php
namespace AquaLuxe\Core;

if (!defined('ABSPATH')) { exit; }

class Theme {
    public static function init(): void {
        add_action('after_setup_theme', [__CLASS__, 'setup']);
        add_action('widgets_init', [__CLASS__, 'register_sidebars']);
    }

    public static function setup(): void {
    \load_theme_textdomain(AQUALUXE_TEXT_DOMAIN, AQUALUXE_PATH . 'languages');

    \add_theme_support('title-tag');
    \add_theme_support('post-thumbnails');
    \add_theme_support('automatic-feed-links');
    \add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script']);
    \add_theme_support('custom-logo');
    \add_theme_support('customize-selective-refresh-widgets');

        // Dual-state: WooCommerce support is added conditionally by module.

    \register_nav_menus([
            'primary' => __('Primary Menu', AQUALUXE_TEXT_DOMAIN),
            'footer'  => __('Footer Menu', AQUALUXE_TEXT_DOMAIN),
        ]);

    \add_image_size('hero', 1920, 1080, true);
    \add_image_size('card', 800, 600, true);
    }

    public static function register_sidebars(): void {
    \register_sidebar([
            'name' => __('Sidebar', AQUALUXE_TEXT_DOMAIN),
            'id' => 'sidebar-1',
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget_title">',
            'after_title'   => '</h2>',
        ]);
    \register_sidebar([
            'name' => __('Footer Widgets', AQUALUXE_TEXT_DOMAIN),
            'id' => 'footer-1',
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget_title">',
            'after_title'   => '</h2>',
        ]);
    }
}
