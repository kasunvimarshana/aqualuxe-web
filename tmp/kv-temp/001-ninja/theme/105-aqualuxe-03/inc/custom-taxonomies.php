<?php
/**
 * Custom Taxonomies Registration
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register Service Categories taxonomy
 */
function aqualuxe_register_service_categories() {
    $labels = array(
        'name'                       => _x('Service Categories', 'Taxonomy General Name', 'aqualuxe'),
        'singular_name'              => _x('Service Category', 'Taxonomy Singular Name', 'aqualuxe'),
        'menu_name'                  => __('Categories', 'aqualuxe'),
        'all_items'                  => __('All Categories', 'aqualuxe'),
        'parent_item'                => __('Parent Category', 'aqualuxe'),
        'parent_item_colon'          => __('Parent Category:', 'aqualuxe'),
        'new_item_name'              => __('New Category Name', 'aqualuxe'),
        'add_new_item'               => __('Add New Category', 'aqualuxe'),
        'edit_item'                  => __('Edit Category', 'aqualuxe'),
        'update_item'                => __('Update Category', 'aqualuxe'),
        'view_item'                  => __('View Category', 'aqualuxe'),
        'separate_items_with_commas' => __('Separate categories with commas', 'aqualuxe'),
        'add_or_remove_items'        => __('Add or remove categories', 'aqualuxe'),
        'choose_from_most_used'      => __('Choose from the most used', 'aqualuxe'),
        'popular_items'              => __('Popular Categories', 'aqualuxe'),
        'search_items'               => __('Search Categories', 'aqualuxe'),
        'not_found'                  => __('Not Found', 'aqualuxe'),
        'no_terms'                   => __('No categories', 'aqualuxe'),
        'items_list'                 => __('Categories list', 'aqualuxe'),
        'items_list_navigation'      => __('Categories list navigation', 'aqualuxe'),
    );

    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => true,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
        'show_in_rest'               => true,
        'rewrite'                    => array('slug' => 'service-category', 'hierarchical' => true),
    );

    register_taxonomy('service_category', array('aqualuxe_service'), $args);
}
add_action('init', 'aqualuxe_register_service_categories', 0);

/**
 * Register Service Tags taxonomy
 */
function aqualuxe_register_service_tags() {
    $labels = array(
        'name'                       => _x('Service Tags', 'Taxonomy General Name', 'aqualuxe'),
        'singular_name'              => _x('Service Tag', 'Taxonomy Singular Name', 'aqualuxe'),
        'menu_name'                  => __('Tags', 'aqualuxe'),
        'all_items'                  => __('All Tags', 'aqualuxe'),
        'parent_item'                => __('Parent Tag', 'aqualuxe'),
        'parent_item_colon'          => __('Parent Tag:', 'aqualuxe'),
        'new_item_name'              => __('New Tag Name', 'aqualuxe'),
        'add_new_item'               => __('Add New Tag', 'aqualuxe'),
        'edit_item'                  => __('Edit Tag', 'aqualuxe'),
        'update_item'                => __('Update Tag', 'aqualuxe'),
        'view_item'                  => __('View Tag', 'aqualuxe'),
        'separate_items_with_commas' => __('Separate tags with commas', 'aqualuxe'),
        'add_or_remove_items'        => __('Add or remove tags', 'aqualuxe'),
        'choose_from_most_used'      => __('Choose from the most used', 'aqualuxe'),
        'popular_items'              => __('Popular Tags', 'aqualuxe'),
        'search_items'               => __('Search Tags', 'aqualuxe'),
        'not_found'                  => __('Not Found', 'aqualuxe'),
        'no_terms'                   => __('No tags', 'aqualuxe'),
        'items_list'                 => __('Tags list', 'aqualuxe'),
        'items_list_navigation'      => __('Tags list navigation', 'aqualuxe'),
    );

    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => false,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
        'show_in_rest'               => true,
        'rewrite'                    => array('slug' => 'service-tag'),
    );

    register_taxonomy('service_tag', array('aqualuxe_service'), $args);
}
add_action('init', 'aqualuxe_register_service_tags', 0);

/**
 * Register Event Categories taxonomy
 */
function aqualuxe_register_event_categories() {
    $labels = array(
        'name'                       => _x('Event Categories', 'Taxonomy General Name', 'aqualuxe'),
        'singular_name'              => _x('Event Category', 'Taxonomy Singular Name', 'aqualuxe'),
        'menu_name'                  => __('Categories', 'aqualuxe'),
        'all_items'                  => __('All Categories', 'aqualuxe'),
        'parent_item'                => __('Parent Category', 'aqualuxe'),
        'parent_item_colon'          => __('Parent Category:', 'aqualuxe'),
        'new_item_name'              => __('New Category Name', 'aqualuxe'),
        'add_new_item'               => __('Add New Category', 'aqualuxe'),
        'edit_item'                  => __('Edit Category', 'aqualuxe'),
        'update_item'                => __('Update Category', 'aqualuxe'),
        'view_item'                  => __('View Category', 'aqualuxe'),
        'separate_items_with_commas' => __('Separate categories with commas', 'aqualuxe'),
        'add_or_remove_items'        => __('Add or remove categories', 'aqualuxe'),
        'choose_from_most_used'      => __('Choose from the most used', 'aqualuxe'),
        'popular_items'              => __('Popular Categories', 'aqualuxe'),
        'search_items'               => __('Search Categories', 'aqualuxe'),
        'not_found'                  => __('Not Found', 'aqualuxe'),
        'no_terms'                   => __('No categories', 'aqualuxe'),
        'items_list'                 => __('Categories list', 'aqualuxe'),
        'items_list_navigation'      => __('Categories list navigation', 'aqualuxe'),
    );

    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => true,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
        'show_in_rest'               => true,
        'rewrite'                    => array('slug' => 'event-category', 'hierarchical' => true),
    );

    register_taxonomy('event_category', array('aqualuxe_event'), $args);
}
add_action('init', 'aqualuxe_register_event_categories', 0);

/**
 * Register Event Tags taxonomy
 */
function aqualuxe_register_event_tags() {
    $labels = array(
        'name'                       => _x('Event Tags', 'Taxonomy General Name', 'aqualuxe'),
        'singular_name'              => _x('Event Tag', 'Taxonomy Singular Name', 'aqualuxe'),
        'menu_name'                  => __('Tags', 'aqualuxe'),
        'all_items'                  => __('All Tags', 'aqualuxe'),
        'parent_item'                => __('Parent Tag', 'aqualuxe'),
        'parent_item_colon'          => __('Parent Tag:', 'aqualuxe'),
        'new_item_name'              => __('New Tag Name', 'aqualuxe'),
        'add_new_item'               => __('Add New Tag', 'aqualuxe'),
        'edit_item'                  => __('Edit Tag', 'aqualuxe'),
        'update_item'                => __('Update Tag', 'aqualuxe'),
        'view_item'                  => __('View Tag', 'aqualuxe'),
        'separate_items_with_commas' => __('Separate tags with commas', 'aqualuxe'),
        'add_or_remove_items'        => __('Add or remove tags', 'aqualuxe'),
        'choose_from_most_used'      => __('Choose from the most used', 'aqualuxe'),
        'popular_items'              => __('Popular Tags', 'aqualuxe'),
        'search_items'               => __('Search Tags', 'aqualuxe'),
        'not_found'                  => __('Not Found', 'aqualuxe'),
        'no_terms'                   => __('No tags', 'aqualuxe'),
        'items_list'                 => __('Tags list', 'aqualuxe'),
        'items_list_navigation'      => __('Tags list navigation', 'aqualuxe'),
    );

    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => false,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
        'show_in_rest'               => true,
        'rewrite'                    => array('slug' => 'event-tag'),
    );

    register_taxonomy('event_tag', array('aqualuxe_event'), $args);
}
add_action('init', 'aqualuxe_register_event_tags', 0);

/**
 * Register Team Departments taxonomy
 */
function aqualuxe_register_team_departments() {
    $labels = array(
        'name'                       => _x('Departments', 'Taxonomy General Name', 'aqualuxe'),
        'singular_name'              => _x('Department', 'Taxonomy Singular Name', 'aqualuxe'),
        'menu_name'                  => __('Departments', 'aqualuxe'),
        'all_items'                  => __('All Departments', 'aqualuxe'),
        'parent_item'                => __('Parent Department', 'aqualuxe'),
        'parent_item_colon'          => __('Parent Department:', 'aqualuxe'),
        'new_item_name'              => __('New Department Name', 'aqualuxe'),
        'add_new_item'               => __('Add New Department', 'aqualuxe'),
        'edit_item'                  => __('Edit Department', 'aqualuxe'),
        'update_item'                => __('Update Department', 'aqualuxe'),
        'view_item'                  => __('View Department', 'aqualuxe'),
        'separate_items_with_commas' => __('Separate departments with commas', 'aqualuxe'),
        'add_or_remove_items'        => __('Add or remove departments', 'aqualuxe'),
        'choose_from_most_used'      => __('Choose from the most used', 'aqualuxe'),
        'popular_items'              => __('Popular Departments', 'aqualuxe'),
        'search_items'               => __('Search Departments', 'aqualuxe'),
        'not_found'                  => __('Not Found', 'aqualuxe'),
        'no_terms'                   => __('No departments', 'aqualuxe'),
        'items_list'                 => __('Departments list', 'aqualuxe'),
        'items_list_navigation'      => __('Departments list navigation', 'aqualuxe'),
    );

    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => true,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => false,
        'show_in_rest'               => true,
        'rewrite'                    => array('slug' => 'department', 'hierarchical' => true),
    );

    register_taxonomy('team_department', array('aqualuxe_team'), $args);
}
add_action('init', 'aqualuxe_register_team_departments', 0);

/**
 * Register Fish Species taxonomy for products
 */
function aqualuxe_register_fish_species() {
    // Only register if WooCommerce is active
    if (!class_exists('WooCommerce')) {
        return;
    }

    $labels = array(
        'name'                       => _x('Fish Species', 'Taxonomy General Name', 'aqualuxe'),
        'singular_name'              => _x('Fish Species', 'Taxonomy Singular Name', 'aqualuxe'),
        'menu_name'                  => __('Fish Species', 'aqualuxe'),
        'all_items'                  => __('All Species', 'aqualuxe'),
        'parent_item'                => __('Parent Species', 'aqualuxe'),
        'parent_item_colon'          => __('Parent Species:', 'aqualuxe'),
        'new_item_name'              => __('New Species Name', 'aqualuxe'),
        'add_new_item'               => __('Add New Species', 'aqualuxe'),
        'edit_item'                  => __('Edit Species', 'aqualuxe'),
        'update_item'                => __('Update Species', 'aqualuxe'),
        'view_item'                  => __('View Species', 'aqualuxe'),
        'separate_items_with_commas' => __('Separate species with commas', 'aqualuxe'),
        'add_or_remove_items'        => __('Add or remove species', 'aqualuxe'),
        'choose_from_most_used'      => __('Choose from the most used', 'aqualuxe'),
        'popular_items'              => __('Popular Species', 'aqualuxe'),
        'search_items'               => __('Search Species', 'aqualuxe'),
        'not_found'                  => __('Not Found', 'aqualuxe'),
        'no_terms'                   => __('No species', 'aqualuxe'),
        'items_list'                 => __('Species list', 'aqualuxe'),
        'items_list_navigation'      => __('Species list navigation', 'aqualuxe'),
    );

    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => true,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
        'show_in_rest'               => true,
        'rewrite'                    => array('slug' => 'fish-species', 'hierarchical' => true),
    );

    register_taxonomy('fish_species', array('product'), $args);
}
add_action('init', 'aqualuxe_register_fish_species', 0);

/**
 * Register Water Types taxonomy for products
 */
function aqualuxe_register_water_types() {
    // Only register if WooCommerce is active
    if (!class_exists('WooCommerce')) {
        return;
    }

    $labels = array(
        'name'                       => _x('Water Types', 'Taxonomy General Name', 'aqualuxe'),
        'singular_name'              => _x('Water Type', 'Taxonomy Singular Name', 'aqualuxe'),
        'menu_name'                  => __('Water Types', 'aqualuxe'),
        'all_items'                  => __('All Water Types', 'aqualuxe'),
        'parent_item'                => __('Parent Water Type', 'aqualuxe'),
        'parent_item_colon'          => __('Parent Water Type:', 'aqualuxe'),
        'new_item_name'              => __('New Water Type Name', 'aqualuxe'),
        'add_new_item'               => __('Add New Water Type', 'aqualuxe'),
        'edit_item'                  => __('Edit Water Type', 'aqualuxe'),
        'update_item'                => __('Update Water Type', 'aqualuxe'),
        'view_item'                  => __('View Water Type', 'aqualuxe'),
        'separate_items_with_commas' => __('Separate water types with commas', 'aqualuxe'),
        'add_or_remove_items'        => __('Add or remove water types', 'aqualuxe'),
        'choose_from_most_used'      => __('Choose from the most used', 'aqualuxe'),
        'popular_items'              => __('Popular Water Types', 'aqualuxe'),
        'search_items'               => __('Search Water Types', 'aqualuxe'),
        'not_found'                  => __('Not Found', 'aqualuxe'),
        'no_terms'                   => __('No water types', 'aqualuxe'),
        'items_list'                 => __('Water types list', 'aqualuxe'),
        'items_list_navigation'      => __('Water types list navigation', 'aqualuxe'),
    );

    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => false,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
        'show_in_rest'               => true,
        'rewrite'                    => array('slug' => 'water-type'),
    );

    register_taxonomy('water_type', array('product'), $args);
}
add_action('init', 'aqualuxe_register_water_types', 0);

/**
 * Register Equipment Types taxonomy for products
 */
function aqualuxe_register_equipment_types() {
    // Only register if WooCommerce is active
    if (!class_exists('WooCommerce')) {
        return;
    }

    $labels = array(
        'name'                       => _x('Equipment Types', 'Taxonomy General Name', 'aqualuxe'),
        'singular_name'              => _x('Equipment Type', 'Taxonomy Singular Name', 'aqualuxe'),
        'menu_name'                  => __('Equipment Types', 'aqualuxe'),
        'all_items'                  => __('All Equipment Types', 'aqualuxe'),
        'parent_item'                => __('Parent Equipment Type', 'aqualuxe'),
        'parent_item_colon'          => __('Parent Equipment Type:', 'aqualuxe'),
        'new_item_name'              => __('New Equipment Type Name', 'aqualuxe'),
        'add_new_item'               => __('Add New Equipment Type', 'aqualuxe'),
        'edit_item'                  => __('Edit Equipment Type', 'aqualuxe'),
        'update_item'                => __('Update Equipment Type', 'aqualuxe'),
        'view_item'                  => __('View Equipment Type', 'aqualuxe'),
        'separate_items_with_commas' => __('Separate equipment types with commas', 'aqualuxe'),
        'add_or_remove_items'        => __('Add or remove equipment types', 'aqualuxe'),
        'choose_from_most_used'      => __('Choose from the most used', 'aqualuxe'),
        'popular_items'              => __('Popular Equipment Types', 'aqualuxe'),
        'search_items'               => __('Search Equipment Types', 'aqualuxe'),
        'not_found'                  => __('Not Found', 'aqualuxe'),
        'no_terms'                   => __('No equipment types', 'aqualuxe'),
        'items_list'                 => __('Equipment types list', 'aqualuxe'),
        'items_list_navigation'      => __('Equipment types list navigation', 'aqualuxe'),
    );

    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => true,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
        'show_in_rest'               => true,
        'rewrite'                    => array('slug' => 'equipment-type', 'hierarchical' => true),
    );

    register_taxonomy('equipment_type', array('product'), $args);
}
add_action('init', 'aqualuxe_register_equipment_types', 0);