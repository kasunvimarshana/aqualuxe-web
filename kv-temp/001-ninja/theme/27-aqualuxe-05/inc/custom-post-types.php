<?php
/**
 * Custom Post Types for AquaLuxe Theme
 *
 * @package AquaLuxe
 */

/**
 * Register custom post types
 */
function aqualuxe_register_post_types() {
    // Services Custom Post Type
    $services_labels = array(
        'name'                  => _x( 'Services', 'Post type general name', 'aqualuxe' ),
        'singular_name'         => _x( 'Service', 'Post type singular name', 'aqualuxe' ),
        'menu_name'             => _x( 'Services', 'Admin Menu text', 'aqualuxe' ),
        'name_admin_bar'        => _x( 'Service', 'Add New on Toolbar', 'aqualuxe' ),
        'add_new'               => __( 'Add New', 'aqualuxe' ),
        'add_new_item'          => __( 'Add New Service', 'aqualuxe' ),
        'new_item'              => __( 'New Service', 'aqualuxe' ),
        'edit_item'             => __( 'Edit Service', 'aqualuxe' ),
        'view_item'             => __( 'View Service', 'aqualuxe' ),
        'all_items'             => __( 'All Services', 'aqualuxe' ),
        'search_items'          => __( 'Search Services', 'aqualuxe' ),
        'parent_item_colon'     => __( 'Parent Services:', 'aqualuxe' ),
        'not_found'             => __( 'No services found.', 'aqualuxe' ),
        'not_found_in_trash'    => __( 'No services found in Trash.', 'aqualuxe' ),
        'featured_image'        => _x( 'Service Cover Image', 'Overrides the "Featured Image" phrase', 'aqualuxe' ),
        'set_featured_image'    => _x( 'Set cover image', 'Overrides the "Set featured image" phrase', 'aqualuxe' ),
        'remove_featured_image' => _x( 'Remove cover image', 'Overrides the "Remove featured image" phrase', 'aqualuxe' ),
        'use_featured_image'    => _x( 'Use as cover image', 'Overrides the "Use as featured image" phrase', 'aqualuxe' ),
        'archives'              => _x( 'Service archives', 'The post type archive label used in nav menus', 'aqualuxe' ),
        'insert_into_item'      => _x( 'Insert into service', 'Overrides the "Insert into post" phrase', 'aqualuxe' ),
        'uploaded_to_this_item' => _x( 'Uploaded to this service', 'Overrides the "Uploaded to this post" phrase', 'aqualuxe' ),
        'filter_items_list'     => _x( 'Filter services list', 'Screen reader text for the filter links', 'aqualuxe' ),
        'items_list_navigation' => _x( 'Services list navigation', 'Screen reader text for the pagination', 'aqualuxe' ),
        'items_list'            => _x( 'Services list', 'Screen reader text for the items list', 'aqualuxe' ),
    );

    $services_args = array(
        'labels'             => $services_labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'services' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 20,
        'menu_icon'          => 'dashicons-admin-tools',
        'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
        'show_in_rest'       => true,
    );

    register_post_type( 'service', $services_args );

    // Events Custom Post Type
    $events_labels = array(
        'name'                  => _x( 'Events', 'Post type general name', 'aqualuxe' ),
        'singular_name'         => _x( 'Event', 'Post type singular name', 'aqualuxe' ),
        'menu_name'             => _x( 'Events', 'Admin Menu text', 'aqualuxe' ),
        'name_admin_bar'        => _x( 'Event', 'Add New on Toolbar', 'aqualuxe' ),
        'add_new'               => __( 'Add New', 'aqualuxe' ),
        'add_new_item'          => __( 'Add New Event', 'aqualuxe' ),
        'new_item'              => __( 'New Event', 'aqualuxe' ),
        'edit_item'             => __( 'Edit Event', 'aqualuxe' ),
        'view_item'             => __( 'View Event', 'aqualuxe' ),
        'all_items'             => __( 'All Events', 'aqualuxe' ),
        'search_items'          => __( 'Search Events', 'aqualuxe' ),
        'parent_item_colon'     => __( 'Parent Events:', 'aqualuxe' ),
        'not_found'             => __( 'No events found.', 'aqualuxe' ),
        'not_found_in_trash'    => __( 'No events found in Trash.', 'aqualuxe' ),
        'featured_image'        => _x( 'Event Cover Image', 'Overrides the "Featured Image" phrase', 'aqualuxe' ),
        'set_featured_image'    => _x( 'Set cover image', 'Overrides the "Set featured image" phrase', 'aqualuxe' ),
        'remove_featured_image' => _x( 'Remove cover image', 'Overrides the "Remove featured image" phrase', 'aqualuxe' ),
        'use_featured_image'    => _x( 'Use as cover image', 'Overrides the "Use as featured image" phrase', 'aqualuxe' ),
        'archives'              => _x( 'Event archives', 'The post type archive label used in nav menus', 'aqualuxe' ),
        'insert_into_item'      => _x( 'Insert into event', 'Overrides the "Insert into post" phrase', 'aqualuxe' ),
        'uploaded_to_this_item' => _x( 'Uploaded to this event', 'Overrides the "Uploaded to this post" phrase', 'aqualuxe' ),
        'filter_items_list'     => _x( 'Filter events list', 'Screen reader text for the filter links', 'aqualuxe' ),
        'items_list_navigation' => _x( 'Events list navigation', 'Screen reader text for the pagination', 'aqualuxe' ),
        'items_list'            => _x( 'Events list', 'Screen reader text for the items list', 'aqualuxe' ),
    );

    $events_args = array(
        'labels'             => $events_labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'events' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 21,
        'menu_icon'          => 'dashicons-calendar-alt',
        'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
        'show_in_rest'       => true,
    );

    register_post_type( 'event', $events_args );

    // Testimonials Custom Post Type
    $testimonials_labels = array(
        'name'                  => _x( 'Testimonials', 'Post type general name', 'aqualuxe' ),
        'singular_name'         => _x( 'Testimonial', 'Post type singular name', 'aqualuxe' ),
        'menu_name'             => _x( 'Testimonials', 'Admin Menu text', 'aqualuxe' ),
        'name_admin_bar'        => _x( 'Testimonial', 'Add New on Toolbar', 'aqualuxe' ),
        'add_new'               => __( 'Add New', 'aqualuxe' ),
        'add_new_item'          => __( 'Add New Testimonial', 'aqualuxe' ),
        'new_item'              => __( 'New Testimonial', 'aqualuxe' ),
        'edit_item'             => __( 'Edit Testimonial', 'aqualuxe' ),
        'view_item'             => __( 'View Testimonial', 'aqualuxe' ),
        'all_items'             => __( 'All Testimonials', 'aqualuxe' ),
        'search_items'          => __( 'Search Testimonials', 'aqualuxe' ),
        'parent_item_colon'     => __( 'Parent Testimonials:', 'aqualuxe' ),
        'not_found'             => __( 'No testimonials found.', 'aqualuxe' ),
        'not_found_in_trash'    => __( 'No testimonials found in Trash.', 'aqualuxe' ),
        'featured_image'        => _x( 'Client Image', 'Overrides the "Featured Image" phrase', 'aqualuxe' ),
        'set_featured_image'    => _x( 'Set client image', 'Overrides the "Set featured image" phrase', 'aqualuxe' ),
        'remove_featured_image' => _x( 'Remove client image', 'Overrides the "Remove featured image" phrase', 'aqualuxe' ),
        'use_featured_image'    => _x( 'Use as client image', 'Overrides the "Use as featured image" phrase', 'aqualuxe' ),
        'archives'              => _x( 'Testimonial archives', 'The post type archive label used in nav menus', 'aqualuxe' ),
        'insert_into_item'      => _x( 'Insert into testimonial', 'Overrides the "Insert into post" phrase', 'aqualuxe' ),
        'uploaded_to_this_item' => _x( 'Uploaded to this testimonial', 'Overrides the "Uploaded to this post" phrase', 'aqualuxe' ),
        'filter_items_list'     => _x( 'Filter testimonials list', 'Screen reader text for the filter links', 'aqualuxe' ),
        'items_list_navigation' => _x( 'Testimonials list navigation', 'Screen reader text for the pagination', 'aqualuxe' ),
        'items_list'            => _x( 'Testimonials list', 'Screen reader text for the items list', 'aqualuxe' ),
    );

    $testimonials_args = array(
        'labels'             => $testimonials_labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'testimonials' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 22,
        'menu_icon'          => 'dashicons-format-quote',
        'supports'           => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
        'show_in_rest'       => true,
    );

    register_post_type( 'testimonial', $testimonials_args );

    // Team Members Custom Post Type
    $team_labels = array(
        'name'                  => _x( 'Team Members', 'Post type general name', 'aqualuxe' ),
        'singular_name'         => _x( 'Team Member', 'Post type singular name', 'aqualuxe' ),
        'menu_name'             => _x( 'Team', 'Admin Menu text', 'aqualuxe' ),
        'name_admin_bar'        => _x( 'Team Member', 'Add New on Toolbar', 'aqualuxe' ),
        'add_new'               => __( 'Add New', 'aqualuxe' ),
        'add_new_item'          => __( 'Add New Team Member', 'aqualuxe' ),
        'new_item'              => __( 'New Team Member', 'aqualuxe' ),
        'edit_item'             => __( 'Edit Team Member', 'aqualuxe' ),
        'view_item'             => __( 'View Team Member', 'aqualuxe' ),
        'all_items'             => __( 'All Team Members', 'aqualuxe' ),
        'search_items'          => __( 'Search Team Members', 'aqualuxe' ),
        'parent_item_colon'     => __( 'Parent Team Members:', 'aqualuxe' ),
        'not_found'             => __( 'No team members found.', 'aqualuxe' ),
        'not_found_in_trash'    => __( 'No team members found in Trash.', 'aqualuxe' ),
        'featured_image'        => _x( 'Team Member Photo', 'Overrides the "Featured Image" phrase', 'aqualuxe' ),
        'set_featured_image'    => _x( 'Set team member photo', 'Overrides the "Set featured image" phrase', 'aqualuxe' ),
        'remove_featured_image' => _x( 'Remove team member photo', 'Overrides the "Remove featured image" phrase', 'aqualuxe' ),
        'use_featured_image'    => _x( 'Use as team member photo', 'Overrides the "Use as featured image" phrase', 'aqualuxe' ),
        'archives'              => _x( 'Team Member archives', 'The post type archive label used in nav menus', 'aqualuxe' ),
        'insert_into_item'      => _x( 'Insert into team member', 'Overrides the "Insert into post" phrase', 'aqualuxe' ),
        'uploaded_to_this_item' => _x( 'Uploaded to this team member', 'Overrides the "Uploaded to this post" phrase', 'aqualuxe' ),
        'filter_items_list'     => _x( 'Filter team members list', 'Screen reader text for the filter links', 'aqualuxe' ),
        'items_list_navigation' => _x( 'Team members list navigation', 'Screen reader text for the pagination', 'aqualuxe' ),
        'items_list'            => _x( 'Team members list', 'Screen reader text for the items list', 'aqualuxe' ),
    );

    $team_args = array(
        'labels'             => $team_labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'team' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 23,
        'menu_icon'          => 'dashicons-groups',
        'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
        'show_in_rest'       => true,
    );

    register_post_type( 'team', $team_args );

    // Projects Custom Post Type
    $projects_labels = array(
        'name'                  => _x( 'Projects', 'Post type general name', 'aqualuxe' ),
        'singular_name'         => _x( 'Project', 'Post type singular name', 'aqualuxe' ),
        'menu_name'             => _x( 'Projects', 'Admin Menu text', 'aqualuxe' ),
        'name_admin_bar'        => _x( 'Project', 'Add New on Toolbar', 'aqualuxe' ),
        'add_new'               => __( 'Add New', 'aqualuxe' ),
        'add_new_item'          => __( 'Add New Project', 'aqualuxe' ),
        'new_item'              => __( 'New Project', 'aqualuxe' ),
        'edit_item'             => __( 'Edit Project', 'aqualuxe' ),
        'view_item'             => __( 'View Project', 'aqualuxe' ),
        'all_items'             => __( 'All Projects', 'aqualuxe' ),
        'search_items'          => __( 'Search Projects', 'aqualuxe' ),
        'parent_item_colon'     => __( 'Parent Projects:', 'aqualuxe' ),
        'not_found'             => __( 'No projects found.', 'aqualuxe' ),
        'not_found_in_trash'    => __( 'No projects found in Trash.', 'aqualuxe' ),
        'featured_image'        => _x( 'Project Cover Image', 'Overrides the "Featured Image" phrase', 'aqualuxe' ),
        'set_featured_image'    => _x( 'Set cover image', 'Overrides the "Set featured image" phrase', 'aqualuxe' ),
        'remove_featured_image' => _x( 'Remove cover image', 'Overrides the "Remove featured image" phrase', 'aqualuxe' ),
        'use_featured_image'    => _x( 'Use as cover image', 'Overrides the "Use as featured image" phrase', 'aqualuxe' ),
        'archives'              => _x( 'Project archives', 'The post type archive label used in nav menus', 'aqualuxe' ),
        'insert_into_item'      => _x( 'Insert into project', 'Overrides the "Insert into post" phrase', 'aqualuxe' ),
        'uploaded_to_this_item' => _x( 'Uploaded to this project', 'Overrides the "Uploaded to this post" phrase', 'aqualuxe' ),
        'filter_items_list'     => _x( 'Filter projects list', 'Screen reader text for the filter links', 'aqualuxe' ),
        'items_list_navigation' => _x( 'Projects list navigation', 'Screen reader text for the pagination', 'aqualuxe' ),
        'items_list'            => _x( 'Projects list', 'Screen reader text for the items list', 'aqualuxe' ),
    );

    $projects_args = array(
        'labels'             => $projects_labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'projects' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 24,
        'menu_icon'          => 'dashicons-portfolio',
        'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
        'show_in_rest'       => true,
    );

    register_post_type( 'project', $projects_args );

    // FAQ Custom Post Type
    $faq_labels = array(
        'name'                  => _x( 'FAQs', 'Post type general name', 'aqualuxe' ),
        'singular_name'         => _x( 'FAQ', 'Post type singular name', 'aqualuxe' ),
        'menu_name'             => _x( 'FAQs', 'Admin Menu text', 'aqualuxe' ),
        'name_admin_bar'        => _x( 'FAQ', 'Add New on Toolbar', 'aqualuxe' ),
        'add_new'               => __( 'Add New', 'aqualuxe' ),
        'add_new_item'          => __( 'Add New FAQ', 'aqualuxe' ),
        'new_item'              => __( 'New FAQ', 'aqualuxe' ),
        'edit_item'             => __( 'Edit FAQ', 'aqualuxe' ),
        'view_item'             => __( 'View FAQ', 'aqualuxe' ),
        'all_items'             => __( 'All FAQs', 'aqualuxe' ),
        'search_items'          => __( 'Search FAQs', 'aqualuxe' ),
        'parent_item_colon'     => __( 'Parent FAQs:', 'aqualuxe' ),
        'not_found'             => __( 'No FAQs found.', 'aqualuxe' ),
        'not_found_in_trash'    => __( 'No FAQs found in Trash.', 'aqualuxe' ),
        'archives'              => _x( 'FAQ archives', 'The post type archive label used in nav menus', 'aqualuxe' ),
        'insert_into_item'      => _x( 'Insert into FAQ', 'Overrides the "Insert into post" phrase', 'aqualuxe' ),
        'uploaded_to_this_item' => _x( 'Uploaded to this FAQ', 'Overrides the "Uploaded to this post" phrase', 'aqualuxe' ),
        'filter_items_list'     => _x( 'Filter FAQs list', 'Screen reader text for the filter links', 'aqualuxe' ),
        'items_list_navigation' => _x( 'FAQs list navigation', 'Screen reader text for the pagination', 'aqualuxe' ),
        'items_list'            => _x( 'FAQs list', 'Screen reader text for the items list', 'aqualuxe' ),
    );

    $faq_args = array(
        'labels'             => $faq_labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'faqs' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 25,
        'menu_icon'          => 'dashicons-editor-help',
        'supports'           => array( 'title', 'editor', 'custom-fields' ),
        'show_in_rest'       => true,
    );

    register_post_type( 'faq', $faq_args );
}
add_action( 'init', 'aqualuxe_register_post_types' );

/**
 * Add meta boxes for custom post types
 */
function aqualuxe_add_meta_boxes() {
    // Service meta box
    add_meta_box(
        'service_details',
        __( 'Service Details', 'aqualuxe' ),
        'aqualuxe_service_meta_box_callback',
        'service',
        'normal',
        'high'
    );

    // Event meta box
    add_meta_box(
        'event_details',
        __( 'Event Details', 'aqualuxe' ),
        'aqualuxe_event_meta_box_callback',
        'event',
        'normal',
        'high'
    );

    // Testimonial meta box
    add_meta_box(
        'testimonial_details',
        __( 'Testimonial Details', 'aqualuxe' ),
        'aqualuxe_testimonial_meta_box_callback',
        'testimonial',
        'normal',
        'high'
    );

    // Team member meta box
    add_meta_box(
        'team_details',
        __( 'Team Member Details', 'aqualuxe' ),
        'aqualuxe_team_meta_box_callback',
        'team',
        'normal',
        'high'
    );

    // Project meta box
    add_meta_box(
        'project_details',
        __( 'Project Details', 'aqualuxe' ),
        'aqualuxe_project_meta_box_callback',
        'project',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'aqualuxe_add_meta_boxes' );

/**
 * Service meta box callback
 */
function aqualuxe_service_meta_box_callback( $post ) {
    wp_nonce_field( 'aqualuxe_service_meta_box', 'aqualuxe_service_meta_box_nonce' );

    $service_price = get_post_meta( $post->ID, '_service_price', true );
    $service_duration = get_post_meta( $post->ID, '_service_duration', true );
    $service_icon = get_post_meta( $post->ID, '_service_icon', true );
    $service_features = get_post_meta( $post->ID, '_service_features', true );

    ?>
    <div class="aqualuxe-meta-box">
        <p>
            <label for="service_price"><?php esc_html_e( 'Price', 'aqualuxe' ); ?></label>
            <input type="text" id="service_price" name="service_price" value="<?php echo esc_attr( $service_price ); ?>" class="widefat" />
            <span class="description"><?php esc_html_e( 'Enter the price for this service (e.g. $99, $99-$199, or "Starting at $99").', 'aqualuxe' ); ?></span>
        </p>
        <p>
            <label for="service_duration"><?php esc_html_e( 'Duration', 'aqualuxe' ); ?></label>
            <input type="text" id="service_duration" name="service_duration" value="<?php echo esc_attr( $service_duration ); ?>" class="widefat" />
            <span class="description"><?php esc_html_e( 'Enter the duration for this service (e.g. 1 hour, 2-3 hours, or "Varies").', 'aqualuxe' ); ?></span>
        </p>
        <p>
            <label for="service_icon"><?php esc_html_e( 'Icon', 'aqualuxe' ); ?></label>
            <input type="text" id="service_icon" name="service_icon" value="<?php echo esc_attr( $service_icon ); ?>" class="widefat" />
            <span class="description"><?php esc_html_e( 'Enter an icon class (e.g. "fa-fish" for Font Awesome).', 'aqualuxe' ); ?></span>
        </p>
        <p>
            <label for="service_features"><?php esc_html_e( 'Features', 'aqualuxe' ); ?></label>
            <textarea id="service_features" name="service_features" class="widefat" rows="5"><?php echo esc_textarea( $service_features ); ?></textarea>
            <span class="description"><?php esc_html_e( 'Enter features for this service, one per line.', 'aqualuxe' ); ?></span>
        </p>
    </div>
    <?php
}

/**
 * Event meta box callback
 */
function aqualuxe_event_meta_box_callback( $post ) {
    wp_nonce_field( 'aqualuxe_event_meta_box', 'aqualuxe_event_meta_box_nonce' );

    $event_date = get_post_meta( $post->ID, '_event_date', true );
    $event_time = get_post_meta( $post->ID, '_event_time', true );
    $event_location = get_post_meta( $post->ID, '_event_location', true );
    $event_price = get_post_meta( $post->ID, '_event_price', true );
    $event_registration_url = get_post_meta( $post->ID, '_event_registration_url', true );

    ?>
    <div class="aqualuxe-meta-box">
        <p>
            <label for="event_date"><?php esc_html_e( 'Date', 'aqualuxe' ); ?></label>
            <input type="date" id="event_date" name="event_date" value="<?php echo esc_attr( $event_date ); ?>" class="widefat" />
        </p>
        <p>
            <label for="event_time"><?php esc_html_e( 'Time', 'aqualuxe' ); ?></label>
            <input type="text" id="event_time" name="event_time" value="<?php echo esc_attr( $event_time ); ?>" class="widefat" />
            <span class="description"><?php esc_html_e( 'Enter the time for this event (e.g. 6:00 PM - 9:00 PM).', 'aqualuxe' ); ?></span>
        </p>
        <p>
            <label for="event_location"><?php esc_html_e( 'Location', 'aqualuxe' ); ?></label>
            <input type="text" id="event_location" name="event_location" value="<?php echo esc_attr( $event_location ); ?>" class="widefat" />
        </p>
        <p>
            <label for="event_price"><?php esc_html_e( 'Price', 'aqualuxe' ); ?></label>
            <input type="text" id="event_price" name="event_price" value="<?php echo esc_attr( $event_price ); ?>" class="widefat" />
            <span class="description"><?php esc_html_e( 'Enter the price for this event (e.g. $25, Free, or "Varies").', 'aqualuxe' ); ?></span>
        </p>
        <p>
            <label for="event_registration_url"><?php esc_html_e( 'Registration URL', 'aqualuxe' ); ?></label>
            <input type="url" id="event_registration_url" name="event_registration_url" value="<?php echo esc_url( $event_registration_url ); ?>" class="widefat" />
        </p>
    </div>
    <?php
}

/**
 * Testimonial meta box callback
 */
function aqualuxe_testimonial_meta_box_callback( $post ) {
    wp_nonce_field( 'aqualuxe_testimonial_meta_box', 'aqualuxe_testimonial_meta_box_nonce' );

    $client_name = get_post_meta( $post->ID, '_client_name', true );
    $client_position = get_post_meta( $post->ID, '_client_position', true );
    $client_company = get_post_meta( $post->ID, '_client_company', true );
    $client_rating = get_post_meta( $post->ID, '_client_rating', true );

    ?>
    <div class="aqualuxe-meta-box">
        <p>
            <label for="client_name"><?php esc_html_e( 'Client Name', 'aqualuxe' ); ?></label>
            <input type="text" id="client_name" name="client_name" value="<?php echo esc_attr( $client_name ); ?>" class="widefat" />
        </p>
        <p>
            <label for="client_position"><?php esc_html_e( 'Client Position', 'aqualuxe' ); ?></label>
            <input type="text" id="client_position" name="client_position" value="<?php echo esc_attr( $client_position ); ?>" class="widefat" />
        </p>
        <p>
            <label for="client_company"><?php esc_html_e( 'Client Company', 'aqualuxe' ); ?></label>
            <input type="text" id="client_company" name="client_company" value="<?php echo esc_attr( $client_company ); ?>" class="widefat" />
        </p>
        <p>
            <label for="client_rating"><?php esc_html_e( 'Rating (1-5)', 'aqualuxe' ); ?></label>
            <select id="client_rating" name="client_rating" class="widefat">
                <option value=""><?php esc_html_e( 'Select Rating', 'aqualuxe' ); ?></option>
                <?php for ( $i = 1; $i <= 5; $i++ ) : ?>
                    <option value="<?php echo esc_attr( $i ); ?>" <?php selected( $client_rating, $i ); ?>><?php echo esc_html( $i ); ?></option>
                <?php endfor; ?>
            </select>
        </p>
    </div>
    <?php
}

/**
 * Team member meta box callback
 */
function aqualuxe_team_meta_box_callback( $post ) {
    wp_nonce_field( 'aqualuxe_team_meta_box', 'aqualuxe_team_meta_box_nonce' );

    $team_position = get_post_meta( $post->ID, '_team_position', true );
    $team_email = get_post_meta( $post->ID, '_team_email', true );
    $team_phone = get_post_meta( $post->ID, '_team_phone', true );
    $team_facebook = get_post_meta( $post->ID, '_team_facebook', true );
    $team_twitter = get_post_meta( $post->ID, '_team_twitter', true );
    $team_linkedin = get_post_meta( $post->ID, '_team_linkedin', true );
    $team_instagram = get_post_meta( $post->ID, '_team_instagram', true );

    ?>
    <div class="aqualuxe-meta-box">
        <p>
            <label for="team_position"><?php esc_html_e( 'Position', 'aqualuxe' ); ?></label>
            <input type="text" id="team_position" name="team_position" value="<?php echo esc_attr( $team_position ); ?>" class="widefat" />
        </p>
        <p>
            <label for="team_email"><?php esc_html_e( 'Email', 'aqualuxe' ); ?></label>
            <input type="email" id="team_email" name="team_email" value="<?php echo esc_attr( $team_email ); ?>" class="widefat" />
        </p>
        <p>
            <label for="team_phone"><?php esc_html_e( 'Phone', 'aqualuxe' ); ?></label>
            <input type="text" id="team_phone" name="team_phone" value="<?php echo esc_attr( $team_phone ); ?>" class="widefat" />
        </p>
        <h4><?php esc_html_e( 'Social Media', 'aqualuxe' ); ?></h4>
        <p>
            <label for="team_facebook"><?php esc_html_e( 'Facebook URL', 'aqualuxe' ); ?></label>
            <input type="url" id="team_facebook" name="team_facebook" value="<?php echo esc_url( $team_facebook ); ?>" class="widefat" />
        </p>
        <p>
            <label for="team_twitter"><?php esc_html_e( 'Twitter URL', 'aqualuxe' ); ?></label>
            <input type="url" id="team_twitter" name="team_twitter" value="<?php echo esc_url( $team_twitter ); ?>" class="widefat" />
        </p>
        <p>
            <label for="team_linkedin"><?php esc_html_e( 'LinkedIn URL', 'aqualuxe' ); ?></label>
            <input type="url" id="team_linkedin" name="team_linkedin" value="<?php echo esc_url( $team_linkedin ); ?>" class="widefat" />
        </p>
        <p>
            <label for="team_instagram"><?php esc_html_e( 'Instagram URL', 'aqualuxe' ); ?></label>
            <input type="url" id="team_instagram" name="team_instagram" value="<?php echo esc_url( $team_instagram ); ?>" class="widefat" />
        </p>
    </div>
    <?php
}

/**
 * Project meta box callback
 */
function aqualuxe_project_meta_box_callback( $post ) {
    wp_nonce_field( 'aqualuxe_project_meta_box', 'aqualuxe_project_meta_box_nonce' );

    $project_client = get_post_meta( $post->ID, '_project_client', true );
    $project_location = get_post_meta( $post->ID, '_project_location', true );
    $project_date = get_post_meta( $post->ID, '_project_date', true );
    $project_value = get_post_meta( $post->ID, '_project_value', true );
    $project_gallery = get_post_meta( $post->ID, '_project_gallery', true );

    ?>
    <div class="aqualuxe-meta-box">
        <p>
            <label for="project_client"><?php esc_html_e( 'Client', 'aqualuxe' ); ?></label>
            <input type="text" id="project_client" name="project_client" value="<?php echo esc_attr( $project_client ); ?>" class="widefat" />
        </p>
        <p>
            <label for="project_location"><?php esc_html_e( 'Location', 'aqualuxe' ); ?></label>
            <input type="text" id="project_location" name="project_location" value="<?php echo esc_attr( $project_location ); ?>" class="widefat" />
        </p>
        <p>
            <label for="project_date"><?php esc_html_e( 'Completion Date', 'aqualuxe' ); ?></label>
            <input type="date" id="project_date" name="project_date" value="<?php echo esc_attr( $project_date ); ?>" class="widefat" />
        </p>
        <p>
            <label for="project_value"><?php esc_html_e( 'Project Value', 'aqualuxe' ); ?></label>
            <input type="text" id="project_value" name="project_value" value="<?php echo esc_attr( $project_value ); ?>" class="widefat" />
        </p>
        <p>
            <label for="project_gallery"><?php esc_html_e( 'Gallery IDs', 'aqualuxe' ); ?></label>
            <input type="text" id="project_gallery" name="project_gallery" value="<?php echo esc_attr( $project_gallery ); ?>" class="widefat" />
            <span class="description"><?php esc_html_e( 'Enter image IDs separated by commas.', 'aqualuxe' ); ?></span>
        </p>
    </div>
    <?php
}

/**
 * Save meta box data
 */
function aqualuxe_save_meta_box_data( $post_id ) {
    // Check if our nonce is set for each post type
    if ( isset( $_POST['aqualuxe_service_meta_box_nonce'] ) ) {
        if ( ! wp_verify_nonce( $_POST['aqualuxe_service_meta_box_nonce'], 'aqualuxe_service_meta_box' ) ) {
            return;
        }
        
        if ( isset( $_POST['service_price'] ) ) {
            update_post_meta( $post_id, '_service_price', sanitize_text_field( $_POST['service_price'] ) );
        }
        
        if ( isset( $_POST['service_duration'] ) ) {
            update_post_meta( $post_id, '_service_duration', sanitize_text_field( $_POST['service_duration'] ) );
        }
        
        if ( isset( $_POST['service_icon'] ) ) {
            update_post_meta( $post_id, '_service_icon', sanitize_text_field( $_POST['service_icon'] ) );
        }
        
        if ( isset( $_POST['service_features'] ) ) {
            update_post_meta( $post_id, '_service_features', sanitize_textarea_field( $_POST['service_features'] ) );
        }
    }

    if ( isset( $_POST['aqualuxe_event_meta_box_nonce'] ) ) {
        if ( ! wp_verify_nonce( $_POST['aqualuxe_event_meta_box_nonce'], 'aqualuxe_event_meta_box' ) ) {
            return;
        }
        
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

    if ( isset( $_POST['aqualuxe_testimonial_meta_box_nonce'] ) ) {
        if ( ! wp_verify_nonce( $_POST['aqualuxe_testimonial_meta_box_nonce'], 'aqualuxe_testimonial_meta_box' ) ) {
            return;
        }
        
        if ( isset( $_POST['client_name'] ) ) {
            update_post_meta( $post_id, '_client_name', sanitize_text_field( $_POST['client_name'] ) );
        }
        
        if ( isset( $_POST['client_position'] ) ) {
            update_post_meta( $post_id, '_client_position', sanitize_text_field( $_POST['client_position'] ) );
        }
        
        if ( isset( $_POST['client_company'] ) ) {
            update_post_meta( $post_id, '_client_company', sanitize_text_field( $_POST['client_company'] ) );
        }
        
        if ( isset( $_POST['client_rating'] ) ) {
            update_post_meta( $post_id, '_client_rating', absint( $_POST['client_rating'] ) );
        }
    }

    if ( isset( $_POST['aqualuxe_team_meta_box_nonce'] ) ) {
        if ( ! wp_verify_nonce( $_POST['aqualuxe_team_meta_box_nonce'], 'aqualuxe_team_meta_box' ) ) {
            return;
        }
        
        if ( isset( $_POST['team_position'] ) ) {
            update_post_meta( $post_id, '_team_position', sanitize_text_field( $_POST['team_position'] ) );
        }
        
        if ( isset( $_POST['team_email'] ) ) {
            update_post_meta( $post_id, '_team_email', sanitize_email( $_POST['team_email'] ) );
        }
        
        if ( isset( $_POST['team_phone'] ) ) {
            update_post_meta( $post_id, '_team_phone', sanitize_text_field( $_POST['team_phone'] ) );
        }
        
        if ( isset( $_POST['team_facebook'] ) ) {
            update_post_meta( $post_id, '_team_facebook', esc_url_raw( $_POST['team_facebook'] ) );
        }
        
        if ( isset( $_POST['team_twitter'] ) ) {
            update_post_meta( $post_id, '_team_twitter', esc_url_raw( $_POST['team_twitter'] ) );
        }
        
        if ( isset( $_POST['team_linkedin'] ) ) {
            update_post_meta( $post_id, '_team_linkedin', esc_url_raw( $_POST['team_linkedin'] ) );
        }
        
        if ( isset( $_POST['team_instagram'] ) ) {
            update_post_meta( $post_id, '_team_instagram', esc_url_raw( $_POST['team_instagram'] ) );
        }
    }

    if ( isset( $_POST['aqualuxe_project_meta_box_nonce'] ) ) {
        if ( ! wp_verify_nonce( $_POST['aqualuxe_project_meta_box_nonce'], 'aqualuxe_project_meta_box' ) ) {
            return;
        }
        
        if ( isset( $_POST['project_client'] ) ) {
            update_post_meta( $post_id, '_project_client', sanitize_text_field( $_POST['project_client'] ) );
        }
        
        if ( isset( $_POST['project_location'] ) ) {
            update_post_meta( $post_id, '_project_location', sanitize_text_field( $_POST['project_location'] ) );
        }
        
        if ( isset( $_POST['project_date'] ) ) {
            update_post_meta( $post_id, '_project_date', sanitize_text_field( $_POST['project_date'] ) );
        }
        
        if ( isset( $_POST['project_value'] ) ) {
            update_post_meta( $post_id, '_project_value', sanitize_text_field( $_POST['project_value'] ) );
        }
        
        if ( isset( $_POST['project_gallery'] ) ) {
            update_post_meta( $post_id, '_project_gallery', sanitize_text_field( $_POST['project_gallery'] ) );
        }
    }
}
add_action( 'save_post', 'aqualuxe_save_meta_box_data' );

/**
 * Register shortcodes for custom post types
 */
function aqualuxe_register_shortcodes() {
    add_shortcode( 'aqualuxe_services', 'aqualuxe_services_shortcode' );
    add_shortcode( 'aqualuxe_events', 'aqualuxe_events_shortcode' );
    add_shortcode( 'aqualuxe_testimonials', 'aqualuxe_testimonials_shortcode' );
    add_shortcode( 'aqualuxe_team', 'aqualuxe_team_shortcode' );
    add_shortcode( 'aqualuxe_projects', 'aqualuxe_projects_shortcode' );
    add_shortcode( 'aqualuxe_faqs', 'aqualuxe_faqs_shortcode' );
}
add_action( 'init', 'aqualuxe_register_shortcodes' );

/**
 * Services shortcode
 */
function aqualuxe_services_shortcode( $atts ) {
    $atts = shortcode_atts(
        array(
            'count'      => 3,
            'category'   => '',
            'columns'    => 3,
            'orderby'    => 'date',
            'order'      => 'DESC',
            'layout'     => 'grid', // grid, list
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
        
        if ( 'grid' === $atts['layout'] ) {
            echo '<div class="grid grid-cols-1 md:grid-cols-' . esc_attr( $atts['columns'] ) . ' gap-8">';
        }

        while ( $services->have_posts() ) {
            $services->the_post();
            
            $service_price = get_post_meta( get_the_ID(), '_service_price', true );
            $service_duration = get_post_meta( get_the_ID(), '_service_duration', true );
            $service_icon = get_post_meta( get_the_ID(), '_service_icon', true );
            
            if ( 'grid' === $atts['layout'] ) {
                ?>
                <div class="service-item bg-white rounded-lg shadow-md overflow-hidden">
                    <?php if ( has_post_thumbnail() ) : ?>
                        <div class="service-image">
                            <?php the_post_thumbnail( 'medium', array( 'class' => 'w-full h-auto' ) ); ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="service-content p-6">
                        <?php if ( ! empty( $service_icon ) ) : ?>
                            <div class="service-icon mb-4">
                                <i class="<?php echo esc_attr( $service_icon ); ?>"></i>
                            </div>
                        <?php endif; ?>
                        
                        <h3 class="service-title text-xl font-bold mb-2">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h3>
                        
                        <div class="service-meta text-sm text-gray-600 mb-4">
                            <?php if ( ! empty( $service_price ) ) : ?>
                                <div class="service-price mb-1">
                                    <strong><?php esc_html_e( 'Price:', 'aqualuxe' ); ?></strong> <?php echo esc_html( $service_price ); ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ( ! empty( $service_duration ) ) : ?>
                                <div class="service-duration">
                                    <strong><?php esc_html_e( 'Duration:', 'aqualuxe' ); ?></strong> <?php echo esc_html( $service_duration ); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="service-excerpt mb-4">
                            <?php the_excerpt(); ?>
                        </div>
                        
                        <a href="<?php the_permalink(); ?>" class="service-link inline-block bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded transition-colors">
                            <?php esc_html_e( 'Learn More', 'aqualuxe' ); ?>
                        </a>
                    </div>
                </div>
                <?php
            } else {
                ?>
                <div class="service-item flex flex-col md:flex-row bg-white rounded-lg shadow-md overflow-hidden mb-8">
                    <?php if ( has_post_thumbnail() ) : ?>
                        <div class="service-image md:w-1/3">
                            <?php the_post_thumbnail( 'medium', array( 'class' => 'w-full h-full object-cover' ) ); ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="service-content p-6 md:w-2/3">
                        <h3 class="service-title text-xl font-bold mb-2">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h3>
                        
                        <div class="service-meta text-sm text-gray-600 mb-4">
                            <?php if ( ! empty( $service_price ) ) : ?>
                                <div class="service-price mb-1">
                                    <strong><?php esc_html_e( 'Price:', 'aqualuxe' ); ?></strong> <?php echo esc_html( $service_price ); ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ( ! empty( $service_duration ) ) : ?>
                                <div class="service-duration">
                                    <strong><?php esc_html_e( 'Duration:', 'aqualuxe' ); ?></strong> <?php echo esc_html( $service_duration ); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="service-excerpt mb-4">
                            <?php the_excerpt(); ?>
                        </div>
                        
                        <a href="<?php the_permalink(); ?>" class="service-link inline-block bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded transition-colors">
                            <?php esc_html_e( 'Learn More', 'aqualuxe' ); ?>
                        </a>
                    </div>
                </div>
                <?php
            }
        }

        if ( 'grid' === $atts['layout'] ) {
            echo '</div>';
        }
        
        echo '</div>';
    }

    wp_reset_postdata();

    return ob_get_clean();
}

/**
 * Events shortcode
 */
function aqualuxe_events_shortcode( $atts ) {
    $atts = shortcode_atts(
        array(
            'count'      => 3,
            'category'   => '',
            'columns'    => 3,
            'orderby'    => 'meta_value',
            'meta_key'   => '_event_date',
            'order'      => 'ASC',
            'layout'     => 'grid', // grid, list, calendar
            'show_past'  => 'no',
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

    // Filter out past events if show_past is set to 'no'
    if ( 'no' === $atts['show_past'] ) {
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
        
        if ( 'grid' === $atts['layout'] ) {
            echo '<div class="grid grid-cols-1 md:grid-cols-' . esc_attr( $atts['columns'] ) . ' gap-8">';
        } elseif ( 'calendar' === $atts['layout'] ) {
            echo '<div class="events-calendar">';
            // Calendar header would go here
        }

        while ( $events->have_posts() ) {
            $events->the_post();
            
            $event_date = get_post_meta( get_the_ID(), '_event_date', true );
            $event_time = get_post_meta( get_the_ID(), '_event_time', true );
            $event_location = get_post_meta( get_the_ID(), '_event_location', true );
            $event_price = get_post_meta( get_the_ID(), '_event_price', true );
            $event_registration_url = get_post_meta( get_the_ID(), '_event_registration_url', true );
            
            // Format date
            $formatted_date = ! empty( $event_date ) ? date_i18n( get_option( 'date_format' ), strtotime( $event_date ) ) : '';
            
            if ( 'grid' === $atts['layout'] ) {
                ?>
                <div class="event-item bg-white rounded-lg shadow-md overflow-hidden">
                    <?php if ( has_post_thumbnail() ) : ?>
                        <div class="event-image">
                            <?php the_post_thumbnail( 'medium', array( 'class' => 'w-full h-auto' ) ); ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="event-content p-6">
                        <div class="event-date-badge mb-4 inline-block bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                            <?php echo esc_html( $formatted_date ); ?>
                            <?php if ( ! empty( $event_time ) ) : ?>
                                <span class="event-time ml-1"><?php echo esc_html( $event_time ); ?></span>
                            <?php endif; ?>
                        </div>
                        
                        <h3 class="event-title text-xl font-bold mb-2">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h3>
                        
                        <?php if ( ! empty( $event_location ) ) : ?>
                            <div class="event-location text-sm text-gray-600 mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <?php echo esc_html( $event_location ); ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ( ! empty( $event_price ) ) : ?>
                            <div class="event-price text-sm text-gray-600 mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <?php echo esc_html( $event_price ); ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="event-excerpt mb-4">
                            <?php the_excerpt(); ?>
                        </div>
                        
                        <div class="event-actions">
                            <a href="<?php the_permalink(); ?>" class="event-link inline-block bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded transition-colors mr-2">
                                <?php esc_html_e( 'Details', 'aqualuxe' ); ?>
                            </a>
                            
                            <?php if ( ! empty( $event_registration_url ) ) : ?>
                                <a href="<?php echo esc_url( $event_registration_url ); ?>" class="event-register inline-block bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded transition-colors">
                                    <?php esc_html_e( 'Register', 'aqualuxe' ); ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php
            } elseif ( 'list' === $atts['layout'] ) {
                ?>
                <div class="event-item flex flex-col md:flex-row bg-white rounded-lg shadow-md overflow-hidden mb-8">
                    <?php if ( has_post_thumbnail() ) : ?>
                        <div class="event-image md:w-1/4">
                            <?php the_post_thumbnail( 'medium', array( 'class' => 'w-full h-full object-cover' ) ); ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="event-content p-6 md:w-3/4">
                        <div class="event-date-badge mb-2 inline-block bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                            <?php echo esc_html( $formatted_date ); ?>
                            <?php if ( ! empty( $event_time ) ) : ?>
                                <span class="event-time ml-1"><?php echo esc_html( $event_time ); ?></span>
                            <?php endif; ?>
                        </div>
                        
                        <h3 class="event-title text-xl font-bold mb-2">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h3>
                        
                        <div class="event-meta flex flex-wrap mb-4">
                            <?php if ( ! empty( $event_location ) ) : ?>
                                <div class="event-location text-sm text-gray-600 mr-4 mb-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <?php echo esc_html( $event_location ); ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ( ! empty( $event_price ) ) : ?>
                                <div class="event-price text-sm text-gray-600 mb-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <?php echo esc_html( $event_price ); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="event-excerpt mb-4">
                            <?php the_excerpt(); ?>
                        </div>
                        
                        <div class="event-actions">
                            <a href="<?php the_permalink(); ?>" class="event-link inline-block bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded transition-colors mr-2">
                                <?php esc_html_e( 'Details', 'aqualuxe' ); ?>
                            </a>
                            
                            <?php if ( ! empty( $event_registration_url ) ) : ?>
                                <a href="<?php echo esc_url( $event_registration_url ); ?>" class="event-register inline-block bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded transition-colors">
                                    <?php esc_html_e( 'Register', 'aqualuxe' ); ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php
            } elseif ( 'calendar' === $atts['layout'] ) {
                // Calendar layout would go here
            }
        }

        if ( 'grid' === $atts['layout'] || 'calendar' === $atts['layout'] ) {
            echo '</div>';
        }
        
        echo '</div>';
    }

    wp_reset_postdata();

    return ob_get_clean();
}

/**
 * Testimonials shortcode
 */
function aqualuxe_testimonials_shortcode( $atts ) {
    $atts = shortcode_atts(
        array(
            'count'      => 3,
            'orderby'    => 'date',
            'order'      => 'DESC',
            'layout'     => 'grid', // grid, slider
            'columns'    => 3,
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

    $testimonials = new WP_Query( $args );

    ob_start();

    if ( $testimonials->have_posts() ) {
        echo '<div class="aqualuxe-testimonials ' . esc_attr( $atts['layout'] ) . '-layout">';
        
        if ( 'grid' === $atts['layout'] ) {
            echo '<div class="grid grid-cols-1 md:grid-cols-' . esc_attr( $atts['columns'] ) . ' gap-8">';
        } elseif ( 'slider' === $atts['layout'] ) {
            echo '<div class="testimonials-slider">';
        }

        while ( $testimonials->have_posts() ) {
            $testimonials->the_post();
            
            $client_name = get_post_meta( get_the_ID(), '_client_name', true );
            $client_position = get_post_meta( get_the_ID(), '_client_position', true );
            $client_company = get_post_meta( get_the_ID(), '_client_company', true );
            $client_rating = get_post_meta( get_the_ID(), '_client_rating', true );
            
            ?>
            <div class="testimonial-item bg-white rounded-lg shadow-md p-6">
                <div class="testimonial-content mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-300 mb-2" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>
                    </svg>
                    <?php the_content(); ?>
                </div>
                
                <?php if ( ! empty( $client_rating ) ) : ?>
                    <div class="testimonial-rating mb-4">
                        <?php for ( $i = 1; $i <= 5; $i++ ) : ?>
                            <?php if ( $i <= $client_rating ) : ?>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400 inline-block" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            <?php else : ?>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-300 inline-block" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            <?php endif; ?>
                        <?php endfor; ?>
                    </div>
                <?php endif; ?>
                
                <div class="testimonial-author flex items-center">
                    <?php if ( has_post_thumbnail() ) : ?>
                        <div class="testimonial-avatar mr-4">
                            <?php the_post_thumbnail( 'thumbnail', array( 'class' => 'rounded-full w-12 h-12 object-cover' ) ); ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="testimonial-info">
                        <?php if ( ! empty( $client_name ) ) : ?>
                            <div class="testimonial-name font-bold"><?php echo esc_html( $client_name ); ?></div>
                        <?php else : ?>
                            <div class="testimonial-name font-bold"><?php the_title(); ?></div>
                        <?php endif; ?>
                        
                        <?php if ( ! empty( $client_position ) || ! empty( $client_company ) ) : ?>
                            <div class="testimonial-position text-sm text-gray-600">
                                <?php 
                                if ( ! empty( $client_position ) && ! empty( $client_company ) ) {
                                    echo esc_html( $client_position ) . ', ' . esc_html( $client_company );
                                } elseif ( ! empty( $client_position ) ) {
                                    echo esc_html( $client_position );
                                } elseif ( ! empty( $client_company ) ) {
                                    echo esc_html( $client_company );
                                }
                                ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php
        }

        echo '</div>';
        echo '</div>';
    }

    wp_reset_postdata();

    return ob_get_clean();
}

/**
 * Team shortcode
 */
function aqualuxe_team_shortcode( $atts ) {
    $atts = shortcode_atts(
        array(
            'count'      => -1,
            'category'   => '',
            'columns'    => 3,
            'orderby'    => 'menu_order',
            'order'      => 'ASC',
        ),
        $atts,
        'aqualuxe_team'
    );

    $args = array(
        'post_type'      => 'team',
        'posts_per_page' => $atts['count'],
        'orderby'        => $atts['orderby'],
        'order'          => $atts['order'],
    );

    if ( ! empty( $atts['category'] ) ) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'team_category',
                'field'    => 'slug',
                'terms'    => explode( ',', $atts['category'] ),
            ),
        );
    }

    $team = new WP_Query( $args );

    ob_start();

    if ( $team->have_posts() ) {
        echo '<div class="aqualuxe-team">';
        echo '<div class="grid grid-cols-1 md:grid-cols-' . esc_attr( $atts['columns'] ) . ' gap-8">';

        while ( $team->have_posts() ) {
            $team->the_post();
            
            $team_position = get_post_meta( get_the_ID(), '_team_position', true );
            $team_email = get_post_meta( get_the_ID(), '_team_email', true );
            $team_phone = get_post_meta( get_the_ID(), '_team_phone', true );
            $team_facebook = get_post_meta( get_the_ID(), '_team_facebook', true );
            $team_twitter = get_post_meta( get_the_ID(), '_team_twitter', true );
            $team_linkedin = get_post_meta( get_the_ID(), '_team_linkedin', true );
            $team_instagram = get_post_meta( get_the_ID(), '_team_instagram', true );
            
            ?>
            <div class="team-member bg-white rounded-lg shadow-md overflow-hidden">
                <?php if ( has_post_thumbnail() ) : ?>
                    <div class="team-image">
                        <?php the_post_thumbnail( 'medium', array( 'class' => 'w-full h-auto' ) ); ?>
                    </div>
                <?php endif; ?>
                
                <div class="team-content p-6">
                    <h3 class="team-name text-xl font-bold mb-1">
                        <?php the_title(); ?>
                    </h3>
                    
                    <?php if ( ! empty( $team_position ) ) : ?>
                        <div class="team-position text-blue-600 mb-4"><?php echo esc_html( $team_position ); ?></div>
                    <?php endif; ?>
                    
                    <div class="team-bio mb-4">
                        <?php the_excerpt(); ?>
                    </div>
                    
                    <div class="team-contact mb-4">
                        <?php if ( ! empty( $team_email ) ) : ?>
                            <div class="team-email mb-1">
                                <a href="mailto:<?php echo esc_attr( $team_email ); ?>" class="text-gray-600 hover:text-blue-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    <?php echo esc_html( $team_email ); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ( ! empty( $team_phone ) ) : ?>
                            <div class="team-phone">
                                <a href="tel:<?php echo esc_attr( $team_phone ); ?>" class="text-gray-600 hover:text-blue-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                    <?php echo esc_html( $team_phone ); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="team-social flex space-x-3">
                        <?php if ( ! empty( $team_facebook ) ) : ?>
                            <a href="<?php echo esc_url( $team_facebook ); ?>" class="text-gray-400 hover:text-blue-600" target="_blank" rel="noopener noreferrer">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/>
                                </svg>
                            </a>
                        <?php endif; ?>
                        
                        <?php if ( ! empty( $team_twitter ) ) : ?>
                            <a href="<?php echo esc_url( $team_twitter ); ?>" class="text-gray-400 hover:text-blue-400" target="_blank" rel="noopener noreferrer">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                                </svg>
                            </a>
                        <?php endif; ?>
                        
                        <?php if ( ! empty( $team_linkedin ) ) : ?>
                            <a href="<?php echo esc_url( $team_linkedin ); ?>" class="text-gray-400 hover:text-blue-700" target="_blank" rel="noopener noreferrer">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M4.98 3.5c0 1.381-1.11 2.5-2.48 2.5s-2.48-1.119-2.48-2.5c0-1.38 1.11-2.5 2.48-2.5s2.48 1.12 2.48 2.5zm.02 4.5h-5v16h5v-16zm7.982 0h-4.968v16h4.969v-8.399c0-4.67 6.029-5.052 6.029 0v8.399h4.988v-10.131c0-7.88-8.922-7.593-11.018-3.714v-2.155z"/>
                                </svg>
                            </a>
                        <?php endif; ?>
                        
                        <?php if ( ! empty( $team_instagram ) ) : ?>
                            <a href="<?php echo esc_url( $team_instagram ); ?>" class="text-gray-400 hover:text-pink-600" target="_blank" rel="noopener noreferrer">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                </svg>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php
        }

        echo '</div>';
        echo '</div>';
    }

    wp_reset_postdata();

    return ob_get_clean();
}

/**
 * Projects shortcode
 */
function aqualuxe_projects_shortcode( $atts ) {
    $atts = shortcode_atts(
        array(
            'count'      => 6,
            'category'   => '',
            'columns'    => 3,
            'orderby'    => 'date',
            'order'      => 'DESC',
            'layout'     => 'grid', // grid, masonry
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

    if ( ! empty( $atts['category'] ) ) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'project_category',
                'field'    => 'slug',
                'terms'    => explode( ',', $atts['category'] ),
            ),
        );
    }

    $projects = new WP_Query( $args );

    ob_start();

    if ( $projects->have_posts() ) {
        echo '<div class="aqualuxe-projects ' . esc_attr( $atts['layout'] ) . '-layout">';
        
        if ( 'grid' === $atts['layout'] ) {
            echo '<div class="grid grid-cols-1 md:grid-cols-' . esc_attr( $atts['columns'] ) . ' gap-8">';
        } elseif ( 'masonry' === $atts['layout'] ) {
            echo '<div class="masonry-grid columns-1 md:columns-' . esc_attr( $atts['columns'] ) . ' gap-8">';
        }

        while ( $projects->have_posts() ) {
            $projects->the_post();
            
            $project_client = get_post_meta( get_the_ID(), '_project_client', true );
            $project_location = get_post_meta( get_the_ID(), '_project_location', true );
            $project_date = get_post_meta( get_the_ID(), '_project_date', true );
            $project_value = get_post_meta( get_the_ID(), '_project_value', true );
            
            // Format date
            $formatted_date = ! empty( $project_date ) ? date_i18n( get_option( 'date_format' ), strtotime( $project_date ) ) : '';
            
            $item_class = 'masonry' === $atts['layout'] ? 'mb-8 break-inside-avoid' : '';
            
            ?>
            <div class="project-item bg-white rounded-lg shadow-md overflow-hidden <?php echo esc_attr( $item_class ); ?>">
                <?php if ( has_post_thumbnail() ) : ?>
                    <div class="project-image relative">
                        <?php the_post_thumbnail( 'large', array( 'class' => 'w-full h-auto' ) ); ?>
                        <div class="project-overlay absolute inset-0 bg-blue-900 bg-opacity-75 opacity-0 hover:opacity-100 flex items-center justify-center transition-opacity duration-300">
                            <a href="<?php the_permalink(); ?>" class="inline-block bg-white hover:bg-blue-50 text-blue-900 font-medium py-2 px-4 rounded transition-colors">
                                <?php esc_html_e( 'View Project', 'aqualuxe' ); ?>
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
                
                <div class="project-content p-6">
                    <h3 class="project-title text-xl font-bold mb-2">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h3>
                    
                    <div class="project-meta text-sm text-gray-600 mb-4">
                        <?php if ( ! empty( $project_client ) ) : ?>
                            <div class="project-client mb-1">
                                <strong><?php esc_html_e( 'Client:', 'aqualuxe' ); ?></strong> <?php echo esc_html( $project_client ); ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ( ! empty( $project_location ) ) : ?>
                            <div class="project-location mb-1">
                                <strong><?php esc_html_e( 'Location:', 'aqualuxe' ); ?></strong> <?php echo esc_html( $project_location ); ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ( ! empty( $formatted_date ) ) : ?>
                            <div class="project-date mb-1">
                                <strong><?php esc_html_e( 'Completed:', 'aqualuxe' ); ?></strong> <?php echo esc_html( $formatted_date ); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="project-excerpt mb-4">
                        <?php the_excerpt(); ?>
                    </div>
                    
                    <a href="<?php the_permalink(); ?>" class="project-link inline-block bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded transition-colors">
                        <?php esc_html_e( 'View Details', 'aqualuxe' ); ?>
                    </a>
                </div>
            </div>
            <?php
        }

        echo '</div>';
        echo '</div>';
    }

    wp_reset_postdata();

    return ob_get_clean();
}

/**
 * FAQs shortcode
 */
function aqualuxe_faqs_shortcode( $atts ) {
    $atts = shortcode_atts(
        array(
            'count'      => -1,
            'category'   => '',
            'orderby'    => 'menu_order',
            'order'      => 'ASC',
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
        echo '<div class="aqualuxe-faqs">';
        echo '<div class="faq-accordion space-y-4">';

        $counter = 0;
        while ( $faqs->have_posts() ) {
            $faqs->the_post();
            $counter++;
            
            ?>
            <div class="faq-item bg-white rounded-lg shadow-md overflow-hidden">
                <button class="faq-question w-full text-left p-6 focus:outline-none flex justify-between items-center" aria-expanded="false" aria-controls="faq-answer-<?php echo esc_attr( $counter ); ?>">
                    <h3 class="text-lg font-medium"><?php the_title(); ?></h3>
                    <svg xmlns="http://www.w3.org/2000/svg" class="faq-icon h-5 w-5 transform transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div id="faq-answer-<?php echo esc_attr( $counter ); ?>" class="faq-answer hidden p-6 pt-0 prose max-w-none">
                    <?php the_content(); ?>
                </div>
            </div>
            <?php
        }

        echo '</div>';
        echo '</div>';
        
        // Add JavaScript for accordion functionality
        ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const faqQuestions = document.querySelectorAll('.faq-question');
                
                faqQuestions.forEach(question => {
                    question.addEventListener('click', function() {
                        const answer = this.nextElementSibling;
                        const icon = this.querySelector('.faq-icon');
                        
                        // Toggle the answer visibility
                        answer.classList.toggle('hidden');
                        
                        // Toggle the icon rotation
                        icon.classList.toggle('rotate-180');
                        
                        // Update aria-expanded attribute
                        const isExpanded = answer.classList.contains('hidden') ? 'false' : 'true';
                        this.setAttribute('aria-expanded', isExpanded);
                    });
                });
            });
        </script>
        <?php
    }

    wp_reset_postdata();

    return ob_get_clean();
}

/**
 * Register custom taxonomies for custom post types
 */
function aqualuxe_register_taxonomies() {
    // Service Category Taxonomy
    $service_cat_labels = array(
        'name'              => _x( 'Service Categories', 'taxonomy general name', 'aqualuxe' ),
        'singular_name'     => _x( 'Service Category', 'taxonomy singular name', 'aqualuxe' ),
        'search_items'      => __( 'Search Service Categories', 'aqualuxe' ),
        'all_items'         => __( 'All Service Categories', 'aqualuxe' ),
        'parent_item'       => __( 'Parent Service Category', 'aqualuxe' ),
        'parent_item_colon' => __( 'Parent Service Category:', 'aqualuxe' ),
        'edit_item'         => __( 'Edit Service Category', 'aqualuxe' ),
        'update_item'       => __( 'Update Service Category', 'aqualuxe' ),
        'add_new_item'      => __( 'Add New Service Category', 'aqualuxe' ),
        'new_item_name'     => __( 'New Service Category Name', 'aqualuxe' ),
        'menu_name'         => __( 'Categories', 'aqualuxe' ),
    );

    register_taxonomy( 'service_category', array( 'service' ), array(
        'hierarchical'      => true,
        'labels'            => $service_cat_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'service-category' ),
        'show_in_rest'      => true,
    ) );

    // Event Category Taxonomy
    $event_cat_labels = array(
        'name'              => _x( 'Event Categories', 'taxonomy general name', 'aqualuxe' ),
        'singular_name'     => _x( 'Event Category', 'taxonomy singular name', 'aqualuxe' ),
        'search_items'      => __( 'Search Event Categories', 'aqualuxe' ),
        'all_items'         => __( 'All Event Categories', 'aqualuxe' ),
        'parent_item'       => __( 'Parent Event Category', 'aqualuxe' ),
        'parent_item_colon' => __( 'Parent Event Category:', 'aqualuxe' ),
        'edit_item'         => __( 'Edit Event Category', 'aqualuxe' ),
        'update_item'       => __( 'Update Event Category', 'aqualuxe' ),
        'add_new_item'      => __( 'Add New Event Category', 'aqualuxe' ),
        'new_item_name'     => __( 'New Event Category Name', 'aqualuxe' ),
        'menu_name'         => __( 'Categories', 'aqualuxe' ),
    );

    register_taxonomy( 'event_category', array( 'event' ), array(
        'hierarchical'      => true,
        'labels'            => $event_cat_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'event-category' ),
        'show_in_rest'      => true,
    ) );

    // Team Category Taxonomy
    $team_cat_labels = array(
        'name'              => _x( 'Team Categories', 'taxonomy general name', 'aqualuxe' ),
        'singular_name'     => _x( 'Team Category', 'taxonomy singular name', 'aqualuxe' ),
        'search_items'      => __( 'Search Team Categories', 'aqualuxe' ),
        'all_items'         => __( 'All Team Categories', 'aqualuxe' ),
        'parent_item'       => __( 'Parent Team Category', 'aqualuxe' ),
        'parent_item_colon' => __( 'Parent Team Category:', 'aqualuxe' ),
        'edit_item'         => __( 'Edit Team Category', 'aqualuxe' ),
        'update_item'       => __( 'Update Team Category', 'aqualuxe' ),
        'add_new_item'      => __( 'Add New Team Category', 'aqualuxe' ),
        'new_item_name'     => __( 'New Team Category Name', 'aqualuxe' ),
        'menu_name'         => __( 'Categories', 'aqualuxe' ),
    );

    register_taxonomy( 'team_category', array( 'team' ), array(
        'hierarchical'      => true,
        'labels'            => $team_cat_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'team-category' ),
        'show_in_rest'      => true,
    ) );

    // Project Category Taxonomy
    $project_cat_labels = array(
        'name'              => _x( 'Project Categories', 'taxonomy general name', 'aqualuxe' ),
        'singular_name'     => _x( 'Project Category', 'taxonomy singular name', 'aqualuxe' ),
        'search_items'      => __( 'Search Project Categories', 'aqualuxe' ),
        'all_items'         => __( 'All Project Categories', 'aqualuxe' ),
        'parent_item'       => __( 'Parent Project Category', 'aqualuxe' ),
        'parent_item_colon' => __( 'Parent Project Category:', 'aqualuxe' ),
        'edit_item'         => __( 'Edit Project Category', 'aqualuxe' ),
        'update_item'       => __( 'Update Project Category', 'aqualuxe' ),
        'add_new_item'      => __( 'Add New Project Category', 'aqualuxe' ),
        'new_item_name'     => __( 'New Project Category Name', 'aqualuxe' ),
        'menu_name'         => __( 'Categories', 'aqualuxe' ),
    );

    register_taxonomy( 'project_category', array( 'project' ), array(
        'hierarchical'      => true,
        'labels'            => $project_cat_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'project-category' ),
        'show_in_rest'      => true,
    ) );

    // FAQ Category Taxonomy
    $faq_cat_labels = array(
        'name'              => _x( 'FAQ Categories', 'taxonomy general name', 'aqualuxe' ),
        'singular_name'     => _x( 'FAQ Category', 'taxonomy singular name', 'aqualuxe' ),
        'search_items'      => __( 'Search FAQ Categories', 'aqualuxe' ),
        'all_items'         => __( 'All FAQ Categories', 'aqualuxe' ),
        'parent_item'       => __( 'Parent FAQ Category', 'aqualuxe' ),
        'parent_item_colon' => __( 'Parent FAQ Category:', 'aqualuxe' ),
        'edit_item'         => __( 'Edit FAQ Category', 'aqualuxe' ),
        'update_item'       => __( 'Update FAQ Category', 'aqualuxe' ),
        'add_new_item'      => __( 'Add New FAQ Category', 'aqualuxe' ),
        'new_item_name'     => __( 'New FAQ Category Name', 'aqualuxe' ),
        'menu_name'         => __( 'Categories', 'aqualuxe' ),
    );

    register_taxonomy( 'faq_category', array( 'faq' ), array(
        'hierarchical'      => true,
        'labels'            => $faq_cat_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'faq-category' ),
        'show_in_rest'      => true,
    ) );
}
add_action( 'init', 'aqualuxe_register_taxonomies' );

/**
 * Add custom columns to admin list tables
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
            
        case 'testimonial':
            $columns['client_name'] = __( 'Client', 'aqualuxe' );
            $columns['client_rating'] = __( 'Rating', 'aqualuxe' );
            break;
            
        case 'team':
            $columns['team_position'] = __( 'Position', 'aqualuxe' );
            break;
            
        case 'project':
            $columns['project_client'] = __( 'Client', 'aqualuxe' );
            $columns['project_date'] = __( 'Completion Date', 'aqualuxe' );
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
            $event_date = get_post_meta( $post_id, '_event_date', true );
            if ( ! empty( $event_date ) ) {
                echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $event_date ) ) );
            }
            break;
            
        case 'event_location':
            echo esc_html( get_post_meta( $post_id, '_event_location', true ) );
            break;
            
        case 'client_name':
            echo esc_html( get_post_meta( $post_id, '_client_name', true ) );
            break;
            
        case 'client_rating':
            $rating = get_post_meta( $post_id, '_client_rating', true );
            if ( ! empty( $rating ) ) {
                for ( $i = 1; $i <= 5; $i++ ) {
                    if ( $i <= $rating ) {
                        echo '★';
                    } else {
                        echo '☆';
                    }
                }
            }
            break;
            
        case 'team_position':
            echo esc_html( get_post_meta( $post_id, '_team_position', true ) );
            break;
            
        case 'project_client':
            echo esc_html( get_post_meta( $post_id, '_project_client', true ) );
            break;
            
        case 'project_date':
            $project_date = get_post_meta( $post_id, '_project_date', true );
            if ( ! empty( $project_date ) ) {
                echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $project_date ) ) );
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
        case 'event':
            $columns['event_date'] = 'event_date';
            break;
            
        case 'testimonial':
            $columns['client_rating'] = 'client_rating';
            break;
            
        case 'project':
            $columns['project_date'] = 'project_date';
            break;
    }
    
    return $columns;
}
add_filter( 'manage_edit-event_sortable_columns', 'aqualuxe_sortable_columns' );
add_filter( 'manage_edit-testimonial_sortable_columns', 'aqualuxe_sortable_columns' );
add_filter( 'manage_edit-project_sortable_columns', 'aqualuxe_sortable_columns' );

/**
 * Handle sorting for custom columns
 */
function aqualuxe_sort_custom_columns( $query ) {
    if ( ! is_admin() ) {
        return;
    }
    
    $orderby = $query->get( 'orderby' );
    
    switch ( $orderby ) {
        case 'event_date':
            $query->set( 'meta_key', '_event_date' );
            $query->set( 'orderby', 'meta_value' );
            break;
            
        case 'client_rating':
            $query->set( 'meta_key', '_client_rating' );
            $query->set( 'orderby', 'meta_value_num' );
            break;
            
        case 'project_date':
            $query->set( 'meta_key', '_project_date' );
            $query->set( 'orderby', 'meta_value' );
            break;
    }
}
add_action( 'pre_get_posts', 'aqualuxe_sort_custom_columns' );