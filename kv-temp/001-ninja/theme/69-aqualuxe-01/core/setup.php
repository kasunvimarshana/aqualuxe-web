<?php
add_action('after_setup_theme', function() {
    // Theme supports
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo');
    add_theme_support('customize-selective-refresh-widgets');
    add_theme_support('woocommerce');
    add_theme_support('automatic-feed-links');
    add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption']);
    add_theme_support('align-wide');
    add_theme_support('editor-styles');
    add_theme_support('responsive-embeds');
    add_theme_support('wp-block-styles');
    add_theme_support('custom-background');
    add_theme_support('custom-header');
    add_theme_support('menus');

    // Register nav menus
    register_nav_menus([
        'primary'   => __('Primary Menu', 'aqualuxe'),
        'footer'    => __('Footer Menu', 'aqualuxe'),
        'account'   => __('Account Menu', 'aqualuxe'),
    ]);
});
