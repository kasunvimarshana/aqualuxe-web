<?php
/**
 * Enqueue assets
 */
add_action( 'wp_enqueue_scripts', 'aqualuxe_enqueue' );

function aqualuxe_enqueue() {
    // Parent + child
    wp_enqueue_style( 'storefront-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'aqualuxe-style', get_stylesheet_uri(), ['storefront-style'], '1.0.0' );

    wp_enqueue_script( 'aqualuxe-js', get_stylesheet_directory_uri() . '/assets/js/aqualuxe.js', ['jquery'], '1.0.0', true );
}