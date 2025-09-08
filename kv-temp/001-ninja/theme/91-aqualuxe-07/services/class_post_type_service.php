<?php
/**
 * AquaLuxe Post Type Service
 *
 * @package AquaLuxe
 * @since   1.2.0
 */

namespace AquaLuxe\Services;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Post_Type_Service
 *
 * Handles custom post type registration and management
 */
class Post_Type_Service {

	/**
	 * Configuration
	 *
	 * @var array
	 */
	protected $config;

	/**
	 * Constructor
	 *
	 * @param array $config Configuration
	 */
	public function __construct( array $config = [] ) {
		$this->config = array_merge( $this->get_default_config(), $config );
	}

	/**
	 * Register all custom post types
	 *
	 * @return void
	 */
	public function register_post_types(): void {
		$post_types = $this->config['post_types'] ?? [];

		foreach ( $post_types as $post_type => $args ) {
			register_post_type( $post_type, $args );
		}
	}

	/**
	 * Get default configuration
	 *
	 * @return array
	 */
	protected function get_default_config(): array {
		return [
			'post_types' => [
				'portfolio' => [
					'labels' => [
						'name' => __( 'Portfolio', 'aqualuxe' ),
						'singular_name' => __( 'Portfolio Item', 'aqualuxe' ),
						'menu_name' => __( 'Portfolio', 'aqualuxe' ),
						'add_new' => __( 'Add New', 'aqualuxe' ),
						'add_new_item' => __( 'Add New Portfolio Item', 'aqualuxe' ),
						'edit_item' => __( 'Edit Portfolio Item', 'aqualuxe' ),
						'new_item' => __( 'New Portfolio Item', 'aqualuxe' ),
						'view_item' => __( 'View Portfolio Item', 'aqualuxe' ),
						'search_items' => __( 'Search Portfolio', 'aqualuxe' ),
						'not_found' => __( 'No portfolio items found', 'aqualuxe' ),
						'not_found_in_trash' => __( 'No portfolio items found in trash', 'aqualuxe' ),
					],
					'public' => true,
					'publicly_queryable' => true,
					'show_ui' => true,
					'show_in_menu' => true,
					'show_in_rest' => true,
					'query_var' => true,
					'rewrite' => [ 'slug' => 'portfolio' ],
					'capability_type' => 'post',
					'has_archive' => true,
					'hierarchical' => false,
					'menu_position' => 20,
					'menu_icon' => 'dashicons-portfolio',
					'supports' => [ 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ],
				],
				'testimonial' => [
					'labels' => [
						'name' => __( 'Testimonials', 'aqualuxe' ),
						'singular_name' => __( 'Testimonial', 'aqualuxe' ),
						'menu_name' => __( 'Testimonials', 'aqualuxe' ),
						'add_new' => __( 'Add New', 'aqualuxe' ),
						'add_new_item' => __( 'Add New Testimonial', 'aqualuxe' ),
						'edit_item' => __( 'Edit Testimonial', 'aqualuxe' ),
						'new_item' => __( 'New Testimonial', 'aqualuxe' ),
						'view_item' => __( 'View Testimonial', 'aqualuxe' ),
						'search_items' => __( 'Search Testimonials', 'aqualuxe' ),
						'not_found' => __( 'No testimonials found', 'aqualuxe' ),
						'not_found_in_trash' => __( 'No testimonials found in trash', 'aqualuxe' ),
					],
					'public' => false,
					'publicly_queryable' => false,
					'show_ui' => true,
					'show_in_menu' => true,
					'show_in_rest' => true,
					'query_var' => true,
					'rewrite' => false,
					'capability_type' => 'post',
					'has_archive' => false,
					'hierarchical' => false,
					'menu_position' => 21,
					'menu_icon' => 'dashicons-testimonial',
					'supports' => [ 'title', 'editor', 'thumbnail' ],
				],
				'team' => [
					'labels' => [
						'name' => __( 'Team Members', 'aqualuxe' ),
						'singular_name' => __( 'Team Member', 'aqualuxe' ),
						'menu_name' => __( 'Team', 'aqualuxe' ),
						'add_new' => __( 'Add New', 'aqualuxe' ),
						'add_new_item' => __( 'Add New Team Member', 'aqualuxe' ),
						'edit_item' => __( 'Edit Team Member', 'aqualuxe' ),
						'new_item' => __( 'New Team Member', 'aqualuxe' ),
						'view_item' => __( 'View Team Member', 'aqualuxe' ),
						'search_items' => __( 'Search Team', 'aqualuxe' ),
						'not_found' => __( 'No team members found', 'aqualuxe' ),
						'not_found_in_trash' => __( 'No team members found in trash', 'aqualuxe' ),
					],
					'public' => true,
					'publicly_queryable' => true,
					'show_ui' => true,
					'show_in_menu' => true,
					'show_in_rest' => true,
					'query_var' => true,
					'rewrite' => [ 'slug' => 'team' ],
					'capability_type' => 'post',
					'has_archive' => true,
					'hierarchical' => false,
					'menu_position' => 22,
					'menu_icon' => 'dashicons-groups',
					'supports' => [ 'title', 'editor', 'thumbnail', 'excerpt' ],
				],
			],
		];
	}
}
