<?php
namespace AquaLuxe\Core;

/**
 * Registers custom post types & taxonomies.
 */
final class CPT {
	public static function init(): void {
		\add_action( 'init', [ __CLASS__, 'register' ] );
	}

	public static function register(): void {
		// Services CPT
		\register_post_type( 'service', [
			'labels' => [
				'name'          => __( 'Services', 'aqualuxe' ),
				'singular_name' => __( 'Service', 'aqualuxe' ),
			],
			'public'      => true,
			'has_archive' => true,
			'show_in_rest'=> true,
			'menu_icon'   => 'dashicons-hammer',
			'supports'    => [ 'title', 'editor', 'excerpt', 'thumbnail', 'revisions' ],
		] );

		// Events CPT
		\register_post_type( 'event', [
			'labels' => [
				'name'          => __( 'Events', 'aqualuxe' ),
				'singular_name' => __( 'Event', 'aqualuxe' ),
			],
			'public'      => true,
			'has_archive' => true,
			'show_in_rest'=> true,
			'menu_icon'   => 'dashicons-calendar-alt',
			'supports'    => [ 'title', 'editor', 'excerpt', 'thumbnail', 'revisions' ],
		] );

		// Taxonomy: Species for Services/Products
		\register_taxonomy( 'species', [ 'product', 'service' ], [
			'labels' => [
				'name'          => __( 'Species', 'aqualuxe' ),
				'singular_name' => __( 'Species', 'aqualuxe' ),
			],
			'show_in_rest' => true,
			'hierarchical' => true,
			'public'       => true,
		] );
	}
}
