<?php
/**
 * Custom Taxonomies
 * 
 * @package AquaLuxe
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register Custom Taxonomies
 */
function aqualuxe_register_taxonomies()
{
    // Service Categories
    register_taxonomy('aqualuxe_service_category', ['aqualuxe_service'], [
        'labels' => [
            'name' => esc_html_x('Service Categories', 'taxonomy general name', 'aqualuxe'),
            'singular_name' => esc_html_x('Service Category', 'taxonomy singular name', 'aqualuxe'),
            'search_items' => esc_html__('Search Service Categories', 'aqualuxe'),
            'all_items' => esc_html__('All Service Categories', 'aqualuxe'),
            'parent_item' => esc_html__('Parent Service Category', 'aqualuxe'),
            'parent_item_colon' => esc_html__('Parent Service Category:', 'aqualuxe'),
            'edit_item' => esc_html__('Edit Service Category', 'aqualuxe'),
            'update_item' => esc_html__('Update Service Category', 'aqualuxe'),
            'add_new_item' => esc_html__('Add New Service Category', 'aqualuxe'),
            'new_item_name' => esc_html__('New Service Category Name', 'aqualuxe'),
            'menu_name' => esc_html__('Service Categories', 'aqualuxe'),
        ],
        'hierarchical' => true,
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'show_in_rest' => true,
        'query_var' => true,
        'rewrite' => ['slug' => 'service-category'],
    ]);

    // Project Categories
    register_taxonomy('aqualuxe_project_category', ['aqualuxe_project'], [
        'labels' => [
            'name' => esc_html_x('Project Categories', 'taxonomy general name', 'aqualuxe'),
            'singular_name' => esc_html_x('Project Category', 'taxonomy singular name', 'aqualuxe'),
            'search_items' => esc_html__('Search Project Categories', 'aqualuxe'),
            'all_items' => esc_html__('All Project Categories', 'aqualuxe'),
            'parent_item' => esc_html__('Parent Project Category', 'aqualuxe'),
            'parent_item_colon' => esc_html__('Parent Project Category:', 'aqualuxe'),
            'edit_item' => esc_html__('Edit Project Category', 'aqualuxe'),
            'update_item' => esc_html__('Update Project Category', 'aqualuxe'),
            'add_new_item' => esc_html__('Add New Project Category', 'aqualuxe'),
            'new_item_name' => esc_html__('New Project Category Name', 'aqualuxe'),
            'menu_name' => esc_html__('Project Categories', 'aqualuxe'),
        ],
        'hierarchical' => true,
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'show_in_rest' => true,
        'query_var' => true,
        'rewrite' => ['slug' => 'project-category'],
    ]);

    // Project Tags
    register_taxonomy('aqualuxe_project_tag', ['aqualuxe_project'], [
        'labels' => [
            'name' => esc_html_x('Project Tags', 'taxonomy general name', 'aqualuxe'),
            'singular_name' => esc_html_x('Project Tag', 'taxonomy singular name', 'aqualuxe'),
            'search_items' => esc_html__('Search Project Tags', 'aqualuxe'),
            'popular_items' => esc_html__('Popular Project Tags', 'aqualuxe'),
            'all_items' => esc_html__('All Project Tags', 'aqualuxe'),
            'edit_item' => esc_html__('Edit Project Tag', 'aqualuxe'),
            'update_item' => esc_html__('Update Project Tag', 'aqualuxe'),
            'add_new_item' => esc_html__('Add New Project Tag', 'aqualuxe'),
            'new_item_name' => esc_html__('New Project Tag Name', 'aqualuxe'),
            'separate_items_with_commas' => esc_html__('Separate project tags with commas', 'aqualuxe'),
            'add_or_remove_items' => esc_html__('Add or remove project tags', 'aqualuxe'),
            'choose_from_most_used' => esc_html__('Choose from the most used project tags', 'aqualuxe'),
            'menu_name' => esc_html__('Project Tags', 'aqualuxe'),
        ],
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'show_in_rest' => true,
        'query_var' => true,
        'rewrite' => ['slug' => 'project-tag'],
    ]);

    // Event Categories
    register_taxonomy('aqualuxe_event_category', ['aqualuxe_event'], [
        'labels' => [
            'name' => esc_html_x('Event Categories', 'taxonomy general name', 'aqualuxe'),
            'singular_name' => esc_html_x('Event Category', 'taxonomy singular name', 'aqualuxe'),
            'search_items' => esc_html__('Search Event Categories', 'aqualuxe'),
            'all_items' => esc_html__('All Event Categories', 'aqualuxe'),
            'parent_item' => esc_html__('Parent Event Category', 'aqualuxe'),
            'parent_item_colon' => esc_html__('Parent Event Category:', 'aqualuxe'),
            'edit_item' => esc_html__('Edit Event Category', 'aqualuxe'),
            'update_item' => esc_html__('Update Event Category', 'aqualuxe'),
            'add_new_item' => esc_html__('Add New Event Category', 'aqualuxe'),
            'new_item_name' => esc_html__('New Event Category Name', 'aqualuxe'),
            'menu_name' => esc_html__('Event Categories', 'aqualuxe'),
        ],
        'hierarchical' => true,
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'show_in_rest' => true,
        'query_var' => true,
        'rewrite' => ['slug' => 'event-category'],
    ]);

    // FAQ Categories
    register_taxonomy('aqualuxe_faq_category', ['aqualuxe_faq'], [
        'labels' => [
            'name' => esc_html_x('FAQ Categories', 'taxonomy general name', 'aqualuxe'),
            'singular_name' => esc_html_x('FAQ Category', 'taxonomy singular name', 'aqualuxe'),
            'search_items' => esc_html__('Search FAQ Categories', 'aqualuxe'),
            'all_items' => esc_html__('All FAQ Categories', 'aqualuxe'),
            'parent_item' => esc_html__('Parent FAQ Category', 'aqualuxe'),
            'parent_item_colon' => esc_html__('Parent FAQ Category:', 'aqualuxe'),
            'edit_item' => esc_html__('Edit FAQ Category', 'aqualuxe'),
            'update_item' => esc_html__('Update FAQ Category', 'aqualuxe'),
            'add_new_item' => esc_html__('Add New FAQ Category', 'aqualuxe'),
            'new_item_name' => esc_html__('New FAQ Category Name', 'aqualuxe'),
            'menu_name' => esc_html__('FAQ Categories', 'aqualuxe'),
        ],
        'hierarchical' => true,
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'show_in_rest' => true,
        'query_var' => true,
        'rewrite' => ['slug' => 'faq-category'],
    ]);

    // Team Departments
    register_taxonomy('aqualuxe_team_department', ['aqualuxe_team'], [
        'labels' => [
            'name' => esc_html_x('Departments', 'taxonomy general name', 'aqualuxe'),
            'singular_name' => esc_html_x('Department', 'taxonomy singular name', 'aqualuxe'),
            'search_items' => esc_html__('Search Departments', 'aqualuxe'),
            'all_items' => esc_html__('All Departments', 'aqualuxe'),
            'parent_item' => esc_html__('Parent Department', 'aqualuxe'),
            'parent_item_colon' => esc_html__('Parent Department:', 'aqualuxe'),
            'edit_item' => esc_html__('Edit Department', 'aqualuxe'),
            'update_item' => esc_html__('Update Department', 'aqualuxe'),
            'add_new_item' => esc_html__('Add New Department', 'aqualuxe'),
            'new_item_name' => esc_html__('New Department Name', 'aqualuxe'),
            'menu_name' => esc_html__('Departments', 'aqualuxe'),
        ],
        'hierarchical' => true,
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'show_in_rest' => true,
        'query_var' => true,
        'rewrite' => ['slug' => 'department'],
    ]);

    // Location Taxonomy (for services, events, projects)
    register_taxonomy('aqualuxe_location', ['aqualuxe_service', 'aqualuxe_event', 'aqualuxe_project'], [
        'labels' => [
            'name' => esc_html_x('Locations', 'taxonomy general name', 'aqualuxe'),
            'singular_name' => esc_html_x('Location', 'taxonomy singular name', 'aqualuxe'),
            'search_items' => esc_html__('Search Locations', 'aqualuxe'),
            'all_items' => esc_html__('All Locations', 'aqualuxe'),
            'parent_item' => esc_html__('Parent Location', 'aqualuxe'),
            'parent_item_colon' => esc_html__('Parent Location:', 'aqualuxe'),
            'edit_item' => esc_html__('Edit Location', 'aqualuxe'),
            'update_item' => esc_html__('Update Location', 'aqualuxe'),
            'add_new_item' => esc_html__('Add New Location', 'aqualuxe'),
            'new_item_name' => esc_html__('New Location Name', 'aqualuxe'),
            'menu_name' => esc_html__('Locations', 'aqualuxe'),
        ],
        'hierarchical' => true,
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'show_in_rest' => true,
        'query_var' => true,
        'rewrite' => ['slug' => 'location'],
    ]);

    // Difficulty Level (for services and projects)
    register_taxonomy('aqualuxe_difficulty', ['aqualuxe_service', 'aqualuxe_project'], [
        'labels' => [
            'name' => esc_html_x('Difficulty Levels', 'taxonomy general name', 'aqualuxe'),
            'singular_name' => esc_html_x('Difficulty Level', 'taxonomy singular name', 'aqualuxe'),
            'search_items' => esc_html__('Search Difficulty Levels', 'aqualuxe'),
            'all_items' => esc_html__('All Difficulty Levels', 'aqualuxe'),
            'edit_item' => esc_html__('Edit Difficulty Level', 'aqualuxe'),
            'update_item' => esc_html__('Update Difficulty Level', 'aqualuxe'),
            'add_new_item' => esc_html__('Add New Difficulty Level', 'aqualuxe'),
            'new_item_name' => esc_html__('New Difficulty Level Name', 'aqualuxe'),
            'menu_name' => esc_html__('Difficulty Levels', 'aqualuxe'),
        ],
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'show_in_rest' => true,
        'query_var' => true,
        'rewrite' => ['slug' => 'difficulty'],
    ]);

    // Features Taxonomy (for highlighting special features)
    register_taxonomy('aqualuxe_feature', ['aqualuxe_service', 'aqualuxe_project'], [
        'labels' => [
            'name' => esc_html_x('Features', 'taxonomy general name', 'aqualuxe'),
            'singular_name' => esc_html_x('Feature', 'taxonomy singular name', 'aqualuxe'),
            'search_items' => esc_html__('Search Features', 'aqualuxe'),
            'popular_items' => esc_html__('Popular Features', 'aqualuxe'),
            'all_items' => esc_html__('All Features', 'aqualuxe'),
            'edit_item' => esc_html__('Edit Feature', 'aqualuxe'),
            'update_item' => esc_html__('Update Feature', 'aqualuxe'),
            'add_new_item' => esc_html__('Add New Feature', 'aqualuxe'),
            'new_item_name' => esc_html__('New Feature Name', 'aqualuxe'),
            'separate_items_with_commas' => esc_html__('Separate features with commas', 'aqualuxe'),
            'add_or_remove_items' => esc_html__('Add or remove features', 'aqualuxe'),
            'choose_from_most_used' => esc_html__('Choose from the most used features', 'aqualuxe'),
            'menu_name' => esc_html__('Features', 'aqualuxe'),
        ],
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'show_in_rest' => true,
        'query_var' => true,
        'rewrite' => ['slug' => 'feature'],
    ]);
}
add_action('init', 'aqualuxe_register_taxonomies');

/**
 * Add custom fields to taxonomy forms
 */
function aqualuxe_add_taxonomy_fields($term)
{
    $taxonomy = $term->taxonomy ?? (isset($_GET['taxonomy']) ? $_GET['taxonomy'] : '');
    
    if (in_array($taxonomy, ['aqualuxe_service_category', 'aqualuxe_project_category', 'aqualuxe_event_category'])) {
        $icon = get_term_meta($term->term_id ?? 0, 'aqualuxe_category_icon', true);
        $color = get_term_meta($term->term_id ?? 0, 'aqualuxe_category_color', true);
        ?>
        <tr class="form-field">
            <th scope="row">
                <label for="aqualuxe_category_icon"><?php esc_html_e('Icon Class', 'aqualuxe'); ?></label>
            </th>
            <td>
                <input type="text" name="aqualuxe_category_icon" id="aqualuxe_category_icon" value="<?php echo esc_attr($icon); ?>" />
                <p class="description"><?php esc_html_e('CSS class for the category icon (e.g., fas fa-fish)', 'aqualuxe'); ?></p>
            </td>
        </tr>
        <tr class="form-field">
            <th scope="row">
                <label for="aqualuxe_category_color"><?php esc_html_e('Category Color', 'aqualuxe'); ?></label>
            </th>
            <td>
                <input type="color" name="aqualuxe_category_color" id="aqualuxe_category_color" value="<?php echo esc_attr($color ?: '#0ea5e9'); ?>" />
                <p class="description"><?php esc_html_e('Color for this category', 'aqualuxe'); ?></p>
            </td>
        </tr>
        <?php
    }
}
add_action('edit_category_form_fields', 'aqualuxe_add_taxonomy_fields');
add_action('add_category_form_fields', 'aqualuxe_add_taxonomy_fields');

// Apply to all custom taxonomies
$custom_taxonomies = [
    'aqualuxe_service_category',
    'aqualuxe_project_category',
    'aqualuxe_event_category',
    'aqualuxe_faq_category'
];

foreach ($custom_taxonomies as $taxonomy) {
    add_action("{$taxonomy}_edit_form_fields", 'aqualuxe_add_taxonomy_fields');
    add_action("{$taxonomy}_add_form_fields", 'aqualuxe_add_taxonomy_fields');
}

/**
 * Save taxonomy custom fields
 */
function aqualuxe_save_taxonomy_fields($term_id)
{
    if (isset($_POST['aqualuxe_category_icon'])) {
        update_term_meta($term_id, 'aqualuxe_category_icon', sanitize_text_field($_POST['aqualuxe_category_icon']));
    }
    
    if (isset($_POST['aqualuxe_category_color'])) {
        update_term_meta($term_id, 'aqualuxe_category_color', sanitize_hex_color($_POST['aqualuxe_category_color']));
    }
}

foreach ($custom_taxonomies as $taxonomy) {
    add_action("created_{$taxonomy}", 'aqualuxe_save_taxonomy_fields');
    add_action("edited_{$taxonomy}", 'aqualuxe_save_taxonomy_fields');
}

/**
 * Add custom columns to taxonomy admin tables
 */
function aqualuxe_add_taxonomy_columns($columns)
{
    $columns['icon'] = esc_html__('Icon', 'aqualuxe');
    $columns['color'] = esc_html__('Color', 'aqualuxe');
    return $columns;
}

/**
 * Populate custom taxonomy columns
 */
function aqualuxe_taxonomy_column_content($content, $column_name, $term_id)
{
    switch ($column_name) {
        case 'icon':
            $icon = get_term_meta($term_id, 'aqualuxe_category_icon', true);
            return $icon ? '<i class="' . esc_attr($icon) . '"></i>' : '—';
        case 'color':
            $color = get_term_meta($term_id, 'aqualuxe_category_color', true);
            return $color ? '<div style="width: 20px; height: 20px; background-color: ' . esc_attr($color) . '; border-radius: 3px; display: inline-block;"></div>' : '—';
    }
    return $content;
}

// Apply to custom taxonomies
foreach ($custom_taxonomies as $taxonomy) {
    add_filter("manage_edit-{$taxonomy}_columns", 'aqualuxe_add_taxonomy_columns');
    add_filter("manage_{$taxonomy}_custom_column", 'aqualuxe_taxonomy_column_content', 10, 3);
}