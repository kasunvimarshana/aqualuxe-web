<?php
/**
 * Register FAQ CPT
 *
 * @package AquaLuxe
 */

function aqualuxe_register_faq_cpt() {
    $labels = array(
        'name'                  => _x( 'FAQs', 'Post Type General Name', 'aqualuxe' ),
        'singular_name'         => _x( 'FAQ', 'Post Type Singular Name', 'aqualuxe' ),
        'menu_name'             => __( 'FAQs', 'aqualuxe' ),
        'name_admin_bar'        => __( 'FAQ', 'aqualuxe' ),
        'archives'              => __( 'FAQ Archives', 'aqualuxe' ),
        'attributes'            => __( 'FAQ Attributes', 'aqualuxe' ),
        'parent_item_colon'     => __( 'Parent FAQ:', 'aqualuxe' ),
        'all_items'             => __( 'All FAQs', 'aqualuxe' ),
        'add_new_item'          => __( 'Add New FAQ', 'aqualuxe' ),
        'add_new'               => __( 'Add New', 'aqualuxe' ),
        'new_item'              => __( 'New FAQ', 'aqualuxe' ),
        'edit_item'             => __( 'Edit FAQ', 'aqualuxe' ),
        'update_item'           => __( 'Update FAQ', 'aqualuxe' ),
        'view_item'             => __( 'View FAQ', 'aqualuxe' ),
        'view_items'            => __( 'View FAQs', 'aqualuxe' ),
        'search_items'          => __( 'Search FAQ', 'aqualuxe' ),
        'not_found'             => __( 'Not found', 'aqualuxe' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'aqualuxe' ),
        'featured_image'        => __( 'Featured Image', 'aqualuxe' ),
        'set_featured_image'    => __( 'Set featured image', 'aqualuxe' ),
        'remove_featured_image' => __( 'Remove featured image', 'aqualuxe' ),
        'use_featured_image'    => __( 'Use as featured image', 'aqualuxe' ),
        'insert_into_item'      => __( 'Insert into FAQ', 'aqualuxe' ),
        'uploaded_to_this_item' => __( 'Uploaded to this FAQ', 'aqualuxe' ),
        'items_list'            => __( 'FAQs list', 'aqualuxe' ),
        'items_list_navigation' => __( 'FAQs list navigation', 'aqualuxe' ),
        'filter_items_list'     => __( 'Filter FAQs list', 'aqualuxe' ),
    );
    $args = array(
        'label'                 => __( 'FAQ', 'aqualuxe' ),
        'description'           => __( 'Post Type for FAQs', 'aqualuxe' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'revisions', 'custom-fields' ),
        'taxonomies'            => array( 'faq_category' ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-editor-help',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'page',
        'show_in_rest'          => true,
    );
    register_post_type( 'faq', $args );
}
add_action( 'init', 'aqualuxe_register_faq_cpt', 0 );
