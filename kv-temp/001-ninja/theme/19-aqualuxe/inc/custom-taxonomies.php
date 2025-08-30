<?php
/**
 * Custom Taxonomies for AquaLuxe theme
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register custom taxonomies
 */
function aqualuxe_register_custom_taxonomies() {
    // Testimonial Category taxonomy
    $testimonial_cat_labels = [
        'name'              => _x('Testimonial Categories', 'taxonomy general name', 'aqualuxe'),
        'singular_name'     => _x('Testimonial Category', 'taxonomy singular name', 'aqualuxe'),
        'search_items'      => __('Search Testimonial Categories', 'aqualuxe'),
        'all_items'         => __('All Testimonial Categories', 'aqualuxe'),
        'parent_item'       => __('Parent Testimonial Category', 'aqualuxe'),
        'parent_item_colon' => __('Parent Testimonial Category:', 'aqualuxe'),
        'edit_item'         => __('Edit Testimonial Category', 'aqualuxe'),
        'update_item'       => __('Update Testimonial Category', 'aqualuxe'),
        'add_new_item'      => __('Add New Testimonial Category', 'aqualuxe'),
        'new_item_name'     => __('New Testimonial Category Name', 'aqualuxe'),
        'menu_name'         => __('Categories', 'aqualuxe'),
    ];

    $testimonial_cat_args = [
        'hierarchical'      => true,
        'labels'            => $testimonial_cat_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => ['slug' => 'testimonial-category'],
        'show_in_rest'      => true,
    ];

    register_taxonomy('testimonial_category', ['testimonial'], $testimonial_cat_args);

    // Team Department taxonomy
    $team_dept_labels = [
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
    ];

    $team_dept_args = [
        'hierarchical'      => true,
        'labels'            => $team_dept_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => ['slug' => 'department'],
        'show_in_rest'      => true,
    ];

    register_taxonomy('department', ['team'], $team_dept_args);

    // Service Category taxonomy
    $service_cat_labels = [
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
    ];

    $service_cat_args = [
        'hierarchical'      => true,
        'labels'            => $service_cat_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => ['slug' => 'service-category'],
        'show_in_rest'      => true,
    ];

    register_taxonomy('service_category', ['service'], $service_cat_args);

    // Project Category taxonomy
    $project_cat_labels = [
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
    ];

    $project_cat_args = [
        'hierarchical'      => true,
        'labels'            => $project_cat_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => ['slug' => 'project-category'],
        'show_in_rest'      => true,
    ];

    register_taxonomy('project_category', ['project'], $project_cat_args);

    // FAQ Category taxonomy
    $faq_cat_labels = [
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
    ];

    $faq_cat_args = [
        'hierarchical'      => true,
        'labels'            => $faq_cat_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => ['slug' => 'faq-category'],
        'show_in_rest'      => true,
    ];

    register_taxonomy('faq_category', ['faq'], $faq_cat_args);

    // Fish Species Category taxonomy
    $fish_cat_labels = [
        'name'              => _x('Fish Categories', 'taxonomy general name', 'aqualuxe'),
        'singular_name'     => _x('Fish Category', 'taxonomy singular name', 'aqualuxe'),
        'search_items'      => __('Search Fish Categories', 'aqualuxe'),
        'all_items'         => __('All Fish Categories', 'aqualuxe'),
        'parent_item'       => __('Parent Fish Category', 'aqualuxe'),
        'parent_item_colon' => __('Parent Fish Category:', 'aqualuxe'),
        'edit_item'         => __('Edit Fish Category', 'aqualuxe'),
        'update_item'       => __('Update Fish Category', 'aqualuxe'),
        'add_new_item'      => __('Add New Fish Category', 'aqualuxe'),
        'new_item_name'     => __('New Fish Category Name', 'aqualuxe'),
        'menu_name'         => __('Categories', 'aqualuxe'),
    ];

    $fish_cat_args = [
        'hierarchical'      => true,
        'labels'            => $fish_cat_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => ['slug' => 'fish-category'],
        'show_in_rest'      => true,
    ];

    register_taxonomy('fish_category', ['fish-species'], $fish_cat_args);

    // Fish Water Type taxonomy
    $water_type_labels = [
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
    ];

    $water_type_args = [
        'hierarchical'      => true,
        'labels'            => $water_type_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => ['slug' => 'water-type'],
        'show_in_rest'      => true,
    ];

    register_taxonomy('water_type', ['fish-species'], $water_type_args);

    // Fish Origin taxonomy
    $fish_origin_labels = [
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
    ];

    $fish_origin_args = [
        'hierarchical'      => true,
        'labels'            => $fish_origin_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => ['slug' => 'fish-origin'],
        'show_in_rest'      => true,
    ];

    register_taxonomy('fish_origin', ['fish-species'], $fish_origin_args);

    // Aquarium Plant Category taxonomy
    $plant_cat_labels = [
        'name'              => _x('Plant Categories', 'taxonomy general name', 'aqualuxe'),
        'singular_name'     => _x('Plant Category', 'taxonomy singular name', 'aqualuxe'),
        'search_items'      => __('Search Plant Categories', 'aqualuxe'),
        'all_items'         => __('All Plant Categories', 'aqualuxe'),
        'parent_item'       => __('Parent Plant Category', 'aqualuxe'),
        'parent_item_colon' => __('Parent Plant Category:', 'aqualuxe'),
        'edit_item'         => __('Edit Plant Category', 'aqualuxe'),
        'update_item'       => __('Update Plant Category', 'aqualuxe'),
        'add_new_item'      => __('Add New Plant Category', 'aqualuxe'),
        'new_item_name'     => __('New Plant Category Name', 'aqualuxe'),
        'menu_name'         => __('Categories', 'aqualuxe'),
    ];

    $plant_cat_args = [
        'hierarchical'      => true,
        'labels'            => $plant_cat_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => ['slug' => 'plant-category'],
        'show_in_rest'      => true,
    ];

    register_taxonomy('plant_category', ['aquarium-plant'], $plant_cat_args);

    // Plant Difficulty taxonomy
    $plant_difficulty_labels = [
        'name'              => _x('Difficulty Levels', 'taxonomy general name', 'aqualuxe'),
        'singular_name'     => _x('Difficulty Level', 'taxonomy singular name', 'aqualuxe'),
        'search_items'      => __('Search Difficulty Levels', 'aqualuxe'),
        'all_items'         => __('All Difficulty Levels', 'aqualuxe'),
        'parent_item'       => __('Parent Difficulty Level', 'aqualuxe'),
        'parent_item_colon' => __('Parent Difficulty Level:', 'aqualuxe'),
        'edit_item'         => __('Edit Difficulty Level', 'aqualuxe'),
        'update_item'       => __('Update Difficulty Level', 'aqualuxe'),
        'add_new_item'      => __('Add New Difficulty Level', 'aqualuxe'),
        'new_item_name'     => __('New Difficulty Level Name', 'aqualuxe'),
        'menu_name'         => __('Difficulty Levels', 'aqualuxe'),
    ];

    $plant_difficulty_args = [
        'hierarchical'      => true,
        'labels'            => $plant_difficulty_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => ['slug' => 'plant-difficulty'],
        'show_in_rest'      => true,
    ];

    register_taxonomy('plant_difficulty', ['aquarium-plant'], $plant_difficulty_args);

    // Plant Growth Rate taxonomy
    $plant_growth_labels = [
        'name'              => _x('Growth Rates', 'taxonomy general name', 'aqualuxe'),
        'singular_name'     => _x('Growth Rate', 'taxonomy singular name', 'aqualuxe'),
        'search_items'      => __('Search Growth Rates', 'aqualuxe'),
        'all_items'         => __('All Growth Rates', 'aqualuxe'),
        'parent_item'       => __('Parent Growth Rate', 'aqualuxe'),
        'parent_item_colon' => __('Parent Growth Rate:', 'aqualuxe'),
        'edit_item'         => __('Edit Growth Rate', 'aqualuxe'),
        'update_item'       => __('Update Growth Rate', 'aqualuxe'),
        'add_new_item'      => __('Add New Growth Rate', 'aqualuxe'),
        'new_item_name'     => __('New Growth Rate Name', 'aqualuxe'),
        'menu_name'         => __('Growth Rates', 'aqualuxe'),
    ];

    $plant_growth_args = [
        'hierarchical'      => true,
        'labels'            => $plant_growth_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => ['slug' => 'growth-rate'],
        'show_in_rest'      => true,
    ];

    register_taxonomy('growth_rate', ['aquarium-plant'], $plant_growth_args);

    // Care Guide Category taxonomy
    $guide_cat_labels = [
        'name'              => _x('Guide Categories', 'taxonomy general name', 'aqualuxe'),
        'singular_name'     => _x('Guide Category', 'taxonomy singular name', 'aqualuxe'),
        'search_items'      => __('Search Guide Categories', 'aqualuxe'),
        'all_items'         => __('All Guide Categories', 'aqualuxe'),
        'parent_item'       => __('Parent Guide Category', 'aqualuxe'),
        'parent_item_colon' => __('Parent Guide Category:', 'aqualuxe'),
        'edit_item'         => __('Edit Guide Category', 'aqualuxe'),
        'update_item'       => __('Update Guide Category', 'aqualuxe'),
        'add_new_item'      => __('Add New Guide Category', 'aqualuxe'),
        'new_item_name'     => __('New Guide Category Name', 'aqualuxe'),
        'menu_name'         => __('Categories', 'aqualuxe'),
    ];

    $guide_cat_args = [
        'hierarchical'      => true,
        'labels'            => $guide_cat_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => ['slug' => 'guide-category'],
        'show_in_rest'      => true,
    ];

    register_taxonomy('guide_category', ['care-guide'], $guide_cat_args);

    // Event Category taxonomy
    $event_cat_labels = [
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
    ];

    $event_cat_args = [
        'hierarchical'      => true,
        'labels'            => $event_cat_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => ['slug' => 'event-category'],
        'show_in_rest'      => true,
    ];

    register_taxonomy('event_category', ['event'], $event_cat_args);

    // Event Location taxonomy
    $event_location_labels = [
        'name'              => _x('Event Locations', 'taxonomy general name', 'aqualuxe'),
        'singular_name'     => _x('Event Location', 'taxonomy singular name', 'aqualuxe'),
        'search_items'      => __('Search Event Locations', 'aqualuxe'),
        'all_items'         => __('All Event Locations', 'aqualuxe'),
        'parent_item'       => __('Parent Event Location', 'aqualuxe'),
        'parent_item_colon' => __('Parent Event Location:', 'aqualuxe'),
        'edit_item'         => __('Edit Event Location', 'aqualuxe'),
        'update_item'       => __('Update Event Location', 'aqualuxe'),
        'add_new_item'      => __('Add New Event Location', 'aqualuxe'),
        'new_item_name'     => __('New Event Location Name', 'aqualuxe'),
        'menu_name'         => __('Locations', 'aqualuxe'),
    ];

    $event_location_args = [
        'hierarchical'      => true,
        'labels'            => $event_location_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => ['slug' => 'event-location'],
        'show_in_rest'      => true,
    ];

    register_taxonomy('event_location', ['event'], $event_location_args);

    // If WooCommerce is active, register product taxonomies
    if (aqualuxe_is_woocommerce_active()) {
        // Product Brand taxonomy
        $brand_labels = [
            'name'              => _x('Brands', 'taxonomy general name', 'aqualuxe'),
            'singular_name'     => _x('Brand', 'taxonomy singular name', 'aqualuxe'),
            'search_items'      => __('Search Brands', 'aqualuxe'),
            'all_items'         => __('All Brands', 'aqualuxe'),
            'parent_item'       => __('Parent Brand', 'aqualuxe'),
            'parent_item_colon' => __('Parent Brand:', 'aqualuxe'),
            'edit_item'         => __('Edit Brand', 'aqualuxe'),
            'update_item'       => __('Update Brand', 'aqualuxe'),
            'add_new_item'      => __('Add New Brand', 'aqualuxe'),
            'new_item_name'     => __('New Brand Name', 'aqualuxe'),
            'menu_name'         => __('Brands', 'aqualuxe'),
        ];

        $brand_args = [
            'hierarchical'      => true,
            'labels'            => $brand_labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => ['slug' => 'brand'],
            'show_in_rest'      => true,
        ];

        register_taxonomy('product_brand', ['product'], $brand_args);

        // Product Compatibility taxonomy
        $compatibility_labels = [
            'name'              => _x('Compatibilities', 'taxonomy general name', 'aqualuxe'),
            'singular_name'     => _x('Compatibility', 'taxonomy singular name', 'aqualuxe'),
            'search_items'      => __('Search Compatibilities', 'aqualuxe'),
            'all_items'         => __('All Compatibilities', 'aqualuxe'),
            'parent_item'       => __('Parent Compatibility', 'aqualuxe'),
            'parent_item_colon' => __('Parent Compatibility:', 'aqualuxe'),
            'edit_item'         => __('Edit Compatibility', 'aqualuxe'),
            'update_item'       => __('Update Compatibility', 'aqualuxe'),
            'add_new_item'      => __('Add New Compatibility', 'aqualuxe'),
            'new_item_name'     => __('New Compatibility Name', 'aqualuxe'),
            'menu_name'         => __('Compatibilities', 'aqualuxe'),
        ];

        $compatibility_args = [
            'hierarchical'      => true,
            'labels'            => $compatibility_labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => ['slug' => 'compatibility'],
            'show_in_rest'      => true,
        ];

        register_taxonomy('product_compatibility', ['product'], $compatibility_args);

        // Product Tank Size taxonomy
        $tank_size_labels = [
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
        ];

        $tank_size_args = [
            'hierarchical'      => true,
            'labels'            => $tank_size_labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => ['slug' => 'tank-size'],
            'show_in_rest'      => true,
        ];

        register_taxonomy('tank_size', ['product'], $tank_size_args);
    }

    // Flush rewrite rules
    flush_rewrite_rules();
}
add_action('init', 'aqualuxe_register_custom_taxonomies');

/**
 * Add custom taxonomy columns
 */
function aqualuxe_add_taxonomy_columns() {
    // Add thumbnail column to product_brand taxonomy
    add_filter('manage_edit-product_brand_columns', 'aqualuxe_add_thumbnail_column');
    add_filter('manage_product_brand_custom_column', 'aqualuxe_thumbnail_column_content', 10, 3);
    
    // Add thumbnail column to fish_category taxonomy
    add_filter('manage_edit-fish_category_columns', 'aqualuxe_add_thumbnail_column');
    add_filter('manage_fish_category_custom_column', 'aqualuxe_thumbnail_column_content', 10, 3);
    
    // Add thumbnail column to plant_category taxonomy
    add_filter('manage_edit-plant_category_columns', 'aqualuxe_add_thumbnail_column');
    add_filter('manage_plant_category_custom_column', 'aqualuxe_thumbnail_column_content', 10, 3);
}
add_action('admin_init', 'aqualuxe_add_taxonomy_columns');

/**
 * Add thumbnail column to taxonomy
 *
 * @param array $columns Columns
 * @return array
 */
function aqualuxe_add_thumbnail_column($columns) {
    $new_columns = [];
    
    foreach ($columns as $key => $value) {
        if ($key === 'name') {
            $new_columns['thumbnail'] = __('Thumbnail', 'aqualuxe');
        }
        
        $new_columns[$key] = $value;
    }
    
    return $new_columns;
}

/**
 * Add thumbnail column content
 *
 * @param string $content Column content
 * @param string $column_name Column name
 * @param int $term_id Term ID
 * @return string
 */
function aqualuxe_thumbnail_column_content($content, $column_name, $term_id) {
    if ($column_name === 'thumbnail') {
        $thumbnail_id = get_term_meta($term_id, 'thumbnail_id', true);
        
        if ($thumbnail_id) {
            $content = wp_get_attachment_image($thumbnail_id, [50, 50]);
        }
    }
    
    return $content;
}

/**
 * Add thumbnail field to taxonomy
 */
function aqualuxe_add_taxonomy_thumbnail_field() {
    // Add thumbnail field to product_brand taxonomy
    add_action('product_brand_add_form_fields', 'aqualuxe_add_thumbnail_field');
    add_action('product_brand_edit_form_fields', 'aqualuxe_edit_thumbnail_field', 10, 2);
    
    // Add thumbnail field to fish_category taxonomy
    add_action('fish_category_add_form_fields', 'aqualuxe_add_thumbnail_field');
    add_action('fish_category_edit_form_fields', 'aqualuxe_edit_thumbnail_field', 10, 2);
    
    // Add thumbnail field to plant_category taxonomy
    add_action('plant_category_add_form_fields', 'aqualuxe_add_thumbnail_field');
    add_action('plant_category_edit_form_fields', 'aqualuxe_edit_thumbnail_field', 10, 2);
    
    // Save thumbnail field
    add_action('created_product_brand', 'aqualuxe_save_thumbnail_field', 10, 2);
    add_action('edited_product_brand', 'aqualuxe_save_thumbnail_field', 10, 2);
    add_action('created_fish_category', 'aqualuxe_save_thumbnail_field', 10, 2);
    add_action('edited_fish_category', 'aqualuxe_save_thumbnail_field', 10, 2);
    add_action('created_plant_category', 'aqualuxe_save_thumbnail_field', 10, 2);
    add_action('edited_plant_category', 'aqualuxe_save_thumbnail_field', 10, 2);
}
add_action('admin_init', 'aqualuxe_add_taxonomy_thumbnail_field');

/**
 * Add thumbnail field to taxonomy
 */
function aqualuxe_add_thumbnail_field() {
    ?>
    <div class="form-field term-thumbnail-wrap">
        <label for="thumbnail_id"><?php esc_html_e('Thumbnail', 'aqualuxe'); ?></label>
        <div id="thumbnail" style="float: left; margin-right: 10px;">
            <img src="<?php echo esc_url(wc_placeholder_img_src()); ?>" width="60px" height="60px" />
        </div>
        <div style="line-height: 60px;">
            <input type="hidden" id="thumbnail_id" name="thumbnail_id" />
            <button type="button" class="upload_image_button button"><?php esc_html_e('Upload/Add image', 'aqualuxe'); ?></button>
            <button type="button" class="remove_image_button button"><?php esc_html_e('Remove image', 'aqualuxe'); ?></button>
        </div>
        <script type="text/javascript">
            // Only show the "remove image" button when needed
            if (!jQuery('#thumbnail_id').val()) {
                jQuery('.remove_image_button').hide();
            }

            // Uploading files
            var file_frame;

            jQuery(document).on('click', '.upload_image_button', function(event) {
                event.preventDefault();

                // If the media frame already exists, reopen it.
                if (file_frame) {
                    file_frame.open();
                    return;
                }

                // Create the media frame.
                file_frame = wp.media.frames.downloadable_file = wp.media({
                    title: '<?php esc_html_e('Choose an image', 'aqualuxe'); ?>',
                    button: {
                        text: '<?php esc_html_e('Use image', 'aqualuxe'); ?>'
                    },
                    multiple: false
                });

                // When an image is selected, run a callback.
                file_frame.on('select', function() {
                    var attachment = file_frame.state().get('selection').first().toJSON();
                    jQuery('#thumbnail_id').val(attachment.id);
                    jQuery('#thumbnail').find('img').attr('src', attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url);
                    jQuery('.remove_image_button').show();
                });

                // Finally, open the modal.
                file_frame.open();
            });

            jQuery(document).on('click', '.remove_image_button', function() {
                jQuery('#thumbnail').find('img').attr('src', '<?php echo esc_js(wc_placeholder_img_src()); ?>');
                jQuery('#thumbnail_id').val('');
                jQuery('.remove_image_button').hide();
                return false;
            });
        </script>
        <div class="clear"></div>
    </div>
    <?php
}

/**
 * Edit thumbnail field for taxonomy
 *
 * @param object $term Term object
 * @param string $taxonomy Taxonomy name
 */
function aqualuxe_edit_thumbnail_field($term, $taxonomy) {
    $thumbnail_id = get_term_meta($term->term_id, 'thumbnail_id', true);
    $image = $thumbnail_id ? wp_get_attachment_thumb_url($thumbnail_id) : wc_placeholder_img_src();
    ?>
    <tr class="form-field term-thumbnail-wrap">
        <th scope="row" valign="top"><label><?php esc_html_e('Thumbnail', 'aqualuxe'); ?></label></th>
        <td>
            <div id="thumbnail" style="float: left; margin-right: 10px;">
                <img src="<?php echo esc_url($image); ?>" width="60px" height="60px" />
            </div>
            <div style="line-height: 60px;">
                <input type="hidden" id="thumbnail_id" name="thumbnail_id" value="<?php echo esc_attr($thumbnail_id); ?>" />
                <button type="button" class="upload_image_button button"><?php esc_html_e('Upload/Add image', 'aqualuxe'); ?></button>
                <button type="button" class="remove_image_button button"><?php esc_html_e('Remove image', 'aqualuxe'); ?></button>
            </div>
            <script type="text/javascript">
                // Only show the "remove image" button when needed
                if (!jQuery('#thumbnail_id').val()) {
                    jQuery('.remove_image_button').hide();
                }

                // Uploading files
                var file_frame;

                jQuery(document).on('click', '.upload_image_button', function(event) {
                    event.preventDefault();

                    // If the media frame already exists, reopen it.
                    if (file_frame) {
                        file_frame.open();
                        return;
                    }

                    // Create the media frame.
                    file_frame = wp.media.frames.downloadable_file = wp.media({
                        title: '<?php esc_html_e('Choose an image', 'aqualuxe'); ?>',
                        button: {
                            text: '<?php esc_html_e('Use image', 'aqualuxe'); ?>'
                        },
                        multiple: false
                    });

                    // When an image is selected, run a callback.
                    file_frame.on('select', function() {
                        var attachment = file_frame.state().get('selection').first().toJSON();
                        jQuery('#thumbnail_id').val(attachment.id);
                        jQuery('#thumbnail').find('img').attr('src', attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url);
                        jQuery('.remove_image_button').show();
                    });

                    // Finally, open the modal.
                    file_frame.open();
                });

                jQuery(document).on('click', '.remove_image_button', function() {
                    jQuery('#thumbnail').find('img').attr('src', '<?php echo esc_js(wc_placeholder_img_src()); ?>');
                    jQuery('#thumbnail_id').val('');
                    jQuery('.remove_image_button').hide();
                    return false;
                });
            </script>
            <div class="clear"></div>
        </td>
    </tr>
    <?php
}

/**
 * Save thumbnail field for taxonomy
 *
 * @param int $term_id Term ID
 * @param int $tt_id Term taxonomy ID
 */
function aqualuxe_save_thumbnail_field($term_id, $tt_id) {
    if (isset($_POST['thumbnail_id'])) {
        update_term_meta($term_id, 'thumbnail_id', absint($_POST['thumbnail_id']));
    }
}