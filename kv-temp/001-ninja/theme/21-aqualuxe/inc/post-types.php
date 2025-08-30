<?php
/**
 * Custom Post Types for AquaLuxe Theme
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

/**
 * Register custom post types
 */
function aqualuxe_register_post_types() {
    // Services Custom Post Type
    $services_labels = array(
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
    );

    $services_args = array(
        'labels'             => $services_labels,
        'description'        => __('Description.', 'aqualuxe'),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'services'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 20,
        'menu_icon'          => 'dashicons-admin-tools',
        'supports'           => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'show_in_rest'       => true,
    );

    register_post_type('service', $services_args);

    // Events Custom Post Type
    $events_labels = array(
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
    );

    $events_args = array(
        'labels'             => $events_labels,
        'description'        => __('Description.', 'aqualuxe'),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'events'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 21,
        'menu_icon'          => 'dashicons-calendar-alt',
        'supports'           => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'show_in_rest'       => true,
    );

    register_post_type('event', $events_args);

    // Projects Custom Post Type
    $projects_labels = array(
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
    );

    $projects_args = array(
        'labels'             => $projects_labels,
        'description'        => __('Description.', 'aqualuxe'),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'projects'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 22,
        'menu_icon'          => 'dashicons-portfolio',
        'supports'           => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'show_in_rest'       => true,
    );

    register_post_type('project', $projects_args);

    // Testimonials Custom Post Type
    $testimonials_labels = array(
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
    );

    $testimonials_args = array(
        'labels'             => $testimonials_labels,
        'description'        => __('Description.', 'aqualuxe'),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'testimonials'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 23,
        'menu_icon'          => 'dashicons-format-quote',
        'supports'           => array('title', 'editor', 'thumbnail', 'custom-fields'),
        'show_in_rest'       => true,
    );

    register_post_type('testimonial', $testimonials_args);

    // Team Members Custom Post Type
    $team_labels = array(
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
    );

    $team_args = array(
        'labels'             => $team_labels,
        'description'        => __('Description.', 'aqualuxe'),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'team'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 24,
        'menu_icon'          => 'dashicons-groups',
        'supports'           => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'show_in_rest'       => true,
    );

    register_post_type('team', $team_args);
}
add_action('init', 'aqualuxe_register_post_types');

/**
 * Register custom taxonomies
 */
function aqualuxe_register_taxonomies() {
    // Service Categories
    $service_cat_labels = array(
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
    );

    $service_cat_args = array(
        'hierarchical'      => true,
        'labels'            => $service_cat_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'service-category'),
        'show_in_rest'      => true,
    );

    register_taxonomy('service_category', array('service'), $service_cat_args);

    // Event Categories
    $event_cat_labels = array(
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
    );

    $event_cat_args = array(
        'hierarchical'      => true,
        'labels'            => $event_cat_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'event-category'),
        'show_in_rest'      => true,
    );

    register_taxonomy('event_category', array('event'), $event_cat_args);

    // Project Categories
    $project_cat_labels = array(
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
    );

    $project_cat_args = array(
        'hierarchical'      => true,
        'labels'            => $project_cat_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'project-category'),
        'show_in_rest'      => true,
    );

    register_taxonomy('project_category', array('project'), $project_cat_args);

    // Team Departments
    $team_dept_labels = array(
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
    );

    $team_dept_args = array(
        'hierarchical'      => true,
        'labels'            => $team_dept_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'department'),
        'show_in_rest'      => true,
    );

    register_taxonomy('department', array('team'), $team_dept_args);
}
add_action('init', 'aqualuxe_register_taxonomies');

/**
 * Add custom meta boxes for post types
 */
function aqualuxe_add_meta_boxes() {
    // Service Details Meta Box
    add_meta_box(
        'service_details',
        __('Service Details', 'aqualuxe'),
        'aqualuxe_service_details_callback',
        'service',
        'normal',
        'high'
    );

    // Event Details Meta Box
    add_meta_box(
        'event_details',
        __('Event Details', 'aqualuxe'),
        'aqualuxe_event_details_callback',
        'event',
        'normal',
        'high'
    );

    // Project Details Meta Box
    add_meta_box(
        'project_details',
        __('Project Details', 'aqualuxe'),
        'aqualuxe_project_details_callback',
        'project',
        'normal',
        'high'
    );

    // Testimonial Details Meta Box
    add_meta_box(
        'testimonial_details',
        __('Testimonial Details', 'aqualuxe'),
        'aqualuxe_testimonial_details_callback',
        'testimonial',
        'normal',
        'high'
    );

    // Team Member Details Meta Box
    add_meta_box(
        'team_details',
        __('Team Member Details', 'aqualuxe'),
        'aqualuxe_team_details_callback',
        'team',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'aqualuxe_add_meta_boxes');

/**
 * Service Details Meta Box Callback
 */
function aqualuxe_service_details_callback($post) {
    wp_nonce_field('aqualuxe_service_details', 'aqualuxe_service_details_nonce');

    $service_icon = get_post_meta($post->ID, '_service_icon', true);
    $service_price = get_post_meta($post->ID, '_service_price', true);
    $service_duration = get_post_meta($post->ID, '_service_duration', true);
    $service_features = get_post_meta($post->ID, '_service_features', true);
    
    ?>
    <div class="aqualuxe-meta-box">
        <div class="aqualuxe-meta-field">
            <label for="service_icon"><?php esc_html_e('Service Icon (Dashicon name or SVG code)', 'aqualuxe'); ?></label>
            <input type="text" id="service_icon" name="service_icon" value="<?php echo esc_attr($service_icon); ?>" class="widefat">
            <p class="description"><?php esc_html_e('Enter a Dashicon name (e.g., "admin-tools") or SVG code.', 'aqualuxe'); ?></p>
        </div>

        <div class="aqualuxe-meta-field">
            <label for="service_price"><?php esc_html_e('Service Price', 'aqualuxe'); ?></label>
            <input type="text" id="service_price" name="service_price" value="<?php echo esc_attr($service_price); ?>" class="widefat">
            <p class="description"><?php esc_html_e('Enter the price for this service (e.g., "$99" or "Starting at $99").', 'aqualuxe'); ?></p>
        </div>

        <div class="aqualuxe-meta-field">
            <label for="service_duration"><?php esc_html_e('Service Duration', 'aqualuxe'); ?></label>
            <input type="text" id="service_duration" name="service_duration" value="<?php echo esc_attr($service_duration); ?>" class="widefat">
            <p class="description"><?php esc_html_e('Enter the duration for this service (e.g., "2 hours" or "1-2 days").', 'aqualuxe'); ?></p>
        </div>

        <div class="aqualuxe-meta-field">
            <label for="service_features"><?php esc_html_e('Service Features (one per line)', 'aqualuxe'); ?></label>
            <textarea id="service_features" name="service_features" class="widefat" rows="5"><?php echo esc_textarea($service_features); ?></textarea>
            <p class="description"><?php esc_html_e('Enter features included in this service, one per line.', 'aqualuxe'); ?></p>
        </div>
    </div>
    <?php
}

/**
 * Event Details Meta Box Callback
 */
function aqualuxe_event_details_callback($post) {
    wp_nonce_field('aqualuxe_event_details', 'aqualuxe_event_details_nonce');

    $event_date = get_post_meta($post->ID, '_event_date', true);
    $event_time = get_post_meta($post->ID, '_event_time', true);
    $event_end_date = get_post_meta($post->ID, '_event_end_date', true);
    $event_end_time = get_post_meta($post->ID, '_event_end_time', true);
    $event_location = get_post_meta($post->ID, '_event_location', true);
    $event_address = get_post_meta($post->ID, '_event_address', true);
    $event_price = get_post_meta($post->ID, '_event_price', true);
    $event_registration_url = get_post_meta($post->ID, '_event_registration_url', true);
    
    ?>
    <div class="aqualuxe-meta-box">
        <div class="aqualuxe-meta-field">
            <label for="event_date"><?php esc_html_e('Event Start Date', 'aqualuxe'); ?></label>
            <input type="date" id="event_date" name="event_date" value="<?php echo esc_attr($event_date); ?>" class="widefat">
        </div>

        <div class="aqualuxe-meta-field">
            <label for="event_time"><?php esc_html_e('Event Start Time', 'aqualuxe'); ?></label>
            <input type="time" id="event_time" name="event_time" value="<?php echo esc_attr($event_time); ?>" class="widefat">
        </div>

        <div class="aqualuxe-meta-field">
            <label for="event_end_date"><?php esc_html_e('Event End Date', 'aqualuxe'); ?></label>
            <input type="date" id="event_end_date" name="event_end_date" value="<?php echo esc_attr($event_end_date); ?>" class="widefat">
        </div>

        <div class="aqualuxe-meta-field">
            <label for="event_end_time"><?php esc_html_e('Event End Time', 'aqualuxe'); ?></label>
            <input type="time" id="event_end_time" name="event_end_time" value="<?php echo esc_attr($event_end_time); ?>" class="widefat">
        </div>

        <div class="aqualuxe-meta-field">
            <label for="event_location"><?php esc_html_e('Event Location Name', 'aqualuxe'); ?></label>
            <input type="text" id="event_location" name="event_location" value="<?php echo esc_attr($event_location); ?>" class="widefat">
        </div>

        <div class="aqualuxe-meta-field">
            <label for="event_address"><?php esc_html_e('Event Address', 'aqualuxe'); ?></label>
            <textarea id="event_address" name="event_address" class="widefat" rows="3"><?php echo esc_textarea($event_address); ?></textarea>
        </div>

        <div class="aqualuxe-meta-field">
            <label for="event_price"><?php esc_html_e('Event Price', 'aqualuxe'); ?></label>
            <input type="text" id="event_price" name="event_price" value="<?php echo esc_attr($event_price); ?>" class="widefat">
            <p class="description"><?php esc_html_e('Enter the price for this event (e.g., "$25" or "Free").', 'aqualuxe'); ?></p>
        </div>

        <div class="aqualuxe-meta-field">
            <label for="event_registration_url"><?php esc_html_e('Registration URL', 'aqualuxe'); ?></label>
            <input type="url" id="event_registration_url" name="event_registration_url" value="<?php echo esc_url($event_registration_url); ?>" class="widefat">
        </div>
    </div>
    <?php
}

/**
 * Project Details Meta Box Callback
 */
function aqualuxe_project_details_callback($post) {
    wp_nonce_field('aqualuxe_project_details', 'aqualuxe_project_details_nonce');

    $project_client = get_post_meta($post->ID, '_project_client', true);
    $project_location = get_post_meta($post->ID, '_project_location', true);
    $project_date = get_post_meta($post->ID, '_project_date', true);
    $project_duration = get_post_meta($post->ID, '_project_duration', true);
    $project_gallery = get_post_meta($post->ID, '_project_gallery', true);
    
    ?>
    <div class="aqualuxe-meta-box">
        <div class="aqualuxe-meta-field">
            <label for="project_client"><?php esc_html_e('Client Name', 'aqualuxe'); ?></label>
            <input type="text" id="project_client" name="project_client" value="<?php echo esc_attr($project_client); ?>" class="widefat">
        </div>

        <div class="aqualuxe-meta-field">
            <label for="project_location"><?php esc_html_e('Project Location', 'aqualuxe'); ?></label>
            <input type="text" id="project_location" name="project_location" value="<?php echo esc_attr($project_location); ?>" class="widefat">
        </div>

        <div class="aqualuxe-meta-field">
            <label for="project_date"><?php esc_html_e('Project Date', 'aqualuxe'); ?></label>
            <input type="date" id="project_date" name="project_date" value="<?php echo esc_attr($project_date); ?>" class="widefat">
        </div>

        <div class="aqualuxe-meta-field">
            <label for="project_duration"><?php esc_html_e('Project Duration', 'aqualuxe'); ?></label>
            <input type="text" id="project_duration" name="project_duration" value="<?php echo esc_attr($project_duration); ?>" class="widefat">
            <p class="description"><?php esc_html_e('Enter the duration of the project (e.g., "2 weeks" or "3 months").', 'aqualuxe'); ?></p>
        </div>

        <div class="aqualuxe-meta-field">
            <label for="project_gallery"><?php esc_html_e('Project Gallery', 'aqualuxe'); ?></label>
            <input type="hidden" id="project_gallery" name="project_gallery" value="<?php echo esc_attr($project_gallery); ?>" class="widefat">
            <div id="project-gallery-container" class="gallery-container"></div>
            <button type="button" class="button" id="add-project-gallery"><?php esc_html_e('Add Images', 'aqualuxe'); ?></button>
            <p class="description"><?php esc_html_e('Add images to the project gallery.', 'aqualuxe'); ?></p>
        </div>
    </div>
    <?php
}

/**
 * Testimonial Details Meta Box Callback
 */
function aqualuxe_testimonial_details_callback($post) {
    wp_nonce_field('aqualuxe_testimonial_details', 'aqualuxe_testimonial_details_nonce');

    $testimonial_author = get_post_meta($post->ID, '_testimonial_author', true);
    $testimonial_position = get_post_meta($post->ID, '_testimonial_position', true);
    $testimonial_company = get_post_meta($post->ID, '_testimonial_company', true);
    $testimonial_rating = get_post_meta($post->ID, '_testimonial_rating', true);
    
    ?>
    <div class="aqualuxe-meta-box">
        <div class="aqualuxe-meta-field">
            <label for="testimonial_author"><?php esc_html_e('Author Name', 'aqualuxe'); ?></label>
            <input type="text" id="testimonial_author" name="testimonial_author" value="<?php echo esc_attr($testimonial_author); ?>" class="widefat">
        </div>

        <div class="aqualuxe-meta-field">
            <label for="testimonial_position"><?php esc_html_e('Author Position', 'aqualuxe'); ?></label>
            <input type="text" id="testimonial_position" name="testimonial_position" value="<?php echo esc_attr($testimonial_position); ?>" class="widefat">
        </div>

        <div class="aqualuxe-meta-field">
            <label for="testimonial_company"><?php esc_html_e('Author Company', 'aqualuxe'); ?></label>
            <input type="text" id="testimonial_company" name="testimonial_company" value="<?php echo esc_attr($testimonial_company); ?>" class="widefat">
        </div>

        <div class="aqualuxe-meta-field">
            <label for="testimonial_rating"><?php esc_html_e('Rating (1-5)', 'aqualuxe'); ?></label>
            <select id="testimonial_rating" name="testimonial_rating" class="widefat">
                <option value=""><?php esc_html_e('Select Rating', 'aqualuxe'); ?></option>
                <option value="5" <?php selected($testimonial_rating, '5'); ?>><?php esc_html_e('5 Stars', 'aqualuxe'); ?></option>
                <option value="4.5" <?php selected($testimonial_rating, '4.5'); ?>><?php esc_html_e('4.5 Stars', 'aqualuxe'); ?></option>
                <option value="4" <?php selected($testimonial_rating, '4'); ?>><?php esc_html_e('4 Stars', 'aqualuxe'); ?></option>
                <option value="3.5" <?php selected($testimonial_rating, '3.5'); ?>><?php esc_html_e('3.5 Stars', 'aqualuxe'); ?></option>
                <option value="3" <?php selected($testimonial_rating, '3'); ?>><?php esc_html_e('3 Stars', 'aqualuxe'); ?></option>
                <option value="2.5" <?php selected($testimonial_rating, '2.5'); ?>><?php esc_html_e('2.5 Stars', 'aqualuxe'); ?></option>
                <option value="2" <?php selected($testimonial_rating, '2'); ?>><?php esc_html_e('2 Stars', 'aqualuxe'); ?></option>
                <option value="1.5" <?php selected($testimonial_rating, '1.5'); ?>><?php esc_html_e('1.5 Stars', 'aqualuxe'); ?></option>
                <option value="1" <?php selected($testimonial_rating, '1'); ?>><?php esc_html_e('1 Star', 'aqualuxe'); ?></option>
            </select>
        </div>
    </div>
    <?php
}

/**
 * Team Member Details Meta Box Callback
 */
function aqualuxe_team_details_callback($post) {
    wp_nonce_field('aqualuxe_team_details', 'aqualuxe_team_details_nonce');

    $team_position = get_post_meta($post->ID, '_team_position', true);
    $team_email = get_post_meta($post->ID, '_team_email', true);
    $team_phone = get_post_meta($post->ID, '_team_phone', true);
    $team_facebook = get_post_meta($post->ID, '_team_facebook', true);
    $team_twitter = get_post_meta($post->ID, '_team_twitter', true);
    $team_linkedin = get_post_meta($post->ID, '_team_linkedin', true);
    $team_instagram = get_post_meta($post->ID, '_team_instagram', true);
    
    ?>
    <div class="aqualuxe-meta-box">
        <div class="aqualuxe-meta-field">
            <label for="team_position"><?php esc_html_e('Position', 'aqualuxe'); ?></label>
            <input type="text" id="team_position" name="team_position" value="<?php echo esc_attr($team_position); ?>" class="widefat">
        </div>

        <div class="aqualuxe-meta-field">
            <label for="team_email"><?php esc_html_e('Email', 'aqualuxe'); ?></label>
            <input type="email" id="team_email" name="team_email" value="<?php echo esc_attr($team_email); ?>" class="widefat">
        </div>

        <div class="aqualuxe-meta-field">
            <label for="team_phone"><?php esc_html_e('Phone', 'aqualuxe'); ?></label>
            <input type="text" id="team_phone" name="team_phone" value="<?php echo esc_attr($team_phone); ?>" class="widefat">
        </div>

        <h4><?php esc_html_e('Social Media', 'aqualuxe'); ?></h4>

        <div class="aqualuxe-meta-field">
            <label for="team_facebook"><?php esc_html_e('Facebook URL', 'aqualuxe'); ?></label>
            <input type="url" id="team_facebook" name="team_facebook" value="<?php echo esc_url($team_facebook); ?>" class="widefat">
        </div>

        <div class="aqualuxe-meta-field">
            <label for="team_twitter"><?php esc_html_e('Twitter URL', 'aqualuxe'); ?></label>
            <input type="url" id="team_twitter" name="team_twitter" value="<?php echo esc_url($team_twitter); ?>" class="widefat">
        </div>

        <div class="aqualuxe-meta-field">
            <label for="team_linkedin"><?php esc_html_e('LinkedIn URL', 'aqualuxe'); ?></label>
            <input type="url" id="team_linkedin" name="team_linkedin" value="<?php echo esc_url($team_linkedin); ?>" class="widefat">
        </div>

        <div class="aqualuxe-meta-field">
            <label for="team_instagram"><?php esc_html_e('Instagram URL', 'aqualuxe'); ?></label>
            <input type="url" id="team_instagram" name="team_instagram" value="<?php echo esc_url($team_instagram); ?>" class="widefat">
        </div>
    </div>
    <?php
}

/**
 * Save post meta when the post is saved.
 */
function aqualuxe_save_post_meta($post_id) {
    // Check if our nonce is set and verify that the nonce is valid.
    if (isset($_POST['aqualuxe_service_details_nonce']) && wp_verify_nonce($_POST['aqualuxe_service_details_nonce'], 'aqualuxe_service_details')) {
        // Service details
        if (isset($_POST['service_icon'])) {
            update_post_meta($post_id, '_service_icon', sanitize_text_field($_POST['service_icon']));
        }
        if (isset($_POST['service_price'])) {
            update_post_meta($post_id, '_service_price', sanitize_text_field($_POST['service_price']));
        }
        if (isset($_POST['service_duration'])) {
            update_post_meta($post_id, '_service_duration', sanitize_text_field($_POST['service_duration']));
        }
        if (isset($_POST['service_features'])) {
            update_post_meta($post_id, '_service_features', sanitize_textarea_field($_POST['service_features']));
        }
    }

    // Check if our nonce is set and verify that the nonce is valid.
    if (isset($_POST['aqualuxe_event_details_nonce']) && wp_verify_nonce($_POST['aqualuxe_event_details_nonce'], 'aqualuxe_event_details')) {
        // Event details
        if (isset($_POST['event_date'])) {
            update_post_meta($post_id, '_event_date', sanitize_text_field($_POST['event_date']));
        }
        if (isset($_POST['event_time'])) {
            update_post_meta($post_id, '_event_time', sanitize_text_field($_POST['event_time']));
        }
        if (isset($_POST['event_end_date'])) {
            update_post_meta($post_id, '_event_end_date', sanitize_text_field($_POST['event_end_date']));
        }
        if (isset($_POST['event_end_time'])) {
            update_post_meta($post_id, '_event_end_time', sanitize_text_field($_POST['event_end_time']));
        }
        if (isset($_POST['event_location'])) {
            update_post_meta($post_id, '_event_location', sanitize_text_field($_POST['event_location']));
        }
        if (isset($_POST['event_address'])) {
            update_post_meta($post_id, '_event_address', sanitize_textarea_field($_POST['event_address']));
        }
        if (isset($_POST['event_price'])) {
            update_post_meta($post_id, '_event_price', sanitize_text_field($_POST['event_price']));
        }
        if (isset($_POST['event_registration_url'])) {
            update_post_meta($post_id, '_event_registration_url', esc_url_raw($_POST['event_registration_url']));
        }
    }

    // Check if our nonce is set and verify that the nonce is valid.
    if (isset($_POST['aqualuxe_project_details_nonce']) && wp_verify_nonce($_POST['aqualuxe_project_details_nonce'], 'aqualuxe_project_details')) {
        // Project details
        if (isset($_POST['project_client'])) {
            update_post_meta($post_id, '_project_client', sanitize_text_field($_POST['project_client']));
        }
        if (isset($_POST['project_location'])) {
            update_post_meta($post_id, '_project_location', sanitize_text_field($_POST['project_location']));
        }
        if (isset($_POST['project_date'])) {
            update_post_meta($post_id, '_project_date', sanitize_text_field($_POST['project_date']));
        }
        if (isset($_POST['project_duration'])) {
            update_post_meta($post_id, '_project_duration', sanitize_text_field($_POST['project_duration']));
        }
        if (isset($_POST['project_gallery'])) {
            update_post_meta($post_id, '_project_gallery', sanitize_text_field($_POST['project_gallery']));
        }
    }

    // Check if our nonce is set and verify that the nonce is valid.
    if (isset($_POST['aqualuxe_testimonial_details_nonce']) && wp_verify_nonce($_POST['aqualuxe_testimonial_details_nonce'], 'aqualuxe_testimonial_details')) {
        // Testimonial details
        if (isset($_POST['testimonial_author'])) {
            update_post_meta($post_id, '_testimonial_author', sanitize_text_field($_POST['testimonial_author']));
        }
        if (isset($_POST['testimonial_position'])) {
            update_post_meta($post_id, '_testimonial_position', sanitize_text_field($_POST['testimonial_position']));
        }
        if (isset($_POST['testimonial_company'])) {
            update_post_meta($post_id, '_testimonial_company', sanitize_text_field($_POST['testimonial_company']));
        }
        if (isset($_POST['testimonial_rating'])) {
            update_post_meta($post_id, '_testimonial_rating', sanitize_text_field($_POST['testimonial_rating']));
        }
    }

    // Check if our nonce is set and verify that the nonce is valid.
    if (isset($_POST['aqualuxe_team_details_nonce']) && wp_verify_nonce($_POST['aqualuxe_team_details_nonce'], 'aqualuxe_team_details')) {
        // Team details
        if (isset($_POST['team_position'])) {
            update_post_meta($post_id, '_team_position', sanitize_text_field($_POST['team_position']));
        }
        if (isset($_POST['team_email'])) {
            update_post_meta($post_id, '_team_email', sanitize_email($_POST['team_email']));
        }
        if (isset($_POST['team_phone'])) {
            update_post_meta($post_id, '_team_phone', sanitize_text_field($_POST['team_phone']));
        }
        if (isset($_POST['team_facebook'])) {
            update_post_meta($post_id, '_team_facebook', esc_url_raw($_POST['team_facebook']));
        }
        if (isset($_POST['team_twitter'])) {
            update_post_meta($post_id, '_team_twitter', esc_url_raw($_POST['team_twitter']));
        }
        if (isset($_POST['team_linkedin'])) {
            update_post_meta($post_id, '_team_linkedin', esc_url_raw($_POST['team_linkedin']));
        }
        if (isset($_POST['team_instagram'])) {
            update_post_meta($post_id, '_team_instagram', esc_url_raw($_POST['team_instagram']));
        }
    }
}
add_action('save_post', 'aqualuxe_save_post_meta');

/**
 * Enqueue admin scripts and styles
 */
function aqualuxe_admin_scripts($hook) {
    global $post;

    if (!$post) {
        return;
    }

    // Only enqueue on post edit screens
    if ($hook == 'post.php' || $hook == 'post-new.php') {
        // Enqueue media scripts for the gallery field
        if ($post->post_type == 'project') {
            wp_enqueue_media();
            wp_enqueue_script('aqualuxe-admin-js', get_template_directory_uri() . '/assets/js/admin.js', array('jquery'), AQUALUXE_VERSION, true);
        }

        // Enqueue admin styles
        wp_enqueue_style('aqualuxe-admin-css', get_template_directory_uri() . '/assets/css/admin.css', array(), AQUALUXE_VERSION);
    }
}
add_action('admin_enqueue_scripts', 'aqualuxe_admin_scripts');

/**
 * Add custom columns to post type admin lists
 */
function aqualuxe_add_custom_columns($columns) {
    $screen = get_current_screen();
    
    if ($screen->post_type == 'service') {
        $columns['service_price'] = __('Price', 'aqualuxe');
        $columns['service_duration'] = __('Duration', 'aqualuxe');
    } elseif ($screen->post_type == 'event') {
        $columns['event_date'] = __('Date', 'aqualuxe');
        $columns['event_location'] = __('Location', 'aqualuxe');
    } elseif ($screen->post_type == 'project') {
        $columns['project_client'] = __('Client', 'aqualuxe');
        $columns['project_date'] = __('Date', 'aqualuxe');
    } elseif ($screen->post_type == 'testimonial') {
        $columns['testimonial_author'] = __('Author', 'aqualuxe');
        $columns['testimonial_rating'] = __('Rating', 'aqualuxe');
    } elseif ($screen->post_type == 'team') {
        $columns['team_position'] = __('Position', 'aqualuxe');
        $columns['team_department'] = __('Department', 'aqualuxe');
    }
    
    return $columns;
}
add_filter('manage_posts_columns', 'aqualuxe_add_custom_columns');

/**
 * Display custom column content
 */
function aqualuxe_custom_column_content($column, $post_id) {
    if ($column == 'service_price') {
        echo esc_html(get_post_meta($post_id, '_service_price', true));
    } elseif ($column == 'service_duration') {
        echo esc_html(get_post_meta($post_id, '_service_duration', true));
    } elseif ($column == 'event_date') {
        $date = get_post_meta($post_id, '_event_date', true);
        if ($date) {
            echo esc_html(date_i18n(get_option('date_format'), strtotime($date)));
        }
    } elseif ($column == 'event_location') {
        echo esc_html(get_post_meta($post_id, '_event_location', true));
    } elseif ($column == 'project_client') {
        echo esc_html(get_post_meta($post_id, '_project_client', true));
    } elseif ($column == 'project_date') {
        $date = get_post_meta($post_id, '_project_date', true);
        if ($date) {
            echo esc_html(date_i18n(get_option('date_format'), strtotime($date)));
        }
    } elseif ($column == 'testimonial_author') {
        echo esc_html(get_post_meta($post_id, '_testimonial_author', true));
    } elseif ($column == 'testimonial_rating') {
        $rating = get_post_meta($post_id, '_testimonial_rating', true);
        if ($rating) {
            echo esc_html($rating) . ' ' . esc_html__('stars', 'aqualuxe');
        }
    } elseif ($column == 'team_position') {
        echo esc_html(get_post_meta($post_id, '_team_position', true));
    } elseif ($column == 'team_department') {
        $terms = get_the_terms($post_id, 'department');
        if ($terms && !is_wp_error($terms)) {
            $department_names = array();
            foreach ($terms as $term) {
                $department_names[] = $term->name;
            }
            echo esc_html(implode(', ', $department_names));
        }
    }
}
add_action('manage_posts_custom_column', 'aqualuxe_custom_column_content', 10, 2);

/**
 * Make custom columns sortable
 */
function aqualuxe_sortable_columns($columns) {
    $columns['service_price'] = 'service_price';
    $columns['event_date'] = 'event_date';
    $columns['project_date'] = 'project_date';
    $columns['testimonial_rating'] = 'testimonial_rating';
    $columns['team_position'] = 'team_position';
    
    return $columns;
}
add_filter('manage_edit-service_sortable_columns', 'aqualuxe_sortable_columns');
add_filter('manage_edit-event_sortable_columns', 'aqualuxe_sortable_columns');
add_filter('manage_edit-project_sortable_columns', 'aqualuxe_sortable_columns');
add_filter('manage_edit-testimonial_sortable_columns', 'aqualuxe_sortable_columns');
add_filter('manage_edit-team_sortable_columns', 'aqualuxe_sortable_columns');

/**
 * Sort posts by custom columns
 */
function aqualuxe_sort_custom_columns($query) {
    if (!is_admin()) {
        return;
    }
    
    $orderby = $query->get('orderby');
    
    if ($orderby == 'service_price') {
        $query->set('meta_key', '_service_price');
        $query->set('orderby', 'meta_value');
    } elseif ($orderby == 'event_date') {
        $query->set('meta_key', '_event_date');
        $query->set('orderby', 'meta_value');
    } elseif ($orderby == 'project_date') {
        $query->set('meta_key', '_project_date');
        $query->set('orderby', 'meta_value');
    } elseif ($orderby == 'testimonial_rating') {
        $query->set('meta_key', '_testimonial_rating');
        $query->set('orderby', 'meta_value_num');
    } elseif ($orderby == 'team_position') {
        $query->set('meta_key', '_team_position');
        $query->set('orderby', 'meta_value');
    }
}
add_action('pre_get_posts', 'aqualuxe_sort_custom_columns');