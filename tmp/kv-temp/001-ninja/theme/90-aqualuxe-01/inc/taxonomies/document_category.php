<?php
/**
 * Register Document Category Taxonomy
 *
 * @package AquaLuxe
 */

function aqualuxe_register_document_category_taxonomy() {
    $labels = array(
        'name'              => _x( 'Document Categories', 'taxonomy general name', 'aqualuxe' ),
        'singular_name'     => _x( 'Document Category', 'taxonomy singular name', 'aqualuxe' ),
        'search_items'      => __( 'Search Document Categories', 'aqualuxe' ),
        'all_items'         => __( 'All Document Categories', 'aqualuxe' ),
        'parent_item'       => __( 'Parent Document Category', 'aqualuxe' ),
        'parent_item_colon' => __( 'Parent Document Category:', 'aqualuxe' ),
        'edit_item'         => __( 'Edit Document Category', 'aqualuxe' ),
        'update_item'       => __( 'Update Document Category', 'aqualuxe' ),
        'add_new_item'      => __( 'Add New Document Category', 'aqualuxe' ),
        'new_item_name'     => __( 'New Document Category Name', 'aqualuxe' ),
        'menu_name'         => __( 'Document Category', 'aqualuxe' ),
    );
    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'document-category' ),
        'show_in_rest'      => true,
    );
    register_taxonomy( 'document_category', array( 'document' ), $args );
}
add_action( 'init', 'aqualuxe_register_document_category_taxonomy', 0 );
