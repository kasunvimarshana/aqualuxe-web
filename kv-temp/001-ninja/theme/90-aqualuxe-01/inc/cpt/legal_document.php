<?php
/**
 * Register Legal Document CPT
 *
 * @package AquaLuxe
 */

function aqualuxe_register_legal_document_cpt() {
    $labels = array(
        'name'                  => _x( 'Legal Documents', 'Post Type General Name', 'aqualuxe' ),
        'singular_name'         => _x( 'Legal Document', 'Post Type Singular Name', 'aqualuxe' ),
        'menu_name'             => __( 'Legal Documents', 'aqualuxe' ),
        'name_admin_bar'        => __( 'Legal Document', 'aqualuxe' ),
        'archives'              => __( 'Legal Document Archives', 'aqualuxe' ),
        'attributes'            => __( 'Legal Document Attributes', 'aqualuxe' ),
        'parent_item_colon'     => __( 'Parent Legal Document:', 'aqualuxe' ),
        'all_items'             => __( 'All Legal Documents', 'aqualuxe' ),
        'add_new_item'          => __( 'Add New Legal Document', 'aqualuxe' ),
        'add_new'               => __( 'Add New', 'aqualuxe' ),
        'new_item'              => __( 'New Legal Document', 'aqualuxe' ),
        'edit_item'             => __( 'Edit Legal Document', 'aqualuxe' ),
        'update_item'           => __( 'Update Legal Document', 'aqualuxe' ),
        'view_item'             => __( 'View Legal Document', 'aqualuxe' ),
        'view_items'            => __( 'View Legal Documents', 'aqualuxe' ),
        'search_items'          => __( 'Search Legal Document', 'aqualuxe' ),
        'not_found'             => __( 'Not found', 'aqualuxe' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'aqualuxe' ),
        'featured_image'        => __( 'Featured Image', 'aqualuxe' ),
        'set_featured_image'    => __( 'Set featured image', 'aqualuxe' ),
        'remove_featured_image' => __( 'Remove featured image', 'aqualuxe' ),
        'use_featured_image'    => __( 'Use as featured image', 'aqualuxe' ),
        'insert_into_item'      => __( 'Insert into legal document', 'aqualuxe' ),
        'uploaded_to_this_item' => __( 'Uploaded to this legal document', 'aqualuxe' ),
        'items_list'            => __( 'Legal Documents list', 'aqualuxe' ),
        'items_list_navigation' => __( 'Legal Documents list navigation', 'aqualuxe' ),
        'filter_items_list'     => __( 'Filter legal documents list', 'aqualuxe' ),
    );
    $args = array(
        'label'                 => __( 'Legal Document', 'aqualuxe' ),
        'description'           => __( 'Post Type for Legal Documents', 'aqualuxe' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'revisions' ),
        'hierarchical'          => true,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 20,
        'menu_icon'             => 'dashicons-shield',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => false,
        'exclude_from_search'   => true,
        'publicly_queryable'    => true,
        'capability_type'       => 'page',
        'show_in_rest'          => true,
    );
    register_post_type( 'legal_document', $args );
}
add_action( 'init', 'aqualuxe_register_legal_document_cpt', 0 );
