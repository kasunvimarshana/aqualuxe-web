<?php
/**
 * AquaLuxe functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package AquaLuxe
 */

// Define theme constants
define('AQUALUXE_VERSION', '1.0.0');
define('AQUALUXE_DIR', trailingslashit(get_template_directory()));
define('AQUALUXE_URI', trailingslashit(get_template_directory_uri()));
define('AQUALUXE_ASSETS_URI', AQUALUXE_URI . 'assets/dist/');

// Prevent direct access to this file
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AquaLuxe Theme Setup
 */
class AquaLuxe_Theme {
    /**
     * Constructor
     */
    public function __construct() {
        // Core theme setup
        add_action('after_setup_theme', array($this, 'theme_setup'));
        
        // Register widget areas
        add_action('widgets_init', array($this, 'widgets_init'));
        
        // Enqueue scripts and styles
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        
        // Load required files
        $this->load_includes();
    }
    
    /**
     * Theme setup
     */
    public function theme_setup() {
        // Load text domain for translation
        load_theme_textdomain('aqualuxe', AQUALUXE_DIR . 'languages');
        
        // Add default posts and comments RSS feed links to head
        add_theme_support('automatic-feed-links');
        
        // Let WordPress manage the document title
        add_theme_support('title-tag');
        
        // Enable support for Post Thumbnails on posts and pages
        add_theme_support('post-thumbnails');
        
        // Set up image sizes
        add_image_size('aqualuxe-featured', 1200, 800, true);
        add_image_size('aqualuxe-product', 600, 600, true);
        add_image_size('aqualuxe-thumbnail', 300, 300, true);
        
        // Register navigation menus
        register_nav_menus(array(
            'primary' => esc_html__('Primary Menu', 'aqualuxe'),
            'footer' => esc_html__('Footer Menu', 'aqualuxe'),
            'mobile' => esc_html__('Mobile Menu', 'aqualuxe'),
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
        
        // Add theme support for selective refresh for widgets
        add_theme_support('customize-selective-refresh-widgets');
        
        // Add support for editor styles
        add_theme_support('editor-styles');
        
        // Add support for responsive embeds
        add_theme_support('responsive-embeds');
        
        // Add support for custom logo
        add_theme_support('custom-logo', array(
            'height' => 250,
            'width' => 250,
            'flex-width' => true,
            'flex-height' => true,
        ));
        
        // Add support for full and wide align images
        add_theme_support('align-wide');
        
        // Add support for custom color palette
        add_theme_support('editor-color-palette', array(
            array(
                'name' => esc_html__('Primary', 'aqualuxe'),
                'slug' => 'primary',
                'color' => '#0073aa',
            ),
            array(
                'name' => esc_html__('Secondary', 'aqualuxe'),
                'slug' => 'secondary',
                'color' => '#005177',
            ),
            array(
                'name' => esc_html__('Accent', 'aqualuxe'),
                'slug' => 'accent',
                'color' => '#00c6ff',
            ),
            array(
                'name' => esc_html__('Dark', 'aqualuxe'),
                'slug' => 'dark',
                'color' => '#111111',
            ),
            array(
                'name' => esc_html__('Light', 'aqualuxe'),
                'slug' => 'light',
                'color' => '#f8f9fa',
            ),
        ));
        
        // WooCommerce support
        $this->woocommerce_support();
    }
    
    /**
     * Register widget areas
     */
    public function widgets_init() {
        register_sidebar(array(
            'name' => esc_html__('Sidebar', 'aqualuxe'),
            'id' => 'sidebar-1',
            'description' => esc_html__('Add widgets here to appear in your sidebar.', 'aqualuxe'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget' => '</section>',
            'before_title' => '<h2 class="widget-title">',
            'after_title' => '</h2>',
        ));
        
        register_sidebar(array(
            'name' => esc_html__('Footer 1', 'aqualuxe'),
            'id' => 'footer-1',
            'description' => esc_html__('Add widgets here to appear in footer column 1.', 'aqualuxe'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ));
        
        register_sidebar(array(
            'name' => esc_html__('Footer 2', 'aqualuxe'),
            'id' => 'footer-2',
            'description' => esc_html__('Add widgets here to appear in footer column 2.', 'aqualuxe'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ));
        
        register_sidebar(array(
            'name' => esc_html__('Footer 3', 'aqualuxe'),
            'id' => 'footer-3',
            'description' => esc_html__('Add widgets here to appear in footer column 3.', 'aqualuxe'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ));
        
        register_sidebar(array(
            'name' => esc_html__('Footer 4', 'aqualuxe'),
            'id' => 'footer-4',
            'description' => esc_html__('Add widgets here to appear in footer column 4.', 'aqualuxe'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ));
        
        // Shop sidebar (only if WooCommerce is active)
        if ($this->is_woocommerce_active()) {
            register_sidebar(array(
                'name' => esc_html__('Shop Sidebar', 'aqualuxe'),
                'id' => 'shop-sidebar',
                'description' => esc_html__('Add widgets here to appear in your shop sidebar.', 'aqualuxe'),
                'before_widget' => '<section id="%1$s" class="widget %2$s">',
                'after_widget' => '</section>',
                'before_title' => '<h2 class="widget-title">',
                'after_title' => '</h2>',
            ));
        }
    }
    
    /**
     * Enqueue scripts and styles
     */
    public function enqueue_scripts() {
        // Get the asset manifest
        $manifest_path = AQUALUXE_DIR . 'assets/dist/mix-manifest.json';
        $manifest = file_exists($manifest_path) ? json_decode(file_get_contents($manifest_path), true) : [];
        
        // Helper function to get versioned asset path
        $get_asset = function($path) use ($manifest) {
            $versioned_path = isset($manifest[$path]) ? $manifest[$path] : $path;
            return AQUALUXE_ASSETS_URI . ltrim($versioned_path, '/');
        };
        
        // Enqueue main stylesheet
        wp_enqueue_style(
            'aqualuxe-style',
            $get_asset('/css/main.css'),
            array(),
            AQUALUXE_VERSION
        );
        
        // Enqueue main script
        wp_enqueue_script(
            'aqualuxe-script',
            $get_asset('/js/main.js'),
            array('jquery'),
            AQUALUXE_VERSION,
            true
        );
        
        // Add dark mode script
        wp_enqueue_script(
            'aqualuxe-dark-mode',
            $get_asset('/js/dark-mode.js'),
            array('jquery'),
            AQUALUXE_VERSION,
            true
        );
        
        // Localize script with theme data
        wp_localize_script('aqualuxe-script', 'aqualuxeData', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'themeUri' => AQUALUXE_URI,
            'isWooCommerceActive' => $this->is_woocommerce_active(),
            'i18n' => array(
                'addToCart' => esc_html__('Add to Cart', 'aqualuxe'),
                'viewCart' => esc_html__('View Cart', 'aqualuxe'),
                'addToWishlist' => esc_html__('Add to Wishlist', 'aqualuxe'),
                'removeFromWishlist' => esc_html__('Remove from Wishlist', 'aqualuxe'),
                'quickView' => esc_html__('Quick View', 'aqualuxe'),
                'loadMore' => esc_html__('Load More', 'aqualuxe'),
                'noMoreProducts' => esc_html__('No more products to load', 'aqualuxe'),
            ),
        ));
        
        // Add comment reply script
        if (is_singular() && comments_open() && get_option('thread_comments')) {
            wp_enqueue_script('comment-reply');
        }
    }
    
    /**
     * Load required files
     */
    private function load_includes() {
        // Include helper functions
        require_once AQUALUXE_DIR . 'inc/helpers.php';
        
        // Include template functions
        require_once AQUALUXE_DIR . 'inc/template-functions.php';
        
        // Include template tags
        require_once AQUALUXE_DIR . 'inc/template-tags.php';
        
        // Include customizer options
        require_once AQUALUXE_DIR . 'inc/customizer.php';
        
        // Include hooks
        require_once AQUALUXE_DIR . 'inc/hooks.php';
        
        // Include custom post types
        require_once AQUALUXE_DIR . 'inc/post-types.php';
        
        // Include custom taxonomies
        require_once AQUALUXE_DIR . 'inc/taxonomies.php';
        
        // Include multilingual support
        require_once AQUALUXE_DIR . 'inc/multilingual.php';
        
        // Include multi-currency support
        require_once AQUALUXE_DIR . 'inc/multi-currency.php';
        
        // Include WooCommerce functions if WooCommerce is active
        if ($this->is_woocommerce_active()) {
            require_once AQUALUXE_DIR . 'inc/woocommerce.php';
        }
        
        // Include fallbacks for when WooCommerce is not active
        // require_once AQUALUXE_DIR . 'inc/woocommerce-fallbacks.php';
    }
    
    /**
     * Add WooCommerce support
     */
    private function woocommerce_support() {
        // Add WooCommerce support
        add_theme_support('woocommerce');
        
        // Add product gallery features
        add_theme_support('wc-product-gallery-zoom');
        add_theme_support('wc-product-gallery-lightbox');
        add_theme_support('wc-product-gallery-slider');
    }
    
    /**
     * Check if WooCommerce is active
     *
     * @return bool
     */
    public function is_woocommerce_active() {
        return class_exists('WooCommerce');
    }
}

// Initialize the theme
new AquaLuxe_Theme();