<?php
/**
 * Custom Taxonomies Registration.
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Custom Taxonomies.
 */
function aqualuxe_register_taxonomies() {
    // Example Taxonomy: Service Categories
    $labels = array(
        'name'              => _x( 'Service Categories', 'taxonomy general name', 'aqualuxe' ),
        'singular_name'     => _x( 'Service Category', 'taxonomy singular name', 'aqualuxe' ),
        'search_items'      => __( 'Search Service Categories', 'aqualuxe' ),
        'all_items'         => __( 'All Service Categories', 'aqualuxe' ),
        'parent_item'       => __( 'Parent Service Category', 'aqualuxe' ),
        'parent_item_colon' => __( 'Parent Service Category:', 'aqualuxe' ),
        'edit_item'         => __( 'Edit Service Category', 'aqualuxe' ),
        'update_item'       => __( 'Update Service Category', 'aqualuxe' ),
        'add_new_item'      => __( 'Add New Service Category', 'aqualuxe' ),
        'new_item_name'     => __( 'New Service Category Name', 'aqualuxe' ),
        'menu_name'         => __( 'Service Categories', 'aqualuxe' ),
    );
    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'service-category' ),
        'show_in_rest'      => true,
    );
    register_taxonomy( 'service_category', array( 'service' ), $args );

    // Taxonomy: Event Categories
    $labels = array(
        'name'              => _x( 'Event Categories', 'taxonomy general name', 'aqualuxe' ),
        'singular_name'     => _x( 'Event Category', 'taxonomy singular name', 'aqualuxe' ),
        'search_items'      => __( 'Search Event Categories', 'aqualuxe' ),
        'all_items'         => __( 'All Event Categories', 'aqualuxe' ),
        'parent_item'       => __( 'Parent Event Category', 'aqualuxe' ),
        'parent_item_colon' => __( 'Parent Event Category:', 'aqualuxe' ),
        'edit_item'         => __( 'Edit Event Category', 'aqualuxe' ),
        'update_item'       => __( 'Update Event Category', 'aqualuxe' ),
        'add_new_item'      => __( 'Add New Event Category', 'aqualuxe' ),
        'new_item_name'     => __( 'New Event Category Name', 'aqualuxe' ),
        'menu_name'         => __( 'Event Categories', 'aqualuxe' ),
    );
    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'event-category' ),
        'show_in_rest'      => true,
    );
    register_taxonomy( 'event_category', array( 'event' ), $args );

    // Taxonomy: Project Categories
    $labels = array(
        'name'              => _x( 'Project Categories', 'taxonomy general name', 'aqualuxe' ),
        'singular_name'     => _x( 'Project Category', 'taxonomy singular name', 'aqualuxe' ),
        'search_items'      => __( 'Search Project Categories', 'aqualuxe' ),
        'all_items'         => __( 'All Project Categories', 'aqualuxe' ),
        'parent_item'       => __( 'Parent Project Category', 'aqualuxe' ),
        'parent_item_colon' => __( 'Parent Project Category:', 'aqualuxe' ),
        'edit_item'         => __( 'Edit Project Category', 'aqualuxe' ),
        'update_item'       => __( 'Update Project Category', 'aqualuxe' ),
        'add_new_item'      => __( 'Add New Project Category', 'aqualuxe' ),
        'new_item_name'     => __( 'New Project Category Name', 'aqualuxe' ),
        'menu_name'         => __( 'Project Categories', 'aqualuxe' ),
    );
    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'project-category' ),
        'show_in_rest'      => true,
    );
    register_taxonomy( 'project_category', array( 'project' ), $args );

    // Taxonomy: Partner Types
    $labels = array(
        'name'              => _x( 'Partner Types', 'taxonomy general name', 'aqualuxe' ),
        'singular_name'     => _x( 'Partner Type', 'taxonomy singular name', 'aqualuxe' ),
        'search_items'      => __( 'Search Partner Types', 'aqualuxe' ),
        'all_items'         => __( 'All Partner Types', 'aqualuxe' ),
        'parent_item'       => __( 'Parent Partner Type', 'aqualuxe' ),
        'parent_item_colon' => __( 'Parent Partner Type:', 'aqualuxe' ),
        'edit_item'         => __( 'Edit Partner Type', 'aqualuxe' ),
        'update_item'       => __( 'Update Partner Type', 'aqualuxe' ),
        'add_new_item'      => __( 'Add New Partner Type', 'aqualuxe' ),
        'new_item_name'     => __( 'New Partner Type Name', 'aqualuxe' ),
        'menu_name'         => __( 'Partner Types', 'aqualuxe' ),
    );
    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'partner-type' ),
        'show_in_rest'      => true,
    );
    register_taxonomy( 'partner_type', array( 'partner' ), $args );

    // Taxonomy: Auction Categories
    $labels = array(
        'name'              => _x( 'Auction Categories', 'taxonomy general name', 'aqualuxe' ),
        'singular_name'     => _x( 'Auction Category', 'taxonomy singular name', 'aqualuxe' ),
        'search_items'      => __( 'Search Auction Categories', 'aqualuxe' ),
        'all_items'         => __( 'All Auction Categories', 'aqualuxe' ),
        'parent_item'       => __( 'Parent Auction Category', 'aqualuxe' ),
        'parent_item_colon' => __( 'Parent Auction Category:', 'aqualuxe' ),
        'edit_item'         => __( 'Edit Auction Category', 'aqualuxe' ),
        'update_item'       => __( 'Update Auction Category', 'aqualuxe' ),
        'add_new_item'      => __( 'Add New Auction Category', 'aqualuxe' ),
        'new_item_name'     => __( 'New Auction Category Name', 'aqualuxe' ),
        'menu_name'         => __( 'Auction Categories', 'aqualuxe' ),
    );
    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'auction-category' ),
        'show_in_rest'      => true,
    );
    register_taxonomy( 'auction_category', array( 'auction' ), $args );

    // Taxonomy: Wholesale Tiers
    $labels = array(
        'name'              => _x( 'Wholesale Tiers', 'taxonomy general name', 'aqualuxe' ),
        'singular_name'     => _x( 'Wholesale Tier', 'taxonomy singular name', 'aqualuxe' ),
        'search_items'      => __( 'Search Wholesale Tiers', 'aqualuxe' ),
        'all_items'         => __( 'All Wholesale Tiers', 'aqualuxe' ),
        'parent_item'       => __( 'Parent Wholesale Tier', 'aqualuxe' ),
        'parent_item_colon' => __( 'Parent Wholesale Tier:', 'aqualuxe' ),
        'edit_item'         => __( 'Edit Wholesale Tier', 'aqualuxe' ),
        'update_item'       => __( 'Update Wholesale Tier', 'aqualuxe' ),
        'add_new_item'      => __( 'Add New Wholesale Tier', 'aqualuxe' ),
        'new_item_name'     => __( 'New Wholesale Tier Name', 'aqualuxe' ),
        'menu_name'         => __( 'Wholesale Tiers', 'aqualuxe' ),
    );
    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'wholesale-tier' ),
        'show_in_rest'      => true,
    );
    register_taxonomy( 'wholesale_tier', array( 'wholesale_account' ), $args );

    // Taxonomy: Account Status
    $labels = array(
        'name'              => _x( 'Account Statuses', 'taxonomy general name', 'aqualuxe' ),
        'singular_name'     => _x( 'Account Status', 'taxonomy singular name', 'aqualuxe' ),
        'search_items'      => __( 'Search Account Statuses', 'aqualuxe' ),
        'all_items'         => __( 'All Account Statuses', 'aqualuxe' ),
        'parent_item'       => null,
        'parent_item_colon' => null,
        'edit_item'         => __( 'Edit Account Status', 'aqualuxe' ),
        'update_item'       => __( 'Update Account Status', 'aqualuxe' ),
        'add_new_item'      => __( 'Add New Account Status', 'aqualuxe' ),
        'new_item_name'     => __( 'New Account Status Name', 'aqualuxe' ),
        'menu_name'         => __( 'Account Statuses', 'aqualuxe' ),
    );
    $args = array(
        'hierarchical'      => false, // Like tags
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'account-status' ),
        'show_in_rest'      => true,
    );
    register_taxonomy( 'account_status', array( 'wholesale_account' ), $args );
}
add_action( 'init', 'aqualuxe_register_taxonomies', 0 );
