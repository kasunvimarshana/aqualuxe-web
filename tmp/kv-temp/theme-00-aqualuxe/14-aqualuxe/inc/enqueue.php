<?php
/**
 * AquaLuxe Enqueue Scripts and Styles
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
		// Enqueue main stylesheet
		wp_enqueue_style( 'aqualuxe-style', get_stylesheet_uri(), array(), AQUALUXE_VERSION );
		
		// Enqueue main CSS file from assets
		wp_enqueue_style( 'aqualuxe-main', AQUALUXE_URI . '/assets/css/main.css', array(), AQUALUXE_VERSION );
		
		// Enqueue Google Fonts
		wp_enqueue_style( 'aqualuxe-google-fonts', 'https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Montserrat:wght@300;400;500;600;700&display=swap', array(), null );
		
		// Enqueue main JavaScript file
		wp_enqueue_script( 'aqualuxe-main-js', AQUALUXE_URI . '/assets/js/main.js', array( 'jquery' ), AQUALUXE_VERSION, true );
		
		// Enqueue comment reply script if comments are enabled
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
		
		// Localize script for AJAX
		wp_localize_script( 'aqualuxe-main-js', 'aqualuxe_ajax', array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'nonce'    => wp_create_nonce( 'aqualuxe_nonce' ),
		) );
	}
endif;
add_action( 'wp_enqueue_scripts', 'aqualuxe_scripts' );

if ( ! function_exists( 'aqualuxe_admin_scripts' ) ) :
	/**
	 * Enqueue admin scripts and styles.
	 */
	function aqualuxe_admin_scripts() {
		// Enqueue admin CSS
		wp_enqueue_style( 'aqualuxe-admin-style', AQUALUXE_URI . '/assets/css/admin.css', array(), AQUALUXE_VERSION );
		
		// Enqueue admin JavaScript
		wp_enqueue_script( 'aqualuxe-admin-js', AQUALUXE_URI . '/assets/js/admin.js', array( 'jquery' ), AQUALUXE_VERSION, true );
	}
endif;
add_action( 'admin_enqueue_scripts', 'aqualuxe_admin_scripts' );