<?php
/**
 * Custom taxonomies for AquaLuxe theme
 *
 * @package AquaLuxe
 */

/**
 * Register custom taxonomies
 */
function aqualuxe_register_taxonomies() {
    // Register Service Category taxonomy
    register_taxonomy('service_category', 'service', array(
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
    ));

    // Register Service Tag taxonomy
    register_taxonomy('service_tag', 'service', array(
        'labels' => array(
            'name'              => _x('Service Tags', 'taxonomy general name', 'aqualuxe'),
            'singular_name'     => _x('Service Tag', 'taxonomy singular name', 'aqualuxe'),
            'search_items'      => __('Search Service Tags', 'aqualuxe'),
            'all_items'         => __('All Service Tags', 'aqualuxe'),
            'parent_item'       => __('Parent Service Tag', 'aqualuxe'),
            'parent_item_colon' => __('Parent Service Tag:', 'aqualuxe'),
            'edit_item'         => __('Edit Service Tag', 'aqualuxe'),
            'update_item'       => __('Update Service Tag', 'aqualuxe'),
            'add_new_item'      => __('Add New Service Tag', 'aqualuxe'),
            'new_item_name'     => __('New Service Tag Name', 'aqualuxe'),
            'menu_name'         => __('Tags', 'aqualuxe'),
        ),
        'hierarchical'      => false,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'service-tag'),
        'show_in_rest'      => true,
    ));

    // Register Project Category taxonomy
    register_taxonomy('project_category', 'project', array(
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
    ));

    // Register Project Tag taxonomy
    register_taxonomy('project_tag', 'project', array(
        'labels' => array(
            'name'              => _x('Project Tags', 'taxonomy general name', 'aqualuxe'),
            'singular_name'     => _x('Project Tag', 'taxonomy singular name', 'aqualuxe'),
            'search_items'      => __('Search Project Tags', 'aqualuxe'),
            'all_items'         => __('All Project Tags', 'aqualuxe'),
            'parent_item'       => __('Parent Project Tag', 'aqualuxe'),
            'parent_item_colon' => __('Parent Project Tag:', 'aqualuxe'),
            'edit_item'         => __('Edit Project Tag', 'aqualuxe'),
            'update_item'       => __('Update Project Tag', 'aqualuxe'),
            'add_new_item'      => __('Add New Project Tag', 'aqualuxe'),
            'new_item_name'     => __('New Project Tag Name', 'aqualuxe'),
            'menu_name'         => __('Tags', 'aqualuxe'),
        ),
        'hierarchical'      => false,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'project-tag'),
        'show_in_rest'      => true,
    ));

    // Register Event Category taxonomy
    register_taxonomy('event_category', 'event', array(
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
    ));

    // Register Event Tag taxonomy
    register_taxonomy('event_tag', 'event', array(
        'labels' => array(
            'name'              => _x('Event Tags', 'taxonomy general name', 'aqualuxe'),
            'singular_name'     => _x('Event Tag', 'taxonomy singular name', 'aqualuxe'),
            'search_items'      => __('Search Event Tags', 'aqualuxe'),
            'all_items'         => __('All Event Tags', 'aqualuxe'),
            'parent_item'       => __('Parent Event Tag', 'aqualuxe'),
            'parent_item_colon' => __('Parent Event Tag:', 'aqualuxe'),
            'edit_item'         => __('Edit Event Tag', 'aqualuxe'),
            'update_item'       => __('Update Event Tag', 'aqualuxe'),
            'add_new_item'      => __('Add New Event Tag', 'aqualuxe'),
            'new_item_name'     => __('New Event Tag Name', 'aqualuxe'),
            'menu_name'         => __('Tags', 'aqualuxe'),
        ),
        'hierarchical'      => false,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'event-tag'),
        'show_in_rest'      => true,
    ));

    // Register Team Department taxonomy
    register_taxonomy('team_department', 'team_member', array(
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
    ));

    // Register Team Role taxonomy
    register_taxonomy('team_role', 'team_member', array(
        'labels' => array(
            'name'              => _x('Roles', 'taxonomy general name', 'aqualuxe'),
            'singular_name'     => _x('Role', 'taxonomy singular name', 'aqualuxe'),
            'search_items'      => __('Search Roles', 'aqualuxe'),
            'all_items'         => __('All Roles', 'aqualuxe'),
            'parent_item'       => __('Parent Role', 'aqualuxe'),
            'parent_item_colon' => __('Parent Role:', 'aqualuxe'),
            'edit_item'         => __('Edit Role', 'aqualuxe'),
            'update_item'       => __('Update Role', 'aqualuxe'),
            'add_new_item'      => __('Add New Role', 'aqualuxe'),
            'new_item_name'     => __('New Role Name', 'aqualuxe'),
            'menu_name'         => __('Roles', 'aqualuxe'),
        ),
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'role'),
        'show_in_rest'      => true,
    ));

    // Register FAQ Category taxonomy
    register_taxonomy('faq_category', 'faq', array(
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
    ));

    // Register Location Region taxonomy
    register_taxonomy('location_region', 'location', array(
        'labels' => array(
            'name'              => _x('Regions', 'taxonomy general name', 'aqualuxe'),
            'singular_name'     => _x('Region', 'taxonomy singular name', 'aqualuxe'),
            'search_items'      => __('Search Regions', 'aqualuxe'),
            'all_items'         => __('All Regions', 'aqualuxe'),
            'parent_item'       => __('Parent Region', 'aqualuxe'),
            'parent_item_colon' => __('Parent Region:', 'aqualuxe'),
            'edit_item'         => __('Edit Region', 'aqualuxe'),
            'update_item'       => __('Update Region', 'aqualuxe'),
            'add_new_item'      => __('Add New Region', 'aqualuxe'),
            'new_item_name'     => __('New Region Name', 'aqualuxe'),
            'menu_name'         => __('Regions', 'aqualuxe'),
        ),
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'region'),
        'show_in_rest'      => true,
    ));

    // Register Location Type taxonomy
    register_taxonomy('location_type', 'location', array(
        'labels' => array(
            'name'              => _x('Location Types', 'taxonomy general name', 'aqualuxe'),
            'singular_name'     => _x('Location Type', 'taxonomy singular name', 'aqualuxe'),
            'search_items'      => __('Search Location Types', 'aqualuxe'),
            'all_items'         => __('All Location Types', 'aqualuxe'),
            'parent_item'       => __('Parent Location Type', 'aqualuxe'),
            'parent_item_colon' => __('Parent Location Type:', 'aqualuxe'),
            'edit_item'         => __('Edit Location Type', 'aqualuxe'),
            'update_item'       => __('Update Location Type', 'aqualuxe'),
            'add_new_item'      => __('Add New Location Type', 'aqualuxe'),
            'new_item_name'     => __('New Location Type Name', 'aqualuxe'),
            'menu_name'         => __('Types', 'aqualuxe'),
        ),
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'location-type'),
        'show_in_rest'      => true,
    ));

    // Register Sustainability Category taxonomy
    register_taxonomy('sustainability_category', 'sustainability', array(
        'labels' => array(
            'name'              => _x('Sustainability Categories', 'taxonomy general name', 'aqualuxe'),
            'singular_name'     => _x('Sustainability Category', 'taxonomy singular name', 'aqualuxe'),
            'search_items'      => __('Search Sustainability Categories', 'aqualuxe'),
            'all_items'         => __('All Sustainability Categories', 'aqualuxe'),
            'parent_item'       => __('Parent Sustainability Category', 'aqualuxe'),
            'parent_item_colon' => __('Parent Sustainability Category:', 'aqualuxe'),
            'edit_item'         => __('Edit Sustainability Category', 'aqualuxe'),
            'update_item'       => __('Update Sustainability Category', 'aqualuxe'),
            'add_new_item'      => __('Add New Sustainability Category', 'aqualuxe'),
            'new_item_name'     => __('New Sustainability Category Name', 'aqualuxe'),
            'menu_name'         => __('Categories', 'aqualuxe'),
        ),
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'sustainability-category'),
        'show_in_rest'      => true,
    ));

    // Register Research Category taxonomy
    register_taxonomy('research_category', 'research', array(
        'labels' => array(
            'name'              => _x('Research Categories', 'taxonomy general name', 'aqualuxe'),
            'singular_name'     => _x('Research Category', 'taxonomy singular name', 'aqualuxe'),
            'search_items'      => __('Search Research Categories', 'aqualuxe'),
            'all_items'         => __('All Research Categories', 'aqualuxe'),
            'parent_item'       => __('Parent Research Category', 'aqualuxe'),
            'parent_item_colon' => __('Parent Research Category:', 'aqualuxe'),
            'edit_item'         => __('Edit Research Category', 'aqualuxe'),
            'update_item'       => __('Update Research Category', 'aqualuxe'),
            'add_new_item'      => __('Add New Research Category', 'aqualuxe'),
            'new_item_name'     => __('New Research Category Name', 'aqualuxe'),
            'menu_name'         => __('Categories', 'aqualuxe'),
        ),
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'research-category'),
        'show_in_rest'      => true,
    ));

    // Register Franchise Type taxonomy
    register_taxonomy('franchise_type', 'franchise', array(
        'labels' => array(
            'name'              => _x('Franchise Types', 'taxonomy general name', 'aqualuxe'),
            'singular_name'     => _x('Franchise Type', 'taxonomy singular name', 'aqualuxe'),
            'search_items'      => __('Search Franchise Types', 'aqualuxe'),
            'all_items'         => __('All Franchise Types', 'aqualuxe'),
            'parent_item'       => __('Parent Franchise Type', 'aqualuxe'),
            'parent_item_colon' => __('Parent Franchise Type:', 'aqualuxe'),
            'edit_item'         => __('Edit Franchise Type', 'aqualuxe'),
            'update_item'       => __('Update Franchise Type', 'aqualuxe'),
            'add_new_item'      => __('Add New Franchise Type', 'aqualuxe'),
            'new_item_name'     => __('New Franchise Type Name', 'aqualuxe'),
            'menu_name'         => __('Types', 'aqualuxe'),
        ),
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'franchise-type'),
        'show_in_rest'      => true,
    ));

    // Register Fish Species taxonomy for WooCommerce products
    register_taxonomy('fish_species', 'product', array(
        'labels' => array(
            'name'              => _x('Fish Species', 'taxonomy general name', 'aqualuxe'),
            'singular_name'     => _x('Fish Species', 'taxonomy singular name', 'aqualuxe'),
            'search_items'      => __('Search Fish Species', 'aqualuxe'),
            'all_items'         => __('All Fish Species', 'aqualuxe'),
            'parent_item'       => __('Parent Fish Species', 'aqualuxe'),
            'parent_item_colon' => __('Parent Fish Species:', 'aqualuxe'),
            'edit_item'         => __('Edit Fish Species', 'aqualuxe'),
            'update_item'       => __('Update Fish Species', 'aqualuxe'),
            'add_new_item'      => __('Add New Fish Species', 'aqualuxe'),
            'new_item_name'     => __('New Fish Species Name', 'aqualuxe'),
            'menu_name'         => __('Fish Species', 'aqualuxe'),
        ),
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'fish-species'),
        'show_in_rest'      => true,
    ));

    // Register Water Type taxonomy for WooCommerce products
    register_taxonomy('water_type', 'product', array(
        'labels' => array(
            'name'              => _x('Water Types', 'taxonomy general name', 'aqualuxe'),
            'singular_name'     => _x('Water Type', 'taxonomy singular name', 'aqualuxe'),
            'search_items'      => __('Search Water Types', 'aqualuxe'),
            'all_items'         => __('All Water Types', 'aqualuxe'),
            'parent_item'       => __('Parent Water Type', 'aqualuxe'),
            'parent_item_colon' => __('Parent Water Type:', 'aqualuxe'),
            'edit_item'         => __('Edit Water Type', 'aqualuxe'),
            'update_item'       => __('Update Water Type', 'aqualuxe'),
            'add_new_item'      => __('Add New Water Type', 'aqualuxe'),
            'new_item_name'     => __('New Water Type Name', 'aqualuxe'),
            'menu_name'         => __('Water Types', 'aqualuxe'),
        ),
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'water-type'),
        'show_in_rest'      => true,
    ));

    // Register Tank Size taxonomy for WooCommerce products
    register_taxonomy('tank_size', 'product', array(
        'labels' => array(
            'name'              => _x('Tank Sizes', 'taxonomy general name', 'aqualuxe'),
            'singular_name'     => _x('Tank Size', 'taxonomy singular name', 'aqualuxe'),
            'search_items'      => __('Search Tank Sizes', 'aqualuxe'),
            'all_items'         => __('All Tank Sizes', 'aqualuxe'),
            'parent_item'       => __('Parent Tank Size', 'aqualuxe'),
            'parent_item_colon' => __('Parent Tank Size:', 'aqualuxe'),
            'edit_item'         => __('Edit Tank Size', 'aqualuxe'),
            'update_item'       => __('Update Tank Size', 'aqualuxe'),
            'add_new_item'      => __('Add New Tank Size', 'aqualuxe'),
            'new_item_name'     => __('New Tank Size Name', 'aqualuxe'),
            'menu_name'         => __('Tank Sizes', 'aqualuxe'),
        ),
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'tank-size'),
        'show_in_rest'      => true,
    ));

    // Register Care Level taxonomy for WooCommerce products
    register_taxonomy('care_level', 'product', array(
        'labels' => array(
            'name'              => _x('Care Levels', 'taxonomy general name', 'aqualuxe'),
            'singular_name'     => _x('Care Level', 'taxonomy singular name', 'aqualuxe'),
            'search_items'      => __('Search Care Levels', 'aqualuxe'),
            'all_items'         => __('All Care Levels', 'aqualuxe'),
            'parent_item'       => __('Parent Care Level', 'aqualuxe'),
            'parent_item_colon' => __('Parent Care Level:', 'aqualuxe'),
            'edit_item'         => __('Edit Care Level', 'aqualuxe'),
            'update_item'       => __('Update Care Level', 'aqualuxe'),
            'add_new_item'      => __('Add New Care Level', 'aqualuxe'),
            'new_item_name'     => __('New Care Level Name', 'aqualuxe'),
            'menu_name'         => __('Care Levels', 'aqualuxe'),
        ),
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'care-level'),
        'show_in_rest'      => true,
    ));

    // Register Origin taxonomy for WooCommerce products
    register_taxonomy('origin', 'product', array(
        'labels' => array(
            'name'              => _x('Origins', 'taxonomy general name', 'aqualuxe'),
            'singular_name'     => _x('Origin', 'taxonomy singular name', 'aqualuxe'),
            'search_items'      => __('Search Origins', 'aqualuxe'),
            'all_items'         => __('All Origins', 'aqualuxe'),
            'parent_item'       => __('Parent Origin', 'aqualuxe'),
            'parent_item_colon' => __('Parent Origin:', 'aqualuxe'),
            'edit_item'         => __('Edit Origin', 'aqualuxe'),
            'update_item'       => __('Update Origin', 'aqualuxe'),
            'add_new_item'      => __('Add New Origin', 'aqualuxe'),
            'new_item_name'     => __('New Origin Name', 'aqualuxe'),
            'menu_name'         => __('Origins', 'aqualuxe'),
        ),
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'origin'),
        'show_in_rest'      => true,
    ));
}
add_action('init', 'aqualuxe_register_taxonomies');

/**
 * Add custom taxonomy columns
 */
function aqualuxe_add_taxonomy_columns() {
    // Add columns to service_category taxonomy
    add_filter('manage_edit-service_category_columns', 'aqualuxe_taxonomy_columns');
    add_filter('manage_service_category_custom_column', 'aqualuxe_taxonomy_custom_column', 10, 3);
    
    // Add columns to project_category taxonomy
    add_filter('manage_edit-project_category_columns', 'aqualuxe_taxonomy_columns');
    add_filter('manage_project_category_custom_column', 'aqualuxe_taxonomy_custom_column', 10, 3);
    
    // Add columns to event_category taxonomy
    add_filter('manage_edit-event_category_columns', 'aqualuxe_taxonomy_columns');
    add_filter('manage_event_category_custom_column', 'aqualuxe_taxonomy_custom_column', 10, 3);
    
    // Add columns to team_department taxonomy
    add_filter('manage_edit-team_department_columns', 'aqualuxe_taxonomy_columns');
    add_filter('manage_team_department_custom_column', 'aqualuxe_taxonomy_custom_column', 10, 3);
    
    // Add columns to faq_category taxonomy
    add_filter('manage_edit-faq_category_columns', 'aqualuxe_taxonomy_columns');
    add_filter('manage_faq_category_custom_column', 'aqualuxe_taxonomy_custom_column', 10, 3);
    
    // Add columns to location_region taxonomy
    add_filter('manage_edit-location_region_columns', 'aqualuxe_taxonomy_columns');
    add_filter('manage_location_region_custom_column', 'aqualuxe_taxonomy_custom_column', 10, 3);
    
    // Add columns to fish_species taxonomy
    add_filter('manage_edit-fish_species_columns', 'aqualuxe_taxonomy_columns');
    add_filter('manage_fish_species_custom_column', 'aqualuxe_taxonomy_custom_column', 10, 3);
    
    // Add columns to water_type taxonomy
    add_filter('manage_edit-water_type_columns', 'aqualuxe_taxonomy_columns');
    add_filter('manage_water_type_custom_column', 'aqualuxe_taxonomy_custom_column', 10, 3);
}
add_action('admin_init', 'aqualuxe_add_taxonomy_columns');

/**
 * Define taxonomy columns
 *
 * @param array $columns Array of columns.
 * @return array Modified array of columns.
 */
function aqualuxe_taxonomy_columns($columns) {
    $new_columns = array(
        'cb' => $columns['cb'],
        'name' => $columns['name'],
        'slug' => __('Slug', 'aqualuxe'),
        'description' => __('Description', 'aqualuxe'),
        'count' => $columns['count'],
    );
    
    return $new_columns;
}

/**
 * Display taxonomy custom column content
 *
 * @param string $content Column content.
 * @param string $column Column name.
 * @param int $term_id Term ID.
 * @return string Modified column content.
 */
function aqualuxe_taxonomy_custom_column($content, $column, $term_id) {
    if ('slug' === $column) {
        $term = get_term($term_id);
        $content = $term->slug;
    }
    
    if ('description' === $column) {
        $term = get_term($term_id);
        $content = $term->description;
    }
    
    return $content;
}

/**
 * Add term meta fields for taxonomies
 */
function aqualuxe_add_term_meta_fields() {
    // Add term meta fields for service_category taxonomy
    add_action('service_category_add_form_fields', 'aqualuxe_add_term_meta_fields_callback');
    add_action('service_category_edit_form_fields', 'aqualuxe_edit_term_meta_fields_callback', 10, 2);
    
    // Add term meta fields for project_category taxonomy
    add_action('project_category_add_form_fields', 'aqualuxe_add_term_meta_fields_callback');
    add_action('project_category_edit_form_fields', 'aqualuxe_edit_term_meta_fields_callback', 10, 2);
    
    // Add term meta fields for event_category taxonomy
    add_action('event_category_add_form_fields', 'aqualuxe_add_term_meta_fields_callback');
    add_action('event_category_edit_form_fields', 'aqualuxe_edit_term_meta_fields_callback', 10, 2);
    
    // Add term meta fields for team_department taxonomy
    add_action('team_department_add_form_fields', 'aqualuxe_add_term_meta_fields_callback');
    add_action('team_department_edit_form_fields', 'aqualuxe_edit_term_meta_fields_callback', 10, 2);
    
    // Add term meta fields for faq_category taxonomy
    add_action('faq_category_add_form_fields', 'aqualuxe_add_term_meta_fields_callback');
    add_action('faq_category_edit_form_fields', 'aqualuxe_edit_term_meta_fields_callback', 10, 2);
    
    // Add term meta fields for location_region taxonomy
    add_action('location_region_add_form_fields', 'aqualuxe_add_term_meta_fields_callback');
    add_action('location_region_edit_form_fields', 'aqualuxe_edit_term_meta_fields_callback', 10, 2);
    
    // Add term meta fields for fish_species taxonomy
    add_action('fish_species_add_form_fields', 'aqualuxe_add_term_meta_fields_callback');
    add_action('fish_species_edit_form_fields', 'aqualuxe_edit_term_meta_fields_callback', 10, 2);
    
    // Add term meta fields for water_type taxonomy
    add_action('water_type_add_form_fields', 'aqualuxe_add_term_meta_fields_callback');
    add_action('water_type_edit_form_fields', 'aqualuxe_edit_term_meta_fields_callback', 10, 2);
    
    // Save term meta
    add_action('created_term', 'aqualuxe_save_term_meta', 10, 3);
    add_action('edited_term', 'aqualuxe_save_term_meta', 10, 3);
}
add_action('admin_init', 'aqualuxe_add_term_meta_fields');

/**
 * Add term meta fields callback
 */
function aqualuxe_add_term_meta_fields_callback() {
    ?>
    <div class="form-field term-icon-wrap">
        <label for="term_icon"><?php esc_html_e('Icon (Font Awesome class or SVG code)', 'aqualuxe'); ?></label>
        <input type="text" id="term_icon" name="term_icon" value="" />
        <p class="description"><?php esc_html_e('Enter a Font Awesome class (e.g., fa-fish) or SVG code for the icon.', 'aqualuxe'); ?></p>
    </div>
    
    <div class="form-field term-image-wrap">
        <label for="term_image"><?php esc_html_e('Featured Image', 'aqualuxe'); ?></label>
        <input type="hidden" id="term_image" name="term_image" value="" />
        <div id="term_image_preview"></div>
        <button type="button" class="button" id="term_image_button"><?php esc_html_e('Select Image', 'aqualuxe'); ?></button>
        <button type="button" class="button" id="term_image_remove" style="display:none;"><?php esc_html_e('Remove Image', 'aqualuxe'); ?></button>
        <p class="description"><?php esc_html_e('Select a featured image for this term.', 'aqualuxe'); ?></p>
    </div>
    
    <div class="form-field term-color-wrap">
        <label for="term_color"><?php esc_html_e('Color', 'aqualuxe'); ?></label>
        <input type="text" id="term_color" name="term_color" value="#0077b6" class="color-picker" />
        <p class="description"><?php esc_html_e('Select a color for this term.', 'aqualuxe'); ?></p>
    </div>
    
    <div class="form-field term-featured-wrap">
        <label for="term_featured">
            <input type="checkbox" id="term_featured" name="term_featured" value="1" />
            <?php esc_html_e('Featured', 'aqualuxe'); ?>
        </label>
        <p class="description"><?php esc_html_e('Check to mark this term as featured.', 'aqualuxe'); ?></p>
    </div>
    
    <script>
    jQuery(document).ready(function($) {
        // Initialize color picker
        $('.color-picker').wpColorPicker();
        
        // Media uploader for term image
        $('#term_image_button').click(function(e) {
            e.preventDefault();
            
            var imageFrame = wp.media({
                title: '<?php esc_html_e('Select Image', 'aqualuxe'); ?>',
                multiple: false,
                library: {
                    type: 'image'
                },
                button: {
                    text: '<?php esc_html_e('Use This Image', 'aqualuxe'); ?>'
                }
            });
            
            imageFrame.on('select', function() {
                var attachment = imageFrame.state().get('selection').first().toJSON();
                $('#term_image').val(attachment.id);
                $('#term_image_preview').html('<img src="' + attachment.url + '" style="max-width:100%;height:auto;" />');
                $('#term_image_remove').show();
            });
            
            imageFrame.open();
        });
        
        // Remove image
        $('#term_image_remove').click(function(e) {
            e.preventDefault();
            $('#term_image').val('');
            $('#term_image_preview').html('');
            $(this).hide();
        });
    });
    </script>
    <?php
}

/**
 * Edit term meta fields callback
 *
 * @param WP_Term $term Term object.
 * @param string $taxonomy Taxonomy slug.
 */
function aqualuxe_edit_term_meta_fields_callback($term, $taxonomy) {
    $term_icon = get_term_meta($term->term_id, 'term_icon', true);
    $term_image = get_term_meta($term->term_id, 'term_image', true);
    $term_color = get_term_meta($term->term_id, 'term_color', true);
    $term_featured = get_term_meta($term->term_id, 'term_featured', true);
    
    if (empty($term_color)) {
        $term_color = '#0077b6';
    }
    ?>
    <tr class="form-field term-icon-wrap">
        <th scope="row"><label for="term_icon"><?php esc_html_e('Icon', 'aqualuxe'); ?></label></th>
        <td>
            <input type="text" id="term_icon" name="term_icon" value="<?php echo esc_attr($term_icon); ?>" />
            <p class="description"><?php esc_html_e('Enter a Font Awesome class (e.g., fa-fish) or SVG code for the icon.', 'aqualuxe'); ?></p>
        </td>
    </tr>
    
    <tr class="form-field term-image-wrap">
        <th scope="row"><label for="term_image"><?php esc_html_e('Featured Image', 'aqualuxe'); ?></label></th>
        <td>
            <input type="hidden" id="term_image" name="term_image" value="<?php echo esc_attr($term_image); ?>" />
            <div id="term_image_preview">
                <?php if ($term_image) : ?>
                    <?php echo wp_get_attachment_image($term_image, 'thumbnail'); ?>
                <?php endif; ?>
            </div>
            <button type="button" class="button" id="term_image_button"><?php esc_html_e('Select Image', 'aqualuxe'); ?></button>
            <button type="button" class="button" id="term_image_remove" <?php echo empty($term_image) ? 'style="display:none;"' : ''; ?>><?php esc_html_e('Remove Image', 'aqualuxe'); ?></button>
            <p class="description"><?php esc_html_e('Select a featured image for this term.', 'aqualuxe'); ?></p>
        </td>
    </tr>
    
    <tr class="form-field term-color-wrap">
        <th scope="row"><label for="term_color"><?php esc_html_e('Color', 'aqualuxe'); ?></label></th>
        <td>
            <input type="text" id="term_color" name="term_color" value="<?php echo esc_attr($term_color); ?>" class="color-picker" />
            <p class="description"><?php esc_html_e('Select a color for this term.', 'aqualuxe'); ?></p>
        </td>
    </tr>
    
    <tr class="form-field term-featured-wrap">
        <th scope="row"><label for="term_featured"><?php esc_html_e('Featured', 'aqualuxe'); ?></label></th>
        <td>
            <input type="checkbox" id="term_featured" name="term_featured" value="1" <?php checked($term_featured, '1'); ?> />
            <p class="description"><?php esc_html_e('Check to mark this term as featured.', 'aqualuxe'); ?></p>
        </td>
    </tr>
    
    <script>
    jQuery(document).ready(function($) {
        // Initialize color picker
        $('.color-picker').wpColorPicker();
        
        // Media uploader for term image
        $('#term_image_button').click(function(e) {
            e.preventDefault();
            
            var imageFrame = wp.media({
                title: '<?php esc_html_e('Select Image', 'aqualuxe'); ?>',
                multiple: false,
                library: {
                    type: 'image'
                },
                button: {
                    text: '<?php esc_html_e('Use This Image', 'aqualuxe'); ?>'
                }
            });
            
            imageFrame.on('select', function() {
                var attachment = imageFrame.state().get('selection').first().toJSON();
                $('#term_image').val(attachment.id);
                $('#term_image_preview').html('<img src="' + attachment.url + '" style="max-width:100%;height:auto;" />');
                $('#term_image_remove').show();
            });
            
            imageFrame.open();
        });
        
        // Remove image
        $('#term_image_remove').click(function(e) {
            e.preventDefault();
            $('#term_image').val('');
            $('#term_image_preview').html('');
            $(this).hide();
        });
    });
    </script>
    <?php
}

/**
 * Save term meta
 *
 * @param int $term_id Term ID.
 * @param int $tt_id Term taxonomy ID.
 * @param string $taxonomy Taxonomy slug.
 */
function aqualuxe_save_term_meta($term_id, $tt_id, $taxonomy) {
    if (isset($_POST['term_icon'])) {
        update_term_meta($term_id, 'term_icon', sanitize_text_field($_POST['term_icon']));
    }
    
    if (isset($_POST['term_image'])) {
        update_term_meta($term_id, 'term_image', absint($_POST['term_image']));
    }
    
    if (isset($_POST['term_color'])) {
        update_term_meta($term_id, 'term_color', sanitize_hex_color($_POST['term_color']));
    }
    
    update_term_meta($term_id, 'term_featured', isset($_POST['term_featured']) ? '1' : '');
}

/**
 * Add term meta columns
 */
function aqualuxe_add_term_meta_columns() {
    // Add columns to service_category taxonomy
    add_filter('manage_edit-service_category_columns', 'aqualuxe_term_meta_columns');
    add_filter('manage_service_category_custom_column', 'aqualuxe_term_meta_custom_column', 10, 3);
    
    // Add columns to project_category taxonomy
    add_filter('manage_edit-project_category_columns', 'aqualuxe_term_meta_columns');
    add_filter('manage_project_category_custom_column', 'aqualuxe_term_meta_custom_column', 10, 3);
    
    // Add columns to event_category taxonomy
    add_filter('manage_edit-event_category_columns', 'aqualuxe_term_meta_columns');
    add_filter('manage_event_category_custom_column', 'aqualuxe_term_meta_custom_column', 10, 3);
    
    // Add columns to team_department taxonomy
    add_filter('manage_edit-team_department_columns', 'aqualuxe_term_meta_columns');
    add_filter('manage_team_department_custom_column', 'aqualuxe_term_meta_custom_column', 10, 3);
    
    // Add columns to faq_category taxonomy
    add_filter('manage_edit-faq_category_columns', 'aqualuxe_term_meta_columns');
    add_filter('manage_faq_category_custom_column', 'aqualuxe_term_meta_custom_column', 10, 3);
    
    // Add columns to location_region taxonomy
    add_filter('manage_edit-location_region_columns', 'aqualuxe_term_meta_columns');
    add_filter('manage_location_region_custom_column', 'aqualuxe_term_meta_custom_column', 10, 3);
    
    // Add columns to fish_species taxonomy
    add_filter('manage_edit-fish_species_columns', 'aqualuxe_term_meta_columns');
    add_filter('manage_fish_species_custom_column', 'aqualuxe_term_meta_custom_column', 10, 3);
    
    // Add columns to water_type taxonomy
    add_filter('manage_edit-water_type_columns', 'aqualuxe_term_meta_columns');
    add_filter('manage_water_type_custom_column', 'aqualuxe_term_meta_custom_column', 10, 3);
}
add_action('admin_init', 'aqualuxe_add_term_meta_columns');

/**
 * Define term meta columns
 *
 * @param array $columns Array of columns.
 * @return array Modified array of columns.
 */
function aqualuxe_term_meta_columns($columns) {
    $new_columns = array();
    
    foreach ($columns as $key => $value) {
        $new_columns[$key] = $value;
        
        if ('description' === $key) {
            $new_columns['icon'] = __('Icon', 'aqualuxe');
            $new_columns['image'] = __('Image', 'aqualuxe');
            $new_columns['color'] = __('Color', 'aqualuxe');
            $new_columns['featured'] = __('Featured', 'aqualuxe');
        }
    }
    
    return $new_columns;
}

/**
 * Display term meta custom column content
 *
 * @param string $content Column content.
 * @param string $column Column name.
 * @param int $term_id Term ID.
 * @return string Modified column content.
 */
function aqualuxe_term_meta_custom_column($content, $column, $term_id) {
    if ('icon' === $column) {
        $term_icon = get_term_meta($term_id, 'term_icon', true);
        if ($term_icon) {
            if (strpos($term_icon, '<svg') !== false) {
                $content = $term_icon;
            } else {
                $content = '<i class="' . esc_attr($term_icon) . '"></i>';
            }
        }
    }
    
    if ('image' === $column) {
        $term_image = get_term_meta($term_id, 'term_image', true);
        if ($term_image) {
            $content = wp_get_attachment_image($term_image, array(50, 50));
        }
    }
    
    if ('color' === $column) {
        $term_color = get_term_meta($term_id, 'term_color', true);
        if ($term_color) {
            $content = '<span style="display:inline-block;width:20px;height:20px;background-color:' . esc_attr($term_color) . ';border-radius:50%;"></span>';
        }
    }
    
    if ('featured' === $column) {
        $term_featured = get_term_meta($term_id, 'term_featured', true);
        $content = $term_featured ? '✓' : '–';
    }
    
    return $content;
}

/**
 * Flush rewrite rules on theme activation
 */
function aqualuxe_taxonomies_rewrite_flush() {
    aqualuxe_register_taxonomies();
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'aqualuxe_taxonomies_rewrite_flush');