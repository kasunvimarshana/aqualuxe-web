<?php
namespace AquaLuxe\Core;

use AquaLuxe\Core\Assets;
use AquaLuxe\Core\Customizer;
use AquaLuxe\Core\REST;
use AquaLuxe\Core\CPTs;

class Theme
{
    public static function init(): void
    {
        \add_action('after_setup_theme', [__CLASS__, 'setup']);
        \add_action('widgets_init', [__CLASS__, 'widgets']);
        \add_action('init', [CPTs::class, 'register']);
        \add_action('wp_enqueue_scripts', [Assets::class, 'enqueue']);
        \add_action('customize_register', [Customizer::class, 'register']);
        \add_action('rest_api_init', [REST::class, 'register_routes']);
    \add_action('after_switch_theme', [__CLASS__, 'flush_rewrite']);
    }

    public static function setup(): void
    {
        \load_theme_textdomain('aqualuxe', AQUALUXE_DIR . '/languages');
        \add_theme_support('automatic-feed-links');
        \add_theme_support('title-tag');
        \add_theme_support('post-thumbnails');
        \add_theme_support('html5', [
            'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'script', 'style', 'navigation-widgets'
        ]);
        \add_theme_support('custom-logo', [
            'height' => 80,
            'width' => 240,
            'flex-height' => true,
            'flex-width' => true,
        ]);
        \add_theme_support('customize-selective-refresh-widgets');
        \add_theme_support('woocommerce');
        \add_theme_support('align-wide');
        \add_theme_support('responsive-embeds');

        \register_nav_menus([
            'primary' => \__('Primary Menu', 'aqualuxe'),
            'footer'  => \__('Footer Menu', 'aqualuxe'),
        ]);
    }

    public static function widgets(): void
    {
        \register_sidebar([
            'name' => \__('Sidebar', 'aqualuxe'),
            'id' => 'sidebar-1',
            'description' => \__('Main sidebar', 'aqualuxe'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        ]);
        \register_sidebar([
            'name' => \__('Footer Widgets', 'aqualuxe'),
            'id' => 'footer-1',
            'description' => \__('Footer widget area', 'aqualuxe'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        ]);
    }

    public static function flush_rewrite(): void
    {
        if (\function_exists('flush_rewrite_rules')) {
            \flush_rewrite_rules();
        }
    }
}
