<?php
/**
 * Theme setup functions
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

/**
 * Register custom image sizes
 */
function aqualuxe_register_image_sizes() {
    // Add custom image sizes
    add_image_size('aqualuxe-hero', 1920, 800, true);
    add_image_size('aqualuxe-featured', 1200, 675, true);
    add_image_size('aqualuxe-blog', 800, 450, true);
    add_image_size('aqualuxe-product-thumbnail', 300, 300, true);
    add_image_size('aqualuxe-product-gallery', 600, 600, true);
    add_image_size('aqualuxe-square', 400, 400, true);
}
add_action('after_setup_theme', 'aqualuxe_register_image_sizes');

/**
 * Add image sizes to media library dropdown
 *
 * @param array $sizes Image sizes
 * @return array
 */
function aqualuxe_custom_image_sizes($sizes) {
    return array_merge($sizes, array(
        'aqualuxe-hero' => __('Hero Image', 'aqualuxe'),
        'aqualuxe-featured' => __('Featured Image', 'aqualuxe'),
        'aqualuxe-blog' => __('Blog Image', 'aqualuxe'),
        'aqualuxe-product-thumbnail' => __('Product Thumbnail', 'aqualuxe'),
        'aqualuxe-product-gallery' => __('Product Gallery', 'aqualuxe'),
        'aqualuxe-square' => __('Square Image', 'aqualuxe'),
    ));
}
add_filter('image_size_names_choose', 'aqualuxe_custom_image_sizes');

/**
 * Register custom post types
 */
function aqualuxe_register_post_types() {
    // Services post type
    register_post_type('aqualuxe_service', array(
        'labels' => array(
            'name' => __('Services', 'aqualuxe'),
            'singular_name' => __('Service', 'aqualuxe'),
            'add_new' => __('Add New', 'aqualuxe'),
            'add_new_item' => __('Add New Service', 'aqualuxe'),
            'edit_item' => __('Edit Service', 'aqualuxe'),
            'new_item' => __('New Service', 'aqualuxe'),
            'view_item' => __('View Service', 'aqualuxe'),
            'search_items' => __('Search Services', 'aqualuxe'),
            'not_found' => __('No services found', 'aqualuxe'),
            'not_found_in_trash' => __('No services found in Trash', 'aqualuxe'),
            'parent_item_colon' => __('Parent Service:', 'aqualuxe'),
            'menu_name' => __('Services', 'aqualuxe'),
        ),
        'public' => true,
        'has_archive' => true,
        'show_in_rest' => true,
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'menu_icon' => 'dashicons-admin-tools',
        'rewrite' => array('slug' => 'services'),
    ));

    // Testimonials post type
    register_post_type('aqualuxe_testimonial', array(
        'labels' => array(
            'name' => __('Testimonials', 'aqualuxe'),
            'singular_name' => __('Testimonial', 'aqualuxe'),
            'add_new' => __('Add New', 'aqualuxe'),
            'add_new_item' => __('Add New Testimonial', 'aqualuxe'),
            'edit_item' => __('Edit Testimonial', 'aqualuxe'),
            'new_item' => __('New Testimonial', 'aqualuxe'),
            'view_item' => __('View Testimonial', 'aqualuxe'),
            'search_items' => __('Search Testimonials', 'aqualuxe'),
            'not_found' => __('No testimonials found', 'aqualuxe'),
            'not_found_in_trash' => __('No testimonials found in Trash', 'aqualuxe'),
            'parent_item_colon' => __('Parent Testimonial:', 'aqualuxe'),
            'menu_name' => __('Testimonials', 'aqualuxe'),
        ),
        'public' => true,
        'has_archive' => true,
        'show_in_rest' => true,
        'supports' => array('title', 'editor', 'thumbnail', 'custom-fields'),
        'menu_icon' => 'dashicons-format-quote',
        'rewrite' => array('slug' => 'testimonials'),
    ));

    // Team members post type
    register_post_type('aqualuxe_team', array(
        'labels' => array(
            'name' => __('Team Members', 'aqualuxe'),
            'singular_name' => __('Team Member', 'aqualuxe'),
            'add_new' => __('Add New', 'aqualuxe'),
            'add_new_item' => __('Add New Team Member', 'aqualuxe'),
            'edit_item' => __('Edit Team Member', 'aqualuxe'),
            'new_item' => __('New Team Member', 'aqualuxe'),
            'view_item' => __('View Team Member', 'aqualuxe'),
            'search_items' => __('Search Team Members', 'aqualuxe'),
            'not_found' => __('No team members found', 'aqualuxe'),
            'not_found_in_trash' => __('No team members found in Trash', 'aqualuxe'),
            'parent_item_colon' => __('Parent Team Member:', 'aqualuxe'),
            'menu_name' => __('Team', 'aqualuxe'),
        ),
        'public' => true,
        'has_archive' => true,
        'show_in_rest' => true,
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'menu_icon' => 'dashicons-groups',
        'rewrite' => array('slug' => 'team'),
    ));

    // Events post type
    register_post_type('aqualuxe_event', array(
        'labels' => array(
            'name' => __('Events', 'aqualuxe'),
            'singular_name' => __('Event', 'aqualuxe'),
            'add_new' => __('Add New', 'aqualuxe'),
            'add_new_item' => __('Add New Event', 'aqualuxe'),
            'edit_item' => __('Edit Event', 'aqualuxe'),
            'new_item' => __('New Event', 'aqualuxe'),
            'view_item' => __('View Event', 'aqualuxe'),
            'search_items' => __('Search Events', 'aqualuxe'),
            'not_found' => __('No events found', 'aqualuxe'),
            'not_found_in_trash' => __('No events found in Trash', 'aqualuxe'),
            'parent_item_colon' => __('Parent Event:', 'aqualuxe'),
            'menu_name' => __('Events', 'aqualuxe'),
        ),
        'public' => true,
        'has_archive' => true,
        'show_in_rest' => true,
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'menu_icon' => 'dashicons-calendar',
        'rewrite' => array('slug' => 'events'),
    ));

    // FAQ post type
    register_post_type('aqualuxe_faq', array(
        'labels' => array(
            'name' => __('FAQs', 'aqualuxe'),
            'singular_name' => __('FAQ', 'aqualuxe'),
            'add_new' => __('Add New', 'aqualuxe'),
            'add_new_item' => __('Add New FAQ', 'aqualuxe'),
            'edit_item' => __('Edit FAQ', 'aqualuxe'),
            'new_item' => __('New FAQ', 'aqualuxe'),
            'view_item' => __('View FAQ', 'aqualuxe'),
            'search_items' => __('Search FAQs', 'aqualuxe'),
            'not_found' => __('No FAQs found', 'aqualuxe'),
            'not_found_in_trash' => __('No FAQs found in Trash', 'aqualuxe'),
            'parent_item_colon' => __('Parent FAQ:', 'aqualuxe'),
            'menu_name' => __('FAQs', 'aqualuxe'),
        ),
        'public' => true,
        'has_archive' => true,
        'show_in_rest' => true,
        'supports' => array('title', 'editor', 'custom-fields'),
        'menu_icon' => 'dashicons-editor-help',
        'rewrite' => array('slug' => 'faqs'),
    ));
}
add_action('init', 'aqualuxe_register_post_types');

/**
 * Register custom taxonomies
 */
function aqualuxe_register_taxonomies() {
    // Service categories
    register_taxonomy('aqualuxe_service_category', 'aqualuxe_service', array(
        'labels' => array(
            'name' => __('Service Categories', 'aqualuxe'),
            'singular_name' => __('Service Category', 'aqualuxe'),
            'search_items' => __('Search Service Categories', 'aqualuxe'),
            'all_items' => __('All Service Categories', 'aqualuxe'),
            'parent_item' => __('Parent Service Category', 'aqualuxe'),
            'parent_item_colon' => __('Parent Service Category:', 'aqualuxe'),
            'edit_item' => __('Edit Service Category', 'aqualuxe'),
            'update_item' => __('Update Service Category', 'aqualuxe'),
            'add_new_item' => __('Add New Service Category', 'aqualuxe'),
            'new_item_name' => __('New Service Category Name', 'aqualuxe'),
            'menu_name' => __('Categories', 'aqualuxe'),
        ),
        'hierarchical' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'show_in_rest' => true,
        'rewrite' => array('slug' => 'service-category'),
    ));

    // Event categories
    register_taxonomy('aqualuxe_event_category', 'aqualuxe_event', array(
        'labels' => array(
            'name' => __('Event Categories', 'aqualuxe'),
            'singular_name' => __('Event Category', 'aqualuxe'),
            'search_items' => __('Search Event Categories', 'aqualuxe'),
            'all_items' => __('All Event Categories', 'aqualuxe'),
            'parent_item' => __('Parent Event Category', 'aqualuxe'),
            'parent_item_colon' => __('Parent Event Category:', 'aqualuxe'),
            'edit_item' => __('Edit Event Category', 'aqualuxe'),
            'update_item' => __('Update Event Category', 'aqualuxe'),
            'add_new_item' => __('Add New Event Category', 'aqualuxe'),
            'new_item_name' => __('New Event Category Name', 'aqualuxe'),
            'menu_name' => __('Categories', 'aqualuxe'),
        ),
        'hierarchical' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'show_in_rest' => true,
        'rewrite' => array('slug' => 'event-category'),
    ));

    // FAQ categories
    register_taxonomy('aqualuxe_faq_category', 'aqualuxe_faq', array(
        'labels' => array(
            'name' => __('FAQ Categories', 'aqualuxe'),
            'singular_name' => __('FAQ Category', 'aqualuxe'),
            'search_items' => __('Search FAQ Categories', 'aqualuxe'),
            'all_items' => __('All FAQ Categories', 'aqualuxe'),
            'parent_item' => __('Parent FAQ Category', 'aqualuxe'),
            'parent_item_colon' => __('Parent FAQ Category:', 'aqualuxe'),
            'edit_item' => __('Edit FAQ Category', 'aqualuxe'),
            'update_item' => __('Update FAQ Category', 'aqualuxe'),
            'add_new_item' => __('Add New FAQ Category', 'aqualuxe'),
            'new_item_name' => __('New FAQ Category Name', 'aqualuxe'),
            'menu_name' => __('Categories', 'aqualuxe'),
        ),
        'hierarchical' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'show_in_rest' => true,
        'rewrite' => array('slug' => 'faq-category'),
    ));

    // Team member departments
    register_taxonomy('aqualuxe_team_department', 'aqualuxe_team', array(
        'labels' => array(
            'name' => __('Departments', 'aqualuxe'),
            'singular_name' => __('Department', 'aqualuxe'),
            'search_items' => __('Search Departments', 'aqualuxe'),
            'all_items' => __('All Departments', 'aqualuxe'),
            'parent_item' => __('Parent Department', 'aqualuxe'),
            'parent_item_colon' => __('Parent Department:', 'aqualuxe'),
            'edit_item' => __('Edit Department', 'aqualuxe'),
            'update_item' => __('Update Department', 'aqualuxe'),
            'add_new_item' => __('Add New Department', 'aqualuxe'),
            'new_item_name' => __('New Department Name', 'aqualuxe'),
            'menu_name' => __('Departments', 'aqualuxe'),
        ),
        'hierarchical' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'show_in_rest' => true,
        'rewrite' => array('slug' => 'department'),
    ));
}
add_action('init', 'aqualuxe_register_taxonomies');

/**
 * Register custom meta boxes
 */
function aqualuxe_register_meta_boxes() {
    // Service meta box
    add_meta_box(
        'aqualuxe_service_details',
        __('Service Details', 'aqualuxe'),
        'aqualuxe_service_details_callback',
        'aqualuxe_service',
        'normal',
        'high'
    );

    // Event meta box
    add_meta_box(
        'aqualuxe_event_details',
        __('Event Details', 'aqualuxe'),
        'aqualuxe_event_details_callback',
        'aqualuxe_event',
        'normal',
        'high'
    );

    // Team member meta box
    add_meta_box(
        'aqualuxe_team_details',
        __('Team Member Details', 'aqualuxe'),
        'aqualuxe_team_details_callback',
        'aqualuxe_team',
        'normal',
        'high'
    );

    // Testimonial meta box
    add_meta_box(
        'aqualuxe_testimonial_details',
        __('Testimonial Details', 'aqualuxe'),
        'aqualuxe_testimonial_details_callback',
        'aqualuxe_testimonial',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'aqualuxe_register_meta_boxes');

/**
 * Service details meta box callback
 *
 * @param WP_Post $post Post object
 */
function aqualuxe_service_details_callback($post) {
    // Add nonce for security
    wp_nonce_field('aqualuxe_service_details', 'aqualuxe_service_details_nonce');

    // Get current values
    $price = get_post_meta($post->ID, '_aqualuxe_service_price', true);
    $duration = get_post_meta($post->ID, '_aqualuxe_service_duration', true);
    $icon = get_post_meta($post->ID, '_aqualuxe_service_icon', true);
    $featured = get_post_meta($post->ID, '_aqualuxe_service_featured', true);

    // Output fields
    ?>
    <div class="aqualuxe-meta-box">
        <div class="aqualuxe-meta-field">
            <label for="aqualuxe_service_price"><?php _e('Price', 'aqualuxe'); ?></label>
            <input type="text" id="aqualuxe_service_price" name="aqualuxe_service_price" value="<?php echo esc_attr($price); ?>" />
            <p class="description"><?php _e('Enter the service price (e.g. 99.99).', 'aqualuxe'); ?></p>
        </div>

        <div class="aqualuxe-meta-field">
            <label for="aqualuxe_service_duration"><?php _e('Duration', 'aqualuxe'); ?></label>
            <input type="text" id="aqualuxe_service_duration" name="aqualuxe_service_duration" value="<?php echo esc_attr($duration); ?>" />
            <p class="description"><?php _e('Enter the service duration (e.g. 2 hours).', 'aqualuxe'); ?></p>
        </div>

        <div class="aqualuxe-meta-field">
            <label for="aqualuxe_service_icon"><?php _e('Icon', 'aqualuxe'); ?></label>
            <input type="text" id="aqualuxe_service_icon" name="aqualuxe_service_icon" value="<?php echo esc_attr($icon); ?>" />
            <p class="description"><?php _e('Enter the service icon class (e.g. fa-fish).', 'aqualuxe'); ?></p>
        </div>

        <div class="aqualuxe-meta-field">
            <label for="aqualuxe_service_featured"><?php _e('Featured', 'aqualuxe'); ?></label>
            <input type="checkbox" id="aqualuxe_service_featured" name="aqualuxe_service_featured" value="1" <?php checked($featured, '1'); ?> />
            <p class="description"><?php _e('Check to mark this service as featured.', 'aqualuxe'); ?></p>
        </div>
    </div>
    <?php
}

/**
 * Event details meta box callback
 *
 * @param WP_Post $post Post object
 */
function aqualuxe_event_details_callback($post) {
    // Add nonce for security
    wp_nonce_field('aqualuxe_event_details', 'aqualuxe_event_details_nonce');

    // Get current values
    $start_date = get_post_meta($post->ID, '_aqualuxe_event_start_date', true);
    $end_date = get_post_meta($post->ID, '_aqualuxe_event_end_date', true);
    $location = get_post_meta($post->ID, '_aqualuxe_event_location', true);
    $price = get_post_meta($post->ID, '_aqualuxe_event_price', true);
    $featured = get_post_meta($post->ID, '_aqualuxe_event_featured', true);

    // Output fields
    ?>
    <div class="aqualuxe-meta-box">
        <div class="aqualuxe-meta-field">
            <label for="aqualuxe_event_start_date"><?php _e('Start Date', 'aqualuxe'); ?></label>
            <input type="datetime-local" id="aqualuxe_event_start_date" name="aqualuxe_event_start_date" value="<?php echo esc_attr($start_date); ?>" />
            <p class="description"><?php _e('Enter the event start date and time.', 'aqualuxe'); ?></p>
        </div>

        <div class="aqualuxe-meta-field">
            <label for="aqualuxe_event_end_date"><?php _e('End Date', 'aqualuxe'); ?></label>
            <input type="datetime-local" id="aqualuxe_event_end_date" name="aqualuxe_event_end_date" value="<?php echo esc_attr($end_date); ?>" />
            <p class="description"><?php _e('Enter the event end date and time.', 'aqualuxe'); ?></p>
        </div>

        <div class="aqualuxe-meta-field">
            <label for="aqualuxe_event_location"><?php _e('Location', 'aqualuxe'); ?></label>
            <input type="text" id="aqualuxe_event_location" name="aqualuxe_event_location" value="<?php echo esc_attr($location); ?>" />
            <p class="description"><?php _e('Enter the event location.', 'aqualuxe'); ?></p>
        </div>

        <div class="aqualuxe-meta-field">
            <label for="aqualuxe_event_price"><?php _e('Price', 'aqualuxe'); ?></label>
            <input type="text" id="aqualuxe_event_price" name="aqualuxe_event_price" value="<?php echo esc_attr($price); ?>" />
            <p class="description"><?php _e('Enter the event price (e.g. 99.99). Leave empty if free.', 'aqualuxe'); ?></p>
        </div>

        <div class="aqualuxe-meta-field">
            <label for="aqualuxe_event_featured"><?php _e('Featured', 'aqualuxe'); ?></label>
            <input type="checkbox" id="aqualuxe_event_featured" name="aqualuxe_event_featured" value="1" <?php checked($featured, '1'); ?> />
            <p class="description"><?php _e('Check to mark this event as featured.', 'aqualuxe'); ?></p>
        </div>
    </div>
    <?php
}

/**
 * Team member details meta box callback
 *
 * @param WP_Post $post Post object
 */
function aqualuxe_team_details_callback($post) {
    // Add nonce for security
    wp_nonce_field('aqualuxe_team_details', 'aqualuxe_team_details_nonce');

    // Get current values
    $position = get_post_meta($post->ID, '_aqualuxe_team_position', true);
    $email = get_post_meta($post->ID, '_aqualuxe_team_email', true);
    $phone = get_post_meta($post->ID, '_aqualuxe_team_phone', true);
    $facebook = get_post_meta($post->ID, '_aqualuxe_team_facebook', true);
    $twitter = get_post_meta($post->ID, '_aqualuxe_team_twitter', true);
    $linkedin = get_post_meta($post->ID, '_aqualuxe_team_linkedin', true);
    $instagram = get_post_meta($post->ID, '_aqualuxe_team_instagram', true);

    // Output fields
    ?>
    <div class="aqualuxe-meta-box">
        <div class="aqualuxe-meta-field">
            <label for="aqualuxe_team_position"><?php _e('Position', 'aqualuxe'); ?></label>
            <input type="text" id="aqualuxe_team_position" name="aqualuxe_team_position" value="<?php echo esc_attr($position); ?>" />
            <p class="description"><?php _e('Enter the team member position.', 'aqualuxe'); ?></p>
        </div>

        <div class="aqualuxe-meta-field">
            <label for="aqualuxe_team_email"><?php _e('Email', 'aqualuxe'); ?></label>
            <input type="email" id="aqualuxe_team_email" name="aqualuxe_team_email" value="<?php echo esc_attr($email); ?>" />
            <p class="description"><?php _e('Enter the team member email address.', 'aqualuxe'); ?></p>
        </div>

        <div class="aqualuxe-meta-field">
            <label for="aqualuxe_team_phone"><?php _e('Phone', 'aqualuxe'); ?></label>
            <input type="text" id="aqualuxe_team_phone" name="aqualuxe_team_phone" value="<?php echo esc_attr($phone); ?>" />
            <p class="description"><?php _e('Enter the team member phone number.', 'aqualuxe'); ?></p>
        </div>

        <h4><?php _e('Social Media', 'aqualuxe'); ?></h4>

        <div class="aqualuxe-meta-field">
            <label for="aqualuxe_team_facebook"><?php _e('Facebook', 'aqualuxe'); ?></label>
            <input type="url" id="aqualuxe_team_facebook" name="aqualuxe_team_facebook" value="<?php echo esc_attr($facebook); ?>" />
            <p class="description"><?php _e('Enter the Facebook profile URL.', 'aqualuxe'); ?></p>
        </div>

        <div class="aqualuxe-meta-field">
            <label for="aqualuxe_team_twitter"><?php _e('Twitter', 'aqualuxe'); ?></label>
            <input type="url" id="aqualuxe_team_twitter" name="aqualuxe_team_twitter" value="<?php echo esc_attr($twitter); ?>" />
            <p class="description"><?php _e('Enter the Twitter profile URL.', 'aqualuxe'); ?></p>
        </div>

        <div class="aqualuxe-meta-field">
            <label for="aqualuxe_team_linkedin"><?php _e('LinkedIn', 'aqualuxe'); ?></label>
            <input type="url" id="aqualuxe_team_linkedin" name="aqualuxe_team_linkedin" value="<?php echo esc_attr($linkedin); ?>" />
            <p class="description"><?php _e('Enter the LinkedIn profile URL.', 'aqualuxe'); ?></p>
        </div>

        <div class="aqualuxe-meta-field">
            <label for="aqualuxe_team_instagram"><?php _e('Instagram', 'aqualuxe'); ?></label>
            <input type="url" id="aqualuxe_team_instagram" name="aqualuxe_team_instagram" value="<?php echo esc_attr($instagram); ?>" />
            <p class="description"><?php _e('Enter the Instagram profile URL.', 'aqualuxe'); ?></p>
        </div>
    </div>
    <?php
}

/**
 * Testimonial details meta box callback
 *
 * @param WP_Post $post Post object
 */
function aqualuxe_testimonial_details_callback($post) {
    // Add nonce for security
    wp_nonce_field('aqualuxe_testimonial_details', 'aqualuxe_testimonial_details_nonce');

    // Get current values
    $author = get_post_meta($post->ID, '_aqualuxe_testimonial_author', true);
    $position = get_post_meta($post->ID, '_aqualuxe_testimonial_position', true);
    $company = get_post_meta($post->ID, '_aqualuxe_testimonial_company', true);
    $rating = get_post_meta($post->ID, '_aqualuxe_testimonial_rating', true);
    $featured = get_post_meta($post->ID, '_aqualuxe_testimonial_featured', true);

    // Output fields
    ?>
    <div class="aqualuxe-meta-box">
        <div class="aqualuxe-meta-field">
            <label for="aqualuxe_testimonial_author"><?php _e('Author', 'aqualuxe'); ?></label>
            <input type="text" id="aqualuxe_testimonial_author" name="aqualuxe_testimonial_author" value="<?php echo esc_attr($author); ?>" />
            <p class="description"><?php _e('Enter the testimonial author name.', 'aqualuxe'); ?></p>
        </div>

        <div class="aqualuxe-meta-field">
            <label for="aqualuxe_testimonial_position"><?php _e('Position', 'aqualuxe'); ?></label>
            <input type="text" id="aqualuxe_testimonial_position" name="aqualuxe_testimonial_position" value="<?php echo esc_attr($position); ?>" />
            <p class="description"><?php _e('Enter the author position.', 'aqualuxe'); ?></p>
        </div>

        <div class="aqualuxe-meta-field">
            <label for="aqualuxe_testimonial_company"><?php _e('Company', 'aqualuxe'); ?></label>
            <input type="text" id="aqualuxe_testimonial_company" name="aqualuxe_testimonial_company" value="<?php echo esc_attr($company); ?>" />
            <p class="description"><?php _e('Enter the author company.', 'aqualuxe'); ?></p>
        </div>

        <div class="aqualuxe-meta-field">
            <label for="aqualuxe_testimonial_rating"><?php _e('Rating', 'aqualuxe'); ?></label>
            <select id="aqualuxe_testimonial_rating" name="aqualuxe_testimonial_rating">
                <option value="5" <?php selected($rating, '5'); ?>><?php _e('5 Stars', 'aqualuxe'); ?></option>
                <option value="4.5" <?php selected($rating, '4.5'); ?>><?php _e('4.5 Stars', 'aqualuxe'); ?></option>
                <option value="4" <?php selected($rating, '4'); ?>><?php _e('4 Stars', 'aqualuxe'); ?></option>
                <option value="3.5" <?php selected($rating, '3.5'); ?>><?php _e('3.5 Stars', 'aqualuxe'); ?></option>
                <option value="3" <?php selected($rating, '3'); ?>><?php _e('3 Stars', 'aqualuxe'); ?></option>
                <option value="2.5" <?php selected($rating, '2.5'); ?>><?php _e('2.5 Stars', 'aqualuxe'); ?></option>
                <option value="2" <?php selected($rating, '2'); ?>><?php _e('2 Stars', 'aqualuxe'); ?></option>
                <option value="1.5" <?php selected($rating, '1.5'); ?>><?php _e('1.5 Stars', 'aqualuxe'); ?></option>
                <option value="1" <?php selected($rating, '1'); ?>><?php _e('1 Star', 'aqualuxe'); ?></option>
            </select>
            <p class="description"><?php _e('Select the testimonial rating.', 'aqualuxe'); ?></p>
        </div>

        <div class="aqualuxe-meta-field">
            <label for="aqualuxe_testimonial_featured"><?php _e('Featured', 'aqualuxe'); ?></label>
            <input type="checkbox" id="aqualuxe_testimonial_featured" name="aqualuxe_testimonial_featured" value="1" <?php checked($featured, '1'); ?> />
            <p class="description"><?php _e('Check to mark this testimonial as featured.', 'aqualuxe'); ?></p>
        </div>
    </div>
    <?php
}

/**
 * Save meta box data
 *
 * @param int $post_id Post ID
 */
function aqualuxe_save_meta_box_data($post_id) {
    // Check if we're doing an autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check the user's permissions
    if (isset($_POST['post_type'])) {
        if ('aqualuxe_service' === $_POST['post_type']) {
            if (!current_user_can('edit_post', $post_id)) {
                return;
            }
        }
    }

    // Service details
    if (isset($_POST['aqualuxe_service_details_nonce']) && wp_verify_nonce($_POST['aqualuxe_service_details_nonce'], 'aqualuxe_service_details')) {
        if (isset($_POST['aqualuxe_service_price'])) {
            update_post_meta($post_id, '_aqualuxe_service_price', sanitize_text_field($_POST['aqualuxe_service_price']));
        }
        if (isset($_POST['aqualuxe_service_duration'])) {
            update_post_meta($post_id, '_aqualuxe_service_duration', sanitize_text_field($_POST['aqualuxe_service_duration']));
        }
        if (isset($_POST['aqualuxe_service_icon'])) {
            update_post_meta($post_id, '_aqualuxe_service_icon', sanitize_text_field($_POST['aqualuxe_service_icon']));
        }
        update_post_meta($post_id, '_aqualuxe_service_featured', isset($_POST['aqualuxe_service_featured']) ? '1' : '');
    }

    // Event details
    if (isset($_POST['aqualuxe_event_details_nonce']) && wp_verify_nonce($_POST['aqualuxe_event_details_nonce'], 'aqualuxe_event_details')) {
        if (isset($_POST['aqualuxe_event_start_date'])) {
            update_post_meta($post_id, '_aqualuxe_event_start_date', sanitize_text_field($_POST['aqualuxe_event_start_date']));
        }
        if (isset($_POST['aqualuxe_event_end_date'])) {
            update_post_meta($post_id, '_aqualuxe_event_end_date', sanitize_text_field($_POST['aqualuxe_event_end_date']));
        }
        if (isset($_POST['aqualuxe_event_location'])) {
            update_post_meta($post_id, '_aqualuxe_event_location', sanitize_text_field($_POST['aqualuxe_event_location']));
        }
        if (isset($_POST['aqualuxe_event_price'])) {
            update_post_meta($post_id, '_aqualuxe_event_price', sanitize_text_field($_POST['aqualuxe_event_price']));
        }
        update_post_meta($post_id, '_aqualuxe_event_featured', isset($_POST['aqualuxe_event_featured']) ? '1' : '');
    }

    // Team member details
    if (isset($_POST['aqualuxe_team_details_nonce']) && wp_verify_nonce($_POST['aqualuxe_team_details_nonce'], 'aqualuxe_team_details')) {
        if (isset($_POST['aqualuxe_team_position'])) {
            update_post_meta($post_id, '_aqualuxe_team_position', sanitize_text_field($_POST['aqualuxe_team_position']));
        }
        if (isset($_POST['aqualuxe_team_email'])) {
            update_post_meta($post_id, '_aqualuxe_team_email', sanitize_email($_POST['aqualuxe_team_email']));
        }
        if (isset($_POST['aqualuxe_team_phone'])) {
            update_post_meta($post_id, '_aqualuxe_team_phone', sanitize_text_field($_POST['aqualuxe_team_phone']));
        }
        if (isset($_POST['aqualuxe_team_facebook'])) {
            update_post_meta($post_id, '_aqualuxe_team_facebook', esc_url_raw($_POST['aqualuxe_team_facebook']));
        }
        if (isset($_POST['aqualuxe_team_twitter'])) {
            update_post_meta($post_id, '_aqualuxe_team_twitter', esc_url_raw($_POST['aqualuxe_team_twitter']));
        }
        if (isset($_POST['aqualuxe_team_linkedin'])) {
            update_post_meta($post_id, '_aqualuxe_team_linkedin', esc_url_raw($_POST['aqualuxe_team_linkedin']));
        }
        if (isset($_POST['aqualuxe_team_instagram'])) {
            update_post_meta($post_id, '_aqualuxe_team_instagram', esc_url_raw($_POST['aqualuxe_team_instagram']));
        }
    }

    // Testimonial details
    if (isset($_POST['aqualuxe_testimonial_details_nonce']) && wp_verify_nonce($_POST['aqualuxe_testimonial_details_nonce'], 'aqualuxe_testimonial_details')) {
        if (isset($_POST['aqualuxe_testimonial_author'])) {
            update_post_meta($post_id, '_aqualuxe_testimonial_author', sanitize_text_field($_POST['aqualuxe_testimonial_author']));
        }
        if (isset($_POST['aqualuxe_testimonial_position'])) {
            update_post_meta($post_id, '_aqualuxe_testimonial_position', sanitize_text_field($_POST['aqualuxe_testimonial_position']));
        }
        if (isset($_POST['aqualuxe_testimonial_company'])) {
            update_post_meta($post_id, '_aqualuxe_testimonial_company', sanitize_text_field($_POST['aqualuxe_testimonial_company']));
        }
        if (isset($_POST['aqualuxe_testimonial_rating'])) {
            update_post_meta($post_id, '_aqualuxe_testimonial_rating', sanitize_text_field($_POST['aqualuxe_testimonial_rating']));
        }
        update_post_meta($post_id, '_aqualuxe_testimonial_featured', isset($_POST['aqualuxe_testimonial_featured']) ? '1' : '');
    }
}
add_action('save_post', 'aqualuxe_save_meta_box_data');

/**
 * Add custom admin styles
 */
function aqualuxe_admin_styles() {
    ?>
    <style>
        .aqualuxe-meta-box {
            padding: 10px;
        }
        .aqualuxe-meta-field {
            margin-bottom: 15px;
        }
        .aqualuxe-meta-field label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .aqualuxe-meta-field input[type="text"],
        .aqualuxe-meta-field input[type="email"],
        .aqualuxe-meta-field input[type="url"],
        .aqualuxe-meta-field input[type="datetime-local"],
        .aqualuxe-meta-field select {
            width: 100%;
            max-width: 400px;
        }
        .aqualuxe-meta-field .description {
            font-style: italic;
            color: #666;
        }
    </style>
    <?php
}
add_action('admin_head', 'aqualuxe_admin_styles');