<?php
/**
 * AquaLuxe Theme Functions
 * 
 * Main theme functionality and setup
 * 
 * @package AquaLuxe
 * @version 1.0.0
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Theme constants
 */
define('AQUALUXE_VERSION', '1.0.0');
define('AQUALUXE_THEME_DIR', get_template_directory());
define('AQUALUXE_THEME_URI', get_template_directory_uri());
define('AQUALUXE_ASSETS_URI', AQUALUXE_THEME_URI . '/assets/dist');
define('AQUALUXE_INCLUDES_DIR', AQUALUXE_THEME_DIR . '/inc');
define('AQUALUXE_MODULES_DIR', AQUALUXE_THEME_DIR . '/modules');

/**
 * Theme Core Class
 */
class AquaLuxe_Theme {
    
    /**
     * Theme instance
     * @var AquaLuxe_Theme
     */
    private static $instance = null;
    
    /**
     * Get theme instance
     * @return AquaLuxe_Theme
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
        $this->init_hooks();
        $this->load_dependencies();
        $this->init_modules();
    }
    
    /**
     * Initialize hooks
     */
    private function init_hooks() {
        add_action('after_setup_theme', [$this, 'theme_setup']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
        add_action('admin_enqueue_scripts', [$this, 'admin_enqueue_assets']);
        add_action('customize_register', [$this, 'customizer_setup']);
        add_action('widgets_init', [$this, 'widgets_init']);
        add_action('init', [$this, 'init_theme']);
        
        // WooCommerce hooks
        add_action('after_setup_theme', [$this, 'woocommerce_setup']);
        add_filter('woocommerce_enqueue_styles', '__return_empty_array');
        
        // Security hooks
        add_action('wp_head', [$this, 'security_headers']);
        remove_action('wp_head', 'wp_generator');
        
        // Performance hooks
        add_action('wp_head', [$this, 'preload_assets']);
        add_filter('script_loader_tag', [$this, 'add_async_defer_attributes'], 10, 2);
    }
    
    /**
     * Load core dependencies
     */
    private function load_dependencies() {
        $includes = [
            'class-assets-manager.php',
            'class-customizer.php',
            'class-security.php',
            'class-performance.php',
            'class-seo.php',
            'class-woocommerce.php',
            'class-demo-importer.php',
            'template-functions.php',
            'template-hooks.php',
            'admin-functions.php',
            'ajax-handlers.php',
        ];
        
        foreach ($includes as $file) {
            $file_path = AQUALUXE_INCLUDES_DIR . '/' . $file;
            if (file_exists($file_path)) {
                require_once $file_path;
            }
        }
    }
    
    /**
     * Initialize modules
     */
    private function init_modules() {
        $modules = [
            'multilingual',
            'dark-mode',
            'subscriptions',
            'auctions',
            'bookings',
            'events',
            'wholesale',
            'trade-ins',
            'services',
            'franchise',
            'sustainability',
            'affiliates',
        ];
        
        foreach ($modules as $module) {
            $module_file = AQUALUXE_MODULES_DIR . '/' . $module . '/module.php';
            if (file_exists($module_file)) {
                require_once $module_file;
            }
        }
    }
    
    /**
     * Theme setup
     */
    public function theme_setup() {
        // Make theme available for translation
        load_theme_textdomain('aqualuxe', AQUALUXE_THEME_DIR . '/languages');
        
        // Add default posts and comments RSS feed links to head
        add_theme_support('automatic-feed-links');
        
        // Let WordPress manage the document title
        add_theme_support('title-tag');
        
        // Enable support for Post Thumbnails
        add_theme_support('post-thumbnails');
        
        // Add custom image sizes
        add_image_size('aqualuxe-hero', 1920, 1080, true);
        add_image_size('aqualuxe-featured', 800, 600, true);
        add_image_size('aqualuxe-product-thumb', 400, 400, true);
        add_image_size('aqualuxe-gallery-thumb', 300, 300, true);
        add_image_size('aqualuxe-blog-thumb', 600, 400, true);
        
        // Register navigation menus
        register_nav_menus([
            'primary' => esc_html__('Primary Menu', 'aqualuxe'),
            'secondary' => esc_html__('Secondary Menu', 'aqualuxe'),
            'mobile' => esc_html__('Mobile Menu', 'aqualuxe'),
            'footer' => esc_html__('Footer Menu', 'aqualuxe'),
            'wholesale' => esc_html__('Wholesale Menu', 'aqualuxe'),
        ]);
        
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
        
        // Set up the WordPress core custom background feature
        add_theme_support('custom-background', [
            'default-color' => 'ffffff',
            'default-image' => '',
        ]);
        
        // Add theme support for selective refresh for widgets
        add_theme_support('customize-selective-refresh-widgets');
        
        // Add support for custom logo
        add_theme_support('custom-logo', [
            'height' => 250,
            'width' => 250,
            'flex-width' => true,
            'flex-height' => true,
        ]);
        
        // Add support for responsive embeds
        add_theme_support('responsive-embeds');
        
        // Add support for editor styles
        add_theme_support('editor-styles');
        add_editor_style('assets/dist/css/editor-style.css');
        
        // Add support for wide and full alignment
        add_theme_support('align-wide');
        
        // Add support for block styles
        add_theme_support('wp-block-styles');
        
        // Add support for editor color palette
        add_theme_support('editor-color-palette', [
            [
                'name' => esc_html__('Primary', 'aqualuxe'),
                'slug' => 'primary',
                'color' => '#14b8a6',
            ],
            [
                'name' => esc_html__('Secondary', 'aqualuxe'),
                'slug' => 'secondary',
                'color' => '#64748b',
            ],
            [
                'name' => esc_html__('Accent', 'aqualuxe'),
                'slug' => 'accent',
                'color' => '#eab308',
            ],
        ]);
        
        // Add support for custom line height
        add_theme_support('custom-line-height');
        
        // Add support for experimental link color
        add_theme_support('experimental-link-color');
        
        // Remove core block patterns
        remove_theme_support('core-block-patterns');
    }
    
    /**
     * WooCommerce setup
     */
    public function woocommerce_setup() {
        if (!class_exists('WooCommerce')) {
            return;
        }
        
        add_theme_support('woocommerce', [
            'thumbnail_image_width' => 400,
            'single_image_width' => 800,
            'product_grid' => [
                'default_rows' => 3,
                'min_rows' => 1,
                'default_columns' => 4,
                'min_columns' => 1,
                'max_columns' => 6,
            ],
        ]);
        
        add_theme_support('wc-product-gallery-zoom');
        add_theme_support('wc-product-gallery-lightbox');
        add_theme_support('wc-product-gallery-slider');
    }
    
    /**
     * Enqueue frontend assets
     */
    public function enqueue_assets() {
        $manifest = $this->get_mix_manifest();
        
        // Styles
        wp_enqueue_style('aqualuxe-style', $this->get_versioned_asset('css/app.css', $manifest), [], AQUALUXE_VERSION);
        
        if (class_exists('WooCommerce')) {
            wp_enqueue_style('aqualuxe-woocommerce', $this->get_versioned_asset('css/woocommerce.css', $manifest), ['aqualuxe-style'], AQUALUXE_VERSION);
        }
        
        // Dark mode styles (conditionally)
        if (get_theme_mod('aqualuxe_dark_mode_enabled', true)) {
            wp_enqueue_style('aqualuxe-dark-mode', $this->get_versioned_asset('css/dark-mode.css', $manifest), ['aqualuxe-style'], AQUALUXE_VERSION);
        }
        
        // Scripts
        wp_enqueue_script('aqualuxe-vendor', $this->get_versioned_asset('js/vendor.js', $manifest), [], AQUALUXE_VERSION, true);
        wp_enqueue_script('aqualuxe-app', $this->get_versioned_asset('js/app.js', $manifest), ['aqualuxe-vendor'], AQUALUXE_VERSION, true);
        
        if (class_exists('WooCommerce')) {
            wp_enqueue_script('aqualuxe-woocommerce', $this->get_versioned_asset('js/woocommerce.js', $manifest), ['aqualuxe-app'], AQUALUXE_VERSION, true);
        }
        
        // Localize script
        wp_localize_script('aqualuxe-app', 'aqualuxe_ajax', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe_nonce'),
            'theme_uri' => AQUALUXE_THEME_URI,
            'is_rtl' => is_rtl(),
            'is_user_logged_in' => is_user_logged_in(),
            'wc_enabled' => class_exists('WooCommerce'),
            'strings' => [
                'loading' => esc_html__('Loading...', 'aqualuxe'),
                'error' => esc_html__('Something went wrong. Please try again.', 'aqualuxe'),
                'success' => esc_html__('Success!', 'aqualuxe'),
            ],
        ]);
        
        // Comment reply script
        if (is_singular() && comments_open() && get_option('thread_comments')) {
            wp_enqueue_script('comment-reply');
        }
    }
    
    /**
     * Enqueue admin assets
     */
    public function admin_enqueue_assets($hook) {
        $manifest = $this->get_mix_manifest();
        
        wp_enqueue_style('aqualuxe-admin', $this->get_versioned_asset('css/admin.css', $manifest), [], AQUALUXE_VERSION);
        wp_enqueue_script('aqualuxe-admin', $this->get_versioned_asset('js/admin.js', $manifest), ['jquery'], AQUALUXE_VERSION, true);
        
        // Customizer assets
        if ('customize.php' === $hook) {
            wp_enqueue_script('aqualuxe-customizer', $this->get_versioned_asset('js/customizer.js', $manifest), ['customize-controls'], AQUALUXE_VERSION, true);
            wp_enqueue_style('aqualuxe-customizer', $this->get_versioned_asset('css/customizer.css', $manifest), [], AQUALUXE_VERSION);
        }
    }
    
    /**
     * Get mix manifest
     */
    private function get_mix_manifest() {
        static $manifest = null;
        
        if (is_null($manifest)) {
            $manifest_path = AQUALUXE_THEME_DIR . '/assets/dist/mix-manifest.json';
            if (file_exists($manifest_path)) {
                $manifest = json_decode(file_get_contents($manifest_path), true);
            } else {
                $manifest = [];
            }
        }
        
        return $manifest;
    }
    
    /**
     * Get versioned asset URL
     */
    private function get_versioned_asset($asset, $manifest = null) {
        if (is_null($manifest)) {
            $manifest = $this->get_mix_manifest();
        }
        
        $asset_key = '/' . ltrim($asset, '/');
        
        if (isset($manifest[$asset_key])) {
            return AQUALUXE_ASSETS_URI . $manifest[$asset_key];
        }
        
        return AQUALUXE_ASSETS_URI . '/' . ltrim($asset, '/');
    }
    
    /**
     * Initialize theme
     */
    public function init_theme() {
        // Register custom post types
        $this->register_post_types();
        
        // Register taxonomies
        $this->register_taxonomies();
        
        // Add rewrite rules
        $this->add_rewrite_rules();
        
        // Initialize theme options
        $this->init_theme_options();
    }
    
    /**
     * Register custom post types
     */
    private function register_post_types() {
        // Services post type
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
                'not_found_in_trash' => esc_html__('No services found in Trash', 'aqualuxe'),
            ],
            'public' => true,
            'has_archive' => true,
            'menu_position' => 5,
            'menu_icon' => 'dashicons-admin-tools',
            'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
            'rewrite' => ['slug' => 'services'],
            'show_in_rest' => true,
        ]);
        
        // Events post type
        register_post_type('aqualuxe_event', [
            'labels' => [
                'name' => esc_html__('Events', 'aqualuxe'),
                'singular_name' => esc_html__('Event', 'aqualuxe'),
                'add_new' => esc_html__('Add New Event', 'aqualuxe'),
                'add_new_item' => esc_html__('Add New Event', 'aqualuxe'),
                'edit_item' => esc_html__('Edit Event', 'aqualuxe'),
                'new_item' => esc_html__('New Event', 'aqualuxe'),
                'view_item' => esc_html__('View Event', 'aqualuxe'),
                'search_items' => esc_html__('Search Events', 'aqualuxe'),
                'not_found' => esc_html__('No events found', 'aqualuxe'),
                'not_found_in_trash' => esc_html__('No events found in Trash', 'aqualuxe'),
            ],
            'public' => true,
            'has_archive' => true,
            'menu_position' => 6,
            'menu_icon' => 'dashicons-calendar-alt',
            'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
            'rewrite' => ['slug' => 'events'],
            'show_in_rest' => true,
        ]);
        
        // Testimonials post type
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
                'not_found_in_trash' => esc_html__('No testimonials found in Trash', 'aqualuxe'),
            ],
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => true,
            'menu_position' => 7,
            'menu_icon' => 'dashicons-format-quote',
            'supports' => ['title', 'editor', 'thumbnail', 'custom-fields'],
            'show_in_rest' => true,
        ]);
    }
    
    /**
     * Register taxonomies
     */
    private function register_taxonomies() {
        // Service categories
        register_taxonomy('service_category', 'aqualuxe_service', [
            'labels' => [
                'name' => esc_html__('Service Categories', 'aqualuxe'),
                'singular_name' => esc_html__('Service Category', 'aqualuxe'),
                'search_items' => esc_html__('Search Service Categories', 'aqualuxe'),
                'all_items' => esc_html__('All Service Categories', 'aqualuxe'),
                'parent_item' => esc_html__('Parent Service Category', 'aqualuxe'),
                'parent_item_colon' => esc_html__('Parent Service Category:', 'aqualuxe'),
                'edit_item' => esc_html__('Edit Service Category', 'aqualuxe'),
                'update_item' => esc_html__('Update Service Category', 'aqualuxe'),
                'add_new_item' => esc_html__('Add New Service Category', 'aqualuxe'),
                'new_item_name' => esc_html__('New Service Category Name', 'aqualuxe'),
                'menu_name' => esc_html__('Service Categories', 'aqualuxe'),
            ],
            'hierarchical' => true,
            'public' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => ['slug' => 'service-category'],
            'show_in_rest' => true,
        ]);
        
        // Event categories
        register_taxonomy('event_category', 'aqualuxe_event', [
            'labels' => [
                'name' => esc_html__('Event Categories', 'aqualuxe'),
                'singular_name' => esc_html__('Event Category', 'aqualuxe'),
                'search_items' => esc_html__('Search Event Categories', 'aqualuxe'),
                'all_items' => esc_html__('All Event Categories', 'aqualuxe'),
                'parent_item' => esc_html__('Parent Event Category', 'aqualuxe'),
                'parent_item_colon' => esc_html__('Parent Event Category:', 'aqualuxe'),
                'edit_item' => esc_html__('Edit Event Category', 'aqualuxe'),
                'update_item' => esc_html__('Update Event Category', 'aqualuxe'),
                'add_new_item' => esc_html__('Add New Event Category', 'aqualuxe'),
                'new_item_name' => esc_html__('New Event Category Name', 'aqualuxe'),
                'menu_name' => esc_html__('Event Categories', 'aqualuxe'),
            ],
            'hierarchical' => true,
            'public' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => ['slug' => 'event-category'],
            'show_in_rest' => true,
        ]);
    }
    
    /**
     * Add rewrite rules
     */
    private function add_rewrite_rules() {
        // Custom rewrite rules for better SEO
        add_rewrite_rule('^wholesale/?$', 'index.php?pagename=wholesale', 'top');
        add_rewrite_rule('^export/?$', 'index.php?pagename=export', 'top');
        add_rewrite_rule('^trade-in/?$', 'index.php?pagename=trade-in', 'top');
    }
    
    /**
     * Initialize theme options
     */
    private function init_theme_options() {
        // Set default options on theme activation
        if (get_option('aqualuxe_theme_activated') !== AQUALUXE_VERSION) {
            $this->set_default_options();
            update_option('aqualuxe_theme_activated', AQUALUXE_VERSION);
        }
    }
    
    /**
     * Set default theme options
     */
    private function set_default_options() {
        $defaults = [
            'aqualuxe_primary_color' => '#14b8a6',
            'aqualuxe_secondary_color' => '#64748b',
            'aqualuxe_accent_color' => '#eab308',
            'aqualuxe_dark_mode_enabled' => true,
            'aqualuxe_lazy_loading' => true,
            'aqualuxe_performance_mode' => true,
            'aqualuxe_seo_enabled' => true,
            'aqualuxe_social_sharing' => true,
            'aqualuxe_newsletter_enabled' => true,
            'aqualuxe_demo_content' => false,
        ];
        
        foreach ($defaults as $option => $value) {
            if (get_theme_mod($option) === null) {
                set_theme_mod($option, $value);
            }
        }
    }
    
    /**
     * Widget areas
     */
    public function widgets_init() {
        register_sidebar([
            'name' => esc_html__('Primary Sidebar', 'aqualuxe'),
            'id' => 'sidebar-1',
            'description' => esc_html__('Add widgets here to appear in your sidebar.', 'aqualuxe'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget' => '</section>',
            'before_title' => '<h2 class="widget-title">',
            'after_title' => '</h2>',
        ]);
        
        register_sidebar([
            'name' => esc_html__('Footer Area 1', 'aqualuxe'),
            'id' => 'footer-1',
            'description' => esc_html__('Add widgets here to appear in the first footer area.', 'aqualuxe'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget' => '</section>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ]);
        
        register_sidebar([
            'name' => esc_html__('Footer Area 2', 'aqualuxe'),
            'id' => 'footer-2',
            'description' => esc_html__('Add widgets here to appear in the second footer area.', 'aqualuxe'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget' => '</section>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ]);
        
        register_sidebar([
            'name' => esc_html__('Footer Area 3', 'aqualuxe'),
            'id' => 'footer-3',
            'description' => esc_html__('Add widgets here to appear in the third footer area.', 'aqualuxe'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget' => '</section>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ]);
        
        register_sidebar([
            'name' => esc_html__('Footer Area 4', 'aqualuxe'),
            'id' => 'footer-4',
            'description' => esc_html__('Add widgets here to appear in the fourth footer area.', 'aqualuxe'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget' => '</section>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ]);
        
        // WooCommerce specific sidebars
        if (class_exists('WooCommerce')) {
            register_sidebar([
                'name' => esc_html__('Shop Sidebar', 'aqualuxe'),
                'id' => 'shop-sidebar',
                'description' => esc_html__('Add widgets here to appear in the shop sidebar.', 'aqualuxe'),
                'before_widget' => '<section id="%1$s" class="widget %2$s">',
                'after_widget' => '</section>',
                'before_title' => '<h3 class="widget-title">',
                'after_title' => '</h3>',
            ]);
        }
    }
    
    /**
     * Customizer setup
     */
    public function customizer_setup($wp_customize) {
        // Will be handled by AquaLuxe_Customizer class
    }
    
    /**
     * Security headers
     */
    public function security_headers() {
        // Basic security headers
        if (!headers_sent()) {
            header('X-Content-Type-Options: nosniff');
            header('X-Frame-Options: SAMEORIGIN');
            header('X-XSS-Protection: 1; mode=block');
            header('Referrer-Policy: strict-origin-when-cross-origin');
        }
    }
    
    /**
     * Preload critical assets
     */
    public function preload_assets() {
        $manifest = $this->get_mix_manifest();
        
        // Preload critical CSS
        $critical_css = $this->get_versioned_asset('css/app.css', $manifest);
        echo '<link rel="preload" href="' . esc_url($critical_css) . '" as="style" onload="this.onload=null;this.rel=\'stylesheet\'">' . "\n";
        
        // Preload critical JS
        $critical_js = $this->get_versioned_asset('js/app.js', $manifest);
        echo '<link rel="preload" href="' . esc_url($critical_js) . '" as="script">' . "\n";
        
        // Preload fonts (if any)
        $fonts = [
            'Inter-Regular.woff2',
            'Inter-Medium.woff2',
            'Inter-SemiBold.woff2',
            'PlayfairDisplay-Regular.woff2',
        ];
        
        foreach ($fonts as $font) {
            $font_url = AQUALUXE_ASSETS_URI . '/fonts/' . $font;
            if (file_exists(AQUALUXE_THEME_DIR . '/assets/dist/fonts/' . $font)) {
                echo '<link rel="preload" href="' . esc_url($font_url) . '" as="font" type="font/woff2" crossorigin>' . "\n";
            }
        }
    }
    
    /**
     * Add async/defer attributes to scripts
     */
    public function add_async_defer_attributes($tag, $handle) {
        $async_scripts = ['aqualuxe-app', 'aqualuxe-woocommerce'];
        $defer_scripts = ['aqualuxe-admin'];
        
        if (in_array($handle, $async_scripts)) {
            return str_replace(' src', ' async src', $tag);
        }
        
        if (in_array($handle, $defer_scripts)) {
            return str_replace(' src', ' defer src', $tag);
        }
        
        return $tag;
    }
}

/**
 * Initialize theme
 */
function aqualuxe_theme() {
    return AquaLuxe_Theme::instance();
}

// Initialize the theme
aqualuxe_theme();

/**
 * Template functions for backward compatibility
 */
function aqualuxe_get_theme_mod($option, $default = false) {
    return get_theme_mod('aqualuxe_' . $option, $default);
}

function aqualuxe_is_woocommerce_active() {
    return class_exists('WooCommerce');
}

function aqualuxe_is_dark_mode() {
    return get_theme_mod('aqualuxe_dark_mode_enabled', true);
}

/**
 * Theme activation hook
 */
function aqualuxe_theme_activation() {
    // Flush rewrite rules
    flush_rewrite_rules();
    
    // Set theme activation flag
    update_option('aqualuxe_theme_activated', AQUALUXE_VERSION);
    
    // Create necessary pages
    aqualuxe_create_default_pages();
}

/**
 * Create default pages
 */
function aqualuxe_create_default_pages() {
    $pages = [
        'wholesale' => [
            'title' => 'Wholesale',
            'content' => '[aqualuxe_wholesale_page]',
            'template' => 'page-wholesale.php'
        ],
        'export' => [
            'title' => 'Export',
            'content' => '[aqualuxe_export_page]',
            'template' => 'page-export.php'
        ],
        'trade-in' => [
            'title' => 'Trade-In',
            'content' => '[aqualuxe_trade_in_page]',
            'template' => 'page-trade-in.php'
        ],
        'services' => [
            'title' => 'Services',
            'content' => '[aqualuxe_services_page]',
            'template' => 'page-services.php'
        ],
        'events' => [
            'title' => 'Events',
            'content' => '[aqualuxe_events_page]',
            'template' => 'page-events.php'
        ],
    ];
    
    foreach ($pages as $slug => $page_data) {
        $page = get_page_by_path($slug);
        if (!$page) {
            $page_id = wp_insert_post([
                'post_title' => $page_data['title'],
                'post_content' => $page_data['content'],
                'post_status' => 'publish',
                'post_type' => 'page',
                'post_name' => $slug,
            ]);
            
            if ($page_id && isset($page_data['template'])) {
                update_post_meta($page_id, '_wp_page_template', $page_data['template']);
            }
        }
    }
}

// Hook theme activation
add_action('after_switch_theme', 'aqualuxe_theme_activation');

/**
 * Custom excerpt length
 */
function aqualuxe_excerpt_length($length) {
    return 25;
}
add_filter('excerpt_length', 'aqualuxe_excerpt_length');

/**
 * Custom excerpt more
 */
function aqualuxe_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'aqualuxe_excerpt_more');

/**
 * Body classes
 */
function aqualuxe_body_classes($classes) {
    // Add class for WooCommerce
    if (aqualuxe_is_woocommerce_active()) {
        $classes[] = 'woocommerce-active';
    }
    
    // Add class for dark mode
    if (aqualuxe_is_dark_mode()) {
        $classes[] = 'dark-mode-enabled';
    }
    
    // Add class for mobile
    if (wp_is_mobile()) {
        $classes[] = 'mobile-device';
    }
    
    return $classes;
}
add_filter('body_class', 'aqualuxe_body_classes');
