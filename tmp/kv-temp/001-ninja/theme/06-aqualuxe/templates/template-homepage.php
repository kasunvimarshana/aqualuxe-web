<?php
/**
 * Template Name: Homepage
 *
 * The template for displaying the homepage.
 *
 * @package AquaLuxe
 */

get_header();

// Get homepage options
$homepage_options = get_option( 'aqualuxe_homepage_options', array() );

// Hero Section
if ( isset( $homepage_options['hero_enabled'] ) && $homepage_options['hero_enabled'] ) {
    get_template_part( 'templates/blocks/homepage-hero', null, $homepage_options );
}

// Featured Products Section
if ( isset( $homepage_options['featured_products_enabled'] ) && $homepage_options['featured_products_enabled'] ) {
    get_template_part( 'templates/blocks/homepage-featured-products', null, $homepage_options );
}

// Fish Species Section
if ( isset( $homepage_options['fish_species_enabled'] ) && $homepage_options['fish_species_enabled'] ) {
    get_template_part( 'templates/blocks/homepage-fish-species', null, $homepage_options );
}

// Testimonials Section
if ( isset( $homepage_options['testimonials_enabled'] ) && $homepage_options['testimonials_enabled'] ) {
    get_template_part( 'templates/blocks/homepage-testimonials', null, $homepage_options );
}

// Blog Section
if ( isset( $homepage_options['blog_enabled'] ) && $homepage_options['blog_enabled'] ) {
    get_template_part( 'templates/blocks/homepage-blog', null, $homepage_options );
}

// CTA Section
if ( isset( $homepage_options['cta_enabled'] ) && $homepage_options['cta_enabled'] ) {
    get_template_part( 'templates/blocks/homepage-cta', null, $homepage_options );
}

get_footer();