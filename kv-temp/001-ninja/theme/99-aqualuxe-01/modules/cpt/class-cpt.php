<?php
/**
 * Custom Post Types and Taxonomies for AquaLuxe.
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * The main CPT and Taxonomies class.
 */
class AquaLuxe_CPT {

	/**
	 * Initialize the CPT and Taxonomies.
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'register_project_cpt' ) );
		add_action( 'init', array( __CLASS__, 'register_taxonomies' ) );
	}

	/**
	 * Register the Project CPT.
	 */
	public static function register_project_cpt() {
		$labels = array(
			'name'               => _x( 'Projects', 'post type general name', 'aqualuxe' ),
			'singular_name'      => _x( 'Project', 'post type singular name', 'aqualuxe' ),
			'menu_name'          => _x( 'Projects', 'admin menu', 'aqualuxe' ),
			'name_admin_bar'     => _x( 'Project', 'add new on admin bar', 'aqualuxe' ),
			'add_new'            => _x( 'Add New', 'project', 'aqualuxe' ),
			'add_new_item'       => __( 'Add New Project', 'aqualuxe' ),
			'new_item'           => __( 'New Project', 'aqualuxe' ),
			'edit_item'          => __( 'Edit Project', 'aqualuxe' ),
			'view_item'          => __( 'View Project', 'aqualuxe' ),
			'all_items'          => __( 'All Projects', 'aqualuxe' ),
			'search_items'       => __( 'Search Projects', 'aqualuxe' ),
			'parent_item_colon'  => __( 'Parent Projects:', 'aqualuxe' ),
			'not_found'          => __( 'No projects found.', 'aqualuxe' ),
			'not_found_in_trash' => __( 'No projects found in Trash.', 'aqualuxe' )
		);

		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'project' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
            'taxonomies'         => array( 'service', 'industry' ),
		);

		register_post_type( 'project', $args );
	}

	/**
	 * Register custom taxonomies.
	 */
	public static function register_taxonomies() {
		// Register Service Taxonomy
		$service_labels = array(
			'name'              => _x( 'Services', 'taxonomy general name', 'aqualuxe' ),
			'singular_name'     => _x( 'Service', 'taxonomy singular name', 'aqualuxe' ),
			'search_items'      => __( 'Search Services', 'aqualuxe' ),
			'all_items'         => __( 'All Services', 'aqualuxe' ),
			'parent_item'       => __( 'Parent Service', 'aqualuxe' ),
			'parent_item_colon' => __( 'Parent Service:', 'aqualuxe' ),
			'edit_item'         => __( 'Edit Service', 'aqualuxe' ),
			'update_item'       => __( 'Update Service', 'aqualuxe' ),
			'add_new_item'      => __( 'Add New Service', 'aqualuxe' ),
			'new_item_name'     => __( 'New Service Name', 'aqualuxe' ),
			'menu_name'         => __( 'Services', 'aqualuxe' ),
		);

		$service_args = array(
			'hierarchical'      => true,
			'labels'            => $service_labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'service' ),
		);

		register_taxonomy( 'service', array( 'project' ), $service_args );

		// Register Industry Taxonomy
		$industry_labels = array(
			'name'              => _x( 'Industries', 'taxonomy general name', 'aqualuxe' ),
			'singular_name'     => _x( 'Industry', 'taxonomy singular name', 'aqualuxe' ),
			'search_items'      => __( 'Search Industries', 'aqualuxe' ),
			'all_items'         => __( 'All Industries', 'aqualuxe' ),
			'parent_item'       => __( 'Parent Industry', 'aqualuxe' ),
			'parent_item_colon' => __( 'Parent Industry:', 'aqualuxe' ),
			'edit_item'         => __( 'Edit Industry', 'aqualuxe' ),
			'update_item'       => __( 'Update Industry', 'aqualuxe' ),
			'add_new_item'      => __( 'Add New Industry', 'aqualuxe' ),
			'new_item_name'     => __( 'New Industry Name', 'aqualuxe' ),
			'menu_name'         => __( 'Industries', 'aqualuxe' ),
		);

		$industry_args = array(
			'hierarchical'      => true,
			'labels'            => $industry_labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'industry' ),
		);

		register_taxonomy( 'industry', array( 'project' ), $industry_args );
	}
}
