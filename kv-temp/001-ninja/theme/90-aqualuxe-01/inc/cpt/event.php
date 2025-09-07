<?php
/**
 * Register Event CPT
 *
 * @package AquaLuxe
 */

function aqualuxe_register_event_cpt() {
    $labels = array(
        'name'                  => _x( 'Events', 'Post Type General Name', 'aqualuxe' ),
        'singular_name'         => _x( 'Event', 'Post Type Singular Name', 'aqualuxe' ),
        'menu_name'             => __( 'Events', 'aqualuxe' ),
        'name_admin_bar'        => __( 'Event', 'aqualuxe' ),
        'archives'              => __( 'Event Archives', 'aqualuxe' ),
        'attributes'            => __( 'Event Attributes', 'aqualuxe' ),
        'parent_item_colon'     => __( 'Parent Event:', 'aqualuxe' ),
        'all_items'             => __( 'All Events', 'aqualuxe' ),
        'add_new_item'          => __( 'Add New Event', 'aqualuxe' ),
        'add_new'               => __( 'Add New', 'aqualuxe' ),
        'new_item'              => __( 'New Event', 'aqualuxe' ),
        'edit_item'             => __( 'Edit Event', 'aqualuxe' ),
        'update_item'           => __( 'Update Event', 'aqualuxe' ),
        'view_item'             => __( 'View Event', 'aqualuxe' ),
        'view_items'            => __( 'View Events', 'aqualuxe' ),
        'search_items'          => __( 'Search Event', 'aqualuxe' ),
        'not_found'             => __( 'Not found', 'aqualuxe' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'aqualuxe' ),
        'featured_image'        => __( 'Featured Image', 'aqualuxe' ),
        'set_featured_image'    => __( 'Set featured image', 'aqualuxe' ),
        'remove_featured_image' => __( 'Remove featured image', 'aqualuxe' ),
        'use_featured_image'    => __( 'Use as featured image', 'aqualuxe' ),
        'insert_into_item'      => __( 'Insert into event', 'aqualuxe' ),
        'uploaded_to_this_item' => __( 'Uploaded to this event', 'aqualuxe' ),
        'items_list'            => __( 'Events list', 'aqualuxe' ),
        'items_list_navigation' => __( 'Events list navigation', 'aqualuxe' ),
        'filter_items_list'     => __( 'Filter events list', 'aqualuxe' ),
    );
    $args = array(
        'label'                 => __( 'Event', 'aqualuxe' ),
        'description'           => __( 'Post Type for Events', 'aqualuxe' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments', 'revisions', 'custom-fields' ),
        'taxonomies'            => array( 'event_category', 'post_tag' ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-calendar-alt',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'page',
        'show_in_rest'          => true,
    );
    register_post_type( 'event', $args );
}
add_action( 'init', 'aqualuxe_register_event_cpt', 0 );
