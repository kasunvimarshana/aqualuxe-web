<?php
/**
 * Main Theme Class
 * 
 * @package AquaLuxe
 * @version 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class AquaLuxe_Theme {
    
    /**
     * Constructor
     */
    public function __construct() {
        add_action('after_setup_theme', array($this, 'theme_setup'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('init', array($this, 'init'));
        add_action('widgets_init', array($this, 'register_sidebars'));
        add_filter('excerpt_more', array($this, 'custom_excerpt_more'));
        add_filter('excerpt_length', array($this, 'custom_excerpt_length'));
        
        // WooCommerce integration
        add_action('after_setup_theme', array($this, 'woocommerce_setup'));
        
        // Custom post types and taxonomies
        add_action('init', array($this, 'register_custom_post_types'));
        add_action('init', array($this, 'register_custom_taxonomies'));
        
        // Custom image sizes
        add_action('after_setup_theme', array($this, 'register_image_sizes'));
        
        // Admin customizations
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'admin_init'));
    }
    
    /**
     * Theme setup
     */
    public function theme_setup() {
        // Make theme available for translation
        load_theme_textdomain('aqualuxe', get_template_directory() . '/languages');
        
        // Add default posts and comments RSS feed links to head
        add_theme_support('automatic-feed-links');
        
        // Let WordPress manage the document title
        add_theme_support('title-tag');
        
        // Enable support for Post Thumbnails on posts and pages
        add_theme_support('post-thumbnails');
        
        // Enable HTML5 markup
        add_theme_support('html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script',
        ));
        
        // Add theme support for selective refresh for widgets
        add_theme_support('customize-selective-refresh-widgets');
        
        // Add support for core custom logo
        add_theme_support('custom-logo', array(
            'height'      => 250,
            'width'       => 250,
            'flex-width'  => true,
            'flex-height' => true,
        ));
        
        // Add support for custom header
        add_theme_support('custom-header', array(
            'default-image'      => '',
            'width'              => 1920,
            'height'             => 1080,
            'flex-height'        => true,
            'flex-width'         => true,
            'uploads'            => true,
            'random-default'     => false,
            'header-text'        => true,
            'default-text-color' => '000000',
            'wp-head-callback'   => '',
            'admin-head-callback' => '',
            'admin-preview-callback' => '',
        ));
        
        // Add support for custom background
        add_theme_support('custom-background', array(
            'default-color' => 'ffffff',
            'default-image' => '',
        ));
        
        // Add support for responsive embeds
        add_theme_support('responsive-embeds');
        
        // Add support for editor styles
        add_theme_support('editor-styles');
        
        // Add support for dark editor style
        add_theme_support('dark-editor-style');
        
        // Add support for wide alignment
        add_theme_support('align-wide');
        
        // Add support for block templates
        add_theme_support('block-templates');
        
        // Add support for block template parts
        add_theme_support('block-template-parts');
        
        // Register navigation menus
        register_nav_menus(array(
            'primary' => esc_html__('Primary Menu', 'aqualuxe'),
            'footer'  => esc_html__('Footer Menu', 'aqualuxe'),
            'mobile'  => esc_html__('Mobile Menu', 'aqualuxe'),
            'top'     => esc_html__('Top Bar Menu', 'aqualuxe'),
        ));
    }
    
    /**
     * WooCommerce setup
     */
    public function woocommerce_setup() {
        if (class_exists('WooCommerce')) {
            add_theme_support('woocommerce');
            add_theme_support('wc-product-gallery-zoom');
            add_theme_support('wc-product-gallery-lightbox');
            add_theme_support('wc-product-gallery-slider');
        }
    }
    
    /**
     * Enqueue scripts and styles
     */
    public function enqueue_scripts() {
        // This will be handled by AquaLuxe_Assets class
        // Keeping here for reference
    }
    
    /**
     * Initialize theme
     */
    public function init() {
        // Load custom functions
        $this->load_custom_functions();
        
        // Initialize multilingual support
        $this->init_multilingual();
        
        // Initialize dark mode
        $this->init_dark_mode();
    }
    
    /**
     * Register sidebars
     */
    public function register_sidebars() {
        register_sidebar(array(
            'name'          => esc_html__('Primary Sidebar', 'aqualuxe'),
            'id'            => 'sidebar-primary',
            'description'   => esc_html__('Main sidebar for blog and pages.', 'aqualuxe'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        ));
        
        register_sidebar(array(
            'name'          => esc_html__('Footer Sidebar', 'aqualuxe'),
            'id'            => 'sidebar-footer',
            'description'   => esc_html__('Sidebar for footer area.', 'aqualuxe'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        ));
        
        if (class_exists('WooCommerce')) {
            register_sidebar(array(
                'name'          => esc_html__('Shop Sidebar', 'aqualuxe'),
                'id'            => 'sidebar-shop',
                'description'   => esc_html__('Sidebar for WooCommerce shop and product pages.', 'aqualuxe'),
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<h3 class="widget-title">',
                'after_title'   => '</h3>',
            ));
        }
    }
    
    /**
     * Custom excerpt more
     */
    public function custom_excerpt_more($more) {
        return '...';
    }
    
    /**
     * Custom excerpt length
     */
    public function custom_excerpt_length($length) {
        return 30;
    }
    
    /**
     * Register custom post types
     */
    public function register_custom_post_types() {
        // Services post type
        register_post_type('aqualuxe_service', array(
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
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array('slug' => 'services'),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'menu_icon'          => 'dashicons-admin-tools',
            'supports'           => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
            'show_in_rest'       => true,
        ));
        
        // Events post type
        register_post_type('aqualuxe_event', array(
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
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array('slug' => 'events'),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'menu_icon'          => 'dashicons-calendar-alt',
            'supports'           => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
            'show_in_rest'       => true,
        ));
        
        // Testimonials post type
        register_post_type('aqualuxe_testimonial', array(
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
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array('slug' => 'testimonials'),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'menu_icon'          => 'dashicons-testimonial',
            'supports'           => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
            'show_in_rest'       => true,
        ));
    }
    
    /**
     * Register custom taxonomies
     */
    public function register_custom_taxonomies() {
        // Service categories
        register_taxonomy('service_category', 'aqualuxe_service', array(
            'hierarchical'      => true,
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
                'menu_name'         => esc_html__('Service Categories', 'aqualuxe'),
            ),
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => 'service-category'),
            'show_in_rest'      => true,
        ));
        
        // Event categories
        register_taxonomy('event_category', 'aqualuxe_event', array(
            'hierarchical'      => true,
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
                'menu_name'         => esc_html__('Event Categories', 'aqualuxe'),
            ),
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => 'event-category'),
            'show_in_rest'      => true,
        ));
    }
    
    /**
     * Register custom image sizes
     */
    public function register_image_sizes() {
        add_image_size('aqualuxe-hero', 1920, 1080, true);
        add_image_size('aqualuxe-featured', 800, 600, true);
        add_image_size('aqualuxe-thumbnail', 400, 300, true);
        add_image_size('aqualuxe-gallery', 600, 400, true);
        add_image_size('aqualuxe-product-large', 800, 800, true);
        add_image_size('aqualuxe-product-medium', 400, 400, true);
        add_image_size('aqualuxe-product-small', 200, 200, true);
    }
    
    /**
     * Load custom functions
     */
    private function load_custom_functions() {
        $files = array(
            'template-functions.php',
            'template-hooks.php',
            'woocommerce-functions.php',
            'customizer-functions.php',
            'admin-functions.php',
        );
        
        foreach ($files as $file) {
            $path = AQUALUXE_THEME_INC . '/' . $file;
            if (file_exists($path)) {
                require_once $path;
            }
        }
    }
    
    /**
     * Initialize multilingual support
     */
    private function init_multilingual() {
        if (get_theme_mod('aqualuxe_enable_multilingual', true)) {
            // Initialize multilingual functionality
            // This can be extended with WPML, Polylang, or custom implementation
        }
    }
    
    /**
     * Initialize dark mode
     */
    private function init_dark_mode() {
        if (get_theme_mod('aqualuxe_enable_dark_mode', true)) {
            // Initialize dark mode functionality
            add_action('wp_footer', array($this, 'add_dark_mode_script'));
        }
    }
    
    /**
     * Add dark mode script
     */
    public function add_dark_mode_script() {
        ?>
        <script>
        (function() {
            var darkMode = localStorage.getItem('aqualuxe-dark-mode');
            if (darkMode === 'enabled') {
                document.documentElement.classList.add('dark-mode');
            }
        })();
        </script>
        <?php
    }
    
    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_theme_page(
            esc_html__('AquaLuxe Options', 'aqualuxe'),
            esc_html__('AquaLuxe Options', 'aqualuxe'),
            'manage_options',
            'aqualuxe-options',
            array($this, 'admin_page_callback')
        );
    }
    
    /**
     * Admin page callback
     */
    public function admin_page_callback() {
        include_once AQUALUXE_THEME_INC . '/admin-page.php';
    }
    
    /**
     * Admin init
     */
    public function admin_init() {
        // Register admin settings
        register_setting('aqualuxe_options', 'aqualuxe_options');
    }
}
