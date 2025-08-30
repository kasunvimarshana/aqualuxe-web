<?php
/**
 * AquaLuxe functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Define theme constants
define('AQUALUXE_VERSION', '1.0.0');
define('AQUALUXE_DIR', trailingslashit(get_template_directory()));
define('AQUALUXE_URI', trailingslashit(get_template_directory_uri()));
define('AQUALUXE_ASSETS_URI', trailingslashit(AQUALUXE_URI . 'assets'));
define('AQUALUXE_INC_DIR', trailingslashit(AQUALUXE_DIR . 'inc'));
define('AQUALUXE_TEMPLATES_DIR', trailingslashit(AQUALUXE_DIR . 'templates'));

/**
 * AquaLuxe Theme Class
 * 
 * Main theme class that initializes the theme and its components
 */
final class AquaLuxe {
    /**
     * Instance of this class
     * 
     * @var AquaLuxe
     */
    private static $instance = null;

    /**
     * Get the singleton instance of this class
     * 
     * @return AquaLuxe
     */
    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        // Load required files
        $this->load_dependencies();

        // Setup theme
        add_action('after_setup_theme', [$this, 'setup']);

        // Register assets
        add_action('wp_enqueue_scripts', [$this, 'register_assets']);
        add_action('admin_enqueue_scripts', [$this, 'register_admin_assets']);

        // Register menus
        add_action('init', [$this, 'register_menus']);

        // Register sidebars
        add_action('widgets_init', [$this, 'register_sidebars']);

        // Add theme support
        add_action('after_setup_theme', [$this, 'add_theme_support']);

        // WooCommerce support
        add_action('after_setup_theme', [$this, 'woocommerce_support']);
        
        // Register custom post types and taxonomies
        add_action('init', [$this, 'register_post_types']);
        add_action('init', [$this, 'register_taxonomies']);
    }

    /**
     * Load dependencies
     */
    private function load_dependencies() {
        // Include helper functions
        require_once AQUALUXE_INC_DIR . 'helpers/template-functions.php';
        require_once AQUALUXE_INC_DIR . 'helpers/woocommerce-functions.php';
        
        // Include classes
        require_once AQUALUXE_INC_DIR . 'classes/class-aqualuxe-customizer.php';
        require_once AQUALUXE_INC_DIR . 'classes/class-aqualuxe-walker-nav-menu.php';
        require_once AQUALUXE_INC_DIR . 'classes/class-aqualuxe-breadcrumb.php';
        require_once AQUALUXE_INC_DIR . 'classes/class-aqualuxe-assets.php';
        require_once AQUALUXE_INC_DIR . 'classes/class-aqualuxe-schema.php';
        require_once AQUALUXE_INC_DIR . 'classes/class-aqualuxe-social-meta.php';
        
        // Include custom post types
        require_once AQUALUXE_INC_DIR . 'classes/post-types/class-aqualuxe-service.php';
        require_once AQUALUXE_INC_DIR . 'classes/post-types/class-aqualuxe-event.php';
        require_once AQUALUXE_INC_DIR . 'classes/post-types/class-aqualuxe-testimonial.php';
        
        // Include WooCommerce customizations
        if (class_exists('WooCommerce')) {
            require_once AQUALUXE_INC_DIR . 'classes/class-aqualuxe-woocommerce.php';
        }
    }

    /**
     * Setup theme
     */
    public function setup() {
        // Load text domain
        load_theme_textdomain('aqualuxe', AQUALUXE_DIR . 'languages');
        
        // Add default posts and comments RSS feed links to head
        add_theme_support('automatic-feed-links');
        
        // Let WordPress manage the document title
        add_theme_support('title-tag');
        
        // Enable support for Post Thumbnails on posts and pages
        add_theme_support('post-thumbnails');
        
        // Add image sizes
        add_image_size('aqualuxe-featured', 1200, 800, true);
        add_image_size('aqualuxe-card', 600, 400, true);
        add_image_size('aqualuxe-thumbnail', 300, 300, true);
        add_image_size('aqualuxe-square', 800, 800, true);
        
        // Switch default core markup to output valid HTML5
        add_theme_support('html5', [
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script',
        ]);
        
        // Add theme support for selective refresh for widgets
        add_theme_support('customize-selective-refresh-widgets');
        
        // Add support for editor styles
        add_theme_support('editor-styles');
        add_editor_style('assets/css/editor-style.css');
        
        // Add support for responsive embeds
        add_theme_support('responsive-embeds');
        
        // Add support for custom line height controls
        add_theme_support('custom-line-height');
        
        // Add support for experimental link color control
        add_theme_support('experimental-link-color');
        
        // Add support for custom units
        add_theme_support('custom-units');
        
        // Add support for custom spacing
        add_theme_support('custom-spacing');
        
        // Add support for block styles
        add_theme_support('wp-block-styles');
        
        // Add support for full and wide align images
        add_theme_support('align-wide');
    }

    /**
     * Register assets
     */
    public function register_assets() {
        // Register styles
        wp_register_style(
            'aqualuxe-main',
            AQUALUXE_ASSETS_URI . 'css/main.css',
            [],
            AQUALUXE_VERSION
        );
        
        // Enqueue styles
        wp_enqueue_style('aqualuxe-main');
        
        // Register scripts
        wp_register_script(
            'aqualuxe-main',
            AQUALUXE_ASSETS_URI . 'js/main.js',
            [],
            AQUALUXE_VERSION,
            true
        );
        
        // Enqueue scripts
        wp_enqueue_script('aqualuxe-main');
        
        // Localize script
        wp_localize_script('aqualuxe-main', 'aqualuxeData', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe-nonce'),
            'homeUrl' => home_url('/'),
            'isLoggedIn' => is_user_logged_in(),
            'currency' => get_woocommerce_currency_symbol(),
        ]);
        
        // Enqueue comment reply script if needed
        if (is_singular() && comments_open() && get_option('thread_comments')) {
            wp_enqueue_script('comment-reply');
        }
    }

    /**
     * Register admin assets
     */
    public function register_admin_assets() {
        // Register admin styles
        wp_register_style(
            'aqualuxe-admin',
            AQUALUXE_ASSETS_URI . 'css/admin.css',
            [],
            AQUALUXE_VERSION
        );
        
        // Enqueue admin styles
        wp_enqueue_style('aqualuxe-admin');
        
        // Register admin scripts
        wp_register_script(
            'aqualuxe-admin',
            AQUALUXE_ASSETS_URI . 'js/admin.js',
            ['jquery', 'wp-color-picker'],
            AQUALUXE_VERSION,
            true
        );
        
        // Enqueue admin scripts
        wp_enqueue_script('aqualuxe-admin');
        
        // Enqueue WordPress media uploader
        wp_enqueue_media();
        
        // Enqueue color picker
        wp_enqueue_style('wp-color-picker');
    }

    /**
     * Register menus
     */
    public function register_menus() {
        register_nav_menus([
            'primary' => esc_html__('Primary Menu', 'aqualuxe'),
            'footer' => esc_html__('Footer Menu', 'aqualuxe'),
            'top-bar' => esc_html__('Top Bar Menu', 'aqualuxe'),
            'mobile' => esc_html__('Mobile Menu', 'aqualuxe'),
            'account' => esc_html__('Account Menu', 'aqualuxe'),
        ]);
    }

    /**
     * Register sidebars
     */
    public function register_sidebars() {
        register_sidebar([
            'name'          => esc_html__('Blog Sidebar', 'aqualuxe'),
            'id'            => 'sidebar-blog',
            'description'   => esc_html__('Add widgets here to appear in your blog sidebar.', 'aqualuxe'),
            'before_widget' => '<div id="%1$s" class="widget %2$s mb-8">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title text-xl font-serif font-bold mb-4">',
            'after_title'   => '</h3>',
        ]);
        
        register_sidebar([
            'name'          => esc_html__('Shop Sidebar', 'aqualuxe'),
            'id'            => 'sidebar-shop',
            'description'   => esc_html__('Add widgets here to appear in your shop sidebar.', 'aqualuxe'),
            'before_widget' => '<div id="%1$s" class="widget %2$s mb-8">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title text-xl font-serif font-bold mb-4">',
            'after_title'   => '</h3>',
        ]);
        
        register_sidebar([
            'name'          => esc_html__('Footer 1', 'aqualuxe'),
            'id'            => 'footer-1',
            'description'   => esc_html__('Add widgets here to appear in footer column 1.', 'aqualuxe'),
            'before_widget' => '<div id="%1$s" class="widget %2$s mb-6">',
            'after_widget'  => '</div>',
            'before_title'  => '<h4 class="widget-title text-lg font-bold mb-4">',
            'after_title'   => '</h4>',
        ]);
        
        register_sidebar([
            'name'          => esc_html__('Footer 2', 'aqualuxe'),
            'id'            => 'footer-2',
            'description'   => esc_html__('Add widgets here to appear in footer column 2.', 'aqualuxe'),
            'before_widget' => '<div id="%1$s" class="widget %2$s mb-6">',
            'after_widget'  => '</div>',
            'before_title'  => '<h4 class="widget-title text-lg font-bold mb-4">',
            'after_title'   => '</h4>',
        ]);
        
        register_sidebar([
            'name'          => esc_html__('Footer 3', 'aqualuxe'),
            'id'            => 'footer-3',
            'description'   => esc_html__('Add widgets here to appear in footer column 3.', 'aqualuxe'),
            'before_widget' => '<div id="%1$s" class="widget %2$s mb-6">',
            'after_widget'  => '</div>',
            'before_title'  => '<h4 class="widget-title text-lg font-bold mb-4">',
            'after_title'   => '</h4>',
        ]);
        
        register_sidebar([
            'name'          => esc_html__('Footer 4', 'aqualuxe'),
            'id'            => 'footer-4',
            'description'   => esc_html__('Add widgets here to appear in footer column 4.', 'aqualuxe'),
            'before_widget' => '<div id="%1$s" class="widget %2$s mb-6">',
            'after_widget'  => '</div>',
            'before_title'  => '<h4 class="widget-title text-lg font-bold mb-4">',
            'after_title'   => '</h4>',
        ]);
    }

    /**
     * Add theme support
     */
    public function add_theme_support() {
        // Add support for custom logo
        add_theme_support('custom-logo', [
            'height'      => 250,
            'width'       => 250,
            'flex-width'  => true,
            'flex-height' => true,
        ]);
        
        // Add support for custom background
        add_theme_support('custom-background', [
            'default-color' => 'ffffff',
            'default-image' => '',
        ]);
        
        // Add support for custom header
        add_theme_support('custom-header', [
            'default-image'          => '',
            'width'                  => 1920,
            'height'                 => 500,
            'flex-height'            => true,
            'flex-width'             => true,
            'uploads'                => true,
            'random-default'         => false,
            'header-text'            => false,
            'default-text-color'     => '',
            'wp-head-callback'       => '',
            'admin-head-callback'    => '',
            'admin-preview-callback' => '',
        ]);
    }

    /**
     * WooCommerce support
     */
    public function woocommerce_support() {
        // Add WooCommerce support
        add_theme_support('woocommerce');
        
        // Add product gallery features
        add_theme_support('wc-product-gallery-zoom');
        add_theme_support('wc-product-gallery-lightbox');
        add_theme_support('wc-product-gallery-slider');
    }

    /**
     * Register custom post types
     */
    public function register_post_types() {
        // Service post type
        $service_labels = [
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
        ];
        
        $service_args = [
            'labels'             => $service_labels,
            'description'        => __('Aquarium services offered by AquaLuxe', 'aqualuxe'),
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => ['slug' => 'services'],
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => 20,
            'menu_icon'          => 'dashicons-hammer',
            'supports'           => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
            'show_in_rest'       => true,
        ];
        
        register_post_type('aqualuxe_service', $service_args);
        
        // Event post type
        $event_labels = [
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
        ];
        
        $event_args = [
            'labels'             => $event_labels,
            'description'        => __('Aquarium events and exhibitions', 'aqualuxe'),
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => ['slug' => 'events'],
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => 21,
            'menu_icon'          => 'dashicons-calendar-alt',
            'supports'           => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
            'show_in_rest'       => true,
        ];
        
        register_post_type('aqualuxe_event', $event_args);
        
        // Testimonial post type
        $testimonial_labels = [
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
        ];
        
        $testimonial_args = [
            'labels'             => $testimonial_labels,
            'description'        => __('Customer testimonials', 'aqualuxe'),
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => ['slug' => 'testimonials'],
            'capability_type'    => 'post',
            'has_archive'        => false,
            'hierarchical'       => false,
            'menu_position'      => 22,
            'menu_icon'          => 'dashicons-format-quote',
            'supports'           => ['title', 'editor', 'thumbnail', 'custom-fields'],
            'show_in_rest'       => true,
        ];
        
        register_post_type('aqualuxe_testimonial', $testimonial_args);
    }

    /**
     * Register taxonomies
     */
    public function register_taxonomies() {
        // Service Category
        $service_cat_labels = [
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
            'menu_name'         => __('Categories', 'aqualuxe'),
        ];
        
        $service_cat_args = [
            'hierarchical'      => true,
            'labels'            => $service_cat_labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => ['slug' => 'service-category'],
            'show_in_rest'      => true,
        ];
        
        register_taxonomy('aqualuxe_service_cat', ['aqualuxe_service'], $service_cat_args);
        
        // Event Type
        $event_type_labels = [
            'name'              => _x('Event Types', 'taxonomy general name', 'aqualuxe'),
            'singular_name'     => _x('Event Type', 'taxonomy singular name', 'aqualuxe'),
            'search_items'      => __('Search Event Types', 'aqualuxe'),
            'all_items'         => __('All Event Types', 'aqualuxe'),
            'parent_item'       => __('Parent Event Type', 'aqualuxe'),
            'parent_item_colon' => __('Parent Event Type:', 'aqualuxe'),
            'edit_item'         => __('Edit Event Type', 'aqualuxe'),
            'update_item'       => __('Update Event Type', 'aqualuxe'),
            'add_new_item'      => __('Add New Event Type', 'aqualuxe'),
            'new_item_name'     => __('New Event Type Name', 'aqualuxe'),
            'menu_name'         => __('Event Types', 'aqualuxe'),
        ];
        
        $event_type_args = [
            'hierarchical'      => true,
            'labels'            => $event_type_labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => ['slug' => 'event-type'],
            'show_in_rest'      => true,
        ];
        
        register_taxonomy('aqualuxe_event_type', ['aqualuxe_event'], $event_type_args);
        
        // Fish Species (for WooCommerce products)
        if (class_exists('WooCommerce')) {
            $species_labels = [
                'name'              => _x('Fish Species', 'taxonomy general name', 'aqualuxe'),
                'singular_name'     => _x('Fish Species', 'taxonomy singular name', 'aqualuxe'),
                'search_items'      => __('Search Fish Species', 'aqualuxe'),
                'all_items'         => __('All Fish Species', 'aqualuxe'),
                'parent_item'       => __('Parent Fish Species', 'aqualuxe'),
                'parent_item_colon' => __('Parent Fish Species:', 'aqualuxe'),
                'edit_item'         => __('Edit Fish Species', 'aqualuxe'),
                'update_item'       => __('Update Fish Species', 'aqualuxe'),
                'add_new_item'      => __('Add New Fish Species', 'aqualuxe'),
                'new_item_name'     => __('New Fish Species Name', 'aqualuxe'),
                'menu_name'         => __('Fish Species', 'aqualuxe'),
            ];
            
            $species_args = [
                'hierarchical'      => true,
                'labels'            => $species_labels,
                'show_ui'           => true,
                'show_admin_column' => true,
                'query_var'         => true,
                'rewrite'           => ['slug' => 'fish-species'],
                'show_in_rest'      => true,
            ];
            
            register_taxonomy('fish_species', ['product'], $species_args);
            
            // Water Type (for WooCommerce products)
            $water_type_labels = [
                'name'              => _x('Water Types', 'taxonomy general name', 'aqualuxe'),
                'singular_name'     => _x('Water Type', 'taxonomy singular name', 'aqualuxe'),
                'search_items'      => __('Search Water Types', 'aqualuxe'),
                'all_items'         => __('All Water Types', 'aqualuxe'),
                'parent_item'       => __('Parent Water Type', 'aqualuxe'),
                'parent_item_colon' => __('Parent Water Type:', 'aqualuxe'),
                'edit_item'         => __('Edit Water Type', 'aqualuxe'),
                'update_item'       => __('Update Water Type', 'aqualuxe'),
                'add_new_item'      => __('Add New Water Type', 'aqualuxe'),
                'new_item_name'     => __('New Water Type Name', 'aqualuxe'),
                'menu_name'         => __('Water Types', 'aqualuxe'),
            ];
            
            $water_type_args = [
                'hierarchical'      => true,
                'labels'            => $water_type_labels,
                'show_ui'           => true,
                'show_admin_column' => true,
                'query_var'         => true,
                'rewrite'           => ['slug' => 'water-type'],
                'show_in_rest'      => true,
            ];
            
            register_taxonomy('water_type', ['product'], $water_type_args);
        }
    }
}

// Initialize the theme
AquaLuxe::instance();

/**
 * Custom template tags for this theme.
 */
require_once AQUALUXE_INC_DIR . 'template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require_once AQUALUXE_INC_DIR . 'template-functions.php';

/**
 * Customizer additions.
 */
require_once AQUALUXE_INC_DIR . 'customizer.php';

/**
 * WooCommerce compatibility file.
 */
if (class_exists('WooCommerce')) {
    require_once AQUALUXE_INC_DIR . 'woocommerce.php';
}