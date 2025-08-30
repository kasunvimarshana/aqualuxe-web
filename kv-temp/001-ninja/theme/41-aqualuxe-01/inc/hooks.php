<?php
/** 
 * AquaLuxe Theme Hooks 
 * 
 * @package AquaLuxe
 */

// Prevent direct access to this file
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Initialize theme hooks
 */
function aqualuxe_hooks_init() {
    // Theme setup
    add_action('after_setup_theme', 'aqualuxe_theme_setup');

    // Register sidebars
    add_action('widgets_init', 'aqualuxe_widgets_init');

    // Add body classes
    add_filter('body_class', 'aqualuxe_body_classes');

    // Add async/defer attributes to scripts
    add_filter('script_loader_tag', 'aqualuxe_script_loader_tag', 10, 2);

    // Add preload/preconnect links
    add_action('wp_head', 'aqualuxe_resource_hints', 2);

    // Add theme color meta
    add_action('wp_head', 'aqualuxe_theme_color_meta', 2);

    // Add favicon
    add_action('wp_head', 'aqualuxe_favicon', 2);

    // Add custom CSS variables
    add_action('wp_head', 'aqualuxe_custom_css_variables', 2);

    // Add custom fonts
    add_action('wp_head', 'aqualuxe_custom_fonts', 2);

    // Add color mode script
    add_action('wp_head', 'aqualuxe_color_mode_script', 0);

    // Add custom CSS
    add_action('wp_enqueue_scripts', 'aqualuxe_enqueue_styles');

    // Add custom JS
    add_action('wp_enqueue_scripts', 'aqualuxe_enqueue_scripts');

    // Add excerpt length
    add_filter('excerpt_length', 'aqualuxe_excerpt_length');

    // Add excerpt more
    add_filter('excerpt_more', 'aqualuxe_excerpt_more');

    // Add archive title
    add_filter('get_the_archive_title', 'aqualuxe_archive_title');

    // Add comment form fields
    add_filter('comment_form_default_fields', 'aqualuxe_comment_form_fields');

    // Add comment form defaults
    add_filter('comment_form_defaults', 'aqualuxe_comment_form_defaults');

    // Add search form
    add_filter('get_search_form', 'aqualuxe_search_form');

    // Add gallery style
    add_filter('use_default_gallery_style', '__return_false');

    // Add nav menu args
    add_filter('wp_nav_menu_args', 'aqualuxe_nav_menu_args');

    // Add allowed HTML tags
    add_filter('wp_kses_allowed_html', 'aqualuxe_allowed_html', 10, 2);

    // Add post class
    add_filter('post_class', 'aqualuxe_post_class', 10, 3);

    // Add post thumbnail HTML
    add_filter('post_thumbnail_html', 'aqualuxe_post_thumbnail_html', 10, 5);

    // Add content more link
    add_filter('the_content_more_link', 'aqualuxe_content_more_link', 10, 2);

    // Add title separator
    add_filter('document_title_separator', 'aqualuxe_title_separator');

    // Add title parts
    add_filter('document_title_parts', 'aqualuxe_title_parts');

    // WooCommerce hooks
    if (aqualuxe_is_woocommerce_active()) {
        aqualuxe_woocommerce_hooks_init();
    }
}
add_action('init', 'aqualuxe_hooks_init');
