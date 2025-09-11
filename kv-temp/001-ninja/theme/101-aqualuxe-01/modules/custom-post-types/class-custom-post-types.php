<?php
/**
 * Custom Post Types Module
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Custom Post Types functionality
 */
class AquaLuxe_Custom_Post_Types {

    /**
     * Constructor
     */
    public function __construct() {
        $this->init_hooks();
    }

    /**
     * Initialize hooks
     */
    private function init_hooks() {
        add_action( 'init', array( $this, 'register_post_types' ) );
        add_action( 'init', array( $this, 'register_taxonomies' ) );
        add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
        add_action( 'save_post', array( $this, 'save_meta_boxes' ) );
        add_action( 'pre_get_posts', array( $this, 'modify_queries' ) );
    }

    /**
     * Register custom post types
     */
    public function register_post_types() {
        // Services post type
        $this->register_services_post_type();
        
        // Events post type
        $this->register_events_post_type();
        
        // Team members post type
        $this->register_team_post_type();
        
        // Testimonials post type
        $this->register_testimonials_post_type();
        
        // Portfolio/Gallery post type
        $this->register_portfolio_post_type();
        
        // FAQ post type
        $this->register_faq_post_type();
    }

    /**
     * Register Services post type
     */
    private function register_services_post_type() {
        $labels = array(
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
        );

        $args = array(
            'labels'             => $labels,
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
            'menu_icon'          => 'dashicons-hammer',
            'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
            'show_in_rest'       => true,
        );

        register_post_type( 'service', $args );
    }

    /**
     * Register Events post type
     */
    private function register_events_post_type() {
        $labels = array(
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
        );

        $args = array(
            'labels'             => $labels,
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

        register_post_type( 'event', $args );
    }

    /**
     * Register Team post type
     */
    private function register_team_post_type() {
        $labels = array(
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
            'not_found'             => __( 'No team members found.', 'aqualuxe' ),
            'not_found_in_trash'    => __( 'No team members found in Trash.', 'aqualuxe' ),
        );

        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array( 'slug' => 'team' ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => 22,
            'menu_icon'          => 'dashicons-groups',
            'supports'           => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
            'show_in_rest'       => true,
        );

        register_post_type( 'team', $args );
    }

    /**
     * Register Testimonials post type
     */
    private function register_testimonials_post_type() {
        $labels = array(
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
            'not_found'             => __( 'No testimonials found.', 'aqualuxe' ),
            'not_found_in_trash'    => __( 'No testimonials found in Trash.', 'aqualuxe' ),
        );

        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array( 'slug' => 'testimonials' ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => 23,
            'menu_icon'          => 'dashicons-format-quote',
            'supports'           => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
            'show_in_rest'       => true,
        );

        register_post_type( 'testimonial', $args );
    }

    /**
     * Register Portfolio post type
     */
    private function register_portfolio_post_type() {
        $labels = array(
            'name'                  => _x( 'Portfolio', 'Post type general name', 'aqualuxe' ),
            'singular_name'         => _x( 'Portfolio Item', 'Post type singular name', 'aqualuxe' ),
            'menu_name'             => _x( 'Portfolio', 'Admin Menu text', 'aqualuxe' ),
            'name_admin_bar'        => _x( 'Portfolio Item', 'Add New on Toolbar', 'aqualuxe' ),
            'add_new'               => __( 'Add New', 'aqualuxe' ),
            'add_new_item'          => __( 'Add New Portfolio Item', 'aqualuxe' ),
            'new_item'              => __( 'New Portfolio Item', 'aqualuxe' ),
            'edit_item'             => __( 'Edit Portfolio Item', 'aqualuxe' ),
            'view_item'             => __( 'View Portfolio Item', 'aqualuxe' ),
            'all_items'             => __( 'All Portfolio Items', 'aqualuxe' ),
            'search_items'          => __( 'Search Portfolio', 'aqualuxe' ),
            'not_found'             => __( 'No portfolio items found.', 'aqualuxe' ),
            'not_found_in_trash'    => __( 'No portfolio items found in Trash.', 'aqualuxe' ),
        );

        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array( 'slug' => 'portfolio' ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => 24,
            'menu_icon'          => 'dashicons-portfolio',
            'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'page-attributes' ),
            'show_in_rest'       => true,
        );

        register_post_type( 'portfolio', $args );
    }

    /**
     * Register FAQ post type
     */
    private function register_faq_post_type() {
        $labels = array(
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
            'not_found'             => __( 'No FAQs found.', 'aqualuxe' ),
            'not_found_in_trash'    => __( 'No FAQs found in Trash.', 'aqualuxe' ),
        );

        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array( 'slug' => 'faq' ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => 25,
            'menu_icon'          => 'dashicons-editor-help',
            'supports'           => array( 'title', 'editor', 'custom-fields', 'page-attributes' ),
            'show_in_rest'       => true,
        );

        register_post_type( 'faq', $args );
    }

    /**
     * Register custom taxonomies
     */
    public function register_taxonomies() {
        // Media categories for attachments
        register_taxonomy( 'media_category', 'attachment', array(
            'labels' => array(
                'name'              => _x( 'Media Categories', 'taxonomy general name', 'aqualuxe' ),
                'singular_name'     => _x( 'Media Category', 'taxonomy singular name', 'aqualuxe' ),
                'search_items'      => __( 'Search Media Categories', 'aqualuxe' ),
                'all_items'         => __( 'All Media Categories', 'aqualuxe' ),
                'parent_item'       => __( 'Parent Media Category', 'aqualuxe' ),
                'parent_item_colon' => __( 'Parent Media Category:', 'aqualuxe' ),
                'edit_item'         => __( 'Edit Media Category', 'aqualuxe' ),
                'update_item'       => __( 'Update Media Category', 'aqualuxe' ),
                'add_new_item'      => __( 'Add New Media Category', 'aqualuxe' ),
                'new_item_name'     => __( 'New Media Category Name', 'aqualuxe' ),
                'menu_name'         => __( 'Media Categories', 'aqualuxe' ),
            ),
            'hierarchical'      => true,
            'public'            => false,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => false,
            'show_in_rest'      => true,
        ) );

        // Service categories
        register_taxonomy( 'service_category', 'service', array(
            'labels' => array(
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
                'menu_name'         => __( 'Service Categories', 'aqualuxe' ),
            ),
            'hierarchical'      => true,
            'public'            => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'service-category' ),
            'show_in_rest'      => true,
        ) );

        // Event categories
        register_taxonomy( 'event_category', 'event', array(
            'labels' => array(
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
                'menu_name'         => __( 'Event Categories', 'aqualuxe' ),
            ),
            'hierarchical'      => true,
            'public'            => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'event-category' ),
            'show_in_rest'      => true,
        ) );

        // Portfolio categories
        register_taxonomy( 'portfolio_category', 'portfolio', array(
            'labels' => array(
                'name'              => _x( 'Portfolio Categories', 'taxonomy general name', 'aqualuxe' ),
                'singular_name'     => _x( 'Portfolio Category', 'taxonomy singular name', 'aqualuxe' ),
                'search_items'      => __( 'Search Portfolio Categories', 'aqualuxe' ),
                'all_items'         => __( 'All Portfolio Categories', 'aqualuxe' ),
                'parent_item'       => __( 'Parent Portfolio Category', 'aqualuxe' ),
                'parent_item_colon' => __( 'Parent Portfolio Category:', 'aqualuxe' ),
                'edit_item'         => __( 'Edit Portfolio Category', 'aqualuxe' ),
                'update_item'       => __( 'Update Portfolio Category', 'aqualuxe' ),
                'add_new_item'      => __( 'Add New Portfolio Category', 'aqualuxe' ),
                'new_item_name'     => __( 'New Portfolio Category Name', 'aqualuxe' ),
                'menu_name'         => __( 'Portfolio Categories', 'aqualuxe' ),
            ),
            'hierarchical'      => true,
            'public'            => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'portfolio-category' ),
            'show_in_rest'      => true,
        ) );

        // FAQ categories
        register_taxonomy( 'faq_category', 'faq', array(
            'labels' => array(
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
                'menu_name'         => __( 'FAQ Categories', 'aqualuxe' ),
            ),
            'hierarchical'      => true,
            'public'            => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'faq-category' ),
            'show_in_rest'      => true,
        ) );
    }

    /**
     * Add meta boxes
     */
    public function add_meta_boxes() {
        // Service meta box
        add_meta_box(
            'service_details',
            __( 'Service Details', 'aqualuxe' ),
            array( $this, 'service_meta_box_callback' ),
            'service',
            'normal',
            'high'
        );

        // Event meta box
        add_meta_box(
            'event_details',
            __( 'Event Details', 'aqualuxe' ),
            array( $this, 'event_meta_box_callback' ),
            'event',
            'normal',
            'high'
        );

        // Team member meta box
        add_meta_box(
            'team_details',
            __( 'Team Member Details', 'aqualuxe' ),
            array( $this, 'team_meta_box_callback' ),
            'team',
            'normal',
            'high'
        );

        // Testimonial meta box
        add_meta_box(
            'testimonial_details',
            __( 'Testimonial Details', 'aqualuxe' ),
            array( $this, 'testimonial_meta_box_callback' ),
            'testimonial',
            'normal',
            'high'
        );
    }

    /**
     * Service meta box callback
     */
    public function service_meta_box_callback( $post ) {
        wp_nonce_field( 'service_meta_box', 'service_meta_box_nonce' );
        
        $price = get_post_meta( $post->ID, '_service_price', true );
        $duration = get_post_meta( $post->ID, '_service_duration', true );
        $includes = get_post_meta( $post->ID, '_service_includes', true );
        ?>
        <table class="form-table">
            <tr>
                <th><label for="service_price"><?php _e( 'Price', 'aqualuxe' ); ?></label></th>
                <td><input type="text" id="service_price" name="service_price" value="<?php echo esc_attr( $price ); ?>" /></td>
            </tr>
            <tr>
                <th><label for="service_duration"><?php _e( 'Duration', 'aqualuxe' ); ?></label></th>
                <td><input type="text" id="service_duration" name="service_duration" value="<?php echo esc_attr( $duration ); ?>" /></td>
            </tr>
            <tr>
                <th><label for="service_includes"><?php _e( 'What\'s Included', 'aqualuxe' ); ?></label></th>
                <td><textarea id="service_includes" name="service_includes" rows="5" cols="50"><?php echo esc_textarea( $includes ); ?></textarea></td>
            </tr>
        </table>
        <?php
    }

    /**
     * Event meta box callback
     */
    public function event_meta_box_callback( $post ) {
        wp_nonce_field( 'event_meta_box', 'event_meta_box_nonce' );
        
        $start_date = get_post_meta( $post->ID, '_event_start_date', true );
        $end_date = get_post_meta( $post->ID, '_event_end_date', true );
        $location = get_post_meta( $post->ID, '_event_location', true );
        $price = get_post_meta( $post->ID, '_event_price', true );
        ?>
        <table class="form-table">
            <tr>
                <th><label for="event_start_date"><?php _e( 'Start Date', 'aqualuxe' ); ?></label></th>
                <td><input type="datetime-local" id="event_start_date" name="event_start_date" value="<?php echo esc_attr( $start_date ); ?>" /></td>
            </tr>
            <tr>
                <th><label for="event_end_date"><?php _e( 'End Date', 'aqualuxe' ); ?></label></th>
                <td><input type="datetime-local" id="event_end_date" name="event_end_date" value="<?php echo esc_attr( $end_date ); ?>" /></td>
            </tr>
            <tr>
                <th><label for="event_location"><?php _e( 'Location', 'aqualuxe' ); ?></label></th>
                <td><input type="text" id="event_location" name="event_location" value="<?php echo esc_attr( $location ); ?>" /></td>
            </tr>
            <tr>
                <th><label for="event_price"><?php _e( 'Price', 'aqualuxe' ); ?></label></th>
                <td><input type="text" id="event_price" name="event_price" value="<?php echo esc_attr( $price ); ?>" /></td>
            </tr>
        </table>
        <?php
    }

    /**
     * Team meta box callback
     */
    public function team_meta_box_callback( $post ) {
        wp_nonce_field( 'team_meta_box', 'team_meta_box_nonce' );
        
        $position = get_post_meta( $post->ID, '_team_position', true );
        $email = get_post_meta( $post->ID, '_team_email', true );
        $phone = get_post_meta( $post->ID, '_team_phone', true );
        $social = get_post_meta( $post->ID, '_team_social', true );
        ?>
        <table class="form-table">
            <tr>
                <th><label for="team_position"><?php _e( 'Position', 'aqualuxe' ); ?></label></th>
                <td><input type="text" id="team_position" name="team_position" value="<?php echo esc_attr( $position ); ?>" /></td>
            </tr>
            <tr>
                <th><label for="team_email"><?php _e( 'Email', 'aqualuxe' ); ?></label></th>
                <td><input type="email" id="team_email" name="team_email" value="<?php echo esc_attr( $email ); ?>" /></td>
            </tr>
            <tr>
                <th><label for="team_phone"><?php _e( 'Phone', 'aqualuxe' ); ?></label></th>
                <td><input type="tel" id="team_phone" name="team_phone" value="<?php echo esc_attr( $phone ); ?>" /></td>
            </tr>
            <tr>
                <th><label for="team_social"><?php _e( 'Social Media (JSON)', 'aqualuxe' ); ?></label></th>
                <td><textarea id="team_social" name="team_social" rows="5" cols="50"><?php echo esc_textarea( $social ); ?></textarea></td>
            </tr>
        </table>
        <?php
    }

    /**
     * Testimonial meta box callback
     */
    public function testimonial_meta_box_callback( $post ) {
        wp_nonce_field( 'testimonial_meta_box', 'testimonial_meta_box_nonce' );
        
        $author = get_post_meta( $post->ID, '_testimonial_author', true );
        $company = get_post_meta( $post->ID, '_testimonial_company', true );
        $rating = get_post_meta( $post->ID, '_testimonial_rating', true );
        ?>
        <table class="form-table">
            <tr>
                <th><label for="testimonial_author"><?php _e( 'Author Name', 'aqualuxe' ); ?></label></th>
                <td><input type="text" id="testimonial_author" name="testimonial_author" value="<?php echo esc_attr( $author ); ?>" /></td>
            </tr>
            <tr>
                <th><label for="testimonial_company"><?php _e( 'Company/Title', 'aqualuxe' ); ?></label></th>
                <td><input type="text" id="testimonial_company" name="testimonial_company" value="<?php echo esc_attr( $company ); ?>" /></td>
            </tr>
            <tr>
                <th><label for="testimonial_rating"><?php _e( 'Rating (1-5)', 'aqualuxe' ); ?></label></th>
                <td>
                    <select id="testimonial_rating" name="testimonial_rating">
                        <option value=""><?php _e( 'Select Rating', 'aqualuxe' ); ?></option>
                        <?php for ( $i = 1; $i <= 5; $i++ ) : ?>
                            <option value="<?php echo $i; ?>" <?php selected( $rating, $i ); ?>><?php echo $i; ?> Star<?php echo $i > 1 ? 's' : ''; ?></option>
                        <?php endfor; ?>
                    </select>
                </td>
            </tr>
        </table>
        <?php
    }

    /**
     * Save meta boxes
     */
    public function save_meta_boxes( $post_id ) {
        // Check if nonce is valid
        if ( ! isset( $_POST['service_meta_box_nonce'] ) && ! isset( $_POST['event_meta_box_nonce'] ) && 
             ! isset( $_POST['team_meta_box_nonce'] ) && ! isset( $_POST['testimonial_meta_box_nonce'] ) ) {
            return;
        }

        // Verify nonce
        $nonce_verified = false;
        if ( isset( $_POST['service_meta_box_nonce'] ) && wp_verify_nonce( $_POST['service_meta_box_nonce'], 'service_meta_box' ) ) {
            $nonce_verified = true;
        } elseif ( isset( $_POST['event_meta_box_nonce'] ) && wp_verify_nonce( $_POST['event_meta_box_nonce'], 'event_meta_box' ) ) {
            $nonce_verified = true;
        } elseif ( isset( $_POST['team_meta_box_nonce'] ) && wp_verify_nonce( $_POST['team_meta_box_nonce'], 'team_meta_box' ) ) {
            $nonce_verified = true;
        } elseif ( isset( $_POST['testimonial_meta_box_nonce'] ) && wp_verify_nonce( $_POST['testimonial_meta_box_nonce'], 'testimonial_meta_box' ) ) {
            $nonce_verified = true;
        }

        if ( ! $nonce_verified ) {
            return;
        }

        // If this is an autosave, our form has not been submitted, so we don't want to do anything
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        // Check the user's permissions
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }

        // Save service meta
        if ( get_post_type( $post_id ) === 'service' ) {
            if ( isset( $_POST['service_price'] ) ) {
                update_post_meta( $post_id, '_service_price', sanitize_text_field( $_POST['service_price'] ) );
            }
            if ( isset( $_POST['service_duration'] ) ) {
                update_post_meta( $post_id, '_service_duration', sanitize_text_field( $_POST['service_duration'] ) );
            }
            if ( isset( $_POST['service_includes'] ) ) {
                update_post_meta( $post_id, '_service_includes', sanitize_textarea_field( $_POST['service_includes'] ) );
            }
        }

        // Save event meta
        if ( get_post_type( $post_id ) === 'event' ) {
            if ( isset( $_POST['event_start_date'] ) ) {
                update_post_meta( $post_id, '_event_start_date', sanitize_text_field( $_POST['event_start_date'] ) );
            }
            if ( isset( $_POST['event_end_date'] ) ) {
                update_post_meta( $post_id, '_event_end_date', sanitize_text_field( $_POST['event_end_date'] ) );
            }
            if ( isset( $_POST['event_location'] ) ) {
                update_post_meta( $post_id, '_event_location', sanitize_text_field( $_POST['event_location'] ) );
            }
            if ( isset( $_POST['event_price'] ) ) {
                update_post_meta( $post_id, '_event_price', sanitize_text_field( $_POST['event_price'] ) );
            }
        }

        // Save team meta
        if ( get_post_type( $post_id ) === 'team' ) {
            if ( isset( $_POST['team_position'] ) ) {
                update_post_meta( $post_id, '_team_position', sanitize_text_field( $_POST['team_position'] ) );
            }
            if ( isset( $_POST['team_email'] ) ) {
                update_post_meta( $post_id, '_team_email', sanitize_email( $_POST['team_email'] ) );
            }
            if ( isset( $_POST['team_phone'] ) ) {
                update_post_meta( $post_id, '_team_phone', sanitize_text_field( $_POST['team_phone'] ) );
            }
            if ( isset( $_POST['team_social'] ) ) {
                update_post_meta( $post_id, '_team_social', sanitize_textarea_field( $_POST['team_social'] ) );
            }
        }

        // Save testimonial meta
        if ( get_post_type( $post_id ) === 'testimonial' ) {
            if ( isset( $_POST['testimonial_author'] ) ) {
                update_post_meta( $post_id, '_testimonial_author', sanitize_text_field( $_POST['testimonial_author'] ) );
            }
            if ( isset( $_POST['testimonial_company'] ) ) {
                update_post_meta( $post_id, '_testimonial_company', sanitize_text_field( $_POST['testimonial_company'] ) );
            }
            if ( isset( $_POST['testimonial_rating'] ) ) {
                update_post_meta( $post_id, '_testimonial_rating', absint( $_POST['testimonial_rating'] ) );
            }
        }
    }

    /**
     * Modify queries
     */
    public function modify_queries( $query ) {
        if ( ! is_admin() && $query->is_main_query() ) {
            // Order events by start date
            if ( is_home() && $query->is_home() && ! $query->is_paged() ) {
                // Add custom post types to home page
                $post_types = array( 'post', 'event' );
                $query->set( 'post_type', $post_types );
            }
            
            if ( is_post_type_archive( 'event' ) ) {
                $query->set( 'meta_key', '_event_start_date' );
                $query->set( 'orderby', 'meta_value' );
                $query->set( 'order', 'ASC' );
                $query->set( 'meta_query', array(
                    array(
                        'key'     => '_event_start_date',
                        'value'   => current_time( 'Y-m-d H:i:s' ),
                        'compare' => '>=',
                        'type'    => 'DATETIME',
                    ),
                ) );
            }
        }
    }
}