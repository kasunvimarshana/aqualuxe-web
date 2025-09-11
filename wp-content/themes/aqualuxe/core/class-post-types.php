<?php
/**
 * Custom Post Types Class
 *
 * Registers custom post types for the theme
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * AquaLuxe Post Types Class
 */
class AquaLuxe_Post_Types {

    /**
     * Constructor
     */
    public function __construct() {
        // Class is auto-initialized when loaded
    }

    /**
     * Register custom post types
     */
    public function register() {
        add_action( 'init', array( $this, 'register_post_types' ) );
        add_action( 'init', array( $this, 'register_taxonomies' ) );
        add_filter( 'manage_edit-service_columns', array( $this, 'service_columns' ) );
        add_action( 'manage_service_posts_custom_column', array( $this, 'service_custom_column' ), 10, 2 );
    }

    /**
     * Register custom post types
     */
    public function register_post_types() {
        
        // Services Post Type
        register_post_type( 'service', array(
            'labels' => array(
                'name'               => esc_html__( 'Services', 'aqualuxe' ),
                'singular_name'      => esc_html__( 'Service', 'aqualuxe' ),
                'menu_name'          => esc_html__( 'Services', 'aqualuxe' ),
                'name_admin_bar'     => esc_html__( 'Service', 'aqualuxe' ),
                'add_new'            => esc_html__( 'Add New', 'aqualuxe' ),
                'add_new_item'       => esc_html__( 'Add New Service', 'aqualuxe' ),
                'new_item'           => esc_html__( 'New Service', 'aqualuxe' ),
                'edit_item'          => esc_html__( 'Edit Service', 'aqualuxe' ),
                'view_item'          => esc_html__( 'View Service', 'aqualuxe' ),
                'all_items'          => esc_html__( 'All Services', 'aqualuxe' ),
                'search_items'       => esc_html__( 'Search Services', 'aqualuxe' ),
                'parent_item_colon'  => esc_html__( 'Parent Services:', 'aqualuxe' ),
                'not_found'          => esc_html__( 'No services found.', 'aqualuxe' ),
                'not_found_in_trash' => esc_html__( 'No services found in Trash.', 'aqualuxe' ),
            ),
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
        ) );

        // Team Members Post Type
        register_post_type( 'team', array(
            'labels' => array(
                'name'               => esc_html__( 'Team Members', 'aqualuxe' ),
                'singular_name'      => esc_html__( 'Team Member', 'aqualuxe' ),
                'menu_name'          => esc_html__( 'Team', 'aqualuxe' ),
                'name_admin_bar'     => esc_html__( 'Team Member', 'aqualuxe' ),
                'add_new'            => esc_html__( 'Add New', 'aqualuxe' ),
                'add_new_item'       => esc_html__( 'Add New Team Member', 'aqualuxe' ),
                'new_item'           => esc_html__( 'New Team Member', 'aqualuxe' ),
                'edit_item'          => esc_html__( 'Edit Team Member', 'aqualuxe' ),
                'view_item'          => esc_html__( 'View Team Member', 'aqualuxe' ),
                'all_items'          => esc_html__( 'All Team Members', 'aqualuxe' ),
                'search_items'       => esc_html__( 'Search Team Members', 'aqualuxe' ),
                'not_found'          => esc_html__( 'No team members found.', 'aqualuxe' ),
                'not_found_in_trash' => esc_html__( 'No team members found in Trash.', 'aqualuxe' ),
            ),
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array( 'slug' => 'team' ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => 21,
            'menu_icon'          => 'dashicons-groups',
            'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
            'show_in_rest'       => true,
        ) );

        // Testimonials Post Type
        register_post_type( 'testimonial', array(
            'labels' => array(
                'name'               => esc_html__( 'Testimonials', 'aqualuxe' ),
                'singular_name'      => esc_html__( 'Testimonial', 'aqualuxe' ),
                'menu_name'          => esc_html__( 'Testimonials', 'aqualuxe' ),
                'name_admin_bar'     => esc_html__( 'Testimonial', 'aqualuxe' ),
                'add_new'            => esc_html__( 'Add New', 'aqualuxe' ),
                'add_new_item'       => esc_html__( 'Add New Testimonial', 'aqualuxe' ),
                'new_item'           => esc_html__( 'New Testimonial', 'aqualuxe' ),
                'edit_item'          => esc_html__( 'Edit Testimonial', 'aqualuxe' ),
                'view_item'          => esc_html__( 'View Testimonial', 'aqualuxe' ),
                'all_items'          => esc_html__( 'All Testimonials', 'aqualuxe' ),
                'search_items'       => esc_html__( 'Search Testimonials', 'aqualuxe' ),
                'not_found'          => esc_html__( 'No testimonials found.', 'aqualuxe' ),
                'not_found_in_trash' => esc_html__( 'No testimonials found in Trash.', 'aqualuxe' ),
            ),
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
            'supports'           => array( 'title', 'editor', 'thumbnail' ),
            'show_in_rest'       => true,
        ) );

        // Fish Species Post Type
        register_post_type( 'fish', array(
            'labels' => array(
                'name'               => esc_html__( 'Fish Species', 'aqualuxe' ),
                'singular_name'      => esc_html__( 'Fish Species', 'aqualuxe' ),
                'menu_name'          => esc_html__( 'Fish', 'aqualuxe' ),
                'name_admin_bar'     => esc_html__( 'Fish Species', 'aqualuxe' ),
                'add_new'            => esc_html__( 'Add New', 'aqualuxe' ),
                'add_new_item'       => esc_html__( 'Add New Fish Species', 'aqualuxe' ),
                'new_item'           => esc_html__( 'New Fish Species', 'aqualuxe' ),
                'edit_item'          => esc_html__( 'Edit Fish Species', 'aqualuxe' ),
                'view_item'          => esc_html__( 'View Fish Species', 'aqualuxe' ),
                'all_items'          => esc_html__( 'All Fish Species', 'aqualuxe' ),
                'search_items'       => esc_html__( 'Search Fish Species', 'aqualuxe' ),
                'not_found'          => esc_html__( 'No fish species found.', 'aqualuxe' ),
                'not_found_in_trash' => esc_html__( 'No fish species found in Trash.', 'aqualuxe' ),
            ),
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array( 'slug' => 'fish-species' ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => 23,
            'menu_icon'          => 'dashicons-pets',
            'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
            'show_in_rest'       => true,
        ) );

        // Events Post Type
        register_post_type( 'event', array(
            'labels' => array(
                'name'               => esc_html__( 'Events', 'aqualuxe' ),
                'singular_name'      => esc_html__( 'Event', 'aqualuxe' ),
                'menu_name'          => esc_html__( 'Events', 'aqualuxe' ),
                'name_admin_bar'     => esc_html__( 'Event', 'aqualuxe' ),
                'add_new'            => esc_html__( 'Add New', 'aqualuxe' ),
                'add_new_item'       => esc_html__( 'Add New Event', 'aqualuxe' ),
                'new_item'           => esc_html__( 'New Event', 'aqualuxe' ),
                'edit_item'          => esc_html__( 'Edit Event', 'aqualuxe' ),
                'view_item'          => esc_html__( 'View Event', 'aqualuxe' ),
                'all_items'          => esc_html__( 'All Events', 'aqualuxe' ),
                'search_items'       => esc_html__( 'Search Events', 'aqualuxe' ),
                'not_found'          => esc_html__( 'No events found.', 'aqualuxe' ),
                'not_found_in_trash' => esc_html__( 'No events found in Trash.', 'aqualuxe' ),
            ),
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array( 'slug' => 'events' ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => 24,
            'menu_icon'          => 'dashicons-calendar-alt',
            'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
            'show_in_rest'       => true,
        ) );
    }

    /**
     * Register custom taxonomies
     */
    public function register_taxonomies() {
        
        // Service Categories
        register_taxonomy( 'service_category', 'service', array(
            'labels' => array(
                'name'              => esc_html__( 'Service Categories', 'aqualuxe' ),
                'singular_name'     => esc_html__( 'Service Category', 'aqualuxe' ),
                'search_items'      => esc_html__( 'Search Service Categories', 'aqualuxe' ),
                'all_items'         => esc_html__( 'All Service Categories', 'aqualuxe' ),
                'parent_item'       => esc_html__( 'Parent Service Category', 'aqualuxe' ),
                'parent_item_colon' => esc_html__( 'Parent Service Category:', 'aqualuxe' ),
                'edit_item'         => esc_html__( 'Edit Service Category', 'aqualuxe' ),
                'update_item'       => esc_html__( 'Update Service Category', 'aqualuxe' ),
                'add_new_item'      => esc_html__( 'Add New Service Category', 'aqualuxe' ),
                'new_item_name'     => esc_html__( 'New Service Category Name', 'aqualuxe' ),
                'menu_name'         => esc_html__( 'Service Categories', 'aqualuxe' ),
            ),
            'hierarchical'      => true,
            'public'            => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'service-category' ),
            'show_in_rest'      => true,
        ) );

        // Fish Water Types
        register_taxonomy( 'fish_water_type', 'fish', array(
            'labels' => array(
                'name'              => esc_html__( 'Water Types', 'aqualuxe' ),
                'singular_name'     => esc_html__( 'Water Type', 'aqualuxe' ),
                'search_items'      => esc_html__( 'Search Water Types', 'aqualuxe' ),
                'all_items'         => esc_html__( 'All Water Types', 'aqualuxe' ),
                'edit_item'         => esc_html__( 'Edit Water Type', 'aqualuxe' ),
                'update_item'       => esc_html__( 'Update Water Type', 'aqualuxe' ),
                'add_new_item'      => esc_html__( 'Add New Water Type', 'aqualuxe' ),
                'new_item_name'     => esc_html__( 'New Water Type Name', 'aqualuxe' ),
                'menu_name'         => esc_html__( 'Water Types', 'aqualuxe' ),
            ),
            'hierarchical'      => false,
            'public'            => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'water-type' ),
            'show_in_rest'      => true,
        ) );

        // Fish Care Levels
        register_taxonomy( 'fish_care_level', 'fish', array(
            'labels' => array(
                'name'              => esc_html__( 'Care Levels', 'aqualuxe' ),
                'singular_name'     => esc_html__( 'Care Level', 'aqualuxe' ),
                'search_items'      => esc_html__( 'Search Care Levels', 'aqualuxe' ),
                'all_items'         => esc_html__( 'All Care Levels', 'aqualuxe' ),
                'edit_item'         => esc_html__( 'Edit Care Level', 'aqualuxe' ),
                'update_item'       => esc_html__( 'Update Care Level', 'aqualuxe' ),
                'add_new_item'      => esc_html__( 'Add New Care Level', 'aqualuxe' ),
                'new_item_name'     => esc_html__( 'New Care Level Name', 'aqualuxe' ),
                'menu_name'         => esc_html__( 'Care Levels', 'aqualuxe' ),
            ),
            'hierarchical'      => false,
            'public'            => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'care-level' ),
            'show_in_rest'      => true,
        ) );

        // Event Types
        register_taxonomy( 'event_type', 'event', array(
            'labels' => array(
                'name'              => esc_html__( 'Event Types', 'aqualuxe' ),
                'singular_name'     => esc_html__( 'Event Type', 'aqualuxe' ),
                'search_items'      => esc_html__( 'Search Event Types', 'aqualuxe' ),
                'all_items'         => esc_html__( 'All Event Types', 'aqualuxe' ),
                'edit_item'         => esc_html__( 'Edit Event Type', 'aqualuxe' ),
                'update_item'       => esc_html__( 'Update Event Type', 'aqualuxe' ),
                'add_new_item'      => esc_html__( 'Add New Event Type', 'aqualuxe' ),
                'new_item_name'     => esc_html__( 'New Event Type Name', 'aqualuxe' ),
                'menu_name'         => esc_html__( 'Event Types', 'aqualuxe' ),
            ),
            'hierarchical'      => true,
            'public'            => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'event-type' ),
            'show_in_rest'      => true,
        ) );
    }

    /**
     * Customize service columns
     *
     * @param array $columns
     * @return array
     */
    public function service_columns( $columns ) {
        $columns['service_category'] = esc_html__( 'Category', 'aqualuxe' );
        $columns['featured_image']   = esc_html__( 'Image', 'aqualuxe' );
        return $columns;
    }

    /**
     * Service custom column content
     *
     * @param string $column
     * @param int    $post_id
     */
    public function service_custom_column( $column, $post_id ) {
        switch ( $column ) {
            case 'service_category':
                $terms = get_the_terms( $post_id, 'service_category' );
                if ( $terms && ! is_wp_error( $terms ) ) {
                    $term_names = array();
                    foreach ( $terms as $term ) {
                        $term_names[] = $term->name;
                    }
                    echo esc_html( implode( ', ', $term_names ) );
                } else {
                    echo '—';
                }
                break;
                
            case 'featured_image':
                if ( has_post_thumbnail( $post_id ) ) {
                    echo get_the_post_thumbnail( $post_id, array( 50, 50 ) );
                } else {
                    echo '—';
                }
                break;
        }
    }
}