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
    );

    $services_args = array(
        'labels'             => $services_labels,
        'description'        => __( 'Description.', 'aqualuxe' ),
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
        'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'custom-fields' ),
        'show_in_rest'       => true,
    );

    register_post_type( 'service', $services_args );

    // Register Service Category Taxonomy
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

    $service_cat_args = array(
        'hierarchical'      => true,
        'labels'            => $service_cat_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'service-category' ),
        'show_in_rest'      => true,
    );

    register_taxonomy( 'service_category', array( 'service' ), $service_cat_args );

    // Events Custom Post Type
    $events_labels = array(
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
    );

    $events_args = array(
        'labels'             => $events_labels,
        'description'        => __( 'Description.', 'aqualuxe' ),
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
        'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'custom-fields' ),
        'show_in_rest'       => true,
    );

    register_post_type( 'event', $events_args );

    // Register Event Category Taxonomy
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

    $event_cat_args = array(
        'hierarchical'      => true,
        'labels'            => $event_cat_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'event-category' ),
        'show_in_rest'      => true,
    );

    register_taxonomy( 'event_category', array( 'event' ), $event_cat_args );

    // Testimonials Custom Post Type
    $testimonials_labels = array(
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
    );

    $testimonials_args = array(
        'labels'             => $testimonials_labels,
        'description'        => __( 'Description.', 'aqualuxe' ),
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

    // Register Testimonial Category Taxonomy
    $testimonial_cat_labels = array(
        'name'              => _x( 'Testimonial Categories', 'taxonomy general name', 'aqualuxe' ),
        'singular_name'     => _x( 'Testimonial Category', 'taxonomy singular name', 'aqualuxe' ),
        'search_items'      => __( 'Search Testimonial Categories', 'aqualuxe' ),
        'all_items'         => __( 'All Testimonial Categories', 'aqualuxe' ),
        'parent_item'       => __( 'Parent Testimonial Category', 'aqualuxe' ),
        'parent_item_colon' => __( 'Parent Testimonial Category:', 'aqualuxe' ),
        'edit_item'         => __( 'Edit Testimonial Category', 'aqualuxe' ),
        'update_item'       => __( 'Update Testimonial Category', 'aqualuxe' ),
        'add_new_item'      => __( 'Add New Testimonial Category', 'aqualuxe' ),
        'new_item_name'     => __( 'New Testimonial Category Name', 'aqualuxe' ),
        'menu_name'         => __( 'Categories', 'aqualuxe' ),
    );

    $testimonial_cat_args = array(
        'hierarchical'      => true,
        'labels'            => $testimonial_cat_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'testimonial-category' ),
        'show_in_rest'      => true,
    );

    register_taxonomy( 'testimonial_category', array( 'testimonial' ), $testimonial_cat_args );

    // Team Members Custom Post Type
    $team_labels = array(
        'name'               => _x( 'Team Members', 'post type general name', 'aqualuxe' ),
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
    );

    $team_args = array(
        'labels'             => $team_labels,
        'description'        => __( 'Description.', 'aqualuxe' ),
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
        'supports'           => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
        'show_in_rest'       => true,
    );

    register_post_type( 'team', $team_args );

    // Register Team Department Taxonomy
    $team_dept_labels = array(
        'name'              => _x( 'Departments', 'taxonomy general name', 'aqualuxe' ),
        'singular_name'     => _x( 'Department', 'taxonomy singular name', 'aqualuxe' ),
        'search_items'      => __( 'Search Departments', 'aqualuxe' ),
        'all_items'         => __( 'All Departments', 'aqualuxe' ),
        'parent_item'       => __( 'Parent Department', 'aqualuxe' ),
        'parent_item_colon' => __( 'Parent Department:', 'aqualuxe' ),
        'edit_item'         => __( 'Edit Department', 'aqualuxe' ),
        'update_item'       => __( 'Update Department', 'aqualuxe' ),
        'add_new_item'      => __( 'Add New Department', 'aqualuxe' ),
        'new_item_name'     => __( 'New Department Name', 'aqualuxe' ),
        'menu_name'         => __( 'Departments', 'aqualuxe' ),
    );

    $team_dept_args = array(
        'hierarchical'      => true,
        'labels'            => $team_dept_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'department' ),
        'show_in_rest'      => true,
    );

    register_taxonomy( 'department', array( 'team' ), $team_dept_args );

    // FAQ Custom Post Type
    $faq_labels = array(
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
    );

    $faq_args = array(
        'labels'             => $faq_labels,
        'description'        => __( 'Description.', 'aqualuxe' ),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'faq' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 24,
        'menu_icon'          => 'dashicons-editor-help',
        'supports'           => array( 'title', 'editor', 'custom-fields' ),
        'show_in_rest'       => true,
    );

    register_post_type( 'faq', $faq_args );

    // Register FAQ Category Taxonomy
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

    $faq_cat_args = array(
        'hierarchical'      => true,
        'labels'            => $faq_cat_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'faq-category' ),
        'show_in_rest'      => true,
    );

    register_taxonomy( 'faq_category', array( 'faq' ), $faq_cat_args );

    // Projects Custom Post Type
    $projects_labels = array(
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
    );

    $projects_args = array(
        'labels'             => $projects_labels,
        'description'        => __( 'Description.', 'aqualuxe' ),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'projects' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 25,
        'menu_icon'          => 'dashicons-portfolio',
        'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'custom-fields' ),
        'show_in_rest'       => true,
    );

    register_post_type( 'project', $projects_args );

    // Register Project Category Taxonomy
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

    $project_cat_args = array(
        'hierarchical'      => true,
        'labels'            => $project_cat_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'project-category' ),
        'show_in_rest'      => true,
    );

    register_taxonomy( 'project_category', array( 'project' ), $project_cat_args );

    // Register Project Tags Taxonomy
    $project_tag_labels = array(
        'name'              => _x( 'Project Tags', 'taxonomy general name', 'aqualuxe' ),
        'singular_name'     => _x( 'Project Tag', 'taxonomy singular name', 'aqualuxe' ),
        'search_items'      => __( 'Search Project Tags', 'aqualuxe' ),
        'all_items'         => __( 'All Project Tags', 'aqualuxe' ),
        'parent_item'       => __( 'Parent Project Tag', 'aqualuxe' ),
        'parent_item_colon' => __( 'Parent Project Tag:', 'aqualuxe' ),
        'edit_item'         => __( 'Edit Project Tag', 'aqualuxe' ),
        'update_item'       => __( 'Update Project Tag', 'aqualuxe' ),
        'add_new_item'      => __( 'Add New Project Tag', 'aqualuxe' ),
        'new_item_name'     => __( 'New Project Tag Name', 'aqualuxe' ),
        'menu_name'         => __( 'Tags', 'aqualuxe' ),
    );

    $project_tag_args = array(
        'hierarchical'      => false,
        'labels'            => $project_tag_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'project-tag' ),
        'show_in_rest'      => true,
    );

    register_taxonomy( 'project_tag', array( 'project' ), $project_tag_args );
}
add_action( 'init', 'aqualuxe_register_post_types' );

/**
 * Add custom meta boxes for post types
 */
function aqualuxe_add_meta_boxes() {
    // Service Meta Box
    add_meta_box(
        'aqualuxe_service_details',
        __( 'Service Details', 'aqualuxe' ),
        'aqualuxe_service_details_callback',
        'service',
        'normal',
        'high'
    );

    // Event Meta Box
    add_meta_box(
        'aqualuxe_event_details',
        __( 'Event Details', 'aqualuxe' ),
        'aqualuxe_event_details_callback',
        'event',
        'normal',
        'high'
    );

    // Testimonial Meta Box
    add_meta_box(
        'aqualuxe_testimonial_details',
        __( 'Testimonial Details', 'aqualuxe' ),
        'aqualuxe_testimonial_details_callback',
        'testimonial',
        'normal',
        'high'
    );

    // Team Member Meta Box
    add_meta_box(
        'aqualuxe_team_details',
        __( 'Team Member Details', 'aqualuxe' ),
        'aqualuxe_team_details_callback',
        'team',
        'normal',
        'high'
    );

    // Project Meta Box
    add_meta_box(
        'aqualuxe_project_details',
        __( 'Project Details', 'aqualuxe' ),
        'aqualuxe_project_details_callback',
        'project',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'aqualuxe_add_meta_boxes' );

/**
 * Service Details Meta Box Callback
 */
function aqualuxe_service_details_callback( $post ) {
    wp_nonce_field( 'aqualuxe_service_details', 'aqualuxe_service_details_nonce' );

    $service_price = get_post_meta( $post->ID, '_aqualuxe_service_price', true );
    $service_duration = get_post_meta( $post->ID, '_aqualuxe_service_duration', true );
    $service_icon = get_post_meta( $post->ID, '_aqualuxe_service_icon', true );
    $service_features = get_post_meta( $post->ID, '_aqualuxe_service_features', true );
    ?>
    <div class="aqualuxe-meta-box">
        <p>
            <label for="aqualuxe_service_price"><?php esc_html_e( 'Service Price', 'aqualuxe' ); ?></label>
            <input type="text" id="aqualuxe_service_price" name="aqualuxe_service_price" value="<?php echo esc_attr( $service_price ); ?>" class="widefat" />
            <span class="description"><?php esc_html_e( 'Enter the service price (e.g. $99, $99-$199, or "Starting at $99")', 'aqualuxe' ); ?></span>
        </p>
        <p>
            <label for="aqualuxe_service_duration"><?php esc_html_e( 'Service Duration', 'aqualuxe' ); ?></label>
            <input type="text" id="aqualuxe_service_duration" name="aqualuxe_service_duration" value="<?php echo esc_attr( $service_duration ); ?>" class="widefat" />
            <span class="description"><?php esc_html_e( 'Enter the service duration (e.g. 1 hour, 2-3 hours, etc.)', 'aqualuxe' ); ?></span>
        </p>
        <p>
            <label for="aqualuxe_service_icon"><?php esc_html_e( 'Service Icon', 'aqualuxe' ); ?></label>
            <input type="text" id="aqualuxe_service_icon" name="aqualuxe_service_icon" value="<?php echo esc_attr( $service_icon ); ?>" class="widefat" />
            <span class="description"><?php esc_html_e( 'Enter the service icon class (e.g. "fa-fish" for Font Awesome)', 'aqualuxe' ); ?></span>
        </p>
        <p>
            <label for="aqualuxe_service_features"><?php esc_html_e( 'Service Features', 'aqualuxe' ); ?></label>
            <textarea id="aqualuxe_service_features" name="aqualuxe_service_features" class="widefat" rows="5"><?php echo esc_textarea( $service_features ); ?></textarea>
            <span class="description"><?php esc_html_e( 'Enter service features, one per line', 'aqualuxe' ); ?></span>
        </p>
    </div>
    <?php
}

/**
 * Event Details Meta Box Callback
 */
function aqualuxe_event_details_callback( $post ) {
    wp_nonce_field( 'aqualuxe_event_details', 'aqualuxe_event_details_nonce' );

    $event_date = get_post_meta( $post->ID, '_aqualuxe_event_date', true );
    $event_time = get_post_meta( $post->ID, '_aqualuxe_event_time', true );
    $event_location = get_post_meta( $post->ID, '_aqualuxe_event_location', true );
    $event_price = get_post_meta( $post->ID, '_aqualuxe_event_price', true );
    $event_registration_url = get_post_meta( $post->ID, '_aqualuxe_event_registration_url', true );
    ?>
    <div class="aqualuxe-meta-box">
        <p>
            <label for="aqualuxe_event_date"><?php esc_html_e( 'Event Date', 'aqualuxe' ); ?></label>
            <input type="date" id="aqualuxe_event_date" name="aqualuxe_event_date" value="<?php echo esc_attr( $event_date ); ?>" class="widefat" />
        </p>
        <p>
            <label for="aqualuxe_event_time"><?php esc_html_e( 'Event Time', 'aqualuxe' ); ?></label>
            <input type="text" id="aqualuxe_event_time" name="aqualuxe_event_time" value="<?php echo esc_attr( $event_time ); ?>" class="widefat" />
            <span class="description"><?php esc_html_e( 'Enter the event time (e.g. 6:00 PM - 9:00 PM)', 'aqualuxe' ); ?></span>
        </p>
        <p>
            <label for="aqualuxe_event_location"><?php esc_html_e( 'Event Location', 'aqualuxe' ); ?></label>
            <input type="text" id="aqualuxe_event_location" name="aqualuxe_event_location" value="<?php echo esc_attr( $event_location ); ?>" class="widefat" />
        </p>
        <p>
            <label for="aqualuxe_event_price"><?php esc_html_e( 'Event Price', 'aqualuxe' ); ?></label>
            <input type="text" id="aqualuxe_event_price" name="aqualuxe_event_price" value="<?php echo esc_attr( $event_price ); ?>" class="widefat" />
            <span class="description"><?php esc_html_e( 'Enter the event price (e.g. $25, Free, etc.)', 'aqualuxe' ); ?></span>
        </p>
        <p>
            <label for="aqualuxe_event_registration_url"><?php esc_html_e( 'Registration URL', 'aqualuxe' ); ?></label>
            <input type="url" id="aqualuxe_event_registration_url" name="aqualuxe_event_registration_url" value="<?php echo esc_url( $event_registration_url ); ?>" class="widefat" />
            <span class="description"><?php esc_html_e( 'Enter the URL for event registration', 'aqualuxe' ); ?></span>
        </p>
    </div>
    <?php
}

/**
 * Testimonial Details Meta Box Callback
 */
function aqualuxe_testimonial_details_callback( $post ) {
    wp_nonce_field( 'aqualuxe_testimonial_details', 'aqualuxe_testimonial_details_nonce' );

    $testimonial_name = get_post_meta( $post->ID, '_aqualuxe_testimonial_name', true );
    $testimonial_position = get_post_meta( $post->ID, '_aqualuxe_testimonial_position', true );
    $testimonial_company = get_post_meta( $post->ID, '_aqualuxe_testimonial_company', true );
    $testimonial_rating = get_post_meta( $post->ID, '_aqualuxe_testimonial_rating', true );
    ?>
    <div class="aqualuxe-meta-box">
        <p>
            <label for="aqualuxe_testimonial_name"><?php esc_html_e( 'Client Name', 'aqualuxe' ); ?></label>
            <input type="text" id="aqualuxe_testimonial_name" name="aqualuxe_testimonial_name" value="<?php echo esc_attr( $testimonial_name ); ?>" class="widefat" />
        </p>
        <p>
            <label for="aqualuxe_testimonial_position"><?php esc_html_e( 'Client Position', 'aqualuxe' ); ?></label>
            <input type="text" id="aqualuxe_testimonial_position" name="aqualuxe_testimonial_position" value="<?php echo esc_attr( $testimonial_position ); ?>" class="widefat" />
        </p>
        <p>
            <label for="aqualuxe_testimonial_company"><?php esc_html_e( 'Client Company', 'aqualuxe' ); ?></label>
            <input type="text" id="aqualuxe_testimonial_company" name="aqualuxe_testimonial_company" value="<?php echo esc_attr( $testimonial_company ); ?>" class="widefat" />
        </p>
        <p>
            <label for="aqualuxe_testimonial_rating"><?php esc_html_e( 'Rating (1-5)', 'aqualuxe' ); ?></label>
            <select id="aqualuxe_testimonial_rating" name="aqualuxe_testimonial_rating" class="widefat">
                <option value=""><?php esc_html_e( 'Select Rating', 'aqualuxe' ); ?></option>
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
    </div>
    <?php
}

/**
 * Team Member Details Meta Box Callback
 */
function aqualuxe_team_details_callback( $post ) {
    wp_nonce_field( 'aqualuxe_team_details', 'aqualuxe_team_details_nonce' );

    $team_position = get_post_meta( $post->ID, '_aqualuxe_team_position', true );
    $team_email = get_post_meta( $post->ID, '_aqualuxe_team_email', true );
    $team_phone = get_post_meta( $post->ID, '_aqualuxe_team_phone', true );
    $team_facebook = get_post_meta( $post->ID, '_aqualuxe_team_facebook', true );
    $team_twitter = get_post_meta( $post->ID, '_aqualuxe_team_twitter', true );
    $team_linkedin = get_post_meta( $post->ID, '_aqualuxe_team_linkedin', true );
    $team_instagram = get_post_meta( $post->ID, '_aqualuxe_team_instagram', true );
    ?>
    <div class="aqualuxe-meta-box">
        <p>
            <label for="aqualuxe_team_position"><?php esc_html_e( 'Position', 'aqualuxe' ); ?></label>
            <input type="text" id="aqualuxe_team_position" name="aqualuxe_team_position" value="<?php echo esc_attr( $team_position ); ?>" class="widefat" />
        </p>
        <p>
            <label for="aqualuxe_team_email"><?php esc_html_e( 'Email', 'aqualuxe' ); ?></label>
            <input type="email" id="aqualuxe_team_email" name="aqualuxe_team_email" value="<?php echo esc_attr( $team_email ); ?>" class="widefat" />
        </p>
        <p>
            <label for="aqualuxe_team_phone"><?php esc_html_e( 'Phone', 'aqualuxe' ); ?></label>
            <input type="text" id="aqualuxe_team_phone" name="aqualuxe_team_phone" value="<?php echo esc_attr( $team_phone ); ?>" class="widefat" />
        </p>
        <h4><?php esc_html_e( 'Social Media', 'aqualuxe' ); ?></h4>
        <p>
            <label for="aqualuxe_team_facebook"><?php esc_html_e( 'Facebook URL', 'aqualuxe' ); ?></label>
            <input type="url" id="aqualuxe_team_facebook" name="aqualuxe_team_facebook" value="<?php echo esc_url( $team_facebook ); ?>" class="widefat" />
        </p>
        <p>
            <label for="aqualuxe_team_twitter"><?php esc_html_e( 'Twitter URL', 'aqualuxe' ); ?></label>
            <input type="url" id="aqualuxe_team_twitter" name="aqualuxe_team_twitter" value="<?php echo esc_url( $team_twitter ); ?>" class="widefat" />
        </p>
        <p>
            <label for="aqualuxe_team_linkedin"><?php esc_html_e( 'LinkedIn URL', 'aqualuxe' ); ?></label>
            <input type="url" id="aqualuxe_team_linkedin" name="aqualuxe_team_linkedin" value="<?php echo esc_url( $team_linkedin ); ?>" class="widefat" />
        </p>
        <p>
            <label for="aqualuxe_team_instagram"><?php esc_html_e( 'Instagram URL', 'aqualuxe' ); ?></label>
            <input type="url" id="aqualuxe_team_instagram" name="aqualuxe_team_instagram" value="<?php echo esc_url( $team_instagram ); ?>" class="widefat" />
        </p>
    </div>
    <?php
}

/**
 * Project Details Meta Box Callback
 */
function aqualuxe_project_details_callback( $post ) {
    wp_nonce_field( 'aqualuxe_project_details', 'aqualuxe_project_details_nonce' );

    $project_client = get_post_meta( $post->ID, '_aqualuxe_project_client', true );
    $project_location = get_post_meta( $post->ID, '_aqualuxe_project_location', true );
    $project_date = get_post_meta( $post->ID, '_aqualuxe_project_date', true );
    $project_url = get_post_meta( $post->ID, '_aqualuxe_project_url', true );
    $project_gallery = get_post_meta( $post->ID, '_aqualuxe_project_gallery', true );
    ?>
    <div class="aqualuxe-meta-box">
        <p>
            <label for="aqualuxe_project_client"><?php esc_html_e( 'Client', 'aqualuxe' ); ?></label>
            <input type="text" id="aqualuxe_project_client" name="aqualuxe_project_client" value="<?php echo esc_attr( $project_client ); ?>" class="widefat" />
        </p>
        <p>
            <label for="aqualuxe_project_location"><?php esc_html_e( 'Location', 'aqualuxe' ); ?></label>
            <input type="text" id="aqualuxe_project_location" name="aqualuxe_project_location" value="<?php echo esc_attr( $project_location ); ?>" class="widefat" />
        </p>
        <p>
            <label for="aqualuxe_project_date"><?php esc_html_e( 'Completion Date', 'aqualuxe' ); ?></label>
            <input type="date" id="aqualuxe_project_date" name="aqualuxe_project_date" value="<?php echo esc_attr( $project_date ); ?>" class="widefat" />
        </p>
        <p>
            <label for="aqualuxe_project_url"><?php esc_html_e( 'Project URL', 'aqualuxe' ); ?></label>
            <input type="url" id="aqualuxe_project_url" name="aqualuxe_project_url" value="<?php echo esc_url( $project_url ); ?>" class="widefat" />
        </p>
        <p>
            <label for="aqualuxe_project_gallery"><?php esc_html_e( 'Gallery Image IDs', 'aqualuxe' ); ?></label>
            <input type="text" id="aqualuxe_project_gallery" name="aqualuxe_project_gallery" value="<?php echo esc_attr( $project_gallery ); ?>" class="widefat" />
            <span class="description"><?php esc_html_e( 'Enter image IDs separated by commas (e.g. 42,56,78)', 'aqualuxe' ); ?></span>
        </p>
        <p>
            <button type="button" class="button" id="aqualuxe_project_gallery_button"><?php esc_html_e( 'Select Images', 'aqualuxe' ); ?></button>
        </p>
    </div>
    <script>
        jQuery(document).ready(function($) {
            $('#aqualuxe_project_gallery_button').click(function(e) {
                e.preventDefault();
                
                var galleryFrame = wp.media({
                    title: '<?php esc_html_e( 'Select Project Gallery Images', 'aqualuxe' ); ?>',
                    button: {
                        text: '<?php esc_html_e( 'Add to Gallery', 'aqualuxe' ); ?>'
                    },
                    multiple: true
                });
                
                galleryFrame.on('select', function() {
                    var attachment = galleryFrame.state().get('selection').map(function(attachment) {
                        attachment = attachment.toJSON();
                        return attachment.id;
                    });
                    
                    $('#aqualuxe_project_gallery').val(attachment.join(','));
                });
                
                galleryFrame.open();
            });
        });
    </script>
    <?php
}

/**
 * Save post meta when the post is saved
 */
function aqualuxe_save_post_meta( $post_id ) {
    // Check if our nonce is set for the different post types
    $nonces = array(
        'aqualuxe_service_details_nonce'     => 'aqualuxe_service_details',
        'aqualuxe_event_details_nonce'       => 'aqualuxe_event_details',
        'aqualuxe_testimonial_details_nonce' => 'aqualuxe_testimonial_details',
        'aqualuxe_team_details_nonce'        => 'aqualuxe_team_details',
        'aqualuxe_project_details_nonce'     => 'aqualuxe_project_details',
    );

    // Check if this is an autosave
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // Check the user's permissions
    if ( isset( $_POST['post_type'] ) ) {
        if ( 'page' === $_POST['post_type'] ) {
            if ( ! current_user_can( 'edit_page', $post_id ) ) {
                return;
            }
        } else {
            if ( ! current_user_can( 'edit_post', $post_id ) ) {
                return;
            }
        }
    }

    // Loop through each nonce and verify it
    foreach ( $nonces as $nonce_field => $nonce_action ) {
        if ( isset( $_POST[ $nonce_field ] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST[ $nonce_field ] ) ), $nonce_action ) ) {
            // Process the specific post type meta
            switch ( $nonce_action ) {
                case 'aqualuxe_service_details':
                    aqualuxe_save_service_meta( $post_id );
                    break;
                case 'aqualuxe_event_details':
                    aqualuxe_save_event_meta( $post_id );
                    break;
                case 'aqualuxe_testimonial_details':
                    aqualuxe_save_testimonial_meta( $post_id );
                    break;
                case 'aqualuxe_team_details':
                    aqualuxe_save_team_meta( $post_id );
                    break;
                case 'aqualuxe_project_details':
                    aqualuxe_save_project_meta( $post_id );
                    break;
            }
        }
    }
}
add_action( 'save_post', 'aqualuxe_save_post_meta' );

/**
 * Save service meta
 */
function aqualuxe_save_service_meta( $post_id ) {
    if ( isset( $_POST['aqualuxe_service_price'] ) ) {
        update_post_meta( $post_id, '_aqualuxe_service_price', sanitize_text_field( wp_unslash( $_POST['aqualuxe_service_price'] ) ) );
    }
    if ( isset( $_POST['aqualuxe_service_duration'] ) ) {
        update_post_meta( $post_id, '_aqualuxe_service_duration', sanitize_text_field( wp_unslash( $_POST['aqualuxe_service_duration'] ) ) );
    }
    if ( isset( $_POST['aqualuxe_service_icon'] ) ) {
        update_post_meta( $post_id, '_aqualuxe_service_icon', sanitize_text_field( wp_unslash( $_POST['aqualuxe_service_icon'] ) ) );
    }
    if ( isset( $_POST['aqualuxe_service_features'] ) ) {
        update_post_meta( $post_id, '_aqualuxe_service_features', sanitize_textarea_field( wp_unslash( $_POST['aqualuxe_service_features'] ) ) );
    }
}

/**
 * Save event meta
 */
function aqualuxe_save_event_meta( $post_id ) {
    if ( isset( $_POST['aqualuxe_event_date'] ) ) {
        update_post_meta( $post_id, '_aqualuxe_event_date', sanitize_text_field( wp_unslash( $_POST['aqualuxe_event_date'] ) ) );
    }
    if ( isset( $_POST['aqualuxe_event_time'] ) ) {
        update_post_meta( $post_id, '_aqualuxe_event_time', sanitize_text_field( wp_unslash( $_POST['aqualuxe_event_time'] ) ) );
    }
    if ( isset( $_POST['aqualuxe_event_location'] ) ) {
        update_post_meta( $post_id, '_aqualuxe_event_location', sanitize_text_field( wp_unslash( $_POST['aqualuxe_event_location'] ) ) );
    }
    if ( isset( $_POST['aqualuxe_event_price'] ) ) {
        update_post_meta( $post_id, '_aqualuxe_event_price', sanitize_text_field( wp_unslash( $_POST['aqualuxe_event_price'] ) ) );
    }
    if ( isset( $_POST['aqualuxe_event_registration_url'] ) ) {
        update_post_meta( $post_id, '_aqualuxe_event_registration_url', esc_url_raw( wp_unslash( $_POST['aqualuxe_event_registration_url'] ) ) );
    }
}

/**
 * Save testimonial meta
 */
function aqualuxe_save_testimonial_meta( $post_id ) {
    if ( isset( $_POST['aqualuxe_testimonial_name'] ) ) {
        update_post_meta( $post_id, '_aqualuxe_testimonial_name', sanitize_text_field( wp_unslash( $_POST['aqualuxe_testimonial_name'] ) ) );
    }
    if ( isset( $_POST['aqualuxe_testimonial_position'] ) ) {
        update_post_meta( $post_id, '_aqualuxe_testimonial_position', sanitize_text_field( wp_unslash( $_POST['aqualuxe_testimonial_position'] ) ) );
    }
    if ( isset( $_POST['aqualuxe_testimonial_company'] ) ) {
        update_post_meta( $post_id, '_aqualuxe_testimonial_company', sanitize_text_field( wp_unslash( $_POST['aqualuxe_testimonial_company'] ) ) );
    }
    if ( isset( $_POST['aqualuxe_testimonial_rating'] ) ) {
        update_post_meta( $post_id, '_aqualuxe_testimonial_rating', sanitize_text_field( wp_unslash( $_POST['aqualuxe_testimonial_rating'] ) ) );
    }
}

/**
 * Save team meta
 */
function aqualuxe_save_team_meta( $post_id ) {
    if ( isset( $_POST['aqualuxe_team_position'] ) ) {
        update_post_meta( $post_id, '_aqualuxe_team_position', sanitize_text_field( wp_unslash( $_POST['aqualuxe_team_position'] ) ) );
    }
    if ( isset( $_POST['aqualuxe_team_email'] ) ) {
        update_post_meta( $post_id, '_aqualuxe_team_email', sanitize_email( wp_unslash( $_POST['aqualuxe_team_email'] ) ) );
    }
    if ( isset( $_POST['aqualuxe_team_phone'] ) ) {
        update_post_meta( $post_id, '_aqualuxe_team_phone', sanitize_text_field( wp_unslash( $_POST['aqualuxe_team_phone'] ) ) );
    }
    if ( isset( $_POST['aqualuxe_team_facebook'] ) ) {
        update_post_meta( $post_id, '_aqualuxe_team_facebook', esc_url_raw( wp_unslash( $_POST['aqualuxe_team_facebook'] ) ) );
    }
    if ( isset( $_POST['aqualuxe_team_twitter'] ) ) {
        update_post_meta( $post_id, '_aqualuxe_team_twitter', esc_url_raw( wp_unslash( $_POST['aqualuxe_team_twitter'] ) ) );
    }
    if ( isset( $_POST['aqualuxe_team_linkedin'] ) ) {
        update_post_meta( $post_id, '_aqualuxe_team_linkedin', esc_url_raw( wp_unslash( $_POST['aqualuxe_team_linkedin'] ) ) );
    }
    if ( isset( $_POST['aqualuxe_team_instagram'] ) ) {
        update_post_meta( $post_id, '_aqualuxe_team_instagram', esc_url_raw( wp_unslash( $_POST['aqualuxe_team_instagram'] ) ) );
    }
}

/**
 * Save project meta
 */
function aqualuxe_save_project_meta( $post_id ) {
    if ( isset( $_POST['aqualuxe_project_client'] ) ) {
        update_post_meta( $post_id, '_aqualuxe_project_client', sanitize_text_field( wp_unslash( $_POST['aqualuxe_project_client'] ) ) );
    }
    if ( isset( $_POST['aqualuxe_project_location'] ) ) {
        update_post_meta( $post_id, '_aqualuxe_project_location', sanitize_text_field( wp_unslash( $_POST['aqualuxe_project_location'] ) ) );
    }
    if ( isset( $_POST['aqualuxe_project_date'] ) ) {
        update_post_meta( $post_id, '_aqualuxe_project_date', sanitize_text_field( wp_unslash( $_POST['aqualuxe_project_date'] ) ) );
    }
    if ( isset( $_POST['aqualuxe_project_url'] ) ) {
        update_post_meta( $post_id, '_aqualuxe_project_url', esc_url_raw( wp_unslash( $_POST['aqualuxe_project_url'] ) ) );
    }
    if ( isset( $_POST['aqualuxe_project_gallery'] ) ) {
        update_post_meta( $post_id, '_aqualuxe_project_gallery', sanitize_text_field( wp_unslash( $_POST['aqualuxe_project_gallery'] ) ) );
    }
}

/**
 * Add admin column for featured image
 */
function aqualuxe_add_thumbnail_column( $columns ) {
    $new_columns = array();
    foreach ( $columns as $key => $value ) {
        if ( $key === 'title' ) {
            $new_columns[ $key ] = $value;
            $new_columns['thumbnail'] = __( 'Featured Image', 'aqualuxe' );
        } else {
            $new_columns[ $key ] = $value;
        }
    }
    return $new_columns;
}
add_filter( 'manage_service_posts_columns', 'aqualuxe_add_thumbnail_column' );
add_filter( 'manage_event_posts_columns', 'aqualuxe_add_thumbnail_column' );
add_filter( 'manage_testimonial_posts_columns', 'aqualuxe_add_thumbnail_column' );
add_filter( 'manage_team_posts_columns', 'aqualuxe_add_thumbnail_column' );
add_filter( 'manage_project_posts_columns', 'aqualuxe_add_thumbnail_column' );

/**
 * Display featured image in admin column
 */
function aqualuxe_display_thumbnail_column( $column, $post_id ) {
    if ( 'thumbnail' === $column ) {
        if ( has_post_thumbnail( $post_id ) ) {
            echo '<img src="' . esc_url( get_the_post_thumbnail_url( $post_id, 'thumbnail' ) ) . '" width="50" height="50" />';
        } else {
            echo '<span aria-hidden="true">—</span>';
        }
    }
}
add_action( 'manage_service_posts_custom_column', 'aqualuxe_display_thumbnail_column', 10, 2 );
add_action( 'manage_event_posts_custom_column', 'aqualuxe_display_thumbnail_column', 10, 2 );
add_action( 'manage_testimonial_posts_custom_column', 'aqualuxe_display_thumbnail_column', 10, 2 );
add_action( 'manage_team_posts_custom_column', 'aqualuxe_display_thumbnail_column', 10, 2 );
add_action( 'manage_project_posts_custom_column', 'aqualuxe_display_thumbnail_column', 10, 2 );

/**
 * Add custom columns to post types
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
            $columns['testimonial_name'] = __( 'Client', 'aqualuxe' );
            $columns['testimonial_rating'] = __( 'Rating', 'aqualuxe' );
            break;
        case 'team':
            $columns['team_position'] = __( 'Position', 'aqualuxe' );
            $columns['team_department'] = __( 'Department', 'aqualuxe' );
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
function aqualuxe_display_custom_column_content( $column, $post_id ) {
    switch ( $column ) {
        case 'service_price':
            echo esc_html( get_post_meta( $post_id, '_aqualuxe_service_price', true ) );
            break;
        case 'service_duration':
            echo esc_html( get_post_meta( $post_id, '_aqualuxe_service_duration', true ) );
            break;
        case 'event_date':
            $date = get_post_meta( $post_id, '_aqualuxe_event_date', true );
            if ( $date ) {
                echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $date ) ) );
            }
            break;
        case 'event_location':
            echo esc_html( get_post_meta( $post_id, '_aqualuxe_event_location', true ) );
            break;
        case 'testimonial_name':
            echo esc_html( get_post_meta( $post_id, '_aqualuxe_testimonial_name', true ) );
            break;
        case 'testimonial_rating':
            $rating = get_post_meta( $post_id, '_aqualuxe_testimonial_rating', true );
            if ( $rating ) {
                $stars = '';
                $full_stars = floor( $rating );
                $half_star = ( $rating - $full_stars ) >= 0.5;
                
                for ( $i = 1; $i <= 5; $i++ ) {
                    if ( $i <= $full_stars ) {
                        $stars .= '★';
                    } elseif ( $i === $full_stars + 1 && $half_star ) {
                        $stars .= '½';
                    } else {
                        $stars .= '☆';
                    }
                }
                
                echo esc_html( $stars . ' (' . $rating . ')' );
            }
            break;
        case 'team_position':
            echo esc_html( get_post_meta( $post_id, '_aqualuxe_team_position', true ) );
            break;
        case 'team_department':
            $terms = get_the_terms( $post_id, 'department' );
            if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
                $department_names = array();
                foreach ( $terms as $term ) {
                    $department_names[] = $term->name;
                }
                echo esc_html( implode( ', ', $department_names ) );
            }
            break;
        case 'project_client':
            echo esc_html( get_post_meta( $post_id, '_aqualuxe_project_client', true ) );
            break;
        case 'project_date':
            $date = get_post_meta( $post_id, '_aqualuxe_project_date', true );
            if ( $date ) {
                echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $date ) ) );
            }
            break;
    }
}
add_action( 'manage_posts_custom_column', 'aqualuxe_display_custom_column_content', 10, 2 );

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
add_filter( 'manage_edit-service_sortable_columns', 'aqualuxe_sortable_columns' );
add_filter( 'manage_edit-event_sortable_columns', 'aqualuxe_sortable_columns' );
add_filter( 'manage_edit-testimonial_sortable_columns', 'aqualuxe_sortable_columns' );
add_filter( 'manage_edit-project_sortable_columns', 'aqualuxe_sortable_columns' );

/**
 * Sort custom columns
 */
function aqualuxe_sort_custom_columns( $query ) {
    if ( ! is_admin() ) {
        return;
    }
    
    $orderby = $query->get( 'orderby' );
    
    switch ( $orderby ) {
        case 'service_price':
            $query->set( 'meta_key', '_aqualuxe_service_price' );
            $query->set( 'orderby', 'meta_value' );
            break;
        case 'event_date':
            $query->set( 'meta_key', '_aqualuxe_event_date' );
            $query->set( 'orderby', 'meta_value' );
            break;
        case 'testimonial_rating':
            $query->set( 'meta_key', '_aqualuxe_testimonial_rating' );
            $query->set( 'orderby', 'meta_value_num' );
            break;
        case 'project_date':
            $query->set( 'meta_key', '_aqualuxe_project_date' );
            $query->set( 'orderby', 'meta_value' );
            break;
    }
}
add_action( 'pre_get_posts', 'aqualuxe_sort_custom_columns' );

/**
 * Add custom post type to main query
 */
function aqualuxe_add_cpt_to_query( $query ) {
    if ( is_home() && $query->is_main_query() ) {
        $post_types = array( 'post' );
        
        // Check if we should include services in the blog
        if ( get_theme_mod( 'aqualuxe_show_services_in_blog', false ) ) {
            $post_types[] = 'service';
        }
        
        // Check if we should include events in the blog
        if ( get_theme_mod( 'aqualuxe_show_events_in_blog', false ) ) {
            $post_types[] = 'event';
        }
        
        // Check if we should include projects in the blog
        if ( get_theme_mod( 'aqualuxe_show_projects_in_blog', false ) ) {
            $post_types[] = 'project';
        }
        
        $query->set( 'post_type', $post_types );
    }
}
add_action( 'pre_get_posts', 'aqualuxe_add_cpt_to_query' );

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
            'count'     => 3,
            'category'  => '',
            'orderby'   => 'date',
            'order'     => 'DESC',
            'layout'    => 'grid',
            'columns'   => 3,
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
        echo '<div class="aqualuxe-services aqualuxe-services-' . esc_attr( $atts['layout'] ) . ' grid grid-cols-1 md:grid-cols-' . esc_attr( $atts['columns'] ) . ' gap-6">';
        
        while ( $services->have_posts() ) {
            $services->the_post();
            
            $service_price = get_post_meta( get_the_ID(), '_aqualuxe_service_price', true );
            $service_duration = get_post_meta( get_the_ID(), '_aqualuxe_service_duration', true );
            $service_icon = get_post_meta( get_the_ID(), '_aqualuxe_service_icon', true );
            
            echo '<div class="aqualuxe-service bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden transition-shadow duration-300 hover:shadow-lg">';
            
            if ( has_post_thumbnail() ) {
                echo '<a href="' . esc_url( get_permalink() ) . '" class="block">';
                the_post_thumbnail( 'medium', array( 'class' => 'w-full h-48 object-cover' ) );
                echo '</a>';
            }
            
            echo '<div class="p-6">';
            
            if ( ! empty( $service_icon ) ) {
                echo '<div class="service-icon mb-4 text-primary-600 dark:text-primary-400">';
                echo '<i class="' . esc_attr( $service_icon ) . ' text-3xl"></i>';
                echo '</div>';
            }
            
            echo '<h3 class="text-xl font-bold mb-2">';
            echo '<a href="' . esc_url( get_permalink() ) . '" class="text-gray-900 dark:text-gray-100 hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-300">';
            the_title();
            echo '</a>';
            echo '</h3>';
            
            echo '<div class="service-meta flex flex-wrap gap-4 mb-4 text-sm text-gray-600 dark:text-gray-400">';
            
            if ( ! empty( $service_price ) ) {
                echo '<div class="service-price">';
                echo '<span class="font-medium">' . esc_html__( 'Price:', 'aqualuxe' ) . '</span> ';
                echo esc_html( $service_price );
                echo '</div>';
            }
            
            if ( ! empty( $service_duration ) ) {
                echo '<div class="service-duration">';
                echo '<span class="font-medium">' . esc_html__( 'Duration:', 'aqualuxe' ) . '</span> ';
                echo esc_html( $service_duration );
                echo '</div>';
            }
            
            echo '</div>';
            
            echo '<div class="service-excerpt text-gray-700 dark:text-gray-300 mb-4">';
            the_excerpt();
            echo '</div>';
            
            echo '<a href="' . esc_url( get_permalink() ) . '" class="inline-block bg-primary-600 hover:bg-primary-700 text-white font-medium py-2 px-4 rounded transition-colors duration-300">';
            esc_html_e( 'Learn More', 'aqualuxe' );
            echo '</a>';
            
            echo '</div>';
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
            'count'     => 3,
            'category'  => '',
            'orderby'   => 'meta_value',
            'meta_key'  => '_aqualuxe_event_date',
            'order'     => 'ASC',
            'layout'    => 'grid',
            'columns'   => 3,
            'upcoming'  => 'true',
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
    if ( 'true' === $atts['upcoming'] ) {
        $args['meta_query'] = array(
            array(
                'key'     => '_aqualuxe_event_date',
                'value'   => date( 'Y-m-d' ),
                'compare' => '>=',
                'type'    => 'DATE',
            ),
        );
    }
    
    $events = new WP_Query( $args );
    
    ob_start();
    
    if ( $events->have_posts() ) {
        echo '<div class="aqualuxe-events aqualuxe-events-' . esc_attr( $atts['layout'] ) . ' grid grid-cols-1 md:grid-cols-' . esc_attr( $atts['columns'] ) . ' gap-6">';
        
        while ( $events->have_posts() ) {
            $events->the_post();
            
            $event_date = get_post_meta( get_the_ID(), '_aqualuxe_event_date', true );
            $event_time = get_post_meta( get_the_ID(), '_aqualuxe_event_time', true );
            $event_location = get_post_meta( get_the_ID(), '_aqualuxe_event_location', true );
            $event_price = get_post_meta( get_the_ID(), '_aqualuxe_event_price', true );
            $event_registration_url = get_post_meta( get_the_ID(), '_aqualuxe_event_registration_url', true );
            
            echo '<div class="aqualuxe-event bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden transition-shadow duration-300 hover:shadow-lg">';
            
            if ( has_post_thumbnail() ) {
                echo '<a href="' . esc_url( get_permalink() ) . '" class="block">';
                the_post_thumbnail( 'medium', array( 'class' => 'w-full h-48 object-cover' ) );
                echo '</a>';
            }
            
            echo '<div class="p-6">';
            
            if ( ! empty( $event_date ) ) {
                echo '<div class="event-date mb-2 text-primary-600 dark:text-primary-400 font-semibold">';
                echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $event_date ) ) );
                
                if ( ! empty( $event_time ) ) {
                    echo ' <span class="event-time">' . esc_html( $event_time ) . '</span>';
                }
                
                echo '</div>';
            }
            
            echo '<h3 class="text-xl font-bold mb-2">';
            echo '<a href="' . esc_url( get_permalink() ) . '" class="text-gray-900 dark:text-gray-100 hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-300">';
            the_title();
            echo '</a>';
            echo '</h3>';
            
            echo '<div class="event-meta mb-4">';
            
            if ( ! empty( $event_location ) ) {
                echo '<div class="event-location text-gray-600 dark:text-gray-400 mb-1">';
                echo '<i class="fas fa-map-marker-alt mr-2"></i>';
                echo esc_html( $event_location );
                echo '</div>';
            }
            
            if ( ! empty( $event_price ) ) {
                echo '<div class="event-price text-gray-600 dark:text-gray-400">';
                echo '<i class="fas fa-ticket-alt mr-2"></i>';
                echo esc_html( $event_price );
                echo '</div>';
            }
            
            echo '</div>';
            
            echo '<div class="event-excerpt text-gray-700 dark:text-gray-300 mb-4">';
            the_excerpt();
            echo '</div>';
            
            echo '<div class="event-actions flex flex-wrap gap-3">';
            
            echo '<a href="' . esc_url( get_permalink() ) . '" class="inline-block bg-primary-600 hover:bg-primary-700 text-white font-medium py-2 px-4 rounded transition-colors duration-300">';
            esc_html_e( 'Learn More', 'aqualuxe' );
            echo '</a>';
            
            if ( ! empty( $event_registration_url ) ) {
                echo '<a href="' . esc_url( $event_registration_url ) . '" class="inline-block bg-accent-600 hover:bg-accent-700 text-white font-medium py-2 px-4 rounded transition-colors duration-300">';
                esc_html_e( 'Register', 'aqualuxe' );
                echo '</a>';
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

/**
 * Testimonials shortcode
 */
function aqualuxe_testimonials_shortcode( $atts ) {
    $atts = shortcode_atts(
        array(
            'count'     => 3,
            'category'  => '',
            'orderby'   => 'date',
            'order'     => 'DESC',
            'layout'    => 'grid',
            'columns'   => 3,
            'slider'    => 'false',
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
        $slider_class = 'true' === $atts['slider'] ? ' testimonial-slider' : '';
        
        echo '<div class="aqualuxe-testimonials aqualuxe-testimonials-' . esc_attr( $atts['layout'] ) . $slider_class . ' grid grid-cols-1 md:grid-cols-' . esc_attr( $atts['columns'] ) . ' gap-6">';
        
        while ( $testimonials->have_posts() ) {
            $testimonials->the_post();
            
            $testimonial_name = get_post_meta( get_the_ID(), '_aqualuxe_testimonial_name', true );
            $testimonial_position = get_post_meta( get_the_ID(), '_aqualuxe_testimonial_position', true );
            $testimonial_company = get_post_meta( get_the_ID(), '_aqualuxe_testimonial_company', true );
            $testimonial_rating = get_post_meta( get_the_ID(), '_aqualuxe_testimonial_rating', true );
            
            echo '<div class="aqualuxe-testimonial bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden transition-shadow duration-300 hover:shadow-lg p-6">';
            
            if ( ! empty( $testimonial_rating ) ) {
                echo '<div class="testimonial-rating mb-4 text-yellow-400">';
                
                $full_stars = floor( $testimonial_rating );
                $half_star = ( $testimonial_rating - $full_stars ) >= 0.5;
                
                for ( $i = 1; $i <= 5; $i++ ) {
                    if ( $i <= $full_stars ) {
                        echo '<i class="fas fa-star"></i>';
                    } elseif ( $i === $full_stars + 1 && $half_star ) {
                        echo '<i class="fas fa-star-half-alt"></i>';
                    } else {
                        echo '<i class="far fa-star"></i>';
                    }
                }
                
                echo '</div>';
            }
            
            echo '<div class="testimonial-content text-gray-700 dark:text-gray-300 mb-4">';
            echo '<i class="fas fa-quote-left text-gray-400 dark:text-gray-600 text-xl mr-2"></i>';
            the_content();
            echo '</div>';
            
            echo '<div class="testimonial-author flex items-center">';
            
            if ( has_post_thumbnail() ) {
                echo '<div class="testimonial-avatar mr-4">';
                the_post_thumbnail( 'thumbnail', array( 'class' => 'w-12 h-12 rounded-full' ) );
                echo '</div>';
            }
            
            echo '<div class="testimonial-info">';
            
            if ( ! empty( $testimonial_name ) ) {
                echo '<div class="testimonial-name font-bold text-gray-900 dark:text-gray-100">';
                echo esc_html( $testimonial_name );
                echo '</div>';
            }
            
            if ( ! empty( $testimonial_position ) || ! empty( $testimonial_company ) ) {
                echo '<div class="testimonial-meta text-sm text-gray-600 dark:text-gray-400">';
                
                if ( ! empty( $testimonial_position ) ) {
                    echo esc_html( $testimonial_position );
                    
                    if ( ! empty( $testimonial_company ) ) {
                        echo ', ';
                    }
                }
                
                if ( ! empty( $testimonial_company ) ) {
                    echo esc_html( $testimonial_company );
                }
                
                echo '</div>';
            }
            
            echo '</div>';
            echo '</div>';
            
            echo '</div>';
        }
        
        echo '</div>';
        
        if ( 'true' === $atts['slider'] ) {
            ?>
            <script>
                jQuery(document).ready(function($) {
                    $('.testimonial-slider').slick({
                        dots: true,
                        arrows: true,
                        infinite: true,
                        speed: 500,
                        slidesToShow: <?php echo esc_js( $atts['columns'] ); ?>,
                        slidesToScroll: 1,
                        autoplay: true,
                        autoplaySpeed: 5000,
                        responsive: [
                            {
                                breakpoint: 1024,
                                settings: {
                                    slidesToShow: 2,
                                    slidesToScroll: 1,
                                }
                            },
                            {
                                breakpoint: 640,
                                settings: {
                                    slidesToShow: 1,
                                    slidesToScroll: 1,
                                }
                            }
                        ]
                    });
                });
            </script>
            <?php
        }
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
            'department' => '',
            'orderby'    => 'menu_order',
            'order'      => 'ASC',
            'layout'     => 'grid',
            'columns'    => 4,
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
        echo '<div class="aqualuxe-team aqualuxe-team-' . esc_attr( $atts['layout'] ) . ' grid grid-cols-1 sm:grid-cols-2 md:grid-cols-' . esc_attr( $atts['columns'] ) . ' gap-6">';
        
        while ( $team->have_posts() ) {
            $team->the_post();
            
            $team_position = get_post_meta( get_the_ID(), '_aqualuxe_team_position', true );
            $team_email = get_post_meta( get_the_ID(), '_aqualuxe_team_email', true );
            $team_phone = get_post_meta( get_the_ID(), '_aqualuxe_team_phone', true );
            $team_facebook = get_post_meta( get_the_ID(), '_aqualuxe_team_facebook', true );
            $team_twitter = get_post_meta( get_the_ID(), '_aqualuxe_team_twitter', true );
            $team_linkedin = get_post_meta( get_the_ID(), '_aqualuxe_team_linkedin', true );
            $team_instagram = get_post_meta( get_the_ID(), '_aqualuxe_team_instagram', true );
            
            echo '<div class="aqualuxe-team-member bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden transition-shadow duration-300 hover:shadow-lg">';
            
            if ( has_post_thumbnail() ) {
                echo '<div class="team-image">';
                the_post_thumbnail( 'medium', array( 'class' => 'w-full h-64 object-cover' ) );
                echo '</div>';
            }
            
            echo '<div class="p-6">';
            
            echo '<h3 class="text-xl font-bold mb-1">';
            echo '<a href="' . esc_url( get_permalink() ) . '" class="text-gray-900 dark:text-gray-100 hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-300">';
            the_title();
            echo '</a>';
            echo '</h3>';
            
            if ( ! empty( $team_position ) ) {
                echo '<div class="team-position text-primary-600 dark:text-primary-400 mb-3">';
                echo esc_html( $team_position );
                echo '</div>';
            }
            
            echo '<div class="team-excerpt text-gray-700 dark:text-gray-300 mb-4">';
            the_excerpt();
            echo '</div>';
            
            echo '<div class="team-contact mb-4">';
            
            if ( ! empty( $team_email ) ) {
                echo '<div class="team-email text-sm text-gray-600 dark:text-gray-400 mb-1">';
                echo '<i class="fas fa-envelope mr-2"></i>';
                echo '<a href="mailto:' . esc_attr( $team_email ) . '" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-300">';
                echo esc_html( $team_email );
                echo '</a>';
                echo '</div>';
            }
            
            if ( ! empty( $team_phone ) ) {
                echo '<div class="team-phone text-sm text-gray-600 dark:text-gray-400">';
                echo '<i class="fas fa-phone mr-2"></i>';
                echo '<a href="tel:' . esc_attr( preg_replace( '/[^0-9+]/', '', $team_phone ) ) . '" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-300">';
                echo esc_html( $team_phone );
                echo '</a>';
                echo '</div>';
            }
            
            echo '</div>';
            
            echo '<div class="team-social flex space-x-3">';
            
            if ( ! empty( $team_facebook ) ) {
                echo '<a href="' . esc_url( $team_facebook ) . '" target="_blank" rel="noopener noreferrer" class="text-gray-600 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-400 transition-colors duration-300">';
                echo '<i class="fab fa-facebook-f"></i>';
                echo '</a>';
            }
            
            if ( ! empty( $team_twitter ) ) {
                echo '<a href="' . esc_url( $team_twitter ) . '" target="_blank" rel="noopener noreferrer" class="text-gray-600 hover:text-blue-400 dark:text-gray-400 dark:hover:text-blue-300 transition-colors duration-300">';
                echo '<i class="fab fa-twitter"></i>';
                echo '</a>';
            }
            
            if ( ! empty( $team_linkedin ) ) {
                echo '<a href="' . esc_url( $team_linkedin ) . '" target="_blank" rel="noopener noreferrer" class="text-gray-600 hover:text-blue-700 dark:text-gray-400 dark:hover:text-blue-500 transition-colors duration-300">';
                echo '<i class="fab fa-linkedin-in"></i>';
                echo '</a>';
            }
            
            if ( ! empty( $team_instagram ) ) {
                echo '<a href="' . esc_url( $team_instagram ) . '" target="_blank" rel="noopener noreferrer" class="text-gray-600 hover:text-pink-600 dark:text-gray-400 dark:hover:text-pink-400 transition-colors duration-300">';
                echo '<i class="fab fa-instagram"></i>';
                echo '</a>';
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

/**
 * Projects shortcode
 */
function aqualuxe_projects_shortcode( $atts ) {
    $atts = shortcode_atts(
        array(
            'count'     => 6,
            'category'  => '',
            'tag'       => '',
            'orderby'   => 'date',
            'order'     => 'DESC',
            'layout'    => 'grid',
            'columns'   => 3,
            'filter'    => 'true',
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
    
    if ( ! empty( $atts['tag'] ) ) {
        $tax_query[] = array(
            'taxonomy' => 'project_tag',
            'field'    => 'slug',
            'terms'    => explode( ',', $atts['tag'] ),
        );
    }
    
    if ( ! empty( $tax_query ) ) {
        $args['tax_query'] = $tax_query;
    }
    
    $projects = new WP_Query( $args );
    
    ob_start();
    
    if ( $projects->have_posts() ) {
        // Filter buttons
        if ( 'true' === $atts['filter'] && empty( $atts['category'] ) ) {
            $categories = get_terms( array(
                'taxonomy'   => 'project_category',
                'hide_empty' => true,
            ) );
            
            if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
                echo '<div class="aqualuxe-project-filter flex flex-wrap gap-2 mb-8">';
                
                echo '<button class="filter-button active bg-primary-600 hover:bg-primary-700 text-white font-medium py-2 px-4 rounded transition-colors duration-300" data-filter="*">';
                esc_html_e( 'All', 'aqualuxe' );
                echo '</button>';
                
                foreach ( $categories as $category ) {
                    echo '<button class="filter-button bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 font-medium py-2 px-4 rounded transition-colors duration-300" data-filter=".' . esc_attr( $category->slug ) . '">';
                    echo esc_html( $category->name );
                    echo '</button>';
                }
                
                echo '</div>';
            }
        }
        
        echo '<div class="aqualuxe-projects aqualuxe-projects-' . esc_attr( $atts['layout'] ) . ' grid grid-cols-1 md:grid-cols-' . esc_attr( $atts['columns'] ) . ' gap-6">';
        
        while ( $projects->have_posts() ) {
            $projects->the_post();
            
            $project_client = get_post_meta( get_the_ID(), '_aqualuxe_project_client', true );
            $project_location = get_post_meta( get_the_ID(), '_aqualuxe_project_location', true );
            $project_date = get_post_meta( get_the_ID(), '_aqualuxe_project_date', true );
            
            // Get project categories for filtering
            $project_cats = get_the_terms( get_the_ID(), 'project_category' );
            $cat_classes = '';
            
            if ( ! empty( $project_cats ) && ! is_wp_error( $project_cats ) ) {
                $cat_slugs = array();
                foreach ( $project_cats as $cat ) {
                    $cat_slugs[] = $cat->slug;
                }
                $cat_classes = implode( ' ', $cat_slugs );
            }
            
            echo '<div class="aqualuxe-project ' . esc_attr( $cat_classes ) . ' bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden transition-shadow duration-300 hover:shadow-lg">';
            
            if ( has_post_thumbnail() ) {
                echo '<a href="' . esc_url( get_permalink() ) . '" class="block relative group">';
                the_post_thumbnail( 'medium', array( 'class' => 'w-full h-56 object-cover transition-transform duration-500 group-hover:scale-110' ) );
                echo '<div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity duration-300">';
                echo '<span class="text-white text-lg font-bold">' . esc_html__( 'View Project', 'aqualuxe' ) . '</span>';
                echo '</div>';
                echo '</a>';
            }
            
            echo '<div class="p-6">';
            
            echo '<h3 class="text-xl font-bold mb-2">';
            echo '<a href="' . esc_url( get_permalink() ) . '" class="text-gray-900 dark:text-gray-100 hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-300">';
            the_title();
            echo '</a>';
            echo '</h3>';
            
            echo '<div class="project-meta flex flex-wrap gap-4 mb-4 text-sm text-gray-600 dark:text-gray-400">';
            
            if ( ! empty( $project_client ) ) {
                echo '<div class="project-client">';
                echo '<span class="font-medium">' . esc_html__( 'Client:', 'aqualuxe' ) . '</span> ';
                echo esc_html( $project_client );
                echo '</div>';
            }
            
            if ( ! empty( $project_location ) ) {
                echo '<div class="project-location">';
                echo '<span class="font-medium">' . esc_html__( 'Location:', 'aqualuxe' ) . '</span> ';
                echo esc_html( $project_location );
                echo '</div>';
            }
            
            if ( ! empty( $project_date ) ) {
                echo '<div class="project-date">';
                echo '<span class="font-medium">' . esc_html__( 'Date:', 'aqualuxe' ) . '</span> ';
                echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $project_date ) ) );
                echo '</div>';
            }
            
            echo '</div>';
            
            echo '<div class="project-excerpt text-gray-700 dark:text-gray-300 mb-4">';
            the_excerpt();
            echo '</div>';
            
            echo '<a href="' . esc_url( get_permalink() ) . '" class="inline-block bg-primary-600 hover:bg-primary-700 text-white font-medium py-2 px-4 rounded transition-colors duration-300">';
            esc_html_e( 'View Details', 'aqualuxe' );
            echo '</a>';
            
            echo '</div>';
            echo '</div>';
        }
        
        echo '</div>';
        
        if ( 'true' === $atts['filter'] ) {
            ?>
            <script>
                jQuery(document).ready(function($) {
                    // Initialize isotope
                    var $grid = $('.aqualuxe-projects').isotope({
                        itemSelector: '.aqualuxe-project',
                        layoutMode: 'fitRows'
                    });
                    
                    // Filter items on button click
                    $('.filter-button').on('click', function() {
                        var filterValue = $(this).attr('data-filter');
                        $grid.isotope({ filter: filterValue });
                        
                        // Toggle active class
                        $('.filter-button').removeClass('active bg-primary-600 text-white').addClass('bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-200');
                        $(this).removeClass('bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-200').addClass('active bg-primary-600 text-white');
                    });
                });
            </script>
            <?php
        }
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
            'count'     => -1,
            'category'  => '',
            'orderby'   => 'menu_order',
            'order'     => 'ASC',
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
        echo '<div class="aqualuxe-faqs divide-y divide-gray-200 dark:divide-gray-700">';
        
        $counter = 0;
        while ( $faqs->have_posts() ) {
            $faqs->the_post();
            $counter++;
            
            echo '<div class="aqualuxe-faq py-4">';
            
            echo '<button class="faq-question w-full flex justify-between items-center text-left font-semibold text-gray-900 dark:text-gray-100 focus:outline-none" aria-expanded="false" aria-controls="faq-answer-' . esc_attr( $counter ) . '">';
            echo '<span>' . get_the_title() . '</span>';
            echo '<svg class="faq-icon w-5 h-5 text-gray-500 dark:text-gray-400 transform transition-transform duration-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>';
            echo '</button>';
            
            echo '<div id="faq-answer-' . esc_attr( $counter ) . '" class="faq-answer mt-2 text-gray-700 dark:text-gray-300 hidden">';
            the_content();
            echo '</div>';
            
            echo '</div>';
        }
        
        echo '</div>';
        
        ?>
        <script>
            jQuery(document).ready(function($) {
                $('.faq-question').on('click', function() {
                    var $answer = $(this).next('.faq-answer');
                    var $icon = $(this).find('.faq-icon');
                    
                    $answer.slideToggle(300);
                    $icon.toggleClass('rotate-180');
                    
                    // Update aria attributes
                    var expanded = $(this).attr('aria-expanded') === 'true';
                    $(this).attr('aria-expanded', !expanded);
                });
            });
        </script>
        <?php
    }
    
    wp_reset_postdata();
    
    return ob_get_clean();
}

/**
 * Add shortcode button to TinyMCE
 */
function aqualuxe_add_shortcode_button() {
    if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
        return;
    }
    
    if ( get_user_option( 'rich_editing' ) !== 'true' ) {
        return;
    }
    
    add_filter( 'mce_external_plugins', 'aqualuxe_add_shortcode_tinymce_plugin' );
    add_filter( 'mce_buttons', 'aqualuxe_register_shortcode_button' );
}
add_action( 'admin_init', 'aqualuxe_add_shortcode_button' );

/**
 * Register shortcode button
 */
function aqualuxe_register_shortcode_button( $buttons ) {
    array_push( $buttons, 'aqualuxe_shortcodes' );
    return $buttons;
}

/**
 * Add shortcode plugin to TinyMCE
 */
function aqualuxe_add_shortcode_tinymce_plugin( $plugin_array ) {
    $plugin_array['aqualuxe_shortcodes'] = AQUALUXE_URI . 'assets/js/shortcodes.js';
    return $plugin_array;
}

/**
 * Add shortcode data to admin footer
 */
function aqualuxe_shortcode_data() {
    ?>
    <script type="text/javascript">
        var aqualuxeShortcodes = {
            'services': {
                'title': '<?php esc_html_e( 'Services', 'aqualuxe' ); ?>',
                'shortcode': '[aqualuxe_services count="3" category="" orderby="date" order="DESC" layout="grid" columns="3"]'
            },
            'events': {
                'title': '<?php esc_html_e( 'Events', 'aqualuxe' ); ?>',
                'shortcode': '[aqualuxe_events count="3" category="" orderby="meta_value" meta_key="_aqualuxe_event_date" order="ASC" layout="grid" columns="3" upcoming="true"]'
            },
            'testimonials': {
                'title': '<?php esc_html_e( 'Testimonials', 'aqualuxe' ); ?>',
                'shortcode': '[aqualuxe_testimonials count="3" category="" orderby="date" order="DESC" layout="grid" columns="3" slider="false"]'
            },
            'team': {
                'title': '<?php esc_html_e( 'Team', 'aqualuxe' ); ?>',
                'shortcode': '[aqualuxe_team count="-1" department="" orderby="menu_order" order="ASC" layout="grid" columns="4"]'
            },
            'projects': {
                'title': '<?php esc_html_e( 'Projects', 'aqualuxe' ); ?>',
                'shortcode': '[aqualuxe_projects count="6" category="" tag="" orderby="date" order="DESC" layout="grid" columns="3" filter="true"]'
            },
            'faqs': {
                'title': '<?php esc_html_e( 'FAQs', 'aqualuxe' ); ?>',
                'shortcode': '[aqualuxe_faqs count="-1" category="" orderby="menu_order" order="ASC"]'
            }
        };
    </script>
    <?php
}
add_action( 'admin_footer', 'aqualuxe_shortcode_data' );