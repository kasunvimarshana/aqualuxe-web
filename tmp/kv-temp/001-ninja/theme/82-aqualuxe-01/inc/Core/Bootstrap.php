<?php
namespace AquaLuxe\Core;

class Bootstrap {
	public static function init() : void {
		self::register_cpts();
	}

	private static function register_cpts() : void {
		// Services CPT
		register_post_type( 'service', [
			'label' => __( 'Services', 'aqualuxe' ),
			'public' => true,
			'show_in_rest' => true,
			'supports' => [ 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ],
			'menu_icon' => 'dashicons-hammer',
			'has_archive' => true,
			'rewrite' => [ 'slug' => 'services' ],
		] );

		// Events CPT
		register_post_type( 'event', [
			'label' => __( 'Events', 'aqualuxe' ),
			'public' => true,
			'show_in_rest' => true,
			'supports' => [ 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ],
			'menu_icon' => 'dashicons-calendar-alt',
			'has_archive' => true,
			'rewrite' => [ 'slug' => 'events' ],
		] );

		// Taxonomies
		register_taxonomy( 'service_category', [ 'service' ], [
			'label' => __( 'Service Categories', 'aqualuxe' ),
			'hierarchical' => true,
			'show_in_rest' => true,
			'rewrite' => [ 'slug' => 'service-category' ],
		] );

		register_taxonomy( 'event_type', [ 'event' ], [
			'label' => __( 'Event Types', 'aqualuxe' ),
			'hierarchical' => true,
			'show_in_rest' => true,
			'rewrite' => [ 'slug' => 'event-type' ],
		] );
	}
}
