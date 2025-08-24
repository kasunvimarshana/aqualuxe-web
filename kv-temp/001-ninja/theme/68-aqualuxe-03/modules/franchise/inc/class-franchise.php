<?php
/**
 * Franchise/Licensing Module Main Class
 */
if ( ! defined( 'ABSPATH' ) ) exit;

class AquaLuxe_Franchise_Module {
    public function __construct() {
        add_action( 'init', [ $this, 'register_post_type' ] );
        add_action( 'init', [ $this, 'register_location_fields' ] );
    }

    public function register_post_type() {
        $labels = [
            'name' => __( 'Franchise Locations', 'aqualuxe' ),
            'singular_name' => __( 'Franchise Location', 'aqualuxe' ),
            'add_new' => __( 'Add New', 'aqualuxe' ),
            'add_new_item' => __( 'Add New Franchise Location', 'aqualuxe' ),
            'edit_item' => __( 'Edit Franchise Location', 'aqualuxe' ),
            'new_item' => __( 'New Franchise Location', 'aqualuxe' ),
            'view_item' => __( 'View Franchise Location', 'aqualuxe' ),
            'search_items' => __( 'Search Franchise Locations', 'aqualuxe' ),
            'not_found' => __( 'No franchise locations found', 'aqualuxe' ),
            'not_found_in_trash' => __( 'No franchise locations found in Trash', 'aqualuxe' ),
            'menu_name' => __( 'Franchise', 'aqualuxe' ),
        ];
        $args = [
            'labels' => $labels,
            'public' => true,
            'has_archive' => true,
            'rewrite' => [ 'slug' => 'franchise-locations' ],
            'supports' => [ 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ],
            'show_in_rest' => true,
            'menu_icon' => 'dashicons-location-alt',
        ];
        register_post_type( 'aqualuxe_franchise_location', $args );
    }

    public function register_location_fields() {
        // Placeholder for custom fields (e.g., map, application form, territory management)
    }
}
