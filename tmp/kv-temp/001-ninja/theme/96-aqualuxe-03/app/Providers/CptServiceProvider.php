<?php

namespace App\Providers;

use App\Core\ServiceProvider;

class CptServiceProvider extends ServiceProvider {
	public function register() {
		add_action( 'init', [ $this, 'register_post_types' ] );
		add_action( 'init', [ $this, 'register_taxonomies' ] );
	}

	public function register_post_types() {
		$this->register_service_post_type();
		$this->register_project_post_type();
	}

	public function register_taxonomies() {
		$this->register_service_type_taxonomy();
		$this->register_project_type_taxonomy();
	}

	private function register_service_post_type() {
		$labels = [
			'name'                  => _x( 'Services', 'Post type general name', 'aqualuxe' ),
			'singular_name'         => _x( 'Service', 'Post type singular name', 'aqualuxe' ),
			'menu_name'             => _x( 'Services', 'Admin Menu text', 'aqualuxe' ),
			'name_admin_bar'        => _x( 'Service', 'Add New on Toolbar', 'aqualuxe' ),
			'add_new'               => __( 'Add New', 'aqualuxe' ),
			'add_new_item'          => __( 'Add New Service', 'aqualuxe' ),
			'new_item'              => __( 'New Service', 'aqualuxe' ),
			'edit_item'             => __( 'Edit Service', 'aqualuxe' ),
			'view_item'             => __( 'View Service', 'aqualuxe' ),
			'all_items'             => __( 'All Services', 'aqualuxe' ),
			'search_items'          => __( 'Search Services', 'aqualuxe' ),
			'parent_item_colon'     => __( 'Parent Services:', 'aqualuxe' ),
			'not_found'             => __( 'No services found.', 'aqualuxe' ),
			'not_found_in_trash'    => __( 'No services found in Trash.', 'aqualuxe' ),
			'featured_image'        => _x( 'Service Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'aqualuxe' ),
			'set_featured_image'    => _x( 'Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'aqualuxe' ),
			'remove_featured_image' => _x( 'Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'aqualuxe' ),
			'use_featured_image'    => _x( 'Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'aqualuxe' ),
			'archives'              => _x( 'Service archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'aqualuxe' ),
			'insert_into_item'      => _x( 'Insert into service', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'aqualuxe' ),
			'uploaded_to_this_item' => _x( 'Uploaded to this service', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'aqualuxe' ),
			'filter_items_list'     => _x( 'Filter services list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'aqualuxe' ),
			'items_list_navigation' => _x( 'Services list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'aqualuxe' ),
			'items_list'            => _x( 'Services list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'aqualuxe' ),
		];

		$args = [
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => [ 'slug' => 'service' ],
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => [ 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ],
			'taxonomies'         => [ 'service_type' ],
			'show_in_rest'       => true,
		];

		register_post_type( 'service', $args );
	}

	private function register_project_post_type() {
		$labels = [
			'name'                  => _x( 'Projects', 'Post type general name', 'aqualuxe' ),
			'singular_name'         => _x( 'Project', 'Post type singular name', 'aqualuxe' ),
			'menu_name'             => _x( 'Projects', 'Admin Menu text', 'aqualuxe' ),
			'name_admin_bar'        => _x( 'Project', 'Add New on Toolbar', 'aqualuxe' ),
			'add_new'               => __( 'Add New', 'aqualuxe' ),
			'add_new_item'          => __( 'Add New Project', 'aqualuxe' ),
			'new_item'              => __( 'New Project', 'aqualuxe' ),
			'edit_item'             => __( 'Edit Project', 'aqualuxe' ),
			'view_item'             => __( 'View Project', 'aqualuxe' ),
			'all_items'             => __( 'All Projects', 'aqualuxe' ),
			'search_items'          => __( 'Search Projects', 'aqualuxe' ),
			'parent_item_colon'     => __( 'Parent Projects:', 'aqualuxe' ),
			'not_found'             => __( 'No projects found.', 'aqualuxe' ),
			'not_found_in_trash'    => __( 'No projects found in Trash.', 'aqualuxe' ),
			'featured_image'        => _x( 'Project Cover Image', 'aqualuxe', 'aqualuxe' ),
			'set_featured_image'    => _x( 'Set cover image', 'aqualuxe', 'aqualuxe' ),
			'remove_featured_image' => _x( 'Remove cover image', 'aqualuxe', 'aqualuxe' ),
			'use_featured_image'    => _x( 'Use as cover image', 'aqualuxe', 'aqualuxe' ),
			'archives'              => _x( 'Project archives', 'aqualuxe', 'aqualuxe' ),
			'insert_into_item'      => _x( 'Insert into project', 'aqualuxe', 'aqualuxe' ),
			'uploaded_to_this_item' => _x( 'Uploaded to this project', 'aqualuxe', 'aqualuxe' ),
			'filter_items_list'     => _x( 'Filter projects list', 'aqualuxe', 'aqualuxe' ),
			'items_list_navigation' => _x( 'Projects list navigation', 'aqualuxe', 'aqualuxe' ),
			'items_list'            => _x( 'Projects list', 'aqualuxe', 'aqualuxe' ),
		];

		$args = [
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => [ 'slug' => 'project' ],
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => [ 'title', 'editor', 'thumbnail', 'excerpt' ],
			'taxonomies'         => [ 'project_type' ],
			'show_in_rest'       => true,
		];

		register_post_type( 'project', $args );
	}

	private function register_service_type_taxonomy() {
		$labels = [
			'name'              => _x( 'Service Types', 'taxonomy general name', 'aqualuxe' ),
			'singular_name'     => _x( 'Service Type', 'taxonomy singular name', 'aqualuxe' ),
			'search_items'      => __( 'Search Service Types', 'aqualuxe' ),
			'all_items'         => __( 'All Service Types', 'aqualuxe' ),
			'parent_item'       => __( 'Parent Service Type', 'aqualuxe' ),
			'parent_item_colon' => __( 'Parent Service Type:', 'aqualuxe' ),
			'edit_item'         => __( 'Edit Service Type', 'aqualuxe' ),
			'update_item'       => __( 'Update Service Type', 'aqualuxe' ),
			'add_new_item'      => __( 'Add New Service Type', 'aqualuxe' ),
			'new_item_name'     => __( 'New Service Type Name', 'aqualuxe' ),
			'menu_name'         => __( 'Service Type', 'aqualuxe' ),
		];

		$args = [
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => [ 'slug' => 'service-type' ],
			'show_in_rest'      => true,
		];

		register_taxonomy( 'service_type', [ 'service' ], $args );
	}

	private function register_project_type_taxonomy() {
		$labels = [
			'name'              => _x( 'Project Types', 'taxonomy general name', 'aqualuxe' ),
			'singular_name'     => _x( 'Project Type', 'taxonomy singular name', 'aqualuxe' ),
			'search_items'      => __( 'Search Project Types', 'aqualuxe' ),
			'all_items'         => __( 'All Project Types', 'aqualuxe' ),
			'parent_item'       => __( 'Parent Project Type', 'aqualuxe' ),
			'parent_item_colon' => __( 'Parent Project Type:', 'aqualuxe' ),
			'edit_item'         => __( 'Edit Project Type', 'aqualuxe' ),
			'update_item'       => __( 'Update Project Type', 'aqualuxe' ),
			'add_new_item'      => __( 'Add New Project Type', 'aqualuxe' ),
			'new_item_name'     => __( 'New Project Type Name', 'aqualuxe' ),
			'menu_name'         => __( 'Project Type', 'aqualuxe' ),
		];

		$args = [
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => [ 'slug' => 'project-type' ],
			'show_in_rest'      => true,
		];

		register_taxonomy( 'project_type', [ 'project' ], $args );
	}
}
