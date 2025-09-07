<?php
/**
 * Register Job Category Taxonomy
 *
 * @package AquaLuxe
 */

function aqualuxe_register_job_category_taxonomy() {
    $labels = array(
        'name'              => _x( 'Job Categories', 'taxonomy general name', 'aqualuxe' ),
        'singular_name'     => _x( 'Job Category', 'taxonomy singular name', 'aqualuxe' ),
        'search_items'      => __( 'Search Job Categories', 'aqualuxe' ),
        'all_items'         => __( 'All Job Categories', 'aqualuxe' ),
        'parent_item'       => __( 'Parent Job Category', 'aqualuxe' ),
        'parent_item_colon' => __( 'Parent Job Category:', 'aqualuxe' ),
        'edit_item'         => __( 'Edit Job Category', 'aqualuxe' ),
        'update_item'       => __( 'Update Job Category', 'aqualuxe' ),
        'add_new_item'      => __( 'Add New Job Category', 'aqualuxe' ),
        'new_item_name'     => __( 'New Job Category Name', 'aqualuxe' ),
        'menu_name'         => __( 'Job Category', 'aqualuxe' ),
    );
    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'job-category' ),
        'show_in_rest'      => true,
    );
    register_taxonomy( 'job_category', array( 'job_listing' ), $args );
}
add_action( 'init', 'aqualuxe_register_job_category_taxonomy', 0 );
