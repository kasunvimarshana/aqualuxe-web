<?php

namespace App\Providers;

use App\Core\ServiceProvider;

class CustomPostTypeServiceProvider extends ServiceProvider {
	public function register() {
		add_action( 'init', [ $this, 'register_post_types' ] );
		add_action( 'init', [ $this, 'register_taxonomies' ] );
	}

	public function register_post_types() {
		$this->register_service_post_type();
		$this->register_project_post_type();
		$this->register_event_post_type();
		$this->register_team_member_post_type();
		$this->register_testimonial_post_type();
		$this->register_faq_post_type();
	}

	public function register_taxonomies() {
		$this->register_service_category_taxonomy();
		$this->register_project_category_taxonomy();
		$this->register_event_category_taxonomy();
	}

	private function register_service_post_type() {
		$labels = [
			'name'          => _x( 'Services', 'Post type general name', 'aqualuxe' ),
			'singular_name' => _x( 'Service', 'Post type singular name', 'aqualuxe' ),
			'menu_name'     => _x( 'Services', 'Admin Menu text', 'aqualuxe' ),
			'add_new_item'  => __( 'Add New Service', 'aqualuxe' ),
			'edit_item'     => __( 'Edit Service', 'aqualuxe' ),
			'view_item'     => __( 'View Service', 'aqualuxe' ),
			'all_items'     => __( 'All Services', 'aqualuxe' ),
		];
		$args   = [
			'labels'        => $labels,
			'public'        => true,
			'has_archive'   => true,
			'rewrite'       => [ 'slug' => 'services' ],
			'supports'      => [ 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ],
			'show_in_rest'  => true,
			'menu_icon'     => 'dashicons-admin-tools',
		];
		register_post_type( 'service', $args );
	}

	private function register_project_post_type() {
		$labels = [
			'name'          => _x( 'Projects', 'Post type general name', 'aqualuxe' ),
			'singular_name' => _x( 'Project', 'Post type singular name', 'aqualuxe' ),
			'menu_name'     => _x( 'Projects', 'Admin Menu text', 'aqualuxe' ),
			'add_new_item'  => __( 'Add New Project', 'aqualuxe' ),
			'edit_item'     => __( 'Edit Project', 'aqualuxe' ),
			'view_item'     => __( 'View Project', 'aqualuxe' ),
			'all_items'     => __( 'All Projects', 'aqualuxe' ),
		];
		$args   = [
			'labels'        => $labels,
			'public'        => true,
			'has_archive'   => true,
			'rewrite'       => [ 'slug' => 'projects' ],
			'supports'      => [ 'title', 'editor', 'thumbnail', 'excerpt' ],
			'show_in_rest'  => true,
			'menu_icon'     => 'dashicons-portfolio',
		];
		register_post_type( 'project', $args );
	}

	private function register_event_post_type() {
		$labels = [
			'name'          => _x( 'Events', 'Post type general name', 'aqualuxe' ),
			'singular_name' => _x( 'Event', 'Post type singular name', 'aqualuxe' ),
			'menu_name'     => _x( 'Events', 'Admin Menu text', 'aqualuxe' ),
			'add_new_item'  => __( 'Add New Event', 'aqualuxe' ),
			'edit_item'     => __( 'Edit Event', 'aqualuxe' ),
			'view_item'     => __( 'View Event', 'aqualuxe' ),
			'all_items'     => __( 'All Events', 'aqualuxe' ),
		];
		$args   = [
			'labels'        => $labels,
			'public'        => true,
			'has_archive'   => true,
			'rewrite'       => [ 'slug' => 'events' ],
			'supports'      => [ 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ],
			'show_in_rest'  => true,
			'menu_icon'     => 'dashicons-calendar-alt',
		];
		register_post_type( 'event', $args );
	}

	private function register_team_member_post_type() {
		$labels = [
			'name'          => _x( 'Team Members', 'Post type general name', 'aqualuxe' ),
			'singular_name' => _x( 'Team Member', 'Post type singular name', 'aqualuxe' ),
			'menu_name'     => _x( 'Team', 'Admin Menu text', 'aqualuxe' ),
			'add_new_item'  => __( 'Add New Team Member', 'aqualuxe' ),
			'edit_item'     => __( 'Edit Team Member', 'aqualuxe' ),
			'view_item'     => __( 'View Team Member', 'aqualuxe' ),
			'all_items'     => __( 'All Team Members', 'aqualuxe' ),
		];
		$args   = [
			'labels'        => $labels,
			'public'        => false,
			'show_ui'       => true,
			'supports'      => [ 'title', 'thumbnail', 'custom-fields' ],
			'show_in_rest'  => true,
			'menu_icon'     => 'dashicons-groups',
		];
		register_post_type( 'team_member', $args );
	}

	private function register_testimonial_post_type() {
		$labels = [
			'name'          => _x( 'Testimonials', 'Post type general name', 'aqualuxe' ),
			'singular_name' => _x( 'Testimonial', 'Post type singular name', 'aqualuxe' ),
			'menu_name'     => _x( 'Testimonials', 'Admin Menu text', 'aqualuxe' ),
			'add_new_item'  => __( 'Add New Testimonial', 'aqualuxe' ),
			'edit_item'     => __( 'Edit Testimonial', 'aqualuxe' ),
			'view_item'     => __( 'View Testimonial', 'aqualuxe' ),
			'all_items'     => __( 'All Testimonials', 'aqualuxe' ),
		];
		$args   = [
			'labels'        => $labels,
			'public'        => false,
			'show_ui'       => true,
			'supports'      => [ 'title', 'editor', 'thumbnail' ],
			'show_in_rest'  => true,
			'menu_icon'     => 'dashicons-format-quote',
		];
		register_post_type( 'testimonial', $args );
	}

	private function register_faq_post_type() {
		$labels = [
			'name'          => _x( 'FAQs', 'Post type general name', 'aqualuxe' ),
			'singular_name' => _x( 'FAQ', 'Post type singular name', 'aqualuxe' ),
			'menu_name'     => _x( 'FAQs', 'Admin Menu text', 'aqualuxe' ),
			'add_new_item'  => __( 'Add New FAQ', 'aqualuxe' ),
			'edit_item'     => __( 'Edit FAQ', 'aqualuxe' ),
			'view_item'     => __( 'View FAQ', 'aqualuxe' ),
			'all_items'     => __( 'All FAQs', 'aqualuxe' ),
		];
		$args   = [
			'labels'        => $labels,
			'public'        => false,
			'show_ui'       => true,
			'supports'      => [ 'title', 'editor' ],
			'show_in_rest'  => true,
			'menu_icon'     => 'dashicons-editor-help',
		];
		register_post_type( 'faq', $args );
	}

	private function register_service_category_taxonomy() {
		$labels = [
			'name'          => _x( 'Service Categories', 'Taxonomy General Name', 'aqualuxe' ),
			'singular_name' => _x( 'Service Category', 'Taxonomy Singular Name', 'aqualuxe' ),
			'menu_name'     => __( 'Service Categories', 'aqualuxe' ),
		];
		$args   = [
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => [ 'slug' => 'service-category' ],
			'show_in_rest'      => true,
		];
		register_taxonomy( 'service_category', [ 'service' ], $args );
	}

	private function register_project_category_taxonomy() {
		$labels = [
			'name'          => _x( 'Project Categories', 'Taxonomy General Name', 'aqualuxe' ),
			'singular_name' => _x( 'Project Category', 'Taxonomy Singular Name', 'aqualuxe' ),
			'menu_name'     => __( 'Project Categories', 'aqualuxe' ),
		];
		$args   = [
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => [ 'slug' => 'project-category' ],
			'show_in_rest'      => true,
		];
		register_taxonomy( 'project_category', [ 'project' ], $args );
	}

	private function register_event_category_taxonomy() {
		$labels = [
			'name'          => _x( 'Event Categories', 'Taxonomy General Name', 'aqualuxe' ),
			'singular_name' => _x( 'Event Category', 'Taxonomy Singular Name', 'aqualuxe' ),
			'menu_name'     => __( 'Event Categories', 'aqualuxe' ),
		];
		$args   = [
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => [ 'slug' => 'event-category' ],
			'show_in_rest'      => true,
		];
		register_taxonomy( 'event_category', [ 'event' ], $args );
	}
}
