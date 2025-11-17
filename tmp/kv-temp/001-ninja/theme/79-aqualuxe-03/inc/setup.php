<?php
declare(strict_types=1);

namespace Aqualuxe\Inc;

// Theme setup.
add_action('after_setup_theme', static function (): void {
    // i18n
    load_theme_textdomain('aqualuxe', get_template_directory() . '/languages');

    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('responsive-embeds');
    add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script']);
    add_theme_support('custom-logo', [
        'height' => 80,
        'width' => 240,
        'flex-height' => true,
        'flex-width' => true,
    ]);
    add_theme_support('customize-selective-refresh-widgets');
    add_theme_support('automatic-feed-links');
    add_theme_support('align-wide');
    add_theme_support('wp-block-styles');

    // Editor styles.
    add_theme_support('editor-styles');
    add_editor_style('assets/dist/editor.css');

    // Menus.
    register_nav_menus([
        'primary' => __('Primary Menu', 'aqualuxe'),
        'footer' => __('Footer Menu', 'aqualuxe'),
        'account' => __('Account Menu', 'aqualuxe'),
    ]);

    // Block pattern category
    if (function_exists('register_block_pattern_category')) {
        register_block_pattern_category('aqualuxe', ['label' => __('AquaLuxe', 'aqualuxe')]);
    }
});

// Widgets.
add_action('widgets_init', static function (): void {
    register_sidebar([
        'name' => __('Sidebar', 'aqualuxe'),
        'id' => 'sidebar-1',
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ]);
    register_sidebar([
        'name' => __('Footer Widgets', 'aqualuxe'),
        'id' => 'footer-1',
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ]);
});
