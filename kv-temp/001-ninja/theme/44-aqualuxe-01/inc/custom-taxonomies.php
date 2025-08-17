<?php
/**
 * Custom taxonomies for AquaLuxe theme
 *
 * @package AquaLuxe
 */

// Prevent direct access to this file
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register custom taxonomies
 */
function aqualuxe_register_taxonomies() {
    // Service Category Taxonomy
    register_taxonomy(
        'service_category',
        'service',
        array(
            'labels' => array(
                'name'              => _x('Service Categories', 'taxonomy general name', 'aqualuxe'),
                'singular_name'     => _x('Service Category', 'taxonomy singular name', 'aqualuxe'),
                'search_items'      => __('Search Service Categories', 'aqualuxe'),
                'all_items'         => __('All Service Categories', 'aqualuxe'),
                'parent_item'       => __('Parent Service Category', 'aqualuxe'),
                'parent_item_colon' => __('Parent Service Category:', 'aqualuxe'),
                'edit_item'         => __('Edit Service Category', 'aqualuxe'),
                'update_item'       => __('Update Service Category', 'aqualuxe'),
                'add_new_item'      => __('Add New Service Category', 'aqualuxe'),
                'new_item_name'     => __('New Service Category Name', 'aqualuxe'),
                'menu_name'         => __('Categories', 'aqualuxe'),
            ),
            'hierarchical'      => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => 'service-category'),
            'show_in_rest'      => true,
        )
    );

    // Event Category Taxonomy
    register_taxonomy(
        'event_category',
        'event',
        array(
            'labels' => array(
                'name'              => _x('Event Categories', 'taxonomy general name', 'aqualuxe'),
                'singular_name'     => _x('Event Category', 'taxonomy singular name', 'aqualuxe'),
                'search_items'      => __('Search Event Categories', 'aqualuxe'),
                'all_items'         => __('All Event Categories', 'aqualuxe'),
                'parent_item'       => __('Parent Event Category', 'aqualuxe'),
                'parent_item_colon' => __('Parent Event Category:', 'aqualuxe'),
                'edit_item'         => __('Edit Event Category', 'aqualuxe'),
                'update_item'       => __('Update Event Category', 'aqualuxe'),
                'add_new_item'      => __('Add New Event Category', 'aqualuxe'),
                'new_item_name'     => __('New Event Category Name', 'aqualuxe'),
                'menu_name'         => __('Categories', 'aqualuxe'),
            ),
            'hierarchical'      => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => 'event-category'),
            'show_in_rest'      => true,
        )
    );

    // Project Category Taxonomy
    register_taxonomy(
        'project_category',
        'project',
        array(
            'labels' => array(
                'name'              => _x('Project Categories', 'taxonomy general name', 'aqualuxe'),
                'singular_name'     => _x('Project Category', 'taxonomy singular name', 'aqualuxe'),
                'search_items'      => __('Search Project Categories', 'aqualuxe'),
                'all_items'         => __('All Project Categories', 'aqualuxe'),
                'parent_item'       => __('Parent Project Category', 'aqualuxe'),
                'parent_item_colon' => __('Parent Project Category:', 'aqualuxe'),
                'edit_item'         => __('Edit Project Category', 'aqualuxe'),
                'update_item'       => __('Update Project Category', 'aqualuxe'),
                'add_new_item'      => __('Add New Project Category', 'aqualuxe'),
                'new_item_name'     => __('New Project Category Name', 'aqualuxe'),
                'menu_name'         => __('Categories', 'aqualuxe'),
            ),
            'hierarchical'      => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => 'project-category'),
            'show_in_rest'      => true,
        )
    );

    // FAQ Category Taxonomy
    register_taxonomy(
        'faq_category',
        'faq',
        array(
            'labels' => array(
                'name'              => _x('FAQ Categories', 'taxonomy general name', 'aqualuxe'),
                'singular_name'     => _x('FAQ Category', 'taxonomy singular name', 'aqualuxe'),
                'search_items'      => __('Search FAQ Categories', 'aqualuxe'),
                'all_items'         => __('All FAQ Categories', 'aqualuxe'),
                'parent_item'       => __('Parent FAQ Category', 'aqualuxe'),
                'parent_item_colon' => __('Parent FAQ Category:', 'aqualuxe'),
                'edit_item'         => __('Edit FAQ Category', 'aqualuxe'),
                'update_item'       => __('Update FAQ Category', 'aqualuxe'),
                'add_new_item'      => __('Add New FAQ Category', 'aqualuxe'),
                'new_item_name'     => __('New FAQ Category Name', 'aqualuxe'),
                'menu_name'         => __('Categories', 'aqualuxe'),
            ),
            'hierarchical'      => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => 'faq-category'),
            'show_in_rest'      => true,
        )
    );

    // Team Department Taxonomy
    register_taxonomy(
        'team_department',
        'team',
        array(
            'labels' => array(
                'name'              => _x('Departments', 'taxonomy general name', 'aqualuxe'),
                'singular_name'     => _x('Department', 'taxonomy singular name', 'aqualuxe'),
                'search_items'      => __('Search Departments', 'aqualuxe'),
                'all_items'         => __('All Departments', 'aqualuxe'),
                'parent_item'       => __('Parent Department', 'aqualuxe'),
                'parent_item_colon' => __('Parent Department:', 'aqualuxe'),
                'edit_item'         => __('Edit Department', 'aqualuxe'),
                'update_item'       => __('Update Department', 'aqualuxe'),
                'add_new_item'      => __('Add New Department', 'aqualuxe'),
                'new_item_name'     => __('New Department Name', 'aqualuxe'),
                'menu_name'         => __('Departments', 'aqualuxe'),
            ),
            'hierarchical'      => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => 'department'),
            'show_in_rest'      => true,
        )
    );

    // Testimonial Type Taxonomy
    register_taxonomy(
        'testimonial_type',
        'testimonial',
        array(
            'labels' => array(
                'name'              => _x('Testimonial Types', 'taxonomy general name', 'aqualuxe'),
                'singular_name'     => _x('Testimonial Type', 'taxonomy singular name', 'aqualuxe'),
                'search_items'      => __('Search Testimonial Types', 'aqualuxe'),
                'all_items'         => __('All Testimonial Types', 'aqualuxe'),
                'parent_item'       => __('Parent Testimonial Type', 'aqualuxe'),
                'parent_item_colon' => __('Parent Testimonial Type:', 'aqualuxe'),
                'edit_item'         => __('Edit Testimonial Type', 'aqualuxe'),
                'update_item'       => __('Update Testimonial Type', 'aqualuxe'),
                'add_new_item'      => __('Add New Testimonial Type', 'aqualuxe'),
                'new_item_name'     => __('New Testimonial Type Name', 'aqualuxe'),
                'menu_name'         => __('Types', 'aqualuxe'),
            ),
            'hierarchical'      => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => 'testimonial-type'),
            'show_in_rest'      => true,
        )
    );
}
add_action('init', 'aqualuxe_register_taxonomies');