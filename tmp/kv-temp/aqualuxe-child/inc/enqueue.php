<?php

/**
 * Enqueue scripts and styles
 */
function aqualuxe_enqueue_assets()
{
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('aqualuxe-style', get_stylesheet_directory_uri() . '/style.css', ['parent-style'], '1.0.0');
    wp_enqueue_style('aqualuxe-custom', get_stylesheet_directory_uri() . '/assets/css/custom.css', ['aqualuxe-style'], '1.0.0');
    wp_enqueue_script('aqualuxe-script', get_stylesheet_directory_uri() . '/assets/js/aqualuxe.js', ['jquery'], '1.0.0', true);
}
add_action('wp_enqueue_scripts', 'aqualuxe_enqueue_assets');
