<?php
/**
 * Custom post types for the AquaLuxe theme
 *
 * @package AquaLuxe
 */

/**
 * Register custom post types
 */
function aqualuxe_register_post_types() {
    // Services
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
            'not_found_in_trash' => __('No services found in Trash.', 'aqualuxe'),
        ),
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
    ));

    // Events
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
            'not_found_in_trash' => __('No events found in Trash.', 'aqualuxe'),
        ),
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
    ));

    // Team Members
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
            'not_found_in_trash' => __('No team members found in Trash.', 'aqualuxe'),
        ),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'team'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 22,
        'menu_icon'          => 'dashicons-groups',
        'supports'           => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'show_in_rest'       => true,
    ));

    // Projects
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
            'not_found_in_trash' => __('No projects found in Trash.', 'aqualuxe'),
        ),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'projects'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 23,
        'menu_icon'          => 'dashicons-portfolio',
        'supports'           => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'show_in_rest'       => true,
    ));

    // Testimonials
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
            'not_found_in_trash' => __('No testimonials found in Trash.', 'aqualuxe'),
        ),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'testimonials'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 24,
        'menu_icon'          => 'dashicons-format-quote',
        'supports'           => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'show_in_rest'       => true,
    ));

    // FAQs
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
            'not_found_in_trash' => __('No FAQs found in Trash.', 'aqualuxe'),
        ),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'faqs'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 25,
        'menu_icon'          => 'dashicons-editor-help',
        'supports'           => array('title', 'editor', 'excerpt', 'custom-fields'),
        'show_in_rest'       => true,
    ));

    // Care Guides
    register_post_type('aqualuxe_care_guide', array(
        'labels' => array(
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
            'not_found_in_trash' => __('No care guides found in Trash.', 'aqualuxe'),
        ),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'care-guides'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 26,
        'menu_icon'          => 'dashicons-book-alt',
        'supports'           => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'show_in_rest'       => true,
    ));

    // Auctions
    register_post_type('aqualuxe_auction', array(
        'labels' => array(
            'name'               => _x('Auctions', 'post type general name', 'aqualuxe'),
            'singular_name'      => _x('Auction', 'post type singular name', 'aqualuxe'),
            'menu_name'          => _x('Auctions', 'admin menu', 'aqualuxe'),
            'name_admin_bar'     => _x('Auction', 'add new on admin bar', 'aqualuxe'),
            'add_new'            => _x('Add New', 'auction', 'aqualuxe'),
            'add_new_item'       => __('Add New Auction', 'aqualuxe'),
            'new_item'           => __('New Auction', 'aqualuxe'),
            'edit_item'          => __('Edit Auction', 'aqualuxe'),
            'view_item'          => __('View Auction', 'aqualuxe'),
            'all_items'          => __('All Auctions', 'aqualuxe'),
            'search_items'       => __('Search Auctions', 'aqualuxe'),
            'parent_item_colon'  => __('Parent Auctions:', 'aqualuxe'),
            'not_found'          => __('No auctions found.', 'aqualuxe'),
            'not_found_in_trash' => __('No auctions found in Trash.', 'aqualuxe'),
        ),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'auctions'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 27,
        'menu_icon'          => 'dashicons-money-alt',
        'supports'           => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'show_in_rest'       => true,
    ));

    // Trade-Ins
    register_post_type('aqualuxe_trade_in', array(
        'labels' => array(
            'name'               => _x('Trade-Ins', 'post type general name', 'aqualuxe'),
            'singular_name'      => _x('Trade-In', 'post type singular name', 'aqualuxe'),
            'menu_name'          => _x('Trade-Ins', 'admin menu', 'aqualuxe'),
            'name_admin_bar'     => _x('Trade-In', 'add new on admin bar', 'aqualuxe'),
            'add_new'            => _x('Add New', 'trade-in', 'aqualuxe'),
            'add_new_item'       => __('Add New Trade-In', 'aqualuxe'),
            'new_item'           => __('New Trade-In', 'aqualuxe'),
            'edit_item'          => __('Edit Trade-In', 'aqualuxe'),
            'view_item'          => __('View Trade-In', 'aqualuxe'),
            'all_items'          => __('All Trade-Ins', 'aqualuxe'),
            'search_items'       => __('Search Trade-Ins', 'aqualuxe'),
            'parent_item_colon'  => __('Parent Trade-Ins:', 'aqualuxe'),
            'not_found'          => __('No trade-ins found.', 'aqualuxe'),
            'not_found_in_trash' => __('No trade-ins found in Trash.', 'aqualuxe'),
        ),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'trade-ins'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 28,
        'menu_icon'          => 'dashicons-update',
        'supports'           => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'show_in_rest'       => true,
    ));
}
add_action('init', 'aqualuxe_register_post_types');

/**
 * Add custom meta boxes for post types
 */
function aqualuxe_add_meta_boxes() {
    // Service details meta box
    add_meta_box(
        'aqualuxe_service_details',
        __('Service Details', 'aqualuxe'),
        'aqualuxe_service_details_callback',
        'aqualuxe_service',
        'normal',
        'high'
    );

    // Event details meta box
    add_meta_box(
        'aqualuxe_event_details',
        __('Event Details', 'aqualuxe'),
        'aqualuxe_event_details_callback',
        'aqualuxe_event',
        'normal',
        'high'
    );

    // Team member details meta box
    add_meta_box(
        'aqualuxe_team_details',
        __('Team Member Details', 'aqualuxe'),
        'aqualuxe_team_details_callback',
        'aqualuxe_team',
        'normal',
        'high'
    );

    // Project details meta box
    add_meta_box(
        'aqualuxe_project_details',
        __('Project Details', 'aqualuxe'),
        'aqualuxe_project_details_callback',
        'aqualuxe_project',
        'normal',
        'high'
    );

    // Testimonial details meta box
    add_meta_box(
        'aqualuxe_testimonial_details',
        __('Testimonial Details', 'aqualuxe'),
        'aqualuxe_testimonial_details_callback',
        'aqualuxe_testimonial',
        'normal',
        'high'
    );

    // Auction details meta box
    add_meta_box(
        'aqualuxe_auction_details',
        __('Auction Details', 'aqualuxe'),
        'aqualuxe_auction_details_callback',
        'aqualuxe_auction',
        'normal',
        'high'
    );

    // Trade-In details meta box
    add_meta_box(
        'aqualuxe_trade_in_details',
        __('Trade-In Details', 'aqualuxe'),
        'aqualuxe_trade_in_details_callback',
        'aqualuxe_trade_in',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'aqualuxe_add_meta_boxes');

/**
 * Service details meta box callback
 */
function aqualuxe_service_details_callback($post) {
    // Add nonce for security
    wp_nonce_field('aqualuxe_service_details_nonce', 'aqualuxe_service_details_nonce');

    // Get current values
    $service_price = get_post_meta($post->ID, '_aqualuxe_service_price', true);
    $service_duration = get_post_meta($post->ID, '_aqualuxe_service_duration', true);
    $service_icon = get_post_meta($post->ID, '_aqualuxe_service_icon', true);
    $service_featured = get_post_meta($post->ID, '_aqualuxe_service_featured', true);

    // Output fields
    ?>
    <p>
        <label for="aqualuxe_service_price"><?php esc_html_e('Price', 'aqualuxe'); ?></label>
        <input type="text" id="aqualuxe_service_price" name="aqualuxe_service_price" value="<?php echo esc_attr($service_price); ?>" class="regular-text" />
        <span class="description"><?php esc_html_e('Enter the price for this service (e.g., $99.99, Contact for pricing, etc.).', 'aqualuxe'); ?></span>
    </p>
    <p>
        <label for="aqualuxe_service_duration"><?php esc_html_e('Duration', 'aqualuxe'); ?></label>
        <input type="text" id="aqualuxe_service_duration" name="aqualuxe_service_duration" value="<?php echo esc_attr($service_duration); ?>" class="regular-text" />
        <span class="description"><?php esc_html_e('Enter the duration for this service (e.g., 1 hour, 2-3 days, etc.).', 'aqualuxe'); ?></span>
    </p>
    <p>
        <label for="aqualuxe_service_icon"><?php esc_html_e('Icon', 'aqualuxe'); ?></label>
        <input type="text" id="aqualuxe_service_icon" name="aqualuxe_service_icon" value="<?php echo esc_attr($service_icon); ?>" class="regular-text" />
        <span class="description"><?php esc_html_e('Enter the icon class for this service (e.g., fa-fish, fa-water, etc.).', 'aqualuxe'); ?></span>
    </p>
    <p>
        <label for="aqualuxe_service_featured">
            <input type="checkbox" id="aqualuxe_service_featured" name="aqualuxe_service_featured" value="1" <?php checked($service_featured, '1'); ?> />
            <?php esc_html_e('Featured Service', 'aqualuxe'); ?>
        </label>
        <span class="description"><?php esc_html_e('Check this box to mark this service as featured.', 'aqualuxe'); ?></span>
    </p>
    <?php
}

/**
 * Event details meta box callback
 */
function aqualuxe_event_details_callback($post) {
    // Add nonce for security
    wp_nonce_field('aqualuxe_event_details_nonce', 'aqualuxe_event_details_nonce');

    // Get current values
    $event_date = get_post_meta($post->ID, '_aqualuxe_event_date', true);
    $event_time = get_post_meta($post->ID, '_aqualuxe_event_time', true);
    $event_location = get_post_meta($post->ID, '_aqualuxe_event_location', true);
    $event_price = get_post_meta($post->ID, '_aqualuxe_event_price', true);
    $event_registration_url = get_post_meta($post->ID, '_aqualuxe_event_registration_url', true);

    // Output fields
    ?>
    <p>
        <label for="aqualuxe_event_date"><?php esc_html_e('Date', 'aqualuxe'); ?></label>
        <input type="date" id="aqualuxe_event_date" name="aqualuxe_event_date" value="<?php echo esc_attr($event_date); ?>" class="regular-text" />
        <span class="description"><?php esc_html_e('Enter the date for this event.', 'aqualuxe'); ?></span>
    </p>
    <p>
        <label for="aqualuxe_event_time"><?php esc_html_e('Time', 'aqualuxe'); ?></label>
        <input type="text" id="aqualuxe_event_time" name="aqualuxe_event_time" value="<?php echo esc_attr($event_time); ?>" class="regular-text" />
        <span class="description"><?php esc_html_e('Enter the time for this event (e.g., 7:00 PM - 9:00 PM).', 'aqualuxe'); ?></span>
    </p>
    <p>
        <label for="aqualuxe_event_location"><?php esc_html_e('Location', 'aqualuxe'); ?></label>
        <input type="text" id="aqualuxe_event_location" name="aqualuxe_event_location" value="<?php echo esc_attr($event_location); ?>" class="regular-text" />
        <span class="description"><?php esc_html_e('Enter the location for this event.', 'aqualuxe'); ?></span>
    </p>
    <p>
        <label for="aqualuxe_event_price"><?php esc_html_e('Price', 'aqualuxe'); ?></label>
        <input type="text" id="aqualuxe_event_price" name="aqualuxe_event_price" value="<?php echo esc_attr($event_price); ?>" class="regular-text" />
        <span class="description"><?php esc_html_e('Enter the price for this event (e.g., $25, Free, etc.).', 'aqualuxe'); ?></span>
    </p>
    <p>
        <label for="aqualuxe_event_registration_url"><?php esc_html_e('Registration URL', 'aqualuxe'); ?></label>
        <input type="url" id="aqualuxe_event_registration_url" name="aqualuxe_event_registration_url" value="<?php echo esc_url($event_registration_url); ?>" class="regular-text" />
        <span class="description"><?php esc_html_e('Enter the URL for event registration.', 'aqualuxe'); ?></span>
    </p>
    <?php
}

/**
 * Team member details meta box callback
 */
function aqualuxe_team_details_callback($post) {
    // Add nonce for security
    wp_nonce_field('aqualuxe_team_details_nonce', 'aqualuxe_team_details_nonce');

    // Get current values
    $team_position = get_post_meta($post->ID, '_aqualuxe_team_position', true);
    $team_email = get_post_meta($post->ID, '_aqualuxe_team_email', true);
    $team_phone = get_post_meta($post->ID, '_aqualuxe_team_phone', true);
    $team_facebook = get_post_meta($post->ID, '_aqualuxe_team_facebook', true);
    $team_twitter = get_post_meta($post->ID, '_aqualuxe_team_twitter', true);
    $team_linkedin = get_post_meta($post->ID, '_aqualuxe_team_linkedin', true);
    $team_instagram = get_post_meta($post->ID, '_aqualuxe_team_instagram', true);

    // Output fields
    ?>
    <p>
        <label for="aqualuxe_team_position"><?php esc_html_e('Position', 'aqualuxe'); ?></label>
        <input type="text" id="aqualuxe_team_position" name="aqualuxe_team_position" value="<?php echo esc_attr($team_position); ?>" class="regular-text" />
        <span class="description"><?php esc_html_e('Enter the position of this team member.', 'aqualuxe'); ?></span>
    </p>
    <p>
        <label for="aqualuxe_team_email"><?php esc_html_e('Email', 'aqualuxe'); ?></label>
        <input type="email" id="aqualuxe_team_email" name="aqualuxe_team_email" value="<?php echo esc_attr($team_email); ?>" class="regular-text" />
        <span class="description"><?php esc_html_e('Enter the email address of this team member.', 'aqualuxe'); ?></span>
    </p>
    <p>
        <label for="aqualuxe_team_phone"><?php esc_html_e('Phone', 'aqualuxe'); ?></label>
        <input type="text" id="aqualuxe_team_phone" name="aqualuxe_team_phone" value="<?php echo esc_attr($team_phone); ?>" class="regular-text" />
        <span class="description"><?php esc_html_e('Enter the phone number of this team member.', 'aqualuxe'); ?></span>
    </p>
    <p>
        <label for="aqualuxe_team_facebook"><?php esc_html_e('Facebook', 'aqualuxe'); ?></label>
        <input type="url" id="aqualuxe_team_facebook" name="aqualuxe_team_facebook" value="<?php echo esc_url($team_facebook); ?>" class="regular-text" />
        <span class="description"><?php esc_html_e('Enter the Facebook URL of this team member.', 'aqualuxe'); ?></span>
    </p>
    <p>
        <label for="aqualuxe_team_twitter"><?php esc_html_e('Twitter', 'aqualuxe'); ?></label>
        <input type="url" id="aqualuxe_team_twitter" name="aqualuxe_team_twitter" value="<?php echo esc_url($team_twitter); ?>" class="regular-text" />
        <span class="description"><?php esc_html_e('Enter the Twitter URL of this team member.', 'aqualuxe'); ?></span>
    </p>
    <p>
        <label for="aqualuxe_team_linkedin"><?php esc_html_e('LinkedIn', 'aqualuxe'); ?></label>
        <input type="url" id="aqualuxe_team_linkedin" name="aqualuxe_team_linkedin" value="<?php echo esc_url($team_linkedin); ?>" class="regular-text" />
        <span class="description"><?php esc_html_e('Enter the LinkedIn URL of this team member.', 'aqualuxe'); ?></span>
    </p>
    <p>
        <label for="aqualuxe_team_instagram"><?php esc_html_e('Instagram', 'aqualuxe'); ?></label>
        <input type="url" id="aqualuxe_team_instagram" name="aqualuxe_team_instagram" value="<?php echo esc_url($team_instagram); ?>" class="regular-text" />
        <span class="description"><?php esc_html_e('Enter the Instagram URL of this team member.', 'aqualuxe'); ?></span>
    </p>
    <?php
}

/**
 * Project details meta box callback
 */
function aqualuxe_project_details_callback($post) {
    // Add nonce for security
    wp_nonce_field('aqualuxe_project_details_nonce', 'aqualuxe_project_details_nonce');

    // Get current values
    $project_client = get_post_meta($post->ID, '_aqualuxe_project_client', true);
    $project_location = get_post_meta($post->ID, '_aqualuxe_project_location', true);
    $project_date = get_post_meta($post->ID, '_aqualuxe_project_date', true);
    $project_url = get_post_meta($post->ID, '_aqualuxe_project_url', true);
    $project_featured = get_post_meta($post->ID, '_aqualuxe_project_featured', true);

    // Output fields
    ?>
    <p>
        <label for="aqualuxe_project_client"><?php esc_html_e('Client', 'aqualuxe'); ?></label>
        <input type="text" id="aqualuxe_project_client" name="aqualuxe_project_client" value="<?php echo esc_attr($project_client); ?>" class="regular-text" />
        <span class="description"><?php esc_html_e('Enter the client name for this project.', 'aqualuxe'); ?></span>
    </p>
    <p>
        <label for="aqualuxe_project_location"><?php esc_html_e('Location', 'aqualuxe'); ?></label>
        <input type="text" id="aqualuxe_project_location" name="aqualuxe_project_location" value="<?php echo esc_attr($project_location); ?>" class="regular-text" />
        <span class="description"><?php esc_html_e('Enter the location for this project.', 'aqualuxe'); ?></span>
    </p>
    <p>
        <label for="aqualuxe_project_date"><?php esc_html_e('Date', 'aqualuxe'); ?></label>
        <input type="text" id="aqualuxe_project_date" name="aqualuxe_project_date" value="<?php echo esc_attr($project_date); ?>" class="regular-text" />
        <span class="description"><?php esc_html_e('Enter the completion date for this project.', 'aqualuxe'); ?></span>
    </p>
    <p>
        <label for="aqualuxe_project_url"><?php esc_html_e('Project URL', 'aqualuxe'); ?></label>
        <input type="url" id="aqualuxe_project_url" name="aqualuxe_project_url" value="<?php echo esc_url($project_url); ?>" class="regular-text" />
        <span class="description"><?php esc_html_e('Enter the URL for this project.', 'aqualuxe'); ?></span>
    </p>
    <p>
        <label for="aqualuxe_project_featured">
            <input type="checkbox" id="aqualuxe_project_featured" name="aqualuxe_project_featured" value="1" <?php checked($project_featured, '1'); ?> />
            <?php esc_html_e('Featured Project', 'aqualuxe'); ?>
        </label>
        <span class="description"><?php esc_html_e('Check this box to mark this project as featured.', 'aqualuxe'); ?></span>
    </p>
    <?php
}

/**
 * Testimonial details meta box callback
 */
function aqualuxe_testimonial_details_callback($post) {
    // Add nonce for security
    wp_nonce_field('aqualuxe_testimonial_details_nonce', 'aqualuxe_testimonial_details_nonce');

    // Get current values
    $testimonial_author = get_post_meta($post->ID, '_aqualuxe_testimonial_author', true);
    $testimonial_position = get_post_meta($post->ID, '_aqualuxe_testimonial_position', true);
    $testimonial_company = get_post_meta($post->ID, '_aqualuxe_testimonial_company', true);
    $testimonial_rating = get_post_meta($post->ID, '_aqualuxe_testimonial_rating', true);
    $testimonial_featured = get_post_meta($post->ID, '_aqualuxe_testimonial_featured', true);

    // Output fields
    ?>
    <p>
        <label for="aqualuxe_testimonial_author"><?php esc_html_e('Author', 'aqualuxe'); ?></label>
        <input type="text" id="aqualuxe_testimonial_author" name="aqualuxe_testimonial_author" value="<?php echo esc_attr($testimonial_author); ?>" class="regular-text" />
        <span class="description"><?php esc_html_e('Enter the author of this testimonial.', 'aqualuxe'); ?></span>
    </p>
    <p>
        <label for="aqualuxe_testimonial_position"><?php esc_html_e('Position', 'aqualuxe'); ?></label>
        <input type="text" id="aqualuxe_testimonial_position" name="aqualuxe_testimonial_position" value="<?php echo esc_attr($testimonial_position); ?>" class="regular-text" />
        <span class="description"><?php esc_html_e('Enter the position of the author.', 'aqualuxe'); ?></span>
    </p>
    <p>
        <label for="aqualuxe_testimonial_company"><?php esc_html_e('Company', 'aqualuxe'); ?></label>
        <input type="text" id="aqualuxe_testimonial_company" name="aqualuxe_testimonial_company" value="<?php echo esc_attr($testimonial_company); ?>" class="regular-text" />
        <span class="description"><?php esc_html_e('Enter the company of the author.', 'aqualuxe'); ?></span>
    </p>
    <p>
        <label for="aqualuxe_testimonial_rating"><?php esc_html_e('Rating', 'aqualuxe'); ?></label>
        <select id="aqualuxe_testimonial_rating" name="aqualuxe_testimonial_rating">
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
        <span class="description"><?php esc_html_e('Select the rating for this testimonial.', 'aqualuxe'); ?></span>
    </p>
    <p>
        <label for="aqualuxe_testimonial_featured">
            <input type="checkbox" id="aqualuxe_testimonial_featured" name="aqualuxe_testimonial_featured" value="1" <?php checked($testimonial_featured, '1'); ?> />
            <?php esc_html_e('Featured Testimonial', 'aqualuxe'); ?>
        </label>
        <span class="description"><?php esc_html_e('Check this box to mark this testimonial as featured.', 'aqualuxe'); ?></span>
    </p>
    <?php
}

/**
 * Auction details meta box callback
 */
function aqualuxe_auction_details_callback($post) {
    // Add nonce for security
    wp_nonce_field('aqualuxe_auction_details_nonce', 'aqualuxe_auction_details_nonce');

    // Get current values
    $auction_start_date = get_post_meta($post->ID, '_aqualuxe_auction_start_date', true);
    $auction_end_date = get_post_meta($post->ID, '_aqualuxe_auction_end_date', true);
    $auction_start_price = get_post_meta($post->ID, '_aqualuxe_auction_start_price', true);
    $auction_current_price = get_post_meta($post->ID, '_aqualuxe_auction_current_price', true);
    $auction_status = get_post_meta($post->ID, '_aqualuxe_auction_status', true);

    // Output fields
    ?>
    <p>
        <label for="aqualuxe_auction_start_date"><?php esc_html_e('Start Date', 'aqualuxe'); ?></label>
        <input type="datetime-local" id="aqualuxe_auction_start_date" name="aqualuxe_auction_start_date" value="<?php echo esc_attr($auction_start_date); ?>" class="regular-text" />
        <span class="description"><?php esc_html_e('Enter the start date and time for this auction.', 'aqualuxe'); ?></span>
    </p>
    <p>
        <label for="aqualuxe_auction_end_date"><?php esc_html_e('End Date', 'aqualuxe'); ?></label>
        <input type="datetime-local" id="aqualuxe_auction_end_date" name="aqualuxe_auction_end_date" value="<?php echo esc_attr($auction_end_date); ?>" class="regular-text" />
        <span class="description"><?php esc_html_e('Enter the end date and time for this auction.', 'aqualuxe'); ?></span>
    </p>
    <p>
        <label for="aqualuxe_auction_start_price"><?php esc_html_e('Starting Price', 'aqualuxe'); ?></label>
        <input type="text" id="aqualuxe_auction_start_price" name="aqualuxe_auction_start_price" value="<?php echo esc_attr($auction_start_price); ?>" class="regular-text" />
        <span class="description"><?php esc_html_e('Enter the starting price for this auction.', 'aqualuxe'); ?></span>
    </p>
    <p>
        <label for="aqualuxe_auction_current_price"><?php esc_html_e('Current Price', 'aqualuxe'); ?></label>
        <input type="text" id="aqualuxe_auction_current_price" name="aqualuxe_auction_current_price" value="<?php echo esc_attr($auction_current_price); ?>" class="regular-text" />
        <span class="description"><?php esc_html_e('Enter the current price for this auction.', 'aqualuxe'); ?></span>
    </p>
    <p>
        <label for="aqualuxe_auction_status"><?php esc_html_e('Status', 'aqualuxe'); ?></label>
        <select id="aqualuxe_auction_status" name="aqualuxe_auction_status">
            <option value="upcoming" <?php selected($auction_status, 'upcoming'); ?>><?php esc_html_e('Upcoming', 'aqualuxe'); ?></option>
            <option value="active" <?php selected($auction_status, 'active'); ?>><?php esc_html_e('Active', 'aqualuxe'); ?></option>
            <option value="ended" <?php selected($auction_status, 'ended'); ?>><?php esc_html_e('Ended', 'aqualuxe'); ?></option>
            <option value="sold" <?php selected($auction_status, 'sold'); ?>><?php esc_html_e('Sold', 'aqualuxe'); ?></option>
        </select>
        <span class="description"><?php esc_html_e('Select the status for this auction.', 'aqualuxe'); ?></span>
    </p>
    <?php
}

/**
 * Trade-In details meta box callback
 */
function aqualuxe_trade_in_details_callback($post) {
    // Add nonce for security
    wp_nonce_field('aqualuxe_trade_in_details_nonce', 'aqualuxe_trade_in_details_nonce');

    // Get current values
    $trade_in_type = get_post_meta($post->ID, '_aqualuxe_trade_in_type', true);
    $trade_in_condition = get_post_meta($post->ID, '_aqualuxe_trade_in_condition', true);
    $trade_in_value = get_post_meta($post->ID, '_aqualuxe_trade_in_value', true);
    $trade_in_status = get_post_meta($post->ID, '_aqualuxe_trade_in_status', true);

    // Output fields
    ?>
    <p>
        <label for="aqualuxe_trade_in_type"><?php esc_html_e('Type', 'aqualuxe'); ?></label>
        <select id="aqualuxe_trade_in_type" name="aqualuxe_trade_in_type">
            <option value="fish" <?php selected($trade_in_type, 'fish'); ?>><?php esc_html_e('Fish', 'aqualuxe'); ?></option>
            <option value="plant" <?php selected($trade_in_type, 'plant'); ?>><?php esc_html_e('Plant', 'aqualuxe'); ?></option>
            <option value="equipment" <?php selected($trade_in_type, 'equipment'); ?>><?php esc_html_e('Equipment', 'aqualuxe'); ?></option>
            <option value="aquarium" <?php selected($trade_in_type, 'aquarium'); ?>><?php esc_html_e('Aquarium', 'aqualuxe'); ?></option>
            <option value="other" <?php selected($trade_in_type, 'other'); ?>><?php esc_html_e('Other', 'aqualuxe'); ?></option>
        </select>
        <span class="description"><?php esc_html_e('Select the type for this trade-in.', 'aqualuxe'); ?></span>
    </p>
    <p>
        <label for="aqualuxe_trade_in_condition"><?php esc_html_e('Condition', 'aqualuxe'); ?></label>
        <select id="aqualuxe_trade_in_condition" name="aqualuxe_trade_in_condition">
            <option value="excellent" <?php selected($trade_in_condition, 'excellent'); ?>><?php esc_html_e('Excellent', 'aqualuxe'); ?></option>
            <option value="good" <?php selected($trade_in_condition, 'good'); ?>><?php esc_html_e('Good', 'aqualuxe'); ?></option>
            <option value="fair" <?php selected($trade_in_condition, 'fair'); ?>><?php esc_html_e('Fair', 'aqualuxe'); ?></option>
            <option value="poor" <?php selected($trade_in_condition, 'poor'); ?>><?php esc_html_e('Poor', 'aqualuxe'); ?></option>
        </select>
        <span class="description"><?php esc_html_e('Select the condition for this trade-in.', 'aqualuxe'); ?></span>
    </p>
    <p>
        <label for="aqualuxe_trade_in_value"><?php esc_html_e('Value', 'aqualuxe'); ?></label>
        <input type="text" id="aqualuxe_trade_in_value" name="aqualuxe_trade_in_value" value="<?php echo esc_attr($trade_in_value); ?>" class="regular-text" />
        <span class="description"><?php esc_html_e('Enter the value for this trade-in.', 'aqualuxe'); ?></span>
    </p>
    <p>
        <label for="aqualuxe_trade_in_status"><?php esc_html_e('Status', 'aqualuxe'); ?></label>
        <select id="aqualuxe_trade_in_status" name="aqualuxe_trade_in_status">
            <option value="available" <?php selected($trade_in_status, 'available'); ?>><?php esc_html_e('Available', 'aqualuxe'); ?></option>
            <option value="pending" <?php selected($trade_in_status, 'pending'); ?>><?php esc_html_e('Pending', 'aqualuxe'); ?></option>
            <option value="sold" <?php selected($trade_in_status, 'sold'); ?>><?php esc_html_e('Sold', 'aqualuxe'); ?></option>
        </select>
        <span class="description"><?php esc_html_e('Select the status for this trade-in.', 'aqualuxe'); ?></span>
    </p>
    <?php
}

/**
 * Save post meta when the post is saved
 */
function aqualuxe_save_post_meta($post_id) {
    // Check if our nonce is set
    if (!isset($_POST['aqualuxe_service_details_nonce']) &&
        !isset($_POST['aqualuxe_event_details_nonce']) &&
        !isset($_POST['aqualuxe_team_details_nonce']) &&
        !isset($_POST['aqualuxe_project_details_nonce']) &&
        !isset($_POST['aqualuxe_testimonial_details_nonce']) &&
        !isset($_POST['aqualuxe_auction_details_nonce']) &&
        !isset($_POST['aqualuxe_trade_in_details_nonce'])) {
        return;
    }

    // Verify that the nonce is valid
    if ((isset($_POST['aqualuxe_service_details_nonce']) && !wp_verify_nonce($_POST['aqualuxe_service_details_nonce'], 'aqualuxe_service_details_nonce')) ||
        (isset($_POST['aqualuxe_event_details_nonce']) && !wp_verify_nonce($_POST['aqualuxe_event_details_nonce'], 'aqualuxe_event_details_nonce')) ||
        (isset($_POST['aqualuxe_team_details_nonce']) && !wp_verify_nonce($_POST['aqualuxe_team_details_nonce'], 'aqualuxe_team_details_nonce')) ||
        (isset($_POST['aqualuxe_project_details_nonce']) && !wp_verify_nonce($_POST['aqualuxe_project_details_nonce'], 'aqualuxe_project_details_nonce')) ||
        (isset($_POST['aqualuxe_testimonial_details_nonce']) && !wp_verify_nonce($_POST['aqualuxe_testimonial_details_nonce'], 'aqualuxe_testimonial_details_nonce')) ||
        (isset($_POST['aqualuxe_auction_details_nonce']) && !wp_verify_nonce($_POST['aqualuxe_auction_details_nonce'], 'aqualuxe_auction_details_nonce')) ||
        (isset($_POST['aqualuxe_trade_in_details_nonce']) && !wp_verify_nonce($_POST['aqualuxe_trade_in_details_nonce'], 'aqualuxe_trade_in_details_nonce'))) {
        return;
    }

    // Check if this is an autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check the user's permissions
    if (isset($_POST['post_type'])) {
        if ($_POST['post_type'] === 'aqualuxe_service' && !current_user_can('edit_post', $post_id)) {
            return;
        } elseif ($_POST['post_type'] === 'aqualuxe_event' && !current_user_can('edit_post', $post_id)) {
            return;
        } elseif ($_POST['post_type'] === 'aqualuxe_team' && !current_user_can('edit_post', $post_id)) {
            return;
        } elseif ($_POST['post_type'] === 'aqualuxe_project' && !current_user_can('edit_post', $post_id)) {
            return;
        } elseif ($_POST['post_type'] === 'aqualuxe_testimonial' && !current_user_can('edit_post', $post_id)) {
            return;
        } elseif ($_POST['post_type'] === 'aqualuxe_auction' && !current_user_can('edit_post', $post_id)) {
            return;
        } elseif ($_POST['post_type'] === 'aqualuxe_trade_in' && !current_user_can('edit_post', $post_id)) {
            return;
        }
    }

    // Save service details
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

    // Save event details
    if (isset($_POST['aqualuxe_event_date'])) {
        update_post_meta($post_id, '_aqualuxe_event_date', sanitize_text_field($_POST['aqualuxe_event_date']));
    }
    if (isset($_POST['aqualuxe_event_time'])) {
        update_post_meta($post_id, '_aqualuxe_event_time', sanitize_text_field($_POST['aqualuxe_event_time']));
    }
    if (isset($_POST['aqualuxe_event_location'])) {
        update_post_meta($post_id, '_aqualuxe_event_location', sanitize_text_field($_POST['aqualuxe_event_location']));
    }
    if (isset($_POST['aqualuxe_event_price'])) {
        update_post_meta($post_id, '_aqualuxe_event_price', sanitize_text_field($_POST['aqualuxe_event_price']));
    }
    if (isset($_POST['aqualuxe_event_registration_url'])) {
        update_post_meta($post_id, '_aqualuxe_event_registration_url', esc_url_raw($_POST['aqualuxe_event_registration_url']));
    }

    // Save team details
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

    // Save project details
    if (isset($_POST['aqualuxe_project_client'])) {
        update_post_meta($post_id, '_aqualuxe_project_client', sanitize_text_field($_POST['aqualuxe_project_client']));
    }
    if (isset($_POST['aqualuxe_project_location'])) {
        update_post_meta($post_id, '_aqualuxe_project_location', sanitize_text_field($_POST['aqualuxe_project_location']));
    }
    if (isset($_POST['aqualuxe_project_date'])) {
        update_post_meta($post_id, '_aqualuxe_project_date', sanitize_text_field($_POST['aqualuxe_project_date']));
    }
    if (isset($_POST['aqualuxe_project_url'])) {
        update_post_meta($post_id, '_aqualuxe_project_url', esc_url_raw($_POST['aqualuxe_project_url']));
    }
    update_post_meta($post_id, '_aqualuxe_project_featured', isset($_POST['aqualuxe_project_featured']) ? '1' : '');

    // Save testimonial details
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

    // Save auction details
    if (isset($_POST['aqualuxe_auction_start_date'])) {
        update_post_meta($post_id, '_aqualuxe_auction_start_date', sanitize_text_field($_POST['aqualuxe_auction_start_date']));
    }
    if (isset($_POST['aqualuxe_auction_end_date'])) {
        update_post_meta($post_id, '_aqualuxe_auction_end_date', sanitize_text_field($_POST['aqualuxe_auction_end_date']));
    }
    if (isset($_POST['aqualuxe_auction_start_price'])) {
        update_post_meta($post_id, '_aqualuxe_auction_start_price', sanitize_text_field($_POST['aqualuxe_auction_start_price']));
    }
    if (isset($_POST['aqualuxe_auction_current_price'])) {
        update_post_meta($post_id, '_aqualuxe_auction_current_price', sanitize_text_field($_POST['aqualuxe_auction_current_price']));
    }
    if (isset($_POST['aqualuxe_auction_status'])) {
        update_post_meta($post_id, '_aqualuxe_auction_status', sanitize_text_field($_POST['aqualuxe_auction_status']));
    }

    // Save trade-in details
    if (isset($_POST['aqualuxe_trade_in_type'])) {
        update_post_meta($post_id, '_aqualuxe_trade_in_type', sanitize_text_field($_POST['aqualuxe_trade_in_type']));
    }
    if (isset($_POST['aqualuxe_trade_in_condition'])) {
        update_post_meta($post_id, '_aqualuxe_trade_in_condition', sanitize_text_field($_POST['aqualuxe_trade_in_condition']));
    }
    if (isset($_POST['aqualuxe_trade_in_value'])) {
        update_post_meta($post_id, '_aqualuxe_trade_in_value', sanitize_text_field($_POST['aqualuxe_trade_in_value']));
    }
    if (isset($_POST['aqualuxe_trade_in_status'])) {
        update_post_meta($post_id, '_aqualuxe_trade_in_status', sanitize_text_field($_POST['aqualuxe_trade_in_status']));
    }
}
add_action('save_post', 'aqualuxe_save_post_meta');

/**
 * Add custom columns to the post type admin screens
 */
function aqualuxe_add_custom_columns($columns) {
    $post_type = get_current_screen()->post_type;

    if ($post_type === 'aqualuxe_service') {
        $columns['price'] = __('Price', 'aqualuxe');
        $columns['duration'] = __('Duration', 'aqualuxe');
        $columns['featured'] = __('Featured', 'aqualuxe');
    } elseif ($post_type === 'aqualuxe_event') {
        $columns['date_time'] = __('Date & Time', 'aqualuxe');
        $columns['location'] = __('Location', 'aqualuxe');
        $columns['price'] = __('Price', 'aqualuxe');
    } elseif ($post_type === 'aqualuxe_team') {
        $columns['position'] = __('Position', 'aqualuxe');
        $columns['email'] = __('Email', 'aqualuxe');
    } elseif ($post_type === 'aqualuxe_project') {
        $columns['client'] = __('Client', 'aqualuxe');
        $columns['location'] = __('Location', 'aqualuxe');
        $columns['featured'] = __('Featured', 'aqualuxe');
    } elseif ($post_type === 'aqualuxe_testimonial') {
        $columns['author'] = __('Author', 'aqualuxe');
        $columns['company'] = __('Company', 'aqualuxe');
        $columns['rating'] = __('Rating', 'aqualuxe');
        $columns['featured'] = __('Featured', 'aqualuxe');
    } elseif ($post_type === 'aqualuxe_auction') {
        $columns['dates'] = __('Dates', 'aqualuxe');
        $columns['price'] = __('Price', 'aqualuxe');
        $columns['status'] = __('Status', 'aqualuxe');
    } elseif ($post_type === 'aqualuxe_trade_in') {
        $columns['type'] = __('Type', 'aqualuxe');
        $columns['condition'] = __('Condition', 'aqualuxe');
        $columns['value'] = __('Value', 'aqualuxe');
        $columns['status'] = __('Status', 'aqualuxe');
    }

    return $columns;
}
add_filter('manage_posts_columns', 'aqualuxe_add_custom_columns');

/**
 * Display custom column content
 */
function aqualuxe_custom_column_content($column, $post_id) {
    $post_type = get_post_type($post_id);

    if ($post_type === 'aqualuxe_service') {
        if ($column === 'price') {
            echo esc_html(get_post_meta($post_id, '_aqualuxe_service_price', true));
        } elseif ($column === 'duration') {
            echo esc_html(get_post_meta($post_id, '_aqualuxe_service_duration', true));
        } elseif ($column === 'featured') {
            echo get_post_meta($post_id, '_aqualuxe_service_featured', true) ? '<span class="dashicons dashicons-star-filled"></span>' : '<span class="dashicons dashicons-star-empty"></span>';
        }
    } elseif ($post_type === 'aqualuxe_event') {
        if ($column === 'date_time') {
            $date = get_post_meta($post_id, '_aqualuxe_event_date', true);
            $time = get_post_meta($post_id, '_aqualuxe_event_time', true);
            echo esc_html($date . ' ' . $time);
        } elseif ($column === 'location') {
            echo esc_html(get_post_meta($post_id, '_aqualuxe_event_location', true));
        } elseif ($column === 'price') {
            echo esc_html(get_post_meta($post_id, '_aqualuxe_event_price', true));
        }
    } elseif ($post_type === 'aqualuxe_team') {
        if ($column === 'position') {
            echo esc_html(get_post_meta($post_id, '_aqualuxe_team_position', true));
        } elseif ($column === 'email') {
            echo esc_html(get_post_meta($post_id, '_aqualuxe_team_email', true));
        }
    } elseif ($post_type === 'aqualuxe_project') {
        if ($column === 'client') {
            echo esc_html(get_post_meta($post_id, '_aqualuxe_project_client', true));
        } elseif ($column === 'location') {
            echo esc_html(get_post_meta($post_id, '_aqualuxe_project_location', true));
        } elseif ($column === 'featured') {
            echo get_post_meta($post_id, '_aqualuxe_project_featured', true) ? '<span class="dashicons dashicons-star-filled"></span>' : '<span class="dashicons dashicons-star-empty"></span>';
        }
    } elseif ($post_type === 'aqualuxe_testimonial') {
        if ($column === 'author') {
            echo esc_html(get_post_meta($post_id, '_aqualuxe_testimonial_author', true));
        } elseif ($column === 'company') {
            echo esc_html(get_post_meta($post_id, '_aqualuxe_testimonial_company', true));
        } elseif ($column === 'rating') {
            $rating = get_post_meta($post_id, '_aqualuxe_testimonial_rating', true);
            echo esc_html($rating . ' / 5');
        } elseif ($column === 'featured') {
            echo get_post_meta($post_id, '_aqualuxe_testimonial_featured', true) ? '<span class="dashicons dashicons-star-filled"></span>' : '<span class="dashicons dashicons-star-empty"></span>';
        }
    } elseif ($post_type === 'aqualuxe_auction') {
        if ($column === 'dates') {
            $start_date = get_post_meta($post_id, '_aqualuxe_auction_start_date', true);
            $end_date = get_post_meta($post_id, '_aqualuxe_auction_end_date', true);
            echo esc_html($start_date . ' - ' . $end_date);
        } elseif ($column === 'price') {
            $start_price = get_post_meta($post_id, '_aqualuxe_auction_start_price', true);
            $current_price = get_post_meta($post_id, '_aqualuxe_auction_current_price', true);
            echo esc_html($start_price . ' / ' . $current_price);
        } elseif ($column === 'status') {
            echo esc_html(get_post_meta($post_id, '_aqualuxe_auction_status', true));
        }
    } elseif ($post_type === 'aqualuxe_trade_in') {
        if ($column === 'type') {
            echo esc_html(get_post_meta($post_id, '_aqualuxe_trade_in_type', true));
        } elseif ($column === 'condition') {
            echo esc_html(get_post_meta($post_id, '_aqualuxe_trade_in_condition', true));
        } elseif ($column === 'value') {
            echo esc_html(get_post_meta($post_id, '_aqualuxe_trade_in_value', true));
        } elseif ($column === 'status') {
            echo esc_html(get_post_meta($post_id, '_aqualuxe_trade_in_status', true));
        }
    }
}
add_action('manage_posts_custom_column', 'aqualuxe_custom_column_content', 10, 2);

/**
 * Make custom columns sortable
 */
function aqualuxe_sortable_columns($columns) {
    $post_type = get_current_screen()->post_type;

    if ($post_type === 'aqualuxe_service') {
        $columns['price'] = 'price';
        $columns['featured'] = 'featured';
    } elseif ($post_type === 'aqualuxe_event') {
        $columns['date_time'] = 'date_time';
        $columns['price'] = 'price';
    } elseif ($post_type === 'aqualuxe_team') {
        $columns['position'] = 'position';
    } elseif ($post_type === 'aqualuxe_project') {
        $columns['client'] = 'client';
        $columns['featured'] = 'featured';
    } elseif ($post_type === 'aqualuxe_testimonial') {
        $columns['author'] = 'author';
        $columns['rating'] = 'rating';
        $columns['featured'] = 'featured';
    } elseif ($post_type === 'aqualuxe_auction') {
        $columns['dates'] = 'dates';
        $columns['price'] = 'price';
        $columns['status'] = 'status';
    } elseif ($post_type === 'aqualuxe_trade_in') {
        $columns['type'] = 'type';
        $columns['condition'] = 'condition';
        $columns['value'] = 'value';
        $columns['status'] = 'status';
    }

    return $columns;
}
add_filter('manage_edit-post_sortable_columns', 'aqualuxe_sortable_columns');

/**
 * Flush rewrite rules on theme activation
 */
function aqualuxe_rewrite_flush() {
    aqualuxe_register_post_types();
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'aqualuxe_rewrite_flush');