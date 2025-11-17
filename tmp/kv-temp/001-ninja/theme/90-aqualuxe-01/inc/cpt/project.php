<?php
/**
 * Register Project CPT
 *
 * @package AquaLuxe
 */

function aqualuxe_register_project_cpt() {
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
        'description'           => __( 'Post Type for Projects', 'aqualuxe' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments', 'revisions', 'custom-fields' ),
        'taxonomies'            => array( 'project_category', 'post_tag' ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-portfolio',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'page',
        'show_in_rest'          => true,
    );
    register_post_type( 'project', $args );
}
add_action( 'init', 'aqualuxe_register_project_cpt', 0 );
