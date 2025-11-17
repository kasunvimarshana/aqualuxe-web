<?php
namespace AquaLuxe\Core;

class Setup {
    public static function init() : void {
        add_theme_support('title-tag');
        add_theme_support('post-thumbnails');
        add_theme_support('html5', ['search-form','comment-form','comment-list','gallery','caption','style','script']);
        add_theme_support('custom-logo', [
            'height'      => 120,
            'width'       => 320,
            'flex-width'  => true,
            'flex-height' => true,
        ]);
        add_theme_support('align-wide');
        add_theme_support('responsive-embeds');

        if (class_exists('WooCommerce')) {
            add_theme_support('woocommerce');
            add_theme_support('wc-product-gallery-zoom');
            add_theme_support('wc-product-gallery-lightbox');
            add_theme_support('wc-product-gallery-slider');
        }

        register_nav_menus([
            'primary'   => __('Primary Menu', 'aqualuxe'),
            'secondary' => __('Secondary Menu', 'aqualuxe'),
            'footer'    => __('Footer Menu', 'aqualuxe'),
        ]);

        add_image_size('aqlx-hero', 1920, 800, true);
        add_image_size('aqlx-card', 600, 400, true);

        add_action('widgets_init', [__CLASS__, 'widgets']);
        remove_action('wp_head', 'wp_generator');
    }

    public static function widgets() : void {
        register_sidebar([
            'name'          => __('Sidebar', 'aqualuxe'),
            'id'            => 'sidebar-1',
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        ]);

        register_sidebar([
            'name'          => __('Footer', 'aqualuxe'),
            'id'            => 'footer-1',
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        ]);
    }
}
