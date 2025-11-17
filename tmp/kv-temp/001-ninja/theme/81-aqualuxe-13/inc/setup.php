<?php
/** Theme setup and supports */
if (!defined('ABSPATH')) { exit; }

add_action('after_setup_theme', function() {
    load_theme_textdomain('aqualuxe', get_template_directory() . '/languages');

    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('automatic-feed-links');
    add_theme_support('responsive-embeds');
    add_theme_support('align-wide');
    add_theme_support('wp-block-styles');
    add_theme_support('editor-styles');
    add_theme_support('html5', ['search-form','comment-form','comment-list','gallery','caption','style','script','navigation-widgets']);
    add_theme_support('custom-logo', [
        'height' => 80,
        'width' => 240,
        'flex-width' => true,
        'flex-height' => true,
    ]);
    add_theme_support('customize-selective-refresh-widgets');

    register_nav_menus([
        'primary' => __('Primary Menu', 'aqualuxe'),
        'footer' => __('Footer Menu', 'aqualuxe'),
        'account' => __('Account Menu', 'aqualuxe'),
    ]);

    // Load front-end styles into the block editor for better parity
    add_editor_style('assets/dist/css/app.css');
});

// Register a custom block pattern category
add_action('init', function(){
    if (function_exists('register_block_pattern_category')) {
        register_block_pattern_category('aqualuxe', [
            'label' => __('AquaLuxe', 'aqualuxe')
        ]);
    }
});

// Image sizes
add_action('after_setup_theme', function(){
    add_image_size('hero', 1920, 1080, true);
    add_image_size('card', 800, 600, true);
});
