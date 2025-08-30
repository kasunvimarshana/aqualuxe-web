<?php
/**
 * Custom post types for AquaLuxe theme
 *
 * @package AquaLuxe
 */

/**
 * Register custom post types
 */
function aqualuxe_register_post_types() {
    // Register Testimonial post type
    register_post_type('testimonial', array(
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
            'not_found_in_trash' => __('No testimonials found in Trash.', 'aqualuxe'),
        ),
        'description'         => __('Customer testimonials', 'aqualuxe'),
        'public'              => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 20,
        'menu_icon'           => 'dashicons-format-quote',
        'capability_type'     => 'post',
        'hierarchical'        => false,
        'supports'            => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'has_archive'         => true,
        'rewrite'             => array('slug' => 'testimonials'),
        'query_var'           => true,
        'show_in_rest'        => true,
    ));

    // Register Team Member post type
    register_post_type('team_member', array(
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
            'not_found_in_trash' => __('No team members found in Trash.', 'aqualuxe'),
        ),
        'description'         => __('Team members', 'aqualuxe'),
        'public'              => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 21,
        'menu_icon'           => 'dashicons-groups',
        'capability_type'     => 'post',
        'hierarchical'        => false,
        'supports'            => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'has_archive'         => true,
        'rewrite'             => array('slug' => 'team'),
        'query_var'           => true,
        'show_in_rest'        => true,
    ));

    // Register Service post type
    register_post_type('service', array(
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
            'not_found_in_trash' => __('No services found in Trash.', 'aqualuxe'),
        ),
        'description'         => __('Services offered', 'aqualuxe'),
        'public'              => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 22,
        'menu_icon'           => 'dashicons-admin-tools',
        'capability_type'     => 'post',
        'hierarchical'        => true,
        'supports'            => array('title', 'editor', 'thumbnail', 'excerpt', 'page-attributes', 'custom-fields'),
        'has_archive'         => true,
        'rewrite'             => array('slug' => 'services'),
        'query_var'           => true,
        'show_in_rest'        => true,
    ));

    // Register FAQ post type
    register_post_type('faq', array(
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
            'not_found_in_trash' => __('No FAQs found in Trash.', 'aqualuxe'),
        ),
        'description'         => __('Frequently asked questions', 'aqualuxe'),
        'public'              => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 23,
        'menu_icon'           => 'dashicons-editor-help',
        'capability_type'     => 'post',
        'hierarchical'        => false,
        'supports'            => array('title', 'editor', 'excerpt', 'custom-fields'),
        'has_archive'         => true,
        'rewrite'             => array('slug' => 'faqs'),
        'query_var'           => true,
        'show_in_rest'        => true,
    ));

    // Register Event post type
    register_post_type('event', array(
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
            'not_found_in_trash' => __('No events found in Trash.', 'aqualuxe'),
        ),
        'description'         => __('Events and exhibitions', 'aqualuxe'),
        'public'              => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 24,
        'menu_icon'           => 'dashicons-calendar-alt',
        'capability_type'     => 'post',
        'hierarchical'        => false,
        'supports'            => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'has_archive'         => true,
        'rewrite'             => array('slug' => 'events'),
        'query_var'           => true,
        'show_in_rest'        => true,
    ));

    // Register Project post type
    register_post_type('project', array(
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
            'not_found_in_trash' => __('No projects found in Trash.', 'aqualuxe'),
        ),
        'description'         => __('Portfolio projects', 'aqualuxe'),
        'public'              => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 25,
        'menu_icon'           => 'dashicons-portfolio',
        'capability_type'     => 'post',
        'hierarchical'        => false,
        'supports'            => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'has_archive'         => true,
        'rewrite'             => array('slug' => 'projects'),
        'query_var'           => true,
        'show_in_rest'        => true,
    ));

    // Register Sustainability post type
    register_post_type('sustainability', array(
        'labels' => array(
            'name'               => _x('Sustainability', 'post type general name', 'aqualuxe'),
            'singular_name'      => _x('Sustainability', 'post type singular name', 'aqualuxe'),
            'menu_name'          => _x('Sustainability', 'admin menu', 'aqualuxe'),
            'name_admin_bar'     => _x('Sustainability', 'add new on admin bar', 'aqualuxe'),
            'add_new'            => _x('Add New', 'sustainability', 'aqualuxe'),
            'add_new_item'       => __('Add New Sustainability Initiative', 'aqualuxe'),
            'new_item'           => __('New Sustainability Initiative', 'aqualuxe'),
            'edit_item'          => __('Edit Sustainability Initiative', 'aqualuxe'),
            'view_item'          => __('View Sustainability Initiative', 'aqualuxe'),
            'all_items'          => __('All Sustainability Initiatives', 'aqualuxe'),
            'search_items'       => __('Search Sustainability Initiatives', 'aqualuxe'),
            'parent_item_colon'  => __('Parent Sustainability Initiatives:', 'aqualuxe'),
            'not_found'          => __('No sustainability initiatives found.', 'aqualuxe'),
            'not_found_in_trash' => __('No sustainability initiatives found in Trash.', 'aqualuxe'),
        ),
        'description'         => __('Sustainability initiatives and eco-friendly practices', 'aqualuxe'),
        'public'              => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 26,
        'menu_icon'           => 'dashicons-leaf',
        'capability_type'     => 'post',
        'hierarchical'        => false,
        'supports'            => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'has_archive'         => true,
        'rewrite'             => array('slug' => 'sustainability'),
        'query_var'           => true,
        'show_in_rest'        => true,
    ));

    // Register Research post type
    register_post_type('research', array(
        'labels' => array(
            'name'               => _x('Research', 'post type general name', 'aqualuxe'),
            'singular_name'      => _x('Research', 'post type singular name', 'aqualuxe'),
            'menu_name'          => _x('Research', 'admin menu', 'aqualuxe'),
            'name_admin_bar'     => _x('Research', 'add new on admin bar', 'aqualuxe'),
            'add_new'            => _x('Add New', 'research', 'aqualuxe'),
            'add_new_item'       => __('Add New Research', 'aqualuxe'),
            'new_item'           => __('New Research', 'aqualuxe'),
            'edit_item'          => __('Edit Research', 'aqualuxe'),
            'view_item'          => __('View Research', 'aqualuxe'),
            'all_items'          => __('All Research', 'aqualuxe'),
            'search_items'       => __('Search Research', 'aqualuxe'),
            'parent_item_colon'  => __('Parent Research:', 'aqualuxe'),
            'not_found'          => __('No research found.', 'aqualuxe'),
            'not_found_in_trash' => __('No research found in Trash.', 'aqualuxe'),
        ),
        'description'         => __('Research and development', 'aqualuxe'),
        'public'              => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 27,
        'menu_icon'           => 'dashicons-chart-area',
        'capability_type'     => 'post',
        'hierarchical'        => false,
        'supports'            => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'has_archive'         => true,
        'rewrite'             => array('slug' => 'research'),
        'query_var'           => true,
        'show_in_rest'        => true,
    ));

    // Register Franchise post type
    register_post_type('franchise', array(
        'labels' => array(
            'name'               => _x('Franchises', 'post type general name', 'aqualuxe'),
            'singular_name'      => _x('Franchise', 'post type singular name', 'aqualuxe'),
            'menu_name'          => _x('Franchises', 'admin menu', 'aqualuxe'),
            'name_admin_bar'     => _x('Franchise', 'add new on admin bar', 'aqualuxe'),
            'add_new'            => _x('Add New', 'franchise', 'aqualuxe'),
            'add_new_item'       => __('Add New Franchise', 'aqualuxe'),
            'new_item'           => __('New Franchise', 'aqualuxe'),
            'edit_item'          => __('Edit Franchise', 'aqualuxe'),
            'view_item'          => __('View Franchise', 'aqualuxe'),
            'all_items'          => __('All Franchises', 'aqualuxe'),
            'search_items'       => __('Search Franchises', 'aqualuxe'),
            'parent_item_colon'  => __('Parent Franchises:', 'aqualuxe'),
            'not_found'          => __('No franchises found.', 'aqualuxe'),
            'not_found_in_trash' => __('No franchises found in Trash.', 'aqualuxe'),
        ),
        'description'         => __('Franchise opportunities', 'aqualuxe'),
        'public'              => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 28,
        'menu_icon'           => 'dashicons-store',
        'capability_type'     => 'post',
        'hierarchical'        => false,
        'supports'            => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'has_archive'         => true,
        'rewrite'             => array('slug' => 'franchises'),
        'query_var'           => true,
        'show_in_rest'        => true,
    ));

    // Register Location post type
    register_post_type('location', array(
        'labels' => array(
            'name'               => _x('Locations', 'post type general name', 'aqualuxe'),
            'singular_name'      => _x('Location', 'post type singular name', 'aqualuxe'),
            'menu_name'          => _x('Locations', 'admin menu', 'aqualuxe'),
            'name_admin_bar'     => _x('Location', 'add new on admin bar', 'aqualuxe'),
            'add_new'            => _x('Add New', 'location', 'aqualuxe'),
            'add_new_item'       => __('Add New Location', 'aqualuxe'),
            'new_item'           => __('New Location', 'aqualuxe'),
            'edit_item'          => __('Edit Location', 'aqualuxe'),
            'view_item'          => __('View Location', 'aqualuxe'),
            'all_items'          => __('All Locations', 'aqualuxe'),
            'search_items'       => __('Search Locations', 'aqualuxe'),
            'parent_item_colon'  => __('Parent Locations:', 'aqualuxe'),
            'not_found'          => __('No locations found.', 'aqualuxe'),
            'not_found_in_trash' => __('No locations found in Trash.', 'aqualuxe'),
        ),
        'description'         => __('Store and office locations', 'aqualuxe'),
        'public'              => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 29,
        'menu_icon'           => 'dashicons-location',
        'capability_type'     => 'post',
        'hierarchical'        => false,
        'supports'            => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'has_archive'         => true,
        'rewrite'             => array('slug' => 'locations'),
        'query_var'           => true,
        'show_in_rest'        => true,
    ));
}
add_action('init', 'aqualuxe_register_post_types');

/**
 * Add meta boxes for custom post types
 */
function aqualuxe_add_meta_boxes() {
    // Testimonial meta box
    add_meta_box(
        'testimonial_meta_box',
        __('Testimonial Details', 'aqualuxe'),
        'aqualuxe_testimonial_meta_box_callback',
        'testimonial',
        'normal',
        'high'
    );

    // Team Member meta box
    add_meta_box(
        'team_member_meta_box',
        __('Team Member Details', 'aqualuxe'),
        'aqualuxe_team_member_meta_box_callback',
        'team_member',
        'normal',
        'high'
    );

    // Service meta box
    add_meta_box(
        'service_meta_box',
        __('Service Details', 'aqualuxe'),
        'aqualuxe_service_meta_box_callback',
        'service',
        'normal',
        'high'
    );

    // Event meta box
    add_meta_box(
        'event_meta_box',
        __('Event Details', 'aqualuxe'),
        'aqualuxe_event_meta_box_callback',
        'event',
        'normal',
        'high'
    );

    // Project meta box
    add_meta_box(
        'project_meta_box',
        __('Project Details', 'aqualuxe'),
        'aqualuxe_project_meta_box_callback',
        'project',
        'normal',
        'high'
    );

    // Location meta box
    add_meta_box(
        'location_meta_box',
        __('Location Details', 'aqualuxe'),
        'aqualuxe_location_meta_box_callback',
        'location',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'aqualuxe_add_meta_boxes');

/**
 * Testimonial meta box callback
 *
 * @param WP_Post $post The post object.
 */
function aqualuxe_testimonial_meta_box_callback($post) {
    // Add nonce for security
    wp_nonce_field('aqualuxe_testimonial_meta_box', 'aqualuxe_testimonial_meta_box_nonce');

    // Get current values
    $client_name = get_post_meta($post->ID, '_client_name', true);
    $client_company = get_post_meta($post->ID, '_client_company', true);
    $client_position = get_post_meta($post->ID, '_client_position', true);
    $client_location = get_post_meta($post->ID, '_client_location', true);
    $client_rating = get_post_meta($post->ID, '_client_rating', true);
    $testimonial_date = get_post_meta($post->ID, '_testimonial_date', true);
    $featured = get_post_meta($post->ID, '_featured_testimonial', true);

    ?>
    <div class="aqualuxe-meta-box">
        <div class="aqualuxe-meta-field">
            <label for="client_name"><?php esc_html_e('Client Name', 'aqualuxe'); ?></label>
            <input type="text" id="client_name" name="client_name" value="<?php echo esc_attr($client_name); ?>">
        </div>

        <div class="aqualuxe-meta-field">
            <label for="client_company"><?php esc_html_e('Client Company', 'aqualuxe'); ?></label>
            <input type="text" id="client_company" name="client_company" value="<?php echo esc_attr($client_company); ?>">
        </div>

        <div class="aqualuxe-meta-field">
            <label for="client_position"><?php esc_html_e('Client Position', 'aqualuxe'); ?></label>
            <input type="text" id="client_position" name="client_position" value="<?php echo esc_attr($client_position); ?>">
        </div>

        <div class="aqualuxe-meta-field">
            <label for="client_location"><?php esc_html_e('Client Location', 'aqualuxe'); ?></label>
            <input type="text" id="client_location" name="client_location" value="<?php echo esc_attr($client_location); ?>">
        </div>

        <div class="aqualuxe-meta-field">
            <label for="client_rating"><?php esc_html_e('Rating (1-5)', 'aqualuxe'); ?></label>
            <select id="client_rating" name="client_rating">
                <option value=""><?php esc_html_e('Select Rating', 'aqualuxe'); ?></option>
                <?php for ($i = 1; $i <= 5; $i++) : ?>
                    <option value="<?php echo esc_attr($i); ?>" <?php selected($client_rating, $i); ?>><?php echo esc_html($i); ?></option>
                <?php endfor; ?>
            </select>
        </div>

        <div class="aqualuxe-meta-field">
            <label for="testimonial_date"><?php esc_html_e('Testimonial Date', 'aqualuxe'); ?></label>
            <input type="date" id="testimonial_date" name="testimonial_date" value="<?php echo esc_attr($testimonial_date); ?>">
        </div>

        <div class="aqualuxe-meta-field">
            <label for="featured_testimonial"><?php esc_html_e('Featured Testimonial', 'aqualuxe'); ?></label>
            <input type="checkbox" id="featured_testimonial" name="featured_testimonial" value="1" <?php checked($featured, '1'); ?>>
        </div>
    </div>
    <?php
}

/**
 * Team Member meta box callback
 *
 * @param WP_Post $post The post object.
 */
function aqualuxe_team_member_meta_box_callback($post) {
    // Add nonce for security
    wp_nonce_field('aqualuxe_team_member_meta_box', 'aqualuxe_team_member_meta_box_nonce');

    // Get current values
    $position = get_post_meta($post->ID, '_position', true);
    $department = get_post_meta($post->ID, '_department', true);
    $email = get_post_meta($post->ID, '_email', true);
    $phone = get_post_meta($post->ID, '_phone', true);
    $linkedin = get_post_meta($post->ID, '_linkedin', true);
    $twitter = get_post_meta($post->ID, '_twitter', true);
    $facebook = get_post_meta($post->ID, '_facebook', true);
    $instagram = get_post_meta($post->ID, '_instagram', true);
    $featured = get_post_meta($post->ID, '_featured_team_member', true);

    ?>
    <div class="aqualuxe-meta-box">
        <div class="aqualuxe-meta-field">
            <label for="position"><?php esc_html_e('Position', 'aqualuxe'); ?></label>
            <input type="text" id="position" name="position" value="<?php echo esc_attr($position); ?>">
        </div>

        <div class="aqualuxe-meta-field">
            <label for="department"><?php esc_html_e('Department', 'aqualuxe'); ?></label>
            <input type="text" id="department" name="department" value="<?php echo esc_attr($department); ?>">
        </div>

        <div class="aqualuxe-meta-field">
            <label for="email"><?php esc_html_e('Email', 'aqualuxe'); ?></label>
            <input type="email" id="email" name="email" value="<?php echo esc_attr($email); ?>">
        </div>

        <div class="aqualuxe-meta-field">
            <label for="phone"><?php esc_html_e('Phone', 'aqualuxe'); ?></label>
            <input type="text" id="phone" name="phone" value="<?php echo esc_attr($phone); ?>">
        </div>

        <div class="aqualuxe-meta-field">
            <label for="linkedin"><?php esc_html_e('LinkedIn Profile URL', 'aqualuxe'); ?></label>
            <input type="url" id="linkedin" name="linkedin" value="<?php echo esc_url($linkedin); ?>">
        </div>

        <div class="aqualuxe-meta-field">
            <label for="twitter"><?php esc_html_e('Twitter Profile URL', 'aqualuxe'); ?></label>
            <input type="url" id="twitter" name="twitter" value="<?php echo esc_url($twitter); ?>">
        </div>

        <div class="aqualuxe-meta-field">
            <label for="facebook"><?php esc_html_e('Facebook Profile URL', 'aqualuxe'); ?></label>
            <input type="url" id="facebook" name="facebook" value="<?php echo esc_url($facebook); ?>">
        </div>

        <div class="aqualuxe-meta-field">
            <label for="instagram"><?php esc_html_e('Instagram Profile URL', 'aqualuxe'); ?></label>
            <input type="url" id="instagram" name="instagram" value="<?php echo esc_url($instagram); ?>">
        </div>

        <div class="aqualuxe-meta-field">
            <label for="featured_team_member"><?php esc_html_e('Featured Team Member', 'aqualuxe'); ?></label>
            <input type="checkbox" id="featured_team_member" name="featured_team_member" value="1" <?php checked($featured, '1'); ?>>
        </div>
    </div>
    <?php
}

/**
 * Service meta box callback
 *
 * @param WP_Post $post The post object.
 */
function aqualuxe_service_meta_box_callback($post) {
    // Add nonce for security
    wp_nonce_field('aqualuxe_service_meta_box', 'aqualuxe_service_meta_box_nonce');

    // Get current values
    $icon = get_post_meta($post->ID, '_service_icon', true);
    $price = get_post_meta($post->ID, '_service_price', true);
    $duration = get_post_meta($post->ID, '_service_duration', true);
    $featured = get_post_meta($post->ID, '_featured_service', true);
    $button_text = get_post_meta($post->ID, '_button_text', true);
    $button_url = get_post_meta($post->ID, '_button_url', true);

    ?>
    <div class="aqualuxe-meta-box">
        <div class="aqualuxe-meta-field">
            <label for="service_icon"><?php esc_html_e('Service Icon (Font Awesome class or SVG code)', 'aqualuxe'); ?></label>
            <input type="text" id="service_icon" name="service_icon" value="<?php echo esc_attr($icon); ?>">
        </div>

        <div class="aqualuxe-meta-field">
            <label for="service_price"><?php esc_html_e('Price', 'aqualuxe'); ?></label>
            <input type="text" id="service_price" name="service_price" value="<?php echo esc_attr($price); ?>">
        </div>

        <div class="aqualuxe-meta-field">
            <label for="service_duration"><?php esc_html_e('Duration', 'aqualuxe'); ?></label>
            <input type="text" id="service_duration" name="service_duration" value="<?php echo esc_attr($duration); ?>">
        </div>

        <div class="aqualuxe-meta-field">
            <label for="button_text"><?php esc_html_e('Button Text', 'aqualuxe'); ?></label>
            <input type="text" id="button_text" name="button_text" value="<?php echo esc_attr($button_text); ?>">
        </div>

        <div class="aqualuxe-meta-field">
            <label for="button_url"><?php esc_html_e('Button URL', 'aqualuxe'); ?></label>
            <input type="url" id="button_url" name="button_url" value="<?php echo esc_url($button_url); ?>">
        </div>

        <div class="aqualuxe-meta-field">
            <label for="featured_service"><?php esc_html_e('Featured Service', 'aqualuxe'); ?></label>
            <input type="checkbox" id="featured_service" name="featured_service" value="1" <?php checked($featured, '1'); ?>>
        </div>
    </div>
    <?php
}

/**
 * Event meta box callback
 *
 * @param WP_Post $post The post object.
 */
function aqualuxe_event_meta_box_callback($post) {
    // Add nonce for security
    wp_nonce_field('aqualuxe_event_meta_box', 'aqualuxe_event_meta_box_nonce');

    // Get current values
    $start_date = get_post_meta($post->ID, '_event_start_date', true);
    $end_date = get_post_meta($post->ID, '_event_end_date', true);
    $start_time = get_post_meta($post->ID, '_event_start_time', true);
    $end_time = get_post_meta($post->ID, '_event_end_time', true);
    $location = get_post_meta($post->ID, '_event_location', true);
    $address = get_post_meta($post->ID, '_event_address', true);
    $map_coordinates = get_post_meta($post->ID, '_event_map_coordinates', true);
    $registration_url = get_post_meta($post->ID, '_event_registration_url', true);
    $price = get_post_meta($post->ID, '_event_price', true);
    $capacity = get_post_meta($post->ID, '_event_capacity', true);
    $featured = get_post_meta($post->ID, '_featured_event', true);

    ?>
    <div class="aqualuxe-meta-box">
        <div class="aqualuxe-meta-field">
            <label for="event_start_date"><?php esc_html_e('Start Date', 'aqualuxe'); ?></label>
            <input type="date" id="event_start_date" name="event_start_date" value="<?php echo esc_attr($start_date); ?>">
        </div>

        <div class="aqualuxe-meta-field">
            <label for="event_end_date"><?php esc_html_e('End Date', 'aqualuxe'); ?></label>
            <input type="date" id="event_end_date" name="event_end_date" value="<?php echo esc_attr($end_date); ?>">
        </div>

        <div class="aqualuxe-meta-field">
            <label for="event_start_time"><?php esc_html_e('Start Time', 'aqualuxe'); ?></label>
            <input type="time" id="event_start_time" name="event_start_time" value="<?php echo esc_attr($start_time); ?>">
        </div>

        <div class="aqualuxe-meta-field">
            <label for="event_end_time"><?php esc_html_e('End Time', 'aqualuxe'); ?></label>
            <input type="time" id="event_end_time" name="event_end_time" value="<?php echo esc_attr($end_time); ?>">
        </div>

        <div class="aqualuxe-meta-field">
            <label for="event_location"><?php esc_html_e('Location Name', 'aqualuxe'); ?></label>
            <input type="text" id="event_location" name="event_location" value="<?php echo esc_attr($location); ?>">
        </div>

        <div class="aqualuxe-meta-field">
            <label for="event_address"><?php esc_html_e('Address', 'aqualuxe'); ?></label>
            <textarea id="event_address" name="event_address"><?php echo esc_textarea($address); ?></textarea>
        </div>

        <div class="aqualuxe-meta-field">
            <label for="event_map_coordinates"><?php esc_html_e('Map Coordinates (latitude,longitude)', 'aqualuxe'); ?></label>
            <input type="text" id="event_map_coordinates" name="event_map_coordinates" value="<?php echo esc_attr($map_coordinates); ?>">
        </div>

        <div class="aqualuxe-meta-field">
            <label for="event_registration_url"><?php esc_html_e('Registration URL', 'aqualuxe'); ?></label>
            <input type="url" id="event_registration_url" name="event_registration_url" value="<?php echo esc_url($registration_url); ?>">
        </div>

        <div class="aqualuxe-meta-field">
            <label for="event_price"><?php esc_html_e('Price', 'aqualuxe'); ?></label>
            <input type="text" id="event_price" name="event_price" value="<?php echo esc_attr($price); ?>">
        </div>

        <div class="aqualuxe-meta-field">
            <label for="event_capacity"><?php esc_html_e('Capacity', 'aqualuxe'); ?></label>
            <input type="number" id="event_capacity" name="event_capacity" value="<?php echo esc_attr($capacity); ?>">
        </div>

        <div class="aqualuxe-meta-field">
            <label for="featured_event"><?php esc_html_e('Featured Event', 'aqualuxe'); ?></label>
            <input type="checkbox" id="featured_event" name="featured_event" value="1" <?php checked($featured, '1'); ?>>
        </div>
    </div>
    <?php
}

/**
 * Project meta box callback
 *
 * @param WP_Post $post The post object.
 */
function aqualuxe_project_meta_box_callback($post) {
    // Add nonce for security
    wp_nonce_field('aqualuxe_project_meta_box', 'aqualuxe_project_meta_box_nonce');

    // Get current values
    $client = get_post_meta($post->ID, '_project_client', true);
    $location = get_post_meta($post->ID, '_project_location', true);
    $completion_date = get_post_meta($post->ID, '_project_completion_date', true);
    $budget = get_post_meta($post->ID, '_project_budget', true);
    $services = get_post_meta($post->ID, '_project_services', true);
    $gallery = get_post_meta($post->ID, '_project_gallery', true);
    $video_url = get_post_meta($post->ID, '_project_video_url', true);
    $featured = get_post_meta($post->ID, '_featured_project', true);

    ?>
    <div class="aqualuxe-meta-box">
        <div class="aqualuxe-meta-field">
            <label for="project_client"><?php esc_html_e('Client', 'aqualuxe'); ?></label>
            <input type="text" id="project_client" name="project_client" value="<?php echo esc_attr($client); ?>">
        </div>

        <div class="aqualuxe-meta-field">
            <label for="project_location"><?php esc_html_e('Location', 'aqualuxe'); ?></label>
            <input type="text" id="project_location" name="project_location" value="<?php echo esc_attr($location); ?>">
        </div>

        <div class="aqualuxe-meta-field">
            <label for="project_completion_date"><?php esc_html_e('Completion Date', 'aqualuxe'); ?></label>
            <input type="date" id="project_completion_date" name="project_completion_date" value="<?php echo esc_attr($completion_date); ?>">
        </div>

        <div class="aqualuxe-meta-field">
            <label for="project_budget"><?php esc_html_e('Budget', 'aqualuxe'); ?></label>
            <input type="text" id="project_budget" name="project_budget" value="<?php echo esc_attr($budget); ?>">
        </div>

        <div class="aqualuxe-meta-field">
            <label for="project_services"><?php esc_html_e('Services Provided', 'aqualuxe'); ?></label>
            <textarea id="project_services" name="project_services"><?php echo esc_textarea($services); ?></textarea>
        </div>

        <div class="aqualuxe-meta-field">
            <label for="project_gallery"><?php esc_html_e('Gallery Image IDs (comma-separated)', 'aqualuxe'); ?></label>
            <input type="text" id="project_gallery" name="project_gallery" value="<?php echo esc_attr($gallery); ?>">
            <button type="button" class="button" id="project_gallery_button"><?php esc_html_e('Select Images', 'aqualuxe'); ?></button>
        </div>

        <div class="aqualuxe-meta-field">
            <label for="project_video_url"><?php esc_html_e('Video URL', 'aqualuxe'); ?></label>
            <input type="url" id="project_video_url" name="project_video_url" value="<?php echo esc_url($video_url); ?>">
        </div>

        <div class="aqualuxe-meta-field">
            <label for="featured_project"><?php esc_html_e('Featured Project', 'aqualuxe'); ?></label>
            <input type="checkbox" id="featured_project" name="featured_project" value="1" <?php checked($featured, '1'); ?>>
        </div>
    </div>

    <script>
    jQuery(document).ready(function($) {
        $('#project_gallery_button').click(function(e) {
            e.preventDefault();
            
            var galleryFrame = wp.media({
                title: '<?php esc_html_e('Select Gallery Images', 'aqualuxe'); ?>',
                button: {
                    text: '<?php esc_html_e('Add to Gallery', 'aqualuxe'); ?>',
                },
                multiple: true
            });
            
            galleryFrame.on('select', function() {
                var attachments = galleryFrame.state().get('selection').map(function(attachment) {
                    attachment = attachment.toJSON();
                    return attachment.id;
                });
                
                $('#project_gallery').val(attachments.join(','));
            });
            
            galleryFrame.open();
        });
    });
    </script>
    <?php
}

/**
 * Location meta box callback
 *
 * @param WP_Post $post The post object.
 */
function aqualuxe_location_meta_box_callback($post) {
    // Add nonce for security
    wp_nonce_field('aqualuxe_location_meta_box', 'aqualuxe_location_meta_box_nonce');

    // Get current values
    $address = get_post_meta($post->ID, '_location_address', true);
    $city = get_post_meta($post->ID, '_location_city', true);
    $state = get_post_meta($post->ID, '_location_state', true);
    $postal_code = get_post_meta($post->ID, '_location_postal_code', true);
    $country = get_post_meta($post->ID, '_location_country', true);
    $phone = get_post_meta($post->ID, '_location_phone', true);
    $email = get_post_meta($post->ID, '_location_email', true);
    $hours = get_post_meta($post->ID, '_location_hours', true);
    $map_coordinates = get_post_meta($post->ID, '_location_map_coordinates', true);
    $featured = get_post_meta($post->ID, '_featured_location', true);

    ?>
    <div class="aqualuxe-meta-box">
        <div class="aqualuxe-meta-field">
            <label for="location_address"><?php esc_html_e('Address', 'aqualuxe'); ?></label>
            <input type="text" id="location_address" name="location_address" value="<?php echo esc_attr($address); ?>">
        </div>

        <div class="aqualuxe-meta-field">
            <label for="location_city"><?php esc_html_e('City', 'aqualuxe'); ?></label>
            <input type="text" id="location_city" name="location_city" value="<?php echo esc_attr($city); ?>">
        </div>

        <div class="aqualuxe-meta-field">
            <label for="location_state"><?php esc_html_e('State/Province', 'aqualuxe'); ?></label>
            <input type="text" id="location_state" name="location_state" value="<?php echo esc_attr($state); ?>">
        </div>

        <div class="aqualuxe-meta-field">
            <label for="location_postal_code"><?php esc_html_e('Postal Code', 'aqualuxe'); ?></label>
            <input type="text" id="location_postal_code" name="location_postal_code" value="<?php echo esc_attr($postal_code); ?>">
        </div>

        <div class="aqualuxe-meta-field">
            <label for="location_country"><?php esc_html_e('Country', 'aqualuxe'); ?></label>
            <input type="text" id="location_country" name="location_country" value="<?php echo esc_attr($country); ?>">
        </div>

        <div class="aqualuxe-meta-field">
            <label for="location_phone"><?php esc_html_e('Phone', 'aqualuxe'); ?></label>
            <input type="text" id="location_phone" name="location_phone" value="<?php echo esc_attr($phone); ?>">
        </div>

        <div class="aqualuxe-meta-field">
            <label for="location_email"><?php esc_html_e('Email', 'aqualuxe'); ?></label>
            <input type="email" id="location_email" name="location_email" value="<?php echo esc_attr($email); ?>">
        </div>

        <div class="aqualuxe-meta-field">
            <label for="location_hours"><?php esc_html_e('Business Hours', 'aqualuxe'); ?></label>
            <textarea id="location_hours" name="location_hours"><?php echo esc_textarea($hours); ?></textarea>
        </div>

        <div class="aqualuxe-meta-field">
            <label for="location_map_coordinates"><?php esc_html_e('Map Coordinates (latitude,longitude)', 'aqualuxe'); ?></label>
            <input type="text" id="location_map_coordinates" name="location_map_coordinates" value="<?php echo esc_attr($map_coordinates); ?>">
        </div>

        <div class="aqualuxe-meta-field">
            <label for="featured_location"><?php esc_html_e('Featured Location', 'aqualuxe'); ?></label>
            <input type="checkbox" id="featured_location" name="featured_location" value="1" <?php checked($featured, '1'); ?>>
        </div>
    </div>
    <?php
}

/**
 * Save meta box data
 *
 * @param int $post_id The post ID.
 */
function aqualuxe_save_meta_box_data($post_id) {
    // Check if our nonce is set for each meta box
    $nonces = array(
        'aqualuxe_testimonial_meta_box_nonce' => 'aqualuxe_testimonial_meta_box',
        'aqualuxe_team_member_meta_box_nonce' => 'aqualuxe_team_member_meta_box',
        'aqualuxe_service_meta_box_nonce' => 'aqualuxe_service_meta_box',
        'aqualuxe_event_meta_box_nonce' => 'aqualuxe_event_meta_box',
        'aqualuxe_project_meta_box_nonce' => 'aqualuxe_project_meta_box',
        'aqualuxe_location_meta_box_nonce' => 'aqualuxe_location_meta_box',
    );

    $nonce_verified = false;
    foreach ($nonces as $nonce_field => $nonce_action) {
        if (isset($_POST[$nonce_field]) && wp_verify_nonce($_POST[$nonce_field], $nonce_action)) {
            $nonce_verified = true;
            break;
        }
    }

    if (!$nonce_verified) {
        return;
    }

    // Check if this is an autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check the user's permissions
    if (isset($_POST['post_type'])) {
        if ('page' === $_POST['post_type']) {
            if (!current_user_can('edit_page', $post_id)) {
                return;
            }
        } else {
            if (!current_user_can('edit_post', $post_id)) {
                return;
            }
        }
    }

    // Save testimonial meta data
    if (isset($_POST['client_name'])) {
        update_post_meta($post_id, '_client_name', sanitize_text_field($_POST['client_name']));
    }
    if (isset($_POST['client_company'])) {
        update_post_meta($post_id, '_client_company', sanitize_text_field($_POST['client_company']));
    }
    if (isset($_POST['client_position'])) {
        update_post_meta($post_id, '_client_position', sanitize_text_field($_POST['client_position']));
    }
    if (isset($_POST['client_location'])) {
        update_post_meta($post_id, '_client_location', sanitize_text_field($_POST['client_location']));
    }
    if (isset($_POST['client_rating'])) {
        update_post_meta($post_id, '_client_rating', sanitize_text_field($_POST['client_rating']));
    }
    if (isset($_POST['testimonial_date'])) {
        update_post_meta($post_id, '_testimonial_date', sanitize_text_field($_POST['testimonial_date']));
    }
    update_post_meta($post_id, '_featured_testimonial', isset($_POST['featured_testimonial']) ? '1' : '');

    // Save team member meta data
    if (isset($_POST['position'])) {
        update_post_meta($post_id, '_position', sanitize_text_field($_POST['position']));
    }
    if (isset($_POST['department'])) {
        update_post_meta($post_id, '_department', sanitize_text_field($_POST['department']));
    }
    if (isset($_POST['email'])) {
        update_post_meta($post_id, '_email', sanitize_email($_POST['email']));
    }
    if (isset($_POST['phone'])) {
        update_post_meta($post_id, '_phone', sanitize_text_field($_POST['phone']));
    }
    if (isset($_POST['linkedin'])) {
        update_post_meta($post_id, '_linkedin', esc_url_raw($_POST['linkedin']));
    }
    if (isset($_POST['twitter'])) {
        update_post_meta($post_id, '_twitter', esc_url_raw($_POST['twitter']));
    }
    if (isset($_POST['facebook'])) {
        update_post_meta($post_id, '_facebook', esc_url_raw($_POST['facebook']));
    }
    if (isset($_POST['instagram'])) {
        update_post_meta($post_id, '_instagram', esc_url_raw($_POST['instagram']));
    }
    update_post_meta($post_id, '_featured_team_member', isset($_POST['featured_team_member']) ? '1' : '');

    // Save service meta data
    if (isset($_POST['service_icon'])) {
        update_post_meta($post_id, '_service_icon', sanitize_text_field($_POST['service_icon']));
    }
    if (isset($_POST['service_price'])) {
        update_post_meta($post_id, '_service_price', sanitize_text_field($_POST['service_price']));
    }
    if (isset($_POST['service_duration'])) {
        update_post_meta($post_id, '_service_duration', sanitize_text_field($_POST['service_duration']));
    }
    if (isset($_POST['button_text'])) {
        update_post_meta($post_id, '_button_text', sanitize_text_field($_POST['button_text']));
    }
    if (isset($_POST['button_url'])) {
        update_post_meta($post_id, '_button_url', esc_url_raw($_POST['button_url']));
    }
    update_post_meta($post_id, '_featured_service', isset($_POST['featured_service']) ? '1' : '');

    // Save event meta data
    if (isset($_POST['event_start_date'])) {
        update_post_meta($post_id, '_event_start_date', sanitize_text_field($_POST['event_start_date']));
    }
    if (isset($_POST['event_end_date'])) {
        update_post_meta($post_id, '_event_end_date', sanitize_text_field($_POST['event_end_date']));
    }
    if (isset($_POST['event_start_time'])) {
        update_post_meta($post_id, '_event_start_time', sanitize_text_field($_POST['event_start_time']));
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
    if (isset($_POST['event_map_coordinates'])) {
        update_post_meta($post_id, '_event_map_coordinates', sanitize_text_field($_POST['event_map_coordinates']));
    }
    if (isset($_POST['event_registration_url'])) {
        update_post_meta($post_id, '_event_registration_url', esc_url_raw($_POST['event_registration_url']));
    }
    if (isset($_POST['event_price'])) {
        update_post_meta($post_id, '_event_price', sanitize_text_field($_POST['event_price']));
    }
    if (isset($_POST['event_capacity'])) {
        update_post_meta($post_id, '_event_capacity', absint($_POST['event_capacity']));
    }
    update_post_meta($post_id, '_featured_event', isset($_POST['featured_event']) ? '1' : '');

    // Save project meta data
    if (isset($_POST['project_client'])) {
        update_post_meta($post_id, '_project_client', sanitize_text_field($_POST['project_client']));
    }
    if (isset($_POST['project_location'])) {
        update_post_meta($post_id, '_project_location', sanitize_text_field($_POST['project_location']));
    }
    if (isset($_POST['project_completion_date'])) {
        update_post_meta($post_id, '_project_completion_date', sanitize_text_field($_POST['project_completion_date']));
    }
    if (isset($_POST['project_budget'])) {
        update_post_meta($post_id, '_project_budget', sanitize_text_field($_POST['project_budget']));
    }
    if (isset($_POST['project_services'])) {
        update_post_meta($post_id, '_project_services', sanitize_textarea_field($_POST['project_services']));
    }
    if (isset($_POST['project_gallery'])) {
        update_post_meta($post_id, '_project_gallery', sanitize_text_field($_POST['project_gallery']));
    }
    if (isset($_POST['project_video_url'])) {
        update_post_meta($post_id, '_project_video_url', esc_url_raw($_POST['project_video_url']));
    }
    update_post_meta($post_id, '_featured_project', isset($_POST['featured_project']) ? '1' : '');

    // Save location meta data
    if (isset($_POST['location_address'])) {
        update_post_meta($post_id, '_location_address', sanitize_text_field($_POST['location_address']));
    }
    if (isset($_POST['location_city'])) {
        update_post_meta($post_id, '_location_city', sanitize_text_field($_POST['location_city']));
    }
    if (isset($_POST['location_state'])) {
        update_post_meta($post_id, '_location_state', sanitize_text_field($_POST['location_state']));
    }
    if (isset($_POST['location_postal_code'])) {
        update_post_meta($post_id, '_location_postal_code', sanitize_text_field($_POST['location_postal_code']));
    }
    if (isset($_POST['location_country'])) {
        update_post_meta($post_id, '_location_country', sanitize_text_field($_POST['location_country']));
    }
    if (isset($_POST['location_phone'])) {
        update_post_meta($post_id, '_location_phone', sanitize_text_field($_POST['location_phone']));
    }
    if (isset($_POST['location_email'])) {
        update_post_meta($post_id, '_location_email', sanitize_email($_POST['location_email']));
    }
    if (isset($_POST['location_hours'])) {
        update_post_meta($post_id, '_location_hours', sanitize_textarea_field($_POST['location_hours']));
    }
    if (isset($_POST['location_map_coordinates'])) {
        update_post_meta($post_id, '_location_map_coordinates', sanitize_text_field($_POST['location_map_coordinates']));
    }
    update_post_meta($post_id, '_featured_location', isset($_POST['featured_location']) ? '1' : '');
}
add_action('save_post', 'aqualuxe_save_meta_box_data');

/**
 * Add admin columns for custom post types
 */
function aqualuxe_add_admin_columns() {
    // Testimonial columns
    add_filter('manage_testimonial_posts_columns', 'aqualuxe_testimonial_columns');
    add_action('manage_testimonial_posts_custom_column', 'aqualuxe_testimonial_custom_column', 10, 2);
    
    // Team Member columns
    add_filter('manage_team_member_posts_columns', 'aqualuxe_team_member_columns');
    add_action('manage_team_member_posts_custom_column', 'aqualuxe_team_member_custom_column', 10, 2);
    
    // Service columns
    add_filter('manage_service_posts_columns', 'aqualuxe_service_columns');
    add_action('manage_service_posts_custom_column', 'aqualuxe_service_custom_column', 10, 2);
    
    // Event columns
    add_filter('manage_event_posts_columns', 'aqualuxe_event_columns');
    add_action('manage_event_posts_custom_column', 'aqualuxe_event_custom_column', 10, 2);
    
    // Project columns
    add_filter('manage_project_posts_columns', 'aqualuxe_project_columns');
    add_action('manage_project_posts_custom_column', 'aqualuxe_project_custom_column', 10, 2);
    
    // Location columns
    add_filter('manage_location_posts_columns', 'aqualuxe_location_columns');
    add_action('manage_location_posts_custom_column', 'aqualuxe_location_custom_column', 10, 2);
}
add_action('admin_init', 'aqualuxe_add_admin_columns');

/**
 * Define testimonial columns
 *
 * @param array $columns Array of columns.
 * @return array Modified array of columns.
 */
function aqualuxe_testimonial_columns($columns) {
    $columns = array(
        'cb' => $columns['cb'],
        'title' => __('Title', 'aqualuxe'),
        'client_name' => __('Client Name', 'aqualuxe'),
        'client_company' => __('Company', 'aqualuxe'),
        'rating' => __('Rating', 'aqualuxe'),
        'featured' => __('Featured', 'aqualuxe'),
        'date' => __('Date', 'aqualuxe'),
    );
    return $columns;
}

/**
 * Display testimonial custom column content
 *
 * @param string $column Column name.
 * @param int $post_id Post ID.
 */
function aqualuxe_testimonial_custom_column($column, $post_id) {
    switch ($column) {
        case 'client_name':
            echo esc_html(get_post_meta($post_id, '_client_name', true));
            break;
        case 'client_company':
            echo esc_html(get_post_meta($post_id, '_client_company', true));
            break;
        case 'rating':
            $rating = get_post_meta($post_id, '_client_rating', true);
            echo esc_html($rating ? $rating . '/5' : '-');
            break;
        case 'featured':
            $featured = get_post_meta($post_id, '_featured_testimonial', true);
            echo $featured ? '✓' : '–';
            break;
    }
}

/**
 * Define team member columns
 *
 * @param array $columns Array of columns.
 * @return array Modified array of columns.
 */
function aqualuxe_team_member_columns($columns) {
    $columns = array(
        'cb' => $columns['cb'],
        'thumbnail' => __('Photo', 'aqualuxe'),
        'title' => __('Name', 'aqualuxe'),
        'position' => __('Position', 'aqualuxe'),
        'department' => __('Department', 'aqualuxe'),
        'featured' => __('Featured', 'aqualuxe'),
        'date' => __('Date', 'aqualuxe'),
    );
    return $columns;
}

/**
 * Display team member custom column content
 *
 * @param string $column Column name.
 * @param int $post_id Post ID.
 */
function aqualuxe_team_member_custom_column($column, $post_id) {
    switch ($column) {
        case 'thumbnail':
            if (has_post_thumbnail($post_id)) {
                echo get_the_post_thumbnail($post_id, array(50, 50));
            } else {
                echo '–';
            }
            break;
        case 'position':
            echo esc_html(get_post_meta($post_id, '_position', true));
            break;
        case 'department':
            echo esc_html(get_post_meta($post_id, '_department', true));
            break;
        case 'featured':
            $featured = get_post_meta($post_id, '_featured_team_member', true);
            echo $featured ? '✓' : '–';
            break;
    }
}

/**
 * Define service columns
 *
 * @param array $columns Array of columns.
 * @return array Modified array of columns.
 */
function aqualuxe_service_columns($columns) {
    $columns = array(
        'cb' => $columns['cb'],
        'title' => __('Title', 'aqualuxe'),
        'price' => __('Price', 'aqualuxe'),
        'duration' => __('Duration', 'aqualuxe'),
        'featured' => __('Featured', 'aqualuxe'),
        'date' => __('Date', 'aqualuxe'),
    );
    return $columns;
}

/**
 * Display service custom column content
 *
 * @param string $column Column name.
 * @param int $post_id Post ID.
 */
function aqualuxe_service_custom_column($column, $post_id) {
    switch ($column) {
        case 'price':
            echo esc_html(get_post_meta($post_id, '_service_price', true));
            break;
        case 'duration':
            echo esc_html(get_post_meta($post_id, '_service_duration', true));
            break;
        case 'featured':
            $featured = get_post_meta($post_id, '_featured_service', true);
            echo $featured ? '✓' : '–';
            break;
    }
}

/**
 * Define event columns
 *
 * @param array $columns Array of columns.
 * @return array Modified array of columns.
 */
function aqualuxe_event_columns($columns) {
    $columns = array(
        'cb' => $columns['cb'],
        'title' => __('Title', 'aqualuxe'),
        'start_date' => __('Start Date', 'aqualuxe'),
        'location' => __('Location', 'aqualuxe'),
        'price' => __('Price', 'aqualuxe'),
        'featured' => __('Featured', 'aqualuxe'),
        'date' => __('Date', 'aqualuxe'),
    );
    return $columns;
}

/**
 * Display event custom column content
 *
 * @param string $column Column name.
 * @param int $post_id Post ID.
 */
function aqualuxe_event_custom_column($column, $post_id) {
    switch ($column) {
        case 'start_date':
            $start_date = get_post_meta($post_id, '_event_start_date', true);
            echo esc_html($start_date);
            break;
        case 'location':
            echo esc_html(get_post_meta($post_id, '_event_location', true));
            break;
        case 'price':
            echo esc_html(get_post_meta($post_id, '_event_price', true));
            break;
        case 'featured':
            $featured = get_post_meta($post_id, '_featured_event', true);
            echo $featured ? '✓' : '–';
            break;
    }
}

/**
 * Define project columns
 *
 * @param array $columns Array of columns.
 * @return array Modified array of columns.
 */
function aqualuxe_project_columns($columns) {
    $columns = array(
        'cb' => $columns['cb'],
        'thumbnail' => __('Thumbnail', 'aqualuxe'),
        'title' => __('Title', 'aqualuxe'),
        'client' => __('Client', 'aqualuxe'),
        'location' => __('Location', 'aqualuxe'),
        'completion_date' => __('Completion Date', 'aqualuxe'),
        'featured' => __('Featured', 'aqualuxe'),
        'date' => __('Date', 'aqualuxe'),
    );
    return $columns;
}

/**
 * Display project custom column content
 *
 * @param string $column Column name.
 * @param int $post_id Post ID.
 */
function aqualuxe_project_custom_column($column, $post_id) {
    switch ($column) {
        case 'thumbnail':
            if (has_post_thumbnail($post_id)) {
                echo get_the_post_thumbnail($post_id, array(50, 50));
            } else {
                echo '–';
            }
            break;
        case 'client':
            echo esc_html(get_post_meta($post_id, '_project_client', true));
            break;
        case 'location':
            echo esc_html(get_post_meta($post_id, '_project_location', true));
            break;
        case 'completion_date':
            echo esc_html(get_post_meta($post_id, '_project_completion_date', true));
            break;
        case 'featured':
            $featured = get_post_meta($post_id, '_featured_project', true);
            echo $featured ? '✓' : '–';
            break;
    }
}

/**
 * Define location columns
 *
 * @param array $columns Array of columns.
 * @return array Modified array of columns.
 */
function aqualuxe_location_columns($columns) {
    $columns = array(
        'cb' => $columns['cb'],
        'title' => __('Title', 'aqualuxe'),
        'address' => __('Address', 'aqualuxe'),
        'city' => __('City', 'aqualuxe'),
        'country' => __('Country', 'aqualuxe'),
        'featured' => __('Featured', 'aqualuxe'),
        'date' => __('Date', 'aqualuxe'),
    );
    return $columns;
}

/**
 * Display location custom column content
 *
 * @param string $column Column name.
 * @param int $post_id Post ID.
 */
function aqualuxe_location_custom_column($column, $post_id) {
    switch ($column) {
        case 'address':
            echo esc_html(get_post_meta($post_id, '_location_address', true));
            break;
        case 'city':
            echo esc_html(get_post_meta($post_id, '_location_city', true));
            break;
        case 'country':
            echo esc_html(get_post_meta($post_id, '_location_country', true));
            break;
        case 'featured':
            $featured = get_post_meta($post_id, '_featured_location', true);
            echo $featured ? '✓' : '–';
            break;
    }
}

/**
 * Add sortable columns for custom post types
 */
function aqualuxe_sortable_columns() {
    // Testimonial sortable columns
    add_filter('manage_edit-testimonial_sortable_columns', 'aqualuxe_testimonial_sortable_columns');
    
    // Team Member sortable columns
    add_filter('manage_edit-team_member_sortable_columns', 'aqualuxe_team_member_sortable_columns');
    
    // Service sortable columns
    add_filter('manage_edit-service_sortable_columns', 'aqualuxe_service_sortable_columns');
    
    // Event sortable columns
    add_filter('manage_edit-event_sortable_columns', 'aqualuxe_event_sortable_columns');
    
    // Project sortable columns
    add_filter('manage_edit-project_sortable_columns', 'aqualuxe_project_sortable_columns');
    
    // Location sortable columns
    add_filter('manage_edit-location_sortable_columns', 'aqualuxe_location_sortable_columns');
}
add_action('admin_init', 'aqualuxe_sortable_columns');

/**
 * Define testimonial sortable columns
 *
 * @param array $columns Array of sortable columns.
 * @return array Modified array of sortable columns.
 */
function aqualuxe_testimonial_sortable_columns($columns) {
    $columns['client_name'] = 'client_name';
    $columns['client_company'] = 'client_company';
    $columns['rating'] = 'rating';
    $columns['featured'] = 'featured';
    return $columns;
}

/**
 * Define team member sortable columns
 *
 * @param array $columns Array of sortable columns.
 * @return array Modified array of sortable columns.
 */
function aqualuxe_team_member_sortable_columns($columns) {
    $columns['position'] = 'position';
    $columns['department'] = 'department';
    $columns['featured'] = 'featured';
    return $columns;
}

/**
 * Define service sortable columns
 *
 * @param array $columns Array of sortable columns.
 * @return array Modified array of sortable columns.
 */
function aqualuxe_service_sortable_columns($columns) {
    $columns['price'] = 'price';
    $columns['duration'] = 'duration';
    $columns['featured'] = 'featured';
    return $columns;
}

/**
 * Define event sortable columns
 *
 * @param array $columns Array of sortable columns.
 * @return array Modified array of sortable columns.
 */
function aqualuxe_event_sortable_columns($columns) {
    $columns['start_date'] = 'start_date';
    $columns['location'] = 'location';
    $columns['price'] = 'price';
    $columns['featured'] = 'featured';
    return $columns;
}

/**
 * Define project sortable columns
 *
 * @param array $columns Array of sortable columns.
 * @return array Modified array of sortable columns.
 */
function aqualuxe_project_sortable_columns($columns) {
    $columns['client'] = 'client';
    $columns['location'] = 'location';
    $columns['completion_date'] = 'completion_date';
    $columns['featured'] = 'featured';
    return $columns;
}

/**
 * Define location sortable columns
 *
 * @param array $columns Array of sortable columns.
 * @return array Modified array of sortable columns.
 */
function aqualuxe_location_sortable_columns($columns) {
    $columns['city'] = 'city';
    $columns['country'] = 'country';
    $columns['featured'] = 'featured';
    return $columns;
}

/**
 * Add custom query vars for sorting
 *
 * @param WP_Query $query The WP_Query instance.
 */
function aqualuxe_custom_orderby($query) {
    if (!is_admin()) {
        return;
    }

    $orderby = $query->get('orderby');

    if ('client_name' === $orderby) {
        $query->set('meta_key', '_client_name');
        $query->set('orderby', 'meta_value');
    } elseif ('client_company' === $orderby) {
        $query->set('meta_key', '_client_company');
        $query->set('orderby', 'meta_value');
    } elseif ('rating' === $orderby) {
        $query->set('meta_key', '_client_rating');
        $query->set('orderby', 'meta_value_num');
    } elseif ('position' === $orderby) {
        $query->set('meta_key', '_position');
        $query->set('orderby', 'meta_value');
    } elseif ('department' === $orderby) {
        $query->set('meta_key', '_department');
        $query->set('orderby', 'meta_value');
    } elseif ('price' === $orderby) {
        $query->set('meta_key', '_service_price');
        $query->set('orderby', 'meta_value');
    } elseif ('duration' === $orderby) {
        $query->set('meta_key', '_service_duration');
        $query->set('orderby', 'meta_value');
    } elseif ('start_date' === $orderby) {
        $query->set('meta_key', '_event_start_date');
        $query->set('orderby', 'meta_value');
    } elseif ('location' === $orderby) {
        $query->set('meta_key', '_event_location');
        $query->set('orderby', 'meta_value');
    } elseif ('client' === $orderby) {
        $query->set('meta_key', '_project_client');
        $query->set('orderby', 'meta_value');
    } elseif ('completion_date' === $orderby) {
        $query->set('meta_key', '_project_completion_date');
        $query->set('orderby', 'meta_value');
    } elseif ('city' === $orderby) {
        $query->set('meta_key', '_location_city');
        $query->set('orderby', 'meta_value');
    } elseif ('country' === $orderby) {
        $query->set('meta_key', '_location_country');
        $query->set('orderby', 'meta_value');
    } elseif ('featured' === $orderby) {
        $post_type = $query->get('post_type');
        
        if ('testimonial' === $post_type) {
            $query->set('meta_key', '_featured_testimonial');
        } elseif ('team_member' === $post_type) {
            $query->set('meta_key', '_featured_team_member');
        } elseif ('service' === $post_type) {
            $query->set('meta_key', '_featured_service');
        } elseif ('event' === $post_type) {
            $query->set('meta_key', '_featured_event');
        } elseif ('project' === $post_type) {
            $query->set('meta_key', '_featured_project');
        } elseif ('location' === $post_type) {
            $query->set('meta_key', '_featured_location');
        }
        
        $query->set('orderby', 'meta_value');
    }
}
add_action('pre_get_posts', 'aqualuxe_custom_orderby');

/**
 * Flush rewrite rules on theme activation
 */
function aqualuxe_rewrite_flush() {
    aqualuxe_register_post_types();
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'aqualuxe_rewrite_flush');