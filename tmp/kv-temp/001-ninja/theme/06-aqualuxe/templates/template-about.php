<?php
/**
 * Template Name: About
 *
 * The template for displaying the about page.
 *
 * @package AquaLuxe
 */

get_header();

// Get about page options
$about_options = get_option( 'aqualuxe_about_options', array() );

// Hero Section
if ( isset( $about_options['hero_enabled'] ) && $about_options['hero_enabled'] ) {
    get_template_part( 'templates/blocks/about-hero', null, $about_options );
}

// History Section
if ( isset( $about_options['history_enabled'] ) && $about_options['history_enabled'] ) {
    get_template_part( 'templates/blocks/about-history', null, $about_options );
}

// Mission & Values Section
if ( isset( $about_options['mission_enabled'] ) && $about_options['mission_enabled'] ) {
    get_template_part( 'templates/blocks/about-mission', null, $about_options );
}

// Team Section
if ( isset( $about_options['team_enabled'] ) && $about_options['team_enabled'] ) {
    get_template_part( 'templates/blocks/about-team', null, $about_options );
}

// Facilities Section
if ( isset( $about_options['facilities_enabled'] ) && $about_options['facilities_enabled'] ) {
    get_template_part( 'templates/blocks/about-facilities', null, $about_options );
}

// CTA Section
if ( isset( $about_options['cta_enabled'] ) && $about_options['cta_enabled'] ) {
    get_template_part( 'templates/blocks/about-cta', null, $about_options );
}

get_footer();