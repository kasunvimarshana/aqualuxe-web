<?php
/**
 * AquaLuxe Custom Post Types
 *
 * @package AquaLuxe
 */

// Prevent direct access to this file
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register custom post types
 */
function aqualuxe_register_post_types() {
    // Register testimonials post type
    aqualuxe_register_testimonials_post_type();
    
    // Register team members post type
    aqualuxe_register_team_members_post_type();
    
    // Register services post type
    aqualuxe_register_services_post_type();
    
    // Register projects post type
    aqualuxe_register_projects_post_type();
    
    // Register events post type
    aqualuxe_register_events_post_type();
    
    // Register FAQs post type
    aqualuxe_register_faqs_post_type();
}
add_action('init', 'aqualuxe_register_post_types');

/**
 * Register testimonials post type
 */
function aqualuxe_register_testimonials_post_type() {
    $labels = [
        'name' => _x('Testimonials', 'Post type general name', 'aqualuxe'),
        'singular_name' => _x('Testimonial', 'Post type singular name', 'aqualuxe'),
        'menu_name' => _x('Testimonials', 'Admin Menu text', 'aqualuxe'),
        'name_admin_bar' => _x('Testimonial', 'Add New on Toolbar', 'aqualuxe'),
        'add_new' => __('Add New', 'aqualuxe'),
        'add_new_item' => __('Add New Testimonial', 'aqualuxe'),
        'new_item' => __('New Testimonial', 'aqualuxe'),
        'edit_item' => __('Edit Testimonial', 'aqualuxe'),
        'view_item' => __('View Testimonial', 'aqualuxe'),
        'all_items' => __('All Testimonials', 'aqualuxe'),
        'search_items' => __('Search Testimonials', 'aqualuxe'),
        'parent_item_colon' => __('Parent Testimonials:', 'aqualuxe'),
        'not_found' => __('No testimonials found.', 'aqualuxe'),
        'not_found_in_trash' => __('No testimonials found in Trash.', 'aqualuxe'),
        'featured_image' => _x('Testimonial Image', 'Overrides the "Featured Image" phrase', 'aqualuxe'),
        'set_featured_image' => _x('Set testimonial image', 'Overrides the "Set featured image" phrase', 'aqualuxe'),
        'remove_featured_image' => _x('Remove testimonial image', 'Overrides the "Remove featured image" phrase', 'aqualuxe'),
        'use_featured_image' => _x('Use as testimonial image', 'Overrides the "Use as featured image" phrase', 'aqualuxe'),
        'archives' => _x('Testimonial archives', 'The post type archive label used in nav menus', 'aqualuxe'),
        'insert_into_item' => _x('Insert into testimonial', 'Overrides the "Insert into post" phrase', 'aqualuxe'),
        'uploaded_to_this_item' => _x('Uploaded to this testimonial', 'Overrides the "Uploaded to this post" phrase', 'aqualuxe'),
        'filter_items_list' => _x('Filter testimonials list', 'Screen reader text for the filter links heading on the post type listing screen', 'aqualuxe'),
        'items_list_navigation' => _x('Testimonials list navigation', 'Screen reader text for the pagination heading on the post type listing screen', 'aqualuxe'),
        'items_list' => _x('Testimonials list', 'Screen reader text for the items list heading on the post type listing screen', 'aqualuxe'),
    ];

    $args = [
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => ['slug' => 'testimonial'],
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 20,
        'menu_icon' => 'dashicons-format-quote',
        'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
        'show_in_rest' => true,
    ];

    register_post_type('testimonial', $args);
}

/**
 * Register team members post type
 */
function aqualuxe_register_team_members_post_type() {
    $labels = [
        'name' => _x('Team Members', 'Post type general name', 'aqualuxe'),
        'singular_name' => _x('Team Member', 'Post type singular name', 'aqualuxe'),
        'menu_name' => _x('Team', 'Admin Menu text', 'aqualuxe'),
        'name_admin_bar' => _x('Team Member', 'Add New on Toolbar', 'aqualuxe'),
        'add_new' => __('Add New', 'aqualuxe'),
        'add_new_item' => __('Add New Team Member', 'aqualuxe'),
        'new_item' => __('New Team Member', 'aqualuxe'),
        'edit_item' => __('Edit Team Member', 'aqualuxe'),
        'view_item' => __('View Team Member', 'aqualuxe'),
        'all_items' => __('All Team Members', 'aqualuxe'),
        'search_items' => __('Search Team Members', 'aqualuxe'),
        'parent_item_colon' => __('Parent Team Members:', 'aqualuxe'),
        'not_found' => __('No team members found.', 'aqualuxe'),
        'not_found_in_trash' => __('No team members found in Trash.', 'aqualuxe'),
        'featured_image' => _x('Team Member Image', 'Overrides the "Featured Image" phrase', 'aqualuxe'),
        'set_featured_image' => _x('Set team member image', 'Overrides the "Set featured image" phrase', 'aqualuxe'),
        'remove_featured_image' => _x('Remove team member image', 'Overrides the "Remove featured image" phrase', 'aqualuxe'),
        'use_featured_image' => _x('Use as team member image', 'Overrides the "Use as featured image" phrase', 'aqualuxe'),
        'archives' => _x('Team Member archives', 'The post type archive label used in nav menus', 'aqualuxe'),
        'insert_into_item' => _x('Insert into team member', 'Overrides the "Insert into post" phrase', 'aqualuxe'),
        'uploaded_to_this_item' => _x('Uploaded to this team member', 'Overrides the "Uploaded to this post" phrase', 'aqualuxe'),
        'filter_items_list' => _x('Filter team members list', 'Screen reader text for the filter links heading on the post type listing screen', 'aqualuxe'),
        'items_list_navigation' => _x('Team Members list navigation', 'Screen reader text for the pagination heading on the post type listing screen', 'aqualuxe'),
        'items_list' => _x('Team Members list', 'Screen reader text for the items list heading on the post type listing screen', 'aqualuxe'),
    ];

    $args = [
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => ['slug' => 'team'],
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 21,
        'menu_icon' => 'dashicons-groups',
        'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
        'show_in_rest' => true,
    ];

    register_post_type('team_member', $args);
}

/**
 * Register services post type
 */
function aqualuxe_register_services_post_type() {
    $labels = [
        'name' => _x('Services', 'Post type general name', 'aqualuxe'),
        'singular_name' => _x('Service', 'Post type singular name', 'aqualuxe'),
        'menu_name' => _x('Services', 'Admin Menu text', 'aqualuxe'),
        'name_admin_bar' => _x('Service', 'Add New on Toolbar', 'aqualuxe'),
        'add_new' => __('Add New', 'aqualuxe'),
        'add_new_item' => __('Add New Service', 'aqualuxe'),
        'new_item' => __('New Service', 'aqualuxe'),
        'edit_item' => __('Edit Service', 'aqualuxe'),
        'view_item' => __('View Service', 'aqualuxe'),
        'all_items' => __('All Services', 'aqualuxe'),
        'search_items' => __('Search Services', 'aqualuxe'),
        'parent_item_colon' => __('Parent Services:', 'aqualuxe'),
        'not_found' => __('No services found.', 'aqualuxe'),
        'not_found_in_trash' => __('No services found in Trash.', 'aqualuxe'),
        'featured_image' => _x('Service Image', 'Overrides the "Featured Image" phrase', 'aqualuxe'),
        'set_featured_image' => _x('Set service image', 'Overrides the "Set featured image" phrase', 'aqualuxe'),
        'remove_featured_image' => _x('Remove service image', 'Overrides the "Remove featured image" phrase', 'aqualuxe'),
        'use_featured_image' => _x('Use as service image', 'Overrides the "Use as featured image" phrase', 'aqualuxe'),
        'archives' => _x('Service archives', 'The post type archive label used in nav menus', 'aqualuxe'),
        'insert_into_item' => _x('Insert into service', 'Overrides the "Insert into post" phrase', 'aqualuxe'),
        'uploaded_to_this_item' => _x('Uploaded to this service', 'Overrides the "Uploaded to this post" phrase', 'aqualuxe'),
        'filter_items_list' => _x('Filter services list', 'Screen reader text for the filter links heading on the post type listing screen', 'aqualuxe'),
        'items_list_navigation' => _x('Services list navigation', 'Screen reader text for the pagination heading on the post type listing screen', 'aqualuxe'),
        'items_list' => _x('Services list', 'Screen reader text for the items list heading on the post type listing screen', 'aqualuxe'),
    ];

    $args = [
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => ['slug' => 'service'],
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 22,
        'menu_icon' => 'dashicons-admin-tools',
        'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'page-attributes'],
        'show_in_rest' => true,
    ];

    register_post_type('service', $args);
}

/**
 * Register projects post type
 */
function aqualuxe_register_projects_post_type() {
    $labels = [
        'name' => _x('Projects', 'Post type general name', 'aqualuxe'),
        'singular_name' => _x('Project', 'Post type singular name', 'aqualuxe'),
        'menu_name' => _x('Projects', 'Admin Menu text', 'aqualuxe'),
        'name_admin_bar' => _x('Project', 'Add New on Toolbar', 'aqualuxe'),
        'add_new' => __('Add New', 'aqualuxe'),
        'add_new_item' => __('Add New Project', 'aqualuxe'),
        'new_item' => __('New Project', 'aqualuxe'),
        'edit_item' => __('Edit Project', 'aqualuxe'),
        'view_item' => __('View Project', 'aqualuxe'),
        'all_items' => __('All Projects', 'aqualuxe'),
        'search_items' => __('Search Projects', 'aqualuxe'),
        'parent_item_colon' => __('Parent Projects:', 'aqualuxe'),
        'not_found' => __('No projects found.', 'aqualuxe'),
        'not_found_in_trash' => __('No projects found in Trash.', 'aqualuxe'),
        'featured_image' => _x('Project Image', 'Overrides the "Featured Image" phrase', 'aqualuxe'),
        'set_featured_image' => _x('Set project image', 'Overrides the "Set featured image" phrase', 'aqualuxe'),
        'remove_featured_image' => _x('Remove project image', 'Overrides the "Remove featured image" phrase', 'aqualuxe'),
        'use_featured_image' => _x('Use as project image', 'Overrides the "Use as featured image" phrase', 'aqualuxe'),
        'archives' => _x('Project archives', 'The post type archive label used in nav menus', 'aqualuxe'),
        'insert_into_item' => _x('Insert into project', 'Overrides the "Insert into post" phrase', 'aqualuxe'),
        'uploaded_to_this_item' => _x('Uploaded to this project', 'Overrides the "Uploaded to this post" phrase', 'aqualuxe'),
        'filter_items_list' => _x('Filter projects list', 'Screen reader text for the filter links heading on the post type listing screen', 'aqualuxe'),
        'items_list_navigation' => _x('Projects list navigation', 'Screen reader text for the pagination heading on the post type listing screen', 'aqualuxe'),
        'items_list' => _x('Projects list', 'Screen reader text for the items list heading on the post type listing screen', 'aqualuxe'),
    ];

    $args = [
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => ['slug' => 'project'],
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 23,
        'menu_icon' => 'dashicons-portfolio',
        'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'page-attributes'],
        'show_in_rest' => true,
    ];

    register_post_type('project', $args);
}

/**
 * Register events post type
 */
function aqualuxe_register_events_post_type() {
    $labels = [
        'name' => _x('Events', 'Post type general name', 'aqualuxe'),
        'singular_name' => _x('Event', 'Post type singular name', 'aqualuxe'),
        'menu_name' => _x('Events', 'Admin Menu text', 'aqualuxe'),
        'name_admin_bar' => _x('Event', 'Add New on Toolbar', 'aqualuxe'),
        'add_new' => __('Add New', 'aqualuxe'),
        'add_new_item' => __('Add New Event', 'aqualuxe'),
        'new_item' => __('New Event', 'aqualuxe'),
        'edit_item' => __('Edit Event', 'aqualuxe'),
        'view_item' => __('View Event', 'aqualuxe'),
        'all_items' => __('All Events', 'aqualuxe'),
        'search_items' => __('Search Events', 'aqualuxe'),
        'parent_item_colon' => __('Parent Events:', 'aqualuxe'),
        'not_found' => __('No events found.', 'aqualuxe'),
        'not_found_in_trash' => __('No events found in Trash.', 'aqualuxe'),
        'featured_image' => _x('Event Image', 'Overrides the "Featured Image" phrase', 'aqualuxe'),
        'set_featured_image' => _x('Set event image', 'Overrides the "Set featured image" phrase', 'aqualuxe'),
        'remove_featured_image' => _x('Remove event image', 'Overrides the "Remove featured image" phrase', 'aqualuxe'),
        'use_featured_image' => _x('Use as event image', 'Overrides the "Use as featured image" phrase', 'aqualuxe'),
        'archives' => _x('Event archives', 'The post type archive label used in nav menus', 'aqualuxe'),
        'insert_into_item' => _x('Insert into event', 'Overrides the "Insert into post" phrase', 'aqualuxe'),
        'uploaded_to_this_item' => _x('Uploaded to this event', 'Overrides the "Uploaded to this post" phrase', 'aqualuxe'),
        'filter_items_list' => _x('Filter events list', 'Screen reader text for the filter links heading on the post type listing screen', 'aqualuxe'),
        'items_list_navigation' => _x('Events list navigation', 'Screen reader text for the pagination heading on the post type listing screen', 'aqualuxe'),
        'items_list' => _x('Events list', 'Screen reader text for the items list heading on the post type listing screen', 'aqualuxe'),
    ];

    $args = [
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => ['slug' => 'event'],
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 24,
        'menu_icon' => 'dashicons-calendar-alt',
        'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
        'show_in_rest' => true,
    ];

    register_post_type('event', $args);
}

/**
 * Register FAQs post type
 */
function aqualuxe_register_faqs_post_type() {
    $labels = [
        'name' => _x('FAQs', 'Post type general name', 'aqualuxe'),
        'singular_name' => _x('FAQ', 'Post type singular name', 'aqualuxe'),
        'menu_name' => _x('FAQs', 'Admin Menu text', 'aqualuxe'),
        'name_admin_bar' => _x('FAQ', 'Add New on Toolbar', 'aqualuxe'),
        'add_new' => __('Add New', 'aqualuxe'),
        'add_new_item' => __('Add New FAQ', 'aqualuxe'),
        'new_item' => __('New FAQ', 'aqualuxe'),
        'edit_item' => __('Edit FAQ', 'aqualuxe'),
        'view_item' => __('View FAQ', 'aqualuxe'),
        'all_items' => __('All FAQs', 'aqualuxe'),
        'search_items' => __('Search FAQs', 'aqualuxe'),
        'parent_item_colon' => __('Parent FAQs:', 'aqualuxe'),
        'not_found' => __('No FAQs found.', 'aqualuxe'),
        'not_found_in_trash' => __('No FAQs found in Trash.', 'aqualuxe'),
        'featured_image' => _x('FAQ Image', 'Overrides the "Featured Image" phrase', 'aqualuxe'),
        'set_featured_image' => _x('Set FAQ image', 'Overrides the "Set featured image" phrase', 'aqualuxe'),
        'remove_featured_image' => _x('Remove FAQ image', 'Overrides the "Remove featured image" phrase', 'aqualuxe'),
        'use_featured_image' => _x('Use as FAQ image', 'Overrides the "Use as featured image" phrase', 'aqualuxe'),
        'archives' => _x('FAQ archives', 'The post type archive label used in nav menus', 'aqualuxe'),
        'insert_into_item' => _x('Insert into FAQ', 'Overrides the "Insert into post" phrase', 'aqualuxe'),
        'uploaded_to_this_item' => _x('Uploaded to this FAQ', 'Overrides the "Uploaded to this post" phrase', 'aqualuxe'),
        'filter_items_list' => _x('Filter FAQs list', 'Screen reader text for the filter links heading on the post type listing screen', 'aqualuxe'),
        'items_list_navigation' => _x('FAQs list navigation', 'Screen reader text for the pagination heading on the post type listing screen', 'aqualuxe'),
        'items_list' => _x('FAQs list', 'Screen reader text for the items list heading on the post type listing screen', 'aqualuxe'),
    ];

    $args = [
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => ['slug' => 'faq'],
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 25,
        'menu_icon' => 'dashicons-editor-help',
        'supports' => ['title', 'editor', 'excerpt', 'page-attributes'],
        'show_in_rest' => true,
    ];

    register_post_type('faq', $args);
}

/**
 * Register meta boxes for custom post types
 */
function aqualuxe_register_meta_boxes() {
    // Testimonials meta box
    add_meta_box(
        'aqualuxe_testimonial_meta',
        __('Testimonial Details', 'aqualuxe'),
        'aqualuxe_testimonial_meta_box_callback',
        'testimonial',
        'normal',
        'high'
    );
    
    // Team members meta box
    add_meta_box(
        'aqualuxe_team_member_meta',
        __('Team Member Details', 'aqualuxe'),
        'aqualuxe_team_member_meta_box_callback',
        'team_member',
        'normal',
        'high'
    );
    
    // Services meta box
    add_meta_box(
        'aqualuxe_service_meta',
        __('Service Details', 'aqualuxe'),
        'aqualuxe_service_meta_box_callback',
        'service',
        'normal',
        'high'
    );
    
    // Projects meta box
    add_meta_box(
        'aqualuxe_project_meta',
        __('Project Details', 'aqualuxe'),
        'aqualuxe_project_meta_box_callback',
        'project',
        'normal',
        'high'
    );
    
    // Events meta box
    add_meta_box(
        'aqualuxe_event_meta',
        __('Event Details', 'aqualuxe'),
        'aqualuxe_event_meta_box_callback',
        'event',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'aqualuxe_register_meta_boxes');

/**
 * Testimonial meta box callback
 *
 * @param WP_Post $post Post object
 */
function aqualuxe_testimonial_meta_box_callback($post) {
    // Add nonce for security
    wp_nonce_field('aqualuxe_testimonial_meta_box', 'aqualuxe_testimonial_meta_box_nonce');
    
    // Get meta values
    $client_name = get_post_meta($post->ID, '_aqualuxe_testimonial_client_name', true);
    $client_position = get_post_meta($post->ID, '_aqualuxe_testimonial_client_position', true);
    $client_company = get_post_meta($post->ID, '_aqualuxe_testimonial_client_company', true);
    $rating = get_post_meta($post->ID, '_aqualuxe_testimonial_rating', true);
    
    // Output fields
    ?>
    <p>
        <label for="aqualuxe_testimonial_client_name"><?php _e('Client Name', 'aqualuxe'); ?></label>
        <input type="text" id="aqualuxe_testimonial_client_name" name="aqualuxe_testimonial_client_name" value="<?php echo esc_attr($client_name); ?>" class="widefat">
    </p>
    <p>
        <label for="aqualuxe_testimonial_client_position"><?php _e('Client Position', 'aqualuxe'); ?></label>
        <input type="text" id="aqualuxe_testimonial_client_position" name="aqualuxe_testimonial_client_position" value="<?php echo esc_attr($client_position); ?>" class="widefat">
    </p>
    <p>
        <label for="aqualuxe_testimonial_client_company"><?php _e('Client Company', 'aqualuxe'); ?></label>
        <input type="text" id="aqualuxe_testimonial_client_company" name="aqualuxe_testimonial_client_company" value="<?php echo esc_attr($client_company); ?>" class="widefat">
    </p>
    <p>
        <label for="aqualuxe_testimonial_rating"><?php _e('Rating (1-5)', 'aqualuxe'); ?></label>
        <input type="number" id="aqualuxe_testimonial_rating" name="aqualuxe_testimonial_rating" value="<?php echo esc_attr($rating); ?>" class="small-text" min="1" max="5" step="1">
    </p>
    <?php
}

/**
 * Team member meta box callback
 *
 * @param WP_Post $post Post object
 */
function aqualuxe_team_member_meta_box_callback($post) {
    // Add nonce for security
    wp_nonce_field('aqualuxe_team_member_meta_box', 'aqualuxe_team_member_meta_box_nonce');
    
    // Get meta values
    $position = get_post_meta($post->ID, '_aqualuxe_team_member_position', true);
    $email = get_post_meta($post->ID, '_aqualuxe_team_member_email', true);
    $phone = get_post_meta($post->ID, '_aqualuxe_team_member_phone', true);
    $facebook = get_post_meta($post->ID, '_aqualuxe_team_member_facebook', true);
    $twitter = get_post_meta($post->ID, '_aqualuxe_team_member_twitter', true);
    $linkedin = get_post_meta($post->ID, '_aqualuxe_team_member_linkedin', true);
    $instagram = get_post_meta($post->ID, '_aqualuxe_team_member_instagram', true);
    
    // Output fields
    ?>
    <p>
        <label for="aqualuxe_team_member_position"><?php _e('Position', 'aqualuxe'); ?></label>
        <input type="text" id="aqualuxe_team_member_position" name="aqualuxe_team_member_position" value="<?php echo esc_attr($position); ?>" class="widefat">
    </p>
    <p>
        <label for="aqualuxe_team_member_email"><?php _e('Email', 'aqualuxe'); ?></label>
        <input type="email" id="aqualuxe_team_member_email" name="aqualuxe_team_member_email" value="<?php echo esc_attr($email); ?>" class="widefat">
    </p>
    <p>
        <label for="aqualuxe_team_member_phone"><?php _e('Phone', 'aqualuxe'); ?></label>
        <input type="text" id="aqualuxe_team_member_phone" name="aqualuxe_team_member_phone" value="<?php echo esc_attr($phone); ?>" class="widefat">
    </p>
    <h4><?php _e('Social Media', 'aqualuxe'); ?></h4>
    <p>
        <label for="aqualuxe_team_member_facebook"><?php _e('Facebook URL', 'aqualuxe'); ?></label>
        <input type="url" id="aqualuxe_team_member_facebook" name="aqualuxe_team_member_facebook" value="<?php echo esc_url($facebook); ?>" class="widefat">
    </p>
    <p>
        <label for="aqualuxe_team_member_twitter"><?php _e('Twitter URL', 'aqualuxe'); ?></label>
        <input type="url" id="aqualuxe_team_member_twitter" name="aqualuxe_team_member_twitter" value="<?php echo esc_url($twitter); ?>" class="widefat">
    </p>
    <p>
        <label for="aqualuxe_team_member_linkedin"><?php _e('LinkedIn URL', 'aqualuxe'); ?></label>
        <input type="url" id="aqualuxe_team_member_linkedin" name="aqualuxe_team_member_linkedin" value="<?php echo esc_url($linkedin); ?>" class="widefat">
    </p>
    <p>
        <label for="aqualuxe_team_member_instagram"><?php _e('Instagram URL', 'aqualuxe'); ?></label>
        <input type="url" id="aqualuxe_team_member_instagram" name="aqualuxe_team_member_instagram" value="<?php echo esc_url($instagram); ?>" class="widefat">
    </p>
    <?php
}

/**
 * Service meta box callback
 *
 * @param WP_Post $post Post object
 */
function aqualuxe_service_meta_box_callback($post) {
    // Add nonce for security
    wp_nonce_field('aqualuxe_service_meta_box', 'aqualuxe_service_meta_box_nonce');
    
    // Get meta values
    $icon = get_post_meta($post->ID, '_aqualuxe_service_icon', true);
    $price = get_post_meta($post->ID, '_aqualuxe_service_price', true);
    $duration = get_post_meta($post->ID, '_aqualuxe_service_duration', true);
    $button_text = get_post_meta($post->ID, '_aqualuxe_service_button_text', true);
    $button_url = get_post_meta($post->ID, '_aqualuxe_service_button_url', true);
    
    // Output fields
    ?>
    <p>
        <label for="aqualuxe_service_icon"><?php _e('Icon', 'aqualuxe'); ?></label>
        <input type="text" id="aqualuxe_service_icon" name="aqualuxe_service_icon" value="<?php echo esc_attr($icon); ?>" class="widefat">
        <span class="description"><?php _e('Enter an icon name (e.g., "fish", "water", "tank")', 'aqualuxe'); ?></span>
    </p>
    <p>
        <label for="aqualuxe_service_price"><?php _e('Price', 'aqualuxe'); ?></label>
        <input type="text" id="aqualuxe_service_price" name="aqualuxe_service_price" value="<?php echo esc_attr($price); ?>" class="widefat">
        <span class="description"><?php _e('Enter the service price (e.g., "$99", "$99-$199", "From $99")', 'aqualuxe'); ?></span>
    </p>
    <p>
        <label for="aqualuxe_service_duration"><?php _e('Duration', 'aqualuxe'); ?></label>
        <input type="text" id="aqualuxe_service_duration" name="aqualuxe_service_duration" value="<?php echo esc_attr($duration); ?>" class="widefat">
        <span class="description"><?php _e('Enter the service duration (e.g., "1 hour", "2-3 hours", "1 week")', 'aqualuxe'); ?></span>
    </p>
    <p>
        <label for="aqualuxe_service_button_text"><?php _e('Button Text', 'aqualuxe'); ?></label>
        <input type="text" id="aqualuxe_service_button_text" name="aqualuxe_service_button_text" value="<?php echo esc_attr($button_text); ?>" class="widefat">
    </p>
    <p>
        <label for="aqualuxe_service_button_url"><?php _e('Button URL', 'aqualuxe'); ?></label>
        <input type="url" id="aqualuxe_service_button_url" name="aqualuxe_service_button_url" value="<?php echo esc_url($button_url); ?>" class="widefat">
    </p>
    <?php
}

/**
 * Project meta box callback
 *
 * @param WP_Post $post Post object
 */
function aqualuxe_project_meta_box_callback($post) {
    // Add nonce for security
    wp_nonce_field('aqualuxe_project_meta_box', 'aqualuxe_project_meta_box_nonce');
    
    // Get meta values
    $client = get_post_meta($post->ID, '_aqualuxe_project_client', true);
    $location = get_post_meta($post->ID, '_aqualuxe_project_location', true);
    $completion_date = get_post_meta($post->ID, '_aqualuxe_project_completion_date', true);
    $budget = get_post_meta($post->ID, '_aqualuxe_project_budget', true);
    $gallery = get_post_meta($post->ID, '_aqualuxe_project_gallery', true);
    $video_url = get_post_meta($post->ID, '_aqualuxe_project_video_url', true);
    
    // Output fields
    ?>
    <p>
        <label for="aqualuxe_project_client"><?php _e('Client', 'aqualuxe'); ?></label>
        <input type="text" id="aqualuxe_project_client" name="aqualuxe_project_client" value="<?php echo esc_attr($client); ?>" class="widefat">
    </p>
    <p>
        <label for="aqualuxe_project_location"><?php _e('Location', 'aqualuxe'); ?></label>
        <input type="text" id="aqualuxe_project_location" name="aqualuxe_project_location" value="<?php echo esc_attr($location); ?>" class="widefat">
    </p>
    <p>
        <label for="aqualuxe_project_completion_date"><?php _e('Completion Date', 'aqualuxe'); ?></label>
        <input type="text" id="aqualuxe_project_completion_date" name="aqualuxe_project_completion_date" value="<?php echo esc_attr($completion_date); ?>" class="widefat">
    </p>
    <p>
        <label for="aqualuxe_project_budget"><?php _e('Budget', 'aqualuxe'); ?></label>
        <input type="text" id="aqualuxe_project_budget" name="aqualuxe_project_budget" value="<?php echo esc_attr($budget); ?>" class="widefat">
    </p>
    <p>
        <label for="aqualuxe_project_gallery"><?php _e('Gallery', 'aqualuxe'); ?></label>
        <input type="text" id="aqualuxe_project_gallery" name="aqualuxe_project_gallery" value="<?php echo esc_attr($gallery); ?>" class="widefat">
        <span class="description"><?php _e('Enter image IDs separated by commas', 'aqualuxe'); ?></span>
    </p>
    <p>
        <label for="aqualuxe_project_video_url"><?php _e('Video URL', 'aqualuxe'); ?></label>
        <input type="url" id="aqualuxe_project_video_url" name="aqualuxe_project_video_url" value="<?php echo esc_url($video_url); ?>" class="widefat">
        <span class="description"><?php _e('Enter YouTube or Vimeo URL', 'aqualuxe'); ?></span>
    </p>
    <?php
}

/**
 * Event meta box callback
 *
 * @param WP_Post $post Post object
 */
function aqualuxe_event_meta_box_callback($post) {
    // Add nonce for security
    wp_nonce_field('aqualuxe_event_meta_box', 'aqualuxe_event_meta_box_nonce');
    
    // Get meta values
    $start_date = get_post_meta($post->ID, '_aqualuxe_event_start_date', true);
    $end_date = get_post_meta($post->ID, '_aqualuxe_event_end_date', true);
    $time = get_post_meta($post->ID, '_aqualuxe_event_time', true);
    $location = get_post_meta($post->ID, '_aqualuxe_event_location', true);
    $address = get_post_meta($post->ID, '_aqualuxe_event_address', true);
    $map_url = get_post_meta($post->ID, '_aqualuxe_event_map_url', true);
    $cost = get_post_meta($post->ID, '_aqualuxe_event_cost', true);
    $registration_url = get_post_meta($post->ID, '_aqualuxe_event_registration_url', true);
    
    // Output fields
    ?>
    <p>
        <label for="aqualuxe_event_start_date"><?php _e('Start Date', 'aqualuxe'); ?></label>
        <input type="date" id="aqualuxe_event_start_date" name="aqualuxe_event_start_date" value="<?php echo esc_attr($start_date); ?>" class="widefat">
    </p>
    <p>
        <label for="aqualuxe_event_end_date"><?php _e('End Date', 'aqualuxe'); ?></label>
        <input type="date" id="aqualuxe_event_end_date" name="aqualuxe_event_end_date" value="<?php echo esc_attr($end_date); ?>" class="widefat">
    </p>
    <p>
        <label for="aqualuxe_event_time"><?php _e('Time', 'aqualuxe'); ?></label>
        <input type="text" id="aqualuxe_event_time" name="aqualuxe_event_time" value="<?php echo esc_attr($time); ?>" class="widefat">
        <span class="description"><?php _e('Enter event time (e.g., "10:00 AM - 2:00 PM")', 'aqualuxe'); ?></span>
    </p>
    <p>
        <label for="aqualuxe_event_location"><?php _e('Location', 'aqualuxe'); ?></label>
        <input type="text" id="aqualuxe_event_location" name="aqualuxe_event_location" value="<?php echo esc_attr($location); ?>" class="widefat">
    </p>
    <p>
        <label for="aqualuxe_event_address"><?php _e('Address', 'aqualuxe'); ?></label>
        <textarea id="aqualuxe_event_address" name="aqualuxe_event_address" class="widefat" rows="3"><?php echo esc_textarea($address); ?></textarea>
    </p>
    <p>
        <label for="aqualuxe_event_map_url"><?php _e('Map URL', 'aqualuxe'); ?></label>
        <input type="url" id="aqualuxe_event_map_url" name="aqualuxe_event_map_url" value="<?php echo esc_url($map_url); ?>" class="widefat">
    </p>
    <p>
        <label for="aqualuxe_event_cost"><?php _e('Cost', 'aqualuxe'); ?></label>
        <input type="text" id="aqualuxe_event_cost" name="aqualuxe_event_cost" value="<?php echo esc_attr($cost); ?>" class="widefat">
    </p>
    <p>
        <label for="aqualuxe_event_registration_url"><?php _e('Registration URL', 'aqualuxe'); ?></label>
        <input type="url" id="aqualuxe_event_registration_url" name="aqualuxe_event_registration_url" value="<?php echo esc_url($registration_url); ?>" class="widefat">
    </p>
    <?php
}

/**
 * Save meta box data
 *
 * @param int $post_id Post ID
 */
function aqualuxe_save_meta_box_data($post_id) {
    // Check if nonce is set
    if (!isset($_POST['aqualuxe_testimonial_meta_box_nonce']) &&
        !isset($_POST['aqualuxe_team_member_meta_box_nonce']) &&
        !isset($_POST['aqualuxe_service_meta_box_nonce']) &&
        !isset($_POST['aqualuxe_project_meta_box_nonce']) &&
        !isset($_POST['aqualuxe_event_meta_box_nonce'])) {
        return;
    }
    
    // Verify nonce
    if ((isset($_POST['aqualuxe_testimonial_meta_box_nonce']) && !wp_verify_nonce($_POST['aqualuxe_testimonial_meta_box_nonce'], 'aqualuxe_testimonial_meta_box')) ||
        (isset($_POST['aqualuxe_team_member_meta_box_nonce']) && !wp_verify_nonce($_POST['aqualuxe_team_member_meta_box_nonce'], 'aqualuxe_team_member_meta_box')) ||
        (isset($_POST['aqualuxe_service_meta_box_nonce']) && !wp_verify_nonce($_POST['aqualuxe_service_meta_box_nonce'], 'aqualuxe_service_meta_box')) ||
        (isset($_POST['aqualuxe_project_meta_box_nonce']) && !wp_verify_nonce($_POST['aqualuxe_project_meta_box_nonce'], 'aqualuxe_project_meta_box')) ||
        (isset($_POST['aqualuxe_event_meta_box_nonce']) && !wp_verify_nonce($_POST['aqualuxe_event_meta_box_nonce'], 'aqualuxe_event_meta_box'))) {
        return;
    }
    
    // Check if autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    // Check permissions
    if (isset($_POST['post_type'])) {
        if ($_POST['post_type'] === 'testimonial' && !current_user_can('edit_post', $post_id)) {
            return;
        }
        if ($_POST['post_type'] === 'team_member' && !current_user_can('edit_post', $post_id)) {
            return;
        }
        if ($_POST['post_type'] === 'service' && !current_user_can('edit_post', $post_id)) {
            return;
        }
        if ($_POST['post_type'] === 'project' && !current_user_can('edit_post', $post_id)) {
            return;
        }
        if ($_POST['post_type'] === 'event' && !current_user_can('edit_post', $post_id)) {
            return;
        }
        if ($_POST['post_type'] === 'faq' && !current_user_can('edit_post', $post_id)) {
            return;
        }
    }
    
    // Save testimonial meta
    if (isset($_POST['aqualuxe_testimonial_client_name'])) {
        update_post_meta($post_id, '_aqualuxe_testimonial_client_name', sanitize_text_field($_POST['aqualuxe_testimonial_client_name']));
    }
    if (isset($_POST['aqualuxe_testimonial_client_position'])) {
        update_post_meta($post_id, '_aqualuxe_testimonial_client_position', sanitize_text_field($_POST['aqualuxe_testimonial_client_position']));
    }
    if (isset($_POST['aqualuxe_testimonial_client_company'])) {
        update_post_meta($post_id, '_aqualuxe_testimonial_client_company', sanitize_text_field($_POST['aqualuxe_testimonial_client_company']));
    }
    if (isset($_POST['aqualuxe_testimonial_rating'])) {
        update_post_meta($post_id, '_aqualuxe_testimonial_rating', intval($_POST['aqualuxe_testimonial_rating']));
    }
    
    // Save team member meta
    if (isset($_POST['aqualuxe_team_member_position'])) {
        update_post_meta($post_id, '_aqualuxe_team_member_position', sanitize_text_field($_POST['aqualuxe_team_member_position']));
    }
    if (isset($_POST['aqualuxe_team_member_email'])) {
        update_post_meta($post_id, '_aqualuxe_team_member_email', sanitize_email($_POST['aqualuxe_team_member_email']));
    }
    if (isset($_POST['aqualuxe_team_member_phone'])) {
        update_post_meta($post_id, '_aqualuxe_team_member_phone', sanitize_text_field($_POST['aqualuxe_team_member_phone']));
    }
    if (isset($_POST['aqualuxe_team_member_facebook'])) {
        update_post_meta($post_id, '_aqualuxe_team_member_facebook', esc_url_raw($_POST['aqualuxe_team_member_facebook']));
    }
    if (isset($_POST['aqualuxe_team_member_twitter'])) {
        update_post_meta($post_id, '_aqualuxe_team_member_twitter', esc_url_raw($_POST['aqualuxe_team_member_twitter']));
    }
    if (isset($_POST['aqualuxe_team_member_linkedin'])) {
        update_post_meta($post_id, '_aqualuxe_team_member_linkedin', esc_url_raw($_POST['aqualuxe_team_member_linkedin']));
    }
    if (isset($_POST['aqualuxe_team_member_instagram'])) {
        update_post_meta($post_id, '_aqualuxe_team_member_instagram', esc_url_raw($_POST['aqualuxe_team_member_instagram']));
    }
    
    // Save service meta
    if (isset($_POST['aqualuxe_service_icon'])) {
        update_post_meta($post_id, '_aqualuxe_service_icon', sanitize_text_field($_POST['aqualuxe_service_icon']));
    }
    if (isset($_POST['aqualuxe_service_price'])) {
        update_post_meta($post_id, '_aqualuxe_service_price', sanitize_text_field($_POST['aqualuxe_service_price']));
    }
    if (isset($_POST['aqualuxe_service_duration'])) {
        update_post_meta($post_id, '_aqualuxe_service_duration', sanitize_text_field($_POST['aqualuxe_service_duration']));
    }
    if (isset($_POST['aqualuxe_service_button_text'])) {
        update_post_meta($post_id, '_aqualuxe_service_button_text', sanitize_text_field($_POST['aqualuxe_service_button_text']));
    }
    if (isset($_POST['aqualuxe_service_button_url'])) {
        update_post_meta($post_id, '_aqualuxe_service_button_url', esc_url_raw($_POST['aqualuxe_service_button_url']));
    }
    
    // Save project meta
    if (isset($_POST['aqualuxe_project_client'])) {
        update_post_meta($post_id, '_aqualuxe_project_client', sanitize_text_field($_POST['aqualuxe_project_client']));
    }
    if (isset($_POST['aqualuxe_project_location'])) {
        update_post_meta($post_id, '_aqualuxe_project_location', sanitize_text_field($_POST['aqualuxe_project_location']));
    }
    if (isset($_POST['aqualuxe_project_completion_date'])) {
        update_post_meta($post_id, '_aqualuxe_project_completion_date', sanitize_text_field($_POST['aqualuxe_project_completion_date']));
    }
    if (isset($_POST['aqualuxe_project_budget'])) {
        update_post_meta($post_id, '_aqualuxe_project_budget', sanitize_text_field($_POST['aqualuxe_project_budget']));
    }
    if (isset($_POST['aqualuxe_project_gallery'])) {
        update_post_meta($post_id, '_aqualuxe_project_gallery', sanitize_text_field($_POST['aqualuxe_project_gallery']));
    }
    if (isset($_POST['aqualuxe_project_video_url'])) {
        update_post_meta($post_id, '_aqualuxe_project_video_url', esc_url_raw($_POST['aqualuxe_project_video_url']));
    }
    
    // Save event meta
    if (isset($_POST['aqualuxe_event_start_date'])) {
        update_post_meta($post_id, '_aqualuxe_event_start_date', sanitize_text_field($_POST['aqualuxe_event_start_date']));
    }
    if (isset($_POST['aqualuxe_event_end_date'])) {
        update_post_meta($post_id, '_aqualuxe_event_end_date', sanitize_text_field($_POST['aqualuxe_event_end_date']));
    }
    if (isset($_POST['aqualuxe_event_time'])) {
        update_post_meta($post_id, '_aqualuxe_event_time', sanitize_text_field($_POST['aqualuxe_event_time']));
    }
    if (isset($_POST['aqualuxe_event_location'])) {
        update_post_meta($post_id, '_aqualuxe_event_location', sanitize_text_field($_POST['aqualuxe_event_location']));
    }
    if (isset($_POST['aqualuxe_event_address'])) {
        update_post_meta($post_id, '_aqualuxe_event_address', sanitize_textarea_field($_POST['aqualuxe_event_address']));
    }
    if (isset($_POST['aqualuxe_event_map_url'])) {
        update_post_meta($post_id, '_aqualuxe_event_map_url', esc_url_raw($_POST['aqualuxe_event_map_url']));
    }
    if (isset($_POST['aqualuxe_event_cost'])) {
        update_post_meta($post_id, '_aqualuxe_event_cost', sanitize_text_field($_POST['aqualuxe_event_cost']));
    }
    if (isset($_POST['aqualuxe_event_registration_url'])) {
        update_post_meta($post_id, '_aqualuxe_event_registration_url', esc_url_raw($_POST['aqualuxe_event_registration_url']));
    }
}
add_action('save_post', 'aqualuxe_save_meta_box_data');