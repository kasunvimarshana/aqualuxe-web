<?php
/**
 * Enqueue scripts and styles.
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'aqualuxe_scripts' ) ) :
    /**
     * Enqueue scripts and styles.
     */
    function aqualuxe_scripts() {
        // Get the asset manifest.
        $manifest_path = AQUALUXE_THEME_DIR . 'assets/dist/mix-manifest.json';
        $manifest = file_exists( $manifest_path ) ? json_decode( file_get_contents( $manifest_path ), true ) : [];

        // Enqueue main stylesheet.
        $css_path = isset( $manifest['/css/app.css'] ) ? $manifest['/css/app.css'] : '/css/app.css';
        wp_enqueue_style( 'aqualuxe-style', AQUALUXE_THEME_URI . 'assets/dist' . $css_path, array(), AQUALUXE_VERSION );

        // Enqueue main javascript.
        $js_path = isset( $manifest['/js/app.js'] ) ? $manifest['/js/app.js'] : '/js/app.js';
        wp_enqueue_script( 'aqualuxe-script', AQUALUXE_THEME_URI . 'assets/dist' . $js_path, array('jquery'), AQUALUXE_VERSION, true );

        if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
            wp_enqueue_script( 'comment-reply' );
        }
    }
endif;
add_action( 'wp_enqueue_scripts', 'aqualuxe_scripts' );
