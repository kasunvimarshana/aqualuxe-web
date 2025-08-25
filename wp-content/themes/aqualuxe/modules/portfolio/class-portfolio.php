<?php
/**
 * Portfolio Module
 * Handles custom post type, taxonomies, and templates for portfolio/gallery.
 */

namespace AquaLuxe\Modules\Portfolio;

if ( ! defined( 'ABSPATH' ) ) exit;

class Portfolio {
    public function __construct() {
        add_action( 'init', [ $this, 'register_post_type' ] );
        add_action( 'init', [ $this, 'register_taxonomies' ] );
        // TODO: Add template hooks and gallery scripts
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
        ];
        $args = [
            'labels' => $labels,
            'public' => true,
            'has_archive' => true,
            'rewrite' => [ 'slug' => 'portfolio' ],
            'supports' => [ 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ],
            'show_in_rest' => true,
        ];
    register_post_type( 'aqlx_portfolio', $args );
    }

    public function register_taxonomies() {
        // Categories
        register_taxonomy(
            'portfolio_category',
            'aqlx_portfolio',
            [
                'label' => __( 'Portfolio Categories', 'aqualuxe' ),
                'hierarchical' => true,
                'show_in_rest' => true,
            ]
        );
        // Tags
        register_taxonomy(
            'portfolio_tag',
            'aqlx_portfolio',
            [
                'label' => __( 'Portfolio Tags', 'aqualuxe' ),
                'hierarchical' => false,
                'show_in_rest' => true,
            ]
        );
    }
}

// Initialize the module
new Portfolio();
