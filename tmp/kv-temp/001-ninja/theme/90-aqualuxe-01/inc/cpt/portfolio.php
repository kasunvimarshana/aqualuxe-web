<?php
/**
 * Register Portfolio CPT
 *
 * @package AquaLuxe
 */

function aqualuxe_register_portfolio_cpt() {
    $labels = array(
        'name'                  => _x( 'Portfolios', 'Post Type General Name', 'aqualuxe' ),
        'singular_name'         => _x( 'Portfolio', 'Post Type Singular Name', 'aqualuxe' ),
        'menu_name'             => __( 'Portfolios', 'aqualuxe' ),
        'name_admin_bar'        => __( 'Portfolio', 'aqualuxe' ),
        'archives'              => __( 'Portfolio Archives', 'aqualuxe' ),
        'attributes'            => __( 'Portfolio Attributes', 'aqualuxe' ),
        'parent_item_colon'     => __( 'Parent Portfolio:', 'aqualuxe' ),
        'all_items'             => __( 'All Portfolios', 'aqualuxe' ),
        'add_new_item'          => __( 'Add New Portfolio', 'aqualuxe' ),
        'add_new'               => __( 'Add New', 'aqualuxe' ),
        'new_item'              => __( 'New Portfolio', 'aqualuxe' ),
        'edit_item'             => __( 'Edit Portfolio', 'aqualuxe' ),
        'update_item'           => __( 'Update Portfolio', 'aqualuxe' ),
        'view_item'             => __( 'View Portfolio', 'aqualuxe' ),
        'view_items'            => __( 'View Portfolios', 'aqualuxe' ),
        'search_items'          => __( 'Search Portfolio', 'aqualuxe' ),
        'not_found'             => __( 'Not found', 'aqualuxe' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'aqualuxe' ),
        'featured_image'        => __( 'Featured Image', 'aqualuxe' ),
        'set_featured_image'    => __( 'Set featured image', 'aqualuxe' ),
        'remove_featured_image' => __( 'Remove featured image', 'aqualuxe' ),
        'use_featured_image'    => __( 'Use as featured image', 'aqualuxe' ),
        'insert_into_item'      => __( 'Insert into portfolio', 'aqualuxe' ),
        'uploaded_to_this_item' => __( 'Uploaded to this portfolio', 'aqualuxe' ),
        'items_list'            => __( 'Portfolios list', 'aqualuxe' ),
        'items_list_navigation' => __( 'Portfolios list navigation', 'aqualuxe' ),
        'filter_items_list'     => __( 'Filter portfolios list', 'aqualuxe' ),
    );
    $args = array(
        'label'                 => __( 'Portfolio', 'aqualuxe' ),
        'description'           => __( 'Post Type for Portfolios', 'aqualuxe' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'thumbnail', 'excerpt', 'revisions', 'custom-fields' ),
        'taxonomies'            => array( 'portfolio_category' ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-images-alt2',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'page',
        'show_in_rest'          => true,
    );
    register_post_type( 'portfolio', $args );
}
add_action( 'init', 'aqualuxe_register_portfolio_cpt', 0 );
