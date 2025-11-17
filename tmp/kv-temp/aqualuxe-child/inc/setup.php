<?php

/**
 * Theme setup
 */
function aqualuxe_theme_setup()
{
    add_theme_support('woocommerce');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');

    // Custom image size for product cards
    add_image_size('aqualuxe-product', 600, 600, true);
}
add_action('after_setup_theme', 'aqualuxe_theme_setup');
