<?php
/**
 * Custom Post Types Registration
 *
 * Registers all custom post types for the AquaLuxe theme.
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Custom Post Types Class
 */
class AquaLuxe_Post_Types {
    
    /**
     * Constructor
     */
    public function __construct() {
        add_action('init', array($this, 'register_post_types'));
        add_action('init', array($this, 'register_meta_fields'));
    }
    
    /**
     * Register custom post types
     */
    public function register_post_types() {
        // Services Post Type
        $this->register_services();
        
        // Events Post Type
        $this->register_events();
        
        // Testimonials Post Type
        $this->register_testimonials();
        
        // Team Members Post Type
        $this->register_team();
        
        // Portfolio Post Type
        $this->register_portfolio();
        
        // FAQ Post Type
        $this->register_faq();
        
        // Partners Post Type
        $this->register_partners();
    }
    
    /**
     * Register Services Post Type
     */
    private function register_services() {
        $labels = array(
            'name'                  => _x('Services', 'Post type general name', 'aqualuxe'),
            'singular_name'         => _x('Service', 'Post type singular name', 'aqualuxe'),
            'menu_name'             => _x('Services', 'Admin Menu text', 'aqualuxe'),
            'name_admin_bar'        => _x('Service', 'Add New on Toolbar', 'aqualuxe'),
            'add_new'               => __('Add New', 'aqualuxe'),
            'add_new_item'          => __('Add New Service', 'aqualuxe'),
            'new_item'              => __('New Service', 'aqualuxe'),
            'edit_item'             => __('Edit Service', 'aqualuxe'),
            'view_item'             => __('View Service', 'aqualuxe'),
            'all_items'             => __('All Services', 'aqualuxe'),
            'search_items'          => __('Search Services', 'aqualuxe'),
            'parent_item_colon'     => __('Parent Services:', 'aqualuxe'),
            'not_found'             => __('No services found.', 'aqualuxe'),
            'not_found_in_trash'    => __('No services found in Trash.', 'aqualuxe'),
            'featured_image'        => _x('Service Featured Image', 'Overrides the "Featured Image" phrase', 'aqualuxe'),
            'set_featured_image'    => _x('Set featured image', 'Overrides the "Set featured image" phrase', 'aqualuxe'),
            'remove_featured_image' => _x('Remove featured image', 'Overrides the "Remove featured image" phrase', 'aqualuxe'),
            'use_featured_image'    => _x('Use as featured image', 'Overrides the "Use as featured image" phrase', 'aqualuxe'),
            'archives'              => _x('Service archives', 'The post type archive label', 'aqualuxe'),
            'insert_into_item'      => _x('Insert into service', 'Overrides the "Insert into post" phrase', 'aqualuxe'),
            'uploaded_to_this_item' => _x('Uploaded to this service', 'Overrides the "Uploaded to this post" phrase', 'aqualuxe'),
            'filter_items_list'     => _x('Filter services list', 'Screen reader text for the filter links', 'aqualuxe'),
            'items_list_navigation' => _x('Services list navigation', 'Screen reader text for the pagination', 'aqualuxe'),
            'items_list'            => _x('Services list', 'Screen reader text for the items list', 'aqualuxe'),
        );
        
        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array('slug' => 'services'),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => 20,
            'menu_icon'          => 'dashicons-admin-tools',
            'show_in_rest'       => true,
            'supports'           => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'revisions'),
        );
        
        register_post_type('service', $args);
    }
    
    /**
     * Register Events Post Type
     */
    private function register_events() {
        $labels = array(
            'name'                  => _x('Events', 'Post type general name', 'aqualuxe'),
            'singular_name'         => _x('Event', 'Post type singular name', 'aqualuxe'),
            'menu_name'             => _x('Events', 'Admin Menu text', 'aqualuxe'),
            'name_admin_bar'        => _x('Event', 'Add New on Toolbar', 'aqualuxe'),
            'add_new'               => __('Add New', 'aqualuxe'),
            'add_new_item'          => __('Add New Event', 'aqualuxe'),
            'new_item'              => __('New Event', 'aqualuxe'),
            'edit_item'             => __('Edit Event', 'aqualuxe'),
            'view_item'             => __('View Event', 'aqualuxe'),
            'all_items'             => __('All Events', 'aqualuxe'),
            'search_items'          => __('Search Events', 'aqualuxe'),
            'not_found'             => __('No events found.', 'aqualuxe'),
            'not_found_in_trash'    => __('No events found in Trash.', 'aqualuxe'),
        );
        
        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array('slug' => 'events'),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => 21,
            'menu_icon'          => 'dashicons-calendar-alt',
            'show_in_rest'       => true,
            'supports'           => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'revisions'),
        );
        
        register_post_type('event', $args);
    }
    
    /**
     * Register Testimonials Post Type
     */
    private function register_testimonials() {
        $labels = array(
            'name'                  => _x('Testimonials', 'Post type general name', 'aqualuxe'),
            'singular_name'         => _x('Testimonial', 'Post type singular name', 'aqualuxe'),
            'menu_name'             => _x('Testimonials', 'Admin Menu text', 'aqualuxe'),
            'add_new_item'          => __('Add New Testimonial', 'aqualuxe'),
            'edit_item'             => __('Edit Testimonial', 'aqualuxe'),
            'view_item'             => __('View Testimonial', 'aqualuxe'),
            'all_items'             => __('All Testimonials', 'aqualuxe'),
        );
        
        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array('slug' => 'testimonials'),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => 22,
            'menu_icon'          => 'dashicons-format-quote',
            'show_in_rest'       => true,
            'supports'           => array('title', 'editor', 'thumbnail', 'custom-fields'),
        );
        
        register_post_type('testimonial', $args);
    }
    
    /**
     * Register Team Post Type
     */
    private function register_team() {
        $labels = array(
            'name'                  => _x('Team Members', 'Post type general name', 'aqualuxe'),
            'singular_name'         => _x('Team Member', 'Post type singular name', 'aqualuxe'),
            'menu_name'             => _x('Team', 'Admin Menu text', 'aqualuxe'),
            'add_new_item'          => __('Add New Team Member', 'aqualuxe'),
            'edit_item'             => __('Edit Team Member', 'aqualuxe'),
            'view_item'             => __('View Team Member', 'aqualuxe'),
            'all_items'             => __('All Team Members', 'aqualuxe'),
        );
        
        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array('slug' => 'team'),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => 23,
            'menu_icon'          => 'dashicons-groups',
            'show_in_rest'       => true,
            'supports'           => array('title', 'editor', 'thumbnail', 'custom-fields'),
        );
        
        register_post_type('team', $args);
    }
    
    /**
     * Register Portfolio Post Type
     */
    private function register_portfolio() {
        $labels = array(
            'name'                  => _x('Portfolio', 'Post type general name', 'aqualuxe'),
            'singular_name'         => _x('Portfolio Item', 'Post type singular name', 'aqualuxe'),
            'menu_name'             => _x('Portfolio', 'Admin Menu text', 'aqualuxe'),
            'add_new_item'          => __('Add New Portfolio Item', 'aqualuxe'),
            'edit_item'             => __('Edit Portfolio Item', 'aqualuxe'),
            'view_item'             => __('View Portfolio Item', 'aqualuxe'),
            'all_items'             => __('All Portfolio Items', 'aqualuxe'),
        );
        
        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array('slug' => 'portfolio'),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => 24,
            'menu_icon'          => 'dashicons-portfolio',
            'show_in_rest'       => true,
            'supports'           => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        );
        
        register_post_type('portfolio', $args);
    }
    
    /**
     * Register FAQ Post Type
     */
    private function register_faq() {
        $labels = array(
            'name'                  => _x('FAQs', 'Post type general name', 'aqualuxe'),
            'singular_name'         => _x('FAQ', 'Post type singular name', 'aqualuxe'),
            'menu_name'             => _x('FAQs', 'Admin Menu text', 'aqualuxe'),
            'add_new_item'          => __('Add New FAQ', 'aqualuxe'),
            'edit_item'             => __('Edit FAQ', 'aqualuxe'),
            'view_item'             => __('View FAQ', 'aqualuxe'),
            'all_items'             => __('All FAQs', 'aqualuxe'),
        );
        
        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array('slug' => 'faq'),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => 25,
            'menu_icon'          => 'dashicons-editor-help',
            'show_in_rest'       => true,
            'supports'           => array('title', 'editor', 'custom-fields', 'page-attributes'),
        );
        
        register_post_type('faq', $args);
    }
    
    /**
     * Register Partners Post Type
     */
    private function register_partners() {
        $labels = array(
            'name'                  => _x('Partners', 'Post type general name', 'aqualuxe'),
            'singular_name'         => _x('Partner', 'Post type singular name', 'aqualuxe'),
            'menu_name'             => _x('Partners', 'Admin Menu text', 'aqualuxe'),
            'add_new_item'          => __('Add New Partner', 'aqualuxe'),
            'edit_item'             => __('Edit Partner', 'aqualuxe'),
            'view_item'             => __('View Partner', 'aqualuxe'),
            'all_items'             => __('All Partners', 'aqualuxe'),
        );
        
        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array('slug' => 'partners'),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => 26,
            'menu_icon'          => 'dashicons-businessman',
            'show_in_rest'       => true,
            'supports'           => array('title', 'editor', 'thumbnail', 'custom-fields'),
        );
        
        register_post_type('partner', $args);
    }
    
    /**
     * Register meta fields
     */
    public function register_meta_fields() {
        // Event meta fields
        register_post_meta('event', 'event_date', array(
            'type'              => 'string',
            'single'            => true,
            'show_in_rest'      => true,
            'sanitize_callback' => 'sanitize_text_field',
            'auth_callback'     => function() {
                return current_user_can('edit_posts');
            },
        ));
        
        register_post_meta('event', 'event_time', array(
            'type'              => 'string',
            'single'            => true,
            'show_in_rest'      => true,
            'sanitize_callback' => 'sanitize_text_field',
            'auth_callback'     => function() {
                return current_user_can('edit_posts');
            },
        ));
        
        register_post_meta('event', 'event_location', array(
            'type'              => 'string',
            'single'            => true,
            'show_in_rest'      => true,
            'sanitize_callback' => 'sanitize_text_field',
            'auth_callback'     => function() {
                return current_user_can('edit_posts');
            },
        ));
        
        register_post_meta('event', 'event_price', array(
            'type'              => 'string',
            'single'            => true,
            'show_in_rest'      => true,
            'sanitize_callback' => 'sanitize_text_field',
            'auth_callback'     => function() {
                return current_user_can('edit_posts');
            },
        ));
        
        // Service meta fields
        register_post_meta('service', 'service_price', array(
            'type'              => 'string',
            'single'            => true,
            'show_in_rest'      => true,
            'sanitize_callback' => 'sanitize_text_field',
            'auth_callback'     => function() {
                return current_user_can('edit_posts');
            },
        ));
        
        register_post_meta('service', 'service_duration', array(
            'type'              => 'string',
            'single'            => true,
            'show_in_rest'      => true,
            'sanitize_callback' => 'sanitize_text_field',
            'auth_callback'     => function() {
                return current_user_can('edit_posts');
            },
        ));
        
        // Team member meta fields
        register_post_meta('team', 'team_position', array(
            'type'              => 'string',
            'single'            => true,
            'show_in_rest'      => true,
            'sanitize_callback' => 'sanitize_text_field',
            'auth_callback'     => function() {
                return current_user_can('edit_posts');
            },
        ));
        
        register_post_meta('team', 'team_email', array(
            'type'              => 'string',
            'single'            => true,
            'show_in_rest'      => true,
            'sanitize_callback' => 'sanitize_email',
            'auth_callback'     => function() {
                return current_user_can('edit_posts');
            },
        ));
        
        // Testimonial meta fields
        register_post_meta('testimonial', 'testimonial_author', array(
            'type'              => 'string',
            'single'            => true,
            'show_in_rest'      => true,
            'sanitize_callback' => 'sanitize_text_field',
            'auth_callback'     => function() {
                return current_user_can('edit_posts');
            },
        ));
        
        register_post_meta('testimonial', 'testimonial_company', array(
            'type'              => 'string',
            'single'            => true,
            'show_in_rest'      => true,
            'sanitize_callback' => 'sanitize_text_field',
            'auth_callback'     => function() {
                return current_user_can('edit_posts');
            },
        ));
        
        register_post_meta('testimonial', 'testimonial_rating', array(
            'type'              => 'integer',
            'single'            => true,
            'show_in_rest'      => true,
            'sanitize_callback' => 'absint',
            'auth_callback'     => function() {
                return current_user_can('edit_posts');
            },
        ));
        
        // Portfolio meta fields
        register_post_meta('portfolio', 'portfolio_client', array(
            'type'              => 'string',
            'single'            => true,
            'show_in_rest'      => true,
            'sanitize_callback' => 'sanitize_text_field',
            'auth_callback'     => function() {
                return current_user_can('edit_posts');
            },
        ));
        
        register_post_meta('portfolio', 'portfolio_url', array(
            'type'              => 'string',
            'single'            => true,
            'show_in_rest'      => true,
            'sanitize_callback' => 'esc_url_raw',
            'auth_callback'     => function() {
                return current_user_can('edit_posts');
            },
        ));
        
        // Partner meta fields
        register_post_meta('partner', 'partner_url', array(
            'type'              => 'string',
            'single'            => true,
            'show_in_rest'      => true,
            'sanitize_callback' => 'esc_url_raw',
            'auth_callback'     => function() {
                return current_user_can('edit_posts');
            },
        ));
    }
}

// Initialize custom post types
new AquaLuxe_Post_Types();