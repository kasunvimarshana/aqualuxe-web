<?php
/**
 * Register Event Category Taxonomy
 *
 * @package AquaLuxe
 */

function aqualuxe_register_event_category_taxonomy() {
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
        'menu_name'         => __( 'Event Category', 'aqualuxe' ),
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
}
add_action( 'init', 'aqualuxe_register_event_category_taxonomy', 0 );
