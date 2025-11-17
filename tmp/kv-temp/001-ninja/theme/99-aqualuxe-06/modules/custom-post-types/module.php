<?php
/**
 * Custom Post Types Module
 *
 * Registers custom post types for AquaLuxe business operations
 *
 * @package AquaLuxe
 * @since 2.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Custom Post Types Module Class
 */
class AquaLuxe_Custom_Post_Types_Module {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'register_post_types' ) );
		add_action( 'init', array( $this, 'register_taxonomies' ) );
		add_filter( 'post_updated_messages', array( $this, 'updated_messages' ) );
	}

	/**
	 * Register custom post types
	 */
	public function register_post_types() {
		// Services
		$this->register_services();
		
		// Events
		$this->register_events();
		
		// Team Members
		$this->register_team();
		
		// Testimonials
		$this->register_testimonials();
		
		// Portfolio/Projects
		$this->register_portfolio();
		
		// FAQ
		$this->register_faq();
		
		// Partners
		$this->register_partners();
		
		// Auctions
		$this->register_auctions();
	}

	/**
	 * Register Services post type
	 */
	private function register_services() {
		$labels = array(
			'name'                  => _x( 'Services', 'Post Type General Name', 'aqualuxe' ),
			'singular_name'         => _x( 'Service', 'Post Type Singular Name', 'aqualuxe' ),
			'menu_name'             => __( 'Services', 'aqualuxe' ),
			'name_admin_bar'        => __( 'Service', 'aqualuxe' ),
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
			'not_found'             => __( 'Not found', 'aqualuxe' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'aqualuxe' ),
			'featured_image'        => __( 'Service Image', 'aqualuxe' ),
			'set_featured_image'    => __( 'Set service image', 'aqualuxe' ),
			'remove_featured_image' => __( 'Remove service image', 'aqualuxe' ),
			'use_featured_image'    => __( 'Use as service image', 'aqualuxe' ),
		);

		$args = array(
			'label'                 => __( 'Service', 'aqualuxe' ),
			'description'           => __( 'AquaLuxe Services', 'aqualuxe' ),
			'labels'                => $labels,
			'supports'              => array( 'title', 'editor', 'thumbnail', 'excerpt', 'revisions' ),
			'taxonomies'            => array( 'service_category', 'service_tag' ),
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 20,
			'menu_icon'             => 'dashicons-admin-tools',
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => 'services',
			'exclude_from_search'   => false,
			'publicly_queryable'    => true,
			'capability_type'       => 'post',
			'show_in_rest'          => true,
			'rewrite'               => array( 'slug' => 'service' ),
		);

		register_post_type( 'aqualuxe_service', $args );
	}

	/**
	 * Register Events post type
	 */
	private function register_events() {
		$labels = array(
			'name'                  => _x( 'Events', 'Post Type General Name', 'aqualuxe' ),
			'singular_name'         => _x( 'Event', 'Post Type Singular Name', 'aqualuxe' ),
			'menu_name'             => __( 'Events', 'aqualuxe' ),
			'name_admin_bar'        => __( 'Event', 'aqualuxe' ),
			'add_new_item'          => __( 'Add New Event', 'aqualuxe' ),
			'add_new'               => __( 'Add New', 'aqualuxe' ),
			'new_item'              => __( 'New Event', 'aqualuxe' ),
			'edit_item'             => __( 'Edit Event', 'aqualuxe' ),
			'update_item'           => __( 'Update Event', 'aqualuxe' ),
			'view_item'             => __( 'View Event', 'aqualuxe' ),
			'all_items'             => __( 'All Events', 'aqualuxe' ),
		);

		$args = array(
			'label'                 => __( 'Event', 'aqualuxe' ),
			'description'           => __( 'AquaLuxe Events and Workshops', 'aqualuxe' ),
			'labels'                => $labels,
			'supports'              => array( 'title', 'editor', 'thumbnail', 'excerpt', 'revisions' ),
			'taxonomies'            => array( 'event_category' ),
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 21,
			'menu_icon'             => 'dashicons-calendar-alt',
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => 'events',
			'exclude_from_search'   => false,
			'publicly_queryable'    => true,
			'capability_type'       => 'post',
			'show_in_rest'          => true,
			'rewrite'               => array( 'slug' => 'event' ),
		);

		register_post_type( 'aqualuxe_event', $args );
	}

	/**
	 * Register Team Members post type
	 */
	private function register_team() {
		$labels = array(
			'name'                  => _x( 'Team Members', 'Post Type General Name', 'aqualuxe' ),
			'singular_name'         => _x( 'Team Member', 'Post Type Singular Name', 'aqualuxe' ),
			'menu_name'             => __( 'Team', 'aqualuxe' ),
			'name_admin_bar'        => __( 'Team Member', 'aqualuxe' ),
			'add_new_item'          => __( 'Add New Team Member', 'aqualuxe' ),
			'new_item'              => __( 'New Team Member', 'aqualuxe' ),
			'edit_item'             => __( 'Edit Team Member', 'aqualuxe' ),
			'view_item'             => __( 'View Team Member', 'aqualuxe' ),
			'all_items'             => __( 'All Team Members', 'aqualuxe' ),
		);

		$args = array(
			'label'                 => __( 'Team Member', 'aqualuxe' ),
			'description'           => __( 'AquaLuxe Team Members', 'aqualuxe' ),
			'labels'                => $labels,
			'supports'              => array( 'title', 'editor', 'thumbnail', 'revisions' ),
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 22,
			'menu_icon'             => 'dashicons-groups',
			'show_in_admin_bar'     => false,
			'show_in_nav_menus'     => false,
			'can_export'            => true,
			'has_archive'           => false,
			'exclude_from_search'   => true,
			'publicly_queryable'    => true,
			'capability_type'       => 'post',
			'show_in_rest'          => true,
		);

		register_post_type( 'aqualuxe_team', $args );
	}

	/**
	 * Register custom taxonomies
	 */
	public function register_taxonomies() {
		// Service Category
		register_taxonomy( 'service_category', array( 'aqualuxe_service' ), array(
			'hierarchical'      => true,
			'labels'            => array(
				'name'              => _x( 'Service Categories', 'taxonomy general name', 'aqualuxe' ),
				'singular_name'     => _x( 'Service Category', 'taxonomy singular name', 'aqualuxe' ),
			),
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'show_in_rest'      => true,
			'rewrite'           => array( 'slug' => 'service-category' ),
		) );

		// Event Category
		register_taxonomy( 'event_category', array( 'aqualuxe_event' ), array(
			'hierarchical'      => true,
			'labels'            => array(
				'name'              => _x( 'Event Categories', 'taxonomy general name', 'aqualuxe' ),
				'singular_name'     => _x( 'Event Category', 'taxonomy singular name', 'aqualuxe' ),
			),
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'show_in_rest'      => true,
			'rewrite'           => array( 'slug' => 'event-category' ),
		) );
	}

	/**
	 * Custom post type updated messages
	 */
	public function updated_messages( $messages ) {
		$messages['aqualuxe_service'] = array(
			0  => '', // Unused. Messages start at index 1.
			1  => __( 'Service updated.', 'aqualuxe' ),
			2  => __( 'Custom field updated.', 'aqualuxe' ),
			3  => __( 'Custom field deleted.', 'aqualuxe' ),
			4  => __( 'Service updated.', 'aqualuxe' ),
			6  => __( 'Service published.', 'aqualuxe' ),
			7  => __( 'Service saved.', 'aqualuxe' ),
			8  => __( 'Service submitted.', 'aqualuxe' ),
			10 => __( 'Service draft updated.', 'aqualuxe' ),
		);

		return $messages;
	}
}

// Initialize the module
new AquaLuxe_Custom_Post_Types_Module();