<?php
/**
 * Register Job Listing CPT
 *
 * @package AquaLuxe
 */

function aqualuxe_register_job_listing_cpt() {
    $labels = array(
        'name'                  => _x( 'Job Listings', 'Post Type General Name', 'aqualuxe' ),
        'singular_name'         => _x( 'Job Listing', 'Post Type Singular Name', 'aqualuxe' ),
        'menu_name'             => __( 'Job Listings', 'aqualuxe' ),
        'name_admin_bar'        => __( 'Job Listing', 'aqualuxe' ),
        'archives'              => __( 'Job Listing Archives', 'aqualuxe' ),
        'attributes'            => __( 'Job Listing Attributes', 'aqualuxe' ),
        'parent_item_colon'     => __( 'Parent Job Listing:', 'aqualuxe' ),
        'all_items'             => __( 'All Job Listings', 'aqualuxe' ),
        'add_new_item'          => __( 'Add New Job Listing', 'aqualuxe' ),
        'add_new'               => __( 'Add New', 'aqualuxe' ),
        'new_item'              => __( 'New Job Listing', 'aqualuxe' ),
        'edit_item'             => __( 'Edit Job Listing', 'aqualuxe' ),
        'update_item'           => __( 'Update Job Listing', 'aqualuxe' ),
        'view_item'             => __( 'View Job Listing', 'aqualuxe' ),
        'view_items'            => __( 'View Job Listings', 'aqualuxe' ),
        'search_items'          => __( 'Search Job Listing', 'aqualuxe' ),
        'not_found'             => __( 'Not found', 'aqualuxe' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'aqualuxe' ),
        'featured_image'        => __( 'Featured Image', 'aqualuxe' ),
        'set_featured_image'    => __( 'Set featured image', 'aqualuxe' ),
        'remove_featured_image' => __( 'Remove featured image', 'aqualuxe' ),
        'use_featured_image'    => __( 'Use as featured image', 'aqualuxe' ),
        'insert_into_item'      => __( 'Insert into job listing', 'aqualuxe' ),
        'uploaded_to_this_item' => __( 'Uploaded to this job listing', 'aqualuxe' ),
        'items_list'            => __( 'Job Listings list', 'aqualuxe' ),
        'items_list_navigation' => __( 'Job Listings list navigation', 'aqualuxe' ),
        'filter_items_list'     => __( 'Filter job listings list', 'aqualuxe' ),
    );
    $args = array(
        'label'                 => __( 'Job Listing', 'aqualuxe' ),
        'description'           => __( 'Post Type for Job Listings', 'aqualuxe' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'excerpt', 'revisions', 'custom-fields' ),
        'taxonomies'            => array( 'job_category', 'job_type' ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-businessman',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'page',
        'show_in_rest'          => true,
    );
    register_post_type( 'job_listing', $args );
}
add_action( 'init', 'aqualuxe_register_job_listing_cpt', 0 );
