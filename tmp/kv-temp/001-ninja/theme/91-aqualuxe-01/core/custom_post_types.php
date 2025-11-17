<?php
/**
 * Custom Post Types Registration.
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Custom Post Types.
 */
function aqualuxe_register_cpts() {
    // Example CPT: Services
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
        'description'           => __( 'AquaLuxe Services', 'aqualuxe' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'thumbnail', 'revisions', 'custom-fields' ),
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

    // CPT: Events
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
        'description'           => __( 'AquaLuxe Events', 'aqualuxe' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'thumbnail', 'revisions', 'custom-fields' ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 6,
        'menu_icon'             => 'dashicons-calendar-alt',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'show_in_rest'          => true,
    );
    register_post_type( 'event', $args );

    // CPT: Projects
    $labels = array(
        'name'                  => _x( 'Projects', 'Post Type General Name', 'aqualuxe' ),
        'singular_name'         => _x( 'Project', 'Post Type Singular Name', 'aqualuxe' ),
        'menu_name'             => __( 'Projects', 'aqualuxe' ),
        'name_admin_bar'        => __( 'Project', 'aqualuxe' ),
        'archives'              => __( 'Project Archives', 'aqualuxe' ),
        'attributes'            => __( 'Project Attributes', 'aqualuxe' ),
        'parent_item_colon'     => __( 'Parent Project:', 'aqualuxe' ),
        'all_items'             => __( 'All Projects', 'aqualuxe' ),
        'add_new_item'          => __( 'Add New Project', 'aqualuxe' ),
        'add_new'               => __( 'Add New', 'aqualuxe' ),
        'new_item'              => __( 'New Project', 'aqualuxe' ),
        'edit_item'             => __( 'Edit Project', 'aqualuxe' ),
        'update_item'           => __( 'Update Project', 'aqualuxe' ),
        'view_item'             => __( 'View Project', 'aqualuxe' ),
        'view_items'            => __( 'View Projects', 'aqualuxe' ),
        'search_items'          => __( 'Search Project', 'aqualuxe' ),
        'not_found'             => __( 'Not found', 'aqualuxe' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'aqualuxe' ),
        'featured_image'        => __( 'Featured Image', 'aqualuxe' ),
        'set_featured_image'    => __( 'Set featured image', 'aqualuxe' ),
        'remove_featured_image' => __( 'Remove featured image', 'aqualuxe' ),
        'use_featured_image'    => __( 'Use as featured image', 'aqualuxe' ),
        'insert_into_item'      => __( 'Insert into project', 'aqualuxe' ),
        'uploaded_to_this_item' => __( 'Uploaded to this project', 'aqualuxe' ),
        'items_list'            => __( 'Projects list', 'aqualuxe' ),
        'items_list_navigation' => __( 'Projects list navigation', 'aqualuxe' ),
        'filter_items_list'     => __( 'Filter projects list', 'aqualuxe' ),
    );
    $args = array(
        'label'                 => __( 'Project', 'aqualuxe' ),
        'description'           => __( 'R&D, Sustainability, etc.', 'aqualuxe' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'thumbnail', 'revisions' ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 7,
        'menu_icon'             => 'dashicons-portfolio',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'show_in_rest'          => true,
    );
    register_post_type( 'project', $args );

    // CPT: Partners
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
        'featured_image'        => __( 'Logo', 'aqualuxe' ),
        'set_featured_image'    => __( 'Set logo', 'aqualuxe' ),
        'remove_featured_image' => __( 'Remove logo', 'aqualuxe' ),
        'use_featured_image'    => __( 'Use as logo', 'aqualuxe' ),
        'insert_into_item'      => __( 'Insert into partner', 'aqualuxe' ),
        'uploaded_to_this_item' => __( 'Uploaded to this partner', 'aqualuxe' ),
        'items_list'            => __( 'Partners list', 'aqualuxe' ),
        'items_list_navigation' => __( 'Partners list navigation', 'aqualuxe' ),
        'filter_items_list'     => __( 'Filter partners list', 'aqualuxe' ),
    );
    $args = array(
        'label'                 => __( 'Partner', 'aqualuxe' ),
        'description'           => __( 'Franchise, Affiliates, etc.', 'aqualuxe' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'thumbnail', 'revisions' ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 8,
        'menu_icon'             => 'dashicons-groups',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'show_in_rest'          => true,
    );
    register_post_type( 'partner', $args );
}
add_action( 'init', 'aqualuxe_register_cpts', 0 );
