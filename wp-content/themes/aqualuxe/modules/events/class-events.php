<?php
/**
 * Events Calendar Module
 *
 * @package AquaLuxe
 * @subpackage Modules\Events
 * @since 1.0.0
 */
namespace AquaLuxe\Modules\Events;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Events Calendar Module Class
 *
 * This class handles event functionality for aquatic businesses.
 */
class Events {
    /**
     * Instance of this class
     *
     * @var Events
     */
    private static $instance = null;

    /**
     * Module slug
     *
     * @var string
     */
    private $slug = 'events';

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
            $admin_file = __DIR__ . '/admin/class-admin.php';
            if ( file_exists( $admin_file ) ) {
                require_once $admin_file;
            }
        }

        // Include frontend files
        $event_files = array(
            __DIR__ . '/inc/class-event.php',
            __DIR__ . '/inc/class-event-calendar.php',
            __DIR__ . '/inc/class-event-widget.php',
        );
        foreach ( $event_files as $file ) {
            if ( file_exists( $file ) ) {
                require_once $file;
            }
        }
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



        // Register REST API endpoints
        add_action( 'rest_api_init', [ $this, 'register_rest_routes' ] );


        // Add menu items
    // add_action( 'admin_menu', [ $this, 'add_menu_items' ] ); // Method does not exist, removed to prevent fatal error

        // Add settings
    // add_action( 'admin_init', [ $this, 'register_settings' ] ); // Method does not exist, removed to prevent fatal error

        // Add meta boxes
        add_action( 'add_meta_boxes', [ $this, 'add_meta_boxes' ] );

    // Save post meta handled by admin class only. Removed to prevent fatal error.

        // Add event data to WooCommerce orders if WooCommerce is active
        if ( class_exists( 'WooCommerce' ) ) {
            add_action( 'woocommerce_checkout_create_order_line_item', [ $this, 'add_event_data_to_order_item' ], 10, 4 );
        }

        // Add custom columns to admin list
    add_filter( 'manage_aqlx_event_posts_columns', [ $this, 'add_event_columns' ] );
    add_action( 'manage_aqlx_event_posts_custom_column', [ $this, 'render_event_columns' ], 10, 2 );
    add_filter( 'manage_edit-aqlx_event_sortable_columns', [ $this, 'sortable_event_columns' ] );

        // Add event to Google Calendar
        add_action( 'wp_ajax_aqualuxe_add_to_google_calendar', [ $this, 'add_to_google_calendar' ] );
        add_action( 'wp_ajax_nopriv_aqualuxe_add_to_google_calendar', [ $this, 'add_to_google_calendar' ] );

        // Add event to iCal
        add_action( 'wp_ajax_aqualuxe_add_to_ical', [ $this, 'add_to_ical' ] );
        add_action( 'wp_ajax_nopriv_aqualuxe_add_to_ical', [ $this, 'add_to_ical' ] );

        // Add event schema markup
    // add_action( 'wp_head', [ $this, 'add_event_schema' ] ); // Method does not exist, removed to prevent fatal error
    }

    /**
     * Register post types
     *
     * @return void
     */
    public function register_post_types() {
        // Register event post type
        register_post_type(
            'aqlx_event',
            [
                'labels'              => [
                    'name'                  => __( 'Events', 'aqualuxe' ),
                    'singular_name'         => __( 'Event', 'aqualuxe' ),
                    'menu_name'             => __( 'Events', 'aqualuxe' ),
                    'name_admin_bar'        => __( 'Event', 'aqualuxe' ),
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
                    'featured_image'        => __( 'Event Image', 'aqualuxe' ),
                    'set_featured_image'    => __( 'Set event image', 'aqualuxe' ),
                    'remove_featured_image' => __( 'Remove event image', 'aqualuxe' ),
                    'use_featured_image'    => __( 'Use as event image', 'aqualuxe' ),
                ],
                'public'              => true,
                'publicly_queryable'  => true,
                'show_ui'             => true,
                'show_in_menu'        => true,
                'query_var'           => true,
                'rewrite'             => [ 'slug' => 'event' ],
                'capability_type'     => 'post',
                'has_archive'         => true,
                'hierarchical'        => false,
                'menu_position'       => null,
                'menu_icon'           => 'dashicons-calendar',
                'supports'            => [ 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'comments' ],
                'show_in_rest'        => true,
            ]
        );

        // Register event venue post type
        register_post_type(
            'aqlx_venue',
            [
                'labels'              => [
                    'name'                  => __( 'Venues', 'aqualuxe' ),
                    'singular_name'         => __( 'Venue', 'aqualuxe' ),
                    'menu_name'             => __( 'Venues', 'aqualuxe' ),
                    'name_admin_bar'        => __( 'Venue', 'aqualuxe' ),
                    'add_new'               => __( 'Add New', 'aqualuxe' ),
                    'add_new_item'          => __( 'Add New Venue', 'aqualuxe' ),
                    'new_item'              => __( 'New Venue', 'aqualuxe' ),
                    'edit_item'             => __( 'Edit Venue', 'aqualuxe' ),
                    'view_item'             => __( 'View Venue', 'aqualuxe' ),
                    'all_items'             => __( 'All Venues', 'aqualuxe' ),
                    'search_items'          => __( 'Search Venues', 'aqualuxe' ),
                    'parent_item_colon'     => __( 'Parent Venues:', 'aqualuxe' ),
                    'not_found'             => __( 'No venues found.', 'aqualuxe' ),
                    'not_found_in_trash'    => __( 'No venues found in Trash.', 'aqualuxe' ),
                    'featured_image'        => __( 'Venue Image', 'aqualuxe' ),
                    'set_featured_image'    => __( 'Set venue image', 'aqualuxe' ),
                    'remove_featured_image' => __( 'Remove venue image', 'aqualuxe' ),
                    'use_featured_image'    => __( 'Use as venue image', 'aqualuxe' ),
                ],
                'public'              => true,
                'publicly_queryable'  => true,
                'show_ui'             => true,
                'show_in_menu'        => 'edit.php?post_type=aqlx_event',
                'query_var'           => true,
                'rewrite'             => [ 'slug' => 'venue' ],
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
        // Register event category taxonomy
        register_taxonomy(
            'event_category',
            [ 'aqualuxe_event' ],
            [
                'labels'            => [
                    'name'                       => __( 'Event Categories', 'aqualuxe' ),
                    'singular_name'              => __( 'Event Category', 'aqualuxe' ),
                    'search_items'               => __( 'Search Event Categories', 'aqualuxe' ),
                    'popular_items'              => __( 'Popular Event Categories', 'aqualuxe' ),
                    'all_items'                  => __( 'All Event Categories', 'aqualuxe' ),
                    'parent_item'                => __( 'Parent Event Category', 'aqualuxe' ),
                    'parent_item_colon'          => __( 'Parent Event Category:', 'aqualuxe' ),
                    'edit_item'                  => __( 'Edit Event Category', 'aqualuxe' ),
                    'update_item'                => __( 'Update Event Category', 'aqualuxe' ),
                    'add_new_item'               => __( 'Add New Event Category', 'aqualuxe' ),
                    'new_item_name'              => __( 'New Event Category Name', 'aqualuxe' ),
                    'separate_items_with_commas' => __( 'Separate event categories with commas', 'aqualuxe' ),
                    'add_or_remove_items'        => __( 'Add or remove event categories', 'aqualuxe' ),
                    'choose_from_most_used'      => __( 'Choose from the most used event categories', 'aqualuxe' ),
                    'not_found'                  => __( 'No event categories found.', 'aqualuxe' ),
                    'menu_name'                  => __( 'Categories', 'aqualuxe' ),
                ],
                'hierarchical'      => true,
                'public'            => true,
                'show_ui'           => true,
                'show_admin_column' => true,
                'query_var'         => true,
                'rewrite'           => [ 'slug' => 'event-category' ],
                'show_in_rest'      => true,
            ]
        );

        // Register event tag taxonomy
        register_taxonomy(
            'event_tag',
            [ 'aqualuxe_event' ],
            [
                'labels'            => [
                    'name'                       => __( 'Event Tags', 'aqualuxe' ),
                    'singular_name'              => __( 'Event Tag', 'aqualuxe' ),
                    'search_items'               => __( 'Search Event Tags', 'aqualuxe' ),
                    'popular_items'              => __( 'Popular Event Tags', 'aqualuxe' ),
                    'all_items'                  => __( 'All Event Tags', 'aqualuxe' ),
                    'parent_item'                => __( 'Parent Event Tag', 'aqualuxe' ),
                    'parent_item_colon'          => __( 'Parent Event Tag:', 'aqualuxe' ),
                    'edit_item'                  => __( 'Edit Event Tag', 'aqualuxe' ),
                    'update_item'                => __( 'Update Event Tag', 'aqualuxe' ),
                    'add_new_item'               => __( 'Add New Event Tag', 'aqualuxe' ),
                    'new_item_name'              => __( 'New Event Tag Name', 'aqualuxe' ),
                    'separate_items_with_commas' => __( 'Separate event tags with commas', 'aqualuxe' ),
                    'add_or_remove_items'        => __( 'Add or remove event tags', 'aqualuxe' ),
                    'choose_from_most_used'      => __( 'Choose from the most used event tags', 'aqualuxe' ),
                    'not_found'                  => __( 'No event tags found.', 'aqualuxe' ),
                    'menu_name'                  => __( 'Tags', 'aqualuxe' ),
                ],
                'hierarchical'      => false,
                'public'            => true,
                'show_ui'           => true,
                'show_admin_column' => true,
                'query_var'         => true,
                'rewrite'           => [ 'slug' => 'event-tag' ],
                'show_in_rest'      => true,
            ]
        );
    }
}