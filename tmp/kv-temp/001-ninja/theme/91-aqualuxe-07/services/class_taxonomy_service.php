<?php
/**
 * AquaLuxe Taxonomy Service
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
 * Class Taxonomy_Service
 *
 * Handles custom taxonomy registration and management
 */
class Taxonomy_Service {

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
	 * Register all custom taxonomies
	 *
	 * @return void
	 */
	public function register_taxonomies(): void {
		$taxonomies = $this->config['taxonomies'] ?? [];

		foreach ( $taxonomies as $taxonomy => $config ) {
			$object_type = $config['object_type'] ?? 'post';
			$args = $config['args'] ?? [];
			
			register_taxonomy( $taxonomy, $object_type, $args );
		}
	}

	/**
	 * Get default configuration
	 *
	 * @return array
	 */
	protected function get_default_config(): array {
		return [
			'taxonomies' => [
				'portfolio_category' => [
					'object_type' => 'portfolio',
					'args' => [
						'labels' => [
							'name' => __( 'Portfolio Categories', 'aqualuxe' ),
							'singular_name' => __( 'Portfolio Category', 'aqualuxe' ),
							'search_items' => __( 'Search Portfolio Categories', 'aqualuxe' ),
							'all_items' => __( 'All Portfolio Categories', 'aqualuxe' ),
							'parent_item' => __( 'Parent Portfolio Category', 'aqualuxe' ),
							'parent_item_colon' => __( 'Parent Portfolio Category:', 'aqualuxe' ),
							'edit_item' => __( 'Edit Portfolio Category', 'aqualuxe' ),
							'update_item' => __( 'Update Portfolio Category', 'aqualuxe' ),
							'add_new_item' => __( 'Add New Portfolio Category', 'aqualuxe' ),
							'new_item_name' => __( 'New Portfolio Category Name', 'aqualuxe' ),
							'menu_name' => __( 'Portfolio Categories', 'aqualuxe' ),
						],
						'hierarchical' => true,
						'public' => true,
						'publicly_queryable' => true,
						'show_ui' => true,
						'show_admin_column' => true,
						'show_in_nav_menus' => true,
						'show_tagcloud' => true,
						'show_in_rest' => true,
						'rewrite' => [ 'slug' => 'portfolio-category' ],
					],
				],
				'portfolio_tag' => [
					'object_type' => 'portfolio',
					'args' => [
						'labels' => [
							'name' => __( 'Portfolio Tags', 'aqualuxe' ),
							'singular_name' => __( 'Portfolio Tag', 'aqualuxe' ),
							'search_items' => __( 'Search Portfolio Tags', 'aqualuxe' ),
							'popular_items' => __( 'Popular Portfolio Tags', 'aqualuxe' ),
							'all_items' => __( 'All Portfolio Tags', 'aqualuxe' ),
							'edit_item' => __( 'Edit Portfolio Tag', 'aqualuxe' ),
							'update_item' => __( 'Update Portfolio Tag', 'aqualuxe' ),
							'add_new_item' => __( 'Add New Portfolio Tag', 'aqualuxe' ),
							'new_item_name' => __( 'New Portfolio Tag Name', 'aqualuxe' ),
							'separate_items_with_commas' => __( 'Separate portfolio tags with commas', 'aqualuxe' ),
							'add_or_remove_items' => __( 'Add or remove portfolio tags', 'aqualuxe' ),
							'choose_from_most_used' => __( 'Choose from the most used portfolio tags', 'aqualuxe' ),
							'menu_name' => __( 'Portfolio Tags', 'aqualuxe' ),
						],
						'hierarchical' => false,
						'public' => true,
						'publicly_queryable' => true,
						'show_ui' => true,
						'show_admin_column' => true,
						'show_in_nav_menus' => true,
						'show_tagcloud' => true,
						'show_in_rest' => true,
						'rewrite' => [ 'slug' => 'portfolio-tag' ],
					],
				],
				'team_department' => [
					'object_type' => 'team',
					'args' => [
						'labels' => [
							'name' => __( 'Departments', 'aqualuxe' ),
							'singular_name' => __( 'Department', 'aqualuxe' ),
							'search_items' => __( 'Search Departments', 'aqualuxe' ),
							'all_items' => __( 'All Departments', 'aqualuxe' ),
							'parent_item' => __( 'Parent Department', 'aqualuxe' ),
							'parent_item_colon' => __( 'Parent Department:', 'aqualuxe' ),
							'edit_item' => __( 'Edit Department', 'aqualuxe' ),
							'update_item' => __( 'Update Department', 'aqualuxe' ),
							'add_new_item' => __( 'Add New Department', 'aqualuxe' ),
							'new_item_name' => __( 'New Department Name', 'aqualuxe' ),
							'menu_name' => __( 'Departments', 'aqualuxe' ),
						],
						'hierarchical' => true,
						'public' => true,
						'publicly_queryable' => true,
						'show_ui' => true,
						'show_admin_column' => true,
						'show_in_nav_menus' => true,
						'show_tagcloud' => false,
						'show_in_rest' => true,
						'rewrite' => [ 'slug' => 'department' ],
					],
				],
			],
		];
	}
}
