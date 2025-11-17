<?php
/**
 * Custom Post Types
 *
 * @package AquaLuxe\Core
 */

namespace AquaLuxe\Core;

if ( ! defined( 'ABSPATH' ) ) { exit; }

add_action( 'init', function () {
	\register_post_type( 'service', [
		'labels' => [
			'name'          => \__( 'Services', 'aqualuxe' ),
			'singular_name' => \__( 'Service', 'aqualuxe' ),
		],
		'public'       => true,
		'has_archive'  => true,
		'show_in_rest' => true,
		'menu_icon'    => 'dashicons-hammer',
		'supports'     => [ 'title', 'editor', 'excerpt', 'thumbnail', 'custom-fields' ],
		'rewrite'     => [ 'slug' => 'services' ],
	] );

	\register_post_type( 'event', [
		'labels' => [
			'name'          => \__( 'Events', 'aqualuxe' ),
			'singular_name' => \__( 'Event', 'aqualuxe' ),
		],
		'public'       => true,
		'has_archive'  => true,
		'show_in_rest' => true,
		'menu_icon'    => 'dashicons-calendar-alt',
		'supports'     => [ 'title', 'editor', 'excerpt', 'thumbnail', 'custom-fields' ],
		'rewrite'     => [ 'slug' => 'events' ],
	] );

	\register_post_type( 'testimonial', [
		'labels' => [
			'name'          => \__( 'Testimonials', 'aqualuxe' ),
			'singular_name' => \__( 'Testimonial', 'aqualuxe' ),
		],
		'public'       => true,
		'has_archive'  => false,
		'show_in_rest' => true,
		'menu_icon'    => 'dashicons-format-quote',
		'supports'     => [ 'title', 'editor', 'thumbnail' ],
		'rewrite'     => [ 'slug' => 'testimonials' ],
	] );
} );
