<?php
/**
 * Theme setup
 */
add_action( 'after_setup_theme', 'aqualuxe_setup' );

function aqualuxe_setup() {
    add_theme_support( 'woocommerce' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );

    add_image_size( 'aqualuxe-thumb', 600, 600, true );
}