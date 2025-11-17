<?php
/**
 * Register FAQ Category Taxonomy
 *
 * @package AquaLuxe
 */

function aqualuxe_register_faq_category_taxonomy() {
    $labels = array(
        'name'              => _x( 'FAQ Categories', 'taxonomy general name', 'aqualuxe' ),
        'singular_name'     => _x( 'FAQ Category', 'taxonomy singular name', 'aqualuxe' ),
        'search_items'      => __( 'Search FAQ Categories', 'aqualuxe' ),
        'all_items'         => __( 'All FAQ Categories', 'aqualuxe' ),
        'parent_item'       => __( 'Parent FAQ Category', 'aqualuxe' ),
        'parent_item_colon' => __( 'Parent FAQ Category:', 'aqualuxe' ),
        'edit_item'         => __( 'Edit FAQ Category', 'aqualuxe' ),
        'update_item'       => __( 'Update FAQ Category', 'aqualuxe' ),
        'add_new_item'      => __( 'Add New FAQ Category', 'aqualuxe' ),
        'new_item_name'     => __( 'New FAQ Category Name', 'aqualuxe' ),
        'menu_name'         => __( 'FAQ Category', 'aqualuxe' ),
    );
    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'faq-category' ),
        'show_in_rest'      => true,
    );
    register_taxonomy( 'faq_category', array( 'faq' ), $args );
}
add_action( 'init', 'aqualuxe_register_faq_category_taxonomy', 0 );
