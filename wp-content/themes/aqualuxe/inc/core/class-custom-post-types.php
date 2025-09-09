<?php
/**
 * Custom Post Types.
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Handles custom post type registration.
 */
class AquaLuxe_Custom_Post_Types {

	/**
	 * Register custom post types.
	 */
	public static function register() {
		self::register_services_cpt();
		self::register_events_cpt();
		// Add more CPTs here.
	}

	/**
	 * Register 'Services' Custom Post Type.
	 */
	private static function register_services_cpt() {
		$labels = array(
			'name'                  => _x( 'Services', 'Post Type General Name', 'aqualuxe' ),
			'singular_name'         => _x( 'Service', 'Post Type Singular Name', 'aqualuxe' ),
			'menu_name'             => __( 'Services', 'aqualuxe' ),
			'archives'              => __( 'Service Archives', 'aqualuxe' ),
			'attributes'            => __( 'Service Attributes', 'aqualuxe' ),
			'parent_item_colon'     => __( 'Parent Service:', 'aqualuxe' ),
			'all_items'             => __( 'All Services', 'aqualuxe' ),
			'add_new_item'          => __( 'Add New Service', 'aqualuxe' ),
			'add_new'               => __( 'Add New', 'aqualuxe' ),
			'new_item'              => __( 'New Service', 'aqualuxe' ),
			'edit_item'             => __( 'Edit Service', 'aqualuxe' ),
			'update_item'           => __( 'Update Service', 'aqualuxe' ),
			'view_item'             => __( 'View Service', 'aqualuxe' ),
			'view_items'            => __( 'View Services', 'aqualuxe' ),
			'search_items'          => __( 'Search Service', 'aqualuxe' ),
		);
		$args = array(
			'label'                 => __( 'Service', 'aqualuxe' ),
			'description'           => __( 'AquaLuxe Services', 'aqualuxe' ),
			'labels'                => $labels,
			'supports'              => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 5,
			'menu_icon'             => 'dashicons-admin-generic',
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => true,
			'exclude_from_search'   => false,
			'publicly_queryable'    => true,
			'capability_type'       => 'page',
            'show_in_rest'          => true,
		);
		register_post_type( 'service', $args );
	}

    /**
	 * Register 'Events' Custom Post Type.
	 */
	private static function register_events_cpt() {
		$labels = array(
			'name'                  => _x( 'Events', 'Post Type General Name', 'aqualuxe' ),
			'singular_name'         => _x( 'Event', 'Post Type Singular Name', 'aqualuxe' ),
			'menu_name'             => __( 'Events', 'aqualuxe' ),
			'archives'              => __( 'Event Archives', 'aqualuxe' ),
			'attributes'            => __( 'Event Attributes', 'aqualuxe' ),
			'parent_item_colon'     => __( 'Parent Event:', 'aqualuxe' ),
			'all_items'             => __( 'All Events', 'aqualuxe' ),
			'add_new_item'          => __( 'Add New Event', 'aqualuxe' ),
			'add_new'               => __( 'Add New', 'aqualuxe' ),
			'new_item'              => __( 'New Event', 'aqualuxe' ),
			'edit_item'             => __( 'Edit Event', 'aqualuxe' ),
			'update_item'           => __( 'Update Event', 'aqualuxe' ),
			'view_item'             => __( 'View Event', 'aqualuxe' ),
			'view_items'            => __( 'View Events', 'aqualuxe' ),
			'search_items'          => __( 'Search Event', 'aqualuxe' ),
		);
		$args = array(
			'label'                 => __( 'Event', 'aqualuxe' ),
			'description'           => __( 'AquaLuxe Events', 'aqualuxe' ),
			'labels'                => $labels,
			'supports'              => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 5,
			'menu_icon'             => 'dashicons-calendar-alt',
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => true,
			'exclude_from_search'   => false,
			'publicly_queryable'    => true,
			'capability_type'       => 'page',
            'show_in_rest'          => true,
		);
		register_post_type( 'event', $args );
	}
}
