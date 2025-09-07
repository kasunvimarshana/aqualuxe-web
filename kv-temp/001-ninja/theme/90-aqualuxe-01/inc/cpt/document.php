<?php
/**
 * Register Document CPT
 *
 * @package AquaLuxe
 */

function aqualuxe_register_document_cpt() {
    $labels = array(
        'name'                  => _x( 'Documents', 'Post Type General Name', 'aqualuxe' ),
        'singular_name'         => _x( 'Document', 'Post Type Singular Name', 'aqualuxe' ),
        'menu_name'             => __( 'Documents', 'aqualuxe' ),
        'name_admin_bar'        => __( 'Document', 'aqualuxe' ),
        'archives'              => __( 'Document Archives', 'aqualuxe' ),
        'attributes'            => __( 'Document Attributes', 'aqualuxe' ),
        'parent_item_colon'     => __( 'Parent Document:', 'aqualuxe' ),
        'all_items'             => __( 'All Documents', 'aqualuxe' ),
        'add_new_item'          => __( 'Add New Document', 'aqualuxe' ),
        'add_new'               => __( 'Add New', 'aqualuxe' ),
        'new_item'              => __( 'New Document', 'aqualuxe' ),
        'edit_item'             => __( 'Edit Document', 'aqualuxe' ),
        'update_item'           => __( 'Update Document', 'aqualuxe' ),
        'view_item'             => __( 'View Document', 'aqualuxe' ),
        'view_items'            => __( 'View Documents', 'aqualuxe' ),
        'search_items'          => __( 'Search Document', 'aqualuxe' ),
        'not_found'             => __( 'Not found', 'aqualuxe' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'aqualuxe' ),
        'featured_image'        => __( 'Featured Image', 'aqualuxe' ),
        'set_featured_image'    => __( 'Set featured image', 'aqualuxe' ),
        'remove_featured_image' => __( 'Remove featured image', 'aqualuxe' ),
        'use_featured_image'    => __( 'Use as featured image', 'aqualuxe' ),
        'insert_into_item'      => __( 'Insert into document', 'aqualuxe' ),
        'uploaded_to_this_item' => __( 'Uploaded to this document', 'aqualuxe' ),
        'items_list'            => __( 'Documents list', 'aqualuxe' ),
        'items_list_navigation' => __( 'Documents list navigation', 'aqualuxe' ),
        'filter_items_list'     => __( 'Filter documents list', 'aqualuxe' ),
    );
    $args = array(
        'label'                 => __( 'Document', 'aqualuxe' ),
        'description'           => __( 'Post Type for Documents', 'aqualuxe' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'revisions', 'custom-fields' ),
        'taxonomies'            => array( 'document_category' ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-media-document',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'page',
        'show_in_rest'          => true,
    );
    register_post_type( 'document', $args );
}
add_action( 'init', 'aqualuxe_register_document_cpt', 0 );
