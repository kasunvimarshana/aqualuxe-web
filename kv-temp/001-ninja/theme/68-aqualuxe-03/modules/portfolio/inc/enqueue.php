<?php
// Enqueue Portfolio Module Assets
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'wp_enqueue_scripts', function() {
    $module_uri = get_template_directory_uri() . '/modules/portfolio/assets';
    $module_path = get_template_directory() . '/modules/portfolio/assets';
    // Enqueue compiled CSS/JS from assets/dist in production
    if ( file_exists( $module_path . '/portfolio.css' ) ) {
        wp_enqueue_style( 'aqualuxe-portfolio', $module_uri . '/portfolio.css', [], null );
    }
    if ( file_exists( $module_path . '/portfolio.js' ) ) {
        wp_enqueue_script( 'aqualuxe-portfolio', $module_uri . '/portfolio.js', [], null, true );
    }
}, 20 );
