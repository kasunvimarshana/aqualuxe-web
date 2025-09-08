<?php
/**
 * Module: Custom Post Types
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * AquaLuxe_Custom_Post_Types class.
 */
class AquaLuxe_Custom_Post_Types {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'register_post_types' ) );
		add_action( 'init', array( $this, 'register_taxonomies' ) );
	}

	/**
	 * Register custom post types.
	 */
	public function register_post_types() {
		$this->register_product_post_type();
		$this->register_service_post_type();
		$this->register_event_post_type();
		// Add more post types here.
	}

	/**
	 * Register custom taxonomies.
	 */
	public function register_taxonomies() {
		$this->register_product_taxonomy();
		$this->register_service_taxonomy();
		$this->register_event_taxonomy();
		// Add more taxonomies here.
	}

	/**
	 * Register Product Post Type.
	 */
	private function register_product_post_type() {
		if ( post_type_exists( 'product' ) ) {
			return;
		}

		$labels = array(
			'name'               => _x( 'Products', 'post type general name', 'aqualuxe' ),
			'singular_name'      => _x( 'Product', 'post type singular name', 'aqualuxe' ),
			'menu_name'          => _x( 'Products', 'admin menu', 'aqualuxe' ),
			'name_admin_bar'     => _x( 'Product', 'add new on admin bar', 'aqualuxe' ),
			'add_new'            => _x( 'Add New', 'product', 'aqualuxe' ),
			'add_new_item'       => __( 'Add New Product', 'aqualuxe' ),
			'new_item'           => __( 'New Product', 'aqualuxe' ),
			'edit_item'          => __( 'Edit Product', 'aqualuxe' ),
			'view_item'          => __( 'View Product', 'aqualuxe' ),
			'all_items'          => __( 'All Products', 'aqualuxe' ),
			'search_items'       => __( 'Search Products', 'aqualuxe' ),
			'parent_item_colon'  => __( 'Parent Products:', 'aqualuxe' ),
			'not_found'          => __( 'No products found.', 'aqualuxe' ),
			'not_found_in_trash' => __( 'No products found in Trash.', 'aqualuxe' ),
		);

		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'product' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments', 'custom-fields' ),
			'menu_icon'          => 'dashicons-cart',
		);

		register_post_type( 'product', $args );
	}

	/**
	 * Register Service Post Type.
	 */
	private function register_service_post_type() {
		$labels = array(
			'name'               => _x( 'Services', 'post type general name', 'aqualuxe' ),
			'singular_name'      => _x( 'Service', 'post type singular name', 'aqualuxe' ),
			'menu_name'          => _x( 'Services', 'admin menu', 'aqualuxe' ),
			'name_admin_bar'     => _x( 'Service', 'add new on admin bar', 'aqualuxe' ),
			'add_new'            => _x( 'Add New', 'service', 'aqualuxe' ),
			'add_new_item'       => __( 'Add New Service', 'aqualuxe' ),
			'new_item'           => __( 'New Service', 'aqualuxe' ),
			'edit_item'          => __( 'Edit Service', 'aqualuxe' ),
			'view_item'          => __( 'View Service', 'aqualuxe' ),
			'all_items'          => __( 'All Services', 'aqualuxe' ),
			'search_items'       => __( 'Search Services', 'aqualuxe' ),
			'parent_item_colon'  => __( 'Parent Services:', 'aqualuxe' ),
			'not_found'          => __( 'No services found.', 'aqualuxe' ),
			'not_found_in_trash' => __( 'No services found in Trash.', 'aqualuxe' ),
		);

		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'service' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
			'menu_icon'          => 'dashicons-admin-tools',
		);

		register_post_type( 'service', $args );
	}

	/**
	 * Register Event Post Type.
	 */
	private function register_event_post_type() {
		$labels = array(
			'name'               => _x( 'Events', 'post type general name', 'aqualuxe' ),
			'singular_name'      => _x( 'Event', 'post type singular name', 'aqualuxe' ),
			'menu_name'          => _x( 'Events', 'admin menu', 'aqualuxe' ),
			'name_admin_bar'     => _x( 'Event', 'add new on admin bar', 'aqualuxe' ),
			'add_new'            => _x( 'Add New', 'event', 'aqualuxe' ),
			'add_new_item'       => __( 'Add New Event', 'aqualuxe' ),
			'new_item'           => __( 'New Event', 'aqualuxe' ),
			'edit_item'          => __( 'Edit Event', 'aqualuxe' ),
			'view_item'          => __( 'View Event', 'aqualuxe' ),
			'all_items'          => __( 'All Events', 'aqualuxe' ),
			'search_items'       => __( 'Search Events', 'aqualuxe' ),
			'parent_item_colon'  => __( 'Parent Events:', 'aqualuxe' ),
			'not_found'          => __( 'No events found.', 'aqualuxe' ),
			'not_found_in_trash' => __( 'No events found in Trash.', 'aqualuxe' ),
		);

		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'event' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
			'menu_icon'          => 'dashicons-calendar-alt',
		);

		register_post_type( 'event', $args );
	}

	/**
	 * Register Product Taxonomy.
	 */
	private function register_product_taxonomy() {
		if ( taxonomy_exists( 'product_cat' ) ) {
			return;
		}

		$labels = array(
			'name'              => _x( 'Product Categories', 'taxonomy general name', 'aqualuxe' ),
			'singular_name'     => _x( 'Product Category', 'taxonomy singular name', 'aqualuxe' ),
			'search_items'      => __( 'Search Product Categories', 'aqualuxe' ),
			'all_items'         => __( 'All Product Categories', 'aqualuxe' ),
			'parent_item'       => __( 'Parent Product Category', 'aqualuxe' ),
			'parent_item_colon' => __( 'Parent Product Category:', 'aqualuxe' ),
			'edit_item'         => __( 'Edit Product Category', 'aqualuxe' ),
			'update_item'       => __( 'Update Product Category', 'aqualuxe' ),
			'add_new_item'      => __( 'Add New Product Category', 'aqualuxe' ),
			'new_item_name'     => __( 'New Product Category Name', 'aqualuxe' ),
			'menu_name'         => __( 'Product Categories', 'aqualuxe' ),
		);

		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'product-category' ),
		);

		register_taxonomy( 'product_cat', array( 'product' ), $args );
	}

	/**
	 * Register Service Taxonomy.
	 */
	private function register_service_taxonomy() {
		$labels = array(
			'name'              => _x( 'Service Categories', 'taxonomy general name', 'aqualuxe' ),
			'singular_name'     => _x( 'Service Category', 'taxonomy singular name', 'aqualuxe' ),
			'search_items'      => __( 'Search Service Categories', 'aqualuxe' ),
			'all_items'         => __( 'All Service Categories', 'aqualuxe' ),
			'parent_item'       => __( 'Parent Service Category', 'aqualuxe' ),
			'parent_item_colon' => __( 'Parent Service Category:', 'aqualuxe' ),
			'edit_item'         => __( 'Edit Service Category', 'aqualuxe' ),
			'update_item'       => __( 'Update Service Category', 'aqualuxe' ),
			'add_new_item'      => __( 'Add New Service Category', 'aqualuxe' ),
			'new_item_name'     => __( 'New Service Category Name', 'aqualuxe' ),
			'menu_name'         => __( 'Service Categories', 'aqualuxe' ),
		);

		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'service-category' ),
		);

		register_taxonomy( 'service_cat', array( 'service' ), $args );
	}

	/**
	 * Register Event Taxonomy.
	 */
	private function register_event_taxonomy() {
		$labels = array(
			'name'              => _x( 'Event Categories', 'taxonomy general name', 'aqualuxe' ),
			'singular_name'     => _x( 'Event Category', 'taxonomy singular name', 'aqualuxe' ),
			'search_items'      => __( 'Search Event Categories', 'aqualuxe' ),
			'all_items'         => __( 'All Event Categories', 'aqualuxe' ),
			'parent_item'       => __( 'Parent Event Category', 'aqualuxe' ),
			'parent_item_colon' => __( 'Parent Event Category:', 'aqualuxe' ),
			'edit_item'         => __( 'Edit Event Category', 'aqualuxe' ),
			'update_item'       => __( 'Update Event Category', 'aqualuxe' ),
			'add_new_item'      => __( 'Add New Event Category', 'aqualuxe' ),
			'new_item_name'     => __( 'New Event Category Name', 'aqualuxe' ),
			'menu_name'         => __( 'Event Categories', 'aqualuxe' ),
		);

		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'event-category' ),
		);

		register_taxonomy( 'event_cat', array( 'event' ), $args );
	}
}

new AquaLuxe_Custom_Post_Types();
