<?php
/**
 * Theme Setup Class
 * 
 * Handles theme setup and configuration
 * 
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit;
}

class AquaLuxe_Theme_Setup {
    
    /**
     * Constructor
     */
    public function __construct() {
        add_action('after_setup_theme', [$this, 'setup']);
        add_action('init', [$this, 'register_post_types']);
        add_action('init', [$this, 'register_taxonomies']);
        add_action('widgets_init', [$this, 'register_widget_areas']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_block_styles']);
        add_action('wp_enqueue_scripts', [$this, 'conditional_assets']);
        add_filter('excerpt_length', [$this, 'excerpt_length']);
        add_filter('excerpt_more', [$this, 'excerpt_more']);
    }
    
    /**
     * Theme setup
     */
    public function setup() {
        // Make theme available for translation
        load_theme_textdomain('aqualuxe', AQUALUXE_THEME_DIR . '/languages');
        
        // Add default posts and comments RSS feed links to head
        add_theme_support('automatic-feed-links');
        
        // Let WordPress manage the document title
        add_theme_support('title-tag');
        
        // Enable support for Post Thumbnails on posts and pages
        add_theme_support('post-thumbnails');
        
        // Switch default core markup for search form, comment form, and comments
        add_theme_support('html5', [
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script'
        ]);
        
        // Add theme support for selective refresh for widgets
        add_theme_support('customize-selective-refresh-widgets');
        
        // Add support for Block Styles
        add_theme_support('wp-block-styles');
        
        // Add support for full and wide align images
        add_theme_support('align-wide');
        
        // Add support for editor styles
        add_theme_support('editor-styles');
        
        // Enqueue editor styles
        add_editor_style('assets/dist/css/editor-style.css');
        
        // Add support for responsive embedded content
        add_theme_support('responsive-embeds');
        
        // Add support for custom line height controls
        add_theme_support('custom-line-height');
        
        // Add support for custom units
        add_theme_support('custom-units');
        
        // Add support for custom spacing
        add_theme_support('custom-spacing');
        
        // Add support for custom logo
        add_theme_support('custom-logo', [
            'height'      => 250,
            'width'       => 250,
            'flex-width'  => true,
            'flex-height' => true,
        ]);
        
        // Add support for post formats
        add_theme_support('post-formats', [
            'aside',
            'image',
            'video',
            'quote',
            'link',
            'gallery',
            'audio'
        ]);
        
        // Add support for custom header
        add_theme_support('custom-header', [
            'default-image' => '',
            'default-text-color' => '000',
            'width' => 1200,
            'height' => 280,
            'flex-height' => true,
            'flex-width' => true,
            'uploads' => true,
            'header-text' => true
        ]);
        
        // Add support for custom background
        add_theme_support('custom-background', [
            'default-color' => 'ffffff',
            'default-image' => '',
            'default-repeat' => 'no-repeat',
            'default-position-x' => 'center',
            'default-position-y' => 'center',
            'default-size' => 'cover',
            'default-attachment' => 'fixed'
        ]);
        
        // WooCommerce support
        add_theme_support('woocommerce');
        add_theme_support('wc-product-gallery-zoom');
        add_theme_support('wc-product-gallery-lightbox');
        add_theme_support('wc-product-gallery-slider');
        
        // Register navigation menus
        register_nav_menus([
            'primary' => esc_html__('Primary Menu', 'aqualuxe'),
            'footer' => esc_html__('Footer Menu', 'aqualuxe'),
            'mobile' => esc_html__('Mobile Menu', 'aqualuxe'),
            'top' => esc_html__('Top Bar Menu', 'aqualuxe'),
        ]);
        
        // Add custom image sizes
        add_image_size('aqualuxe-hero', 1920, 1080, true);
        add_image_size('aqualuxe-featured', 800, 600, true);
        add_image_size('aqualuxe-product', 600, 600, true);
        add_image_size('aqualuxe-product-thumb', 300, 300, true);
        add_image_size('aqualuxe-blog', 800, 400, true);
        add_image_size('aqualuxe-blog-thumb', 400, 250, true);
        add_image_size('aqualuxe-gallery', 400, 400, true);
        add_image_size('aqualuxe-testimonial', 100, 100, true);
        
        // Set content width
        $GLOBALS['content_width'] = apply_filters('aqualuxe_content_width', 1200);
    }
    
    /**
     * Enqueue block editor styles
     */
    public function enqueue_block_styles() {
        wp_enqueue_style(
            'aqualuxe-block-styles',
            get_template_directory_uri() . '/assets/dist/css/blocks.css',
            [],
            AQUALUXE_VERSION
        );
    }
    
    /**
     * Custom excerpt length
     */
    public function excerpt_length($length) {
        return 25;
    }
    
    /**
     * Custom excerpt more
     */
    public function excerpt_more($more) {
        return '&hellip;';
    }
    
    /**
     * Register custom post types
     */
    public function register_post_types() {
        // Services
        register_post_type('aqualuxe_service', [
            'labels' => [
                'name' => esc_html__('Services', 'aqualuxe'),
                'singular_name' => esc_html__('Service', 'aqualuxe'),
                'add_new' => esc_html__('Add New Service', 'aqualuxe'),
                'add_new_item' => esc_html__('Add New Service', 'aqualuxe'),
                'edit_item' => esc_html__('Edit Service', 'aqualuxe'),
                'new_item' => esc_html__('New Service', 'aqualuxe'),
                'view_item' => esc_html__('View Service', 'aqualuxe'),
                'search_items' => esc_html__('Search Services', 'aqualuxe'),
                'not_found' => esc_html__('No services found', 'aqualuxe'),
                'not_found_in_trash' => esc_html__('No services found in trash', 'aqualuxe'),
            ],
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_rest' => true,
            'query_var' => true,
            'rewrite' => ['slug' => 'services'],
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => false,
            'menu_position' => 20,
            'menu_icon' => 'dashicons-admin-tools',
            'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'page-attributes']
        ]);
        
        // Portfolio/Gallery
        register_post_type('aqualuxe_portfolio', [
            'labels' => [
                'name' => esc_html__('Portfolio', 'aqualuxe'),
                'singular_name' => esc_html__('Portfolio Item', 'aqualuxe'),
                'add_new' => esc_html__('Add New Item', 'aqualuxe'),
                'add_new_item' => esc_html__('Add New Portfolio Item', 'aqualuxe'),
                'edit_item' => esc_html__('Edit Portfolio Item', 'aqualuxe'),
                'new_item' => esc_html__('New Portfolio Item', 'aqualuxe'),
                'view_item' => esc_html__('View Portfolio Item', 'aqualuxe'),
                'search_items' => esc_html__('Search Portfolio', 'aqualuxe'),
                'not_found' => esc_html__('No portfolio items found', 'aqualuxe'),
                'not_found_in_trash' => esc_html__('No portfolio items found in trash', 'aqualuxe'),
            ],
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_rest' => true,
            'query_var' => true,
            'rewrite' => ['slug' => 'portfolio'],
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => false,
            'menu_position' => 21,
            'menu_icon' => 'dashicons-portfolio',
            'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'page-attributes']
        ]);
        
        // Testimonials
        register_post_type('aqualuxe_testimonial', [
            'labels' => [
                'name' => esc_html__('Testimonials', 'aqualuxe'),
                'singular_name' => esc_html__('Testimonial', 'aqualuxe'),
                'add_new' => esc_html__('Add New Testimonial', 'aqualuxe'),
                'add_new_item' => esc_html__('Add New Testimonial', 'aqualuxe'),
                'edit_item' => esc_html__('Edit Testimonial', 'aqualuxe'),
                'new_item' => esc_html__('New Testimonial', 'aqualuxe'),
                'view_item' => esc_html__('View Testimonial', 'aqualuxe'),
                'search_items' => esc_html__('Search Testimonials', 'aqualuxe'),
                'not_found' => esc_html__('No testimonials found', 'aqualuxe'),
                'not_found_in_trash' => esc_html__('No testimonials found in trash', 'aqualuxe'),
            ],
            'public' => false,
            'publicly_queryable' => false,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_rest' => true,
            'query_var' => false,
            'capability_type' => 'post',
            'has_archive' => false,
            'hierarchical' => false,
            'menu_position' => 22,
            'menu_icon' => 'dashicons-format-quote',
            'supports' => ['title', 'editor', 'thumbnail', 'custom-fields', 'page-attributes']
        ]);
        
        // Team Members
        register_post_type('aqualuxe_team', [
            'labels' => [
                'name' => esc_html__('Team Members', 'aqualuxe'),
                'singular_name' => esc_html__('Team Member', 'aqualuxe'),
                'add_new' => esc_html__('Add New Member', 'aqualuxe'),
                'add_new_item' => esc_html__('Add New Team Member', 'aqualuxe'),
                'edit_item' => esc_html__('Edit Team Member', 'aqualuxe'),
                'new_item' => esc_html__('New Team Member', 'aqualuxe'),
                'view_item' => esc_html__('View Team Member', 'aqualuxe'),
                'search_items' => esc_html__('Search Team', 'aqualuxe'),
                'not_found' => esc_html__('No team members found', 'aqualuxe'),
                'not_found_in_trash' => esc_html__('No team members found in trash', 'aqualuxe'),
            ],
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_rest' => true,
            'query_var' => true,
            'rewrite' => ['slug' => 'team'],
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => false,
            'menu_position' => 23,
            'menu_icon' => 'dashicons-groups',
            'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'page-attributes']
        ]);
    }
    
    /**
     * Register custom taxonomies
     */
    public function register_taxonomies() {
        // Service categories
        register_taxonomy('service_category', 'aqualuxe_service', [
            'labels' => [
                'name' => esc_html__('Service Categories', 'aqualuxe'),
                'singular_name' => esc_html__('Service Category', 'aqualuxe'),
                'search_items' => esc_html__('Search Service Categories', 'aqualuxe'),
                'all_items' => esc_html__('All Service Categories', 'aqualuxe'),
                'edit_item' => esc_html__('Edit Service Category', 'aqualuxe'),
                'update_item' => esc_html__('Update Service Category', 'aqualuxe'),
                'add_new_item' => esc_html__('Add New Service Category', 'aqualuxe'),
                'new_item_name' => esc_html__('New Service Category Name', 'aqualuxe'),
            ],
            'hierarchical' => true,
            'public' => true,
            'show_ui' => true,
            'show_in_rest' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => ['slug' => 'service-category'],
        ]);
        
        // Portfolio categories
        register_taxonomy('portfolio_category', 'aqualuxe_portfolio', [
            'labels' => [
                'name' => esc_html__('Portfolio Categories', 'aqualuxe'),
                'singular_name' => esc_html__('Portfolio Category', 'aqualuxe'),
                'search_items' => esc_html__('Search Portfolio Categories', 'aqualuxe'),
                'all_items' => esc_html__('All Portfolio Categories', 'aqualuxe'),
                'edit_item' => esc_html__('Edit Portfolio Category', 'aqualuxe'),
                'update_item' => esc_html__('Update Portfolio Category', 'aqualuxe'),
                'add_new_item' => esc_html__('Add New Portfolio Category', 'aqualuxe'),
                'new_item_name' => esc_html__('New Portfolio Category Name', 'aqualuxe'),
            ],
            'hierarchical' => true,
            'public' => true,
            'show_ui' => true,
            'show_in_rest' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => ['slug' => 'portfolio-category'],
        ]);
        
        // Portfolio tags
        register_taxonomy('portfolio_tag', 'aqualuxe_portfolio', [
            'labels' => [
                'name' => esc_html__('Portfolio Tags', 'aqualuxe'),
                'singular_name' => esc_html__('Portfolio Tag', 'aqualuxe'),
                'search_items' => esc_html__('Search Portfolio Tags', 'aqualuxe'),
                'all_items' => esc_html__('All Portfolio Tags', 'aqualuxe'),
                'edit_item' => esc_html__('Edit Portfolio Tag', 'aqualuxe'),
                'update_item' => esc_html__('Update Portfolio Tag', 'aqualuxe'),
                'add_new_item' => esc_html__('Add New Portfolio Tag', 'aqualuxe'),
                'new_item_name' => esc_html__('New Portfolio Tag Name', 'aqualuxe'),
            ],
            'hierarchical' => false,
            'public' => true,
            'show_ui' => true,
            'show_in_rest' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => ['slug' => 'portfolio-tag'],
        ]);
    }
    
    /**
     * Register widget areas
     */
    public function register_widget_areas() {
        // Primary sidebar
        register_sidebar([
            'name'          => esc_html__('Primary Sidebar', 'aqualuxe'),
            'id'            => 'sidebar-primary',
            'description'   => esc_html__('Main sidebar area', 'aqualuxe'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        ]);
        
        // Footer widget areas
        for ($i = 1; $i <= 4; $i++) {
            register_sidebar([
                'name'          => sprintf(esc_html__('Footer Widget Area %d', 'aqualuxe'), $i),
                'id'            => 'footer-' . $i,
                'description'   => sprintf(esc_html__('Footer widget area %d', 'aqualuxe'), $i),
                'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<h4 class="footer-widget-title">',
                'after_title'   => '</h4>',
            ]);
        }
        
        // Shop sidebar
        if (aqualuxe_is_woocommerce_active()) {
            register_sidebar([
                'name'          => esc_html__('Shop Sidebar', 'aqualuxe'),
                'id'            => 'sidebar-shop',
                'description'   => esc_html__('Sidebar for WooCommerce pages', 'aqualuxe'),
                'before_widget' => '<section id="%1$s" class="widget %2$s">',
                'after_widget'  => '</section>',
                'before_title'  => '<h3 class="widget-title">',
                'after_title'   => '</h3>',
            ]);
        }
    }
    
    /**
     * Conditional asset loading
     */
    public function conditional_assets() {
        // Load specific scripts/styles based on page context
        if (is_front_page()) {
            // Check if homepage script exists before enqueueing
            $homepage_script = AQUALUXE_ASSETS_URI . '/js/modules/homepage.js';
            if (file_exists(AQUALUXE_THEME_DIR . '/assets/dist/js/modules/homepage.js')) {
                wp_enqueue_script('aqualuxe-homepage', $homepage_script, ['jquery'], AQUALUXE_VERSION, true);
            }
        }
        
        if (is_page_template('templates/page-contact.php')) {
            // Check if contact script exists before enqueueing  
            $contact_script = AQUALUXE_ASSETS_URI . '/js/modules/contact.js';
            if (file_exists(AQUALUXE_THEME_DIR . '/assets/dist/js/modules/contact.js')) {
                wp_enqueue_script('aqualuxe-contact', $contact_script, ['jquery'], AQUALUXE_VERSION, true);
            }
        }
    }
}