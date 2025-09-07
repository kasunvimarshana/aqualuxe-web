<?php
/**
 * Register Project Category Taxonomy
 *
 * @package AquaLuxe
 */

function aqualuxe_register_project_category_taxonomy() {
    $labels = array(
        'name'              => _x( 'Project Categories', 'taxonomy general name', 'aqualuxe' ),
        'singular_name'     => _x( 'Project Category', 'taxonomy singular name', 'aqualuxe' ),
        'search_items'      => __( 'Search Project Categories', 'aqualuxe' ),
        'all_items'         => __( 'All Project Categories', 'aqualuxe' ),
        'parent_item'       => __( 'Parent Project Category', 'aqualuxe' ),
        'parent_item_colon' => __( 'Parent Project Category:', 'aqualuxe' ),
        'edit_item'         => __( 'Edit Project Category', 'aqualuxe' ),
        'update_item'       => __( 'Update Project Category', 'aqualuxe' ),
        'add_new_item'      => __( 'Add New Project Category', 'aqualuxe' ),
        'new_item_name'     => __( 'New Project Category Name', 'aqualuxe' ),
        'menu_name'         => __( 'Project Category', 'aqualuxe' ),
    );
    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'project-category' ),
        'show_in_rest'      => true,
    );
    register_taxonomy( 'project_category', array( 'project' ), $args );
}
add_action( 'init', 'aqualuxe_register_project_category_taxonomy', 0 );
