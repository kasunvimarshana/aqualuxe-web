<?php
/**
 * Jetpack Compatibility File
 *
 * @package AquaLuxe
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Jetpack setup function
 */
function aqualuxe_jetpack_setup() {
    // Add theme support for Infinite Scroll
    add_theme_support('infinite-scroll', array(
        'container' => 'main',
        'render'    => 'aqualuxe_infinite_scroll_render',
        'footer'    => 'page',
    ));

    // Add theme support for Responsive Videos
    add_theme_support('jetpack-responsive-videos');

    // Add theme support for Content Options
    add_theme_support('jetpack-content-options', array(
        'post-details'    => array(
            'stylesheet' => 'aqualuxe-style',
            'date'       => '.posted-on',
            'categories' => '.cat-links',
            'tags'       => '.tags-links',
            'author'     => '.byline',
            'comment'    => '.comments-link',
        ),
        'featured-images' => array(
            'archive'    => true,
            'post'       => true,
            'page'       => true,
        ),
    ));
}
add_action('after_setup_theme', 'aqualuxe_jetpack_setup');

/**
 * Custom render function for Infinite Scroll
 */
function aqualuxe_infinite_scroll_render() {
    while (have_posts()) {
        the_post();
        if (is_search()) :
            get_template_part('template-parts/content', 'search');
        else :
            get_template_part('template-parts/content', get_post_type());
        endif;
    }
}