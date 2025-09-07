<?php
/**
 * Register Partner CPT
 *
 * @package AquaLuxe
 */

function aqualuxe_register_partner_cpt() {
    $labels = array(
        'name'                  => _x( 'Partners', 'Post Type General Name', 'aqualuxe' ),
        'singular_name'         => _x( 'Partner', 'Post Type Singular Name', 'aqualuxe' ),
        'menu_name'             => __( 'Partners', 'aqualuxe' ),
        'name_admin_bar'        => __( 'Partner', 'aqualuxe' ),
        'archives'              => __( 'Partner Archives', 'aqualuxe' ),
        'attributes'            => __( 'Partner Attributes', 'aqualuxe' ),
        'parent_item_colon'     => __( 'Parent Partner:', 'aqualuxe' ),
        'all_items'             => __( 'All Partners', 'aqualuxe' ),
        'add_new_item'          => __( 'Add New Partner', 'aqualuxe' ),
        'add_new'               => __( 'Add New', 'aqualuxe' ),
        'new_item'              => __( 'New Partner', 'aqualuxe' ),
        'edit_item'             => __( 'Edit Partner', 'aqualuxe' ),
        'update_item'           => __( 'Update Partner', 'aqualuxe' ),
        'view_item'             => __( 'View Partner', 'aqualuxe' ),
        'view_items'            => __( 'View Partners', 'aqualuxe' ),
        'search_items'          => __( 'Search Partner', 'aqualuxe' ),
        'not_found'             => __( 'Not found', 'aqualuxe' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'aqualuxe' ),
        'featured_image'        => __( 'Featured Image', 'aqualuxe' ),
        'set_featured_image'    => __( 'Set featured image', 'aqualuxe' ),
        'remove_featured_image' => __( 'Remove featured image', 'aqualuxe' ),
        'use_featured_image'    => __( 'Use as featured image', 'aqualuxe' ),
        'insert_into_item'      => __( 'Insert into partner', 'aqualuxe' ),
        'uploaded_to_this_item' => __( 'Uploaded to this partner', 'aqualuxe' ),
        'items_list'            => __( 'Partners list', 'aqualuxe' ),
        'items_list_navigation' => __( 'Partners list navigation', 'aqualuxe' ),
        'filter_items_list'     => __( 'Filter partners list', 'aqualuxe' ),
    );
    $args = array(
        'label'                 => __( 'Partner', 'aqualuxe' ),
        'description'           => __( 'Post Type for Partners', 'aqualuxe' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'thumbnail', 'revisions', 'custom-fields' ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-admin-users',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'page',
        'show_in_rest'          => true,
    );
    register_post_type( 'partner', $args );
}
add_action( 'init', 'aqualuxe_register_partner_cpt', 0 );
