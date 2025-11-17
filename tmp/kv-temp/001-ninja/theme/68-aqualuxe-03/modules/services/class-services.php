<?php
/**
 * Services Module
 *
 * @package AquaLuxe
 * @subpackage Modules\Services
 * @since 1.0.0
 */

namespace AquaLuxe\Modules\Services;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Services Module Class
 * 
 * This class handles service functionality for aquatic businesses.
 */
class Services {
    /**
     * Instance of this class
     *
     * @var Services
     */
    private static $instance = null;

    /**
     * Module slug
     *
     * @var string
     */
    private $slug = 'services';

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
        require_once __DIR__ . '/inc/class-service.php';
        require_once __DIR__ . '/inc/class-service-display.php';
        require_once __DIR__ . '/inc/class-service-pricing.php';
        require_once __DIR__ . '/inc/class-service-schema.php';
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

        // Add service data to WooCommerce products if WooCommerce is active
        if ( class_exists( 'WooCommerce' ) ) {
            add_action( 'woocommerce_product_options_general_product_data', [ $this, 'add_service_product_options' ] );
            add_action( 'woocommerce_process_product_meta', [ $this, 'save_service_product_options' ] );
        }

        // Add integration with Bookings module if available
        if ( class_exists( 'AquaLuxe\Modules\Bookings\Bookings' ) ) {
            add_filter( 'aqualuxe_bookable_items', [ $this, 'add_services_to_bookable_items' ] );
        }
    }

    /**
     * Register post types
     *
     * @return void
     */
    public function register_post_types() {
        // Register service post type
        register_post_type(
            'aqualuxe_service',
            [
                'labels'              => [
                    'name'                  => __( 'Services', 'aqualuxe' ),
                    'singular_name'         => __( 'Service', 'aqualuxe' ),
                    'menu_name'             => __( 'Services', 'aqualuxe' ),
                    'name_admin_bar'        => __( 'Service', 'aqualuxe' ),
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
                    'featured_image'        => __( 'Service Image', 'aqualuxe' ),
                    'set_featured_image'    => __( 'Set service image', 'aqualuxe' ),
                    'remove_featured_image' => __( 'Remove service image', 'aqualuxe' ),
                    'use_featured_image'    => __( 'Use as service image', 'aqualuxe' ),
                ],
                'public'              => true,
                'publicly_queryable'  => true,
                'show_ui'             => true,
                'show_in_menu'        => true,
                'query_var'           => true,
                'rewrite'             => [ 'slug' => 'service' ],
                'capability_type'     => 'post',
                'has_archive'         => true,
                'hierarchical'        => false,
                'menu_position'       => null,
                'menu_icon'           => 'dashicons-hammer',
                'supports'            => [ 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'page-attributes' ],
                'show_in_rest'        => true,
            ]
        );

        // Register service package post type
        register_post_type(
            'aqualuxe_service_pkg',
            [
                'labels'              => [
                    'name'                  => __( 'Service Packages', 'aqualuxe' ),
                    'singular_name'         => __( 'Service Package', 'aqualuxe' ),
                    'menu_name'             => __( 'Service Packages', 'aqualuxe' ),
                    'name_admin_bar'        => __( 'Service Package', 'aqualuxe' ),
                    'add_new'               => __( 'Add New', 'aqualuxe' ),
                    'add_new_item'          => __( 'Add New Service Package', 'aqualuxe' ),
                    'new_item'              => __( 'New Service Package', 'aqualuxe' ),
                    'edit_item'             => __( 'Edit Service Package', 'aqualuxe' ),
                    'view_item'             => __( 'View Service Package', 'aqualuxe' ),
                    'all_items'             => __( 'All Service Packages', 'aqualuxe' ),
                    'search_items'          => __( 'Search Service Packages', 'aqualuxe' ),
                    'parent_item_colon'     => __( 'Parent Service Packages:', 'aqualuxe' ),
                    'not_found'             => __( 'No service packages found.', 'aqualuxe' ),
                    'not_found_in_trash'    => __( 'No service packages found in Trash.', 'aqualuxe' ),
                    'featured_image'        => __( 'Service Package Image', 'aqualuxe' ),
                    'set_featured_image'    => __( 'Set service package image', 'aqualuxe' ),
                    'remove_featured_image' => __( 'Remove service package image', 'aqualuxe' ),
                    'use_featured_image'    => __( 'Use as service package image', 'aqualuxe' ),
                ],
                'public'              => true,
                'publicly_queryable'  => true,
                'show_ui'             => true,
                'show_in_menu'        => 'edit.php?post_type=aqualuxe_service',
                'query_var'           => true,
                'rewrite'             => [ 'slug' => 'service-package' ],
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
        // Register service category taxonomy
        register_taxonomy(
            'service_category',
            [ 'aqualuxe_service', 'aqualuxe_service_pkg' ],
            [
                'labels'            => [
                    'name'                       => __( 'Service Categories', 'aqualuxe' ),
                    'singular_name'              => __( 'Service Category', 'aqualuxe' ),
                    'search_items'               => __( 'Search Service Categories', 'aqualuxe' ),
                    'popular_items'              => __( 'Popular Service Categories', 'aqualuxe' ),
                    'all_items'                  => __( 'All Service Categories', 'aqualuxe' ),
                    'parent_item'                => __( 'Parent Service Category', 'aqualuxe' ),
                    'parent_item_colon'          => __( 'Parent Service Category:', 'aqualuxe' ),
                    'edit_item'                  => __( 'Edit Service Category', 'aqualuxe' ),
                    'update_item'                => __( 'Update Service Category', 'aqualuxe' ),
                    'add_new_item'               => __( 'Add New Service Category', 'aqualuxe' ),
                    'new_item_name'              => __( 'New Service Category Name', 'aqualuxe' ),
                    'separate_items_with_commas' => __( 'Separate service categories with commas', 'aqualuxe' ),
                    'add_or_remove_items'        => __( 'Add or remove service categories', 'aqualuxe' ),
                    'choose_from_most_used'      => __( 'Choose from the most used service categories', 'aqualuxe' ),
                    'not_found'                  => __( 'No service categories found.', 'aqualuxe' ),
                    'menu_name'                  => __( 'Categories', 'aqualuxe' ),
                ],
                'hierarchical'      => true,
                'public'            => true,
                'show_ui'           => true,
                'show_admin_column' => true,
                'query_var'         => true,
                'rewrite'           => [ 'slug' => 'service-category' ],
                'show_in_rest'      => true,
            ]
        );

        // Register service tag taxonomy
        register_taxonomy(
            'service_tag',
            [ 'aqualuxe_service', 'aqualuxe_service_pkg' ],
            [
                'labels'            => [
                    'name'                       => __( 'Service Tags', 'aqualuxe' ),
                    'singular_name'              => __( 'Service Tag', 'aqualuxe' ),
                    'search_items'               => __( 'Search Service Tags', 'aqualuxe' ),
                    'popular_items'              => __( 'Popular Service Tags', 'aqualuxe' ),
                    'all_items'                  => __( 'All Service Tags', 'aqualuxe' ),
                    'parent_item'                => __( 'Parent Service Tag', 'aqualuxe' ),
                    'parent_item_colon'          => __( 'Parent Service Tag:', 'aqualuxe' ),
                    'edit_item'                  => __( 'Edit Service Tag', 'aqualuxe' ),
                    'update_item'                => __( 'Update Service Tag', 'aqualuxe' ),
                    'add_new_item'               => __( 'Add New Service Tag', 'aqualuxe' ),
                    'new_item_name'              => __( 'New Service Tag Name', 'aqualuxe' ),
                    'separate_items_with_commas' => __( 'Separate service tags with commas', 'aqualuxe' ),
                    'add_or_remove_items'        => __( 'Add or remove service tags', 'aqualuxe' ),
                    'choose_from_most_used'      => __( 'Choose from the most used service tags', 'aqualuxe' ),
                    'not_found'                  => __( 'No service tags found.', 'aqualuxe' ),
                    'menu_name'                  => __( 'Tags', 'aqualuxe' ),
                ],
                'hierarchical'      => false,
                'public'            => true,
                'show_ui'           => true,
                'show_admin_column' => true,
                'query_var'         => true,
                'rewrite'           => [ 'slug' => 'service-tag' ],
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
        add_shortcode( 'aqualuxe_services', [ $this, 'services_shortcode' ] );
        add_shortcode( 'aqualuxe_service_grid', [ $this, 'service_grid_shortcode' ] );
        add_shortcode( 'aqualuxe_service_packages', [ $this, 'service_packages_shortcode' ] );
        add_shortcode( 'aqualuxe_service_comparison', [ $this, 'service_comparison_shortcode' ] );
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
        wp_enqueue_style( 'aqualuxe-services', AQUALUXE_MODULES_URL . $this->slug . '/assets/css/services.css', [], AQUALUXE_VERSION );
        wp_enqueue_script( 'aqualuxe-services', AQUALUXE_MODULES_URL . $this->slug . '/assets/js/services.js', [ 'jquery' ], AQUALUXE_VERSION, true );

        // Localize script
        wp_localize_script(
            'aqualuxe-services',
            'aqualuxeServices',
            [
                'ajaxUrl' => admin_url( 'admin-ajax.php' ),
                'nonce'   => wp_create_nonce( 'aqualuxe-services-nonce' ),
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
        wp_enqueue_style( 'aqualuxe-services-admin', AQUALUXE_MODULES_URL . $this->slug . '/assets/css/admin.css', [], AQUALUXE_VERSION );
        wp_enqueue_script( 'aqualuxe-services-admin', AQUALUXE_MODULES_URL . $this->slug . '/assets/js/admin.js', [ 'jquery' ], AQUALUXE_VERSION, true );

        // Localize script
        wp_localize_script(
            'aqualuxe-services-admin',
            'aqualuxeServicesAdmin',
            [
                'ajaxUrl' => admin_url( 'admin-ajax.php' ),
                'nonce'   => wp_create_nonce( 'aqualuxe-services-admin-nonce' ),
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
            'edit.php?post_type=aqualuxe_service',
            __( 'Service Settings', 'aqualuxe' ),
            __( 'Settings', 'aqualuxe' ),
            'manage_options',
            'aqualuxe-service-settings',
            [ $this, 'render_settings_page' ]
        );
    }

    /**
     * Register settings
     *
     * @return void
     */
    public function register_settings() {
        // Register settings
        register_setting( 'aqualuxe-service-settings', 'aqualuxe_service_settings' );

        // Add settings sections
        add_settings_section(
            'aqualuxe-service-general',
            __( 'General Settings', 'aqualuxe' ),
            [ $this, 'render_general_settings_section' ],
            'aqualuxe-service-settings'
        );

        add_settings_section(
            'aqualuxe-service-display',
            __( 'Display Settings', 'aqualuxe' ),
            [ $this, 'render_display_settings_section' ],
            'aqualuxe-service-settings'
        );

        add_settings_section(
            'aqualuxe-service-integration',
            __( 'Integration Settings', 'aqualuxe' ),
            [ $this, 'render_integration_settings_section' ],
            'aqualuxe-service-settings'
        );

        // Add settings fields
        add_settings_field(
            'services_page',
            __( 'Services Page', 'aqualuxe' ),
            [ $this, 'render_services_page_field' ],
            'aqualuxe-service-settings',
            'aqualuxe-service-general'
        );

        add_settings_field(
            'service_columns',
            __( 'Grid Columns', 'aqualuxe' ),
            [ $this, 'render_service_columns_field' ],
            'aqualuxe-service-settings',
            'aqualuxe-service-display'
        );

        add_settings_field(
            'show_pricing',
            __( 'Show Pricing', 'aqualuxe' ),
            [ $this, 'render_show_pricing_field' ],
            'aqualuxe-service-settings',
            'aqualuxe-service-display'
        );

        add_settings_field(
            'booking_integration',
            __( 'Booking Integration', 'aqualuxe' ),
            [ $this, 'render_booking_integration_field' ],
            'aqualuxe-service-settings',
            'aqualuxe-service-integration'
        );

        add_settings_field(
            'woocommerce_integration',
            __( 'WooCommerce Integration', 'aqualuxe' ),
            [ $this, 'render_woocommerce_integration_field' ],
            'aqualuxe-service-settings',
            'aqualuxe-service-integration'
        );
    }

    /**
     * Add meta boxes
     *
     * @return void
     */
    public function add_meta_boxes() {
        // Add meta boxes for services
        add_meta_box(
            'aqualuxe-service-details',
            __( 'Service Details', 'aqualuxe' ),
            [ $this, 'render_service_details_meta_box' ],
            'aqualuxe_service',
            'normal',
            'high'
        );

        add_meta_box(
            'aqualuxe-service-pricing',
            __( 'Pricing', 'aqualuxe' ),
            [ $this, 'render_service_pricing_meta_box' ],
            'aqualuxe_service',
            'normal',
            'high'
        );

        add_meta_box(
            'aqualuxe-service-booking',
            __( 'Booking Options', 'aqualuxe' ),
            [ $this, 'render_service_booking_meta_box' ],
            'aqualuxe_service',
            'normal',
            'high'
        );

        // Add meta boxes for service packages
        add_meta_box(
            'aqualuxe-service-package-details',
            __( 'Package Details', 'aqualuxe' ),
            [ $this, 'render_service_package_details_meta_box' ],
            'aqualuxe_service_pkg',
            'normal',
            'high'
        );

        add_meta_box(
            'aqualuxe-service-package-services',
            __( 'Included Services', 'aqualuxe' ),
            [ $this, 'render_service_package_services_meta_box' ],
            'aqualuxe_service_pkg',
            'normal',
            'high'
        );

        add_meta_box(
            'aqualuxe-service-package-pricing',
            __( 'Package Pricing', 'aqualuxe' ),
            [ $this, 'render_service_package_pricing_meta_box' ],
            'aqualuxe_service_pkg',
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
        // Check if our nonce is set
        if ( ! isset( $_POST['aqualuxe_service_details_nonce'] ) && ! isset( $_POST['aqualuxe_service_pricing_nonce'] ) && ! isset( $_POST['aqualuxe_service_booking_nonce'] ) ) {
            return;
        }

        // Verify the nonce
        if ( 
            ( isset( $_POST['aqualuxe_service_details_nonce'] ) && ! wp_verify_nonce( $_POST['aqualuxe_service_details_nonce'], 'aqualuxe_service_details' ) ) ||
            ( isset( $_POST['aqualuxe_service_pricing_nonce'] ) && ! wp_verify_nonce( $_POST['aqualuxe_service_pricing_nonce'], 'aqualuxe_service_pricing' ) ) ||
            ( isset( $_POST['aqualuxe_service_booking_nonce'] ) && ! wp_verify_nonce( $_POST['aqualuxe_service_booking_nonce'], 'aqualuxe_service_booking' ) )
        ) {
            return;
        }

        // If this is an autosave, our form has not been submitted, so we don't want to do anything
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        // Check the user's permissions
        if ( isset( $_POST['post_type'] ) ) {
            if ( 'aqualuxe_service' === $_POST['post_type'] ) {
                if ( ! current_user_can( 'edit_post', $post_id ) ) {
                    return;
                }
            } elseif ( 'aqualuxe_service_pkg' === $_POST['post_type'] ) {
                if ( ! current_user_can( 'edit_post', $post_id ) ) {
                    return;
                }
            }
        }

        // Save service details
        if ( isset( $_POST['aqualuxe_service_duration'] ) ) {
            update_post_meta( $post_id, '_aqualuxe_service_duration', sanitize_text_field( $_POST['aqualuxe_service_duration'] ) );
        }

        if ( isset( $_POST['aqualuxe_service_location'] ) ) {
            update_post_meta( $post_id, '_aqualuxe_service_location', sanitize_text_field( $_POST['aqualuxe_service_location'] ) );
        }

        if ( isset( $_POST['aqualuxe_service_features'] ) ) {
            update_post_meta( $post_id, '_aqualuxe_service_features', sanitize_textarea_field( $_POST['aqualuxe_service_features'] ) );
        }

        // Save service pricing
        if ( isset( $_POST['aqualuxe_service_price'] ) ) {
            update_post_meta( $post_id, '_aqualuxe_service_price', sanitize_text_field( $_POST['aqualuxe_service_price'] ) );
        }

        if ( isset( $_POST['aqualuxe_service_price_type'] ) ) {
            update_post_meta( $post_id, '_aqualuxe_service_price_type', sanitize_text_field( $_POST['aqualuxe_service_price_type'] ) );
        }

        if ( isset( $_POST['aqualuxe_service_sale_price'] ) ) {
            update_post_meta( $post_id, '_aqualuxe_service_sale_price', sanitize_text_field( $_POST['aqualuxe_service_sale_price'] ) );
        }

        // Save service booking options
        if ( isset( $_POST['aqualuxe_service_bookable'] ) ) {
            update_post_meta( $post_id, '_aqualuxe_service_bookable', 'yes' );
        } else {
            update_post_meta( $post_id, '_aqualuxe_service_bookable', 'no' );
        }

        if ( isset( $_POST['aqualuxe_service_capacity'] ) ) {
            update_post_meta( $post_id, '_aqualuxe_service_capacity', sanitize_text_field( $_POST['aqualuxe_service_capacity'] ) );
        }

        // Save service package details
        if ( isset( $_POST['aqualuxe_service_package_duration'] ) ) {
            update_post_meta( $post_id, '_aqualuxe_service_package_duration', sanitize_text_field( $_POST['aqualuxe_service_package_duration'] ) );
        }

        // Save included services
        if ( isset( $_POST['aqualuxe_service_package_services'] ) ) {
            update_post_meta( $post_id, '_aqualuxe_service_package_services', array_map( 'intval', $_POST['aqualuxe_service_package_services'] ) );
        }

        // Save package pricing
        if ( isset( $_POST['aqualuxe_service_package_price'] ) ) {
            update_post_meta( $post_id, '_aqualuxe_service_package_price', sanitize_text_field( $_POST['aqualuxe_service_package_price'] ) );
        }

        if ( isset( $_POST['aqualuxe_service_package_sale_price'] ) ) {
            update_post_meta( $post_id, '_aqualuxe_service_package_sale_price', sanitize_text_field( $_POST['aqualuxe_service_package_sale_price'] ) );
        }
    }

    /**
     * Add service product options to WooCommerce
     *
     * @return void
     */
    public function add_service_product_options() {
        // Add service product options to WooCommerce
        echo '<div class="options_group show_if_simple show_if_variable">';
        
        woocommerce_wp_checkbox(
            [
                'id'          => '_is_aqualuxe_service',
                'label'       => __( 'AquaLuxe Service', 'aqualuxe' ),
                'description' => __( 'Check this if this product is an AquaLuxe service.', 'aqualuxe' ),
            ]
        );

        woocommerce_wp_select(
            [
                'id'          => '_aqualuxe_service_id',
                'label'       => __( 'Linked Service', 'aqualuxe' ),
                'description' => __( 'Select the service to link to this product.', 'aqualuxe' ),
                'options'     => $this->get_services_for_select(),
                'desc_tip'    => true,
            ]
        );

        echo '</div>';
    }

    /**
     * Save service product options
     *
     * @param int $post_id
     * @return void
     */
    public function save_service_product_options( $post_id ) {
        // Save service product options
        $is_service = isset( $_POST['_is_aqualuxe_service'] ) ? 'yes' : 'no';
        update_post_meta( $post_id, '_is_aqualuxe_service', $is_service );

        if ( isset( $_POST['_aqualuxe_service_id'] ) ) {
            update_post_meta( $post_id, '_aqualuxe_service_id', sanitize_text_field( $_POST['_aqualuxe_service_id'] ) );
        }
    }

    /**
     * Add services to bookable items
     *
     * @param array $items
     * @return array
     */
    public function add_services_to_bookable_items( $items ) {
        // Get all services that are bookable
        $services = get_posts(
            [
                'post_type'      => 'aqualuxe_service',
                'posts_per_page' => -1,
                'meta_query'     => [
                    [
                        'key'   => '_aqualuxe_service_bookable',
                        'value' => 'yes',
                    ],
                ],
            ]
        );

        // Add services to bookable items
        foreach ( $services as $service ) {
            $items[] = [
                'id'       => $service->ID,
                'title'    => $service->post_title,
                'type'     => 'service',
                'duration' => get_post_meta( $service->ID, '_aqualuxe_service_duration', true ),
                'capacity' => get_post_meta( $service->ID, '_aqualuxe_service_capacity', true ),
            ];
        }

        return $items;
    }

    /**
     * Services shortcode
     *
     * @param array $atts
     * @return string
     */
    public function services_shortcode( $atts ) {
        // Parse attributes
        $atts = shortcode_atts(
            [
                'category' => '',
                'tag'      => '',
                'limit'    => -1,
                'orderby'  => 'title',
                'order'    => 'ASC',
                'layout'   => 'grid', // grid, list
                'columns'  => 3,
            ],
            $atts,
            'aqualuxe_services'
        );

        // Build query args
        $args = [
            'post_type'      => 'aqualuxe_service',
            'posts_per_page' => $atts['limit'],
            'orderby'        => $atts['orderby'],
            'order'          => $atts['order'],
        ];

        // Add taxonomy queries
        $tax_query = [];

        if ( ! empty( $atts['category'] ) ) {
            $tax_query[] = [
                'taxonomy' => 'service_category',
                'field'    => 'slug',
                'terms'    => explode( ',', $atts['category'] ),
            ];
        }

        if ( ! empty( $atts['tag'] ) ) {
            $tax_query[] = [
                'taxonomy' => 'service_tag',
                'field'    => 'slug',
                'terms'    => explode( ',', $atts['tag'] ),
            ];
        }

        if ( ! empty( $tax_query ) ) {
            $args['tax_query'] = $tax_query;
        }

        // Get services
        $services = get_posts( $args );

        // Start output buffer
        ob_start();

        // Include template
        include __DIR__ . '/templates/services-' . $atts['layout'] . '.php';

        // Return output
        return ob_get_clean();
    }

    /**
     * Service grid shortcode
     *
     * @param array $atts
     * @return string
     */
    public function service_grid_shortcode( $atts ) {
        // Parse attributes
        $atts = shortcode_atts(
            [
                'category' => '',
                'tag'      => '',
                'limit'    => -1,
                'orderby'  => 'title',
                'order'    => 'ASC',
                'columns'  => 3,
            ],
            $atts,
            'aqualuxe_service_grid'
        );

        // Set layout to grid
        $atts['layout'] = 'grid';

        // Call services shortcode
        return $this->services_shortcode( $atts );
    }

    /**
     * Service packages shortcode
     *
     * @param array $atts
     * @return string
     */
    public function service_packages_shortcode( $atts ) {
        // Parse attributes
        $atts = shortcode_atts(
            [
                'category' => '',
                'tag'      => '',
                'limit'    => -1,
                'orderby'  => 'title',
                'order'    => 'ASC',
                'layout'   => 'grid', // grid, list
                'columns'  => 3,
            ],
            $atts,
            'aqualuxe_service_packages'
        );

        // Build query args
        $args = [
            'post_type'      => 'aqualuxe_service_pkg',
            'posts_per_page' => $atts['limit'],
            'orderby'        => $atts['orderby'],
            'order'          => $atts['order'],
        ];

        // Add taxonomy queries
        $tax_query = [];

        if ( ! empty( $atts['category'] ) ) {
            $tax_query[] = [
                'taxonomy' => 'service_category',
                'field'    => 'slug',
                'terms'    => explode( ',', $atts['category'] ),
            ];
        }

        if ( ! empty( $atts['tag'] ) ) {
            $tax_query[] = [
                'taxonomy' => 'service_tag',
                'field'    => 'slug',
                'terms'    => explode( ',', $atts['tag'] ),
            ];
        }

        if ( ! empty( $tax_query ) ) {
            $args['tax_query'] = $tax_query;
        }

        // Get service packages
        $packages = get_posts( $args );

        // Start output buffer
        ob_start();

        // Include template
        include __DIR__ . '/templates/service-packages-' . $atts['layout'] . '.php';

        // Return output
        return ob_get_clean();
    }

    /**
     * Service comparison shortcode
     *
     * @param array $atts
     * @return string
     */
    public function service_comparison_shortcode( $atts ) {
        // Parse attributes
        $atts = shortcode_atts(
            [
                'ids'      => '',
                'category' => '',
            ],
            $atts,
            'aqualuxe_service_comparison'
        );

        // Build query args
        $args = [
            'post_type'      => 'aqualuxe_service',
            'posts_per_page' => -1,
        ];

        // If IDs are provided, use them
        if ( ! empty( $atts['ids'] ) ) {
            $args['post__in'] = explode( ',', $atts['ids'] );
            $args['orderby']  = 'post__in';
        }
        // Otherwise, use category
        elseif ( ! empty( $atts['category'] ) ) {
            $args['tax_query'] = [
                [
                    'taxonomy' => 'service_category',
                    'field'    => 'slug',
                    'terms'    => explode( ',', $atts['category'] ),
                ],
            ];
        }

        // Get services
        $services = get_posts( $args );

        // Start output buffer
        ob_start();

        // Include template
        include __DIR__ . '/templates/service-comparison.php';

        // Return output
        return ob_get_clean();
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
            <h1><?php echo esc_html__( 'Service Settings', 'aqualuxe' ); ?></h1>
            <form method="post" action="options.php">
                <?php
                settings_fields( 'aqualuxe-service-settings' );
                do_settings_sections( 'aqualuxe-service-settings' );
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    /**
     * Render general settings section
     *
     * @return void
     */
    public function render_general_settings_section() {
        echo '<p>' . esc_html__( 'Configure general service settings.', 'aqualuxe' ) . '</p>';
    }

    /**
     * Render display settings section
     *
     * @return void
     */
    public function render_display_settings_section() {
        echo '<p>' . esc_html__( 'Configure how services are displayed.', 'aqualuxe' ) . '</p>';
    }

    /**
     * Render integration settings section
     *
     * @return void
     */
    public function render_integration_settings_section() {
        echo '<p>' . esc_html__( 'Configure service integration settings.', 'aqualuxe' ) . '</p>';
    }

    /**
     * Render services page field
     *
     * @return void
     */
    public function render_services_page_field() {
        $settings = get_option( 'aqualuxe_service_settings', [] );
        $services_page = isset( $settings['services_page'] ) ? $settings['services_page'] : '';

        wp_dropdown_pages(
            [
                'name'              => 'aqualuxe_service_settings[services_page]',
                'selected'          => $services_page,
                'show_option_none'  => __( 'Select a page', 'aqualuxe' ),
            ]
        );
        echo '<p class="description">' . esc_html__( 'Select the page where services will be displayed.', 'aqualuxe' ) . '</p>';
    }

    /**
     * Render service columns field
     *
     * @return void
     */
    public function render_service_columns_field() {
        $settings = get_option( 'aqualuxe_service_settings', [] );
        $service_columns = isset( $settings['service_columns'] ) ? $settings['service_columns'] : 3;

        ?>
        <select name="aqualuxe_service_settings[service_columns]">
            <option value="2" <?php selected( $service_columns, 2 ); ?>><?php esc_html_e( '2 Columns', 'aqualuxe' ); ?></option>
            <option value="3" <?php selected( $service_columns, 3 ); ?>><?php esc_html_e( '3 Columns', 'aqualuxe' ); ?></option>
            <option value="4" <?php selected( $service_columns, 4 ); ?>><?php esc_html_e( '4 Columns', 'aqualuxe' ); ?></option>
        </select>
        <p class="description"><?php esc_html_e( 'Select the number of columns for service grid display.', 'aqualuxe' ); ?></p>
        <?php
    }

    /**
     * Render show pricing field
     *
     * @return void
     */
    public function render_show_pricing_field() {
        $settings = get_option( 'aqualuxe_service_settings', [] );
        $show_pricing = isset( $settings['show_pricing'] ) ? $settings['show_pricing'] : true;

        ?>
        <label>
            <input type="checkbox" name="aqualuxe_service_settings[show_pricing]" value="1" <?php checked( $show_pricing, true ); ?> />
            <?php esc_html_e( 'Show pricing information on service listings', 'aqualuxe' ); ?>
        </label>
        <?php
    }

    /**
     * Render booking integration field
     *
     * @return void
     */
    public function render_booking_integration_field() {
        $settings = get_option( 'aqualuxe_service_settings', [] );
        $booking_integration = isset( $settings['booking_integration'] ) ? $settings['booking_integration'] : true;

        ?>
        <label>
            <input type="checkbox" name="aqualuxe_service_settings[booking_integration]" value="1" <?php checked( $booking_integration, true ); ?> />
            <?php esc_html_e( 'Enable integration with Bookings module', 'aqualuxe' ); ?>
        </label>
        <?php
    }

    /**
     * Render WooCommerce integration field
     *
     * @return void
     */
    public function render_woocommerce_integration_field() {
        $settings = get_option( 'aqualuxe_service_settings', [] );
        $woocommerce_integration = isset( $settings['woocommerce_integration'] ) ? $settings['woocommerce_integration'] : true;

        ?>
        <label>
            <input type="checkbox" name="aqualuxe_service_settings[woocommerce_integration]" value="1" <?php checked( $woocommerce_integration, true ); ?> />
            <?php esc_html_e( 'Enable integration with WooCommerce', 'aqualuxe' ); ?>
        </label>
        <?php
    }

    /**
     * Render service details meta box
     *
     * @param \WP_Post $post
     * @return void
     */
    public function render_service_details_meta_box( $post ) {
        // Render service details meta box
        wp_nonce_field( 'aqualuxe_service_details', 'aqualuxe_service_details_nonce' );

        $duration = get_post_meta( $post->ID, '_aqualuxe_service_duration', true );
        $location = get_post_meta( $post->ID, '_aqualuxe_service_location', true );
        $features = get_post_meta( $post->ID, '_aqualuxe_service_features', true );

        ?>
        <div class="aqualuxe-meta-box-row">
            <label for="aqualuxe-service-duration"><?php esc_html_e( 'Duration (minutes)', 'aqualuxe' ); ?></label>
            <input type="number" id="aqualuxe-service-duration" name="aqualuxe_service_duration" value="<?php echo esc_attr( $duration ); ?>" min="1" step="1" />
        </div>

        <div class="aqualuxe-meta-box-row">
            <label for="aqualuxe-service-location"><?php esc_html_e( 'Location', 'aqualuxe' ); ?></label>
            <input type="text" id="aqualuxe-service-location" name="aqualuxe_service_location" value="<?php echo esc_attr( $location ); ?>" />
        </div>

        <div class="aqualuxe-meta-box-row">
            <label for="aqualuxe-service-features"><?php esc_html_e( 'Features (one per line)', 'aqualuxe' ); ?></label>
            <textarea id="aqualuxe-service-features" name="aqualuxe_service_features" rows="5"><?php echo esc_textarea( $features ); ?></textarea>
        </div>
        <?php
    }

    /**
     * Render service pricing meta box
     *
     * @param \WP_Post $post
     * @return void
     */
    public function render_service_pricing_meta_box( $post ) {
        // Render service pricing meta box
        wp_nonce_field( 'aqualuxe_service_pricing', 'aqualuxe_service_pricing_nonce' );

        $price = get_post_meta( $post->ID, '_aqualuxe_service_price', true );
        $price_type = get_post_meta( $post->ID, '_aqualuxe_service_price_type', true );
        if ( ! $price_type ) {
            $price_type = 'fixed';
        }
        $sale_price = get_post_meta( $post->ID, '_aqualuxe_service_sale_price', true );

        ?>
        <div class="aqualuxe-meta-box-row">
            <label for="aqualuxe-service-price"><?php esc_html_e( 'Price', 'aqualuxe' ); ?></label>
            <input type="text" id="aqualuxe-service-price" name="aqualuxe_service_price" value="<?php echo esc_attr( $price ); ?>" />
        </div>

        <div class="aqualuxe-meta-box-row">
            <label for="aqualuxe-service-price-type"><?php esc_html_e( 'Price Type', 'aqualuxe' ); ?></label>
            <select id="aqualuxe-service-price-type" name="aqualuxe_service_price_type">
                <option value="fixed" <?php selected( $price_type, 'fixed' ); ?>><?php esc_html_e( 'Fixed Price', 'aqualuxe' ); ?></option>
                <option value="starting" <?php selected( $price_type, 'starting' ); ?>><?php esc_html_e( 'Starting From', 'aqualuxe' ); ?></option>
                <option value="hourly" <?php selected( $price_type, 'hourly' ); ?>><?php esc_html_e( 'Per Hour', 'aqualuxe' ); ?></option>
                <option value="quote" <?php selected( $price_type, 'quote' ); ?>><?php esc_html_e( 'Quote Only', 'aqualuxe' ); ?></option>
            </select>
        </div>

        <div class="aqualuxe-meta-box-row">
            <label for="aqualuxe-service-sale-price"><?php esc_html_e( 'Sale Price', 'aqualuxe' ); ?></label>
            <input type="text" id="aqualuxe-service-sale-price" name="aqualuxe_service_sale_price" value="<?php echo esc_attr( $sale_price ); ?>" />
            <p class="description"><?php esc_html_e( 'Leave empty for no sale price.', 'aqualuxe' ); ?></p>
        </div>
        <?php
    }

    /**
     * Render service booking meta box
     *
     * @param \WP_Post $post
     * @return void
     */
    public function render_service_booking_meta_box( $post ) {
        // Render service booking meta box
        wp_nonce_field( 'aqualuxe_service_booking', 'aqualuxe_service_booking_nonce' );

        $bookable = get_post_meta( $post->ID, '_aqualuxe_service_bookable', true );
        $capacity = get_post_meta( $post->ID, '_aqualuxe_service_capacity', true );
        if ( ! $capacity ) {
            $capacity = 1;
        }

        ?>
        <div class="aqualuxe-meta-box-row">
            <label>
                <input type="checkbox" name="aqualuxe_service_bookable" value="yes" <?php checked( $bookable, 'yes' ); ?> />
                <?php esc_html_e( 'This service is bookable', 'aqualuxe' ); ?>
            </label>
        </div>

        <div class="aqualuxe-meta-box-row">
            <label for="aqualuxe-service-capacity"><?php esc_html_e( 'Capacity (people)', 'aqualuxe' ); ?></label>
            <input type="number" id="aqualuxe-service-capacity" name="aqualuxe_service_capacity" value="<?php echo esc_attr( $capacity ); ?>" min="1" step="1" />
        </div>
        <?php
    }

    /**
     * Render service package details meta box
     *
     * @param \WP_Post $post
     * @return void
     */
    public function render_service_package_details_meta_box( $post ) {
        // Render service package details meta box
        wp_nonce_field( 'aqualuxe_service_package_details', 'aqualuxe_service_package_details_nonce' );

        $duration = get_post_meta( $post->ID, '_aqualuxe_service_package_duration', true );

        ?>
        <div class="aqualuxe-meta-box-row">
            <label for="aqualuxe-service-package-duration"><?php esc_html_e( 'Total Duration (minutes)', 'aqualuxe' ); ?></label>
            <input type="number" id="aqualuxe-service-package-duration" name="aqualuxe_service_package_duration" value="<?php echo esc_attr( $duration ); ?>" min="1" step="1" />
        </div>
        <?php
    }

    /**
     * Render service package services meta box
     *
     * @param \WP_Post $post
     * @return void
     */
    public function render_service_package_services_meta_box( $post ) {
        // Render service package services meta box
        wp_nonce_field( 'aqualuxe_service_package_services', 'aqualuxe_service_package_services_nonce' );

        $included_services = get_post_meta( $post->ID, '_aqualuxe_service_package_services', true );
        if ( ! is_array( $included_services ) ) {
            $included_services = [];
        }

        // Get all services
        $services = get_posts(
            [
                'post_type'      => 'aqualuxe_service',
                'posts_per_page' => -1,
                'orderby'        => 'title',
                'order'          => 'ASC',
            ]
        );

        ?>
        <div class="aqualuxe-meta-box-row">
            <label><?php esc_html_e( 'Select Services to Include', 'aqualuxe' ); ?></label>
            <div class="aqualuxe-service-package-services">
                <?php foreach ( $services as $service ) : ?>
                    <label>
                        <input type="checkbox" name="aqualuxe_service_package_services[]" value="<?php echo esc_attr( $service->ID ); ?>" <?php checked( in_array( $service->ID, $included_services ) ); ?> />
                        <?php echo esc_html( $service->post_title ); ?>
                    </label><br>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }

    /**
     * Render service package pricing meta box
     *
     * @param \WP_Post $post
     * @return void
     */
    public function render_service_package_pricing_meta_box( $post ) {
        // Render service package pricing meta box
        wp_nonce_field( 'aqualuxe_service_package_pricing', 'aqualuxe_service_package_pricing_nonce' );

        $price = get_post_meta( $post->ID, '_aqualuxe_service_package_price', true );
        $sale_price = get_post_meta( $post->ID, '_aqualuxe_service_package_sale_price', true );

        ?>
        <div class="aqualuxe-meta-box-row">
            <label for="aqualuxe-service-package-price"><?php esc_html_e( 'Package Price', 'aqualuxe' ); ?></label>
            <input type="text" id="aqualuxe-service-package-price" name="aqualuxe_service_package_price" value="<?php echo esc_attr( $price ); ?>" />
        </div>

        <div class="aqualuxe-meta-box-row">
            <label for="aqualuxe-service-package-sale-price"><?php esc_html_e( 'Package Sale Price', 'aqualuxe' ); ?></label>
            <input type="text" id="aqualuxe-service-package-sale-price" name="aqualuxe_service_package_sale_price" value="<?php echo esc_attr( $sale_price ); ?>" />
            <p class="description"><?php esc_html_e( 'Leave empty for no sale price.', 'aqualuxe' ); ?></p>
        </div>
        <?php
    }

    /**
     * Get services for select
     *
     * @return array
     */
    private function get_services_for_select() {
        $services = get_posts(
            [
                'post_type'      => 'aqualuxe_service',
                'posts_per_page' => -1,
                'orderby'        => 'title',
                'order'          => 'ASC',
            ]
        );

        $options = [ '' => __( 'Select a service', 'aqualuxe' ) ];

        foreach ( $services as $service ) {
            $options[ $service->ID ] = $service->post_title;
        }

        return $options;
    }
}

// Initialize the module
new Services();