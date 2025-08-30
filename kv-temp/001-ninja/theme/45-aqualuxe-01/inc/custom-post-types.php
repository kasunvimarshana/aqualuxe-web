<?php
/**
 * Custom Post Types for AquaLuxe Theme
 *
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Register custom post types
 */
function aqualuxe_register_post_types() {
    // Register Services post type
    register_post_type('aqualuxe_service', array(
        'labels' => array(
            'name'               => _x('Services', 'post type general name', 'aqualuxe'),
            'singular_name'      => _x('Service', 'post type singular name', 'aqualuxe'),
            'menu_name'          => _x('Services', 'admin menu', 'aqualuxe'),
            'name_admin_bar'     => _x('Service', 'add new on admin bar', 'aqualuxe'),
            'add_new'            => _x('Add New', 'service', 'aqualuxe'),
            'add_new_item'       => __('Add New Service', 'aqualuxe'),
            'new_item'           => __('New Service', 'aqualuxe'),
            'edit_item'          => __('Edit Service', 'aqualuxe'),
            'view_item'          => __('View Service', 'aqualuxe'),
            'all_items'          => __('All Services', 'aqualuxe'),
            'search_items'       => __('Search Services', 'aqualuxe'),
            'parent_item_colon'  => __('Parent Services:', 'aqualuxe'),
            'not_found'          => __('No services found.', 'aqualuxe'),
            'not_found_in_trash' => __('No services found in Trash.', 'aqualuxe')
        ),
        'description'         => __('Services offered by AquaLuxe', 'aqualuxe'),
        'public'              => true,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'query_var'           => true,
        'rewrite'             => array('slug' => 'services'),
        'capability_type'     => 'post',
        'has_archive'         => true,
        'hierarchical'        => false,
        'menu_position'       => 20,
        'menu_icon'           => 'dashicons-hammer',
        'supports'            => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'page-attributes'),
        'show_in_rest'        => true,
    ));

    // Register Events post type
    register_post_type('aqualuxe_event', array(
        'labels' => array(
            'name'               => _x('Events', 'post type general name', 'aqualuxe'),
            'singular_name'      => _x('Event', 'post type singular name', 'aqualuxe'),
            'menu_name'          => _x('Events', 'admin menu', 'aqualuxe'),
            'name_admin_bar'     => _x('Event', 'add new on admin bar', 'aqualuxe'),
            'add_new'            => _x('Add New', 'event', 'aqualuxe'),
            'add_new_item'       => __('Add New Event', 'aqualuxe'),
            'new_item'           => __('New Event', 'aqualuxe'),
            'edit_item'          => __('Edit Event', 'aqualuxe'),
            'view_item'          => __('View Event', 'aqualuxe'),
            'all_items'          => __('All Events', 'aqualuxe'),
            'search_items'       => __('Search Events', 'aqualuxe'),
            'parent_item_colon'  => __('Parent Events:', 'aqualuxe'),
            'not_found'          => __('No events found.', 'aqualuxe'),
            'not_found_in_trash' => __('No events found in Trash.', 'aqualuxe')
        ),
        'description'         => __('Events hosted by AquaLuxe', 'aqualuxe'),
        'public'              => true,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'query_var'           => true,
        'rewrite'             => array('slug' => 'events'),
        'capability_type'     => 'post',
        'has_archive'         => true,
        'hierarchical'        => false,
        'menu_position'       => 21,
        'menu_icon'           => 'dashicons-calendar-alt',
        'supports'            => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'page-attributes'),
        'show_in_rest'        => true,
    ));

    // Register Team Members post type
    register_post_type('aqualuxe_team', array(
        'labels' => array(
            'name'               => _x('Team Members', 'post type general name', 'aqualuxe'),
            'singular_name'      => _x('Team Member', 'post type singular name', 'aqualuxe'),
            'menu_name'          => _x('Team', 'admin menu', 'aqualuxe'),
            'name_admin_bar'     => _x('Team Member', 'add new on admin bar', 'aqualuxe'),
            'add_new'            => _x('Add New', 'team member', 'aqualuxe'),
            'add_new_item'       => __('Add New Team Member', 'aqualuxe'),
            'new_item'           => __('New Team Member', 'aqualuxe'),
            'edit_item'          => __('Edit Team Member', 'aqualuxe'),
            'view_item'          => __('View Team Member', 'aqualuxe'),
            'all_items'          => __('All Team Members', 'aqualuxe'),
            'search_items'       => __('Search Team Members', 'aqualuxe'),
            'parent_item_colon'  => __('Parent Team Members:', 'aqualuxe'),
            'not_found'          => __('No team members found.', 'aqualuxe'),
            'not_found_in_trash' => __('No team members found in Trash.', 'aqualuxe')
        ),
        'description'         => __('Team members of AquaLuxe', 'aqualuxe'),
        'public'              => true,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'query_var'           => true,
        'rewrite'             => array('slug' => 'team'),
        'capability_type'     => 'post',
        'has_archive'         => true,
        'hierarchical'        => false,
        'menu_position'       => 22,
        'menu_icon'           => 'dashicons-groups',
        'supports'            => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'page-attributes'),
        'show_in_rest'        => true,
    ));

    // Register Testimonials post type
    register_post_type('aqualuxe_testimonial', array(
        'labels' => array(
            'name'               => _x('Testimonials', 'post type general name', 'aqualuxe'),
            'singular_name'      => _x('Testimonial', 'post type singular name', 'aqualuxe'),
            'menu_name'          => _x('Testimonials', 'admin menu', 'aqualuxe'),
            'name_admin_bar'     => _x('Testimonial', 'add new on admin bar', 'aqualuxe'),
            'add_new'            => _x('Add New', 'testimonial', 'aqualuxe'),
            'add_new_item'       => __('Add New Testimonial', 'aqualuxe'),
            'new_item'           => __('New Testimonial', 'aqualuxe'),
            'edit_item'          => __('Edit Testimonial', 'aqualuxe'),
            'view_item'          => __('View Testimonial', 'aqualuxe'),
            'all_items'          => __('All Testimonials', 'aqualuxe'),
            'search_items'       => __('Search Testimonials', 'aqualuxe'),
            'parent_item_colon'  => __('Parent Testimonials:', 'aqualuxe'),
            'not_found'          => __('No testimonials found.', 'aqualuxe'),
            'not_found_in_trash' => __('No testimonials found in Trash.', 'aqualuxe')
        ),
        'description'         => __('Customer testimonials for AquaLuxe', 'aqualuxe'),
        'public'              => true,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'query_var'           => true,
        'rewrite'             => array('slug' => 'testimonials'),
        'capability_type'     => 'post',
        'has_archive'         => true,
        'hierarchical'        => false,
        'menu_position'       => 23,
        'menu_icon'           => 'dashicons-format-quote',
        'supports'            => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'page-attributes'),
        'show_in_rest'        => true,
    ));

    // Register Projects post type
    register_post_type('aqualuxe_project', array(
        'labels' => array(
            'name'               => _x('Projects', 'post type general name', 'aqualuxe'),
            'singular_name'      => _x('Project', 'post type singular name', 'aqualuxe'),
            'menu_name'          => _x('Projects', 'admin menu', 'aqualuxe'),
            'name_admin_bar'     => _x('Project', 'add new on admin bar', 'aqualuxe'),
            'add_new'            => _x('Add New', 'project', 'aqualuxe'),
            'add_new_item'       => __('Add New Project', 'aqualuxe'),
            'new_item'           => __('New Project', 'aqualuxe'),
            'edit_item'          => __('Edit Project', 'aqualuxe'),
            'view_item'          => __('View Project', 'aqualuxe'),
            'all_items'          => __('All Projects', 'aqualuxe'),
            'search_items'       => __('Search Projects', 'aqualuxe'),
            'parent_item_colon'  => __('Parent Projects:', 'aqualuxe'),
            'not_found'          => __('No projects found.', 'aqualuxe'),
            'not_found_in_trash' => __('No projects found in Trash.', 'aqualuxe')
        ),
        'description'         => __('Portfolio projects by AquaLuxe', 'aqualuxe'),
        'public'              => true,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'query_var'           => true,
        'rewrite'             => array('slug' => 'projects'),
        'capability_type'     => 'post',
        'has_archive'         => true,
        'hierarchical'        => false,
        'menu_position'       => 24,
        'menu_icon'           => 'dashicons-portfolio',
        'supports'            => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'page-attributes'),
        'show_in_rest'        => true,
    ));

    // Register FAQ post type
    register_post_type('aqualuxe_faq', array(
        'labels' => array(
            'name'               => _x('FAQs', 'post type general name', 'aqualuxe'),
            'singular_name'      => _x('FAQ', 'post type singular name', 'aqualuxe'),
            'menu_name'          => _x('FAQs', 'admin menu', 'aqualuxe'),
            'name_admin_bar'     => _x('FAQ', 'add new on admin bar', 'aqualuxe'),
            'add_new'            => _x('Add New', 'faq', 'aqualuxe'),
            'add_new_item'       => __('Add New FAQ', 'aqualuxe'),
            'new_item'           => __('New FAQ', 'aqualuxe'),
            'edit_item'          => __('Edit FAQ', 'aqualuxe'),
            'view_item'          => __('View FAQ', 'aqualuxe'),
            'all_items'          => __('All FAQs', 'aqualuxe'),
            'search_items'       => __('Search FAQs', 'aqualuxe'),
            'parent_item_colon'  => __('Parent FAQs:', 'aqualuxe'),
            'not_found'          => __('No FAQs found.', 'aqualuxe'),
            'not_found_in_trash' => __('No FAQs found in Trash.', 'aqualuxe')
        ),
        'description'         => __('Frequently Asked Questions', 'aqualuxe'),
        'public'              => true,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'query_var'           => true,
        'rewrite'             => array('slug' => 'faqs'),
        'capability_type'     => 'post',
        'has_archive'         => true,
        'hierarchical'        => false,
        'menu_position'       => 25,
        'menu_icon'           => 'dashicons-editor-help',
        'supports'            => array('title', 'editor', 'excerpt', 'custom-fields', 'page-attributes'),
        'show_in_rest'        => true,
    ));
}
add_action('init', 'aqualuxe_register_post_types');

/**
 * Add custom meta boxes for post types
 */
function aqualuxe_add_meta_boxes() {
    // Service Details meta box
    add_meta_box(
        'aqualuxe_service_details',
        __('Service Details', 'aqualuxe'),
        'aqualuxe_service_details_callback',
        'aqualuxe_service',
        'normal',
        'high'
    );

    // Event Details meta box
    add_meta_box(
        'aqualuxe_event_details',
        __('Event Details', 'aqualuxe'),
        'aqualuxe_event_details_callback',
        'aqualuxe_event',
        'normal',
        'high'
    );

    // Team Member Details meta box
    add_meta_box(
        'aqualuxe_team_details',
        __('Team Member Details', 'aqualuxe'),
        'aqualuxe_team_details_callback',
        'aqualuxe_team',
        'normal',
        'high'
    );

    // Testimonial Details meta box
    add_meta_box(
        'aqualuxe_testimonial_details',
        __('Testimonial Details', 'aqualuxe'),
        'aqualuxe_testimonial_details_callback',
        'aqualuxe_testimonial',
        'normal',
        'high'
    );

    // Project Details meta box
    add_meta_box(
        'aqualuxe_project_details',
        __('Project Details', 'aqualuxe'),
        'aqualuxe_project_details_callback',
        'aqualuxe_project',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'aqualuxe_add_meta_boxes');

/**
 * Service Details meta box callback
 *
 * @param WP_Post $post The post object.
 */
function aqualuxe_service_details_callback($post) {
    // Add nonce for security
    wp_nonce_field('aqualuxe_service_details_nonce', 'aqualuxe_service_details_nonce');

    // Get the saved values
    $service_price = get_post_meta($post->ID, '_aqualuxe_service_price', true);
    $service_duration = get_post_meta($post->ID, '_aqualuxe_service_duration', true);
    $service_icon = get_post_meta($post->ID, '_aqualuxe_service_icon', true);
    $service_features = get_post_meta($post->ID, '_aqualuxe_service_features', true);
    $service_booking_url = get_post_meta($post->ID, '_aqualuxe_service_booking_url', true);
    ?>
    <div class="aqualuxe-meta-box">
        <div class="aqualuxe-meta-field">
            <label for="aqualuxe_service_price"><?php esc_html_e('Price', 'aqualuxe'); ?></label>
            <input type="text" id="aqualuxe_service_price" name="aqualuxe_service_price" value="<?php echo esc_attr($service_price); ?>" />
            <p class="description"><?php esc_html_e('Enter the price for this service (e.g. $99 or $99-$199).', 'aqualuxe'); ?></p>
        </div>

        <div class="aqualuxe-meta-field">
            <label for="aqualuxe_service_duration"><?php esc_html_e('Duration', 'aqualuxe'); ?></label>
            <input type="text" id="aqualuxe_service_duration" name="aqualuxe_service_duration" value="<?php echo esc_attr($service_duration); ?>" />
            <p class="description"><?php esc_html_e('Enter the duration for this service (e.g. 2 hours or 1-2 days).', 'aqualuxe'); ?></p>
        </div>

        <div class="aqualuxe-meta-field">
            <label for="aqualuxe_service_icon"><?php esc_html_e('Icon', 'aqualuxe'); ?></label>
            <input type="text" id="aqualuxe_service_icon" name="aqualuxe_service_icon" value="<?php echo esc_attr($service_icon); ?>" />
            <p class="description"><?php esc_html_e('Enter a Font Awesome icon class (e.g. fa-fish).', 'aqualuxe'); ?></p>
        </div>

        <div class="aqualuxe-meta-field">
            <label for="aqualuxe_service_features"><?php esc_html_e('Features', 'aqualuxe'); ?></label>
            <textarea id="aqualuxe_service_features" name="aqualuxe_service_features" rows="5"><?php echo esc_textarea($service_features); ?></textarea>
            <p class="description"><?php esc_html_e('Enter features for this service, one per line.', 'aqualuxe'); ?></p>
        </div>

        <div class="aqualuxe-meta-field">
            <label for="aqualuxe_service_booking_url"><?php esc_html_e('Booking URL', 'aqualuxe'); ?></label>
            <input type="url" id="aqualuxe_service_booking_url" name="aqualuxe_service_booking_url" value="<?php echo esc_url($service_booking_url); ?>" />
            <p class="description"><?php esc_html_e('Enter a URL for booking this service.', 'aqualuxe'); ?></p>
        </div>
    </div>
    <?php
}

/**
 * Event Details meta box callback
 *
 * @param WP_Post $post The post object.
 */
function aqualuxe_event_details_callback($post) {
    // Add nonce for security
    wp_nonce_field('aqualuxe_event_details_nonce', 'aqualuxe_event_details_nonce');

    // Get the saved values
    $event_date = get_post_meta($post->ID, '_aqualuxe_event_date', true);
    $event_time = get_post_meta($post->ID, '_aqualuxe_event_time', true);
    $event_end_date = get_post_meta($post->ID, '_aqualuxe_event_end_date', true);
    $event_end_time = get_post_meta($post->ID, '_aqualuxe_event_end_time', true);
    $event_location = get_post_meta($post->ID, '_aqualuxe_event_location', true);
    $event_address = get_post_meta($post->ID, '_aqualuxe_event_address', true);
    $event_map = get_post_meta($post->ID, '_aqualuxe_event_map', true);
    $event_price = get_post_meta($post->ID, '_aqualuxe_event_price', true);
    $event_registration_url = get_post_meta($post->ID, '_aqualuxe_event_registration_url', true);
    ?>
    <div class="aqualuxe-meta-box">
        <div class="aqualuxe-meta-field">
            <label for="aqualuxe_event_date"><?php esc_html_e('Start Date', 'aqualuxe'); ?></label>
            <input type="date" id="aqualuxe_event_date" name="aqualuxe_event_date" value="<?php echo esc_attr($event_date); ?>" />
        </div>

        <div class="aqualuxe-meta-field">
            <label for="aqualuxe_event_time"><?php esc_html_e('Start Time', 'aqualuxe'); ?></label>
            <input type="time" id="aqualuxe_event_time" name="aqualuxe_event_time" value="<?php echo esc_attr($event_time); ?>" />
        </div>

        <div class="aqualuxe-meta-field">
            <label for="aqualuxe_event_end_date"><?php esc_html_e('End Date', 'aqualuxe'); ?></label>
            <input type="date" id="aqualuxe_event_end_date" name="aqualuxe_event_end_date" value="<?php echo esc_attr($event_end_date); ?>" />
        </div>

        <div class="aqualuxe-meta-field">
            <label for="aqualuxe_event_end_time"><?php esc_html_e('End Time', 'aqualuxe'); ?></label>
            <input type="time" id="aqualuxe_event_end_time" name="aqualuxe_event_end_time" value="<?php echo esc_attr($event_end_time); ?>" />
        </div>

        <div class="aqualuxe-meta-field">
            <label for="aqualuxe_event_location"><?php esc_html_e('Location Name', 'aqualuxe'); ?></label>
            <input type="text" id="aqualuxe_event_location" name="aqualuxe_event_location" value="<?php echo esc_attr($event_location); ?>" />
        </div>

        <div class="aqualuxe-meta-field">
            <label for="aqualuxe_event_address"><?php esc_html_e('Address', 'aqualuxe'); ?></label>
            <textarea id="aqualuxe_event_address" name="aqualuxe_event_address" rows="3"><?php echo esc_textarea($event_address); ?></textarea>
        </div>

        <div class="aqualuxe-meta-field">
            <label for="aqualuxe_event_map"><?php esc_html_e('Google Maps Embed URL', 'aqualuxe'); ?></label>
            <input type="url" id="aqualuxe_event_map" name="aqualuxe_event_map" value="<?php echo esc_url($event_map); ?>" />
            <p class="description"><?php esc_html_e('Enter the Google Maps embed URL for this location.', 'aqualuxe'); ?></p>
        </div>

        <div class="aqualuxe-meta-field">
            <label for="aqualuxe_event_price"><?php esc_html_e('Price', 'aqualuxe'); ?></label>
            <input type="text" id="aqualuxe_event_price" name="aqualuxe_event_price" value="<?php echo esc_attr($event_price); ?>" />
            <p class="description"><?php esc_html_e('Enter the price for this event (e.g. $99 or Free).', 'aqualuxe'); ?></p>
        </div>

        <div class="aqualuxe-meta-field">
            <label for="aqualuxe_event_registration_url"><?php esc_html_e('Registration URL', 'aqualuxe'); ?></label>
            <input type="url" id="aqualuxe_event_registration_url" name="aqualuxe_event_registration_url" value="<?php echo esc_url($event_registration_url); ?>" />
            <p class="description"><?php esc_html_e('Enter a URL for registering for this event.', 'aqualuxe'); ?></p>
        </div>
    </div>
    <?php
}

/**
 * Team Member Details meta box callback
 *
 * @param WP_Post $post The post object.
 */
function aqualuxe_team_details_callback($post) {
    // Add nonce for security
    wp_nonce_field('aqualuxe_team_details_nonce', 'aqualuxe_team_details_nonce');

    // Get the saved values
    $team_position = get_post_meta($post->ID, '_aqualuxe_team_position', true);
    $team_email = get_post_meta($post->ID, '_aqualuxe_team_email', true);
    $team_phone = get_post_meta($post->ID, '_aqualuxe_team_phone', true);
    $team_facebook = get_post_meta($post->ID, '_aqualuxe_team_facebook', true);
    $team_twitter = get_post_meta($post->ID, '_aqualuxe_team_twitter', true);
    $team_linkedin = get_post_meta($post->ID, '_aqualuxe_team_linkedin', true);
    $team_instagram = get_post_meta($post->ID, '_aqualuxe_team_instagram', true);
    ?>
    <div class="aqualuxe-meta-box">
        <div class="aqualuxe-meta-field">
            <label for="aqualuxe_team_position"><?php esc_html_e('Position', 'aqualuxe'); ?></label>
            <input type="text" id="aqualuxe_team_position" name="aqualuxe_team_position" value="<?php echo esc_attr($team_position); ?>" />
        </div>

        <div class="aqualuxe-meta-field">
            <label for="aqualuxe_team_email"><?php esc_html_e('Email', 'aqualuxe'); ?></label>
            <input type="email" id="aqualuxe_team_email" name="aqualuxe_team_email" value="<?php echo esc_attr($team_email); ?>" />
        </div>

        <div class="aqualuxe-meta-field">
            <label for="aqualuxe_team_phone"><?php esc_html_e('Phone', 'aqualuxe'); ?></label>
            <input type="text" id="aqualuxe_team_phone" name="aqualuxe_team_phone" value="<?php echo esc_attr($team_phone); ?>" />
        </div>

        <h4><?php esc_html_e('Social Media', 'aqualuxe'); ?></h4>

        <div class="aqualuxe-meta-field">
            <label for="aqualuxe_team_facebook"><?php esc_html_e('Facebook URL', 'aqualuxe'); ?></label>
            <input type="url" id="aqualuxe_team_facebook" name="aqualuxe_team_facebook" value="<?php echo esc_url($team_facebook); ?>" />
        </div>

        <div class="aqualuxe-meta-field">
            <label for="aqualuxe_team_twitter"><?php esc_html_e('Twitter URL', 'aqualuxe'); ?></label>
            <input type="url" id="aqualuxe_team_twitter" name="aqualuxe_team_twitter" value="<?php echo esc_url($team_twitter); ?>" />
        </div>

        <div class="aqualuxe-meta-field">
            <label for="aqualuxe_team_linkedin"><?php esc_html_e('LinkedIn URL', 'aqualuxe'); ?></label>
            <input type="url" id="aqualuxe_team_linkedin" name="aqualuxe_team_linkedin" value="<?php echo esc_url($team_linkedin); ?>" />
        </div>

        <div class="aqualuxe-meta-field">
            <label for="aqualuxe_team_instagram"><?php esc_html_e('Instagram URL', 'aqualuxe'); ?></label>
            <input type="url" id="aqualuxe_team_instagram" name="aqualuxe_team_instagram" value="<?php echo esc_url($team_instagram); ?>" />
        </div>
    </div>
    <?php
}

/**
 * Testimonial Details meta box callback
 *
 * @param WP_Post $post The post object.
 */
function aqualuxe_testimonial_details_callback($post) {
    // Add nonce for security
    wp_nonce_field('aqualuxe_testimonial_details_nonce', 'aqualuxe_testimonial_details_nonce');

    // Get the saved values
    $testimonial_name = get_post_meta($post->ID, '_aqualuxe_testimonial_name', true);
    $testimonial_position = get_post_meta($post->ID, '_aqualuxe_testimonial_position', true);
    $testimonial_company = get_post_meta($post->ID, '_aqualuxe_testimonial_company', true);
    $testimonial_rating = get_post_meta($post->ID, '_aqualuxe_testimonial_rating', true);
    $testimonial_date = get_post_meta($post->ID, '_aqualuxe_testimonial_date', true);
    ?>
    <div class="aqualuxe-meta-box">
        <div class="aqualuxe-meta-field">
            <label for="aqualuxe_testimonial_name"><?php esc_html_e('Name', 'aqualuxe'); ?></label>
            <input type="text" id="aqualuxe_testimonial_name" name="aqualuxe_testimonial_name" value="<?php echo esc_attr($testimonial_name); ?>" />
            <p class="description"><?php esc_html_e('Enter the name of the person giving the testimonial.', 'aqualuxe'); ?></p>
        </div>

        <div class="aqualuxe-meta-field">
            <label for="aqualuxe_testimonial_position"><?php esc_html_e('Position', 'aqualuxe'); ?></label>
            <input type="text" id="aqualuxe_testimonial_position" name="aqualuxe_testimonial_position" value="<?php echo esc_attr($testimonial_position); ?>" />
        </div>

        <div class="aqualuxe-meta-field">
            <label for="aqualuxe_testimonial_company"><?php esc_html_e('Company', 'aqualuxe'); ?></label>
            <input type="text" id="aqualuxe_testimonial_company" name="aqualuxe_testimonial_company" value="<?php echo esc_attr($testimonial_company); ?>" />
        </div>

        <div class="aqualuxe-meta-field">
            <label for="aqualuxe_testimonial_rating"><?php esc_html_e('Rating (1-5)', 'aqualuxe'); ?></label>
            <input type="number" id="aqualuxe_testimonial_rating" name="aqualuxe_testimonial_rating" min="1" max="5" step="0.5" value="<?php echo esc_attr($testimonial_rating); ?>" />
        </div>

        <div class="aqualuxe-meta-field">
            <label for="aqualuxe_testimonial_date"><?php esc_html_e('Date', 'aqualuxe'); ?></label>
            <input type="date" id="aqualuxe_testimonial_date" name="aqualuxe_testimonial_date" value="<?php echo esc_attr($testimonial_date); ?>" />
        </div>
    </div>
    <?php
}

/**
 * Project Details meta box callback
 *
 * @param WP_Post $post The post object.
 */
function aqualuxe_project_details_callback($post) {
    // Add nonce for security
    wp_nonce_field('aqualuxe_project_details_nonce', 'aqualuxe_project_details_nonce');

    // Get the saved values
    $project_client = get_post_meta($post->ID, '_aqualuxe_project_client', true);
    $project_location = get_post_meta($post->ID, '_aqualuxe_project_location', true);
    $project_date = get_post_meta($post->ID, '_aqualuxe_project_date', true);
    $project_budget = get_post_meta($post->ID, '_aqualuxe_project_budget', true);
    $project_url = get_post_meta($post->ID, '_aqualuxe_project_url', true);
    $project_gallery = get_post_meta($post->ID, '_aqualuxe_project_gallery', true);
    ?>
    <div class="aqualuxe-meta-box">
        <div class="aqualuxe-meta-field">
            <label for="aqualuxe_project_client"><?php esc_html_e('Client', 'aqualuxe'); ?></label>
            <input type="text" id="aqualuxe_project_client" name="aqualuxe_project_client" value="<?php echo esc_attr($project_client); ?>" />
        </div>

        <div class="aqualuxe-meta-field">
            <label for="aqualuxe_project_location"><?php esc_html_e('Location', 'aqualuxe'); ?></label>
            <input type="text" id="aqualuxe_project_location" name="aqualuxe_project_location" value="<?php echo esc_attr($project_location); ?>" />
        </div>

        <div class="aqualuxe-meta-field">
            <label for="aqualuxe_project_date"><?php esc_html_e('Date', 'aqualuxe'); ?></label>
            <input type="date" id="aqualuxe_project_date" name="aqualuxe_project_date" value="<?php echo esc_attr($project_date); ?>" />
        </div>

        <div class="aqualuxe-meta-field">
            <label for="aqualuxe_project_budget"><?php esc_html_e('Budget', 'aqualuxe'); ?></label>
            <input type="text" id="aqualuxe_project_budget" name="aqualuxe_project_budget" value="<?php echo esc_attr($project_budget); ?>" />
        </div>

        <div class="aqualuxe-meta-field">
            <label for="aqualuxe_project_url"><?php esc_html_e('Project URL', 'aqualuxe'); ?></label>
            <input type="url" id="aqualuxe_project_url" name="aqualuxe_project_url" value="<?php echo esc_url($project_url); ?>" />
        </div>

        <div class="aqualuxe-meta-field">
            <label for="aqualuxe_project_gallery"><?php esc_html_e('Gallery', 'aqualuxe'); ?></label>
            <input type="hidden" id="aqualuxe_project_gallery" name="aqualuxe_project_gallery" value="<?php echo esc_attr($project_gallery); ?>" />
            <div id="aqualuxe_project_gallery_container">
                <?php
                if ($project_gallery) {
                    $gallery_ids = explode(',', $project_gallery);
                    foreach ($gallery_ids as $gallery_id) {
                        echo '<div class="gallery-image">';
                        echo wp_get_attachment_image($gallery_id, 'thumbnail');
                        echo '<a href="#" class="remove-gallery-image" data-id="' . esc_attr($gallery_id) . '">×</a>';
                        echo '</div>';
                    }
                }
                ?>
            </div>
            <button type="button" id="aqualuxe_project_gallery_button" class="button"><?php esc_html_e('Add Images', 'aqualuxe'); ?></button>
            <p class="description"><?php esc_html_e('Select images for the project gallery.', 'aqualuxe'); ?></p>
        </div>
    </div>

    <script>
    jQuery(document).ready(function($) {
        // Gallery image selection
        $('#aqualuxe_project_gallery_button').on('click', function(e) {
            e.preventDefault();

            var galleryFrame = wp.media({
                title: '<?php esc_html_e('Select Gallery Images', 'aqualuxe'); ?>',
                button: {
                    text: '<?php esc_html_e('Add to Gallery', 'aqualuxe'); ?>'
                },
                multiple: true
            });

            galleryFrame.on('select', function() {
                var attachments = galleryFrame.state().get('selection').toJSON();
                var galleryIds = $('#aqualuxe_project_gallery').val() ? $('#aqualuxe_project_gallery').val().split(',') : [];
                var galleryContainer = $('#aqualuxe_project_gallery_container');

                $.each(attachments, function(index, attachment) {
                    if ($.inArray(attachment.id.toString(), galleryIds) === -1) {
                        galleryIds.push(attachment.id);
                        galleryContainer.append(
                            '<div class="gallery-image">' +
                            '<img src="' + attachment.sizes.thumbnail.url + '" alt="" />' +
                            '<a href="#" class="remove-gallery-image" data-id="' + attachment.id + '">×</a>' +
                            '</div>'
                        );
                    }
                });

                $('#aqualuxe_project_gallery').val(galleryIds.join(','));
            });

            galleryFrame.open();
        });

        // Remove gallery image
        $(document).on('click', '.remove-gallery-image', function(e) {
            e.preventDefault();

            var imageId = $(this).data('id').toString();
            var galleryIds = $('#aqualuxe_project_gallery').val().split(',');
            var index = galleryIds.indexOf(imageId);

            if (index !== -1) {
                galleryIds.splice(index, 1);
                $('#aqualuxe_project_gallery').val(galleryIds.join(','));
                $(this).parent('.gallery-image').remove();
            }
        });
    });
    </script>

    <style>
    .aqualuxe-meta-box {
        margin: 10px 0;
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
    .aqualuxe-meta-field input[type="number"],
    .aqualuxe-meta-field input[type="date"],
    .aqualuxe-meta-field input[type="time"],
    .aqualuxe-meta-field textarea {
        width: 100%;
    }
    #aqualuxe_project_gallery_container {
        display: flex;
        flex-wrap: wrap;
        margin: 10px 0;
    }
    .gallery-image {
        position: relative;
        margin: 0 10px 10px 0;
    }
    .gallery-image img {
        width: 80px;
        height: 80px;
        object-fit: cover;
    }
    .remove-gallery-image {
        position: absolute;
        top: 0;
        right: 0;
        background: rgba(0, 0, 0, 0.5);
        color: #fff;
        width: 20px;
        height: 20px;
        line-height: 20px;
        text-align: center;
        text-decoration: none;
    }
    </style>
    <?php
}

/**
 * Save custom meta box data
 *
 * @param int $post_id The post ID.
 */
function aqualuxe_save_meta_box_data($post_id) {
    // Check if we're doing an autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check the user's permissions
    if (isset($_POST['post_type'])) {
        if ('aqualuxe_service' === $_POST['post_type']) {
            if (!current_user_can('edit_page', $post_id)) {
                return;
            }
        } elseif ('aqualuxe_event' === $_POST['post_type']) {
            if (!current_user_can('edit_page', $post_id)) {
                return;
            }
        } elseif ('aqualuxe_team' === $_POST['post_type']) {
            if (!current_user_can('edit_page', $post_id)) {
                return;
            }
        } elseif ('aqualuxe_testimonial' === $_POST['post_type']) {
            if (!current_user_can('edit_page', $post_id)) {
                return;
            }
        } elseif ('aqualuxe_project' === $_POST['post_type']) {
            if (!current_user_can('edit_page', $post_id)) {
                return;
            }
        } else {
            if (!current_user_can('edit_post', $post_id)) {
                return;
            }
        }
    }

    // Service meta fields
    if (isset($_POST['aqualuxe_service_details_nonce']) && wp_verify_nonce($_POST['aqualuxe_service_details_nonce'], 'aqualuxe_service_details_nonce')) {
        if (isset($_POST['aqualuxe_service_price'])) {
            update_post_meta($post_id, '_aqualuxe_service_price', sanitize_text_field($_POST['aqualuxe_service_price']));
        }
        if (isset($_POST['aqualuxe_service_duration'])) {
            update_post_meta($post_id, '_aqualuxe_service_duration', sanitize_text_field($_POST['aqualuxe_service_duration']));
        }
        if (isset($_POST['aqualuxe_service_icon'])) {
            update_post_meta($post_id, '_aqualuxe_service_icon', sanitize_text_field($_POST['aqualuxe_service_icon']));
        }
        if (isset($_POST['aqualuxe_service_features'])) {
            update_post_meta($post_id, '_aqualuxe_service_features', sanitize_textarea_field($_POST['aqualuxe_service_features']));
        }
        if (isset($_POST['aqualuxe_service_booking_url'])) {
            update_post_meta($post_id, '_aqualuxe_service_booking_url', esc_url_raw($_POST['aqualuxe_service_booking_url']));
        }
    }

    // Event meta fields
    if (isset($_POST['aqualuxe_event_details_nonce']) && wp_verify_nonce($_POST['aqualuxe_event_details_nonce'], 'aqualuxe_event_details_nonce')) {
        if (isset($_POST['aqualuxe_event_date'])) {
            update_post_meta($post_id, '_aqualuxe_event_date', sanitize_text_field($_POST['aqualuxe_event_date']));
        }
        if (isset($_POST['aqualuxe_event_time'])) {
            update_post_meta($post_id, '_aqualuxe_event_time', sanitize_text_field($_POST['aqualuxe_event_time']));
        }
        if (isset($_POST['aqualuxe_event_end_date'])) {
            update_post_meta($post_id, '_aqualuxe_event_end_date', sanitize_text_field($_POST['aqualuxe_event_end_date']));
        }
        if (isset($_POST['aqualuxe_event_end_time'])) {
            update_post_meta($post_id, '_aqualuxe_event_end_time', sanitize_text_field($_POST['aqualuxe_event_end_time']));
        }
        if (isset($_POST['aqualuxe_event_location'])) {
            update_post_meta($post_id, '_aqualuxe_event_location', sanitize_text_field($_POST['aqualuxe_event_location']));
        }
        if (isset($_POST['aqualuxe_event_address'])) {
            update_post_meta($post_id, '_aqualuxe_event_address', sanitize_textarea_field($_POST['aqualuxe_event_address']));
        }
        if (isset($_POST['aqualuxe_event_map'])) {
            update_post_meta($post_id, '_aqualuxe_event_map', esc_url_raw($_POST['aqualuxe_event_map']));
        }
        if (isset($_POST['aqualuxe_event_price'])) {
            update_post_meta($post_id, '_aqualuxe_event_price', sanitize_text_field($_POST['aqualuxe_event_price']));
        }
        if (isset($_POST['aqualuxe_event_registration_url'])) {
            update_post_meta($post_id, '_aqualuxe_event_registration_url', esc_url_raw($_POST['aqualuxe_event_registration_url']));
        }
    }

    // Team meta fields
    if (isset($_POST['aqualuxe_team_details_nonce']) && wp_verify_nonce($_POST['aqualuxe_team_details_nonce'], 'aqualuxe_team_details_nonce')) {
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

    // Testimonial meta fields
    if (isset($_POST['aqualuxe_testimonial_details_nonce']) && wp_verify_nonce($_POST['aqualuxe_testimonial_details_nonce'], 'aqualuxe_testimonial_details_nonce')) {
        if (isset($_POST['aqualuxe_testimonial_name'])) {
            update_post_meta($post_id, '_aqualuxe_testimonial_name', sanitize_text_field($_POST['aqualuxe_testimonial_name']));
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
        if (isset($_POST['aqualuxe_testimonial_date'])) {
            update_post_meta($post_id, '_aqualuxe_testimonial_date', sanitize_text_field($_POST['aqualuxe_testimonial_date']));
        }
    }

    // Project meta fields
    if (isset($_POST['aqualuxe_project_details_nonce']) && wp_verify_nonce($_POST['aqualuxe_project_details_nonce'], 'aqualuxe_project_details_nonce')) {
        if (isset($_POST['aqualuxe_project_client'])) {
            update_post_meta($post_id, '_aqualuxe_project_client', sanitize_text_field($_POST['aqualuxe_project_client']));
        }
        if (isset($_POST['aqualuxe_project_location'])) {
            update_post_meta($post_id, '_aqualuxe_project_location', sanitize_text_field($_POST['aqualuxe_project_location']));
        }
        if (isset($_POST['aqualuxe_project_date'])) {
            update_post_meta($post_id, '_aqualuxe_project_date', sanitize_text_field($_POST['aqualuxe_project_date']));
        }
        if (isset($_POST['aqualuxe_project_budget'])) {
            update_post_meta($post_id, '_aqualuxe_project_budget', sanitize_text_field($_POST['aqualuxe_project_budget']));
        }
        if (isset($_POST['aqualuxe_project_url'])) {
            update_post_meta($post_id, '_aqualuxe_project_url', esc_url_raw($_POST['aqualuxe_project_url']));
        }
        if (isset($_POST['aqualuxe_project_gallery'])) {
            update_post_meta($post_id, '_aqualuxe_project_gallery', sanitize_text_field($_POST['aqualuxe_project_gallery']));
        }
    }
}
add_action('save_post', 'aqualuxe_save_meta_box_data');

/**
 * Add custom columns to post type list tables
 */
function aqualuxe_add_custom_columns($columns) {
    $post_type = get_current_screen()->post_type;

    if ($post_type === 'aqualuxe_service') {
        $columns = array(
            'cb' => $columns['cb'],
            'title' => $columns['title'],
            'service_price' => __('Price', 'aqualuxe'),
            'service_duration' => __('Duration', 'aqualuxe'),
            'service_icon' => __('Icon', 'aqualuxe'),
            'date' => $columns['date'],
        );
    } elseif ($post_type === 'aqualuxe_event') {
        $columns = array(
            'cb' => $columns['cb'],
            'title' => $columns['title'],
            'event_date' => __('Date', 'aqualuxe'),
            'event_location' => __('Location', 'aqualuxe'),
            'event_price' => __('Price', 'aqualuxe'),
            'date' => $columns['date'],
        );
    } elseif ($post_type === 'aqualuxe_team') {
        $columns = array(
            'cb' => $columns['cb'],
            'thumbnail' => __('Photo', 'aqualuxe'),
            'title' => $columns['title'],
            'team_position' => __('Position', 'aqualuxe'),
            'team_email' => __('Email', 'aqualuxe'),
            'date' => $columns['date'],
        );
    } elseif ($post_type === 'aqualuxe_testimonial') {
        $columns = array(
            'cb' => $columns['cb'],
            'thumbnail' => __('Photo', 'aqualuxe'),
            'title' => $columns['title'],
            'testimonial_name' => __('Name', 'aqualuxe'),
            'testimonial_rating' => __('Rating', 'aqualuxe'),
            'date' => $columns['date'],
        );
    } elseif ($post_type === 'aqualuxe_project') {
        $columns = array(
            'cb' => $columns['cb'],
            'thumbnail' => __('Thumbnail', 'aqualuxe'),
            'title' => $columns['title'],
            'project_client' => __('Client', 'aqualuxe'),
            'project_date' => __('Date', 'aqualuxe'),
            'date' => $columns['date'],
        );
    } elseif ($post_type === 'aqualuxe_faq') {
        $columns = array(
            'cb' => $columns['cb'],
            'title' => $columns['title'],
            'faq_category' => __('Category', 'aqualuxe'),
            'date' => $columns['date'],
        );
    }

    return $columns;
}
add_filter('manage_posts_columns', 'aqualuxe_add_custom_columns');

/**
 * Display custom column content
 *
 * @param string $column The column name.
 * @param int $post_id The post ID.
 */
function aqualuxe_custom_column_content($column, $post_id) {
    switch ($column) {
        case 'service_price':
            echo esc_html(get_post_meta($post_id, '_aqualuxe_service_price', true));
            break;
        case 'service_duration':
            echo esc_html(get_post_meta($post_id, '_aqualuxe_service_duration', true));
            break;
        case 'service_icon':
            $icon = get_post_meta($post_id, '_aqualuxe_service_icon', true);
            if ($icon) {
                echo '<i class="' . esc_attr($icon) . '" aria-hidden="true"></i> ' . esc_html($icon);
            }
            break;
        case 'event_date':
            $date = get_post_meta($post_id, '_aqualuxe_event_date', true);
            $time = get_post_meta($post_id, '_aqualuxe_event_time', true);
            if ($date) {
                echo esc_html(date_i18n(get_option('date_format'), strtotime($date)));
                if ($time) {
                    echo ' ' . esc_html(date_i18n(get_option('time_format'), strtotime($time)));
                }
            }
            break;
        case 'event_location':
            echo esc_html(get_post_meta($post_id, '_aqualuxe_event_location', true));
            break;
        case 'event_price':
            echo esc_html(get_post_meta($post_id, '_aqualuxe_event_price', true));
            break;
        case 'thumbnail':
            if (has_post_thumbnail($post_id)) {
                echo get_the_post_thumbnail($post_id, array(50, 50));
            }
            break;
        case 'team_position':
            echo esc_html(get_post_meta($post_id, '_aqualuxe_team_position', true));
            break;
        case 'team_email':
            $email = get_post_meta($post_id, '_aqualuxe_team_email', true);
            if ($email) {
                echo '<a href="mailto:' . esc_attr($email) . '">' . esc_html($email) . '</a>';
            }
            break;
        case 'testimonial_name':
            echo esc_html(get_post_meta($post_id, '_aqualuxe_testimonial_name', true));
            break;
        case 'testimonial_rating':
            $rating = get_post_meta($post_id, '_aqualuxe_testimonial_rating', true);
            if ($rating) {
                $stars = '';
                $full_stars = floor($rating);
                $half_star = ($rating - $full_stars) >= 0.5;
                
                for ($i = 1; $i <= 5; $i++) {
                    if ($i <= $full_stars) {
                        $stars .= '<i class="fas fa-star" aria-hidden="true"></i>';
                    } elseif ($half_star && $i === $full_stars + 1) {
                        $stars .= '<i class="fas fa-star-half-alt" aria-hidden="true"></i>';
                    } else {
                        $stars .= '<i class="far fa-star" aria-hidden="true"></i>';
                    }
                }
                
                echo $stars . ' (' . esc_html($rating) . ')';
            }
            break;
        case 'project_client':
            echo esc_html(get_post_meta($post_id, '_aqualuxe_project_client', true));
            break;
        case 'project_date':
            $date = get_post_meta($post_id, '_aqualuxe_project_date', true);
            if ($date) {
                echo esc_html(date_i18n(get_option('date_format'), strtotime($date)));
            }
            break;
        case 'faq_category':
            $terms = get_the_terms($post_id, 'aqualuxe_faq_category');
            if ($terms && !is_wp_error($terms)) {
                $term_names = array();
                foreach ($terms as $term) {
                    $term_names[] = $term->name;
                }
                echo esc_html(implode(', ', $term_names));
            }
            break;
    }
}
add_action('manage_posts_custom_column', 'aqualuxe_custom_column_content', 10, 2);

/**
 * Make custom columns sortable
 *
 * @param array $columns The sortable columns.
 * @return array
 */
function aqualuxe_sortable_columns($columns) {
    $post_type = get_current_screen()->post_type;

    if ($post_type === 'aqualuxe_service') {
        $columns['service_price'] = 'service_price';
        $columns['service_duration'] = 'service_duration';
    } elseif ($post_type === 'aqualuxe_event') {
        $columns['event_date'] = 'event_date';
        $columns['event_location'] = 'event_location';
        $columns['event_price'] = 'event_price';
    } elseif ($post_type === 'aqualuxe_team') {
        $columns['team_position'] = 'team_position';
    } elseif ($post_type === 'aqualuxe_testimonial') {
        $columns['testimonial_name'] = 'testimonial_name';
        $columns['testimonial_rating'] = 'testimonial_rating';
    } elseif ($post_type === 'aqualuxe_project') {
        $columns['project_client'] = 'project_client';
        $columns['project_date'] = 'project_date';
    }

    return $columns;
}
add_filter('manage_edit-aqualuxe_service_sortable_columns', 'aqualuxe_sortable_columns');
add_filter('manage_edit-aqualuxe_event_sortable_columns', 'aqualuxe_sortable_columns');
add_filter('manage_edit-aqualuxe_team_sortable_columns', 'aqualuxe_sortable_columns');
add_filter('manage_edit-aqualuxe_testimonial_sortable_columns', 'aqualuxe_sortable_columns');
add_filter('manage_edit-aqualuxe_project_sortable_columns', 'aqualuxe_sortable_columns');
add_filter('manage_edit-aqualuxe_faq_sortable_columns', 'aqualuxe_sortable_columns');

/**
 * Handle custom column sorting
 *
 * @param WP_Query $query The query object.
 */
function aqualuxe_sort_custom_columns($query) {
    if (!is_admin()) {
        return;
    }

    $orderby = $query->get('orderby');

    if ($orderby === 'service_price') {
        $query->set('meta_key', '_aqualuxe_service_price');
        $query->set('orderby', 'meta_value');
    } elseif ($orderby === 'service_duration') {
        $query->set('meta_key', '_aqualuxe_service_duration');
        $query->set('orderby', 'meta_value');
    } elseif ($orderby === 'event_date') {
        $query->set('meta_key', '_aqualuxe_event_date');
        $query->set('orderby', 'meta_value');
    } elseif ($orderby === 'event_location') {
        $query->set('meta_key', '_aqualuxe_event_location');
        $query->set('orderby', 'meta_value');
    } elseif ($orderby === 'event_price') {
        $query->set('meta_key', '_aqualuxe_event_price');
        $query->set('orderby', 'meta_value');
    } elseif ($orderby === 'team_position') {
        $query->set('meta_key', '_aqualuxe_team_position');
        $query->set('orderby', 'meta_value');
    } elseif ($orderby === 'testimonial_name') {
        $query->set('meta_key', '_aqualuxe_testimonial_name');
        $query->set('orderby', 'meta_value');
    } elseif ($orderby === 'testimonial_rating') {
        $query->set('meta_key', '_aqualuxe_testimonial_rating');
        $query->set('orderby', 'meta_value_num');
    } elseif ($orderby === 'project_client') {
        $query->set('meta_key', '_aqualuxe_project_client');
        $query->set('orderby', 'meta_value');
    } elseif ($orderby === 'project_date') {
        $query->set('meta_key', '_aqualuxe_project_date');
        $query->set('orderby', 'meta_value');
    }
}
add_action('pre_get_posts', 'aqualuxe_sort_custom_columns');

/**
 * Add custom post type templates
 *
 * @param string $template The template path.
 * @return string
 */
function aqualuxe_custom_post_type_templates($template) {
    if (is_singular('aqualuxe_service')) {
        $new_template = locate_template(array('templates/single-service.php'));
        if ('' != $new_template) {
            return $new_template;
        }
    } elseif (is_post_type_archive('aqualuxe_service')) {
        $new_template = locate_template(array('templates/archive-service.php'));
        if ('' != $new_template) {
            return $new_template;
        }
    } elseif (is_singular('aqualuxe_event')) {
        $new_template = locate_template(array('templates/single-event.php'));
        if ('' != $new_template) {
            return $new_template;
        }
    } elseif (is_post_type_archive('aqualuxe_event')) {
        $new_template = locate_template(array('templates/archive-event.php'));
        if ('' != $new_template) {
            return $new_template;
        }
    } elseif (is_singular('aqualuxe_team')) {
        $new_template = locate_template(array('templates/single-team.php'));
        if ('' != $new_template) {
            return $new_template;
        }
    } elseif (is_post_type_archive('aqualuxe_team')) {
        $new_template = locate_template(array('templates/archive-team.php'));
        if ('' != $new_template) {
            return $new_template;
        }
    } elseif (is_singular('aqualuxe_testimonial')) {
        $new_template = locate_template(array('templates/single-testimonial.php'));
        if ('' != $new_template) {
            return $new_template;
        }
    } elseif (is_post_type_archive('aqualuxe_testimonial')) {
        $new_template = locate_template(array('templates/archive-testimonial.php'));
        if ('' != $new_template) {
            return $new_template;
        }
    } elseif (is_singular('aqualuxe_project')) {
        $new_template = locate_template(array('templates/single-project.php'));
        if ('' != $new_template) {
            return $new_template;
        }
    } elseif (is_post_type_archive('aqualuxe_project')) {
        $new_template = locate_template(array('templates/archive-project.php'));
        if ('' != $new_template) {
            return $new_template;
        }
    } elseif (is_singular('aqualuxe_faq')) {
        $new_template = locate_template(array('templates/single-faq.php'));
        if ('' != $new_template) {
            return $new_template;
        }
    } elseif (is_post_type_archive('aqualuxe_faq')) {
        $new_template = locate_template(array('templates/archive-faq.php'));
        if ('' != $new_template) {
            return $new_template;
        }
    }

    return $template;
}
add_filter('template_include', 'aqualuxe_custom_post_type_templates');

/**
 * Add custom post type shortcodes
 */

/**
 * Services shortcode
 *
 * @param array $atts Shortcode attributes.
 * @return string
 */
function aqualuxe_services_shortcode($atts) {
    $atts = shortcode_atts(array(
        'limit' => 6,
        'columns' => 3,
        'orderby' => 'date',
        'order' => 'DESC',
        'category' => '',
        'show_icon' => 'yes',
        'show_price' => 'yes',
        'show_duration' => 'yes',
        'show_excerpt' => 'yes',
        'show_button' => 'yes',
        'button_text' => __('Learn More', 'aqualuxe'),
        'class' => '',
    ), $atts);

    $query_args = array(
        'post_type' => 'aqualuxe_service',
        'posts_per_page' => absint($atts['limit']),
        'orderby' => $atts['orderby'],
        'order' => $atts['order'],
    );

    if (!empty($atts['category'])) {
        $query_args['tax_query'] = array(
            array(
                'taxonomy' => 'aqualuxe_service_category',
                'field' => 'slug',
                'terms' => explode(',', $atts['category']),
            ),
        );
    }

    $services = new WP_Query($query_args);

    if (!$services->have_posts()) {
        return '';
    }

    $output = '<div class="aqualuxe-services aqualuxe-services-columns-' . esc_attr($atts['columns']) . ' ' . esc_attr($atts['class']) . '">';
    $output .= '<div class="aqualuxe-services-grid">';

    while ($services->have_posts()) {
        $services->the_post();
        $id = get_the_ID();
        $icon = get_post_meta($id, '_aqualuxe_service_icon', true);
        $price = get_post_meta($id, '_aqualuxe_service_price', true);
        $duration = get_post_meta($id, '_aqualuxe_service_duration', true);

        $output .= '<div class="aqualuxe-service">';
        
        if ($atts['show_icon'] === 'yes' && $icon) {
            $output .= '<div class="aqualuxe-service-icon">';
            $output .= '<i class="' . esc_attr($icon) . '" aria-hidden="true"></i>';
            $output .= '</div>';
        }

        $output .= '<h3 class="aqualuxe-service-title">' . esc_html(get_the_title()) . '</h3>';

        if ($atts['show_price'] === 'yes' && $price) {
            $output .= '<div class="aqualuxe-service-price">' . esc_html($price) . '</div>';
        }

        if ($atts['show_duration'] === 'yes' && $duration) {
            $output .= '<div class="aqualuxe-service-duration">' . esc_html($duration) . '</div>';
        }

        if ($atts['show_excerpt'] === 'yes') {
            $output .= '<div class="aqualuxe-service-excerpt">' . wp_kses_post(get_the_excerpt()) . '</div>';
        }

        if ($atts['show_button'] === 'yes') {
            $output .= '<a href="' . esc_url(get_permalink()) . '" class="aqualuxe-service-button">' . esc_html($atts['button_text']) . '</a>';
        }

        $output .= '</div>';
    }

    $output .= '</div>';
    $output .= '</div>';

    wp_reset_postdata();

    return $output;
}
add_shortcode('aqualuxe_services', 'aqualuxe_services_shortcode');

/**
 * Events shortcode
 *
 * @param array $atts Shortcode attributes.
 * @return string
 */
function aqualuxe_events_shortcode($atts) {
    $atts = shortcode_atts(array(
        'limit' => 6,
        'columns' => 3,
        'orderby' => 'meta_value',
        'meta_key' => '_aqualuxe_event_date',
        'order' => 'ASC',
        'category' => '',
        'show_date' => 'yes',
        'show_location' => 'yes',
        'show_price' => 'yes',
        'show_excerpt' => 'yes',
        'show_button' => 'yes',
        'button_text' => __('View Event', 'aqualuxe'),
        'class' => '',
    ), $atts);

    $query_args = array(
        'post_type' => 'aqualuxe_event',
        'posts_per_page' => absint($atts['limit']),
        'orderby' => $atts['orderby'],
        'meta_key' => $atts['meta_key'],
        'order' => $atts['order'],
        'meta_query' => array(
            array(
                'key' => '_aqualuxe_event_date',
                'value' => date('Y-m-d'),
                'compare' => '>=',
                'type' => 'DATE',
            ),
        ),
    );

    if (!empty($atts['category'])) {
        $query_args['tax_query'] = array(
            array(
                'taxonomy' => 'aqualuxe_event_category',
                'field' => 'slug',
                'terms' => explode(',', $atts['category']),
            ),
        );
    }

    $events = new WP_Query($query_args);

    if (!$events->have_posts()) {
        return '';
    }

    $output = '<div class="aqualuxe-events aqualuxe-events-columns-' . esc_attr($atts['columns']) . ' ' . esc_attr($atts['class']) . '">';
    $output .= '<div class="aqualuxe-events-grid">';

    while ($events->have_posts()) {
        $events->the_post();
        $id = get_the_ID();
        $date = get_post_meta($id, '_aqualuxe_event_date', true);
        $time = get_post_meta($id, '_aqualuxe_event_time', true);
        $location = get_post_meta($id, '_aqualuxe_event_location', true);
        $price = get_post_meta($id, '_aqualuxe_event_price', true);

        $output .= '<div class="aqualuxe-event">';
        
        if (has_post_thumbnail()) {
            $output .= '<div class="aqualuxe-event-image">';
            $output .= '<a href="' . esc_url(get_permalink()) . '">';
            $output .= get_the_post_thumbnail($id, 'medium', array('class' => 'aqualuxe-event-thumbnail'));
            $output .= '</a>';
            $output .= '</div>';
        }

        $output .= '<div class="aqualuxe-event-content">';

        if ($atts['show_date'] === 'yes' && $date) {
            $output .= '<div class="aqualuxe-event-date">';
            $output .= '<i class="far fa-calendar-alt" aria-hidden="true"></i> ';
            $output .= esc_html(date_i18n(get_option('date_format'), strtotime($date)));
            if ($time) {
                $output .= ' ' . esc_html(date_i18n(get_option('time_format'), strtotime($time)));
            }
            $output .= '</div>';
        }

        $output .= '<h3 class="aqualuxe-event-title"><a href="' . esc_url(get_permalink()) . '">' . esc_html(get_the_title()) . '</a></h3>';

        if ($atts['show_location'] === 'yes' && $location) {
            $output .= '<div class="aqualuxe-event-location">';
            $output .= '<i class="fas fa-map-marker-alt" aria-hidden="true"></i> ';
            $output .= esc_html($location);
            $output .= '</div>';
        }

        if ($atts['show_price'] === 'yes' && $price) {
            $output .= '<div class="aqualuxe-event-price">';
            $output .= '<i class="fas fa-ticket-alt" aria-hidden="true"></i> ';
            $output .= esc_html($price);
            $output .= '</div>';
        }

        if ($atts['show_excerpt'] === 'yes') {
            $output .= '<div class="aqualuxe-event-excerpt">' . wp_kses_post(get_the_excerpt()) . '</div>';
        }

        if ($atts['show_button'] === 'yes') {
            $output .= '<a href="' . esc_url(get_permalink()) . '" class="aqualuxe-event-button">' . esc_html($atts['button_text']) . '</a>';
        }

        $output .= '</div>'; // .aqualuxe-event-content
        $output .= '</div>'; // .aqualuxe-event
    }

    $output .= '</div>';
    $output .= '</div>';

    wp_reset_postdata();

    return $output;
}
add_shortcode('aqualuxe_events', 'aqualuxe_events_shortcode');

/**
 * Team shortcode
 *
 * @param array $atts Shortcode attributes.
 * @return string
 */
function aqualuxe_team_shortcode($atts) {
    $atts = shortcode_atts(array(
        'limit' => 8,
        'columns' => 4,
        'orderby' => 'menu_order',
        'order' => 'ASC',
        'category' => '',
        'show_position' => 'yes',
        'show_bio' => 'yes',
        'show_social' => 'yes',
        'class' => '',
    ), $atts);

    $query_args = array(
        'post_type' => 'aqualuxe_team',
        'posts_per_page' => absint($atts['limit']),
        'orderby' => $atts['orderby'],
        'order' => $atts['order'],
    );

    if (!empty($atts['category'])) {
        $query_args['tax_query'] = array(
            array(
                'taxonomy' => 'aqualuxe_team_category',
                'field' => 'slug',
                'terms' => explode(',', $atts['category']),
            ),
        );
    }

    $team = new WP_Query($query_args);

    if (!$team->have_posts()) {
        return '';
    }

    $output = '<div class="aqualuxe-team aqualuxe-team-columns-' . esc_attr($atts['columns']) . ' ' . esc_attr($atts['class']) . '">';
    $output .= '<div class="aqualuxe-team-grid">';

    while ($team->have_posts()) {
        $team->the_post();
        $id = get_the_ID();
        $position = get_post_meta($id, '_aqualuxe_team_position', true);
        $facebook = get_post_meta($id, '_aqualuxe_team_facebook', true);
        $twitter = get_post_meta($id, '_aqualuxe_team_twitter', true);
        $linkedin = get_post_meta($id, '_aqualuxe_team_linkedin', true);
        $instagram = get_post_meta($id, '_aqualuxe_team_instagram', true);

        $output .= '<div class="aqualuxe-team-member">';
        
        if (has_post_thumbnail()) {
            $output .= '<div class="aqualuxe-team-member-image">';
            $output .= get_the_post_thumbnail($id, 'medium', array('class' => 'aqualuxe-team-member-thumbnail'));
            $output .= '</div>';
        }

        $output .= '<div class="aqualuxe-team-member-content">';
        $output .= '<h3 class="aqualuxe-team-member-name">' . esc_html(get_the_title()) . '</h3>';

        if ($atts['show_position'] === 'yes' && $position) {
            $output .= '<div class="aqualuxe-team-member-position">' . esc_html($position) . '</div>';
        }

        if ($atts['show_bio'] === 'yes') {
            $output .= '<div class="aqualuxe-team-member-bio">' . wp_kses_post(get_the_excerpt()) . '</div>';
        }

        if ($atts['show_social'] === 'yes' && ($facebook || $twitter || $linkedin || $instagram)) {
            $output .= '<div class="aqualuxe-team-member-social">';
            
            if ($facebook) {
                $output .= '<a href="' . esc_url($facebook) . '" class="aqualuxe-team-member-social-icon facebook" target="_blank" rel="noopener noreferrer">';
                $output .= '<i class="fab fa-facebook-f" aria-hidden="true"></i>';
                $output .= '<span class="screen-reader-text">' . esc_html__('Facebook', 'aqualuxe') . '</span>';
                $output .= '</a>';
            }
            
            if ($twitter) {
                $output .= '<a href="' . esc_url($twitter) . '" class="aqualuxe-team-member-social-icon twitter" target="_blank" rel="noopener noreferrer">';
                $output .= '<i class="fab fa-twitter" aria-hidden="true"></i>';
                $output .= '<span class="screen-reader-text">' . esc_html__('Twitter', 'aqualuxe') . '</span>';
                $output .= '</a>';
            }
            
            if ($linkedin) {
                $output .= '<a href="' . esc_url($linkedin) . '" class="aqualuxe-team-member-social-icon linkedin" target="_blank" rel="noopener noreferrer">';
                $output .= '<i class="fab fa-linkedin-in" aria-hidden="true"></i>';
                $output .= '<span class="screen-reader-text">' . esc_html__('LinkedIn', 'aqualuxe') . '</span>';
                $output .= '</a>';
            }
            
            if ($instagram) {
                $output .= '<a href="' . esc_url($instagram) . '" class="aqualuxe-team-member-social-icon instagram" target="_blank" rel="noopener noreferrer">';
                $output .= '<i class="fab fa-instagram" aria-hidden="true"></i>';
                $output .= '<span class="screen-reader-text">' . esc_html__('Instagram', 'aqualuxe') . '</span>';
                $output .= '</a>';
            }
            
            $output .= '</div>';
        }

        $output .= '</div>'; // .aqualuxe-team-member-content
        $output .= '</div>'; // .aqualuxe-team-member
    }

    $output .= '</div>';
    $output .= '</div>';

    wp_reset_postdata();

    return $output;
}
add_shortcode('aqualuxe_team', 'aqualuxe_team_shortcode');

/**
 * Testimonials shortcode
 *
 * @param array $atts Shortcode attributes.
 * @return string
 */
function aqualuxe_testimonials_shortcode($atts) {
    $atts = shortcode_atts(array(
        'limit' => 6,
        'columns' => 3,
        'orderby' => 'date',
        'order' => 'DESC',
        'category' => '',
        'style' => 'grid', // grid, slider, carousel
        'show_image' => 'yes',
        'show_rating' => 'yes',
        'show_name' => 'yes',
        'show_position' => 'yes',
        'show_company' => 'yes',
        'class' => '',
    ), $atts);

    $query_args = array(
        'post_type' => 'aqualuxe_testimonial',
        'posts_per_page' => absint($atts['limit']),
        'orderby' => $atts['orderby'],
        'order' => $atts['order'],
    );

    if (!empty($atts['category'])) {
        $query_args['tax_query'] = array(
            array(
                'taxonomy' => 'aqualuxe_testimonial_category',
                'field' => 'slug',
                'terms' => explode(',', $atts['category']),
            ),
        );
    }

    $testimonials = new WP_Query($query_args);

    if (!$testimonials->have_posts()) {
        return '';
    }

    $output = '<div class="aqualuxe-testimonials aqualuxe-testimonials-' . esc_attr($atts['style']) . ' aqualuxe-testimonials-columns-' . esc_attr($atts['columns']) . ' ' . esc_attr($atts['class']) . '">';
    
    if ($atts['style'] === 'slider' || $atts['style'] === 'carousel') {
        $output .= '<div class="aqualuxe-testimonials-slider">';
    } else {
        $output .= '<div class="aqualuxe-testimonials-grid">';
    }

    while ($testimonials->have_posts()) {
        $testimonials->the_post();
        $id = get_the_ID();
        $name = get_post_meta($id, '_aqualuxe_testimonial_name', true);
        $position = get_post_meta($id, '_aqualuxe_testimonial_position', true);
        $company = get_post_meta($id, '_aqualuxe_testimonial_company', true);
        $rating = get_post_meta($id, '_aqualuxe_testimonial_rating', true);

        $output .= '<div class="aqualuxe-testimonial">';
        
        $output .= '<div class="aqualuxe-testimonial-content">';
        $output .= '<div class="aqualuxe-testimonial-text">' . wp_kses_post(get_the_content()) . '</div>';
        
        if ($atts['show_rating'] === 'yes' && $rating) {
            $output .= '<div class="aqualuxe-testimonial-rating">';
            $full_stars = floor($rating);
            $half_star = ($rating - $full_stars) >= 0.5;
            
            for ($i = 1; $i <= 5; $i++) {
                if ($i <= $full_stars) {
                    $output .= '<i class="fas fa-star" aria-hidden="true"></i>';
                } elseif ($half_star && $i === $full_stars + 1) {
                    $output .= '<i class="fas fa-star-half-alt" aria-hidden="true"></i>';
                } else {
                    $output .= '<i class="far fa-star" aria-hidden="true"></i>';
                }
            }
            $output .= '</div>';
        }
        $output .= '</div>';

        $output .= '<div class="aqualuxe-testimonial-meta">';
        
        if ($atts['show_image'] === 'yes' && has_post_thumbnail()) {
            $output .= '<div class="aqualuxe-testimonial-image">';
            $output .= get_the_post_thumbnail($id, 'thumbnail', array('class' => 'aqualuxe-testimonial-thumbnail'));
            $output .= '</div>';
        }

        $output .= '<div class="aqualuxe-testimonial-info">';
        
        if ($atts['show_name'] === 'yes' && $name) {
            $output .= '<div class="aqualuxe-testimonial-name">' . esc_html($name) . '</div>';
        }

        if (($atts['show_position'] === 'yes' && $position) || ($atts['show_company'] === 'yes' && $company)) {
            $output .= '<div class="aqualuxe-testimonial-position-company">';
            
            if ($atts['show_position'] === 'yes' && $position) {
                $output .= '<span class="aqualuxe-testimonial-position">' . esc_html($position) . '</span>';
                
                if ($atts['show_company'] === 'yes' && $company) {
                    $output .= ', ';
                }
            }
            
            if ($atts['show_company'] === 'yes' && $company) {
                $output .= '<span class="aqualuxe-testimonial-company">' . esc_html($company) . '</span>';
            }
            
            $output .= '</div>';
        }
        
        $output .= '</div>'; // .aqualuxe-testimonial-info
        $output .= '</div>'; // .aqualuxe-testimonial-meta
        $output .= '</div>'; // .aqualuxe-testimonial
    }

    $output .= '</div>';
    
    if ($atts['style'] === 'slider' || $atts['style'] === 'carousel') {
        $output .= '<div class="aqualuxe-testimonials-navigation">';
        $output .= '<button class="aqualuxe-testimonials-prev" aria-label="' . esc_attr__('Previous', 'aqualuxe') . '"><i class="fas fa-chevron-left" aria-hidden="true"></i></button>';
        $output .= '<button class="aqualuxe-testimonials-next" aria-label="' . esc_attr__('Next', 'aqualuxe') . '"><i class="fas fa-chevron-right" aria-hidden="true"></i></button>';
        $output .= '</div>';
    }
    
    $output .= '</div>';

    wp_reset_postdata();

    return $output;
}
add_shortcode('aqualuxe_testimonials', 'aqualuxe_testimonials_shortcode');

/**
 * Projects shortcode
 *
 * @param array $atts Shortcode attributes.
 * @return string
 */
function aqualuxe_projects_shortcode($atts) {
    $atts = shortcode_atts(array(
        'limit' => 6,
        'columns' => 3,
        'orderby' => 'date',
        'order' => 'DESC',
        'category' => '',
        'show_filters' => 'yes',
        'show_excerpt' => 'yes',
        'show_button' => 'yes',
        'button_text' => __('View Project', 'aqualuxe'),
        'class' => '',
    ), $atts);

    $query_args = array(
        'post_type' => 'aqualuxe_project',
        'posts_per_page' => absint($atts['limit']),
        'orderby' => $atts['orderby'],
        'order' => $atts['order'],
    );

    if (!empty($atts['category'])) {
        $query_args['tax_query'] = array(
            array(
                'taxonomy' => 'aqualuxe_project_category',
                'field' => 'slug',
                'terms' => explode(',', $atts['category']),
            ),
        );
    }

    $projects = new WP_Query($query_args);

    if (!$projects->have_posts()) {
        return '';
    }

    $output = '<div class="aqualuxe-projects aqualuxe-projects-columns-' . esc_attr($atts['columns']) . ' ' . esc_attr($atts['class']) . '">';
    
    // Add filters if enabled
    if ($atts['show_filters'] === 'yes' && empty($atts['category'])) {
        $terms = get_terms(array(
            'taxonomy' => 'aqualuxe_project_category',
            'hide_empty' => true,
        ));
        
        if (!empty($terms) && !is_wp_error($terms)) {
            $output .= '<div class="aqualuxe-projects-filters">';
            $output .= '<button class="aqualuxe-projects-filter active" data-filter="*">' . esc_html__('All', 'aqualuxe') . '</button>';
            
            foreach ($terms as $term) {
                $output .= '<button class="aqualuxe-projects-filter" data-filter=".' . esc_attr($term->slug) . '">' . esc_html($term->name) . '</button>';
            }
            
            $output .= '</div>';
        }
    }
    
    $output .= '<div class="aqualuxe-projects-grid">';

    while ($projects->have_posts()) {
        $projects->the_post();
        $id = get_the_ID();
        $terms = get_the_terms($id, 'aqualuxe_project_category');
        $term_classes = '';
        
        if (!empty($terms) && !is_wp_error($terms)) {
            $term_slugs = array();
            foreach ($terms as $term) {
                $term_slugs[] = $term->slug;
            }
            $term_classes = implode(' ', $term_slugs);
        }

        $output .= '<div class="aqualuxe-project ' . esc_attr($term_classes) . '">';
        
        if (has_post_thumbnail()) {
            $output .= '<div class="aqualuxe-project-image">';
            $output .= '<a href="' . esc_url(get_permalink()) . '">';
            $output .= get_the_post_thumbnail($id, 'large', array('class' => 'aqualuxe-project-thumbnail'));
            $output .= '</a>';
            $output .= '</div>';
        }

        $output .= '<div class="aqualuxe-project-content">';
        $output .= '<h3 class="aqualuxe-project-title"><a href="' . esc_url(get_permalink()) . '">' . esc_html(get_the_title()) . '</a></h3>';

        if ($atts['show_excerpt'] === 'yes') {
            $output .= '<div class="aqualuxe-project-excerpt">' . wp_kses_post(get_the_excerpt()) . '</div>';
        }

        if ($atts['show_button'] === 'yes') {
            $output .= '<a href="' . esc_url(get_permalink()) . '" class="aqualuxe-project-button">' . esc_html($atts['button_text']) . '</a>';
        }

        $output .= '</div>'; // .aqualuxe-project-content
        $output .= '</div>'; // .aqualuxe-project
    }

    $output .= '</div>';
    $output .= '</div>';

    wp_reset_postdata();

    return $output;
}
add_shortcode('aqualuxe_projects', 'aqualuxe_projects_shortcode');

/**
 * FAQs shortcode
 *
 * @param array $atts Shortcode attributes.
 * @return string
 */
function aqualuxe_faqs_shortcode($atts) {
    $atts = shortcode_atts(array(
        'limit' => -1,
        'orderby' => 'menu_order',
        'order' => 'ASC',
        'category' => '',
        'style' => 'accordion', // accordion, toggle, grid
        'show_filters' => 'yes',
        'class' => '',
    ), $atts);

    $query_args = array(
        'post_type' => 'aqualuxe_faq',
        'posts_per_page' => absint($atts['limit']),
        'orderby' => $atts['orderby'],
        'order' => $atts['order'],
    );

    if (!empty($atts['category'])) {
        $query_args['tax_query'] = array(
            array(
                'taxonomy' => 'aqualuxe_faq_category',
                'field' => 'slug',
                'terms' => explode(',', $atts['category']),
            ),
        );
    }

    $faqs = new WP_Query($query_args);

    if (!$faqs->have_posts()) {
        return '';
    }

    $output = '<div class="aqualuxe-faqs aqualuxe-faqs-' . esc_attr($atts['style']) . ' ' . esc_attr($atts['class']) . '">';
    
    // Add filters if enabled
    if ($atts['show_filters'] === 'yes' && empty($atts['category'])) {
        $terms = get_terms(array(
            'taxonomy' => 'aqualuxe_faq_category',
            'hide_empty' => true,
        ));
        
        if (!empty($terms) && !is_wp_error($terms)) {
            $output .= '<div class="aqualuxe-faqs-filters">';
            $output .= '<button class="aqualuxe-faqs-filter active" data-filter="*">' . esc_html__('All', 'aqualuxe') . '</button>';
            
            foreach ($terms as $term) {
                $output .= '<button class="aqualuxe-faqs-filter" data-filter=".' . esc_attr($term->slug) . '">' . esc_html($term->name) . '</button>';
            }
            
            $output .= '</div>';
        }
    }
    
    if ($atts['style'] === 'grid') {
        $output .= '<div class="aqualuxe-faqs-grid">';
    } else {
        $output .= '<div class="aqualuxe-faqs-list">';
    }

    while ($faqs->have_posts()) {
        $faqs->the_post();
        $id = get_the_ID();
        $terms = get_the_terms($id, 'aqualuxe_faq_category');
        $term_classes = '';
        
        if (!empty($terms) && !is_wp_error($terms)) {
            $term_slugs = array();
            foreach ($terms as $term) {
                $term_slugs[] = $term->slug;
            }
            $term_classes = implode(' ', $term_slugs);
        }

        $output .= '<div class="aqualuxe-faq ' . esc_attr($term_classes) . '">';
        
        if ($atts['style'] === 'accordion' || $atts['style'] === 'toggle') {
            $output .= '<div class="aqualuxe-faq-header">';
            $output .= '<h3 class="aqualuxe-faq-question">' . esc_html(get_the_title()) . '</h3>';
            $output .= '<span class="aqualuxe-faq-icon"><i class="fas fa-chevron-down" aria-hidden="true"></i></span>';
            $output .= '</div>';
            
            $output .= '<div class="aqualuxe-faq-content">';
            $output .= '<div class="aqualuxe-faq-answer">' . wp_kses_post(get_the_content()) . '</div>';
            $output .= '</div>';
        } else {
            $output .= '<h3 class="aqualuxe-faq-question">' . esc_html(get_the_title()) . '</h3>';
            $output .= '<div class="aqualuxe-faq-answer">' . wp_kses_post(get_the_content()) . '</div>';
        }
        
        $output .= '</div>'; // .aqualuxe-faq
    }

    $output .= '</div>';
    $output .= '</div>';

    wp_reset_postdata();

    return $output;
}
add_shortcode('aqualuxe_faqs', 'aqualuxe_faqs_shortcode');

/**
 * Register custom taxonomies
 */
function aqualuxe_register_taxonomies() {
    // Register Service Category taxonomy
    register_taxonomy('aqualuxe_service_category', 'aqualuxe_service', array(
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

    // Register Event Category taxonomy
    register_taxonomy('aqualuxe_event_category', 'aqualuxe_event', array(
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

    // Register Team Category taxonomy
    register_taxonomy('aqualuxe_team_category', 'aqualuxe_team', array(
        'labels' => array(
            'name'              => _x('Team Categories', 'taxonomy general name', 'aqualuxe'),
            'singular_name'     => _x('Team Category', 'taxonomy singular name', 'aqualuxe'),
            'search_items'      => __('Search Team Categories', 'aqualuxe'),
            'all_items'         => __('All Team Categories', 'aqualuxe'),
            'parent_item'       => __('Parent Team Category', 'aqualuxe'),
            'parent_item_colon' => __('Parent Team Category:', 'aqualuxe'),
            'edit_item'         => __('Edit Team Category', 'aqualuxe'),
            'update_item'       => __('Update Team Category', 'aqualuxe'),
            'add_new_item'      => __('Add New Team Category', 'aqualuxe'),
            'new_item_name'     => __('New Team Category Name', 'aqualuxe'),
            'menu_name'         => __('Categories', 'aqualuxe'),
        ),
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'team-category'),
        'show_in_rest'      => true,
    ));

    // Register Testimonial Category taxonomy
    register_taxonomy('aqualuxe_testimonial_category', 'aqualuxe_testimonial', array(
        'labels' => array(
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
        ),
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'testimonial-category'),
        'show_in_rest'      => true,
    ));

    // Register Project Category taxonomy
    register_taxonomy('aqualuxe_project_category', 'aqualuxe_project', array(
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

    // Register FAQ Category taxonomy
    register_taxonomy('aqualuxe_faq_category', 'aqualuxe_faq', array(
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
}
add_action('init', 'aqualuxe_register_taxonomies');

/**
 * Flush rewrite rules on theme activation
 */
function aqualuxe_rewrite_flush() {
    aqualuxe_register_post_types();
    aqualuxe_register_taxonomies();
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'aqualuxe_rewrite_flush');