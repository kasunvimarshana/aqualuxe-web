<?php
/**
 * Portfolio Module Main Class
 */
if ( ! defined( 'ABSPATH' ) ) exit;

class AquaLuxe_Portfolio_Module {
    public function __construct() {
        add_action( 'init', [ $this, 'register_post_type' ] );
        add_action( 'init', [ $this, 'register_taxonomies' ] );
    }

    public function register_post_type() {
        $labels = [
            'name' => __( 'Portfolio', 'aqualuxe' ),
            'singular_name' => __( 'Portfolio Item', 'aqualuxe' ),
            'add_new' => __( 'Add New', 'aqualuxe' ),
            'add_new_item' => __( 'Add New Portfolio Item', 'aqualuxe' ),
            'edit_item' => __( 'Edit Portfolio Item', 'aqualuxe' ),
            'new_item' => __( 'New Portfolio Item', 'aqualuxe' ),
            'view_item' => __( 'View Portfolio Item', 'aqualuxe' ),
            'search_items' => __( 'Search Portfolio', 'aqualuxe' ),
            'not_found' => __( 'No portfolio items found', 'aqualuxe' ),
            'not_found_in_trash' => __( 'No portfolio items found in Trash', 'aqualuxe' ),
            'menu_name' => __( 'Portfolio', 'aqualuxe' ),
        ];
        $args = [
            'labels' => $labels,
            'public' => true,
            'has_archive' => true,
            'rewrite' => [ 'slug' => 'portfolio' ],
            'supports' => [ 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ],
            'show_in_rest' => true,
            'menu_icon' => 'dashicons-format-gallery',
        ];
        register_post_type( 'aqualuxe_portfolio', $args );
    }

    public function register_taxonomies() {
        register_taxonomy(
            'portfolio_category',
            'aqualuxe_portfolio',
            [
                'label' => __( 'Categories', 'aqualuxe' ),
                'hierarchical' => true,
                'show_in_rest' => true,
            ]
        );
        register_taxonomy(
            'portfolio_tag',
            'aqualuxe_portfolio',
            [
                'label' => __( 'Tags', 'aqualuxe' ),
                'hierarchical' => false,
                'show_in_rest' => true,
            ]
        );
    }
}
