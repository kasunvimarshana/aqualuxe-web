<?php
/**
 * AquaLuxe Custom Post Types
 *
 * @package AquaLuxe
 * @version 1.0.0
 * @since   1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Custom Post Types Class
 *
 * @class AquaLuxe_Post_Types
 */
class AquaLuxe_Post_Types {

    /**
     * Single instance of the class
     *
     * @var AquaLuxe_Post_Types
     */
    protected static $_instance = null;

    /**
     * Main AquaLuxe_Post_Types Instance
     *
     * @static
     * @return AquaLuxe_Post_Types - Main instance
     */
    public static function get_instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->init_hooks();
    }

    /**
     * Hook into actions and filters
     */
    private function init_hooks() {
        add_action('init', array($this, 'register_post_types'));
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post', array($this, 'save_meta_boxes'));
        add_filter('enter_title_here', array($this, 'change_title_placeholder'));
        add_filter('manage_edit-service_columns', array($this, 'service_columns'));
        add_action('manage_service_posts_custom_column', array($this, 'service_custom_columns'), 10, 2);
        add_filter('manage_edit-event_columns', array($this, 'event_columns'));
        add_action('manage_event_posts_custom_column', array($this, 'event_custom_columns'), 10, 2);
    }

    /**
     * Register custom post types
     */
    public function register_post_types() {
        // Services Post Type
        $this->register_services();
        
        // Events Post Type
        $this->register_events();
        
        // Team Members Post Type
        $this->register_team_members();
        
        // Testimonials Post Type
        $this->register_testimonials();
        
        // FAQ Post Type
        $this->register_faq();
        
        // Portfolio Post Type
        $this->register_portfolio();
    }

    /**
     * Register Services post type
     */
    private function register_services() {
        $labels = array(
            'name'                  => esc_html_x('Services', 'Post Type General Name', 'aqualuxe'),
            'singular_name'         => esc_html_x('Service', 'Post Type Singular Name', 'aqualuxe'),
            'menu_name'             => esc_html__('Services', 'aqualuxe'),
            'name_admin_bar'        => esc_html__('Service', 'aqualuxe'),
            'archives'              => esc_html__('Service Archives', 'aqualuxe'),
            'attributes'            => esc_html__('Service Attributes', 'aqualuxe'),
            'parent_item_colon'     => esc_html__('Parent Service:', 'aqualuxe'),
            'all_items'             => esc_html__('All Services', 'aqualuxe'),
            'add_new_item'          => esc_html__('Add New Service', 'aqualuxe'),
            'add_new'               => esc_html__('Add New', 'aqualuxe'),
            'new_item'              => esc_html__('New Service', 'aqualuxe'),
            'edit_item'             => esc_html__('Edit Service', 'aqualuxe'),
            'update_item'           => esc_html__('Update Service', 'aqualuxe'),
            'view_item'             => esc_html__('View Service', 'aqualuxe'),
            'view_items'            => esc_html__('View Services', 'aqualuxe'),
            'search_items'          => esc_html__('Search Service', 'aqualuxe'),
            'not_found'             => esc_html__('Not found', 'aqualuxe'),
            'not_found_in_trash'    => esc_html__('Not found in Trash', 'aqualuxe'),
            'featured_image'        => esc_html__('Service Image', 'aqualuxe'),
            'set_featured_image'    => esc_html__('Set service image', 'aqualuxe'),
            'remove_featured_image' => esc_html__('Remove service image', 'aqualuxe'),
            'use_featured_image'    => esc_html__('Use as service image', 'aqualuxe'),
        );

        $args = array(
            'label'                 => esc_html__('Service', 'aqualuxe'),
            'description'           => esc_html__('Aquatic services offered', 'aqualuxe'),
            'labels'                => $labels,
            'supports'              => array('title', 'editor', 'thumbnail', 'excerpt', 'revisions', 'page-attributes'),
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 25,
            'menu_icon'             => 'dashicons-admin-tools',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true,
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'capability_type'       => 'post',
            'show_in_rest'          => true,
            'rewrite'               => array('slug' => 'services'),
        );

        register_post_type('service', $args);
    }

    /**
     * Register Events post type
     */
    private function register_events() {
        $labels = array(
            'name'                  => esc_html_x('Events', 'Post Type General Name', 'aqualuxe'),
            'singular_name'         => esc_html_x('Event', 'Post Type Singular Name', 'aqualuxe'),
            'menu_name'             => esc_html__('Events', 'aqualuxe'),
            'name_admin_bar'        => esc_html__('Event', 'aqualuxe'),
            'archives'              => esc_html__('Event Archives', 'aqualuxe'),
            'attributes'            => esc_html__('Event Attributes', 'aqualuxe'),
            'parent_item_colon'     => esc_html__('Parent Event:', 'aqualuxe'),
            'all_items'             => esc_html__('All Events', 'aqualuxe'),
            'add_new_item'          => esc_html__('Add New Event', 'aqualuxe'),
            'add_new'               => esc_html__('Add New', 'aqualuxe'),
            'new_item'              => esc_html__('New Event', 'aqualuxe'),
            'edit_item'             => esc_html__('Edit Event', 'aqualuxe'),
            'update_item'           => esc_html__('Update Event', 'aqualuxe'),
            'view_item'             => esc_html__('View Event', 'aqualuxe'),
            'view_items'            => esc_html__('View Events', 'aqualuxe'),
            'search_items'          => esc_html__('Search Event', 'aqualuxe'),
            'not_found'             => esc_html__('Not found', 'aqualuxe'),
            'not_found_in_trash'    => esc_html__('Not found in Trash', 'aqualuxe'),
            'featured_image'        => esc_html__('Event Image', 'aqualuxe'),
            'set_featured_image'    => esc_html__('Set event image', 'aqualuxe'),
            'remove_featured_image' => esc_html__('Remove event image', 'aqualuxe'),
            'use_featured_image'    => esc_html__('Use as event image', 'aqualuxe'),
        );

        $args = array(
            'label'                 => esc_html__('Event', 'aqualuxe'),
            'description'           => esc_html__('Aquatic events and workshops', 'aqualuxe'),
            'labels'                => $labels,
            'supports'              => array('title', 'editor', 'thumbnail', 'excerpt', 'revisions'),
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 26,
            'menu_icon'             => 'dashicons-calendar-alt',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true,
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'capability_type'       => 'post',
            'show_in_rest'          => true,
            'rewrite'               => array('slug' => 'events'),
        );

        register_post_type('event', $args);
    }

    /**
     * Register Team Members post type
     */
    private function register_team_members() {
        $labels = array(
            'name'                  => esc_html_x('Team Members', 'Post Type General Name', 'aqualuxe'),
            'singular_name'         => esc_html_x('Team Member', 'Post Type Singular Name', 'aqualuxe'),
            'menu_name'             => esc_html__('Team', 'aqualuxe'),
            'name_admin_bar'        => esc_html__('Team Member', 'aqualuxe'),
            'archives'              => esc_html__('Team Archives', 'aqualuxe'),
            'attributes'            => esc_html__('Team Member Attributes', 'aqualuxe'),
            'parent_item_colon'     => esc_html__('Parent Team Member:', 'aqualuxe'),
            'all_items'             => esc_html__('All Team Members', 'aqualuxe'),
            'add_new_item'          => esc_html__('Add New Team Member', 'aqualuxe'),
            'add_new'               => esc_html__('Add New', 'aqualuxe'),
            'new_item'              => esc_html__('New Team Member', 'aqualuxe'),
            'edit_item'             => esc_html__('Edit Team Member', 'aqualuxe'),
            'update_item'           => esc_html__('Update Team Member', 'aqualuxe'),
            'view_item'             => esc_html__('View Team Member', 'aqualuxe'),
            'view_items'            => esc_html__('View Team Members', 'aqualuxe'),
            'search_items'          => esc_html__('Search Team Member', 'aqualuxe'),
            'not_found'             => esc_html__('Not found', 'aqualuxe'),
            'not_found_in_trash'    => esc_html__('Not found in Trash', 'aqualuxe'),
            'featured_image'        => esc_html__('Member Photo', 'aqualuxe'),
            'set_featured_image'    => esc_html__('Set member photo', 'aqualuxe'),
            'remove_featured_image' => esc_html__('Remove member photo', 'aqualuxe'),
            'use_featured_image'    => esc_html__('Use as member photo', 'aqualuxe'),
        );

        $args = array(
            'label'                 => esc_html__('Team Member', 'aqualuxe'),
            'description'           => esc_html__('Team members and staff', 'aqualuxe'),
            'labels'                => $labels,
            'supports'              => array('title', 'editor', 'thumbnail', 'excerpt', 'page-attributes'),
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 27,
            'menu_icon'             => 'dashicons-groups',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => false,
            'can_export'            => true,
            'has_archive'           => true,
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'capability_type'       => 'post',
            'show_in_rest'          => true,
            'rewrite'               => array('slug' => 'team'),
        );

        register_post_type('team_member', $args);
    }

    /**
     * Register Testimonials post type
     */
    private function register_testimonials() {
        $labels = array(
            'name'                  => esc_html_x('Testimonials', 'Post Type General Name', 'aqualuxe'),
            'singular_name'         => esc_html_x('Testimonial', 'Post Type Singular Name', 'aqualuxe'),
            'menu_name'             => esc_html__('Testimonials', 'aqualuxe'),
            'name_admin_bar'        => esc_html__('Testimonial', 'aqualuxe'),
            'archives'              => esc_html__('Testimonial Archives', 'aqualuxe'),
            'attributes'            => esc_html__('Testimonial Attributes', 'aqualuxe'),
            'parent_item_colon'     => esc_html__('Parent Testimonial:', 'aqualuxe'),
            'all_items'             => esc_html__('All Testimonials', 'aqualuxe'),
            'add_new_item'          => esc_html__('Add New Testimonial', 'aqualuxe'),
            'add_new'               => esc_html__('Add New', 'aqualuxe'),
            'new_item'              => esc_html__('New Testimonial', 'aqualuxe'),
            'edit_item'             => esc_html__('Edit Testimonial', 'aqualuxe'),
            'update_item'           => esc_html__('Update Testimonial', 'aqualuxe'),
            'view_item'             => esc_html__('View Testimonial', 'aqualuxe'),
            'view_items'            => esc_html__('View Testimonials', 'aqualuxe'),
            'search_items'          => esc_html__('Search Testimonial', 'aqualuxe'),
            'not_found'             => esc_html__('Not found', 'aqualuxe'),
            'not_found_in_trash'    => esc_html__('Not found in Trash', 'aqualuxe'),
            'featured_image'        => esc_html__('Client Photo', 'aqualuxe'),
            'set_featured_image'    => esc_html__('Set client photo', 'aqualuxe'),
            'remove_featured_image' => esc_html__('Remove client photo', 'aqualuxe'),
            'use_featured_image'    => esc_html__('Use as client photo', 'aqualuxe'),
        );

        $args = array(
            'label'                 => esc_html__('Testimonial', 'aqualuxe'),
            'description'           => esc_html__('Customer testimonials and reviews', 'aqualuxe'),
            'labels'                => $labels,
            'supports'              => array('title', 'editor', 'thumbnail', 'page-attributes'),
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 28,
            'menu_icon'             => 'dashicons-format-quote',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => false,
            'can_export'            => true,
            'has_archive'           => false,
            'exclude_from_search'   => true,
            'publicly_queryable'    => false,
            'capability_type'       => 'post',
            'show_in_rest'          => true,
            'rewrite'               => array('slug' => 'testimonials'),
        );

        register_post_type('testimonial', $args);
    }

    /**
     * Register FAQ post type
     */
    private function register_faq() {
        $labels = array(
            'name'                  => esc_html_x('FAQs', 'Post Type General Name', 'aqualuxe'),
            'singular_name'         => esc_html_x('FAQ', 'Post Type Singular Name', 'aqualuxe'),
            'menu_name'             => esc_html__('FAQs', 'aqualuxe'),
            'name_admin_bar'        => esc_html__('FAQ', 'aqualuxe'),
            'archives'              => esc_html__('FAQ Archives', 'aqualuxe'),
            'attributes'            => esc_html__('FAQ Attributes', 'aqualuxe'),
            'parent_item_colon'     => esc_html__('Parent FAQ:', 'aqualuxe'),
            'all_items'             => esc_html__('All FAQs', 'aqualuxe'),
            'add_new_item'          => esc_html__('Add New FAQ', 'aqualuxe'),
            'add_new'               => esc_html__('Add New', 'aqualuxe'),
            'new_item'              => esc_html__('New FAQ', 'aqualuxe'),
            'edit_item'             => esc_html__('Edit FAQ', 'aqualuxe'),
            'update_item'           => esc_html__('Update FAQ', 'aqualuxe'),
            'view_item'             => esc_html__('View FAQ', 'aqualuxe'),
            'view_items'            => esc_html__('View FAQs', 'aqualuxe'),
            'search_items'          => esc_html__('Search FAQ', 'aqualuxe'),
            'not_found'             => esc_html__('Not found', 'aqualuxe'),
            'not_found_in_trash'    => esc_html__('Not found in Trash', 'aqualuxe'),
        );

        $args = array(
            'label'                 => esc_html__('FAQ', 'aqualuxe'),
            'description'           => esc_html__('Frequently asked questions', 'aqualuxe'),
            'labels'                => $labels,
            'supports'              => array('title', 'editor', 'page-attributes'),
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 29,
            'menu_icon'             => 'dashicons-editor-help',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => false,
            'can_export'            => true,
            'has_archive'           => true,
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'capability_type'       => 'post',
            'show_in_rest'          => true,
            'rewrite'               => array('slug' => 'faq'),
        );

        register_post_type('faq', $args);
    }

    /**
     * Register Portfolio post type
     */
    private function register_portfolio() {
        $labels = array(
            'name'                  => esc_html_x('Portfolio', 'Post Type General Name', 'aqualuxe'),
            'singular_name'         => esc_html_x('Portfolio Item', 'Post Type Singular Name', 'aqualuxe'),
            'menu_name'             => esc_html__('Portfolio', 'aqualuxe'),
            'name_admin_bar'        => esc_html__('Portfolio Item', 'aqualuxe'),
            'archives'              => esc_html__('Portfolio Archives', 'aqualuxe'),
            'attributes'            => esc_html__('Portfolio Attributes', 'aqualuxe'),
            'parent_item_colon'     => esc_html__('Parent Portfolio Item:', 'aqualuxe'),
            'all_items'             => esc_html__('All Portfolio Items', 'aqualuxe'),
            'add_new_item'          => esc_html__('Add New Portfolio Item', 'aqualuxe'),
            'add_new'               => esc_html__('Add New', 'aqualuxe'),
            'new_item'              => esc_html__('New Portfolio Item', 'aqualuxe'),
            'edit_item'             => esc_html__('Edit Portfolio Item', 'aqualuxe'),
            'update_item'           => esc_html__('Update Portfolio Item', 'aqualuxe'),
            'view_item'             => esc_html__('View Portfolio Item', 'aqualuxe'),
            'view_items'            => esc_html__('View Portfolio', 'aqualuxe'),
            'search_items'          => esc_html__('Search Portfolio', 'aqualuxe'),
            'not_found'             => esc_html__('Not found', 'aqualuxe'),
            'not_found_in_trash'    => esc_html__('Not found in Trash', 'aqualuxe'),
            'featured_image'        => esc_html__('Portfolio Image', 'aqualuxe'),
            'set_featured_image'    => esc_html__('Set portfolio image', 'aqualuxe'),
            'remove_featured_image' => esc_html__('Remove portfolio image', 'aqualuxe'),
            'use_featured_image'    => esc_html__('Use as portfolio image', 'aqualuxe'),
        );

        $args = array(
            'label'                 => esc_html__('Portfolio Item', 'aqualuxe'),
            'description'           => esc_html__('Portfolio of completed projects', 'aqualuxe'),
            'labels'                => $labels,
            'supports'              => array('title', 'editor', 'thumbnail', 'excerpt', 'page-attributes'),
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 30,
            'menu_icon'             => 'dashicons-portfolio',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true,
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'capability_type'       => 'post',
            'show_in_rest'          => true,
            'rewrite'               => array('slug' => 'portfolio'),
        );

        register_post_type('portfolio', $args);
    }

    /**
     * Add meta boxes
     */
    public function add_meta_boxes() {
        // Service meta boxes
        add_meta_box(
            'service_details',
            esc_html__('Service Details', 'aqualuxe'),
            array($this, 'service_details_callback'),
            'service',
            'normal',
            'high'
        );

        // Event meta boxes
        add_meta_box(
            'event_details',
            esc_html__('Event Details', 'aqualuxe'),
            array($this, 'event_details_callback'),
            'event',
            'normal',
            'high'
        );

        // Team member meta boxes
        add_meta_box(
            'team_member_details',
            esc_html__('Member Details', 'aqualuxe'),
            array($this, 'team_member_details_callback'),
            'team_member',
            'normal',
            'high'
        );

        // Testimonial meta boxes
        add_meta_box(
            'testimonial_details',
            esc_html__('Testimonial Details', 'aqualuxe'),
            array($this, 'testimonial_details_callback'),
            'testimonial',
            'normal',
            'high'
        );

        // Portfolio meta boxes
        add_meta_box(
            'portfolio_details',
            esc_html__('Portfolio Details', 'aqualuxe'),
            array($this, 'portfolio_details_callback'),
            'portfolio',
            'normal',
            'high'
        );
    }

    /**
     * Service details meta box callback
     */
    public function service_details_callback($post) {
        wp_nonce_field('aqualuxe_service_meta_box', 'aqualuxe_service_meta_box_nonce');
        
        $price = get_post_meta($post->ID, '_aqualuxe_service_price', true);
        $duration = get_post_meta($post->ID, '_aqualuxe_service_duration', true);
        $includes = get_post_meta($post->ID, '_aqualuxe_service_includes', true);
        $icon = get_post_meta($post->ID, '_aqualuxe_service_icon', true);
        ?>
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="aqualuxe_service_price"><?php esc_html_e('Price', 'aqualuxe'); ?></label>
                </th>
                <td>
                    <input type="text" id="aqualuxe_service_price" name="aqualuxe_service_price" value="<?php echo esc_attr($price); ?>" placeholder="<?php esc_attr_e('e.g., $99', 'aqualuxe'); ?>" />
                    <p class="description"><?php esc_html_e('Service price (leave empty if price varies)', 'aqualuxe'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="aqualuxe_service_duration"><?php esc_html_e('Duration', 'aqualuxe'); ?></label>
                </th>
                <td>
                    <input type="text" id="aqualuxe_service_duration" name="aqualuxe_service_duration" value="<?php echo esc_attr($duration); ?>" placeholder="<?php esc_attr_e('e.g., 2 hours', 'aqualuxe'); ?>" />
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="aqualuxe_service_includes"><?php esc_html_e('What\'s Included', 'aqualuxe'); ?></label>
                </th>
                <td>
                    <textarea id="aqualuxe_service_includes" name="aqualuxe_service_includes" rows="4" cols="50"><?php echo esc_textarea($includes); ?></textarea>
                    <p class="description"><?php esc_html_e('List what is included in this service (one item per line)', 'aqualuxe'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="aqualuxe_service_icon"><?php esc_html_e('Icon Class', 'aqualuxe'); ?></label>
                </th>
                <td>
                    <input type="text" id="aqualuxe_service_icon" name="aqualuxe_service_icon" value="<?php echo esc_attr($icon); ?>" placeholder="<?php esc_attr_e('e.g., fas fa-fish', 'aqualuxe'); ?>" />
                    <p class="description"><?php esc_html_e('Font Awesome icon class for this service', 'aqualuxe'); ?></p>
                </td>
            </tr>
        </table>
        <?php
    }

    /**
     * Event details meta box callback
     */
    public function event_details_callback($post) {
        wp_nonce_field('aqualuxe_event_meta_box', 'aqualuxe_event_meta_box_nonce');
        
        $start_date = get_post_meta($post->ID, '_aqualuxe_event_start_date', true);
        $end_date = get_post_meta($post->ID, '_aqualuxe_event_end_date', true);
        $location = get_post_meta($post->ID, '_aqualuxe_event_location', true);
        $price = get_post_meta($post->ID, '_aqualuxe_event_price', true);
        $capacity = get_post_meta($post->ID, '_aqualuxe_event_capacity', true);
        ?>
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="aqualuxe_event_start_date"><?php esc_html_e('Start Date', 'aqualuxe'); ?></label>
                </th>
                <td>
                    <input type="datetime-local" id="aqualuxe_event_start_date" name="aqualuxe_event_start_date" value="<?php echo esc_attr($start_date); ?>" />
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="aqualuxe_event_end_date"><?php esc_html_e('End Date', 'aqualuxe'); ?></label>
                </th>
                <td>
                    <input type="datetime-local" id="aqualuxe_event_end_date" name="aqualuxe_event_end_date" value="<?php echo esc_attr($end_date); ?>" />
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="aqualuxe_event_location"><?php esc_html_e('Location', 'aqualuxe'); ?></label>
                </th>
                <td>
                    <input type="text" id="aqualuxe_event_location" name="aqualuxe_event_location" value="<?php echo esc_attr($location); ?>" />
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="aqualuxe_event_price"><?php esc_html_e('Price', 'aqualuxe'); ?></label>
                </th>
                <td>
                    <input type="text" id="aqualuxe_event_price" name="aqualuxe_event_price" value="<?php echo esc_attr($price); ?>" placeholder="<?php esc_attr_e('e.g., $25 or Free', 'aqualuxe'); ?>" />
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="aqualuxe_event_capacity"><?php esc_html_e('Capacity', 'aqualuxe'); ?></label>
                </th>
                <td>
                    <input type="number" id="aqualuxe_event_capacity" name="aqualuxe_event_capacity" value="<?php echo esc_attr($capacity); ?>" min="1" />
                    <p class="description"><?php esc_html_e('Maximum number of attendees', 'aqualuxe'); ?></p>
                </td>
            </tr>
        </table>
        <?php
    }

    /**
     * Team member details meta box callback
     */
    public function team_member_details_callback($post) {
        wp_nonce_field('aqualuxe_team_member_meta_box', 'aqualuxe_team_member_meta_box_nonce');
        
        $position = get_post_meta($post->ID, '_aqualuxe_team_position', true);
        $email = get_post_meta($post->ID, '_aqualuxe_team_email', true);
        $phone = get_post_meta($post->ID, '_aqualuxe_team_phone', true);
        $linkedin = get_post_meta($post->ID, '_aqualuxe_team_linkedin', true);
        $twitter = get_post_meta($post->ID, '_aqualuxe_team_twitter', true);
        ?>
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="aqualuxe_team_position"><?php esc_html_e('Position', 'aqualuxe'); ?></label>
                </th>
                <td>
                    <input type="text" id="aqualuxe_team_position" name="aqualuxe_team_position" value="<?php echo esc_attr($position); ?>" />
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="aqualuxe_team_email"><?php esc_html_e('Email', 'aqualuxe'); ?></label>
                </th>
                <td>
                    <input type="email" id="aqualuxe_team_email" name="aqualuxe_team_email" value="<?php echo esc_attr($email); ?>" />
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="aqualuxe_team_phone"><?php esc_html_e('Phone', 'aqualuxe'); ?></label>
                </th>
                <td>
                    <input type="tel" id="aqualuxe_team_phone" name="aqualuxe_team_phone" value="<?php echo esc_attr($phone); ?>" />
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="aqualuxe_team_linkedin"><?php esc_html_e('LinkedIn URL', 'aqualuxe'); ?></label>
                </th>
                <td>
                    <input type="url" id="aqualuxe_team_linkedin" name="aqualuxe_team_linkedin" value="<?php echo esc_attr($linkedin); ?>" />
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="aqualuxe_team_twitter"><?php esc_html_e('Twitter URL', 'aqualuxe'); ?></label>
                </th>
                <td>
                    <input type="url" id="aqualuxe_team_twitter" name="aqualuxe_team_twitter" value="<?php echo esc_attr($twitter); ?>" />
                </td>
            </tr>
        </table>
        <?php
    }

    /**
     * Testimonial details meta box callback
     */
    public function testimonial_details_callback($post) {
        wp_nonce_field('aqualuxe_testimonial_meta_box', 'aqualuxe_testimonial_meta_box_nonce');
        
        $client_name = get_post_meta($post->ID, '_aqualuxe_testimonial_client_name', true);
        $client_position = get_post_meta($post->ID, '_aqualuxe_testimonial_client_position', true);
        $client_company = get_post_meta($post->ID, '_aqualuxe_testimonial_client_company', true);
        $rating = get_post_meta($post->ID, '_aqualuxe_testimonial_rating', true);
        ?>
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="aqualuxe_testimonial_client_name"><?php esc_html_e('Client Name', 'aqualuxe'); ?></label>
                </th>
                <td>
                    <input type="text" id="aqualuxe_testimonial_client_name" name="aqualuxe_testimonial_client_name" value="<?php echo esc_attr($client_name); ?>" />
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="aqualuxe_testimonial_client_position"><?php esc_html_e('Client Position', 'aqualuxe'); ?></label>
                </th>
                <td>
                    <input type="text" id="aqualuxe_testimonial_client_position" name="aqualuxe_testimonial_client_position" value="<?php echo esc_attr($client_position); ?>" />
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="aqualuxe_testimonial_client_company"><?php esc_html_e('Client Company', 'aqualuxe'); ?></label>
                </th>
                <td>
                    <input type="text" id="aqualuxe_testimonial_client_company" name="aqualuxe_testimonial_client_company" value="<?php echo esc_attr($client_company); ?>" />
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="aqualuxe_testimonial_rating"><?php esc_html_e('Rating', 'aqualuxe'); ?></label>
                </th>
                <td>
                    <select id="aqualuxe_testimonial_rating" name="aqualuxe_testimonial_rating">
                        <option value=""><?php esc_html_e('Select Rating', 'aqualuxe'); ?></option>
                        <?php for ($i = 1; $i <= 5; $i++) : ?>
                            <option value="<?php echo $i; ?>" <?php selected($rating, $i); ?>>
                                <?php echo $i . ' ' . _n('Star', 'Stars', $i, 'aqualuxe'); ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </td>
            </tr>
        </table>
        <?php
    }

    /**
     * Portfolio details meta box callback
     */
    public function portfolio_details_callback($post) {
        wp_nonce_field('aqualuxe_portfolio_meta_box', 'aqualuxe_portfolio_meta_box_nonce');
        
        $client = get_post_meta($post->ID, '_aqualuxe_portfolio_client', true);
        $project_url = get_post_meta($post->ID, '_aqualuxe_portfolio_url', true);
        $completion_date = get_post_meta($post->ID, '_aqualuxe_portfolio_completion_date', true);
        $technologies = get_post_meta($post->ID, '_aqualuxe_portfolio_technologies', true);
        ?>
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="aqualuxe_portfolio_client"><?php esc_html_e('Client', 'aqualuxe'); ?></label>
                </th>
                <td>
                    <input type="text" id="aqualuxe_portfolio_client" name="aqualuxe_portfolio_client" value="<?php echo esc_attr($client); ?>" />
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="aqualuxe_portfolio_url"><?php esc_html_e('Project URL', 'aqualuxe'); ?></label>
                </th>
                <td>
                    <input type="url" id="aqualuxe_portfolio_url" name="aqualuxe_portfolio_url" value="<?php echo esc_attr($project_url); ?>" />
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="aqualuxe_portfolio_completion_date"><?php esc_html_e('Completion Date', 'aqualuxe'); ?></label>
                </th>
                <td>
                    <input type="date" id="aqualuxe_portfolio_completion_date" name="aqualuxe_portfolio_completion_date" value="<?php echo esc_attr($completion_date); ?>" />
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="aqualuxe_portfolio_technologies"><?php esc_html_e('Technologies Used', 'aqualuxe'); ?></label>
                </th>
                <td>
                    <input type="text" id="aqualuxe_portfolio_technologies" name="aqualuxe_portfolio_technologies" value="<?php echo esc_attr($technologies); ?>" />
                    <p class="description"><?php esc_html_e('Comma-separated list of technologies', 'aqualuxe'); ?></p>
                </td>
            </tr>
        </table>
        <?php
    }

    /**
     * Save meta box data
     */
    public function save_meta_boxes($post_id) {
        // Check if nonce is valid
        $nonce_fields = array(
            'service' => 'aqualuxe_service_meta_box_nonce',
            'event' => 'aqualuxe_event_meta_box_nonce',
            'team_member' => 'aqualuxe_team_member_meta_box_nonce',
            'testimonial' => 'aqualuxe_testimonial_meta_box_nonce',
            'portfolio' => 'aqualuxe_portfolio_meta_box_nonce'
        );
        
        $post_type = get_post_type($post_id);
        
        if (!isset($nonce_fields[$post_type]) || !wp_verify_nonce($_POST[$nonce_fields[$post_type]] ?? '', $nonce_fields[$post_type] . '_box')) {
            return;
        }
        
        // Check if user has permission to edit the post
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        // Don't save on autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        // Save meta data based on post type
        switch ($post_type) {
            case 'service':
                $this->save_service_meta($post_id);
                break;
            case 'event':
                $this->save_event_meta($post_id);
                break;
            case 'team_member':
                $this->save_team_member_meta($post_id);
                break;
            case 'testimonial':
                $this->save_testimonial_meta($post_id);
                break;
            case 'portfolio':
                $this->save_portfolio_meta($post_id);
                break;
        }
    }

    /**
     * Save service meta data
     */
    private function save_service_meta($post_id) {
        $fields = array(
            'aqualuxe_service_price' => '_aqualuxe_service_price',
            'aqualuxe_service_duration' => '_aqualuxe_service_duration',
            'aqualuxe_service_includes' => '_aqualuxe_service_includes',
            'aqualuxe_service_icon' => '_aqualuxe_service_icon'
        );
        
        foreach ($fields as $field => $meta_key) {
            if (isset($_POST[$field])) {
                update_post_meta($post_id, $meta_key, sanitize_text_field($_POST[$field]));
            }
        }
    }

    /**
     * Save event meta data
     */
    private function save_event_meta($post_id) {
        $fields = array(
            'aqualuxe_event_start_date' => '_aqualuxe_event_start_date',
            'aqualuxe_event_end_date' => '_aqualuxe_event_end_date',
            'aqualuxe_event_location' => '_aqualuxe_event_location',
            'aqualuxe_event_price' => '_aqualuxe_event_price',
            'aqualuxe_event_capacity' => '_aqualuxe_event_capacity'
        );
        
        foreach ($fields as $field => $meta_key) {
            if (isset($_POST[$field])) {
                $value = $field === 'aqualuxe_event_capacity' ? intval($_POST[$field]) : sanitize_text_field($_POST[$field]);
                update_post_meta($post_id, $meta_key, $value);
            }
        }
    }

    /**
     * Save team member meta data
     */
    private function save_team_member_meta($post_id) {
        $fields = array(
            'aqualuxe_team_position' => '_aqualuxe_team_position',
            'aqualuxe_team_email' => '_aqualuxe_team_email',
            'aqualuxe_team_phone' => '_aqualuxe_team_phone',
            'aqualuxe_team_linkedin' => '_aqualuxe_team_linkedin',
            'aqualuxe_team_twitter' => '_aqualuxe_team_twitter'
        );
        
        foreach ($fields as $field => $meta_key) {
            if (isset($_POST[$field])) {
                $value = in_array($field, array('aqualuxe_team_email')) ? sanitize_email($_POST[$field]) : 
                        in_array($field, array('aqualuxe_team_linkedin', 'aqualuxe_team_twitter')) ? esc_url_raw($_POST[$field]) : 
                        sanitize_text_field($_POST[$field]);
                update_post_meta($post_id, $meta_key, $value);
            }
        }
    }

    /**
     * Save testimonial meta data
     */
    private function save_testimonial_meta($post_id) {
        $fields = array(
            'aqualuxe_testimonial_client_name' => '_aqualuxe_testimonial_client_name',
            'aqualuxe_testimonial_client_position' => '_aqualuxe_testimonial_client_position',
            'aqualuxe_testimonial_client_company' => '_aqualuxe_testimonial_client_company',
            'aqualuxe_testimonial_rating' => '_aqualuxe_testimonial_rating'
        );
        
        foreach ($fields as $field => $meta_key) {
            if (isset($_POST[$field])) {
                $value = $field === 'aqualuxe_testimonial_rating' ? intval($_POST[$field]) : sanitize_text_field($_POST[$field]);
                update_post_meta($post_id, $meta_key, $value);
            }
        }
    }

    /**
     * Save portfolio meta data
     */
    private function save_portfolio_meta($post_id) {
        $fields = array(
            'aqualuxe_portfolio_client' => '_aqualuxe_portfolio_client',
            'aqualuxe_portfolio_url' => '_aqualuxe_portfolio_url',
            'aqualuxe_portfolio_completion_date' => '_aqualuxe_portfolio_completion_date',
            'aqualuxe_portfolio_technologies' => '_aqualuxe_portfolio_technologies'
        );
        
        foreach ($fields as $field => $meta_key) {
            if (isset($_POST[$field])) {
                $value = $field === 'aqualuxe_portfolio_url' ? esc_url_raw($_POST[$field]) : sanitize_text_field($_POST[$field]);
                update_post_meta($post_id, $meta_key, $value);
            }
        }
    }

    /**
     * Change title placeholder
     */
    public function change_title_placeholder($title) {
        $screen = get_current_screen();
        
        if (isset($screen->post_type)) {
            switch ($screen->post_type) {
                case 'service':
                    $title = esc_html__('Enter service name here', 'aqualuxe');
                    break;
                case 'event':
                    $title = esc_html__('Enter event title here', 'aqualuxe');
                    break;
                case 'team_member':
                    $title = esc_html__('Enter team member name here', 'aqualuxe');
                    break;
                case 'testimonial':
                    $title = esc_html__('Enter testimonial title here', 'aqualuxe');
                    break;
                case 'portfolio':
                    $title = esc_html__('Enter project name here', 'aqualuxe');
                    break;
                case 'faq':
                    $title = esc_html__('Enter question here', 'aqualuxe');
                    break;
            }
        }
        
        return $title;
    }

    /**
     * Service admin columns
     */
    public function service_columns($columns) {
        $new_columns = array();
        
        foreach ($columns as $key => $title) {
            $new_columns[$key] = $title;
            
            if ($key === 'title') {
                $new_columns['price'] = esc_html__('Price', 'aqualuxe');
                $new_columns['duration'] = esc_html__('Duration', 'aqualuxe');
            }
        }
        
        return $new_columns;
    }

    /**
     * Service custom columns content
     */
    public function service_custom_columns($column, $post_id) {
        switch ($column) {
            case 'price':
                $price = get_post_meta($post_id, '_aqualuxe_service_price', true);
                echo $price ? esc_html($price) : '—';
                break;
            case 'duration':
                $duration = get_post_meta($post_id, '_aqualuxe_service_duration', true);
                echo $duration ? esc_html($duration) : '—';
                break;
        }
    }

    /**
     * Event admin columns
     */
    public function event_columns($columns) {
        $new_columns = array();
        
        foreach ($columns as $key => $title) {
            $new_columns[$key] = $title;
            
            if ($key === 'title') {
                $new_columns['event_date'] = esc_html__('Date', 'aqualuxe');
                $new_columns['location'] = esc_html__('Location', 'aqualuxe');
                $new_columns['price'] = esc_html__('Price', 'aqualuxe');
            }
        }
        
        return $new_columns;
    }

    /**
     * Event custom columns content
     */
    public function event_custom_columns($column, $post_id) {
        switch ($column) {
            case 'event_date':
                $start_date = get_post_meta($post_id, '_aqualuxe_event_start_date', true);
                if ($start_date) {
                    echo esc_html(date('M j, Y', strtotime($start_date)));
                } else {
                    echo '—';
                }
                break;
            case 'location':
                $location = get_post_meta($post_id, '_aqualuxe_event_location', true);
                echo $location ? esc_html($location) : '—';
                break;
            case 'price':
                $price = get_post_meta($post_id, '_aqualuxe_event_price', true);
                echo $price ? esc_html($price) : '—';
                break;
        }
    }
}