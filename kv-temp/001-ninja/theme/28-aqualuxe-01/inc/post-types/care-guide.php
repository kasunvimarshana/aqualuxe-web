<?php
/**
 * Fish Care Guide Custom Post Type
 *
 * @package AquaLuxe
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register Care Guide Custom Post Type
 */
function aqualuxe_register_care_guide_post_type() {
    $labels = array(
        'name'               => _x('Care Guides', 'post type general name', 'aqualuxe'),
        'singular_name'      => _x('Care Guide', 'post type singular name', 'aqualuxe'),
        'menu_name'          => _x('Care Guides', 'admin menu', 'aqualuxe'),
        'name_admin_bar'     => _x('Care Guide', 'add new on admin bar', 'aqualuxe'),
        'add_new'            => _x('Add New', 'care guide', 'aqualuxe'),
        'add_new_item'       => __('Add New Care Guide', 'aqualuxe'),
        'new_item'           => __('New Care Guide', 'aqualuxe'),
        'edit_item'          => __('Edit Care Guide', 'aqualuxe'),
        'view_item'          => __('View Care Guide', 'aqualuxe'),
        'all_items'          => __('All Care Guides', 'aqualuxe'),
        'search_items'       => __('Search Care Guides', 'aqualuxe'),
        'parent_item_colon'  => __('Parent Care Guides:', 'aqualuxe'),
        'not_found'          => __('No care guides found.', 'aqualuxe'),
        'not_found_in_trash' => __('No care guides found in Trash.', 'aqualuxe')
    );

    $args = array(
        'labels'             => $labels,
        'description'        => __('Fish care guides with detailed information', 'aqualuxe'),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'care-guide'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 20,
        'menu_icon'          => 'dashicons-book-alt',
        'supports'           => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments', 'revisions'),
        'show_in_rest'       => true, // Enable Gutenberg editor
    );

    register_post_type('care_guide', $args);
}
add_action('init', 'aqualuxe_register_care_guide_post_type');

/**
 * Register taxonomies for Care Guide post type
 */
function aqualuxe_register_care_guide_taxonomies() {
    // Fish Species Taxonomy
    $species_labels = array(
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
    );

    $species_args = array(
        'hierarchical'      => true,
        'labels'            => $species_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'fish-species'),
        'show_in_rest'      => true, // Enable in Gutenberg
    );

    register_taxonomy('fish_species', array('care_guide'), $species_args);

    // Care Category Taxonomy
    $category_labels = array(
        'name'              => _x('Care Categories', 'taxonomy general name', 'aqualuxe'),
        'singular_name'     => _x('Care Category', 'taxonomy singular name', 'aqualuxe'),
        'search_items'      => __('Search Care Categories', 'aqualuxe'),
        'all_items'         => __('All Care Categories', 'aqualuxe'),
        'parent_item'       => __('Parent Care Category', 'aqualuxe'),
        'parent_item_colon' => __('Parent Care Category:', 'aqualuxe'),
        'edit_item'         => __('Edit Care Category', 'aqualuxe'),
        'update_item'       => __('Update Care Category', 'aqualuxe'),
        'add_new_item'      => __('Add New Care Category', 'aqualuxe'),
        'new_item_name'     => __('New Care Category Name', 'aqualuxe'),
        'menu_name'         => __('Care Categories', 'aqualuxe'),
    );

    $category_args = array(
        'hierarchical'      => true,
        'labels'            => $category_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'care-category'),
        'show_in_rest'      => true, // Enable in Gutenberg
    );

    register_taxonomy('care_category', array('care_guide'), $category_args);

    // Difficulty Level Taxonomy
    $difficulty_labels = array(
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
    );

    $difficulty_args = array(
        'hierarchical'      => true,
        'labels'            => $difficulty_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'difficulty-level'),
        'show_in_rest'      => true, // Enable in Gutenberg
    );

    register_taxonomy('difficulty_level', array('care_guide'), $difficulty_args);
}
add_action('init', 'aqualuxe_register_care_guide_taxonomies');

/**
 * Add custom meta boxes for Care Guide post type
 */
function aqualuxe_add_care_guide_meta_boxes() {
    add_meta_box(
        'care_guide_details',
        __('Care Guide Details', 'aqualuxe'),
        'aqualuxe_care_guide_details_callback',
        'care_guide',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'aqualuxe_add_care_guide_meta_boxes');

/**
 * Render Care Guide Details meta box
 */
function aqualuxe_care_guide_details_callback($post) {
    // Add nonce for security
    wp_nonce_field('aqualuxe_care_guide_details', 'aqualuxe_care_guide_details_nonce');

    // Retrieve existing values
    $tank_size = get_post_meta($post->ID, '_tank_size', true);
    $water_temp = get_post_meta($post->ID, '_water_temp', true);
    $ph_level = get_post_meta($post->ID, '_ph_level', true);
    $lifespan = get_post_meta($post->ID, '_lifespan', true);
    $diet = get_post_meta($post->ID, '_diet', true);
    $maintenance_level = get_post_meta($post->ID, '_maintenance_level', true);
    $compatible_with = get_post_meta($post->ID, '_compatible_with', true);
    $not_compatible_with = get_post_meta($post->ID, '_not_compatible_with', true);
    
    // Output fields
    ?>
    <div class="care-guide-meta-box">
        <style>
            .care-guide-meta-box {
                display: grid;
                grid-template-columns: 1fr 1fr;
                grid-gap: 15px;
            }
            .care-guide-field {
                margin-bottom: 15px;
            }
            .care-guide-field label {
                display: block;
                font-weight: bold;
                margin-bottom: 5px;
            }
            .care-guide-field input[type="text"],
            .care-guide-field select,
            .care-guide-field textarea {
                width: 100%;
            }
            .care-guide-full-width {
                grid-column: 1 / span 2;
            }
        </style>
        
        <div class="care-guide-field">
            <label for="tank_size"><?php _e('Minimum Tank Size (gallons)', 'aqualuxe'); ?></label>
            <input type="text" id="tank_size" name="tank_size" value="<?php echo esc_attr($tank_size); ?>">
        </div>
        
        <div class="care-guide-field">
            <label for="water_temp"><?php _e('Water Temperature Range (°F)', 'aqualuxe'); ?></label>
            <input type="text" id="water_temp" name="water_temp" value="<?php echo esc_attr($water_temp); ?>">
        </div>
        
        <div class="care-guide-field">
            <label for="ph_level"><?php _e('pH Level Range', 'aqualuxe'); ?></label>
            <input type="text" id="ph_level" name="ph_level" value="<?php echo esc_attr($ph_level); ?>">
        </div>
        
        <div class="care-guide-field">
            <label for="lifespan"><?php _e('Average Lifespan (years)', 'aqualuxe'); ?></label>
            <input type="text" id="lifespan" name="lifespan" value="<?php echo esc_attr($lifespan); ?>">
        </div>
        
        <div class="care-guide-field">
            <label for="diet"><?php _e('Diet Requirements', 'aqualuxe'); ?></label>
            <input type="text" id="diet" name="diet" value="<?php echo esc_attr($diet); ?>">
        </div>
        
        <div class="care-guide-field">
            <label for="maintenance_level"><?php _e('Maintenance Level', 'aqualuxe'); ?></label>
            <select id="maintenance_level" name="maintenance_level">
                <option value=""><?php _e('Select Level', 'aqualuxe'); ?></option>
                <option value="low" <?php selected($maintenance_level, 'low'); ?>><?php _e('Low', 'aqualuxe'); ?></option>
                <option value="medium" <?php selected($maintenance_level, 'medium'); ?>><?php _e('Medium', 'aqualuxe'); ?></option>
                <option value="high" <?php selected($maintenance_level, 'high'); ?>><?php _e('High', 'aqualuxe'); ?></option>
            </select>
        </div>
        
        <div class="care-guide-field care-guide-full-width">
            <label for="compatible_with"><?php _e('Compatible With (comma separated)', 'aqualuxe'); ?></label>
            <textarea id="compatible_with" name="compatible_with" rows="3"><?php echo esc_textarea($compatible_with); ?></textarea>
        </div>
        
        <div class="care-guide-field care-guide-full-width">
            <label for="not_compatible_with"><?php _e('Not Compatible With (comma separated)', 'aqualuxe'); ?></label>
            <textarea id="not_compatible_with" name="not_compatible_with" rows="3"><?php echo esc_textarea($not_compatible_with); ?></textarea>
        </div>
    </div>
    <?php
}

/**
 * Save Care Guide meta box data
 */
function aqualuxe_save_care_guide_meta_box_data($post_id) {
    // Check if nonce is set
    if (!isset($_POST['aqualuxe_care_guide_details_nonce'])) {
        return;
    }

    // Verify nonce
    if (!wp_verify_nonce($_POST['aqualuxe_care_guide_details_nonce'], 'aqualuxe_care_guide_details')) {
        return;
    }

    // If this is an autosave, don't do anything
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check user permissions
    if (isset($_POST['post_type']) && 'care_guide' === $_POST['post_type']) {
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
    }

    // Save meta box data
    $fields = array(
        'tank_size',
        'water_temp',
        'ph_level',
        'lifespan',
        'diet',
        'maintenance_level',
        'compatible_with',
        'not_compatible_with',
    );

    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
        }
    }
}
add_action('save_post', 'aqualuxe_save_care_guide_meta_box_data');