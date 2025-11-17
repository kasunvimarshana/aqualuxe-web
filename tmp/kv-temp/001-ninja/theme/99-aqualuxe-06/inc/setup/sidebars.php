<?php
/**
 * Sidebar/Widget Areas Setup
 *
 * Register widget areas for the theme
 *
 * @package AquaLuxe
 * @since 2.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register widget areas
 */
function aqualuxe_widgets_init() {
	// Main sidebar
	register_sidebar( array(
		'name'          => esc_html__( 'Main Sidebar', 'aqualuxe' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'aqualuxe' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	// Footer widget areas
	for ( $i = 1; $i <= 4; $i++ ) {
		register_sidebar( array(
			'name'          => sprintf( esc_html__( 'Footer Widget Area %d', 'aqualuxe' ), $i ),
			'id'            => "footer-{$i}",
			'description'   => sprintf( esc_html__( 'Add widgets here to appear in footer column %d.', 'aqualuxe' ), $i ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		) );
	}

	// Shop sidebar (WooCommerce)
	if ( class_exists( 'WooCommerce' ) ) {
		register_sidebar( array(
			'name'          => esc_html__( 'Shop Sidebar', 'aqualuxe' ),
			'id'            => 'sidebar-shop',
			'description'   => esc_html__( 'Add widgets here to appear on shop pages.', 'aqualuxe' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		) );
	}
}
add_action( 'widgets_init', 'aqualuxe_widgets_init' );