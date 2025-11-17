<?php
/**
 * Register Location CPT
 *
 * @package AquaLuxe
 */

function aqualuxe_register_location_cpt() {
    $labels = array(
        'name'                  => _x( 'Locations', 'Post Type General Name', 'aqualuxe' ),
        'singular_name'         => _x( 'Location', 'Post Type Singular Name', 'aqualuxe' ),
        'menu_name'             => __( 'Locations', 'aqualuxe' ),
        'name_admin_bar'        => __( 'Location', 'aqualuxe' ),
        'archives'              => __( 'Location Archives', 'aqualuxe' ),
        'attributes'            => __( 'Location Attributes', 'aqualuxe' ),
        'parent_item_colon'     => __( 'Parent Location:', 'aqualuxe' ),
        'all_items'             => __( 'All Locations', 'aqualuxe' ),
        'add_new_item'          => __( 'Add New Location', 'aqualuxe' ),
        'add_new'               => __( 'Add New', 'aqualuxe' ),
        'new_item'              => __( 'New Location', 'aqualuxe' ),
        'edit_item'             => __( 'Edit Location', 'aqualuxe' ),
        'update_item'           => __( 'Update Location', 'aqualuxe' ),
        'view_item'             => __( 'View Location', 'aqualuxe' ),
        'view_items'            => __( 'View Locations', 'aqualuxe' ),
        'search_items'          => __( 'Search Location', 'aqualuxe' ),
        'not_found'             => __( 'Not found', 'aqualuxe' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'aqualuxe' ),
        'featured_image'        => __( 'Featured Image', 'aqualuxe' ),
        'set_featured_image'    => __( 'Set featured image', 'aqualuxe' ),
        'remove_featured_image' => __( 'Remove featured image', 'aqualuxe' ),
        'use_featured_image'    => __( 'Use as featured image', 'aqualuxe' ),
        'insert_into_item'      => __( 'Insert into location', 'aqualuxe' ),
        'uploaded_to_this_item' => __( 'Uploaded to this location', 'aqualuxe' ),
        'items_list'            => __( 'Locations list', 'aqualuxe' ),
        'items_list_navigation' => __( 'Locations list navigation', 'aqualuxe' ),
        'filter_items_list'     => __( 'Filter locations list', 'aqualuxe' ),
    );
    $args = array(
        'label'                 => __( 'Location', 'aqualuxe' ),
        'description'           => __( 'Post Type for Locations', 'aqualuxe' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'thumbnail', 'revisions', 'custom-fields' ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-location-alt',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'page',
        'show_in_rest'          => true,
    );
    register_post_type( 'location', $args );
}
add_action( 'init', 'aqualuxe_register_location_cpt', 0 );
