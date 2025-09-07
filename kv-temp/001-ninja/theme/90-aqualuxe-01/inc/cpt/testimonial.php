<?php
/**
 * Register Testimonial CPT
 *
 * @package AquaLuxe
 */

function aqualuxe_register_testimonial_cpt() {
    $labels = array(
        'name'                  => _x( 'Testimonials', 'Post Type General Name', 'aqualuxe' ),
        'singular_name'         => _x( 'Testimonial', 'Post Type Singular Name', 'aqualuxe' ),
        'menu_name'             => __( 'Testimonials', 'aqualuxe' ),
        'name_admin_bar'        => __( 'Testimonial', 'aqualuxe' ),
        'archives'              => __( 'Testimonial Archives', 'aqualuxe' ),
        'attributes'            => __( 'Testimonial Attributes', 'aqualuxe' ),
        'parent_item_colon'     => __( 'Parent Testimonial:', 'aqualuxe' ),
        'all_items'             => __( 'All Testimonials', 'aqualuxe' ),
        'add_new_item'          => __( 'Add New Testimonial', 'aqualuxe' ),
        'add_new'               => __( 'Add New', 'aqualuxe' ),
        'new_item'              => __( 'New Testimonial', 'aqualuxe' ),
        'edit_item'             => __( 'Edit Testimonial', 'aqualuxe' ),
        'update_item'           => __( 'Update Testimonial', 'aqualuxe' ),
        'view_item'             => __( 'View Testimonial', 'aqualuxe' ),
        'view_items'            => __( 'View Testimonials', 'aqualuxe' ),
        'search_items'          => __( 'Search Testimonial', 'aqualuxe' ),
        'not_found'             => __( 'Not found', 'aqualuxe' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'aqualuxe' ),
        'featured_image'        => __( 'Featured Image', 'aqualuxe' ),
        'set_featured_image'    => __( 'Set featured image', 'aqualuxe' ),
        'remove_featured_image' => __( 'Remove featured image', 'aqualuxe' ),
        'use_featured_image'    => __( 'Use as featured image', 'aqualuxe' ),
        'insert_into_item'      => __( 'Insert into testimonial', 'aqualuxe' ),
        'uploaded_to_this_item' => __( 'Uploaded to this testimonial', 'aqualuxe' ),
        'items_list'            => __( 'Testimonials list', 'aqualuxe' ),
        'items_list_navigation' => __( 'Testimonials list navigation', 'aqualuxe' ),
        'filter_items_list'     => __( 'Filter testimonials list', 'aqualuxe' ),
    );
    $args = array(
        'label'                 => __( 'Testimonial', 'aqualuxe' ),
        'description'           => __( 'Post Type for Testimonials', 'aqualuxe' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'thumbnail', 'revisions', 'custom-fields' ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-format-quote',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'page',
        'show_in_rest'          => true,
    );
    register_post_type( 'testimonial', $args );
}
add_action( 'init', 'aqualuxe_register_testimonial_cpt', 0 );
