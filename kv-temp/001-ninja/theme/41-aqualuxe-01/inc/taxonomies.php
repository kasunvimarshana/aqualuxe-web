<?php
/**
 * AquaLuxe Custom Taxonomies
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
    // Register testimonial category taxonomy
    aqualuxe_register_testimonial_category_taxonomy();
    
    // Register team member department taxonomy
    aqualuxe_register_team_member_department_taxonomy();
    
    // Register service category taxonomy
    aqualuxe_register_service_category_taxonomy();
    
    // Register project category taxonomy
    aqualuxe_register_project_category_taxonomy();
    
    // Register project tag taxonomy
    aqualuxe_register_project_tag_taxonomy();
    
    // Register event category taxonomy
    aqualuxe_register_event_category_taxonomy();
    
    // Register FAQ category taxonomy
    aqualuxe_register_faq_category_taxonomy();
}
add_action('init', 'aqualuxe_register_taxonomies');

/**
 * Register testimonial category taxonomy
 */
function aqualuxe_register_testimonial_category_taxonomy() {
    $labels = [
        'name' => _x('Testimonial Categories', 'Taxonomy general name', 'aqualuxe'),
        'singular_name' => _x('Testimonial Category', 'Taxonomy singular name', 'aqualuxe'),
        'search_items' => __('Search Testimonial Categories', 'aqualuxe'),
        'all_items' => __('All Testimonial Categories', 'aqualuxe'),
        'parent_item' => __('Parent Testimonial Category', 'aqualuxe'),
        'parent_item_colon' => __('Parent Testimonial Category:', 'aqualuxe'),
        'edit_item' => __('Edit Testimonial Category', 'aqualuxe'),
        'update_item' => __('Update Testimonial Category', 'aqualuxe'),
        'add_new_item' => __('Add New Testimonial Category', 'aqualuxe'),
        'new_item_name' => __('New Testimonial Category Name', 'aqualuxe'),
        'menu_name' => __('Categories', 'aqualuxe'),
    ];

    $args = [
        'hierarchical' => true,
        'labels' => $labels,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => ['slug' => 'testimonial-category'],
        'show_in_rest' => true,
    ];

    register_taxonomy('testimonial_category', ['testimonial'], $args);
}

/**
 * Register team member department taxonomy
 */
function aqualuxe_register_team_member_department_taxonomy() {
    $labels = [
        'name' => _x('Departments', 'Taxonomy general name', 'aqualuxe'),
        'singular_name' => _x('Department', 'Taxonomy singular name', 'aqualuxe'),
        'search_items' => __('Search Departments', 'aqualuxe'),
        'all_items' => __('All Departments', 'aqualuxe'),
        'parent_item' => __('Parent Department', 'aqualuxe'),
        'parent_item_colon' => __('Parent Department:', 'aqualuxe'),
        'edit_item' => __('Edit Department', 'aqualuxe'),
        'update_item' => __('Update Department', 'aqualuxe'),
        'add_new_item' => __('Add New Department', 'aqualuxe'),
        'new_item_name' => __('New Department Name', 'aqualuxe'),
        'menu_name' => __('Departments', 'aqualuxe'),
    ];

    $args = [
        'hierarchical' => true,
        'labels' => $labels,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => ['slug' => 'department'],
        'show_in_rest' => true,
    ];

    register_taxonomy('department', ['team_member'], $args);
}

/**
 * Register service category taxonomy
 */
function aqualuxe_register_service_category_taxonomy() {
    $labels = [
        'name' => _x('Service Categories', 'Taxonomy general name', 'aqualuxe'),
        'singular_name' => _x('Service Category', 'Taxonomy singular name', 'aqualuxe'),
        'search_items' => __('Search Service Categories', 'aqualuxe'),
        'all_items' => __('All Service Categories', 'aqualuxe'),
        'parent_item' => __('Parent Service Category', 'aqualuxe'),
        'parent_item_colon' => __('Parent Service Category:', 'aqualuxe'),
        'edit_item' => __('Edit Service Category', 'aqualuxe'),
        'update_item' => __('Update Service Category', 'aqualuxe'),
        'add_new_item' => __('Add New Service Category', 'aqualuxe'),
        'new_item_name' => __('New Service Category Name', 'aqualuxe'),
        'menu_name' => __('Categories', 'aqualuxe'),
    ];

    $args = [
        'hierarchical' => true,
        'labels' => $labels,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => ['slug' => 'service-category'],
        'show_in_rest' => true,
    ];

    register_taxonomy('service_category', ['service'], $args);
}

/**
 * Register project category taxonomy
 */
function aqualuxe_register_project_category_taxonomy() {
    $labels = [
        'name' => _x('Project Categories', 'Taxonomy general name', 'aqualuxe'),
        'singular_name' => _x('Project Category', 'Taxonomy singular name', 'aqualuxe'),
        'search_items' => __('Search Project Categories', 'aqualuxe'),
        'all_items' => __('All Project Categories', 'aqualuxe'),
        'parent_item' => __('Parent Project Category', 'aqualuxe'),
        'parent_item_colon' => __('Parent Project Category:', 'aqualuxe'),
        'edit_item' => __('Edit Project Category', 'aqualuxe'),
        'update_item' => __('Update Project Category', 'aqualuxe'),
        'add_new_item' => __('Add New Project Category', 'aqualuxe'),
        'new_item_name' => __('New Project Category Name', 'aqualuxe'),
        'menu_name' => __('Categories', 'aqualuxe'),
    ];

    $args = [
        'hierarchical' => true,
        'labels' => $labels,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => ['slug' => 'project-category'],
        'show_in_rest' => true,
    ];

    register_taxonomy('project_category', ['project'], $args);
}

/**
 * Register project tag taxonomy
 */
function aqualuxe_register_project_tag_taxonomy() {
    $labels = [
        'name' => _x('Project Tags', 'Taxonomy general name', 'aqualuxe'),
        'singular_name' => _x('Project Tag', 'Taxonomy singular name', 'aqualuxe'),
        'search_items' => __('Search Project Tags', 'aqualuxe'),
        'all_items' => __('All Project Tags', 'aqualuxe'),
        'parent_item' => __('Parent Project Tag', 'aqualuxe'),
        'parent_item_colon' => __('Parent Project Tag:', 'aqualuxe'),
        'edit_item' => __('Edit Project Tag', 'aqualuxe'),
        'update_item' => __('Update Project Tag', 'aqualuxe'),
        'add_new_item' => __('Add New Project Tag', 'aqualuxe'),
        'new_item_name' => __('New Project Tag Name', 'aqualuxe'),
        'menu_name' => __('Tags', 'aqualuxe'),
    ];

    $args = [
        'hierarchical' => false,
        'labels' => $labels,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => ['slug' => 'project-tag'],
        'show_in_rest' => true,
    ];

    register_taxonomy('project_tag', ['project'], $args);
}

/**
 * Register event category taxonomy
 */
function aqualuxe_register_event_category_taxonomy() {
    $labels = [
        'name' => _x('Event Categories', 'Taxonomy general name', 'aqualuxe'),
        'singular_name' => _x('Event Category', 'Taxonomy singular name', 'aqualuxe'),
        'search_items' => __('Search Event Categories', 'aqualuxe'),
        'all_items' => __('All Event Categories', 'aqualuxe'),
        'parent_item' => __('Parent Event Category', 'aqualuxe'),
        'parent_item_colon' => __('Parent Event Category:', 'aqualuxe'),
        'edit_item' => __('Edit Event Category', 'aqualuxe'),
        'update_item' => __('Update Event Category', 'aqualuxe'),
        'add_new_item' => __('Add New Event Category', 'aqualuxe'),
        'new_item_name' => __('New Event Category Name', 'aqualuxe'),
        'menu_name' => __('Categories', 'aqualuxe'),
    ];

    $args = [
        'hierarchical' => true,
        'labels' => $labels,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => ['slug' => 'event-category'],
        'show_in_rest' => true,
    ];

    register_taxonomy('event_category', ['event'], $args);
}

/**
 * Register FAQ category taxonomy
 */
function aqualuxe_register_faq_category_taxonomy() {
    $labels = [
        'name' => _x('FAQ Categories', 'Taxonomy general name', 'aqualuxe'),
        'singular_name' => _x('FAQ Category', 'Taxonomy singular name', 'aqualuxe'),
        'search_items' => __('Search FAQ Categories', 'aqualuxe'),
        'all_items' => __('All FAQ Categories', 'aqualuxe'),
        'parent_item' => __('Parent FAQ Category', 'aqualuxe'),
        'parent_item_colon' => __('Parent FAQ Category:', 'aqualuxe'),
        'edit_item' => __('Edit FAQ Category', 'aqualuxe'),
        'update_item' => __('Update FAQ Category', 'aqualuxe'),
        'add_new_item' => __('Add New FAQ Category', 'aqualuxe'),
        'new_item_name' => __('New FAQ Category Name', 'aqualuxe'),
        'menu_name' => __('Categories', 'aqualuxe'),
    ];

    $args = [
        'hierarchical' => true,
        'labels' => $labels,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => ['slug' => 'faq-category'],
        'show_in_rest' => true,
    ];

    register_taxonomy('faq_category', ['faq'], $args);
}

/**
 * Add taxonomy meta boxes
 */
function aqualuxe_add_taxonomy_meta_boxes() {
    // Add meta box for testimonial category
    add_action('testimonial_category_add_form_fields', 'aqualuxe_add_taxonomy_image_field');
    add_action('testimonial_category_edit_form_fields', 'aqualuxe_edit_taxonomy_image_field');
    
    // Add meta box for service category
    add_action('service_category_add_form_fields', 'aqualuxe_add_taxonomy_image_field');
    add_action('service_category_edit_form_fields', 'aqualuxe_edit_taxonomy_image_field');
    
    // Add meta box for project category
    add_action('project_category_add_form_fields', 'aqualuxe_add_taxonomy_image_field');
    add_action('project_category_edit_form_fields', 'aqualuxe_edit_taxonomy_image_field');
    
    // Add meta box for event category
    add_action('event_category_add_form_fields', 'aqualuxe_add_taxonomy_image_field');
    add_action('event_category_edit_form_fields', 'aqualuxe_edit_taxonomy_image_field');
    
    // Add meta box for FAQ category
    add_action('faq_category_add_form_fields', 'aqualuxe_add_taxonomy_image_field');
    add_action('faq_category_edit_form_fields', 'aqualuxe_edit_taxonomy_image_field');
    
    // Save taxonomy meta
    add_action('created_testimonial_category', 'aqualuxe_save_taxonomy_image');
    add_action('edited_testimonial_category', 'aqualuxe_save_taxonomy_image');
    add_action('created_service_category', 'aqualuxe_save_taxonomy_image');
    add_action('edited_service_category', 'aqualuxe_save_taxonomy_image');
    add_action('created_project_category', 'aqualuxe_save_taxonomy_image');
    add_action('edited_project_category', 'aqualuxe_save_taxonomy_image');
    add_action('created_event_category', 'aqualuxe_save_taxonomy_image');
    add_action('edited_event_category', 'aqualuxe_save_taxonomy_image');
    add_action('created_faq_category', 'aqualuxe_save_taxonomy_image');
    add_action('edited_faq_category', 'aqualuxe_save_taxonomy_image');
}
add_action('admin_init', 'aqualuxe_add_taxonomy_meta_boxes');

/**
 * Add taxonomy image field
 *
 * @param WP_Term $taxonomy Taxonomy object
 */
function aqualuxe_add_taxonomy_image_field($taxonomy) {
    ?>
    <div class="form-field term-image-wrap">
        <label for="aqualuxe-taxonomy-image"><?php _e('Image', 'aqualuxe'); ?></label>
        <input type="hidden" id="aqualuxe-taxonomy-image" name="aqualuxe_taxonomy_image" class="aqualuxe-taxonomy-image" value="">
        <div class="aqualuxe-taxonomy-image-preview"></div>
        <button type="button" class="button aqualuxe-upload-taxonomy-image"><?php _e('Upload Image', 'aqualuxe'); ?></button>
        <button type="button" class="button aqualuxe-remove-taxonomy-image" style="display:none;"><?php _e('Remove Image', 'aqualuxe'); ?></button>
        <p class="description"><?php _e('Upload an image for this category', 'aqualuxe'); ?></p>
    </div>
    <?php
}

/**
 * Edit taxonomy image field
 *
 * @param WP_Term $term Term object
 */
function aqualuxe_edit_taxonomy_image_field($term) {
    $image_id = get_term_meta($term->term_id, 'aqualuxe_taxonomy_image', true);
    $image_url = '';
    
    if ($image_id) {
        $image_url = wp_get_attachment_image_url($image_id, 'thumbnail');
    }
    ?>
    <tr class="form-field term-image-wrap">
        <th scope="row"><label for="aqualuxe-taxonomy-image"><?php _e('Image', 'aqualuxe'); ?></label></th>
        <td>
            <input type="hidden" id="aqualuxe-taxonomy-image" name="aqualuxe_taxonomy_image" class="aqualuxe-taxonomy-image" value="<?php echo esc_attr($image_id); ?>">
            <div class="aqualuxe-taxonomy-image-preview">
                <?php if ($image_url) : ?>
                    <img src="<?php echo esc_url($image_url); ?>" alt="">
                <?php endif; ?>
            </div>
            <button type="button" class="button aqualuxe-upload-taxonomy-image"><?php _e('Upload Image', 'aqualuxe'); ?></button>
            <button type="button" class="button aqualuxe-remove-taxonomy-image" <?php echo $image_url ? '' : 'style="display:none;"'; ?>><?php _e('Remove Image', 'aqualuxe'); ?></button>
            <p class="description"><?php _e('Upload an image for this category', 'aqualuxe'); ?></p>
        </td>
    </tr>
    <?php
}

/**
 * Save taxonomy image
 *
 * @param int $term_id Term ID
 */
function aqualuxe_save_taxonomy_image($term_id) {
    if (isset($_POST['aqualuxe_taxonomy_image'])) {
        update_term_meta($term_id, 'aqualuxe_taxonomy_image', absint($_POST['aqualuxe_taxonomy_image']));
    }
}

/**
 * Enqueue taxonomy admin scripts
 *
 * @param string $hook Current admin page
 */
function aqualuxe_enqueue_taxonomy_admin_scripts($hook) {
    if ('edit-tags.php' === $hook || 'term.php' === $hook) {
        wp_enqueue_media();
        
        wp_enqueue_script(
            'aqualuxe-taxonomy-admin',
            AQUALUXE_ASSETS_URI . 'js/taxonomy-admin.js',
            ['jquery'],
            AQUALUXE_VERSION,
            true
        );
    }
}
add_action('admin_enqueue_scripts', 'aqualuxe_enqueue_taxonomy_admin_scripts');

/**
 * Get taxonomy image URL
 *
 * @param int $term_id Term ID
 * @param string $size Image size
 * @return string
 */
function aqualuxe_get_taxonomy_image_url($term_id, $size = 'thumbnail') {
    $image_id = get_term_meta($term_id, 'aqualuxe_taxonomy_image', true);
    
    if ($image_id) {
        return wp_get_attachment_image_url($image_id, $size);
    }
    
    return '';
}

/**
 * Get taxonomy image
 *
 * @param int $term_id Term ID
 * @param string $size Image size
 * @param array $attr Image attributes
 * @return string
 */
function aqualuxe_get_taxonomy_image($term_id, $size = 'thumbnail', $attr = []) {
    $image_id = get_term_meta($term_id, 'aqualuxe_taxonomy_image', true);
    
    if ($image_id) {
        return wp_get_attachment_image($image_id, $size, false, $attr);
    }
    
    return '';
}

/**
 * Display taxonomy image
 *
 * @param int $term_id Term ID
 * @param string $size Image size
 * @param array $attr Image attributes
 */
function aqualuxe_taxonomy_image($term_id, $size = 'thumbnail', $attr = []) {
    echo aqualuxe_get_taxonomy_image($term_id, $size, $attr);
}