<?php
/**
 * Register Job Type Taxonomy
 *
 * @package AquaLuxe
 */

function aqualuxe_register_job_type_taxonomy() {
    $labels = array(
        'name'              => _x( 'Job Types', 'taxonomy general name', 'aqualuxe' ),
        'singular_name'     => _x( 'Job Type', 'taxonomy singular name', 'aqualuxe' ),
        'search_items'      => __( 'Search Job Types', 'aqualuxe' ),
        'all_items'         => __( 'All Job Types', 'aqualuxe' ),
        'parent_item'       => __( 'Parent Job Type', 'aqualuxe' ),
        'parent_item_colon' => __( 'Parent Job Type:', 'aqualuxe' ),
        'edit_item'         => __( 'Edit Job Type', 'aqualuxe' ),
        'update_item'       => __( 'Update Job Type', 'aqualuxe' ),
        'add_new_item'      => __( 'Add New Job Type', 'aqualuxe' ),
        'new_item_name'     => __( 'New Job Type Name', 'aqualuxe' ),
        'menu_name'         => __( 'Job Type', 'aqualuxe' ),
    );
    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'job-type' ),
        'show_in_rest'      => true,
    );
    register_taxonomy( 'job_type', array( 'job_listing' ), $args );
}
add_action( 'init', 'aqualuxe_register_job_type_taxonomy', 0 );
