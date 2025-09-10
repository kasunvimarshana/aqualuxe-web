<?php
/**
 * Custom Post Types
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Custom Post Types.
 */
class AquaLuxe_Custom_Post_Types {

	/**
	 * Register hooks.
	 */
	public function register() {
		add_action( 'init', array( $this, 'register_post_types' ) );
		add_action( 'init', array( $this, 'register_taxonomies' ) );
	}

	/**
	 * Register post types.
	 */
	public function register_post_types() {
		// Services
		$this->register_single_post_type(
			'service',
			'Service',
			'Services',
			array(
				'menu_icon' => 'dashicons-admin-tools',
				'supports'  => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
			)
		);

		// Events
		$this->register_single_post_type(
			'event',
			'Event',
			'Events',
			array(
				'menu_icon' => 'dashicons-calendar-alt',
				'supports'  => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
			)
		);

		// Add other CPTs based on the prompt:
		// Products are handled by WooCommerce
		// Testimonials
		$this->register_single_post_type(
			'testimonial',
			'Testimonial',
			'Testimonials',
			array(
				'menu_icon' => 'dashicons-format-quote',
				'supports'  => array( 'title', 'editor', 'thumbnail' ),
			)
		);

		// FAQ
		$this->register_single_post_type(
			'faq',
			'FAQ',
			'FAQs',
			array(
				'menu_icon' => 'dashicons-editor-help',
				'supports'  => array( 'title', 'editor' ),
			)
		);
	}

	/**
	 * Register taxonomies.
	 */
	public function register_taxonomies() {
		// Service Categories
		$this->register_single_taxonomy( 'service_category', 'Service Category', 'Service Categories', array( 'service' ) );

		// Event Categories
		$this->register_single_taxonomy( 'event_category', 'Event Category', 'Event Categories', array( 'event' ) );
	}

	/**
	 * Helper function to register a custom post type.
	 *
	 * @param string $post_type The post type slug.
	 * @param string $singular  The singular name.
	 * @param string $plural    The plural name.
	 * @param array  $args      Additional arguments.
	 */
	private function register_single_post_type( $post_type, $singular, $plural, $args = array() ) {
		$labels = array(
			'name'               => _x( $plural, 'post type general name', 'aqualuxe' ),
			'singular_name'      => _x( $singular, 'post type singular name', 'aqualuxe' ),
			'menu_name'          => _x( $plural, 'admin menu', 'aqualuxe' ),
			'name_admin_bar'     => _x( $singular, 'add new on admin bar', 'aqualuxe' ),
			'add_new'            => _x( 'Add New', $singular, 'aqualuxe' ),
			'add_new_item'       => __( 'Add New ' . $singular, 'aqualuxe' ),
			'new_item'           => __( 'New ' . $singular, 'aqualuxe' ),
			'edit_item'          => __( 'Edit ' . $singular, 'aqualuxe' ),
			'view_item'          => __( 'View ' . $singular, 'aqualuxe' ),
			'all_items'          => __( 'All ' . $plural, 'aqualuxe' ),
			'search_items'       => __( 'Search ' . $plural, 'aqualuxe' ),
			'parent_item_colon'  => __( 'Parent ' . $plural . ':', 'aqualuxe' ),
			'not_found'          => __( 'No ' . strtolower( $plural ) . ' found.', 'aqualuxe' ),
			'not_found_in_trash' => __( 'No ' . strtolower( $plural ) . ' found in Trash.', 'aqualuxe' ),
		);

		$defaults = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => strtolower( $post_type ) ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => 20,
			'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ),
			'show_in_rest'       => true,
		);

		$args = wp_parse_args( $args, $defaults );

		register_post_type( $post_type, $args );
	}

	/**
	 * Helper function to register a custom taxonomy.
	 *
	 * @param string       $taxonomy The taxonomy slug.
	 * @param string       $singular The singular name.
	 * @param string       $plural   The plural name.
	 * @param array|string $post_types Post types to attach the taxonomy to.
	 * @param array        $args     Additional arguments.
	 */
	private function register_single_taxonomy( $taxonomy, $singular, $plural, $post_types, $args = array() ) {
		$labels = array(
			'name'              => _x( $plural, 'taxonomy general name', 'aqualuxe' ),
			'singular_name'     => _x( $singular, 'taxonomy singular name', 'aqualuxe' ),
			'search_items'      => __( 'Search ' . $plural, 'aqualuxe' ),
			'all_items'         => __( 'All ' . $plural, 'aqualuxe' ),
			'parent_item'       => __( 'Parent ' . $singular, 'aqualuxe' ),
			'parent_item_colon' => __( 'Parent ' . $singular . ':', 'aqualuxe' ),
			'edit_item'         => __( 'Edit ' . $singular, 'aqualuxe' ),
			'update_item'       => __( 'Update ' . $singular, 'aqualuxe' ),
			'add_new_item'      => __( 'Add New ' . $singular, 'aqualuxe' ),
			'new_item_name'     => __( 'New ' . $singular . ' Name', 'aqualuxe' ),
			'menu_name'         => __( $plural, 'aqualuxe' ),
		);

		$defaults = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => strtolower( $taxonomy ) ),
			'show_in_rest'      => true,
		);

		$args = wp_parse_args( $args, $defaults );

		register_taxonomy( $taxonomy, $post_types, $args );
	}
}
