<?php
/**
 * Register Portfolio Category Taxonomy
 *
 * @package AquaLuxe
 */

function aqualuxe_register_portfolio_category_taxonomy() {
    $labels = array(
        'name'              => _x( 'Portfolio Categories', 'taxonomy general name', 'aqualuxe' ),
        'singular_name'     => _x( 'Portfolio Category', 'taxonomy singular name', 'aqualuxe' ),
        'search_items'      => __( 'Search Portfolio Categories', 'aqualuxe' ),
        'all_items'         => __( 'All Portfolio Categories', 'aqualuxe' ),
        'parent_item'       => __( 'Parent Portfolio Category', 'aqualuxe' ),
        'parent_item_colon' => __( 'Parent Portfolio Category:', 'aqualuxe' ),
        'edit_item'         => __( 'Edit Portfolio Category', 'aqualuxe' ),
        'update_item'       => __( 'Update Portfolio Category', 'aqualuxe' ),
        'add_new_item'      => __( 'Add New Portfolio Category', 'aqualuxe' ),
        'new_item_name'     => __( 'New Portfolio Category Name', 'aqualuxe' ),
        'menu_name'         => __( 'Portfolio Category', 'aqualuxe' ),
    );
    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'portfolio-category' ),
        'show_in_rest'      => true,
    );
    register_taxonomy( 'portfolio_category', array( 'portfolio' ), $args );
}
add_action( 'init', 'aqualuxe_register_portfolio_category_taxonomy', 0 );
