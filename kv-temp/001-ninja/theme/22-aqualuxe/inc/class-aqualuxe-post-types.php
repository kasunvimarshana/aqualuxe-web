<?php
/**
 * AquaLuxe Custom Post Types
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access to this file
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AquaLuxe_Post_Types Class
 * 
 * Handles the theme custom post types and taxonomies
 */
class AquaLuxe_Post_Types {
    /**
     * Constructor
     */
    public function __construct() {
        // Register custom post types
        add_action('init', array($this, 'register_post_types'));
        
        // Register custom taxonomies
        add_action('init', array($this, 'register_taxonomies'));
        
        // Add custom meta boxes
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        
        // Save custom meta box data
        add_action('save_post', array($this, 'save_meta_box_data'));
        
        // Add custom columns to admin list tables
        add_filter('manage_service_posts_columns', array($this, 'service_columns'));
        add_action('manage_service_posts_custom_column', array($this, 'service_custom_column'), 10, 2);
        
        add_filter('manage_testimonial_posts_columns', array($this, 'testimonial_columns'));
        add_action('manage_testimonial_posts_custom_column', array($this, 'testimonial_custom_column'), 10, 2);
        
        add_filter('manage_team_posts_columns', array($this, 'team_columns'));
        add_action('manage_team_posts_custom_column', array($this, 'team_custom_column'), 10, 2);
        
        add_filter('manage_fish_posts_columns', array($this, 'fish_columns'));
        add_action('manage_fish_posts_custom_column', array($this, 'fish_custom_column'), 10, 2);
    }

    /**
     * Register custom post types
     */
    public function register_post_types() {
        // Service post type
        register_post_type('service', array(
            'labels' => array(
                'name'               => esc_html__('Services', 'aqualuxe'),
                'singular_name'      => esc_html__('Service', 'aqualuxe'),
                'menu_name'          => esc_html__('Services', 'aqualuxe'),
                'name_admin_bar'     => esc_html__('Service', 'aqualuxe'),
                'add_new'            => esc_html__('Add New', 'aqualuxe'),
                'add_new_item'       => esc_html__('Add New Service', 'aqualuxe'),
                'new_item'           => esc_html__('New Service', 'aqualuxe'),
                'edit_item'          => esc_html__('Edit Service', 'aqualuxe'),
                'view_item'          => esc_html__('View Service', 'aqualuxe'),
                'all_items'          => esc_html__('All Services', 'aqualuxe'),
                'search_items'       => esc_html__('Search Services', 'aqualuxe'),
                'parent_item_colon'  => esc_html__('Parent Services:', 'aqualuxe'),
                'not_found'          => esc_html__('No services found.', 'aqualuxe'),
                'not_found_in_trash' => esc_html__('No services found in Trash.', 'aqualuxe'),
            ),
            'public'              => true,
            'exclude_from_search' => false,
            'publicly_queryable'  => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'query_var'           => true,
            'rewrite'             => array('slug' => 'services'),
            'capability_type'     => 'post',
            'has_archive'         => true,
            'hierarchical'        => false,
            'menu_position'       => 20,
            'menu_icon'           => 'dashicons-admin-tools',
            'supports'            => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
            'show_in_rest'        => true,
        ));
        
        // Testimonial post type
        register_post_type('testimonial', array(
            'labels' => array(
                'name'               => esc_html__('Testimonials', 'aqualuxe'),
                'singular_name'      => esc_html__('Testimonial', 'aqualuxe'),
                'menu_name'          => esc_html__('Testimonials', 'aqualuxe'),
                'name_admin_bar'     => esc_html__('Testimonial', 'aqualuxe'),
                'add_new'            => esc_html__('Add New', 'aqualuxe'),
                'add_new_item'       => esc_html__('Add New Testimonial', 'aqualuxe'),
                'new_item'           => esc_html__('New Testimonial', 'aqualuxe'),
                'edit_item'          => esc_html__('Edit Testimonial', 'aqualuxe'),
                'view_item'          => esc_html__('View Testimonial', 'aqualuxe'),
                'all_items'          => esc_html__('All Testimonials', 'aqualuxe'),
                'search_items'       => esc_html__('Search Testimonials', 'aqualuxe'),
                'parent_item_colon'  => esc_html__('Parent Testimonials:', 'aqualuxe'),
                'not_found'          => esc_html__('No testimonials found.', 'aqualuxe'),
                'not_found_in_trash' => esc_html__('No testimonials found in Trash.', 'aqualuxe'),
            ),
            'public'              => true,
            'exclude_from_search' => true,
            'publicly_queryable'  => false,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'query_var'           => true,
            'rewrite'             => array('slug' => 'testimonials'),
            'capability_type'     => 'post',
            'has_archive'         => false,
            'hierarchical'        => false,
            'menu_position'       => 21,
            'menu_icon'           => 'dashicons-format-quote',
            'supports'            => array('title', 'editor', 'thumbnail'),
            'show_in_rest'        => true,
        ));
        
        // Team post type
        register_post_type('team', array(
            'labels' => array(
                'name'               => esc_html__('Team', 'aqualuxe'),
                'singular_name'      => esc_html__('Team Member', 'aqualuxe'),
                'menu_name'          => esc_html__('Team', 'aqualuxe'),
                'name_admin_bar'     => esc_html__('Team Member', 'aqualuxe'),
                'add_new'            => esc_html__('Add New', 'aqualuxe'),
                'add_new_item'       => esc_html__('Add New Team Member', 'aqualuxe'),
                'new_item'           => esc_html__('New Team Member', 'aqualuxe'),
                'edit_item'          => esc_html__('Edit Team Member', 'aqualuxe'),
                'view_item'          => esc_html__('View Team Member', 'aqualuxe'),
                'all_items'          => esc_html__('All Team Members', 'aqualuxe'),
                'search_items'       => esc_html__('Search Team Members', 'aqualuxe'),
                'parent_item_colon'  => esc_html__('Parent Team Members:', 'aqualuxe'),
                'not_found'          => esc_html__('No team members found.', 'aqualuxe'),
                'not_found_in_trash' => esc_html__('No team members found in Trash.', 'aqualuxe'),
            ),
            'public'              => true,
            'exclude_from_search' => false,
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
            'supports'            => array('title', 'editor', 'thumbnail', 'excerpt'),
            'show_in_rest'        => true,
        ));
        
        // Fish post type
        register_post_type('fish', array(
            'labels' => array(
                'name'               => esc_html__('Fish', 'aqualuxe'),
                'singular_name'      => esc_html__('Fish', 'aqualuxe'),
                'menu_name'          => esc_html__('Fish', 'aqualuxe'),
                'name_admin_bar'     => esc_html__('Fish', 'aqualuxe'),
                'add_new'            => esc_html__('Add New', 'aqualuxe'),
                'add_new_item'       => esc_html__('Add New Fish', 'aqualuxe'),
                'new_item'           => esc_html__('New Fish', 'aqualuxe'),
                'edit_item'          => esc_html__('Edit Fish', 'aqualuxe'),
                'view_item'          => esc_html__('View Fish', 'aqualuxe'),
                'all_items'          => esc_html__('All Fish', 'aqualuxe'),
                'search_items'       => esc_html__('Search Fish', 'aqualuxe'),
                'parent_item_colon'  => esc_html__('Parent Fish:', 'aqualuxe'),
                'not_found'          => esc_html__('No fish found.', 'aqualuxe'),
                'not_found_in_trash' => esc_html__('No fish found in Trash.', 'aqualuxe'),
            ),
            'public'              => true,
            'exclude_from_search' => false,
            'publicly_queryable'  => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'query_var'           => true,
            'rewrite'             => array('slug' => 'fish'),
            'capability_type'     => 'post',
            'has_archive'         => true,
            'hierarchical'        => false,
            'menu_position'       => 23,
            'menu_icon'           => 'dashicons-tide',
            'supports'            => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
            'show_in_rest'        => true,
        ));
        
        // Event post type
        register_post_type('event', array(
            'labels' => array(
                'name'               => esc_html__('Events', 'aqualuxe'),
                'singular_name'      => esc_html__('Event', 'aqualuxe'),
                'menu_name'          => esc_html__('Events', 'aqualuxe'),
                'name_admin_bar'     => esc_html__('Event', 'aqualuxe'),
                'add_new'            => esc_html__('Add New', 'aqualuxe'),
                'add_new_item'       => esc_html__('Add New Event', 'aqualuxe'),
                'new_item'           => esc_html__('New Event', 'aqualuxe'),
                'edit_item'          => esc_html__('Edit Event', 'aqualuxe'),
                'view_item'          => esc_html__('View Event', 'aqualuxe'),
                'all_items'          => esc_html__('All Events', 'aqualuxe'),
                'search_items'       => esc_html__('Search Events', 'aqualuxe'),
                'parent_item_colon'  => esc_html__('Parent Events:', 'aqualuxe'),
                'not_found'          => esc_html__('No events found.', 'aqualuxe'),
                'not_found_in_trash' => esc_html__('No events found in Trash.', 'aqualuxe'),
            ),
            'public'              => true,
            'exclude_from_search' => false,
            'publicly_queryable'  => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'query_var'           => true,
            'rewrite'             => array('slug' => 'events'),
            'capability_type'     => 'post',
            'has_archive'         => true,
            'hierarchical'        => false,
            'menu_position'       => 24,
            'menu_icon'           => 'dashicons-calendar-alt',
            'supports'            => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
            'show_in_rest'        => true,
        ));
        
        // FAQ post type
        register_post_type('faq', array(
            'labels' => array(
                'name'               => esc_html__('FAQs', 'aqualuxe'),
                'singular_name'      => esc_html__('FAQ', 'aqualuxe'),
                'menu_name'          => esc_html__('FAQs', 'aqualuxe'),
                'name_admin_bar'     => esc_html__('FAQ', 'aqualuxe'),
                'add_new'            => esc_html__('Add New', 'aqualuxe'),
                'add_new_item'       => esc_html__('Add New FAQ', 'aqualuxe'),
                'new_item'           => esc_html__('New FAQ', 'aqualuxe'),
                'edit_item'          => esc_html__('Edit FAQ', 'aqualuxe'),
                'view_item'          => esc_html__('View FAQ', 'aqualuxe'),
                'all_items'          => esc_html__('All FAQs', 'aqualuxe'),
                'search_items'       => esc_html__('Search FAQs', 'aqualuxe'),
                'parent_item_colon'  => esc_html__('Parent FAQs:', 'aqualuxe'),
                'not_found'          => esc_html__('No FAQs found.', 'aqualuxe'),
                'not_found_in_trash' => esc_html__('No FAQs found in Trash.', 'aqualuxe'),
            ),
            'public'              => true,
            'exclude_from_search' => false,
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
            'supports'            => array('title', 'editor'),
            'show_in_rest'        => true,
        ));
    }

    /**
     * Register custom taxonomies
     */
    public function register_taxonomies() {
        // Service Category taxonomy
        register_taxonomy('service_category', 'service', array(
            'labels' => array(
                'name'              => esc_html__('Service Categories', 'aqualuxe'),
                'singular_name'     => esc_html__('Service Category', 'aqualuxe'),
                'search_items'      => esc_html__('Search Service Categories', 'aqualuxe'),
                'all_items'         => esc_html__('All Service Categories', 'aqualuxe'),
                'parent_item'       => esc_html__('Parent Service Category', 'aqualuxe'),
                'parent_item_colon' => esc_html__('Parent Service Category:', 'aqualuxe'),
                'edit_item'         => esc_html__('Edit Service Category', 'aqualuxe'),
                'update_item'       => esc_html__('Update Service Category', 'aqualuxe'),
                'add_new_item'      => esc_html__('Add New Service Category', 'aqualuxe'),
                'new_item_name'     => esc_html__('New Service Category Name', 'aqualuxe'),
                'menu_name'         => esc_html__('Categories', 'aqualuxe'),
            ),
            'hierarchical'      => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => 'service-category'),
            'show_in_rest'      => true,
        ));
        
        // Team Department taxonomy
        register_taxonomy('team_department', 'team', array(
            'labels' => array(
                'name'              => esc_html__('Departments', 'aqualuxe'),
                'singular_name'     => esc_html__('Department', 'aqualuxe'),
                'search_items'      => esc_html__('Search Departments', 'aqualuxe'),
                'all_items'         => esc_html__('All Departments', 'aqualuxe'),
                'parent_item'       => esc_html__('Parent Department', 'aqualuxe'),
                'parent_item_colon' => esc_html__('Parent Department:', 'aqualuxe'),
                'edit_item'         => esc_html__('Edit Department', 'aqualuxe'),
                'update_item'       => esc_html__('Update Department', 'aqualuxe'),
                'add_new_item'      => esc_html__('Add New Department', 'aqualuxe'),
                'new_item_name'     => esc_html__('New Department Name', 'aqualuxe'),
                'menu_name'         => esc_html__('Departments', 'aqualuxe'),
            ),
            'hierarchical'      => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => 'department'),
            'show_in_rest'      => true,
        ));
        
        // Fish Species taxonomy
        register_taxonomy('fish_species', 'fish', array(
            'labels' => array(
                'name'              => esc_html__('Species', 'aqualuxe'),
                'singular_name'     => esc_html__('Species', 'aqualuxe'),
                'search_items'      => esc_html__('Search Species', 'aqualuxe'),
                'all_items'         => esc_html__('All Species', 'aqualuxe'),
                'parent_item'       => esc_html__('Parent Species', 'aqualuxe'),
                'parent_item_colon' => esc_html__('Parent Species:', 'aqualuxe'),
                'edit_item'         => esc_html__('Edit Species', 'aqualuxe'),
                'update_item'       => esc_html__('Update Species', 'aqualuxe'),
                'add_new_item'      => esc_html__('Add New Species', 'aqualuxe'),
                'new_item_name'     => esc_html__('New Species Name', 'aqualuxe'),
                'menu_name'         => esc_html__('Species', 'aqualuxe'),
            ),
            'hierarchical'      => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => 'species'),
            'show_in_rest'      => true,
        ));
        
        // Fish Water Type taxonomy
        register_taxonomy('fish_water_type', 'fish', array(
            'labels' => array(
                'name'              => esc_html__('Water Types', 'aqualuxe'),
                'singular_name'     => esc_html__('Water Type', 'aqualuxe'),
                'search_items'      => esc_html__('Search Water Types', 'aqualuxe'),
                'all_items'         => esc_html__('All Water Types', 'aqualuxe'),
                'parent_item'       => esc_html__('Parent Water Type', 'aqualuxe'),
                'parent_item_colon' => esc_html__('Parent Water Type:', 'aqualuxe'),
                'edit_item'         => esc_html__('Edit Water Type', 'aqualuxe'),
                'update_item'       => esc_html__('Update Water Type', 'aqualuxe'),
                'add_new_item'      => esc_html__('Add New Water Type', 'aqualuxe'),
                'new_item_name'     => esc_html__('New Water Type Name', 'aqualuxe'),
                'menu_name'         => esc_html__('Water Types', 'aqualuxe'),
            ),
            'hierarchical'      => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => 'water-type'),
            'show_in_rest'      => true,
        ));
        
        // Event Category taxonomy
        register_taxonomy('event_category', 'event', array(
            'labels' => array(
                'name'              => esc_html__('Event Categories', 'aqualuxe'),
                'singular_name'     => esc_html__('Event Category', 'aqualuxe'),
                'search_items'      => esc_html__('Search Event Categories', 'aqualuxe'),
                'all_items'         => esc_html__('All Event Categories', 'aqualuxe'),
                'parent_item'       => esc_html__('Parent Event Category', 'aqualuxe'),
                'parent_item_colon' => esc_html__('Parent Event Category:', 'aqualuxe'),
                'edit_item'         => esc_html__('Edit Event Category', 'aqualuxe'),
                'update_item'       => esc_html__('Update Event Category', 'aqualuxe'),
                'add_new_item'      => esc_html__('Add New Event Category', 'aqualuxe'),
                'new_item_name'     => esc_html__('New Event Category Name', 'aqualuxe'),
                'menu_name'         => esc_html__('Categories', 'aqualuxe'),
            ),
            'hierarchical'      => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => 'event-category'),
            'show_in_rest'      => true,
        ));
        
        // FAQ Category taxonomy
        register_taxonomy('faq_category', 'faq', array(
            'labels' => array(
                'name'              => esc_html__('FAQ Categories', 'aqualuxe'),
                'singular_name'     => esc_html__('FAQ Category', 'aqualuxe'),
                'search_items'      => esc_html__('Search FAQ Categories', 'aqualuxe'),
                'all_items'         => esc_html__('All FAQ Categories', 'aqualuxe'),
                'parent_item'       => esc_html__('Parent FAQ Category', 'aqualuxe'),
                'parent_item_colon' => esc_html__('Parent FAQ Category:', 'aqualuxe'),
                'edit_item'         => esc_html__('Edit FAQ Category', 'aqualuxe'),
                'update_item'       => esc_html__('Update FAQ Category', 'aqualuxe'),
                'add_new_item'      => esc_html__('Add New FAQ Category', 'aqualuxe'),
                'new_item_name'     => esc_html__('New FAQ Category Name', 'aqualuxe'),
                'menu_name'         => esc_html__('Categories', 'aqualuxe'),
            ),
            'hierarchical'      => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => 'faq-category'),
            'show_in_rest'      => true,
        ));
    }

    /**
     * Add custom meta boxes
     */
    public function add_meta_boxes() {
        // Service meta box
        add_meta_box(
            'service_details',
            esc_html__('Service Details', 'aqualuxe'),
            array($this, 'service_meta_box_callback'),
            'service',
            'normal',
            'high'
        );
        
        // Testimonial meta box
        add_meta_box(
            'testimonial_details',
            esc_html__('Testimonial Details', 'aqualuxe'),
            array($this, 'testimonial_meta_box_callback'),
            'testimonial',
            'normal',
            'high'
        );
        
        // Team meta box
        add_meta_box(
            'team_details',
            esc_html__('Team Member Details', 'aqualuxe'),
            array($this, 'team_meta_box_callback'),
            'team',
            'normal',
            'high'
        );
        
        // Fish meta box
        add_meta_box(
            'fish_details',
            esc_html__('Fish Details', 'aqualuxe'),
            array($this, 'fish_meta_box_callback'),
            'fish',
            'normal',
            'high'
        );
        
        // Event meta box
        add_meta_box(
            'event_details',
            esc_html__('Event Details', 'aqualuxe'),
            array($this, 'event_meta_box_callback'),
            'event',
            'normal',
            'high'
        );
    }

    /**
     * Service meta box callback
     * 
     * @param WP_Post $post The post object.
     */
    public function service_meta_box_callback($post) {
        // Add nonce for security
        wp_nonce_field('aqualuxe_service_meta_box', 'aqualuxe_service_meta_box_nonce');
        
        // Get the saved values
        $service_icon = get_post_meta($post->ID, '_service_icon', true);
        $service_price = get_post_meta($post->ID, '_service_price', true);
        $service_duration = get_post_meta($post->ID, '_service_duration', true);
        $service_features = get_post_meta($post->ID, '_service_features', true);
        
        ?>
        <div class="aqualuxe-meta-box">
            <div class="aqualuxe-meta-field">
                <label for="service_icon"><?php esc_html_e('Service Icon', 'aqualuxe'); ?></label>
                <input type="text" id="service_icon" name="service_icon" value="<?php echo esc_attr($service_icon); ?>" placeholder="<?php esc_attr_e('e.g. fas fa-fish', 'aqualuxe'); ?>" />
                <p class="description"><?php esc_html_e('Enter a Font Awesome icon class (e.g. fas fa-fish)', 'aqualuxe'); ?></p>
            </div>
            
            <div class="aqualuxe-meta-field">
                <label for="service_price"><?php esc_html_e('Service Price', 'aqualuxe'); ?></label>
                <input type="text" id="service_price" name="service_price" value="<?php echo esc_attr($service_price); ?>" placeholder="<?php esc_attr_e('e.g. $99', 'aqualuxe'); ?>" />
                <p class="description"><?php esc_html_e('Enter the price for this service', 'aqualuxe'); ?></p>
            </div>
            
            <div class="aqualuxe-meta-field">
                <label for="service_duration"><?php esc_html_e('Service Duration', 'aqualuxe'); ?></label>
                <input type="text" id="service_duration" name="service_duration" value="<?php echo esc_attr($service_duration); ?>" placeholder="<?php esc_attr_e('e.g. 2 hours', 'aqualuxe'); ?>" />
                <p class="description"><?php esc_html_e('Enter the duration for this service', 'aqualuxe'); ?></p>
            </div>
            
            <div class="aqualuxe-meta-field">
                <label for="service_features"><?php esc_html_e('Service Features', 'aqualuxe'); ?></label>
                <textarea id="service_features" name="service_features" rows="5"><?php echo esc_textarea($service_features); ?></textarea>
                <p class="description"><?php esc_html_e('Enter the features for this service (one per line)', 'aqualuxe'); ?></p>
            </div>
        </div>
        <?php
    }

    /**
     * Testimonial meta box callback
     * 
     * @param WP_Post $post The post object.
     */
    public function testimonial_meta_box_callback($post) {
        // Add nonce for security
        wp_nonce_field('aqualuxe_testimonial_meta_box', 'aqualuxe_testimonial_meta_box_nonce');
        
        // Get the saved values
        $testimonial_author = get_post_meta($post->ID, '_testimonial_author', true);
        $testimonial_position = get_post_meta($post->ID, '_testimonial_position', true);
        $testimonial_company = get_post_meta($post->ID, '_testimonial_company', true);
        $testimonial_rating = get_post_meta($post->ID, '_testimonial_rating', true);
        
        ?>
        <div class="aqualuxe-meta-box">
            <div class="aqualuxe-meta-field">
                <label for="testimonial_author"><?php esc_html_e('Author Name', 'aqualuxe'); ?></label>
                <input type="text" id="testimonial_author" name="testimonial_author" value="<?php echo esc_attr($testimonial_author); ?>" />
                <p class="description"><?php esc_html_e('Enter the name of the testimonial author', 'aqualuxe'); ?></p>
            </div>
            
            <div class="aqualuxe-meta-field">
                <label for="testimonial_position"><?php esc_html_e('Author Position', 'aqualuxe'); ?></label>
                <input type="text" id="testimonial_position" name="testimonial_position" value="<?php echo esc_attr($testimonial_position); ?>" />
                <p class="description"><?php esc_html_e('Enter the position of the testimonial author', 'aqualuxe'); ?></p>
            </div>
            
            <div class="aqualuxe-meta-field">
                <label for="testimonial_company"><?php esc_html_e('Author Company', 'aqualuxe'); ?></label>
                <input type="text" id="testimonial_company" name="testimonial_company" value="<?php echo esc_attr($testimonial_company); ?>" />
                <p class="description"><?php esc_html_e('Enter the company of the testimonial author', 'aqualuxe'); ?></p>
            </div>
            
            <div class="aqualuxe-meta-field">
                <label for="testimonial_rating"><?php esc_html_e('Rating (1-5)', 'aqualuxe'); ?></label>
                <select id="testimonial_rating" name="testimonial_rating">
                    <option value=""><?php esc_html_e('Select Rating', 'aqualuxe'); ?></option>
                    <option value="1" <?php selected($testimonial_rating, '1'); ?>>1</option>
                    <option value="2" <?php selected($testimonial_rating, '2'); ?>>2</option>
                    <option value="3" <?php selected($testimonial_rating, '3'); ?>>3</option>
                    <option value="4" <?php selected($testimonial_rating, '4'); ?>>4</option>
                    <option value="5" <?php selected($testimonial_rating, '5'); ?>>5</option>
                </select>
                <p class="description"><?php esc_html_e('Select the rating for this testimonial', 'aqualuxe'); ?></p>
            </div>
        </div>
        <?php
    }

    /**
     * Team meta box callback
     * 
     * @param WP_Post $post The post object.
     */
    public function team_meta_box_callback($post) {
        // Add nonce for security
        wp_nonce_field('aqualuxe_team_meta_box', 'aqualuxe_team_meta_box_nonce');
        
        // Get the saved values
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
                <input type="text" id="team_position" name="team_position" value="<?php echo esc_attr($team_position); ?>" />
                <p class="description"><?php esc_html_e('Enter the position of the team member', 'aqualuxe'); ?></p>
            </div>
            
            <div class="aqualuxe-meta-field">
                <label for="team_email"><?php esc_html_e('Email', 'aqualuxe'); ?></label>
                <input type="email" id="team_email" name="team_email" value="<?php echo esc_attr($team_email); ?>" />
                <p class="description"><?php esc_html_e('Enter the email address of the team member', 'aqualuxe'); ?></p>
            </div>
            
            <div class="aqualuxe-meta-field">
                <label for="team_phone"><?php esc_html_e('Phone', 'aqualuxe'); ?></label>
                <input type="text" id="team_phone" name="team_phone" value="<?php echo esc_attr($team_phone); ?>" />
                <p class="description"><?php esc_html_e('Enter the phone number of the team member', 'aqualuxe'); ?></p>
            </div>
            
            <h4><?php esc_html_e('Social Media', 'aqualuxe'); ?></h4>
            
            <div class="aqualuxe-meta-field">
                <label for="team_facebook"><?php esc_html_e('Facebook', 'aqualuxe'); ?></label>
                <input type="url" id="team_facebook" name="team_facebook" value="<?php echo esc_url($team_facebook); ?>" />
                <p class="description"><?php esc_html_e('Enter the Facebook profile URL', 'aqualuxe'); ?></p>
            </div>
            
            <div class="aqualuxe-meta-field">
                <label for="team_twitter"><?php esc_html_e('Twitter', 'aqualuxe'); ?></label>
                <input type="url" id="team_twitter" name="team_twitter" value="<?php echo esc_url($team_twitter); ?>" />
                <p class="description"><?php esc_html_e('Enter the Twitter profile URL', 'aqualuxe'); ?></p>
            </div>
            
            <div class="aqualuxe-meta-field">
                <label for="team_linkedin"><?php esc_html_e('LinkedIn', 'aqualuxe'); ?></label>
                <input type="url" id="team_linkedin" name="team_linkedin" value="<?php echo esc_url($team_linkedin); ?>" />
                <p class="description"><?php esc_html_e('Enter the LinkedIn profile URL', 'aqualuxe'); ?></p>
            </div>
            
            <div class="aqualuxe-meta-field">
                <label for="team_instagram"><?php esc_html_e('Instagram', 'aqualuxe'); ?></label>
                <input type="url" id="team_instagram" name="team_instagram" value="<?php echo esc_url($team_instagram); ?>" />
                <p class="description"><?php esc_html_e('Enter the Instagram profile URL', 'aqualuxe'); ?></p>
            </div>
        </div>
        <?php
    }

    /**
     * Fish meta box callback
     * 
     * @param WP_Post $post The post object.
     */
    public function fish_meta_box_callback($post) {
        // Add nonce for security
        wp_nonce_field('aqualuxe_fish_meta_box', 'aqualuxe_fish_meta_box_nonce');
        
        // Get the saved values
        $fish_scientific_name = get_post_meta($post->ID, '_fish_scientific_name', true);
        $fish_origin = get_post_meta($post->ID, '_fish_origin', true);
        $fish_size = get_post_meta($post->ID, '_fish_size', true);
        $fish_temperature = get_post_meta($post->ID, '_fish_temperature', true);
        $fish_ph = get_post_meta($post->ID, '_fish_ph', true);
        $fish_diet = get_post_meta($post->ID, '_fish_diet', true);
        $fish_lifespan = get_post_meta($post->ID, '_fish_lifespan', true);
        $fish_difficulty = get_post_meta($post->ID, '_fish_difficulty', true);
        $fish_price = get_post_meta($post->ID, '_fish_price', true);
        $fish_stock = get_post_meta($post->ID, '_fish_stock', true);
        $fish_sku = get_post_meta($post->ID, '_fish_sku', true);
        
        ?>
        <div class="aqualuxe-meta-box">
            <div class="aqualuxe-meta-field">
                <label for="fish_scientific_name"><?php esc_html_e('Scientific Name', 'aqualuxe'); ?></label>
                <input type="text" id="fish_scientific_name" name="fish_scientific_name" value="<?php echo esc_attr($fish_scientific_name); ?>" />
                <p class="description"><?php esc_html_e('Enter the scientific name of the fish', 'aqualuxe'); ?></p>
            </div>
            
            <div class="aqualuxe-meta-field">
                <label for="fish_origin"><?php esc_html_e('Origin', 'aqualuxe'); ?></label>
                <input type="text" id="fish_origin" name="fish_origin" value="<?php echo esc_attr($fish_origin); ?>" />
                <p class="description"><?php esc_html_e('Enter the origin of the fish', 'aqualuxe'); ?></p>
            </div>
            
            <div class="aqualuxe-meta-field">
                <label for="fish_size"><?php esc_html_e('Size', 'aqualuxe'); ?></label>
                <input type="text" id="fish_size" name="fish_size" value="<?php echo esc_attr($fish_size); ?>" />
                <p class="description"><?php esc_html_e('Enter the average size of the fish (e.g. 2-3 inches)', 'aqualuxe'); ?></p>
            </div>
            
            <div class="aqualuxe-meta-field">
                <label for="fish_temperature"><?php esc_html_e('Temperature', 'aqualuxe'); ?></label>
                <input type="text" id="fish_temperature" name="fish_temperature" value="<?php echo esc_attr($fish_temperature); ?>" />
                <p class="description"><?php esc_html_e('Enter the ideal temperature range (e.g. 72-78°F)', 'aqualuxe'); ?></p>
            </div>
            
            <div class="aqualuxe-meta-field">
                <label for="fish_ph"><?php esc_html_e('pH Level', 'aqualuxe'); ?></label>
                <input type="text" id="fish_ph" name="fish_ph" value="<?php echo esc_attr($fish_ph); ?>" />
                <p class="description"><?php esc_html_e('Enter the ideal pH range (e.g. 6.5-7.5)', 'aqualuxe'); ?></p>
            </div>
            
            <div class="aqualuxe-meta-field">
                <label for="fish_diet"><?php esc_html_e('Diet', 'aqualuxe'); ?></label>
                <input type="text" id="fish_diet" name="fish_diet" value="<?php echo esc_attr($fish_diet); ?>" />
                <p class="description"><?php esc_html_e('Enter the diet of the fish', 'aqualuxe'); ?></p>
            </div>
            
            <div class="aqualuxe-meta-field">
                <label for="fish_lifespan"><?php esc_html_e('Lifespan', 'aqualuxe'); ?></label>
                <input type="text" id="fish_lifespan" name="fish_lifespan" value="<?php echo esc_attr($fish_lifespan); ?>" />
                <p class="description"><?php esc_html_e('Enter the average lifespan of the fish (e.g. 3-5 years)', 'aqualuxe'); ?></p>
            </div>
            
            <div class="aqualuxe-meta-field">
                <label for="fish_difficulty"><?php esc_html_e('Care Difficulty', 'aqualuxe'); ?></label>
                <select id="fish_difficulty" name="fish_difficulty">
                    <option value=""><?php esc_html_e('Select Difficulty', 'aqualuxe'); ?></option>
                    <option value="beginner" <?php selected($fish_difficulty, 'beginner'); ?>><?php esc_html_e('Beginner', 'aqualuxe'); ?></option>
                    <option value="intermediate" <?php selected($fish_difficulty, 'intermediate'); ?>><?php esc_html_e('Intermediate', 'aqualuxe'); ?></option>
                    <option value="advanced" <?php selected($fish_difficulty, 'advanced'); ?>><?php esc_html_e('Advanced', 'aqualuxe'); ?></option>
                    <option value="expert" <?php selected($fish_difficulty, 'expert'); ?>><?php esc_html_e('Expert', 'aqualuxe'); ?></option>
                </select>
                <p class="description"><?php esc_html_e('Select the care difficulty level', 'aqualuxe'); ?></p>
            </div>
            
            <div class="aqualuxe-meta-field">
                <label for="fish_price"><?php esc_html_e('Price', 'aqualuxe'); ?></label>
                <input type="text" id="fish_price" name="fish_price" value="<?php echo esc_attr($fish_price); ?>" />
                <p class="description"><?php esc_html_e('Enter the price of the fish', 'aqualuxe'); ?></p>
            </div>
            
            <div class="aqualuxe-meta-field">
                <label for="fish_stock"><?php esc_html_e('Stock', 'aqualuxe'); ?></label>
                <input type="number" id="fish_stock" name="fish_stock" value="<?php echo esc_attr($fish_stock); ?>" min="0" />
                <p class="description"><?php esc_html_e('Enter the current stock quantity', 'aqualuxe'); ?></p>
            </div>
            
            <div class="aqualuxe-meta-field">
                <label for="fish_sku"><?php esc_html_e('SKU', 'aqualuxe'); ?></label>
                <input type="text" id="fish_sku" name="fish_sku" value="<?php echo esc_attr($fish_sku); ?>" />
                <p class="description"><?php esc_html_e('Enter the SKU (Stock Keeping Unit)', 'aqualuxe'); ?></p>
            </div>
        </div>
        <?php
    }

    /**
     * Event meta box callback
     * 
     * @param WP_Post $post The post object.
     */
    public function event_meta_box_callback($post) {
        // Add nonce for security
        wp_nonce_field('aqualuxe_event_meta_box', 'aqualuxe_event_meta_box_nonce');
        
        // Get the saved values
        $event_start_date = get_post_meta($post->ID, '_event_start_date', true);
        $event_end_date = get_post_meta($post->ID, '_event_end_date', true);
        $event_time = get_post_meta($post->ID, '_event_time', true);
        $event_location = get_post_meta($post->ID, '_event_location', true);
        $event_address = get_post_meta($post->ID, '_event_address', true);
        $event_map = get_post_meta($post->ID, '_event_map', true);
        $event_cost = get_post_meta($post->ID, '_event_cost', true);
        $event_registration_url = get_post_meta($post->ID, '_event_registration_url', true);
        $event_capacity = get_post_meta($post->ID, '_event_capacity', true);
        
        ?>
        <div class="aqualuxe-meta-box">
            <div class="aqualuxe-meta-field">
                <label for="event_start_date"><?php esc_html_e('Start Date', 'aqualuxe'); ?></label>
                <input type="date" id="event_start_date" name="event_start_date" value="<?php echo esc_attr($event_start_date); ?>" />
                <p class="description"><?php esc_html_e('Enter the start date of the event', 'aqualuxe'); ?></p>
            </div>
            
            <div class="aqualuxe-meta-field">
                <label for="event_end_date"><?php esc_html_e('End Date', 'aqualuxe'); ?></label>
                <input type="date" id="event_end_date" name="event_end_date" value="<?php echo esc_attr($event_end_date); ?>" />
                <p class="description"><?php esc_html_e('Enter the end date of the event (leave blank for one-day events)', 'aqualuxe'); ?></p>
            </div>
            
            <div class="aqualuxe-meta-field">
                <label for="event_time"><?php esc_html_e('Time', 'aqualuxe'); ?></label>
                <input type="text" id="event_time" name="event_time" value="<?php echo esc_attr($event_time); ?>" placeholder="<?php esc_attr_e('e.g. 10:00 AM - 4:00 PM', 'aqualuxe'); ?>" />
                <p class="description"><?php esc_html_e('Enter the time of the event', 'aqualuxe'); ?></p>
            </div>
            
            <div class="aqualuxe-meta-field">
                <label for="event_location"><?php esc_html_e('Location Name', 'aqualuxe'); ?></label>
                <input type="text" id="event_location" name="event_location" value="<?php echo esc_attr($event_location); ?>" />
                <p class="description"><?php esc_html_e('Enter the name of the event location', 'aqualuxe'); ?></p>
            </div>
            
            <div class="aqualuxe-meta-field">
                <label for="event_address"><?php esc_html_e('Address', 'aqualuxe'); ?></label>
                <textarea id="event_address" name="event_address" rows="3"><?php echo esc_textarea($event_address); ?></textarea>
                <p class="description"><?php esc_html_e('Enter the address of the event location', 'aqualuxe'); ?></p>
            </div>
            
            <div class="aqualuxe-meta-field">
                <label for="event_map"><?php esc_html_e('Google Map Embed URL', 'aqualuxe'); ?></label>
                <input type="url" id="event_map" name="event_map" value="<?php echo esc_url($event_map); ?>" />
                <p class="description"><?php esc_html_e('Enter the Google Map embed URL for the event location', 'aqualuxe'); ?></p>
            </div>
            
            <div class="aqualuxe-meta-field">
                <label for="event_cost"><?php esc_html_e('Cost', 'aqualuxe'); ?></label>
                <input type="text" id="event_cost" name="event_cost" value="<?php echo esc_attr($event_cost); ?>" placeholder="<?php esc_attr_e('e.g. Free or $10', 'aqualuxe'); ?>" />
                <p class="description"><?php esc_html_e('Enter the cost of the event (use "Free" for free events)', 'aqualuxe'); ?></p>
            </div>
            
            <div class="aqualuxe-meta-field">
                <label for="event_registration_url"><?php esc_html_e('Registration URL', 'aqualuxe'); ?></label>
                <input type="url" id="event_registration_url" name="event_registration_url" value="<?php echo esc_url($event_registration_url); ?>" />
                <p class="description"><?php esc_html_e('Enter the URL for event registration', 'aqualuxe'); ?></p>
            </div>
            
            <div class="aqualuxe-meta-field">
                <label for="event_capacity"><?php esc_html_e('Capacity', 'aqualuxe'); ?></label>
                <input type="number" id="event_capacity" name="event_capacity" value="<?php echo esc_attr($event_capacity); ?>" min="0" />
                <p class="description"><?php esc_html_e('Enter the maximum capacity for the event (leave blank for unlimited)', 'aqualuxe'); ?></p>
            </div>
        </div>
        <?php
    }

    /**
     * Save meta box data
     * 
     * @param int $post_id The post ID.
     */
    public function save_meta_box_data($post_id) {
        // Check if we're doing an autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        // Service meta box
        if (isset($_POST['aqualuxe_service_meta_box_nonce']) && wp_verify_nonce($_POST['aqualuxe_service_meta_box_nonce'], 'aqualuxe_service_meta_box')) {
            if (current_user_can('edit_post', $post_id)) {
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
        }
        
        // Testimonial meta box
        if (isset($_POST['aqualuxe_testimonial_meta_box_nonce']) && wp_verify_nonce($_POST['aqualuxe_testimonial_meta_box_nonce'], 'aqualuxe_testimonial_meta_box')) {
            if (current_user_can('edit_post', $post_id)) {
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
        }
        
        // Team meta box
        if (isset($_POST['aqualuxe_team_meta_box_nonce']) && wp_verify_nonce($_POST['aqualuxe_team_meta_box_nonce'], 'aqualuxe_team_meta_box')) {
            if (current_user_can('edit_post', $post_id)) {
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
        
        // Fish meta box
        if (isset($_POST['aqualuxe_fish_meta_box_nonce']) && wp_verify_nonce($_POST['aqualuxe_fish_meta_box_nonce'], 'aqualuxe_fish_meta_box')) {
            if (current_user_can('edit_post', $post_id)) {
                if (isset($_POST['fish_scientific_name'])) {
                    update_post_meta($post_id, '_fish_scientific_name', sanitize_text_field($_POST['fish_scientific_name']));
                }
                
                if (isset($_POST['fish_origin'])) {
                    update_post_meta($post_id, '_fish_origin', sanitize_text_field($_POST['fish_origin']));
                }
                
                if (isset($_POST['fish_size'])) {
                    update_post_meta($post_id, '_fish_size', sanitize_text_field($_POST['fish_size']));
                }
                
                if (isset($_POST['fish_temperature'])) {
                    update_post_meta($post_id, '_fish_temperature', sanitize_text_field($_POST['fish_temperature']));
                }
                
                if (isset($_POST['fish_ph'])) {
                    update_post_meta($post_id, '_fish_ph', sanitize_text_field($_POST['fish_ph']));
                }
                
                if (isset($_POST['fish_diet'])) {
                    update_post_meta($post_id, '_fish_diet', sanitize_text_field($_POST['fish_diet']));
                }
                
                if (isset($_POST['fish_lifespan'])) {
                    update_post_meta($post_id, '_fish_lifespan', sanitize_text_field($_POST['fish_lifespan']));
                }
                
                if (isset($_POST['fish_difficulty'])) {
                    update_post_meta($post_id, '_fish_difficulty', sanitize_text_field($_POST['fish_difficulty']));
                }
                
                if (isset($_POST['fish_price'])) {
                    update_post_meta($post_id, '_fish_price', sanitize_text_field($_POST['fish_price']));
                }
                
                if (isset($_POST['fish_stock'])) {
                    update_post_meta($post_id, '_fish_stock', absint($_POST['fish_stock']));
                }
                
                if (isset($_POST['fish_sku'])) {
                    update_post_meta($post_id, '_fish_sku', sanitize_text_field($_POST['fish_sku']));
                }
            }
        }
        
        // Event meta box
        if (isset($_POST['aqualuxe_event_meta_box_nonce']) && wp_verify_nonce($_POST['aqualuxe_event_meta_box_nonce'], 'aqualuxe_event_meta_box')) {
            if (current_user_can('edit_post', $post_id)) {
                if (isset($_POST['event_start_date'])) {
                    update_post_meta($post_id, '_event_start_date', sanitize_text_field($_POST['event_start_date']));
                }
                
                if (isset($_POST['event_end_date'])) {
                    update_post_meta($post_id, '_event_end_date', sanitize_text_field($_POST['event_end_date']));
                }
                
                if (isset($_POST['event_time'])) {
                    update_post_meta($post_id, '_event_time', sanitize_text_field($_POST['event_time']));
                }
                
                if (isset($_POST['event_location'])) {
                    update_post_meta($post_id, '_event_location', sanitize_text_field($_POST['event_location']));
                }
                
                if (isset($_POST['event_address'])) {
                    update_post_meta($post_id, '_event_address', sanitize_textarea_field($_POST['event_address']));
                }
                
                if (isset($_POST['event_map'])) {
                    update_post_meta($post_id, '_event_map', esc_url_raw($_POST['event_map']));
                }
                
                if (isset($_POST['event_cost'])) {
                    update_post_meta($post_id, '_event_cost', sanitize_text_field($_POST['event_cost']));
                }
                
                if (isset($_POST['event_registration_url'])) {
                    update_post_meta($post_id, '_event_registration_url', esc_url_raw($_POST['event_registration_url']));
                }
                
                if (isset($_POST['event_capacity'])) {
                    update_post_meta($post_id, '_event_capacity', absint($_POST['event_capacity']));
                }
            }
        }
    }

    /**
     * Add custom columns to service post type
     * 
     * @param array $columns Columns array.
     * @return array
     */
    public function service_columns($columns) {
        $columns = array(
            'cb'               => $columns['cb'],
            'title'            => esc_html__('Title', 'aqualuxe'),
            'service_price'    => esc_html__('Price', 'aqualuxe'),
            'service_duration' => esc_html__('Duration', 'aqualuxe'),
            'service_category' => esc_html__('Category', 'aqualuxe'),
            'date'             => $columns['date'],
        );
        
        return $columns;
    }

    /**
     * Add content to custom columns for service post type
     * 
     * @param string $column  Column name.
     * @param int    $post_id Post ID.
     */
    public function service_custom_column($column, $post_id) {
        switch ($column) {
            case 'service_price':
                echo esc_html(get_post_meta($post_id, '_service_price', true));
                break;
            
            case 'service_duration':
                echo esc_html(get_post_meta($post_id, '_service_duration', true));
                break;
            
            case 'service_category':
                $terms = get_the_terms($post_id, 'service_category');
                if (!empty($terms)) {
                    $term_names = array();
                    foreach ($terms as $term) {
                        $term_names[] = $term->name;
                    }
                    echo esc_html(implode(', ', $term_names));
                }
                break;
        }
    }

    /**
     * Add custom columns to testimonial post type
     * 
     * @param array $columns Columns array.
     * @return array
     */
    public function testimonial_columns($columns) {
        $columns = array(
            'cb'                  => $columns['cb'],
            'title'               => esc_html__('Title', 'aqualuxe'),
            'testimonial_author'  => esc_html__('Author', 'aqualuxe'),
            'testimonial_company' => esc_html__('Company', 'aqualuxe'),
            'testimonial_rating'  => esc_html__('Rating', 'aqualuxe'),
            'date'                => $columns['date'],
        );
        
        return $columns;
    }

    /**
     * Add content to custom columns for testimonial post type
     * 
     * @param string $column  Column name.
     * @param int    $post_id Post ID.
     */
    public function testimonial_custom_column($column, $post_id) {
        switch ($column) {
            case 'testimonial_author':
                echo esc_html(get_post_meta($post_id, '_testimonial_author', true));
                break;
            
            case 'testimonial_company':
                echo esc_html(get_post_meta($post_id, '_testimonial_company', true));
                break;
            
            case 'testimonial_rating':
                $rating = get_post_meta($post_id, '_testimonial_rating', true);
                if ($rating) {
                    echo esc_html($rating) . ' / 5';
                }
                break;
        }
    }

    /**
     * Add custom columns to team post type
     * 
     * @param array $columns Columns array.
     * @return array
     */
    public function team_columns($columns) {
        $columns = array(
            'cb'             => $columns['cb'],
            'thumbnail'      => esc_html__('Photo', 'aqualuxe'),
            'title'          => esc_html__('Name', 'aqualuxe'),
            'team_position'  => esc_html__('Position', 'aqualuxe'),
            'team_email'     => esc_html__('Email', 'aqualuxe'),
            'team_department' => esc_html__('Department', 'aqualuxe'),
            'date'           => $columns['date'],
        );
        
        return $columns;
    }

    /**
     * Add content to custom columns for team post type
     * 
     * @param string $column  Column name.
     * @param int    $post_id Post ID.
     */
    public function team_custom_column($column, $post_id) {
        switch ($column) {
            case 'thumbnail':
                if (has_post_thumbnail($post_id)) {
                    echo get_the_post_thumbnail($post_id, array(50, 50));
                }
                break;
            
            case 'team_position':
                echo esc_html(get_post_meta($post_id, '_team_position', true));
                break;
            
            case 'team_email':
                echo esc_html(get_post_meta($post_id, '_team_email', true));
                break;
            
            case 'team_department':
                $terms = get_the_terms($post_id, 'team_department');
                if (!empty($terms)) {
                    $term_names = array();
                    foreach ($terms as $term) {
                        $term_names[] = $term->name;
                    }
                    echo esc_html(implode(', ', $term_names));
                }
                break;
        }
    }

    /**
     * Add custom columns to fish post type
     * 
     * @param array $columns Columns array.
     * @return array
     */
    public function fish_columns($columns) {
        $columns = array(
            'cb'              => $columns['cb'],
            'thumbnail'       => esc_html__('Image', 'aqualuxe'),
            'title'           => esc_html__('Name', 'aqualuxe'),
            'fish_scientific' => esc_html__('Scientific Name', 'aqualuxe'),
            'fish_price'      => esc_html__('Price', 'aqualuxe'),
            'fish_stock'      => esc_html__('Stock', 'aqualuxe'),
            'fish_species'    => esc_html__('Species', 'aqualuxe'),
            'fish_water_type' => esc_html__('Water Type', 'aqualuxe'),
            'date'            => $columns['date'],
        );
        
        return $columns;
    }

    /**
     * Add content to custom columns for fish post type
     * 
     * @param string $column  Column name.
     * @param int    $post_id Post ID.
     */
    public function fish_custom_column($column, $post_id) {
        switch ($column) {
            case 'thumbnail':
                if (has_post_thumbnail($post_id)) {
                    echo get_the_post_thumbnail($post_id, array(50, 50));
                }
                break;
            
            case 'fish_scientific':
                echo esc_html(get_post_meta($post_id, '_fish_scientific_name', true));
                break;
            
            case 'fish_price':
                echo esc_html(get_post_meta($post_id, '_fish_price', true));
                break;
            
            case 'fish_stock':
                echo esc_html(get_post_meta($post_id, '_fish_stock', true));
                break;
            
            case 'fish_species':
                $terms = get_the_terms($post_id, 'fish_species');
                if (!empty($terms)) {
                    $term_names = array();
                    foreach ($terms as $term) {
                        $term_names[] = $term->name;
                    }
                    echo esc_html(implode(', ', $term_names));
                }
                break;
            
            case 'fish_water_type':
                $terms = get_the_terms($post_id, 'fish_water_type');
                if (!empty($terms)) {
                    $term_names = array();
                    foreach ($terms as $term) {
                        $term_names[] = $term->name;
                    }
                    echo esc_html(implode(', ', $term_names));
                }
                break;
        }
    }
}

// Initialize the class
new AquaLuxe_Post_Types();