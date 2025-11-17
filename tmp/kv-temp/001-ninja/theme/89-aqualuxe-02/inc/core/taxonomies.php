<?php
/**
 * Custom Taxonomies
 *
 * @package AquaLuxe\Core
 */

namespace AquaLuxe\Core;

if ( ! defined( 'ABSPATH' ) ) { exit; }

add_action( 'init', function () {
	\register_taxonomy( 'service_category', [ 'service' ], [
		'labels' => [
			'name'          => \__( 'Service Categories', 'aqualuxe' ),
			'singular_name' => \__( 'Service Category', 'aqualuxe' ),
		],
		'hierarchical' => true,
		'show_in_rest' => true,
		'rewrite'     => [ 'slug' => 'service-category' ],
	] );

	\register_taxonomy( 'event_type', [ 'event' ], [
		'labels' => [
			'name'          => \__( 'Event Types', 'aqualuxe' ),
			'singular_name' => \__( 'Event Type', 'aqualuxe' ),
		],
		'hierarchical' => true,
		'show_in_rest' => true,
		'rewrite'     => [ 'slug' => 'event-type' ],
	] );
} );
