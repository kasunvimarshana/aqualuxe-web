<?php
/**
 * The template for displaying the front page
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

get_header();

// Hero Section
get_template_part('template-parts/components/hero', 'home');

// Featured Products Section (if WooCommerce is active)
if (aqualuxe_is_woocommerce_active()) {
    get_template_part('template-parts/components/featured-products');
}

// Services Section
get_template_part('template-parts/components/services', 'home');

// About Section
get_template_part('template-parts/components/about', 'home');

// Projects Section
get_template_part('template-parts/components/projects', 'home');

// Testimonials Section
get_template_part('template-parts/components/testimonials', 'home');

// Latest Blog Posts Section
get_template_part('template-parts/components/latest-posts');

// Call to Action Section
get_template_part('template-parts/components/cta', 'home');

get_footer();