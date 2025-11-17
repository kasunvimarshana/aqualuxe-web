<?php
/**
 * AquaLuxe functions and definitions
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define theme constants
define('AQUALUXE_VERSION', '1.0.0');
define('AQUALUXE_DIR', get_template_directory());
define('AQUALUXE_URI', get_template_directory_uri());

/**
 * AquaLuxe Theme Class
 * 
 * Main theme class that initializes the theme
 */
final class AquaLuxe {
    /**
     * Singleton instance
     *
     * @var AquaLuxe
     */
    private static $instance = null;

    /**
     * Get singleton instance
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
        // Load theme files
        $this->load_files();

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

        // WooCommerce integration
        add_action('after_setup_theme', [$this, 'woocommerce_setup']);
        
        // Register REST API endpoints
        add_action('rest_api_init', [$this, 'register_rest_routes']);
    }

    /**
     * Load theme files
     */
    private function load_files() {
        // Core files
        require_once AQUALUXE_DIR . '/inc/helpers.php';
        require_once AQUALUXE_DIR . '/inc/template-functions.php';
        require_once AQUALUXE_DIR . '/inc/template-tags.php';
        
        // Theme features
        require_once AQUALUXE_DIR . '/inc/customizer.php';
        require_once AQUALUXE_DIR . '/inc/custom-post-types.php';
        require_once AQUALUXE_DIR . '/inc/custom-taxonomies.php';
        require_once AQUALUXE_DIR . '/inc/metaboxes.php';
        require_once AQUALUXE_DIR . '/inc/shortcodes.php';
        require_once AQUALUXE_DIR . '/inc/widgets.php';
        
        // WooCommerce integration
        if (class_exists('WooCommerce')) {
            require_once AQUALUXE_DIR . '/inc/woocommerce.php';
        }
        
        // Multilingual support
        require_once AQUALUXE_DIR . '/inc/multilingual.php';
        
        // Admin features
        if (is_admin()) {
            require_once AQUALUXE_DIR . '/inc/admin/admin.php';
            require_once AQUALUXE_DIR . '/inc/admin/demo-importer.php';
            require_once AQUALUXE_DIR . '/inc/admin/theme-options.php';
        }
        
        // REST API
        require_once AQUALUXE_DIR . '/inc/rest-api.php';
    }

    /**
     * Theme setup
     */
    public function setup() {
        // Load theme text domain
        load_theme_textdomain('aqualuxe', AQUALUXE_DIR . '/languages');
        
        // Add default posts and comments RSS feed links to head
        add_theme_support('automatic-feed-links');
        
        // Let WordPress manage the document title
        add_theme_support('title-tag');
        
        // Enable support for Post Thumbnails on posts and pages
        add_theme_support('post-thumbnails');
        
        // Set post thumbnail size
        set_post_thumbnail_size(1200, 9999);
        
        // Add custom image sizes
        add_image_size('aqualuxe-featured', 1600, 900, true);
        add_image_size('aqualuxe-card', 600, 400, true);
        add_image_size('aqualuxe-thumbnail', 300, 300, true);
        
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
        
        // Enqueue editor styles
        add_editor_style('assets/dist/css/editor-style.css');
        
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
    }

    /**
     * Register theme assets
     */
    public function register_assets() {
        // Enqueue styles
        wp_enqueue_style(
            'aqualuxe-styles',
            AQUALUXE_URI . '/assets/dist/css/app.css',
            [],
            AQUALUXE_VERSION
        );
        
        // Enqueue scripts
        wp_enqueue_script(
            'aqualuxe-scripts',
            AQUALUXE_URI . '/assets/dist/js/app.js',
            [],
            AQUALUXE_VERSION,
            true
        );
        
        // Localize script
        wp_localize_script('aqualuxe-scripts', 'aqualuxeData', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe-nonce'),
            'homeUrl' => home_url(),
            'isLoggedIn' => is_user_logged_in(),
            'currency' => get_woocommerce_currency_symbol(),
        ]);
        
        // Add comment reply script
        if (is_singular() && comments_open() && get_option('thread_comments')) {
            wp_enqueue_script('comment-reply');
        }
    }

    /**
     * Register admin assets
     */
    public function register_admin_assets() {
        // Enqueue admin styles
        wp_enqueue_style(
            'aqualuxe-admin-styles',
            AQUALUXE_URI . '/assets/dist/css/admin.css',
            [],
            AQUALUXE_VERSION
        );
        
        // Enqueue admin scripts
        wp_enqueue_script(
            'aqualuxe-admin-scripts',
            AQUALUXE_URI . '/assets/dist/js/admin.js',
            ['jquery', 'wp-color-picker'],
            AQUALUXE_VERSION,
            true
        );
        
        // Localize admin script
        wp_localize_script('aqualuxe-admin-scripts', 'aqualuxeAdminData', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe-admin-nonce'),
        ]);
    }

    /**
     * Register navigation menus
     */
    public function register_menus() {
        register_nav_menus([
            'primary' => esc_html__('Primary Menu', 'aqualuxe'),
            'footer' => esc_html__('Footer Menu', 'aqualuxe'),
            'social' => esc_html__('Social Links Menu', 'aqualuxe'),
            'mobile' => esc_html__('Mobile Menu', 'aqualuxe'),
        ]);
    }

    /**
     * Register widget areas
     */
    public function register_sidebars() {
        register_sidebar([
            'name'          => esc_html__('Blog Sidebar', 'aqualuxe'),
            'id'            => 'sidebar-1',
            'description'   => esc_html__('Add widgets here to appear in your blog sidebar.', 'aqualuxe'),
            'before_widget' => '<div id="%1$s" class="widget %2$s mb-8">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title text-xl font-bold mb-4">',
            'after_title'   => '</h3>',
        ]);
        
        register_sidebar([
            'name'          => esc_html__('Shop Sidebar', 'aqualuxe'),
            'id'            => 'sidebar-shop',
            'description'   => esc_html__('Add widgets here to appear in your shop sidebar.', 'aqualuxe'),
            'before_widget' => '<div id="%1$s" class="widget %2$s mb-8">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title text-xl font-bold mb-4">',
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
        
        // Add support for post formats
        add_theme_support('post-formats', [
            'gallery',
            'image',
            'video',
            'audio',
            'quote',
            'link',
        ]);
        
        // Add support for align wide
        add_theme_support('align-wide');
        
        // Add support for custom colors
        add_theme_support('editor-color-palette', [
            [
                'name'  => esc_html__('Primary', 'aqualuxe'),
                'slug'  => 'primary',
                'color' => '#0077B6',
            ],
            [
                'name'  => esc_html__('Secondary', 'aqualuxe'),
                'slug'  => 'secondary',
                'color' => '#00B4D8',
            ],
            [
                'name'  => esc_html__('Accent', 'aqualuxe'),
                'slug'  => 'accent',
                'color' => '#FFD166',
            ],
            [
                'name'  => esc_html__('Dark', 'aqualuxe'),
                'slug'  => 'dark',
                'color' => '#023E8A',
            ],
            [
                'name'  => esc_html__('Light', 'aqualuxe'),
                'slug'  => 'light',
                'color' => '#CAF0F8',
            ],
            [
                'name'  => esc_html__('White', 'aqualuxe'),
                'slug'  => 'white',
                'color' => '#FFFFFF',
            ],
            [
                'name'  => esc_html__('Black', 'aqualuxe'),
                'slug'  => 'black',
                'color' => '#000000',
            ],
        ]);
    }

    /**
     * WooCommerce setup
     */
    public function woocommerce_setup() {
        // Add theme support for WooCommerce
        add_theme_support('woocommerce');
        
        // Add support for WooCommerce product gallery features
        add_theme_support('wc-product-gallery-zoom');
        add_theme_support('wc-product-gallery-lightbox');
        add_theme_support('wc-product-gallery-slider');
        
        // Declare WooCommerce support
        add_theme_support('woocommerce', [
            'thumbnail_image_width' => 400,
            'single_image_width'    => 800,
            'product_grid'          => [
                'default_rows'    => 3,
                'min_rows'        => 2,
                'max_rows'        => 8,
                'default_columns' => 4,
                'min_columns'     => 2,
                'max_columns'     => 5,
            ],
        ]);
    }

    /**
     * Register REST API endpoints
     */
    public function register_rest_routes() {
        // Register custom REST API endpoints
        register_rest_route('aqualuxe/v1', '/product/(?P<id>\d+)', [
            'methods'  => 'GET',
            'callback' => 'aqualuxe_rest_get_product',
            'permission_callback' => '__return_true',
        ]);
        
        register_rest_route('aqualuxe/v1', '/wishlist', [
            'methods'  => 'POST',
            'callback' => 'aqualuxe_rest_update_wishlist',
            'permission_callback' => function() {
                return is_user_logged_in();
            },
        ]);
        
        register_rest_route('aqualuxe/v1', '/newsletter', [
            'methods'  => 'POST',
            'callback' => 'aqualuxe_rest_newsletter_subscribe',
            'permission_callback' => '__return_true',
        ]);
    }
}

// Initialize the theme
AquaLuxe::instance();