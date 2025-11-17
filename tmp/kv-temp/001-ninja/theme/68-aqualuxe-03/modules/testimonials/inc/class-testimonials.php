<?php
/**
 * Testimonials Module Main Class
 */
if ( ! defined( 'ABSPATH' ) ) exit;

class AquaLuxe_Testimonials_Module {
    public function __construct() {
        add_action( 'init', [ $this, 'register_post_type' ] );
        add_action( 'init', [ $this, 'register_taxonomies' ] );
    }

    public function register_post_type() {
        $labels = [
            'name' => __( 'Testimonials', 'aqualuxe' ),
            'singular_name' => __( 'Testimonial', 'aqualuxe' ),
            'add_new' => __( 'Add New', 'aqualuxe' ),
            'add_new_item' => __( 'Add New Testimonial', 'aqualuxe' ),
            'edit_item' => __( 'Edit Testimonial', 'aqualuxe' ),
            'new_item' => __( 'New Testimonial', 'aqualuxe' ),
            'view_item' => __( 'View Testimonial', 'aqualuxe' ),
            'search_items' => __( 'Search Testimonials', 'aqualuxe' ),
            'not_found' => __( 'No testimonials found', 'aqualuxe' ),
            'not_found_in_trash' => __( 'No testimonials found in Trash', 'aqualuxe' ),
            'menu_name' => __( 'Testimonials', 'aqualuxe' ),
        ];
        $args = [
            'labels' => $labels,
            'public' => true,
            'has_archive' => true,
            'rewrite' => [ 'slug' => 'testimonials' ],
            'supports' => [ 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ],
            'show_in_rest' => true,
            'menu_icon' => 'dashicons-testimonial',
        ];
        register_post_type( 'aqualuxe_testimonial', $args );
    }

    public function register_taxonomies() {
        register_taxonomy(
            'testimonial_category',
            'aqualuxe_testimonial',
            [
                'label' => __( 'Categories', 'aqualuxe' ),
                'hierarchical' => true,
                'show_in_rest' => true,
            ]
        );
    }
}
