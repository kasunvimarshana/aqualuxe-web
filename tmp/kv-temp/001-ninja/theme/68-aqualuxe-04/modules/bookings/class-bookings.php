<?php
/**
 * Bookings Module
 *
 * @package AquaLuxe
 * @subpackage Modules\Bookings
 * @since 1.0.0
 */

namespace AquaLuxe\Modules\Bookings;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Bookings Module Class
 * 
 * This class handles booking functionality for aquatic services and products.
 */
class Bookings {
    /**
     * Instance of this class
     *
     * @var Bookings
     */
    private static $instance = null;

    /**
     * Module slug
     *
     * @var string
     */
    private $slug = 'bookings';

    /**
     * Constructor
     */
    public function __construct() {
        $this->includes();
        $this->init_hooks();
    }

    /**
     * Include required files
     *
     * @return void
     */
    private function includes() {
        // Include admin files only in admin
        if ( is_admin() ) {
            require_once __DIR__ . '/admin/class-admin.php';
        }

        // Include frontend files
        require_once __DIR__ . '/inc/class-booking.php';
        require_once __DIR__ . '/inc/class-booking-form.php';
        require_once __DIR__ . '/inc/class-booking-calendar.php';
        require_once __DIR__ . '/inc/class-booking-availability.php';
    }

    /**
     * Initialize hooks
     *
     * @return void
     */
    private function init_hooks() {
        // Register post types and taxonomies
        add_action( 'init', [ $this, 'register_post_types' ] );
        add_action( 'init', [ $this, 'register_taxonomies' ] );

        // Register shortcodes
        add_action( 'init', [ $this, 'register_shortcodes' ] );

        // Register widgets
        add_action( 'widgets_init', [ $this, 'register_widgets' ] );

        // Register REST API endpoints
        add_action( 'rest_api_init', [ $this, 'register_rest_routes' ] );

        // Enqueue scripts and styles
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ] );

        // Add menu items
        add_action( 'admin_menu', [ $this, 'add_menu_items' ] );

        // Add settings
        add_action( 'admin_init', [ $this, 'register_settings' ] );

        // Add meta boxes
        add_action( 'add_meta_boxes', [ $this, 'add_meta_boxes' ] );

        // Save post meta
        add_action( 'save_post', [ $this, 'save_meta_box_data' ] );

        // Add booking data to WooCommerce orders if WooCommerce is active
        if ( class_exists( 'WooCommerce' ) ) {
            add_action( 'woocommerce_checkout_create_order_line_item', [ $this, 'add_booking_data_to_order_item' ], 10, 4 );
        }
    }

    /**
     * Register post types
     *
     * @return void
     */
    public function register_post_types() {
        // Register booking post type
        register_post_type(
            'aqualuxe_booking',
            [
                'labels'              => [
                    'name'                  => __( 'Bookings', 'aqualuxe' ),
                    'singular_name'         => __( 'Booking', 'aqualuxe' ),
                    'menu_name'             => __( 'Bookings', 'aqualuxe' ),
                    'name_admin_bar'        => __( 'Booking', 'aqualuxe' ),
                    'add_new'               => __( 'Add New', 'aqualuxe' ),
                    'add_new_item'          => __( 'Add New Booking', 'aqualuxe' ),
                    'new_item'              => __( 'New Booking', 'aqualuxe' ),
                    'edit_item'             => __( 'Edit Booking', 'aqualuxe' ),
                    'view_item'             => __( 'View Booking', 'aqualuxe' ),
                    'all_items'             => __( 'All Bookings', 'aqualuxe' ),
                    'search_items'          => __( 'Search Bookings', 'aqualuxe' ),
                    'parent_item_colon'     => __( 'Parent Bookings:', 'aqualuxe' ),
                    'not_found'             => __( 'No bookings found.', 'aqualuxe' ),
                    'not_found_in_trash'    => __( 'No bookings found in Trash.', 'aqualuxe' ),
                    'featured_image'        => __( 'Booking Image', 'aqualuxe' ),
                    'set_featured_image'    => __( 'Set booking image', 'aqualuxe' ),
                    'remove_featured_image' => __( 'Remove booking image', 'aqualuxe' ),
                    'use_featured_image'    => __( 'Use as booking image', 'aqualuxe' ),
                ],
                'public'              => true,
                'publicly_queryable'  => true,
                'show_ui'             => true,
                'show_in_menu'        => true,
                'query_var'           => true,
                'rewrite'             => [ 'slug' => 'booking' ],
                'capability_type'     => 'post',
                'has_archive'         => true,
                'hierarchical'        => false,
                'menu_position'       => null,
                'menu_icon'           => 'dashicons-calendar-alt',
                'supports'            => [ 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ],
                'show_in_rest'        => true,
            ]
        );

        // Register bookable item post type
        register_post_type(
            'aqualuxe_bookable',
            [
                'labels'              => [
                    'name'                  => __( 'Bookable Items', 'aqualuxe' ),
                    'singular_name'         => __( 'Bookable Item', 'aqualuxe' ),
                    'menu_name'             => __( 'Bookable Items', 'aqualuxe' ),
                    'name_admin_bar'        => __( 'Bookable Item', 'aqualuxe' ),
                    'add_new'               => __( 'Add New', 'aqualuxe' ),
                    'add_new_item'          => __( 'Add New Bookable Item', 'aqualuxe' ),
                    'new_item'              => __( 'New Bookable Item', 'aqualuxe' ),
                    'edit_item'             => __( 'Edit Bookable Item', 'aqualuxe' ),
                    'view_item'             => __( 'View Bookable Item', 'aqualuxe' ),
                    'all_items'             => __( 'All Bookable Items', 'aqualuxe' ),
                    'search_items'          => __( 'Search Bookable Items', 'aqualuxe' ),
                    'parent_item_colon'     => __( 'Parent Bookable Items:', 'aqualuxe' ),
                    'not_found'             => __( 'No bookable items found.', 'aqualuxe' ),
                    'not_found_in_trash'    => __( 'No bookable items found in Trash.', 'aqualuxe' ),
                    'featured_image'        => __( 'Bookable Item Image', 'aqualuxe' ),
                    'set_featured_image'    => __( 'Set bookable item image', 'aqualuxe' ),
                    'remove_featured_image' => __( 'Remove bookable item image', 'aqualuxe' ),
                    'use_featured_image'    => __( 'Use as bookable item image', 'aqualuxe' ),
                ],
                'public'              => true,
                'publicly_queryable'  => true,
                'show_ui'             => true,
                'show_in_menu'        => 'edit.php?post_type=aqualuxe_booking',
                'query_var'           => true,
                'rewrite'             => [ 'slug' => 'bookable' ],
                'capability_type'     => 'post',
                'has_archive'         => true,
                'hierarchical'        => false,
                'menu_position'       => null,
                'supports'            => [ 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ],
                'show_in_rest'        => true,
            ]
        );
    }

    /**
     * Register taxonomies
     *
     * @return void
     */
    public function register_taxonomies() {
        // Register booking category taxonomy
        register_taxonomy(
            'booking_category',
            [ 'aqualuxe_bookable' ],
            [
                'labels'            => [
                    'name'                       => __( 'Booking Categories', 'aqualuxe' ),
                    'singular_name'              => __( 'Booking Category', 'aqualuxe' ),
                    'search_items'               => __( 'Search Booking Categories', 'aqualuxe' ),
                    'popular_items'              => __( 'Popular Booking Categories', 'aqualuxe' ),
                    'all_items'                  => __( 'All Booking Categories', 'aqualuxe' ),
                    'parent_item'                => __( 'Parent Booking Category', 'aqualuxe' ),
                    'parent_item_colon'          => __( 'Parent Booking Category:', 'aqualuxe' ),
                    'edit_item'                  => __( 'Edit Booking Category', 'aqualuxe' ),
                    'update_item'                => __( 'Update Booking Category', 'aqualuxe' ),
                    'add_new_item'               => __( 'Add New Booking Category', 'aqualuxe' ),
                    'new_item_name'              => __( 'New Booking Category Name', 'aqualuxe' ),
                    'separate_items_with_commas' => __( 'Separate booking categories with commas', 'aqualuxe' ),
                    'add_or_remove_items'        => __( 'Add or remove booking categories', 'aqualuxe' ),
                    'choose_from_most_used'      => __( 'Choose from the most used booking categories', 'aqualuxe' ),
                    'not_found'                  => __( 'No booking categories found.', 'aqualuxe' ),
                    'menu_name'                  => __( 'Categories', 'aqualuxe' ),
                ],
                'hierarchical'      => true,
                'public'            => true,
                'show_ui'           => true,
                'show_admin_column' => true,
                'query_var'         => true,
                'rewrite'           => [ 'slug' => 'booking-category' ],
                'show_in_rest'      => true,
            ]
        );

        // Register booking tag taxonomy
        register_taxonomy(
            'booking_tag',
            [ 'aqualuxe_bookable' ],
            [
                'labels'            => [
                    'name'                       => __( 'Booking Tags', 'aqualuxe' ),
                    'singular_name'              => __( 'Booking Tag', 'aqualuxe' ),
                    'search_items'               => __( 'Search Booking Tags', 'aqualuxe' ),
                    'popular_items'              => __( 'Popular Booking Tags', 'aqualuxe' ),
                    'all_items'                  => __( 'All Booking Tags', 'aqualuxe' ),
                    'parent_item'                => __( 'Parent Booking Tag', 'aqualuxe' ),
                    'parent_item_colon'          => __( 'Parent Booking Tag:', 'aqualuxe' ),
                    'edit_item'                  => __( 'Edit Booking Tag', 'aqualuxe' ),
                    'update_item'                => __( 'Update Booking Tag', 'aqualuxe' ),
                    'add_new_item'               => __( 'Add New Booking Tag', 'aqualuxe' ),
                    'new_item_name'              => __( 'New Booking Tag Name', 'aqualuxe' ),
                    'separate_items_with_commas' => __( 'Separate booking tags with commas', 'aqualuxe' ),
                    'add_or_remove_items'        => __( 'Add or remove booking tags', 'aqualuxe' ),
                    'choose_from_most_used'      => __( 'Choose from the most used booking tags', 'aqualuxe' ),
                    'not_found'                  => __( 'No booking tags found.', 'aqualuxe' ),
                    'menu_name'                  => __( 'Tags', 'aqualuxe' ),
                ],
                'hierarchical'      => false,
                'public'            => true,
                'show_ui'           => true,
                'show_admin_column' => true,
                'query_var'         => true,
                'rewrite'           => [ 'slug' => 'booking-tag' ],
                'show_in_rest'      => true,
            ]
        );
    }

    /**
     * Register shortcodes
     *
     * @return void
     */
    public function register_shortcodes() {
        add_shortcode( 'aqualuxe_booking_form', [ $this, 'booking_form_shortcode' ] );
        add_shortcode( 'aqualuxe_booking_calendar', [ $this, 'booking_calendar_shortcode' ] );
        add_shortcode( 'aqualuxe_bookable_items', [ $this, 'bookable_items_shortcode' ] );
    }

    /**
     * Register widgets
     *
     * @return void
     */
    public function register_widgets() {
        // Register widgets if needed
    }

    /**
     * Register REST API endpoints
     *
     * @return void
     */
    public function register_rest_routes() {
        // Register REST API endpoints if needed
    }

    /**
     * Enqueue scripts and styles
     *
     * @return void
     */
    public function enqueue_scripts() {
        // Enqueue frontend scripts and styles
        wp_enqueue_style( 'aqualuxe-bookings', AQUALUXE_MODULES_DIR . $this->slug . '/assets/css/bookings.css', [], AQUALUXE_VERSION );
        wp_enqueue_script( 'aqualuxe-bookings', AQUALUXE_MODULES_DIR . $this->slug . '/assets/js/bookings.js', [ 'jquery' ], AQUALUXE_VERSION, true );

        // Localize script
        wp_localize_script(
            'aqualuxe-bookings',
            'aqualuxeBookings',
            [
                'ajaxUrl' => admin_url( 'admin-ajax.php' ),
                'nonce'   => wp_create_nonce( 'aqualuxe-bookings-nonce' ),
            ]
        );
    }

    /**
     * Enqueue admin scripts and styles
     *
     * @return void
     */
    public function admin_enqueue_scripts() {
        // Enqueue admin scripts and styles
        wp_enqueue_style( 'aqualuxe-bookings-admin', AQUALUXE_MODULES_DIR . $this->slug . '/assets/css/admin.css', [], AQUALUXE_VERSION );
        wp_enqueue_script( 'aqualuxe-bookings-admin', AQUALUXE_MODULES_DIR . $this->slug . '/assets/js/admin.js', [ 'jquery' ], AQUALUXE_VERSION, true );

        // Localize script
        wp_localize_script(
            'aqualuxe-bookings-admin',
            'aqualuxeBookingsAdmin',
            [
                'ajaxUrl' => admin_url( 'admin-ajax.php' ),
                'nonce'   => wp_create_nonce( 'aqualuxe-bookings-admin-nonce' ),
            ]
        );
    }

    /**
     * Add menu items
     *
     * @return void
     */
    public function add_menu_items() {
        // Add settings page
        add_submenu_page(
            'edit.php?post_type=aqualuxe_booking',
            __( 'Booking Settings', 'aqualuxe' ),
            __( 'Settings', 'aqualuxe' ),
            'manage_options',
            'aqualuxe-booking-settings',
            [ $this, 'render_settings_page' ]
        );

        // Add calendar page
        add_submenu_page(
            'edit.php?post_type=aqualuxe_booking',
            __( 'Booking Calendar', 'aqualuxe' ),
            __( 'Calendar', 'aqualuxe' ),
            'manage_options',
            'aqualuxe-booking-calendar',
            [ $this, 'render_calendar_page' ]
        );
    }

    /**
     * Register settings
     *
     * @return void
     */
    public function register_settings() {
        // Register settings
        register_setting( 'aqualuxe-booking-settings', 'aqualuxe_booking_settings' );

        // Add settings sections
        add_settings_section(
            'aqualuxe-booking-general',
            __( 'General Settings', 'aqualuxe' ),
            [ $this, 'render_general_settings_section' ],
            'aqualuxe-booking-settings'
        );

        add_settings_section(
            'aqualuxe-booking-display',
            __( 'Display Settings', 'aqualuxe' ),
            [ $this, 'render_display_settings_section' ],
            'aqualuxe-booking-settings'
        );

        add_settings_section(
            'aqualuxe-booking-notifications',
            __( 'Notification Settings', 'aqualuxe' ),
            [ $this, 'render_notification_settings_section' ],
            'aqualuxe-booking-settings'
        );

        // Add settings fields
        add_settings_field(
            'booking_page',
            __( 'Booking Page', 'aqualuxe' ),
            [ $this, 'render_booking_page_field' ],
            'aqualuxe-booking-settings',
            'aqualuxe-booking-general'
        );

        add_settings_field(
            'confirmation_page',
            __( 'Confirmation Page', 'aqualuxe' ),
            [ $this, 'render_confirmation_page_field' ],
            'aqualuxe-booking-settings',
            'aqualuxe-booking-general'
        );

        add_settings_field(
            'calendar_view',
            __( 'Calendar View', 'aqualuxe' ),
            [ $this, 'render_calendar_view_field' ],
            'aqualuxe-booking-settings',
            'aqualuxe-booking-display'
        );

        add_settings_field(
            'admin_notification',
            __( 'Admin Notification', 'aqualuxe' ),
            [ $this, 'render_admin_notification_field' ],
            'aqualuxe-booking-settings',
            'aqualuxe-booking-notifications'
        );

        add_settings_field(
            'customer_notification',
            __( 'Customer Notification', 'aqualuxe' ),
            [ $this, 'render_customer_notification_field' ],
            'aqualuxe-booking-settings',
            'aqualuxe-booking-notifications'
        );
    }

    /**
     * Add meta boxes
     *
     * @return void
     */
    public function add_meta_boxes() {
        // Add meta boxes for bookable items
        add_meta_box(
            'aqualuxe-bookable-details',
            __( 'Bookable Item Details', 'aqualuxe' ),
            [ $this, 'render_bookable_details_meta_box' ],
            'aqualuxe_bookable',
            'normal',
            'high'
        );

        add_meta_box(
            'aqualuxe-bookable-availability',
            __( 'Availability', 'aqualuxe' ),
            [ $this, 'render_bookable_availability_meta_box' ],
            'aqualuxe_bookable',
            'normal',
            'high'
        );

        add_meta_box(
            'aqualuxe-bookable-pricing',
            __( 'Pricing', 'aqualuxe' ),
            [ $this, 'render_bookable_pricing_meta_box' ],
            'aqualuxe_bookable',
            'normal',
            'high'
        );

        // Add meta boxes for bookings
        add_meta_box(
            'aqualuxe-booking-details',
            __( 'Booking Details', 'aqualuxe' ),
            [ $this, 'render_booking_details_meta_box' ],
            'aqualuxe_booking',
            'normal',
            'high'
        );

        add_meta_box(
            'aqualuxe-booking-customer',
            __( 'Customer Information', 'aqualuxe' ),
            [ $this, 'render_booking_customer_meta_box' ],
            'aqualuxe_booking',
            'normal',
            'high'
        );
    }

    /**
     * Save meta box data
     *
     * @param int $post_id
     * @return void
     */
    public function save_meta_box_data( $post_id ) {
        // Save meta box data
    }

    /**
     * Add booking data to WooCommerce order item
     *
     * @param \WC_Order_Item_Product $item
     * @param string $cart_item_key
     * @param array $values
     * @param \WC_Order $order
     * @return void
     */
    public function add_booking_data_to_order_item( $item, $cart_item_key, $values, $order ) {
        // Add booking data to order item
    }

    /**
     * Booking form shortcode
     *
     * @param array $atts
     * @return string
     */
    public function booking_form_shortcode( $atts ) {
        // Booking form shortcode
        return 'Booking form will be displayed here';
    }

    /**
     * Booking calendar shortcode
     *
     * @param array $atts
     * @return string
     */
    public function booking_calendar_shortcode( $atts ) {
        // Booking calendar shortcode
        return 'Booking calendar will be displayed here';
    }

    /**
     * Bookable items shortcode
     *
     * @param array $atts
     * @return string
     */
    public function bookable_items_shortcode( $atts ) {
        // Bookable items shortcode
        return 'Bookable items will be displayed here';
    }

    /**
     * Render settings page
     *
     * @return void
     */
    public function render_settings_page() {
        // Render settings page
        ?>
        <div class="wrap">
            <h1><?php echo esc_html__( 'Booking Settings', 'aqualuxe' ); ?></h1>
            <form method="post" action="options.php">
                <?php
                settings_fields( 'aqualuxe-booking-settings' );
                do_settings_sections( 'aqualuxe-booking-settings' );
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    /**
     * Render calendar page
     *
     * @return void
     */
    public function render_calendar_page() {
        // Render calendar page
        ?>
        <div class="wrap">
            <h1><?php echo esc_html__( 'Booking Calendar', 'aqualuxe' ); ?></h1>
            <div id="aqualuxe-booking-calendar"></div>
        </div>
        <?php
    }

    /**
     * Render general settings section
     *
     * @return void
     */
    public function render_general_settings_section() {
        echo '<p>' . esc_html__( 'Configure general booking settings.', 'aqualuxe' ) . '</p>';
    }

    /**
     * Render display settings section
     *
     * @return void
     */
    public function render_display_settings_section() {
        echo '<p>' . esc_html__( 'Configure how bookings are displayed.', 'aqualuxe' ) . '</p>';
    }

    /**
     * Render notification settings section
     *
     * @return void
     */
    public function render_notification_settings_section() {
        echo '<p>' . esc_html__( 'Configure booking notification settings.', 'aqualuxe' ) . '</p>';
    }

    /**
     * Render booking page field
     *
     * @return void
     */
    public function render_booking_page_field() {
        $settings = get_option( 'aqualuxe_booking_settings', [] );
        $booking_page = isset( $settings['booking_page'] ) ? $settings['booking_page'] : '';

        wp_dropdown_pages(
            [
                'name'              => 'aqualuxe_booking_settings[booking_page]',
                'selected'          => $booking_page,
                'show_option_none'  => __( 'Select a page', 'aqualuxe' ),
            ]
        );
        echo '<p class="description">' . esc_html__( 'Select the page where the booking form will be displayed.', 'aqualuxe' ) . '</p>';
    }

    /**
     * Render confirmation page field
     *
     * @return void
     */
    public function render_confirmation_page_field() {
        $settings = get_option( 'aqualuxe_booking_settings', [] );
        $confirmation_page = isset( $settings['confirmation_page'] ) ? $settings['confirmation_page'] : '';

        wp_dropdown_pages(
            [
                'name'              => 'aqualuxe_booking_settings[confirmation_page]',
                'selected'          => $confirmation_page,
                'show_option_none'  => __( 'Select a page', 'aqualuxe' ),
            ]
        );
        echo '<p class="description">' . esc_html__( 'Select the page where the booking confirmation will be displayed.', 'aqualuxe' ) . '</p>';
    }

    /**
     * Render calendar view field
     *
     * @return void
     */
    public function render_calendar_view_field() {
        $settings = get_option( 'aqualuxe_booking_settings', [] );
        $calendar_view = isset( $settings['calendar_view'] ) ? $settings['calendar_view'] : 'month';

        ?>
        <select name="aqualuxe_booking_settings[calendar_view]">
            <option value="month" <?php selected( $calendar_view, 'month' ); ?>><?php esc_html_e( 'Month', 'aqualuxe' ); ?></option>
            <option value="week" <?php selected( $calendar_view, 'week' ); ?>><?php esc_html_e( 'Week', 'aqualuxe' ); ?></option>
            <option value="day" <?php selected( $calendar_view, 'day' ); ?>><?php esc_html_e( 'Day', 'aqualuxe' ); ?></option>
        </select>
        <p class="description"><?php esc_html_e( 'Select the default calendar view.', 'aqualuxe' ); ?></p>
        <?php
    }

    /**
     * Render admin notification field
     *
     * @return void
     */
    public function render_admin_notification_field() {
        $settings = get_option( 'aqualuxe_booking_settings', [] );
        $admin_notification = isset( $settings['admin_notification'] ) ? $settings['admin_notification'] : true;

        ?>
        <label>
            <input type="checkbox" name="aqualuxe_booking_settings[admin_notification]" value="1" <?php checked( $admin_notification, true ); ?> />
            <?php esc_html_e( 'Send notification to admin when a new booking is made', 'aqualuxe' ); ?>
        </label>
        <?php
    }

    /**
     * Render customer notification field
     *
     * @return void
     */
    public function render_customer_notification_field() {
        $settings = get_option( 'aqualuxe_booking_settings', [] );
        $customer_notification = isset( $settings['customer_notification'] ) ? $settings['customer_notification'] : true;

        ?>
        <label>
            <input type="checkbox" name="aqualuxe_booking_settings[customer_notification]" value="1" <?php checked( $customer_notification, true ); ?> />
            <?php esc_html_e( 'Send notification to customer when a booking is made', 'aqualuxe' ); ?>
        </label>
        <?php
    }

    /**
     * Render bookable details meta box
     *
     * @param \WP_Post $post
     * @return void
     */
    public function render_bookable_details_meta_box( $post ) {
        // Render bookable details meta box
        wp_nonce_field( 'aqualuxe_bookable_details', 'aqualuxe_bookable_details_nonce' );

        $duration = get_post_meta( $post->ID, '_aqualuxe_bookable_duration', true );
        $capacity = get_post_meta( $post->ID, '_aqualuxe_bookable_capacity', true );
        $location = get_post_meta( $post->ID, '_aqualuxe_bookable_location', true );

        ?>
        <div class="aqualuxe-meta-box-row">
            <label for="aqualuxe-bookable-duration"><?php esc_html_e( 'Duration (minutes)', 'aqualuxe' ); ?></label>
            <input type="number" id="aqualuxe-bookable-duration" name="aqualuxe_bookable_duration" value="<?php echo esc_attr( $duration ); ?>" min="1" step="1" />
        </div>

        <div class="aqualuxe-meta-box-row">
            <label for="aqualuxe-bookable-capacity"><?php esc_html_e( 'Capacity', 'aqualuxe' ); ?></label>
            <input type="number" id="aqualuxe-bookable-capacity" name="aqualuxe_bookable_capacity" value="<?php echo esc_attr( $capacity ); ?>" min="1" step="1" />
        </div>

        <div class="aqualuxe-meta-box-row">
            <label for="aqualuxe-bookable-location"><?php esc_html_e( 'Location', 'aqualuxe' ); ?></label>
            <input type="text" id="aqualuxe-bookable-location" name="aqualuxe_bookable_location" value="<?php echo esc_attr( $location ); ?>" />
        </div>
        <?php
    }

    /**
     * Render bookable availability meta box
     *
     * @param \WP_Post $post
     * @return void
     */
    public function render_bookable_availability_meta_box( $post ) {
        // Render bookable availability meta box
        wp_nonce_field( 'aqualuxe_bookable_availability', 'aqualuxe_bookable_availability_nonce' );

        $availability = get_post_meta( $post->ID, '_aqualuxe_bookable_availability', true );
        if ( ! is_array( $availability ) ) {
            $availability = [
                'monday'    => [ 'enabled' => true, 'start' => '09:00', 'end' => '17:00' ],
                'tuesday'   => [ 'enabled' => true, 'start' => '09:00', 'end' => '17:00' ],
                'wednesday' => [ 'enabled' => true, 'start' => '09:00', 'end' => '17:00' ],
                'thursday'  => [ 'enabled' => true, 'start' => '09:00', 'end' => '17:00' ],
                'friday'    => [ 'enabled' => true, 'start' => '09:00', 'end' => '17:00' ],
                'saturday'  => [ 'enabled' => false, 'start' => '09:00', 'end' => '17:00' ],
                'sunday'    => [ 'enabled' => false, 'start' => '09:00', 'end' => '17:00' ],
            ];
        }

        $days = [
            'monday'    => __( 'Monday', 'aqualuxe' ),
            'tuesday'   => __( 'Tuesday', 'aqualuxe' ),
            'wednesday' => __( 'Wednesday', 'aqualuxe' ),
            'thursday'  => __( 'Thursday', 'aqualuxe' ),
            'friday'    => __( 'Friday', 'aqualuxe' ),
            'saturday'  => __( 'Saturday', 'aqualuxe' ),
            'sunday'    => __( 'Sunday', 'aqualuxe' ),
        ];

        ?>
        <table class="form-table">
            <thead>
                <tr>
                    <th><?php esc_html_e( 'Day', 'aqualuxe' ); ?></th>
                    <th><?php esc_html_e( 'Available', 'aqualuxe' ); ?></th>
                    <th><?php esc_html_e( 'Start Time', 'aqualuxe' ); ?></th>
                    <th><?php esc_html_e( 'End Time', 'aqualuxe' ); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ( $days as $day_key => $day_label ) : ?>
                    <tr>
                        <td><?php echo esc_html( $day_label ); ?></td>
                        <td>
                            <input type="checkbox" name="aqualuxe_bookable_availability[<?php echo esc_attr( $day_key ); ?>][enabled]" value="1" <?php checked( isset( $availability[ $day_key ]['enabled'] ) ? $availability[ $day_key ]['enabled'] : false ); ?> />
                        </td>
                        <td>
                            <input type="time" name="aqualuxe_bookable_availability[<?php echo esc_attr( $day_key ); ?>][start]" value="<?php echo esc_attr( isset( $availability[ $day_key ]['start'] ) ? $availability[ $day_key ]['start'] : '09:00' ); ?>" />
                        </td>
                        <td>
                            <input type="time" name="aqualuxe_bookable_availability[<?php echo esc_attr( $day_key ); ?>][end]" value="<?php echo esc_attr( isset( $availability[ $day_key ]['end'] ) ? $availability[ $day_key ]['end'] : '17:00' ); ?>" />
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php
    }

    /**
     * Render bookable pricing meta box
     *
     * @param \WP_Post $post
     * @return void
     */
    public function render_bookable_pricing_meta_box( $post ) {
        // Render bookable pricing meta box
        wp_nonce_field( 'aqualuxe_bookable_pricing', 'aqualuxe_bookable_pricing_nonce' );

        $price = get_post_meta( $post->ID, '_aqualuxe_bookable_price', true );
        $special_price = get_post_meta( $post->ID, '_aqualuxe_bookable_special_price', true );
        $special_price_start = get_post_meta( $post->ID, '_aqualuxe_bookable_special_price_start', true );
        $special_price_end = get_post_meta( $post->ID, '_aqualuxe_bookable_special_price_end', true );

        ?>
        <div class="aqualuxe-meta-box-row">
            <label for="aqualuxe-bookable-price"><?php esc_html_e( 'Regular Price', 'aqualuxe' ); ?></label>
            <input type="number" id="aqualuxe-bookable-price" name="aqualuxe_bookable_price" value="<?php echo esc_attr( $price ); ?>" min="0" step="0.01" />
        </div>

        <div class="aqualuxe-meta-box-row">
            <label for="aqualuxe-bookable-special-price"><?php esc_html_e( 'Special Price', 'aqualuxe' ); ?></label>
            <input type="number" id="aqualuxe-bookable-special-price" name="aqualuxe_bookable_special_price" value="<?php echo esc_attr( $special_price ); ?>" min="0" step="0.01" />
        </div>

        <div class="aqualuxe-meta-box-row">
            <label for="aqualuxe-bookable-special-price-start"><?php esc_html_e( 'Special Price Start Date', 'aqualuxe' ); ?></label>
            <input type="date" id="aqualuxe-bookable-special-price-start" name="aqualuxe_bookable_special_price_start" value="<?php echo esc_attr( $special_price_start ); ?>" />
        </div>

        <div class="aqualuxe-meta-box-row">
            <label for="aqualuxe-bookable-special-price-end"><?php esc_html_e( 'Special Price End Date', 'aqualuxe' ); ?></label>
            <input type="date" id="aqualuxe-bookable-special-price-end" name="aqualuxe_bookable_special_price_end" value="<?php echo esc_attr( $special_price_end ); ?>" />
        </div>
        <?php
    }

    /**
     * Render booking details meta box
     *
     * @param \WP_Post $post
     * @return void
     */
    public function render_booking_details_meta_box( $post ) {
        // Render booking details meta box
        wp_nonce_field( 'aqualuxe_booking_details', 'aqualuxe_booking_details_nonce' );

        $bookable_id = get_post_meta( $post->ID, '_aqualuxe_booking_bookable_id', true );
        $date = get_post_meta( $post->ID, '_aqualuxe_booking_date', true );
        $time = get_post_meta( $post->ID, '_aqualuxe_booking_time', true );
        $status = get_post_meta( $post->ID, '_aqualuxe_booking_status', true );
        if ( ! $status ) {
            $status = 'pending';
        }

        // Get all bookable items
        $bookable_items = get_posts(
            [
                'post_type'      => 'aqualuxe_bookable',
                'posts_per_page' => -1,
                'orderby'        => 'title',
                'order'          => 'ASC',
            ]
        );

        ?>
        <div class="aqualuxe-meta-box-row">
            <label for="aqualuxe-booking-bookable-id"><?php esc_html_e( 'Bookable Item', 'aqualuxe' ); ?></label>
            <select id="aqualuxe-booking-bookable-id" name="aqualuxe_booking_bookable_id">
                <option value=""><?php esc_html_e( 'Select a bookable item', 'aqualuxe' ); ?></option>
                <?php foreach ( $bookable_items as $bookable_item ) : ?>
                    <option value="<?php echo esc_attr( $bookable_item->ID ); ?>" <?php selected( $bookable_id, $bookable_item->ID ); ?>><?php echo esc_html( $bookable_item->post_title ); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="aqualuxe-meta-box-row">
            <label for="aqualuxe-booking-date"><?php esc_html_e( 'Date', 'aqualuxe' ); ?></label>
            <input type="date" id="aqualuxe-booking-date" name="aqualuxe_booking_date" value="<?php echo esc_attr( $date ); ?>" />
        </div>

        <div class="aqualuxe-meta-box-row">
            <label for="aqualuxe-booking-time"><?php esc_html_e( 'Time', 'aqualuxe' ); ?></label>
            <input type="time" id="aqualuxe-booking-time" name="aqualuxe_booking_time" value="<?php echo esc_attr( $time ); ?>" />
        </div>

        <div class="aqualuxe-meta-box-row">
            <label for="aqualuxe-booking-status"><?php esc_html_e( 'Status', 'aqualuxe' ); ?></label>
            <select id="aqualuxe-booking-status" name="aqualuxe_booking_status">
                <option value="pending" <?php selected( $status, 'pending' ); ?>><?php esc_html_e( 'Pending', 'aqualuxe' ); ?></option>
                <option value="confirmed" <?php selected( $status, 'confirmed' ); ?>><?php esc_html_e( 'Confirmed', 'aqualuxe' ); ?></option>
                <option value="completed" <?php selected( $status, 'completed' ); ?>><?php esc_html_e( 'Completed', 'aqualuxe' ); ?></option>
                <option value="cancelled" <?php selected( $status, 'cancelled' ); ?>><?php esc_html_e( 'Cancelled', 'aqualuxe' ); ?></option>
            </select>
        </div>
        <?php
    }

    /**
     * Render booking customer meta box
     *
     * @param \WP_Post $post
     * @return void
     */
    public function render_booking_customer_meta_box( $post ) {
        // Render booking customer meta box
        wp_nonce_field( 'aqualuxe_booking_customer', 'aqualuxe_booking_customer_nonce' );

        $customer_name = get_post_meta( $post->ID, '_aqualuxe_booking_customer_name', true );
        $customer_email = get_post_meta( $post->ID, '_aqualuxe_booking_customer_email', true );
        $customer_phone = get_post_meta( $post->ID, '_aqualuxe_booking_customer_phone', true );
        $customer_notes = get_post_meta( $post->ID, '_aqualuxe_booking_customer_notes', true );

        ?>
        <div class="aqualuxe-meta-box-row">
            <label for="aqualuxe-booking-customer-name"><?php esc_html_e( 'Name', 'aqualuxe' ); ?></label>
            <input type="text" id="aqualuxe-booking-customer-name" name="aqualuxe_booking_customer_name" value="<?php echo esc_attr( $customer_name ); ?>" />
        </div>

        <div class="aqualuxe-meta-box-row">
            <label for="aqualuxe-booking-customer-email"><?php esc_html_e( 'Email', 'aqualuxe' ); ?></label>
            <input type="email" id="aqualuxe-booking-customer-email" name="aqualuxe_booking_customer_email" value="<?php echo esc_attr( $customer_email ); ?>" />
        </div>

        <div class="aqualuxe-meta-box-row">
            <label for="aqualuxe-booking-customer-phone"><?php esc_html_e( 'Phone', 'aqualuxe' ); ?></label>
            <input type="tel" id="aqualuxe-booking-customer-phone" name="aqualuxe_booking_customer_phone" value="<?php echo esc_attr( $customer_phone ); ?>" />
        </div>

        <div class="aqualuxe-meta-box-row">
            <label for="aqualuxe-booking-customer-notes"><?php esc_html_e( 'Notes', 'aqualuxe' ); ?></label>
            <textarea id="aqualuxe-booking-customer-notes" name="aqualuxe_booking_customer_notes" rows="4"><?php echo esc_textarea( $customer_notes ); ?></textarea>
        </div>
        <?php
    }
}

// Initialize the module
new Bookings();