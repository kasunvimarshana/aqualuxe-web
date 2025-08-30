<?php
/**
 * AquaLuxe Theme functions and definitions
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Define theme constants
define('AQUALUXE_VERSION', '1.0.0');
define('AQUALUXE_DIR', get_template_directory());
define('AQUALUXE_URI', get_template_directory_uri());
define('AQUALUXE_ASSETS_URI', AQUALUXE_URI . '/assets/dist');

/**
 * AquaLuxe Theme Class
 * Main theme class that initializes everything
 */
final class AquaLuxe_Theme {
    /**
     * Instance of this class
     *
     * @var AquaLuxe_Theme
     */
    private static $instance = null;

    /**
     * Is WooCommerce active
     *
     * @var bool
     */
    public $is_woocommerce_active = false;

    /**
     * Get the singleton instance of this class
     *
     * @return AquaLuxe_Theme
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        // Check if WooCommerce is active
        $this->is_woocommerce_active = class_exists('WooCommerce');

        // Load required files
        $this->load_files();

        // Setup theme
        add_action('after_setup_theme', array($this, 'setup'));

        // Register assets
        add_action('wp_enqueue_scripts', array($this, 'register_assets'));

        // Register widget areas
        add_action('widgets_init', array($this, 'register_widget_areas'));

        // Add body classes
        add_filter('body_class', array($this, 'body_classes'));

        // Initialize WooCommerce if active
        if ($this->is_woocommerce_active) {
            $this->init_woocommerce();
        }
    }

    /**
     * Load required files
     */
    private function load_files() {
        // Helper functions
        require_once AQUALUXE_DIR . '/inc/helpers.php';

        // Theme setup
        require_once AQUALUXE_DIR . '/inc/setup.php';

        // Customizer
        require_once AQUALUXE_DIR . '/inc/customizer.php';

        // Template functions
        require_once AQUALUXE_DIR . '/inc/template-functions.php';
        require_once AQUALUXE_DIR . '/inc/template-hooks.php';

        // Custom post types and taxonomies
        require_once AQUALUXE_DIR . '/inc/post-types.php';

        // Widgets
        require_once AQUALUXE_DIR . '/inc/widgets.php';

        // Multi-language support
        require_once AQUALUXE_DIR . '/inc/multilingual.php';

        // Multi-currency support
        require_once AQUALUXE_DIR . '/inc/multi-currency.php';

        // Multi-vendor support
        require_once AQUALUXE_DIR . '/inc/multi-vendor.php';

        // Admin functions
        if (is_admin()) {
            require_once AQUALUXE_DIR . '/inc/admin/admin.php';
        }

        // WooCommerce functions if active
        if ($this->is_woocommerce_active) {
            require_once AQUALUXE_DIR . '/inc/woocommerce/woocommerce.php';
        }
    }

    /**
     * Setup theme
     */
    public function setup() {
        // Make theme available for translation
        load_theme_textdomain('aqualuxe', AQUALUXE_DIR . '/languages');

        // Add default posts and comments RSS feed links to head
        add_theme_support('automatic-feed-links');

        // Let WordPress manage the document title
        add_theme_support('title-tag');

        // Enable support for Post Thumbnails on posts and pages
        add_theme_support('post-thumbnails');

        // Set default thumbnail size
        set_post_thumbnail_size(1200, 9999);

        // Add custom image sizes
        add_image_size('aqualuxe-featured', 1600, 900, true);
        add_image_size('aqualuxe-product', 600, 600, true);
        add_image_size('aqualuxe-thumbnail', 400, 400, true);

        // Register navigation menus
        register_nav_menus(array(
            'primary' => esc_html__('Primary Menu', 'aqualuxe'),
            'footer' => esc_html__('Footer Menu', 'aqualuxe'),
            'social' => esc_html__('Social Menu', 'aqualuxe'),
        ));

        // Switch default core markup to output valid HTML5
        add_theme_support('html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script',
        ));

        // Set up the WordPress core custom background feature
        add_theme_support('custom-background', array(
            'default-color' => 'ffffff',
        ));

        // Add theme support for selective refresh for widgets
        add_theme_support('customize-selective-refresh-widgets');

        // Add support for custom logo
        add_theme_support('custom-logo', array(
            'height' => 250,
            'width' => 250,
            'flex-width' => true,
            'flex-height' => true,
        ));

        // Add support for full and wide align images
        add_theme_support('align-wide');

        // Add support for responsive embeds
        add_theme_support('responsive-embeds');

        // Add support for editor styles
        add_theme_support('editor-styles');

        // Add support for block styles
        add_theme_support('wp-block-styles');
    }

    /**
     * Register and enqueue assets
     */
    public function register_assets() {
        // Get the mix manifest
        $manifest_path = AQUALUXE_DIR . '/assets/dist/mix-manifest.json';
        $manifest = file_exists($manifest_path) ? json_decode(file_get_contents($manifest_path), true) : [];

        // Helper function to get versioned asset URL
        $get_asset = function($path) use ($manifest) {
            $versioned_path = isset($manifest[$path]) ? $manifest[$path] : $path;
            return AQUALUXE_ASSETS_URI . $versioned_path;
        };

        // Register and enqueue styles
        wp_enqueue_style('aqualuxe-main', $get_asset('/css/main.css'), array(), null);

        // Add WooCommerce styles if active
        if ($this->is_woocommerce_active && (is_woocommerce() || is_cart() || is_checkout() || is_account_page())) {
            wp_enqueue_style('aqualuxe-woocommerce', $get_asset('/css/woocommerce.css'), array('aqualuxe-main'), null);
        }

        // Register and enqueue scripts
        wp_enqueue_script('aqualuxe-app', $get_asset('/js/app.js'), array('jquery'), null, true);

        // Localize script
        wp_localize_script('aqualuxe-app', 'aqualuxeData', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'themeUri' => AQUALUXE_URI,
            'isWooCommerceActive' => $this->is_woocommerce_active,
            'i18n' => array(
                'searchPlaceholder' => esc_html__('Search...', 'aqualuxe'),
                'menuToggle' => esc_html__('Toggle Menu', 'aqualuxe'),
                'darkModeToggle' => esc_html__('Toggle Dark Mode', 'aqualuxe'),
            ),
        ));

        // Add comment reply script on single posts
        if (is_singular() && comments_open() && get_option('thread_comments')) {
            wp_enqueue_script('comment-reply');
        }
    }

    /**
     * Register widget areas
     */
    public function register_widget_areas() {
        register_sidebar(array(
            'name' => esc_html__('Sidebar', 'aqualuxe'),
            'id' => 'sidebar-1',
            'description' => esc_html__('Add widgets here to appear in your sidebar.', 'aqualuxe'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ));

        register_sidebar(array(
            'name' => esc_html__('Footer 1', 'aqualuxe'),
            'id' => 'footer-1',
            'description' => esc_html__('Add widgets here to appear in footer column 1.', 'aqualuxe'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h4 class="widget-title">',
            'after_title' => '</h4>',
        ));

        register_sidebar(array(
            'name' => esc_html__('Footer 2', 'aqualuxe'),
            'id' => 'footer-2',
            'description' => esc_html__('Add widgets here to appear in footer column 2.', 'aqualuxe'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h4 class="widget-title">',
            'after_title' => '</h4>',
        ));

        register_sidebar(array(
            'name' => esc_html__('Footer 3', 'aqualuxe'),
            'id' => 'footer-3',
            'description' => esc_html__('Add widgets here to appear in footer column 3.', 'aqualuxe'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h4 class="widget-title">',
            'after_title' => '</h4>',
        ));

        register_sidebar(array(
            'name' => esc_html__('Footer 4', 'aqualuxe'),
            'id' => 'footer-4',
            'description' => esc_html__('Add widgets here to appear in footer column 4.', 'aqualuxe'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h4 class="widget-title">',
            'after_title' => '</h4>',
        ));

        // WooCommerce specific widget areas
        if ($this->is_woocommerce_active) {
            register_sidebar(array(
                'name' => esc_html__('Shop Sidebar', 'aqualuxe'),
                'id' => 'shop-sidebar',
                'description' => esc_html__('Add widgets here to appear in your shop sidebar.', 'aqualuxe'),
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<h3 class="widget-title">',
                'after_title' => '</h3>',
            ));

            register_sidebar(array(
                'name' => esc_html__('Product Sidebar', 'aqualuxe'),
                'id' => 'product-sidebar',
                'description' => esc_html__('Add widgets here to appear in your product sidebar.', 'aqualuxe'),
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<h3 class="widget-title">',
                'after_title' => '</h3>',
            ));
        }
    }

    /**
     * Add custom body classes
     *
     * @param array $classes Body classes
     * @return array
     */
    public function body_classes($classes) {
        // Add a class if WooCommerce is active
        if ($this->is_woocommerce_active) {
            $classes[] = 'woocommerce-active';
        } else {
            $classes[] = 'woocommerce-inactive';
        }

        // Add class for dark mode
        if (isset($_COOKIE['aqualuxe_dark_mode']) && $_COOKIE['aqualuxe_dark_mode'] === 'true') {
            $classes[] = 'dark-mode';
        }

        // Add class for RTL languages
        if (is_rtl()) {
            $classes[] = 'rtl';
        }

        return $classes;
    }

    /**
     * Initialize WooCommerce specific functionality
     */
    private function init_woocommerce() {
        // Add theme support for WooCommerce
        add_theme_support('woocommerce');
        add_theme_support('wc-product-gallery-zoom');
        add_theme_support('wc-product-gallery-lightbox');
        add_theme_support('wc-product-gallery-slider');

        // Remove default WooCommerce styles
        add_filter('woocommerce_enqueue_styles', '__return_empty_array');
    }
}

// Initialize the theme
AquaLuxe_Theme::get_instance();