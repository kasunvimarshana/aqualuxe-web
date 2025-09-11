<?php
/**
 * Enqueue scripts and styles.
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! function_exists( 'aqualuxe_scripts' ) ) :
	/**
	 * Enqueue scripts and styles.
	 */
	function aqualuxe_scripts() {
		// Enqueue main stylesheet.
		wp_enqueue_style( 'aqualuxe-style', get_stylesheet_uri(), array(), AQUALUXE_VERSION );

		// Enqueue main script.
		wp_enqueue_script( 'aqualuxe-script', AQUALUXE_THEME_URI . '/assets/js/main.js', array( 'jquery' ), AQUALUXE_VERSION, true );

		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}
endif;
add_action( 'wp_enqueue_scripts', 'aqualuxe_scripts' );
