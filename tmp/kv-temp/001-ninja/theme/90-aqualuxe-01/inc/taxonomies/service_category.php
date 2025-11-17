<?php
/**
 * Register Service Category Taxonomy
 *
 * @package AquaLuxe
 */

function aqualuxe_register_service_category_taxonomy() {
    $labels = array(
        'name'              => _x( 'Service Categories', 'taxonomy general name', 'aqualuxe' ),
        'singular_name'     => _x( 'Service Category', 'taxonomy singular name', 'aqualuxe' ),
        'search_items'      => __( 'Search Service Categories', 'aqualuxe' ),
        'all_items'         => __( 'All Service Categories', 'aqualuxe' ),
        'parent_item'       => __( 'Parent Service Category', 'aqualuxe' ),
        'parent_item_colon' => __( 'Parent Service Category:', 'aqualuxe' ),
        'edit_item'         => __( 'Edit Service Category', 'aqualuxe' ),
        'update_item'       => __( 'Update Service Category', 'aqualuxe' ),
        'add_new_item'      => __( 'Add New Service Category', 'aqualuxe' ),
        'new_item_name'     => __( 'New Service Category Name', 'aqualuxe' ),
        'menu_name'         => __( 'Service Category', 'aqualuxe' ),
    );
    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'service-category' ),
        'show_in_rest'      => true,
    );
    register_taxonomy( 'service_category', array( 'service' ), $args );
}
add_action( 'init', 'aqualuxe_register_service_category_taxonomy', 0 );
