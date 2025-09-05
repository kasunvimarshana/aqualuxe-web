<?php
namespace AquaLuxe\Core;

use AquaLuxe\Core\Assets;
use AquaLuxe\Core\Customizer;
use AquaLuxe\Core\REST;
use AquaLuxe\Core\CPTs;
use AquaLuxe\Core\Container;
use AquaLuxe\Core\Logger;
use AquaLuxe\Core\A11y;

class Theme
{
    public static function init(): void
    {
        // Register core services (DI container)
    Container::set('logger', function(){
            $level = \apply_filters('aqualuxe/logger/level', 'warning');
            return new Logger(is_string($level) ? $level : 'warning');
        });
    // Alias to contract-like key for easier substitution from plugins
    Container::set('logger.interface', function(){ return Container::get('logger'); });
        \add_action('after_setup_theme', [__CLASS__, 'setup']);
        \add_action('widgets_init', [__CLASS__, 'widgets']);
        \add_action('init', [CPTs::class, 'register']);
        \add_action('wp_enqueue_scripts', [Assets::class, 'enqueue']);
        \add_action('customize_register', [Customizer::class, 'register']);
        \add_action('rest_api_init', [REST::class, 'register_routes']);
    \add_action('after_switch_theme', [__CLASS__, 'flush_rewrite']);
    // Accessibility defaults
    A11y::init();
    }

    public static function setup(): void
    {
    if (\function_exists('load_theme_textdomain')) { \call_user_func('load_theme_textdomain', 'aqualuxe', AQUALUXE_DIR . '/languages'); }
    if (\function_exists('add_theme_support')) { \call_user_func('add_theme_support', 'automatic-feed-links'); }
    if (\function_exists('add_theme_support')) { \call_user_func('add_theme_support', 'title-tag'); }
    if (\function_exists('add_theme_support')) { \call_user_func('add_theme_support', 'post-thumbnails'); }
    if (\function_exists('add_theme_support')) { \call_user_func('add_theme_support', 'html5', [
            'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'script', 'style', 'navigation-widgets'
    ]); }
    if (\function_exists('add_theme_support')) { \call_user_func('add_theme_support', 'custom-logo', [
            'height' => 80,
            'width' => 240,
            'flex-height' => true,
            'flex-width' => true,
    ]); }
    if (\function_exists('add_theme_support')) { \call_user_func('add_theme_support', 'customize-selective-refresh-widgets'); }
    if (\function_exists('add_theme_support')) { \call_user_func('add_theme_support', 'woocommerce'); }
    if (\function_exists('add_theme_support')) { \call_user_func('add_theme_support', 'align-wide'); }
    if (\function_exists('add_theme_support')) { \call_user_func('add_theme_support', 'responsive-embeds'); }

    if (\function_exists('register_nav_menus')) { \call_user_func('register_nav_menus', [
            'primary' => \__('Primary Menu', 'aqualuxe'),
            'footer'  => \__('Footer Menu', 'aqualuxe'),
    ]); }
    }

    public static function widgets(): void
    {
    if (\function_exists('register_sidebar')) { \call_user_func('register_sidebar', [
            'name' => \__('Sidebar', 'aqualuxe'),
            'id' => 'sidebar-1',
            'description' => \__('Main sidebar', 'aqualuxe'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
    ]); }
    if (\function_exists('register_sidebar')) { \call_user_func('register_sidebar', [
            'name' => \__('Footer Widgets', 'aqualuxe'),
            'id' => 'footer-1',
            'description' => \__('Footer widget area', 'aqualuxe'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
    ]); }
    }

    public static function flush_rewrite(): void
    {
    if (\function_exists('flush_rewrite_rules')) { \call_user_func('flush_rewrite_rules'); }
    }
}
