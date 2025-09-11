<?php
/**
 * Theme Setup Class
 *
 * @package AquaLuxe\Core
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Theme setup and initialization
 */
class AquaLuxe_Theme_Setup {
    
    /**
     * Constructor
     */
    public function __construct() {
        add_action('after_setup_theme', array($this, 'theme_support'));
        add_action('init', array($this, 'register_post_types'));
        add_action('init', array($this, 'register_taxonomies'));
        add_action('wp_head', array($this, 'add_meta_tags'));
        add_filter('body_class', array($this, 'body_classes'));
    }
    
    /**
     * Add theme support
     */
    public function theme_support() {
        // Essential WordPress features
        add_theme_support('post-thumbnails');
        add_theme_support('automatic-feed-links');
        add_theme_support('title-tag');
        add_theme_support('custom-logo', array(
            'height'      => 100,
            'width'       => 400,
            'flex-height' => true,
            'flex-width'  => true,
        ));
        
        // HTML5 support
        add_theme_support('html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script'
        ));
        
        // Post formats
        add_theme_support('post-formats', array(
            'aside',
            'gallery',
            'link',
            'image',
            'quote',
            'status',
            'video',
            'audio',
            'chat'
        ));
        
        // WooCommerce support
        add_theme_support('woocommerce');
        add_theme_support('wc-product-gallery-zoom');
        add_theme_support('wc-product-gallery-lightbox');
        add_theme_support('wc-product-gallery-slider');
        
        // Gutenberg support
        add_theme_support('wp-block-styles');
        add_theme_support('align-wide');
        add_theme_support('editor-styles');
        add_theme_support('responsive-embeds');
        
        // Custom background and header
        add_theme_support('custom-background');
        add_theme_support('custom-header', array(
            'default-image'      => '',
            'width'              => 1920,
            'height'             => 1080,
            'flex-height'        => true,
            'flex-width'         => true,
            'uploads'            => true,
            'random-default'     => false,
            'header-text'        => true,
            'default-text-color' => '',
            'wp-head-callback'   => '',
        ));
        
        // Image sizes
        add_image_size('aqualuxe-hero', 1920, 1080, true);
        add_image_size('aqualuxe-featured', 1200, 675, true);
        add_image_size('aqualuxe-thumbnail', 300, 300, true);
        add_image_size('aqualuxe-medium', 600, 400, true);
        add_image_size('aqualuxe-gallery', 400, 400, true);
        add_image_size('aqualuxe-product-single', 800, 800, true);
        add_image_size('aqualuxe-product-thumb', 150, 150, true);
    }
    
    /**
     * Register custom post types
     */
    public function register_post_types() {
        // Services post type
        register_post_type('aqualuxe_service', array(
            'labels' => array(
                'name'               => _x('Services', 'post type general name', 'aqualuxe'),
                'singular_name'      => _x('Service', 'post type singular name', 'aqualuxe'),
                'menu_name'          => _x('Services', 'admin menu', 'aqualuxe'),
                'name_admin_bar'     => _x('Service', 'add new on admin bar', 'aqualuxe'),
                'add_new'            => _x('Add New', 'service', 'aqualuxe'),
                'add_new_item'       => __('Add New Service', 'aqualuxe'),
                'new_item'           => __('New Service', 'aqualuxe'),
                'edit_item'          => __('Edit Service', 'aqualuxe'),
                'view_item'          => __('View Service', 'aqualuxe'),
                'all_items'          => __('All Services', 'aqualuxe'),
                'search_items'       => __('Search Services', 'aqualuxe'),
                'parent_item_colon'  => __('Parent Services:', 'aqualuxe'),
                'not_found'          => __('No services found.', 'aqualuxe'),
                'not_found_in_trash' => __('No services found in Trash.', 'aqualuxe')
            ),
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
            'supports'           => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
            'show_in_rest'       => true,
        ));
        
        // Events post type
        register_post_type('aqualuxe_event', array(
            'labels' => array(
                'name'               => _x('Events', 'post type general name', 'aqualuxe'),
                'singular_name'      => _x('Event', 'post type singular name', 'aqualuxe'),
                'menu_name'          => _x('Events', 'admin menu', 'aqualuxe'),
                'name_admin_bar'     => _x('Event', 'add new on admin bar', 'aqualuxe'),
                'add_new'            => _x('Add New', 'event', 'aqualuxe'),
                'add_new_item'       => __('Add New Event', 'aqualuxe'),
                'new_item'           => __('New Event', 'aqualuxe'),
                'edit_item'          => __('Edit Event', 'aqualuxe'),
                'view_item'          => __('View Event', 'aqualuxe'),
                'all_items'          => __('All Events', 'aqualuxe'),
                'search_items'       => __('Search Events', 'aqualuxe'),
                'parent_item_colon'  => __('Parent Events:', 'aqualuxe'),
                'not_found'          => __('No events found.', 'aqualuxe'),
                'not_found_in_trash' => __('No events found in Trash.', 'aqualuxe')
            ),
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array('slug' => 'events'),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => 25,
            'menu_icon'          => 'dashicons-calendar-alt',
            'supports'           => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
            'show_in_rest'       => true,
        ));
        
        // Testimonials post type
        register_post_type('aqualuxe_testimonial', array(
            'labels' => array(
                'name'               => _x('Testimonials', 'post type general name', 'aqualuxe'),
                'singular_name'      => _x('Testimonial', 'post type singular name', 'aqualuxe'),
                'menu_name'          => _x('Testimonials', 'admin menu', 'aqualuxe'),
                'name_admin_bar'     => _x('Testimonial', 'add new on admin bar', 'aqualuxe'),
                'add_new'            => _x('Add New', 'testimonial', 'aqualuxe'),
                'add_new_item'       => __('Add New Testimonial', 'aqualuxe'),
                'new_item'           => __('New Testimonial', 'aqualuxe'),
                'edit_item'          => __('Edit Testimonial', 'aqualuxe'),
                'view_item'          => __('View Testimonial', 'aqualuxe'),
                'all_items'          => __('All Testimonials', 'aqualuxe'),
                'search_items'       => __('Search Testimonials', 'aqualuxe'),
                'parent_item_colon'  => __('Parent Testimonials:', 'aqualuxe'),
                'not_found'          => __('No testimonials found.', 'aqualuxe'),
                'not_found_in_trash' => __('No testimonials found in Trash.', 'aqualuxe')
            ),
            'public'             => false,
            'publicly_queryable' => false,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'capability_type'    => 'post',
            'has_archive'        => false,
            'hierarchical'       => false,
            'menu_position'      => 30,
            'menu_icon'          => 'dashicons-format-quote',
            'supports'           => array('title', 'editor', 'thumbnail', 'custom-fields'),
            'show_in_rest'       => true,
        ));
    }
    
    /**
     * Register custom taxonomies
     */
    public function register_taxonomies() {
        // Service categories
        register_taxonomy('aqualuxe_service_category', array('aqualuxe_service'), array(
            'hierarchical'      => true,
            'labels'            => array(
                'name'              => _x('Service Categories', 'taxonomy general name', 'aqualuxe'),
                'singular_name'     => _x('Service Category', 'taxonomy singular name', 'aqualuxe'),
                'search_items'      => __('Search Service Categories', 'aqualuxe'),
                'all_items'         => __('All Service Categories', 'aqualuxe'),
                'parent_item'       => __('Parent Service Category', 'aqualuxe'),
                'parent_item_colon' => __('Parent Service Category:', 'aqualuxe'),
                'edit_item'         => __('Edit Service Category', 'aqualuxe'),
                'update_item'       => __('Update Service Category', 'aqualuxe'),
                'add_new_item'      => __('Add New Service Category', 'aqualuxe'),
                'new_item_name'     => __('New Service Category Name', 'aqualuxe'),
                'menu_name'         => __('Service Categories', 'aqualuxe'),
            ),
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => 'service-category'),
            'show_in_rest'      => true,
        ));
        
        // Event categories
        register_taxonomy('aqualuxe_event_category', array('aqualuxe_event'), array(
            'hierarchical'      => true,
            'labels'            => array(
                'name'              => _x('Event Categories', 'taxonomy general name', 'aqualuxe'),
                'singular_name'     => _x('Event Category', 'taxonomy singular name', 'aqualuxe'),
                'search_items'      => __('Search Event Categories', 'aqualuxe'),
                'all_items'         => __('All Event Categories', 'aqualuxe'),
                'parent_item'       => __('Parent Event Category', 'aqualuxe'),
                'parent_item_colon' => __('Parent Event Category:', 'aqualuxe'),
                'edit_item'         => __('Edit Event Category', 'aqualuxe'),
                'update_item'       => __('Update Event Category', 'aqualuxe'),
                'add_new_item'      => __('Add New Event Category', 'aqualuxe'),
                'new_item_name'     => __('New Event Category Name', 'aqualuxe'),
                'menu_name'         => __('Event Categories', 'aqualuxe'),
            ),
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => 'event-category'),
            'show_in_rest'      => true,
        ));
        
        // Product tags for enhanced WooCommerce functionality
        if (aqualuxe_is_woocommerce_active()) {
            register_taxonomy('aqualuxe_product_feature', array('product'), array(
                'hierarchical'      => false,
                'labels'            => array(
                    'name'                       => _x('Product Features', 'taxonomy general name', 'aqualuxe'),
                    'singular_name'              => _x('Product Feature', 'taxonomy singular name', 'aqualuxe'),
                    'search_items'               => __('Search Product Features', 'aqualuxe'),
                    'popular_items'              => __('Popular Product Features', 'aqualuxe'),
                    'all_items'                  => __('All Product Features', 'aqualuxe'),
                    'edit_item'                  => __('Edit Product Feature', 'aqualuxe'),
                    'update_item'                => __('Update Product Feature', 'aqualuxe'),
                    'add_new_item'               => __('Add New Product Feature', 'aqualuxe'),
                    'new_item_name'              => __('New Product Feature Name', 'aqualuxe'),
                    'separate_items_with_commas' => __('Separate features with commas', 'aqualuxe'),
                    'add_or_remove_items'        => __('Add or remove features', 'aqualuxe'),
                    'choose_from_most_used'      => __('Choose from the most used features', 'aqualuxe'),
                    'not_found'                  => __('No features found.', 'aqualuxe'),
                    'menu_name'                  => __('Product Features', 'aqualuxe'),
                ),
                'show_ui'           => true,
                'show_admin_column' => true,
                'query_var'         => true,
                'rewrite'           => array('slug' => 'product-feature'),
                'show_in_rest'      => true,
            ));
        }
    }
    
    /**
     * Add meta tags to head
     */
    public function add_meta_tags() {
        // Viewport meta tag
        echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">' . "\n";
        
        // Theme color for mobile browsers
        echo '<meta name="theme-color" content="' . esc_attr(get_theme_mod('primary_color', '#0ea5e9')) . '">' . "\n";
        
        // Open Graph tags
        if (is_single() || is_page()) {
            global $post;
            $og_title = get_the_title();
            $og_description = get_the_excerpt() ?: get_bloginfo('description');
            $og_url = get_permalink();
            $og_image = get_the_post_thumbnail_url($post->ID, 'aqualuxe-featured');
            
            echo '<meta property="og:title" content="' . esc_attr($og_title) . '">' . "\n";
            echo '<meta property="og:description" content="' . esc_attr($og_description) . '">' . "\n";
            echo '<meta property="og:url" content="' . esc_url($og_url) . '">' . "\n";
            echo '<meta property="og:type" content="website">' . "\n";
            
            if ($og_image) {
                echo '<meta property="og:image" content="' . esc_url($og_image) . '">' . "\n";
            }
        }
        
        // Schema.org markup
        $this->add_schema_markup();
    }
    
    /**
     * Add schema.org markup
     */
    private function add_schema_markup() {
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => get_bloginfo('name'),
            'url' => home_url(),
            'description' => get_bloginfo('description'),
        );
        
        // Add logo if available
        $custom_logo_id = get_theme_mod('custom_logo');
        if ($custom_logo_id) {
            $logo_url = wp_get_attachment_image_url($custom_logo_id, 'full');
            $schema['logo'] = $logo_url;
        }
        
        echo '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>' . "\n";
    }
    
    /**
     * Add custom body classes
     */
    public function body_classes($classes) {
        // Dark mode class
        if (get_theme_mod('enable_dark_mode', false)) {
            $classes[] = 'has-dark-mode';
        }
        
        // WooCommerce class
        if (aqualuxe_is_woocommerce_active()) {
            $classes[] = 'has-woocommerce';
        }
        
        // RTL class
        if (is_rtl()) {
            $classes[] = 'rtl';
        }
        
        // Mobile detection
        if (wp_is_mobile()) {
            $classes[] = 'is-mobile';
        }
        
        return $classes;
    }
}

// Initialize theme setup
new AquaLuxe_Theme_Setup();