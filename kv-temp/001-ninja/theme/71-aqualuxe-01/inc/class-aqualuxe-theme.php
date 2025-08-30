<?php
/**
 * Main AquaLuxe Theme Class
 * 
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Main theme class
 */
class AquaLuxe_Theme {
    
    /**
     * Instance of this class
     */
    private static $instance = null;
    
    /**
     * Theme version
     */
    private $version;
    
    /**
     * Theme options
     */
    private $options;
    
    /**
     * Constructor
     */
    private function __construct() {
        $this->version = AQUALUXE_VERSION;
        $this->init_hooks();
        $this->load_options();
    }
    
    /**
     * Get instance
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Initialize hooks
     */
    private function init_hooks() {
        add_action('init', [$this, 'init']);
        add_action('wp_head', [$this, 'add_meta_tags']);
        add_action('wp_footer', [$this, 'add_schema_markup']);
        add_filter('body_class', [$this, 'add_body_classes']);
        add_filter('wp_nav_menu_args', [$this, 'modify_nav_menu_args']);
    }
    
    /**
     * Initialize theme
     */
    public function init() {
        // Set up theme features
        $this->setup_theme_features();
        
        // Register post types and taxonomies
        $this->register_custom_post_types();
        $this->register_custom_taxonomies();
        
        // Add custom image sizes
        $this->add_custom_image_sizes();
        
        // Initialize modules
        $this->init_modules();
    }
    
    /**
     * Load theme options
     */
    private function load_options() {
        $this->options = [
            'primary_color' => get_theme_mod('aqualuxe_primary_color', '#06b6d4'),
            'secondary_color' => get_theme_mod('aqualuxe_secondary_color', '#d946ef'),
            'dark_mode_enabled' => get_theme_mod('aqualuxe_dark_mode_enabled', true),
            'performance_optimizations' => get_theme_mod('aqualuxe_performance_optimizations', true),
            'multilingual_enabled' => get_theme_mod('aqualuxe_multilingual_enabled', true),
            'woocommerce_integration' => class_exists('WooCommerce'),
            'lazy_loading' => get_theme_mod('aqualuxe_lazy_loading', true),
            'social_sharing' => get_theme_mod('aqualuxe_social_sharing', true),
        ];
    }
    
    /**
     * Setup theme features
     */
    private function setup_theme_features() {
        // Add custom post type support
        add_theme_support('post-formats', [
            'gallery',
            'image',
            'video',
            'quote',
            'link',
        ]);
        
        // Add custom header support
        add_theme_support('custom-header', [
            'default-image' => AQUALUXE_THEME_URI . '/assets/src/images/header-bg.jpg',
            'width' => 1920,
            'height' => 1080,
            'flex-height' => true,
            'flex-width' => true,
            'uploads' => true,
            'random-default' => false,
        ]);
        
        // Add starter content
        add_theme_support('starter-content', [
            'widgets' => [
                'footer-1' => [
                    'text_business_info',
                    'meta',
                ],
                'footer-2' => [
                    'text_about',
                ],
                'footer-3' => [
                    'text_contact',
                ],
            ],
            'posts' => [
                'home',
                'about' => [
                    'thumbnail' => '{{image-sandwich}}',
                ],
                'contact' => [
                    'thumbnail' => '{{image-espresso}}',
                ],
                'blog' => [
                    'thumbnail' => '{{image-coffee}}',
                ],
            ],
            'nav_menus' => [
                'primary' => [
                    'name' => __('Primary', 'aqualuxe'),
                    'items' => [
                        'link_home',
                        'page_about',
                        'page_blog',
                        'page_contact',
                    ],
                ],
            ],
            'options' => [
                'show_on_front' => 'page',
                'page_on_front' => '{{home}}',
                'page_for_posts' => '{{blog}}',
            ],
        ]);
    }
    
    /**
     * Register custom post types
     */
    private function register_custom_post_types() {
        // Services post type
        register_post_type('aqualuxe_service', [
            'labels' => [
                'name' => __('Services', 'aqualuxe'),
                'singular_name' => __('Service', 'aqualuxe'),
                'menu_name' => __('Services', 'aqualuxe'),
                'add_new' => __('Add New Service', 'aqualuxe'),
                'add_new_item' => __('Add New Service', 'aqualuxe'),
                'edit_item' => __('Edit Service', 'aqualuxe'),
                'new_item' => __('New Service', 'aqualuxe'),
                'view_item' => __('View Service', 'aqualuxe'),
                'search_items' => __('Search Services', 'aqualuxe'),
                'not_found' => __('No services found', 'aqualuxe'),
                'not_found_in_trash' => __('No services found in trash', 'aqualuxe'),
            ],
            'public' => true,
            'has_archive' => true,
            'rewrite' => ['slug' => 'services'],
            'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
            'menu_icon' => 'dashicons-admin-tools',
            'show_in_rest' => true,
        ]);
        
        // Events post type
        register_post_type('aqualuxe_event', [
            'labels' => [
                'name' => __('Events', 'aqualuxe'),
                'singular_name' => __('Event', 'aqualuxe'),
                'menu_name' => __('Events', 'aqualuxe'),
                'add_new' => __('Add New Event', 'aqualuxe'),
                'add_new_item' => __('Add New Event', 'aqualuxe'),
                'edit_item' => __('Edit Event', 'aqualuxe'),
                'new_item' => __('New Event', 'aqualuxe'),
                'view_item' => __('View Event', 'aqualuxe'),
                'search_items' => __('Search Events', 'aqualuxe'),
                'not_found' => __('No events found', 'aqualuxe'),
                'not_found_in_trash' => __('No events found in trash', 'aqualuxe'),
            ],
            'public' => true,
            'has_archive' => true,
            'rewrite' => ['slug' => 'events'],
            'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
            'menu_icon' => 'dashicons-calendar-alt',
            'show_in_rest' => true,
        ]);
        
        // Testimonials post type
        register_post_type('aqualuxe_testimonial', [
            'labels' => [
                'name' => __('Testimonials', 'aqualuxe'),
                'singular_name' => __('Testimonial', 'aqualuxe'),
                'menu_name' => __('Testimonials', 'aqualuxe'),
                'add_new' => __('Add New Testimonial', 'aqualuxe'),
                'add_new_item' => __('Add New Testimonial', 'aqualuxe'),
                'edit_item' => __('Edit Testimonial', 'aqualuxe'),
                'new_item' => __('New Testimonial', 'aqualuxe'),
                'view_item' => __('View Testimonial', 'aqualuxe'),
                'search_items' => __('Search Testimonials', 'aqualuxe'),
                'not_found' => __('No testimonials found', 'aqualuxe'),
                'not_found_in_trash' => __('No testimonials found in trash', 'aqualuxe'),
            ],
            'public' => false,
            'show_ui' => true,
            'supports' => ['title', 'editor', 'thumbnail', 'custom-fields'],
            'menu_icon' => 'dashicons-format-quote',
            'show_in_rest' => true,
        ]);
        
        // Team post type
        register_post_type('aqualuxe_team', [
            'labels' => [
                'name' => __('Team Members', 'aqualuxe'),
                'singular_name' => __('Team Member', 'aqualuxe'),
                'menu_name' => __('Team', 'aqualuxe'),
                'add_new' => __('Add New Member', 'aqualuxe'),
                'add_new_item' => __('Add New Team Member', 'aqualuxe'),
                'edit_item' => __('Edit Team Member', 'aqualuxe'),
                'new_item' => __('New Team Member', 'aqualuxe'),
                'view_item' => __('View Team Member', 'aqualuxe'),
                'search_items' => __('Search Team', 'aqualuxe'),
                'not_found' => __('No team members found', 'aqualuxe'),
                'not_found_in_trash' => __('No team members found in trash', 'aqualuxe'),
            ],
            'public' => true,
            'has_archive' => true,
            'rewrite' => ['slug' => 'team'],
            'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
            'menu_icon' => 'dashicons-groups',
            'show_in_rest' => true,
        ]);
    }
    
    /**
     * Register custom taxonomies
     */
    private function register_custom_taxonomies() {
        // Service categories
        register_taxonomy('service_category', 'aqualuxe_service', [
            'labels' => [
                'name' => __('Service Categories', 'aqualuxe'),
                'singular_name' => __('Service Category', 'aqualuxe'),
                'menu_name' => __('Categories', 'aqualuxe'),
                'search_items' => __('Search Categories', 'aqualuxe'),
                'all_items' => __('All Categories', 'aqualuxe'),
                'edit_item' => __('Edit Category', 'aqualuxe'),
                'update_item' => __('Update Category', 'aqualuxe'),
                'add_new_item' => __('Add New Category', 'aqualuxe'),
                'new_item_name' => __('New Category Name', 'aqualuxe'),
            ],
            'hierarchical' => true,
            'public' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'show_in_nav_menus' => true,
            'show_tagcloud' => true,
            'show_in_rest' => true,
        ]);
        
        // Event categories
        register_taxonomy('event_category', 'aqualuxe_event', [
            'labels' => [
                'name' => __('Event Categories', 'aqualuxe'),
                'singular_name' => __('Event Category', 'aqualuxe'),
                'menu_name' => __('Categories', 'aqualuxe'),
                'search_items' => __('Search Categories', 'aqualuxe'),
                'all_items' => __('All Categories', 'aqualuxe'),
                'edit_item' => __('Edit Category', 'aqualuxe'),
                'update_item' => __('Update Category', 'aqualuxe'),
                'add_new_item' => __('Add New Category', 'aqualuxe'),
                'new_item_name' => __('New Category Name', 'aqualuxe'),
            ],
            'hierarchical' => true,
            'public' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'show_in_nav_menus' => true,
            'show_tagcloud' => true,
            'show_in_rest' => true,
        ]);
        
        // Team roles
        register_taxonomy('team_role', 'aqualuxe_team', [
            'labels' => [
                'name' => __('Team Roles', 'aqualuxe'),
                'singular_name' => __('Team Role', 'aqualuxe'),
                'menu_name' => __('Roles', 'aqualuxe'),
                'search_items' => __('Search Roles', 'aqualuxe'),
                'all_items' => __('All Roles', 'aqualuxe'),
                'edit_item' => __('Edit Role', 'aqualuxe'),
                'update_item' => __('Update Role', 'aqualuxe'),
                'add_new_item' => __('Add New Role', 'aqualuxe'),
                'new_item_name' => __('New Role Name', 'aqualuxe'),
            ],
            'hierarchical' => false,
            'public' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'show_in_nav_menus' => true,
            'show_tagcloud' => false,
            'show_in_rest' => true,
        ]);
    }
    
    /**
     * Add custom image sizes
     */
    private function add_custom_image_sizes() {
        // Service images
        add_image_size('aqualuxe-service-thumb', 400, 300, true);
        add_image_size('aqualuxe-service-large', 800, 600, true);
        
        // Event images
        add_image_size('aqualuxe-event-thumb', 300, 200, true);
        add_image_size('aqualuxe-event-large', 1200, 800, true);
        
        // Team images
        add_image_size('aqualuxe-team-thumb', 300, 300, true);
        add_image_size('aqualuxe-team-large', 600, 600, true);
        
        // Blog images
        add_image_size('aqualuxe-blog-thumb', 400, 250, true);
        add_image_size('aqualuxe-blog-large', 1200, 750, true);
    }
    
    /**
     * Initialize modules
     */
    private function init_modules() {
        do_action('aqualuxe_init_modules');
    }
    
    /**
     * Add meta tags
     */
    public function add_meta_tags() {
        echo '<meta name="viewport" content="width=device-width, initial-scale=1">' . "\n";
        echo '<meta name="format-detection" content="telephone=no">' . "\n";
        echo '<meta name="theme-color" content="' . esc_attr($this->options['primary_color']) . '">' . "\n";
        
        if ($this->options['dark_mode_enabled']) {
            echo '<meta name="color-scheme" content="light dark">' . "\n";
        }
    }
    
    /**
     * Add schema markup
     */
    public function add_schema_markup() {
        if (is_front_page()) {
            $schema = [
                '@context' => 'https://schema.org',
                '@type' => 'Organization',
                'name' => get_bloginfo('name'),
                'description' => get_bloginfo('description'),
                'url' => home_url('/'),
                'logo' => wp_get_attachment_image_url(get_theme_mod('custom_logo'), 'full'),
                'sameAs' => [
                    get_theme_mod('aqualuxe_social_facebook', ''),
                    get_theme_mod('aqualuxe_social_twitter', ''),
                    get_theme_mod('aqualuxe_social_instagram', ''),
                    get_theme_mod('aqualuxe_social_linkedin', ''),
                ],
            ];
            
            echo '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>' . "\n";
        }
    }
    
    /**
     * Add body classes
     */
    public function add_body_classes($classes) {
        // Add theme version class
        $classes[] = 'aqualuxe-theme';
        $classes[] = 'aqualuxe-v' . str_replace('.', '-', $this->version);
        
        // Add feature classes
        if ($this->options['dark_mode_enabled']) {
            $classes[] = 'dark-mode-enabled';
        }
        
        if ($this->options['woocommerce_integration']) {
            $classes[] = 'woocommerce-active';
        }
        
        // Add layout classes
        if (is_page_template('templates/full-width.php')) {
            $classes[] = 'full-width-layout';
        }
        
        return $classes;
    }
    
    /**
     * Modify navigation menu arguments
     */
    public function modify_nav_menu_args($args) {
        if ('primary' === $args['theme_location']) {
            $args['menu_class'] = 'primary-menu flex space-x-6';
            $args['container_class'] = 'primary-menu-container';
        }
        
        return $args;
    }
    
    /**
     * Get theme option
     */
    public function get_option($key, $default = null) {
        return isset($this->options[$key]) ? $this->options[$key] : $default;
    }
    
    /**
     * Set theme option
     */
    public function set_option($key, $value) {
        $this->options[$key] = $value;
        set_theme_mod('aqualuxe_' . $key, $value);
    }
    
    /**
     * Get all options
     */
    public function get_options() {
        return $this->options;
    }
    
    /**
     * Get theme version
     */
    public function get_version() {
        return $this->version;
    }
}
