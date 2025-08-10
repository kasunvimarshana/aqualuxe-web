<?php
/**
 * Custom Post Types for AquaLuxe Theme
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Register custom post types
 */
function aqualuxe_register_post_types() {
    // Services Post Type
    register_post_type(
        'service',
        array(
            'labels'              => array(
                'name'               => _x( 'Services', 'post type general name', 'aqualuxe' ),
                'singular_name'      => _x( 'Service', 'post type singular name', 'aqualuxe' ),
                'menu_name'          => _x( 'Services', 'admin menu', 'aqualuxe' ),
                'name_admin_bar'     => _x( 'Service', 'add new on admin bar', 'aqualuxe' ),
                'add_new'            => _x( 'Add New', 'service', 'aqualuxe' ),
                'add_new_item'       => __( 'Add New Service', 'aqualuxe' ),
                'new_item'           => __( 'New Service', 'aqualuxe' ),
                'edit_item'          => __( 'Edit Service', 'aqualuxe' ),
                'view_item'          => __( 'View Service', 'aqualuxe' ),
                'all_items'          => __( 'All Services', 'aqualuxe' ),
                'search_items'       => __( 'Search Services', 'aqualuxe' ),
                'parent_item_colon'  => __( 'Parent Services:', 'aqualuxe' ),
                'not_found'          => __( 'No services found.', 'aqualuxe' ),
                'not_found_in_trash' => __( 'No services found in Trash.', 'aqualuxe' ),
            ),
            'description'         => __( 'Services offered by AquaLuxe', 'aqualuxe' ),
            'public'              => true,
            'publicly_queryable'  => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'query_var'           => true,
            'rewrite'             => array( 'slug' => 'services' ),
            'capability_type'     => 'post',
            'has_archive'         => true,
            'hierarchical'        => false,
            'menu_position'       => 20,
            'menu_icon'           => 'dashicons-hammer',
            'supports'            => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'page-attributes' ),
            'show_in_rest'        => true,
        )
    );

    // Events Post Type
    register_post_type(
        'event',
        array(
            'labels'              => array(
                'name'               => _x( 'Events', 'post type general name', 'aqualuxe' ),
                'singular_name'      => _x( 'Event', 'post type singular name', 'aqualuxe' ),
                'menu_name'          => _x( 'Events', 'admin menu', 'aqualuxe' ),
                'name_admin_bar'     => _x( 'Event', 'add new on admin bar', 'aqualuxe' ),
                'add_new'            => _x( 'Add New', 'event', 'aqualuxe' ),
                'add_new_item'       => __( 'Add New Event', 'aqualuxe' ),
                'new_item'           => __( 'New Event', 'aqualuxe' ),
                'edit_item'          => __( 'Edit Event', 'aqualuxe' ),
                'view_item'          => __( 'View Event', 'aqualuxe' ),
                'all_items'          => __( 'All Events', 'aqualuxe' ),
                'search_items'       => __( 'Search Events', 'aqualuxe' ),
                'parent_item_colon'  => __( 'Parent Events:', 'aqualuxe' ),
                'not_found'          => __( 'No events found.', 'aqualuxe' ),
                'not_found_in_trash' => __( 'No events found in Trash.', 'aqualuxe' ),
            ),
            'description'         => __( 'Events hosted by AquaLuxe', 'aqualuxe' ),
            'public'              => true,
            'publicly_queryable'  => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'query_var'           => true,
            'rewrite'             => array( 'slug' => 'events' ),
            'capability_type'     => 'post',
            'has_archive'         => true,
            'hierarchical'        => false,
            'menu_position'       => 21,
            'menu_icon'           => 'dashicons-calendar-alt',
            'supports'            => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'page-attributes' ),
            'show_in_rest'        => true,
        )
    );

    // Team Members Post Type
    register_post_type(
        'team_member',
        array(
            'labels'              => array(
                'name'               => _x( 'Team', 'post type general name', 'aqualuxe' ),
                'singular_name'      => _x( 'Team Member', 'post type singular name', 'aqualuxe' ),
                'menu_name'          => _x( 'Team', 'admin menu', 'aqualuxe' ),
                'name_admin_bar'     => _x( 'Team Member', 'add new on admin bar', 'aqualuxe' ),
                'add_new'            => _x( 'Add New', 'team member', 'aqualuxe' ),
                'add_new_item'       => __( 'Add New Team Member', 'aqualuxe' ),
                'new_item'           => __( 'New Team Member', 'aqualuxe' ),
                'edit_item'          => __( 'Edit Team Member', 'aqualuxe' ),
                'view_item'          => __( 'View Team Member', 'aqualuxe' ),
                'all_items'          => __( 'All Team Members', 'aqualuxe' ),
                'search_items'       => __( 'Search Team Members', 'aqualuxe' ),
                'parent_item_colon'  => __( 'Parent Team Members:', 'aqualuxe' ),
                'not_found'          => __( 'No team members found.', 'aqualuxe' ),
                'not_found_in_trash' => __( 'No team members found in Trash.', 'aqualuxe' ),
            ),
            'description'         => __( 'Team members at AquaLuxe', 'aqualuxe' ),
            'public'              => true,
            'publicly_queryable'  => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'query_var'           => true,
            'rewrite'             => array( 'slug' => 'team' ),
            'capability_type'     => 'post',
            'has_archive'         => true,
            'hierarchical'        => false,
            'menu_position'       => 22,
            'menu_icon'           => 'dashicons-groups',
            'supports'            => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'page-attributes' ),
            'show_in_rest'        => true,
        )
    );

    // Testimonials Post Type
    register_post_type(
        'testimonial',
        array(
            'labels'              => array(
                'name'               => _x( 'Testimonials', 'post type general name', 'aqualuxe' ),
                'singular_name'      => _x( 'Testimonial', 'post type singular name', 'aqualuxe' ),
                'menu_name'          => _x( 'Testimonials', 'admin menu', 'aqualuxe' ),
                'name_admin_bar'     => _x( 'Testimonial', 'add new on admin bar', 'aqualuxe' ),
                'add_new'            => _x( 'Add New', 'testimonial', 'aqualuxe' ),
                'add_new_item'       => __( 'Add New Testimonial', 'aqualuxe' ),
                'new_item'           => __( 'New Testimonial', 'aqualuxe' ),
                'edit_item'          => __( 'Edit Testimonial', 'aqualuxe' ),
                'view_item'          => __( 'View Testimonial', 'aqualuxe' ),
                'all_items'          => __( 'All Testimonials', 'aqualuxe' ),
                'search_items'       => __( 'Search Testimonials', 'aqualuxe' ),
                'parent_item_colon'  => __( 'Parent Testimonials:', 'aqualuxe' ),
                'not_found'          => __( 'No testimonials found.', 'aqualuxe' ),
                'not_found_in_trash' => __( 'No testimonials found in Trash.', 'aqualuxe' ),
            ),
            'description'         => __( 'Customer testimonials for AquaLuxe', 'aqualuxe' ),
            'public'              => true,
            'publicly_queryable'  => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'query_var'           => true,
            'rewrite'             => array( 'slug' => 'testimonials' ),
            'capability_type'     => 'post',
            'has_archive'         => true,
            'hierarchical'        => false,
            'menu_position'       => 23,
            'menu_icon'           => 'dashicons-format-quote',
            'supports'            => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'page-attributes' ),
            'show_in_rest'        => true,
        )
    );

    // Projects Post Type
    register_post_type(
        'project',
        array(
            'labels'              => array(
                'name'               => _x( 'Projects', 'post type general name', 'aqualuxe' ),
                'singular_name'      => _x( 'Project', 'post type singular name', 'aqualuxe' ),
                'menu_name'          => _x( 'Projects', 'admin menu', 'aqualuxe' ),
                'name_admin_bar'     => _x( 'Project', 'add new on admin bar', 'aqualuxe' ),
                'add_new'            => _x( 'Add New', 'project', 'aqualuxe' ),
                'add_new_item'       => __( 'Add New Project', 'aqualuxe' ),
                'new_item'           => __( 'New Project', 'aqualuxe' ),
                'edit_item'          => __( 'Edit Project', 'aqualuxe' ),
                'view_item'          => __( 'View Project', 'aqualuxe' ),
                'all_items'          => __( 'All Projects', 'aqualuxe' ),
                'search_items'       => __( 'Search Projects', 'aqualuxe' ),
                'parent_item_colon'  => __( 'Parent Projects:', 'aqualuxe' ),
                'not_found'          => __( 'No projects found.', 'aqualuxe' ),
                'not_found_in_trash' => __( 'No projects found in Trash.', 'aqualuxe' ),
            ),
            'description'         => __( 'Aquascaping and installation projects by AquaLuxe', 'aqualuxe' ),
            'public'              => true,
            'publicly_queryable'  => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'query_var'           => true,
            'rewrite'             => array( 'slug' => 'projects' ),
            'capability_type'     => 'post',
            'has_archive'         => true,
            'hierarchical'        => false,
            'menu_position'       => 24,
            'menu_icon'           => 'dashicons-portfolio',
            'supports'            => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'page-attributes' ),
            'show_in_rest'        => true,
        )
    );

    // FAQ Post Type
    register_post_type(
        'faq',
        array(
            'labels'              => array(
                'name'               => _x( 'FAQs', 'post type general name', 'aqualuxe' ),
                'singular_name'      => _x( 'FAQ', 'post type singular name', 'aqualuxe' ),
                'menu_name'          => _x( 'FAQs', 'admin menu', 'aqualuxe' ),
                'name_admin_bar'     => _x( 'FAQ', 'add new on admin bar', 'aqualuxe' ),
                'add_new'            => _x( 'Add New', 'faq', 'aqualuxe' ),
                'add_new_item'       => __( 'Add New FAQ', 'aqualuxe' ),
                'new_item'           => __( 'New FAQ', 'aqualuxe' ),
                'edit_item'          => __( 'Edit FAQ', 'aqualuxe' ),
                'view_item'          => __( 'View FAQ', 'aqualuxe' ),
                'all_items'          => __( 'All FAQs', 'aqualuxe' ),
                'search_items'       => __( 'Search FAQs', 'aqualuxe' ),
                'parent_item_colon'  => __( 'Parent FAQs:', 'aqualuxe' ),
                'not_found'          => __( 'No FAQs found.', 'aqualuxe' ),
                'not_found_in_trash' => __( 'No FAQs found in Trash.', 'aqualuxe' ),
            ),
            'description'         => __( 'Frequently asked questions', 'aqualuxe' ),
            'public'              => true,
            'publicly_queryable'  => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'query_var'           => true,
            'rewrite'             => array( 'slug' => 'faqs' ),
            'capability_type'     => 'post',
            'has_archive'         => true,
            'hierarchical'        => false,
            'menu_position'       => 25,
            'menu_icon'           => 'dashicons-editor-help',
            'supports'            => array( 'title', 'editor', 'excerpt', 'custom-fields', 'page-attributes' ),
            'show_in_rest'        => true,
        )
    );
}
add_action( 'init', 'aqualuxe_register_post_types' );

/**
 * Add custom meta boxes for post types
 */
function aqualuxe_add_meta_boxes() {
    // Service meta box
    add_meta_box(
        'service_details',
        __( 'Service Details', 'aqualuxe' ),
        'aqualuxe_service_details_callback',
        'service',
        'normal',
        'high'
    );

    // Event meta box
    add_meta_box(
        'event_details',
        __( 'Event Details', 'aqualuxe' ),
        'aqualuxe_event_details_callback',
        'event',
        'normal',
        'high'
    );

    // Team member meta box
    add_meta_box(
        'team_member_details',
        __( 'Team Member Details', 'aqualuxe' ),
        'aqualuxe_team_member_details_callback',
        'team_member',
        'normal',
        'high'
    );

    // Testimonial meta box
    add_meta_box(
        'testimonial_details',
        __( 'Testimonial Details', 'aqualuxe' ),
        'aqualuxe_testimonial_details_callback',
        'testimonial',
        'normal',
        'high'
    );

    // Project meta box
    add_meta_box(
        'project_details',
        __( 'Project Details', 'aqualuxe' ),
        'aqualuxe_project_details_callback',
        'project',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'aqualuxe_add_meta_boxes' );

/**
 * Service details meta box callback
 */
function aqualuxe_service_details_callback( $post ) {
    wp_nonce_field( 'aqualuxe_service_details', 'aqualuxe_service_details_nonce' );

    $service_icon = get_post_meta( $post->ID, '_service_icon', true );
    $service_price = get_post_meta( $post->ID, '_service_price', true );
    $service_duration = get_post_meta( $post->ID, '_service_duration', true );
    $service_features = get_post_meta( $post->ID, '_service_features', true );

    ?>
    <p>
        <label for="service_icon"><?php esc_html_e( 'Service Icon', 'aqualuxe' ); ?></label><br>
        <input type="text" id="service_icon" name="service_icon" value="<?php echo esc_attr( $service_icon ); ?>" class="widefat" />
        <span class="description"><?php esc_html_e( 'Enter a Font Awesome icon class (e.g., "fa-fish") or a URL to a custom icon image.', 'aqualuxe' ); ?></span>
    </p>
    <p>
        <label for="service_price"><?php esc_html_e( 'Service Price', 'aqualuxe' ); ?></label><br>
        <input type="text" id="service_price" name="service_price" value="<?php echo esc_attr( $service_price ); ?>" class="widefat" />
        <span class="description"><?php esc_html_e( 'Enter the price for this service (e.g., "$99" or "Starting at $99").', 'aqualuxe' ); ?></span>
    </p>
    <p>
        <label for="service_duration"><?php esc_html_e( 'Service Duration', 'aqualuxe' ); ?></label><br>
        <input type="text" id="service_duration" name="service_duration" value="<?php echo esc_attr( $service_duration ); ?>" class="widefat" />
        <span class="description"><?php esc_html_e( 'Enter the duration for this service (e.g., "2 hours" or "1-2 weeks").', 'aqualuxe' ); ?></span>
    </p>
    <p>
        <label for="service_features"><?php esc_html_e( 'Service Features', 'aqualuxe' ); ?></label><br>
        <textarea id="service_features" name="service_features" class="widefat" rows="5"><?php echo esc_textarea( $service_features ); ?></textarea>
        <span class="description"><?php esc_html_e( 'Enter the features for this service, one per line.', 'aqualuxe' ); ?></span>
    </p>
    <?php
}

/**
 * Event details meta box callback
 */
function aqualuxe_event_details_callback( $post ) {
    wp_nonce_field( 'aqualuxe_event_details', 'aqualuxe_event_details_nonce' );

    $event_date = get_post_meta( $post->ID, '_event_date', true );
    $event_time = get_post_meta( $post->ID, '_event_time', true );
    $event_location = get_post_meta( $post->ID, '_event_location', true );
    $event_price = get_post_meta( $post->ID, '_event_price', true );
    $event_registration_url = get_post_meta( $post->ID, '_event_registration_url', true );

    ?>
    <p>
        <label for="event_date"><?php esc_html_e( 'Event Date', 'aqualuxe' ); ?></label><br>
        <input type="date" id="event_date" name="event_date" value="<?php echo esc_attr( $event_date ); ?>" class="widefat" />
    </p>
    <p>
        <label for="event_time"><?php esc_html_e( 'Event Time', 'aqualuxe' ); ?></label><br>
        <input type="text" id="event_time" name="event_time" value="<?php echo esc_attr( $event_time ); ?>" class="widefat" />
        <span class="description"><?php esc_html_e( 'Enter the time for this event (e.g., "6:00 PM - 8:00 PM").', 'aqualuxe' ); ?></span>
    </p>
    <p>
        <label for="event_location"><?php esc_html_e( 'Event Location', 'aqualuxe' ); ?></label><br>
        <input type="text" id="event_location" name="event_location" value="<?php echo esc_attr( $event_location ); ?>" class="widefat" />
    </p>
    <p>
        <label for="event_price"><?php esc_html_e( 'Event Price', 'aqualuxe' ); ?></label><br>
        <input type="text" id="event_price" name="event_price" value="<?php echo esc_attr( $event_price ); ?>" class="widefat" />
        <span class="description"><?php esc_html_e( 'Enter the price for this event (e.g., "$25" or "Free").', 'aqualuxe' ); ?></span>
    </p>
    <p>
        <label for="event_registration_url"><?php esc_html_e( 'Registration URL', 'aqualuxe' ); ?></label><br>
        <input type="url" id="event_registration_url" name="event_registration_url" value="<?php echo esc_url( $event_registration_url ); ?>" class="widefat" />
    </p>
    <?php
}

/**
 * Team member details meta box callback
 */
function aqualuxe_team_member_details_callback( $post ) {
    wp_nonce_field( 'aqualuxe_team_member_details', 'aqualuxe_team_member_details_nonce' );

    $team_member_position = get_post_meta( $post->ID, '_team_member_position', true );
    $team_member_email = get_post_meta( $post->ID, '_team_member_email', true );
    $team_member_phone = get_post_meta( $post->ID, '_team_member_phone', true );
    $team_member_facebook = get_post_meta( $post->ID, '_team_member_facebook', true );
    $team_member_twitter = get_post_meta( $post->ID, '_team_member_twitter', true );
    $team_member_linkedin = get_post_meta( $post->ID, '_team_member_linkedin', true );
    $team_member_instagram = get_post_meta( $post->ID, '_team_member_instagram', true );

    ?>
    <p>
        <label for="team_member_position"><?php esc_html_e( 'Position', 'aqualuxe' ); ?></label><br>
        <input type="text" id="team_member_position" name="team_member_position" value="<?php echo esc_attr( $team_member_position ); ?>" class="widefat" />
    </p>
    <p>
        <label for="team_member_email"><?php esc_html_e( 'Email', 'aqualuxe' ); ?></label><br>
        <input type="email" id="team_member_email" name="team_member_email" value="<?php echo esc_attr( $team_member_email ); ?>" class="widefat" />
    </p>
    <p>
        <label for="team_member_phone"><?php esc_html_e( 'Phone', 'aqualuxe' ); ?></label><br>
        <input type="text" id="team_member_phone" name="team_member_phone" value="<?php echo esc_attr( $team_member_phone ); ?>" class="widefat" />
    </p>
    <p>
        <label for="team_member_facebook"><?php esc_html_e( 'Facebook URL', 'aqualuxe' ); ?></label><br>
        <input type="url" id="team_member_facebook" name="team_member_facebook" value="<?php echo esc_url( $team_member_facebook ); ?>" class="widefat" />
    </p>
    <p>
        <label for="team_member_twitter"><?php esc_html_e( 'Twitter URL', 'aqualuxe' ); ?></label><br>
        <input type="url" id="team_member_twitter" name="team_member_twitter" value="<?php echo esc_url( $team_member_twitter ); ?>" class="widefat" />
    </p>
    <p>
        <label for="team_member_linkedin"><?php esc_html_e( 'LinkedIn URL', 'aqualuxe' ); ?></label><br>
        <input type="url" id="team_member_linkedin" name="team_member_linkedin" value="<?php echo esc_url( $team_member_linkedin ); ?>" class="widefat" />
    </p>
    <p>
        <label for="team_member_instagram"><?php esc_html_e( 'Instagram URL', 'aqualuxe' ); ?></label><br>
        <input type="url" id="team_member_instagram" name="team_member_instagram" value="<?php echo esc_url( $team_member_instagram ); ?>" class="widefat" />
    </p>
    <?php
}

/**
 * Testimonial details meta box callback
 */
function aqualuxe_testimonial_details_callback( $post ) {
    wp_nonce_field( 'aqualuxe_testimonial_details', 'aqualuxe_testimonial_details_nonce' );

    $testimonial_author = get_post_meta( $post->ID, '_testimonial_author', true );
    $testimonial_position = get_post_meta( $post->ID, '_testimonial_position', true );
    $testimonial_company = get_post_meta( $post->ID, '_testimonial_company', true );
    $testimonial_rating = get_post_meta( $post->ID, '_testimonial_rating', true );

    ?>
    <p>
        <label for="testimonial_author"><?php esc_html_e( 'Author Name', 'aqualuxe' ); ?></label><br>
        <input type="text" id="testimonial_author" name="testimonial_author" value="<?php echo esc_attr( $testimonial_author ); ?>" class="widefat" />
    </p>
    <p>
        <label for="testimonial_position"><?php esc_html_e( 'Author Position', 'aqualuxe' ); ?></label><br>
        <input type="text" id="testimonial_position" name="testimonial_position" value="<?php echo esc_attr( $testimonial_position ); ?>" class="widefat" />
    </p>
    <p>
        <label for="testimonial_company"><?php esc_html_e( 'Author Company', 'aqualuxe' ); ?></label><br>
        <input type="text" id="testimonial_company" name="testimonial_company" value="<?php echo esc_attr( $testimonial_company ); ?>" class="widefat" />
    </p>
    <p>
        <label for="testimonial_rating"><?php esc_html_e( 'Rating (1-5)', 'aqualuxe' ); ?></label><br>
        <select id="testimonial_rating" name="testimonial_rating" class="widefat">
            <option value=""><?php esc_html_e( 'Select a rating', 'aqualuxe' ); ?></option>
            <option value="5" <?php selected( $testimonial_rating, '5' ); ?>>5 <?php esc_html_e( 'Stars', 'aqualuxe' ); ?></option>
            <option value="4.5" <?php selected( $testimonial_rating, '4.5' ); ?>>4.5 <?php esc_html_e( 'Stars', 'aqualuxe' ); ?></option>
            <option value="4" <?php selected( $testimonial_rating, '4' ); ?>>4 <?php esc_html_e( 'Stars', 'aqualuxe' ); ?></option>
            <option value="3.5" <?php selected( $testimonial_rating, '3.5' ); ?>>3.5 <?php esc_html_e( 'Stars', 'aqualuxe' ); ?></option>
            <option value="3" <?php selected( $testimonial_rating, '3' ); ?>>3 <?php esc_html_e( 'Stars', 'aqualuxe' ); ?></option>
            <option value="2.5" <?php selected( $testimonial_rating, '2.5' ); ?>>2.5 <?php esc_html_e( 'Stars', 'aqualuxe' ); ?></option>
            <option value="2" <?php selected( $testimonial_rating, '2' ); ?>>2 <?php esc_html_e( 'Stars', 'aqualuxe' ); ?></option>
            <option value="1.5" <?php selected( $testimonial_rating, '1.5' ); ?>>1.5 <?php esc_html_e( 'Stars', 'aqualuxe' ); ?></option>
            <option value="1" <?php selected( $testimonial_rating, '1' ); ?>>1 <?php esc_html_e( 'Star', 'aqualuxe' ); ?></option>
        </select>
    </p>
    <?php
}

/**
 * Project details meta box callback
 */
function aqualuxe_project_details_callback( $post ) {
    wp_nonce_field( 'aqualuxe_project_details', 'aqualuxe_project_details_nonce' );

    $project_client = get_post_meta( $post->ID, '_project_client', true );
    $project_location = get_post_meta( $post->ID, '_project_location', true );
    $project_date = get_post_meta( $post->ID, '_project_date', true );
    $project_duration = get_post_meta( $post->ID, '_project_duration', true );
    $project_budget = get_post_meta( $post->ID, '_project_budget', true );
    $project_gallery = get_post_meta( $post->ID, '_project_gallery', true );

    ?>
    <p>
        <label for="project_client"><?php esc_html_e( 'Client', 'aqualuxe' ); ?></label><br>
        <input type="text" id="project_client" name="project_client" value="<?php echo esc_attr( $project_client ); ?>" class="widefat" />
    </p>
    <p>
        <label for="project_location"><?php esc_html_e( 'Location', 'aqualuxe' ); ?></label><br>
        <input type="text" id="project_location" name="project_location" value="<?php echo esc_attr( $project_location ); ?>" class="widefat" />
    </p>
    <p>
        <label for="project_date"><?php esc_html_e( 'Completion Date', 'aqualuxe' ); ?></label><br>
        <input type="date" id="project_date" name="project_date" value="<?php echo esc_attr( $project_date ); ?>" class="widefat" />
    </p>
    <p>
        <label for="project_duration"><?php esc_html_e( 'Project Duration', 'aqualuxe' ); ?></label><br>
        <input type="text" id="project_duration" name="project_duration" value="<?php echo esc_attr( $project_duration ); ?>" class="widefat" />
        <span class="description"><?php esc_html_e( 'Enter the duration of the project (e.g., "2 weeks").', 'aqualuxe' ); ?></span>
    </p>
    <p>
        <label for="project_budget"><?php esc_html_e( 'Project Budget', 'aqualuxe' ); ?></label><br>
        <input type="text" id="project_budget" name="project_budget" value="<?php echo esc_attr( $project_budget ); ?>" class="widefat" />
        <span class="description"><?php esc_html_e( 'Enter the budget range (e.g., "$5,000 - $10,000").', 'aqualuxe' ); ?></span>
    </p>
    <p>
        <label for="project_gallery"><?php esc_html_e( 'Project Gallery', 'aqualuxe' ); ?></label><br>
        <input type="hidden" id="project_gallery" name="project_gallery" value="<?php echo esc_attr( $project_gallery ); ?>" class="widefat" />
        <button type="button" class="button" id="project_gallery_button"><?php esc_html_e( 'Select Images', 'aqualuxe' ); ?></button>
        <div id="project_gallery_preview" class="gallery-preview">
            <?php
            if ( ! empty( $project_gallery ) ) {
                $gallery_ids = explode( ',', $project_gallery );
                foreach ( $gallery_ids as $image_id ) {
                    echo wp_get_attachment_image( $image_id, 'thumbnail' );
                }
            }
            ?>
        </div>
    </p>
    <script>
        jQuery(document).ready(function($) {
            var frame;
            $('#project_gallery_button').on('click', function(e) {
                e.preventDefault();

                if (frame) {
                    frame.open();
                    return;
                }

                frame = wp.media({
                    title: '<?php esc_html_e( 'Select Project Gallery Images', 'aqualuxe' ); ?>',
                    button: {
                        text: '<?php esc_html_e( 'Add to Gallery', 'aqualuxe' ); ?>'
                    },
                    multiple: true
                });

                frame.on('select', function() {
                    var attachments = frame.state().get('selection').map(function(attachment) {
                        attachment = attachment.toJSON();
                        return attachment.id;
                    }).join(',');

                    $('#project_gallery').val(attachments);
                    
                    // Update preview
                    $('#project_gallery_preview').html('');
                    var ids = attachments.split(',');
                    ids.forEach(function(id) {
                        wp.media.attachment(id).fetch().then(function() {
                            $('#project_gallery_preview').append('<img src="' + wp.media.attachment(id).get('url') + '" class="thumbnail" />');
                        });
                    });
                });

                frame.open();
            });
        });
    </script>
    <style>
        .gallery-preview {
            display: flex;
            flex-wrap: wrap;
            margin-top: 10px;
        }
        .gallery-preview img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            margin: 0 10px 10px 0;
        }
    </style>
    <?php
}

/**
 * Save custom meta box data
 */
function aqualuxe_save_meta_box_data( $post_id ) {
    // Check if we're autosaving
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // Check the user's permissions
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    // Service details
    if ( isset( $_POST['aqualuxe_service_details_nonce'] ) && wp_verify_nonce( $_POST['aqualuxe_service_details_nonce'], 'aqualuxe_service_details' ) ) {
        if ( isset( $_POST['service_icon'] ) ) {
            update_post_meta( $post_id, '_service_icon', sanitize_text_field( $_POST['service_icon'] ) );
        }
        if ( isset( $_POST['service_price'] ) ) {
            update_post_meta( $post_id, '_service_price', sanitize_text_field( $_POST['service_price'] ) );
        }
        if ( isset( $_POST['service_duration'] ) ) {
            update_post_meta( $post_id, '_service_duration', sanitize_text_field( $_POST['service_duration'] ) );
        }
        if ( isset( $_POST['service_features'] ) ) {
            update_post_meta( $post_id, '_service_features', sanitize_textarea_field( $_POST['service_features'] ) );
        }
    }

    // Event details
    if ( isset( $_POST['aqualuxe_event_details_nonce'] ) && wp_verify_nonce( $_POST['aqualuxe_event_details_nonce'], 'aqualuxe_event_details' ) ) {
        if ( isset( $_POST['event_date'] ) ) {
            update_post_meta( $post_id, '_event_date', sanitize_text_field( $_POST['event_date'] ) );
        }
        if ( isset( $_POST['event_time'] ) ) {
            update_post_meta( $post_id, '_event_time', sanitize_text_field( $_POST['event_time'] ) );
        }
        if ( isset( $_POST['event_location'] ) ) {
            update_post_meta( $post_id, '_event_location', sanitize_text_field( $_POST['event_location'] ) );
        }
        if ( isset( $_POST['event_price'] ) ) {
            update_post_meta( $post_id, '_event_price', sanitize_text_field( $_POST['event_price'] ) );
        }
        if ( isset( $_POST['event_registration_url'] ) ) {
            update_post_meta( $post_id, '_event_registration_url', esc_url_raw( $_POST['event_registration_url'] ) );
        }
    }

    // Team member details
    if ( isset( $_POST['aqualuxe_team_member_details_nonce'] ) && wp_verify_nonce( $_POST['aqualuxe_team_member_details_nonce'], 'aqualuxe_team_member_details' ) ) {
        if ( isset( $_POST['team_member_position'] ) ) {
            update_post_meta( $post_id, '_team_member_position', sanitize_text_field( $_POST['team_member_position'] ) );
        }
        if ( isset( $_POST['team_member_email'] ) ) {
            update_post_meta( $post_id, '_team_member_email', sanitize_email( $_POST['team_member_email'] ) );
        }
        if ( isset( $_POST['team_member_phone'] ) ) {
            update_post_meta( $post_id, '_team_member_phone', sanitize_text_field( $_POST['team_member_phone'] ) );
        }
        if ( isset( $_POST['team_member_facebook'] ) ) {
            update_post_meta( $post_id, '_team_member_facebook', esc_url_raw( $_POST['team_member_facebook'] ) );
        }
        if ( isset( $_POST['team_member_twitter'] ) ) {
            update_post_meta( $post_id, '_team_member_twitter', esc_url_raw( $_POST['team_member_twitter'] ) );
        }
        if ( isset( $_POST['team_member_linkedin'] ) ) {
            update_post_meta( $post_id, '_team_member_linkedin', esc_url_raw( $_POST['team_member_linkedin'] ) );
        }
        if ( isset( $_POST['team_member_instagram'] ) ) {
            update_post_meta( $post_id, '_team_member_instagram', esc_url_raw( $_POST['team_member_instagram'] ) );
        }
    }

    // Testimonial details
    if ( isset( $_POST['aqualuxe_testimonial_details_nonce'] ) && wp_verify_nonce( $_POST['aqualuxe_testimonial_details_nonce'], 'aqualuxe_testimonial_details' ) ) {
        if ( isset( $_POST['testimonial_author'] ) ) {
            update_post_meta( $post_id, '_testimonial_author', sanitize_text_field( $_POST['testimonial_author'] ) );
        }
        if ( isset( $_POST['testimonial_position'] ) ) {
            update_post_meta( $post_id, '_testimonial_position', sanitize_text_field( $_POST['testimonial_position'] ) );
        }
        if ( isset( $_POST['testimonial_company'] ) ) {
            update_post_meta( $post_id, '_testimonial_company', sanitize_text_field( $_POST['testimonial_company'] ) );
        }
        if ( isset( $_POST['testimonial_rating'] ) ) {
            update_post_meta( $post_id, '_testimonial_rating', sanitize_text_field( $_POST['testimonial_rating'] ) );
        }
    }

    // Project details
    if ( isset( $_POST['aqualuxe_project_details_nonce'] ) && wp_verify_nonce( $_POST['aqualuxe_project_details_nonce'], 'aqualuxe_project_details' ) ) {
        if ( isset( $_POST['project_client'] ) ) {
            update_post_meta( $post_id, '_project_client', sanitize_text_field( $_POST['project_client'] ) );
        }
        if ( isset( $_POST['project_location'] ) ) {
            update_post_meta( $post_id, '_project_location', sanitize_text_field( $_POST['project_location'] ) );
        }
        if ( isset( $_POST['project_date'] ) ) {
            update_post_meta( $post_id, '_project_date', sanitize_text_field( $_POST['project_date'] ) );
        }
        if ( isset( $_POST['project_duration'] ) ) {
            update_post_meta( $post_id, '_project_duration', sanitize_text_field( $_POST['project_duration'] ) );
        }
        if ( isset( $_POST['project_budget'] ) ) {
            update_post_meta( $post_id, '_project_budget', sanitize_text_field( $_POST['project_budget'] ) );
        }
        if ( isset( $_POST['project_gallery'] ) ) {
            update_post_meta( $post_id, '_project_gallery', sanitize_text_field( $_POST['project_gallery'] ) );
        }
    }
}
add_action( 'save_post', 'aqualuxe_save_meta_box_data' );

/**
 * Add custom columns to post type admin screens
 */
function aqualuxe_add_custom_columns( $columns ) {
    $post_type = get_current_screen()->post_type;

    switch ( $post_type ) {
        case 'service':
            $columns['service_price'] = __( 'Price', 'aqualuxe' );
            $columns['service_duration'] = __( 'Duration', 'aqualuxe' );
            break;
        case 'event':
            $columns['event_date'] = __( 'Date', 'aqualuxe' );
            $columns['event_location'] = __( 'Location', 'aqualuxe' );
            break;
        case 'team_member':
            $columns['team_member_position'] = __( 'Position', 'aqualuxe' );
            break;
        case 'testimonial':
            $columns['testimonial_author'] = __( 'Author', 'aqualuxe' );
            $columns['testimonial_rating'] = __( 'Rating', 'aqualuxe' );
            break;
        case 'project':
            $columns['project_client'] = __( 'Client', 'aqualuxe' );
            $columns['project_date'] = __( 'Completion Date', 'aqualuxe' );
            break;
        case 'faq':
            $columns['faq_category'] = __( 'Category', 'aqualuxe' );
            break;
    }

    return $columns;
}
add_filter( 'manage_posts_columns', 'aqualuxe_add_custom_columns' );

/**
 * Display custom column content
 */
function aqualuxe_custom_column_content( $column, $post_id ) {
    switch ( $column ) {
        case 'service_price':
            echo esc_html( get_post_meta( $post_id, '_service_price', true ) );
            break;
        case 'service_duration':
            echo esc_html( get_post_meta( $post_id, '_service_duration', true ) );
            break;
        case 'event_date':
            $date = get_post_meta( $post_id, '_event_date', true );
            if ( $date ) {
                echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $date ) ) );
            }
            break;
        case 'event_location':
            echo esc_html( get_post_meta( $post_id, '_event_location', true ) );
            break;
        case 'team_member_position':
            echo esc_html( get_post_meta( $post_id, '_team_member_position', true ) );
            break;
        case 'testimonial_author':
            echo esc_html( get_post_meta( $post_id, '_testimonial_author', true ) );
            break;
        case 'testimonial_rating':
            $rating = get_post_meta( $post_id, '_testimonial_rating', true );
            if ( $rating ) {
                echo esc_html( $rating ) . ' ';
                $stars = floor( $rating );
                $half = $rating - $stars >= 0.5;
                
                for ( $i = 1; $i <= 5; $i++ ) {
                    if ( $i <= $stars ) {
                        echo '★';
                    } elseif ( $half && $i == $stars + 1 ) {
                        echo '½';
                    } else {
                        echo '☆';
                    }
                }
            }
            break;
        case 'project_client':
            echo esc_html( get_post_meta( $post_id, '_project_client', true ) );
            break;
        case 'project_date':
            $date = get_post_meta( $post_id, '_project_date', true );
            if ( $date ) {
                echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $date ) ) );
            }
            break;
        case 'faq_category':
            $terms = get_the_terms( $post_id, 'faq_category' );
            if ( $terms && ! is_wp_error( $terms ) ) {
                $term_names = array();
                foreach ( $terms as $term ) {
                    $term_names[] = $term->name;
                }
                echo esc_html( implode( ', ', $term_names ) );
            }
            break;
    }
}
add_action( 'manage_posts_custom_column', 'aqualuxe_custom_column_content', 10, 2 );

/**
 * Make custom columns sortable
 */
function aqualuxe_sortable_columns( $columns ) {
    $post_type = get_current_screen()->post_type;

    switch ( $post_type ) {
        case 'service':
            $columns['service_price'] = 'service_price';
            break;
        case 'event':
            $columns['event_date'] = 'event_date';
            break;
        case 'testimonial':
            $columns['testimonial_rating'] = 'testimonial_rating';
            break;
        case 'project':
            $columns['project_date'] = 'project_date';
            break;
    }

    return $columns;
}
add_filter( 'manage_edit-post_sortable_columns', 'aqualuxe_sortable_columns' );

/**
 * Add custom post type sorting
 */
function aqualuxe_custom_orderby( $query ) {
    if ( ! is_admin() || ! $query->is_main_query() ) {
        return;
    }

    $orderby = $query->get( 'orderby' );

    switch ( $orderby ) {
        case 'service_price':
            $query->set( 'meta_key', '_service_price' );
            $query->set( 'orderby', 'meta_value_num' );
            break;
        case 'event_date':
            $query->set( 'meta_key', '_event_date' );
            $query->set( 'orderby', 'meta_value' );
            break;
        case 'testimonial_rating':
            $query->set( 'meta_key', '_testimonial_rating' );
            $query->set( 'orderby', 'meta_value_num' );
            break;
        case 'project_date':
            $query->set( 'meta_key', '_project_date' );
            $query->set( 'orderby', 'meta_value' );
            break;
    }
}
add_action( 'pre_get_posts', 'aqualuxe_custom_orderby' );

/**
 * Add custom post type archive templates
 */
function aqualuxe_custom_post_type_templates( $template ) {
    if ( is_post_type_archive( 'service' ) ) {
        $new_template = locate_template( array( 'archive-service.php' ) );
        if ( '' != $new_template ) {
            return $new_template;
        }
    }

    if ( is_post_type_archive( 'event' ) ) {
        $new_template = locate_template( array( 'archive-event.php' ) );
        if ( '' != $new_template ) {
            return $new_template;
        }
    }

    if ( is_post_type_archive( 'team_member' ) ) {
        $new_template = locate_template( array( 'archive-team-member.php' ) );
        if ( '' != $new_template ) {
            return $new_template;
        }
    }

    if ( is_post_type_archive( 'testimonial' ) ) {
        $new_template = locate_template( array( 'archive-testimonial.php' ) );
        if ( '' != $new_template ) {
            return $new_template;
        }
    }

    if ( is_post_type_archive( 'project' ) ) {
        $new_template = locate_template( array( 'archive-project.php' ) );
        if ( '' != $new_template ) {
            return $new_template;
        }
    }

    if ( is_post_type_archive( 'faq' ) ) {
        $new_template = locate_template( array( 'archive-faq.php' ) );
        if ( '' != $new_template ) {
            return $new_template;
        }
    }

    return $template;
}
add_filter( 'template_include', 'aqualuxe_custom_post_type_templates' );

/**
 * Add custom post type single templates
 */
function aqualuxe_custom_post_type_single_templates( $template ) {
    if ( is_singular( 'service' ) ) {
        $new_template = locate_template( array( 'single-service.php' ) );
        if ( '' != $new_template ) {
            return $new_template;
        }
    }

    if ( is_singular( 'event' ) ) {
        $new_template = locate_template( array( 'single-event.php' ) );
        if ( '' != $new_template ) {
            return $new_template;
        }
    }

    if ( is_singular( 'team_member' ) ) {
        $new_template = locate_template( array( 'single-team-member.php' ) );
        if ( '' != $new_template ) {
            return $new_template;
        }
    }

    if ( is_singular( 'testimonial' ) ) {
        $new_template = locate_template( array( 'single-testimonial.php' ) );
        if ( '' != $new_template ) {
            return $new_template;
        }
    }

    if ( is_singular( 'project' ) ) {
        $new_template = locate_template( array( 'single-project.php' ) );
        if ( '' != $new_template ) {
            return $new_template;
        }
    }

    if ( is_singular( 'faq' ) ) {
        $new_template = locate_template( array( 'single-faq.php' ) );
        if ( '' != $new_template ) {
            return $new_template;
        }
    }

    return $template;
}
add_filter( 'single_template', 'aqualuxe_custom_post_type_single_templates' );

/**
 * Add custom post type shortcodes
 */
function aqualuxe_services_shortcode( $atts ) {
    $atts = shortcode_atts(
        array(
            'count'      => 3,
            'orderby'    => 'date',
            'order'      => 'DESC',
            'category'   => '',
            'layout'     => 'grid',
        ),
        $atts,
        'aqualuxe_services'
    );

    $args = array(
        'post_type'      => 'service',
        'posts_per_page' => $atts['count'],
        'orderby'        => $atts['orderby'],
        'order'          => $atts['order'],
    );

    if ( ! empty( $atts['category'] ) ) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'service_category',
                'field'    => 'slug',
                'terms'    => explode( ',', $atts['category'] ),
            ),
        );
    }

    $services = new WP_Query( $args );

    ob_start();

    if ( $services->have_posts() ) {
        echo '<div class="aqualuxe-services ' . esc_attr( $atts['layout'] ) . '-layout">';
        
        while ( $services->have_posts() ) {
            $services->the_post();
            
            $service_icon = get_post_meta( get_the_ID(), '_service_icon', true );
            $service_price = get_post_meta( get_the_ID(), '_service_price', true );
            $service_duration = get_post_meta( get_the_ID(), '_service_duration', true );
            
            echo '<div class="service-item">';
            
            if ( has_post_thumbnail() ) {
                echo '<div class="service-image">';
                echo '<a href="' . esc_url( get_permalink() ) . '">';
                the_post_thumbnail( 'medium' );
                echo '</a>';
                echo '</div>';
            } elseif ( ! empty( $service_icon ) ) {
                echo '<div class="service-icon">';
                if ( filter_var( $service_icon, FILTER_VALIDATE_URL ) ) {
                    echo '<img src="' . esc_url( $service_icon ) . '" alt="' . esc_attr( get_the_title() ) . '" />';
                } else {
                    echo '<i class="' . esc_attr( $service_icon ) . '"></i>';
                }
                echo '</div>';
            }
            
            echo '<div class="service-content">';
            echo '<h3 class="service-title"><a href="' . esc_url( get_permalink() ) . '">' . esc_html( get_the_title() ) . '</a></h3>';
            
            if ( ! empty( $service_price ) || ! empty( $service_duration ) ) {
                echo '<div class="service-meta">';
                
                if ( ! empty( $service_price ) ) {
                    echo '<span class="service-price">' . esc_html( $service_price ) . '</span>';
                }
                
                if ( ! empty( $service_duration ) ) {
                    echo '<span class="service-duration">' . esc_html( $service_duration ) . '</span>';
                }
                
                echo '</div>';
            }
            
            echo '<div class="service-excerpt">' . get_the_excerpt() . '</div>';
            echo '<a href="' . esc_url( get_permalink() ) . '" class="service-link">' . esc_html__( 'Learn More', 'aqualuxe' ) . '</a>';
            echo '</div>';
            
            echo '</div>';
        }
        
        echo '</div>';
    }

    wp_reset_postdata();

    return ob_get_clean();
}
add_shortcode( 'aqualuxe_services', 'aqualuxe_services_shortcode' );

function aqualuxe_events_shortcode( $atts ) {
    $atts = shortcode_atts(
        array(
            'count'      => 3,
            'orderby'    => 'meta_value',
            'meta_key'   => '_event_date',
            'order'      => 'ASC',
            'category'   => '',
            'layout'     => 'list',
            'upcoming'   => 'yes',
        ),
        $atts,
        'aqualuxe_events'
    );

    $args = array(
        'post_type'      => 'event',
        'posts_per_page' => $atts['count'],
        'orderby'        => $atts['orderby'],
        'meta_key'       => $atts['meta_key'],
        'order'          => $atts['order'],
    );

    if ( ! empty( $atts['category'] ) ) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'event_category',
                'field'    => 'slug',
                'terms'    => explode( ',', $atts['category'] ),
            ),
        );
    }

    // Filter for upcoming events only
    if ( 'yes' === $atts['upcoming'] ) {
        $args['meta_query'] = array(
            array(
                'key'     => '_event_date',
                'value'   => date( 'Y-m-d' ),
                'compare' => '>=',
                'type'    => 'DATE',
            ),
        );
    }

    $events = new WP_Query( $args );

    ob_start();

    if ( $events->have_posts() ) {
        echo '<div class="aqualuxe-events ' . esc_attr( $atts['layout'] ) . '-layout">';
        
        while ( $events->have_posts() ) {
            $events->the_post();
            
            $event_date = get_post_meta( get_the_ID(), '_event_date', true );
            $event_time = get_post_meta( get_the_ID(), '_event_time', true );
            $event_location = get_post_meta( get_the_ID(), '_event_location', true );
            $event_price = get_post_meta( get_the_ID(), '_event_price', true );
            
            echo '<div class="event-item">';
            
            if ( has_post_thumbnail() ) {
                echo '<div class="event-image">';
                echo '<a href="' . esc_url( get_permalink() ) . '">';
                the_post_thumbnail( 'medium' );
                echo '</a>';
                echo '</div>';
            }
            
            echo '<div class="event-content">';
            
            if ( ! empty( $event_date ) ) {
                echo '<div class="event-date">';
                echo '<span class="event-day">' . esc_html( date_i18n( 'j', strtotime( $event_date ) ) ) . '</span>';
                echo '<span class="event-month">' . esc_html( date_i18n( 'M', strtotime( $event_date ) ) ) . '</span>';
                echo '</div>';
            }
            
            echo '<h3 class="event-title"><a href="' . esc_url( get_permalink() ) . '">' . esc_html( get_the_title() ) . '</a></h3>';
            
            echo '<div class="event-meta">';
            
            if ( ! empty( $event_time ) ) {
                echo '<span class="event-time"><i class="far fa-clock"></i> ' . esc_html( $event_time ) . '</span>';
            }
            
            if ( ! empty( $event_location ) ) {
                echo '<span class="event-location"><i class="fas fa-map-marker-alt"></i> ' . esc_html( $event_location ) . '</span>';
            }
            
            if ( ! empty( $event_price ) ) {
                echo '<span class="event-price"><i class="fas fa-ticket-alt"></i> ' . esc_html( $event_price ) . '</span>';
            }
            
            echo '</div>';
            
            echo '<div class="event-excerpt">' . get_the_excerpt() . '</div>';
            echo '<a href="' . esc_url( get_permalink() ) . '" class="event-link">' . esc_html__( 'View Details', 'aqualuxe' ) . '</a>';
            echo '</div>';
            
            echo '</div>';
        }
        
        echo '</div>';
    }

    wp_reset_postdata();

    return ob_get_clean();
}
add_shortcode( 'aqualuxe_events', 'aqualuxe_events_shortcode' );

function aqualuxe_team_shortcode( $atts ) {
    $atts = shortcode_atts(
        array(
            'count'      => -1,
            'orderby'    => 'menu_order',
            'order'      => 'ASC',
            'department' => '',
            'layout'     => 'grid',
        ),
        $atts,
        'aqualuxe_team'
    );

    $args = array(
        'post_type'      => 'team_member',
        'posts_per_page' => $atts['count'],
        'orderby'        => $atts['orderby'],
        'order'          => $atts['order'],
    );

    if ( ! empty( $atts['department'] ) ) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'department',
                'field'    => 'slug',
                'terms'    => explode( ',', $atts['department'] ),
            ),
        );
    }

    $team = new WP_Query( $args );

    ob_start();

    if ( $team->have_posts() ) {
        echo '<div class="aqualuxe-team ' . esc_attr( $atts['layout'] ) . '-layout">';
        
        while ( $team->have_posts() ) {
            $team->the_post();
            
            $position = get_post_meta( get_the_ID(), '_team_member_position', true );
            $email = get_post_meta( get_the_ID(), '_team_member_email', true );
            $facebook = get_post_meta( get_the_ID(), '_team_member_facebook', true );
            $twitter = get_post_meta( get_the_ID(), '_team_member_twitter', true );
            $linkedin = get_post_meta( get_the_ID(), '_team_member_linkedin', true );
            $instagram = get_post_meta( get_the_ID(), '_team_member_instagram', true );
            
            echo '<div class="team-member">';
            
            if ( has_post_thumbnail() ) {
                echo '<div class="team-member-image">';
                echo '<a href="' . esc_url( get_permalink() ) . '">';
                the_post_thumbnail( 'medium' );
                echo '</a>';
                echo '</div>';
            }
            
            echo '<div class="team-member-content">';
            echo '<h3 class="team-member-name"><a href="' . esc_url( get_permalink() ) . '">' . esc_html( get_the_title() ) . '</a></h3>';
            
            if ( ! empty( $position ) ) {
                echo '<div class="team-member-position">' . esc_html( $position ) . '</div>';
            }
            
            echo '<div class="team-member-excerpt">' . get_the_excerpt() . '</div>';
            
            echo '<div class="team-member-social">';
            
            if ( ! empty( $email ) ) {
                echo '<a href="mailto:' . esc_attr( $email ) . '" class="team-member-email"><i class="fas fa-envelope"></i></a>';
            }
            
            if ( ! empty( $facebook ) ) {
                echo '<a href="' . esc_url( $facebook ) . '" class="team-member-facebook" target="_blank"><i class="fab fa-facebook-f"></i></a>';
            }
            
            if ( ! empty( $twitter ) ) {
                echo '<a href="' . esc_url( $twitter ) . '" class="team-member-twitter" target="_blank"><i class="fab fa-twitter"></i></a>';
            }
            
            if ( ! empty( $linkedin ) ) {
                echo '<a href="' . esc_url( $linkedin ) . '" class="team-member-linkedin" target="_blank"><i class="fab fa-linkedin-in"></i></a>';
            }
            
            if ( ! empty( $instagram ) ) {
                echo '<a href="' . esc_url( $instagram ) . '" class="team-member-instagram" target="_blank"><i class="fab fa-instagram"></i></a>';
            }
            
            echo '</div>';
            
            echo '</div>';
            echo '</div>';
        }
        
        echo '</div>';
    }

    wp_reset_postdata();

    return ob_get_clean();
}
add_shortcode( 'aqualuxe_team', 'aqualuxe_team_shortcode' );

function aqualuxe_testimonials_shortcode( $atts ) {
    $atts = shortcode_atts(
        array(
            'count'      => 3,
            'orderby'    => 'rand',
            'order'      => 'DESC',
            'category'   => '',
            'layout'     => 'slider',
        ),
        $atts,
        'aqualuxe_testimonials'
    );

    $args = array(
        'post_type'      => 'testimonial',
        'posts_per_page' => $atts['count'],
        'orderby'        => $atts['orderby'],
        'order'          => $atts['order'],
    );

    if ( ! empty( $atts['category'] ) ) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'testimonial_category',
                'field'    => 'slug',
                'terms'    => explode( ',', $atts['category'] ),
            ),
        );
    }

    $testimonials = new WP_Query( $args );

    ob_start();

    if ( $testimonials->have_posts() ) {
        $slider_id = 'testimonial-slider-' . rand( 1000, 9999 );
        
        echo '<div class="aqualuxe-testimonials ' . esc_attr( $atts['layout'] ) . '-layout" id="' . esc_attr( $slider_id ) . '">';
        
        if ( 'slider' === $atts['layout'] ) {
            echo '<div class="testimonial-slider">';
        }
        
        while ( $testimonials->have_posts() ) {
            $testimonials->the_post();
            
            $author = get_post_meta( get_the_ID(), '_testimonial_author', true );
            $position = get_post_meta( get_the_ID(), '_testimonial_position', true );
            $company = get_post_meta( get_the_ID(), '_testimonial_company', true );
            $rating = get_post_meta( get_the_ID(), '_testimonial_rating', true );
            
            echo '<div class="testimonial-item">';
            
            if ( ! empty( $rating ) ) {
                echo '<div class="testimonial-rating">';
                $stars = floor( $rating );
                $half = $rating - $stars >= 0.5;
                
                for ( $i = 1; $i <= 5; $i++ ) {
                    if ( $i <= $stars ) {
                        echo '<i class="fas fa-star"></i>';
                    } elseif ( $half && $i == $stars + 1 ) {
                        echo '<i class="fas fa-star-half-alt"></i>';
                    } else {
                        echo '<i class="far fa-star"></i>';
                    }
                }
                echo '</div>';
            }
            
            echo '<div class="testimonial-content">' . get_the_content() . '</div>';
            
            echo '<div class="testimonial-author">';
            
            if ( has_post_thumbnail() ) {
                echo '<div class="testimonial-author-image">';
                the_post_thumbnail( 'thumbnail' );
                echo '</div>';
            }
            
            echo '<div class="testimonial-author-info">';
            
            if ( ! empty( $author ) ) {
                echo '<div class="testimonial-author-name">' . esc_html( $author ) . '</div>';
            } else {
                echo '<div class="testimonial-author-name">' . esc_html( get_the_title() ) . '</div>';
            }
            
            if ( ! empty( $position ) || ! empty( $company ) ) {
                echo '<div class="testimonial-author-title">';
                
                if ( ! empty( $position ) ) {
                    echo esc_html( $position );
                }
                
                if ( ! empty( $position ) && ! empty( $company ) ) {
                    echo ', ';
                }
                
                if ( ! empty( $company ) ) {
                    echo esc_html( $company );
                }
                
                echo '</div>';
            }
            
            echo '</div>';
            echo '</div>';
            
            echo '</div>';
        }
        
        if ( 'slider' === $atts['layout'] ) {
            echo '</div>';
            echo '<div class="testimonial-slider-nav">';
            echo '<button class="testimonial-prev"><i class="fas fa-chevron-left"></i></button>';
            echo '<button class="testimonial-next"><i class="fas fa-chevron-right"></i></button>';
            echo '</div>';
        }
        
        echo '</div>';
        
        if ( 'slider' === $atts['layout'] ) {
            ?>
            <script>
                jQuery(document).ready(function($) {
                    $('#<?php echo esc_js( $slider_id ); ?> .testimonial-slider').slick({
                        dots: true,
                        arrows: true,
                        infinite: true,
                        speed: 500,
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        adaptiveHeight: true,
                        prevArrow: $('#<?php echo esc_js( $slider_id ); ?> .testimonial-prev'),
                        nextArrow: $('#<?php echo esc_js( $slider_id ); ?> .testimonial-next')
                    });
                });
            </script>
            <?php
        }
    }

    wp_reset_postdata();

    return ob_get_clean();
}
add_shortcode( 'aqualuxe_testimonials', 'aqualuxe_testimonials_shortcode' );

function aqualuxe_projects_shortcode( $atts ) {
    $atts = shortcode_atts(
        array(
            'count'      => 6,
            'orderby'    => 'date',
            'order'      => 'DESC',
            'category'   => '',
            'layout'     => 'grid',
            'columns'    => 3,
            'filter'     => 'yes',
        ),
        $atts,
        'aqualuxe_projects'
    );

    $args = array(
        'post_type'      => 'project',
        'posts_per_page' => $atts['count'],
        'orderby'        => $atts['orderby'],
        'order'          => $atts['order'],
    );

    $tax_query = array();

    if ( ! empty( $atts['category'] ) ) {
        $tax_query[] = array(
            'taxonomy' => 'project_category',
            'field'    => 'slug',
            'terms'    => explode( ',', $atts['category'] ),
        );
    }

    if ( ! empty( $tax_query ) ) {
        $args['tax_query'] = $tax_query;
    }

    $projects = new WP_Query( $args );

    ob_start();

    if ( $projects->have_posts() ) {
        $filter_id = 'project-filter-' . rand( 1000, 9999 );
        $grid_id = 'project-grid-' . rand( 1000, 9999 );
        
        echo '<div class="aqualuxe-projects ' . esc_attr( $atts['layout'] ) . '-layout columns-' . esc_attr( $atts['columns'] ) . '">';
        
        // Filter
        if ( 'yes' === $atts['filter'] && empty( $atts['category'] ) ) {
            $terms = get_terms( array(
                'taxonomy'   => 'project_category',
                'hide_empty' => true,
            ) );
            
            if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
                echo '<div class="project-filter" id="' . esc_attr( $filter_id ) . '">';
                echo '<button class="filter-button active" data-filter="*">' . esc_html__( 'All', 'aqualuxe' ) . '</button>';
                
                foreach ( $terms as $term ) {
                    echo '<button class="filter-button" data-filter=".' . esc_attr( $term->slug ) . '">' . esc_html( $term->name ) . '</button>';
                }
                
                echo '</div>';
            }
        }
        
        echo '<div class="project-grid" id="' . esc_attr( $grid_id ) . '">';
        
        while ( $projects->have_posts() ) {
            $projects->the_post();
            
            $client = get_post_meta( get_the_ID(), '_project_client', true );
            $location = get_post_meta( get_the_ID(), '_project_location', true );
            
            $terms = get_the_terms( get_the_ID(), 'project_category' );
            $term_classes = '';
            
            if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
                $term_slugs = wp_list_pluck( $terms, 'slug' );
                $term_classes = implode( ' ', $term_slugs );
            }
            
            echo '<div class="project-item ' . esc_attr( $term_classes ) . '">';
            
            if ( has_post_thumbnail() ) {
                echo '<div class="project-image">';
                echo '<a href="' . esc_url( get_permalink() ) . '">';
                the_post_thumbnail( 'large' );
                echo '</a>';
                echo '<div class="project-overlay">';
                echo '<div class="project-overlay-content">';
                echo '<h3 class="project-title">' . esc_html( get_the_title() ) . '</h3>';
                
                if ( ! empty( $client ) || ! empty( $location ) ) {
                    echo '<div class="project-meta">';
                    
                    if ( ! empty( $client ) ) {
                        echo '<span class="project-client">' . esc_html( $client ) . '</span>';
                    }
                    
                    if ( ! empty( $client ) && ! empty( $location ) ) {
                        echo ' | ';
                    }
                    
                    if ( ! empty( $location ) ) {
                        echo '<span class="project-location">' . esc_html( $location ) . '</span>';
                    }
                    
                    echo '</div>';
                }
                
                echo '<a href="' . esc_url( get_permalink() ) . '" class="project-link">' . esc_html__( 'View Project', 'aqualuxe' ) . '</a>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
            
            echo '</div>';
        }
        
        echo '</div>';
        echo '</div>';
        
        if ( 'yes' === $atts['filter'] && empty( $atts['category'] ) ) {
            ?>
            <script>
                jQuery(document).ready(function($) {
                    // Initialize Isotope
                    var $grid = $('#<?php echo esc_js( $grid_id ); ?>').isotope({
                        itemSelector: '.project-item',
                        layoutMode: 'fitRows'
                    });
                    
                    // Filter items on button click
                    $('#<?php echo esc_js( $filter_id ); ?>').on('click', 'button', function() {
                        var filterValue = $(this).attr('data-filter');
                        $grid.isotope({ filter: filterValue });
                        
                        // Toggle active class
                        $('#<?php echo esc_js( $filter_id ); ?> button').removeClass('active');
                        $(this).addClass('active');
                    });
                });
            </script>
            <?php
        }
    }

    wp_reset_postdata();

    return ob_get_clean();
}
add_shortcode( 'aqualuxe_projects', 'aqualuxe_projects_shortcode' );

function aqualuxe_faqs_shortcode( $atts ) {
    $atts = shortcode_atts(
        array(
            'count'      => -1,
            'orderby'    => 'menu_order',
            'order'      => 'ASC',
            'category'   => '',
            'layout'     => 'accordion',
        ),
        $atts,
        'aqualuxe_faqs'
    );

    $args = array(
        'post_type'      => 'faq',
        'posts_per_page' => $atts['count'],
        'orderby'        => $atts['orderby'],
        'order'          => $atts['order'],
    );

    if ( ! empty( $atts['category'] ) ) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'faq_category',
                'field'    => 'slug',
                'terms'    => explode( ',', $atts['category'] ),
            ),
        );
    }

    $faqs = new WP_Query( $args );

    ob_start();

    if ( $faqs->have_posts() ) {
        $accordion_id = 'faq-accordion-' . rand( 1000, 9999 );
        
        echo '<div class="aqualuxe-faqs ' . esc_attr( $atts['layout'] ) . '-layout" id="' . esc_attr( $accordion_id ) . '">';
        
        while ( $faqs->have_posts() ) {
            $faqs->the_post();
            
            $faq_id = 'faq-' . get_the_ID();
            
            echo '<div class="faq-item">';
            echo '<div class="faq-question" id="' . esc_attr( $faq_id . '-question' ) . '">';
            echo '<h3>' . esc_html( get_the_title() ) . '</h3>';
            echo '<span class="faq-icon"></span>';
            echo '</div>';
            echo '<div class="faq-answer" id="' . esc_attr( $faq_id . '-answer' ) . '">';
            the_content();
            echo '</div>';
            echo '</div>';
        }
        
        echo '</div>';
        
        if ( 'accordion' === $atts['layout'] ) {
            ?>
            <script>
                jQuery(document).ready(function($) {
                    $('#<?php echo esc_js( $accordion_id ); ?> .faq-question').on('click', function() {
                        $(this).toggleClass('active');
                        $(this).next('.faq-answer').slideToggle();
                    });
                    
                    // Open first FAQ by default
                    $('#<?php echo esc_js( $accordion_id ); ?> .faq-question:first').addClass('active');
                    $('#<?php echo esc_js( $accordion_id ); ?> .faq-answer:first').show();
                });
            </script>
            <?php
        }
    }

    wp_reset_postdata();

    return ob_get_clean();
}
add_shortcode( 'aqualuxe_faqs', 'aqualuxe_faqs_shortcode' );

/**
 * Flush rewrite rules on theme activation
 */
function aqualuxe_rewrite_flush() {
    aqualuxe_register_post_types();
    flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'aqualuxe_rewrite_flush' );