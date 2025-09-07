<?php
/**
 * Register Team Member CPT
 *
 * @package AquaLuxe
 */

function aqualuxe_register_team_member_cpt() {
    $labels = array(
        'name'                  => _x( 'Team Members', 'Post Type General Name', 'aqualuxe' ),
        'singular_name'         => _x( 'Team Member', 'Post Type Singular Name', 'aqualuxe' ),
        'menu_name'             => __( 'Team Members', 'aqualuxe' ),
        'name_admin_bar'        => __( 'Team Member', 'aqualuxe' ),
        'archives'              => __( 'Team Member Archives', 'aqualuxe' ),
        'attributes'            => __( 'Team Member Attributes', 'aqualuxe' ),
        'parent_item_colon'     => __( 'Parent Team Member:', 'aqualuxe' ),
        'all_items'             => __( 'All Team Members', 'aqualuxe' ),
        'add_new_item'          => __( 'Add New Team Member', 'aqualuxe' ),
        'add_new'               => __( 'Add New', 'aqualuxe' ),
        'new_item'              => __( 'New Team Member', 'aqualuxe' ),
        'edit_item'             => __( 'Edit Team Member', 'aqualuxe' ),
        'update_item'           => __( 'Update Team Member', 'aqualuxe' ),
        'view_item'             => __( 'View Team Member', 'aqualuxe' ),
        'view_items'            => __( 'View Team Members', 'aqualuxe' ),
        'search_items'          => __( 'Search Team Member', 'aqualuxe' ),
        'not_found'             => __( 'Not found', 'aqualuxe' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'aqualuxe' ),
        'featured_image'        => __( 'Featured Image', 'aqualuxe' ),
        'set_featured_image'    => __( 'Set featured image', 'aqualuxe' ),
        'remove_featured_image' => __( 'Remove featured image', 'aqualuxe' ),
        'use_featured_image'    => __( 'Use as featured image', 'aqualuxe' ),
        'insert_into_item'      => __( 'Insert into team member', 'aqualuxe' ),
        'uploaded_to_this_item' => __( 'Uploaded to this team member', 'aqualuxe' ),
        'items_list'            => __( 'Team Members list', 'aqualuxe' ),
        'items_list_navigation' => __( 'Team Members list navigation', 'aqualuxe' ),
        'filter_items_list'     => __( 'Filter team members list', 'aqualuxe' ),
    );
    $args = array(
        'label'                 => __( 'Team Member', 'aqualuxe' ),
        'description'           => __( 'Post Type for Team Members', 'aqualuxe' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'thumbnail', 'revisions', 'custom-fields' ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-groups',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'page',
        'show_in_rest'          => true,
    );
    register_post_type( 'team_member', $args );
}
add_action( 'init', 'aqualuxe_register_team_member_cpt', 0 );
