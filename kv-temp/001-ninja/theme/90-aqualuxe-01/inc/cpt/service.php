<?php
/**
 * Register Service CPT
 *
 * @package AquaLuxe
 */

function aqualuxe_register_service_cpt() {
    $labels = array(
        'name'                  => _x( 'Services', 'Post Type General Name', 'aqualuxe' ),
        'singular_name'         => _x( 'Service', 'Post Type Singular Name', 'aqualuxe' ),
        'menu_name'             => __( 'Services', 'aqualuxe' ),
        'name_admin_bar'        => __( 'Service', 'aqualuxe' ),
        'archives'              => __( 'Service Archives', 'aqualuxe' ),
        'attributes'            => __( 'Service Attributes', 'aqualuxe' ),
        'parent_item_colon'     => __( 'Parent Service:', 'aqualuxe' ),
        'all_items'             => __( 'All Services', 'aqualuxe' ),
        'add_new_item'          => __( 'Add New Service', 'aqualuxe' ),
        'add_new'               => __( 'Add New', 'aqualuxe' ),
        'new_item'              => __( 'New Service', 'aqualuxe' ),
        'edit_item'             => __( 'Edit Service', 'aqualuxe' ),
        'update_item'           => __( 'Update Service', 'aqualuxe' ),
        'view_item'             => __( 'View Service', 'aqualuxe' ),
        'view_items'            => __( 'View Services', 'aqualuxe' ),
        'search_items'          => __( 'Search Service', 'aqualuxe' ),
        'not_found'             => __( 'Not found', 'aqualuxe' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'aqualuxe' ),
        'featured_image'        => __( 'Featured Image', 'aqualuxe' ),
        'set_featured_image'    => __( 'Set featured image', 'aqualuxe' ),
        'remove_featured_image' => __( 'Remove featured image', 'aqualuxe' ),
        'use_featured_image'    => __( 'Use as featured image', 'aqualuxe' ),
        'insert_into_item'      => __( 'Insert into service', 'aqualuxe' ),
        'uploaded_to_this_item' => __( 'Uploaded to this service', 'aqualuxe' ),
        'items_list'            => __( 'Services list', 'aqualuxe' ),
        'items_list_navigation' => __( 'Services list navigation', 'aqualuxe' ),
        'filter_items_list'     => __( 'Filter services list', 'aqualuxe' ),
    );
    $args = array(
        'label'                 => __( 'Service', 'aqualuxe' ),
        'description'           => __( 'Post Type for Services', 'aqualuxe' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments', 'revisions', 'custom-fields' ),
        'taxonomies'            => array( 'service_category', 'post_tag' ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-admin-generic',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'page',
        'show_in_rest'          => true,
    );
    register_post_type( 'service', $args );
}
add_action( 'init', 'aqualuxe_register_service_cpt', 0 );
