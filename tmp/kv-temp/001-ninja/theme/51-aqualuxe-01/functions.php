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
define('AQUALUXE_ASSETS_URI', trailingslashit(AQUALUXE_URI . 'assets/dist'));

// Prevent direct access to this file
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AquaLuxe Theme Class
 * 
 * Main theme class that initializes all theme functionality
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
        // Check if WooCommerce is active
        $this->is_woocommerce_active = class_exists('WooCommerce');

        // Load theme files
        $this->load_dependencies();

        // Setup theme
        add_action('after_setup_theme', array($this, 'setup'));

        // Register assets
        add_action('wp_enqueue_scripts', array($this, 'register_assets'));

        // Register widget areas
        add_action('widgets_init', array($this, 'register_sidebars'));

        // Add body classes
        add_filter('body_class', array($this, 'body_classes'));
    }

    /**
     * Load theme dependencies
     */
    private function load_dependencies() {
        // Include core theme files
        require_once AQUALUXE_DIR . 'inc/helpers.php';
        require_once AQUALUXE_DIR . 'inc/template-functions.php';
        require_once AQUALUXE_DIR . 'inc/template-hooks.php';
        require_once AQUALUXE_DIR . 'inc/template-tags.php';
        require_once AQUALUXE_DIR . 'inc/customizer.php';
        require_once AQUALUXE_DIR . 'inc/class-aqualuxe-walker-nav-menu.php';
        
        // Include multilingual support
        require_once AQUALUXE_DIR . 'inc/multilingual.php';
        
        // Include multi-currency support
        require_once AQUALUXE_DIR . 'inc/multi-currency.php';
        
        // Include dark mode functionality
        require_once AQUALUXE_DIR . 'inc/dark-mode.php';
        
        // Include demo content importer
        require_once AQUALUXE_DIR . 'inc/demo-importer.php';
        
        // Include WooCommerce specific functionality if WooCommerce is active
        if ($this->is_woocommerce_active) {
            require_once AQUALUXE_DIR . 'inc/woocommerce/woocommerce.php';
            require_once AQUALUXE_DIR . 'inc/woocommerce/woocommerce-template-hooks.php';
            require_once AQUALUXE_DIR . 'inc/woocommerce/woocommerce-template-functions.php';
            require_once AQUALUXE_DIR . 'inc/woocommerce/class-aqualuxe-woocommerce.php';
            require_once AQUALUXE_DIR . 'inc/woocommerce/wishlist.php';
            require_once AQUALUXE_DIR . 'inc/woocommerce/quick-view.php';
            require_once AQUALUXE_DIR . 'inc/woocommerce/advanced-filters.php';
        }
        
        // Include multivendor support
        require_once AQUALUXE_DIR . 'inc/multivendor.php';
        
        // Include multitenant support
        require_once AQUALUXE_DIR . 'inc/multitenant.php';
    }

    /**
     * Sets up theme defaults and registers support for various WordPress features.
     */
    public function setup() {
        // Load text domain for translation
        load_theme_textdomain('aqualuxe', AQUALUXE_DIR . 'languages');

        // Add default posts and comments RSS feed links to head
        add_theme_support('automatic-feed-links');

        // Let WordPress manage the document title
        add_theme_support('title-tag');

        // Enable support for Post Thumbnails on posts and pages
        add_theme_support('post-thumbnails');

        // Set default thumbnail size
        set_post_thumbnail_size(1200, 9999);

        // Register navigation menus
        register_nav_menus(array(
            'primary' => esc_html__('Primary Menu', 'aqualuxe'),
            'secondary' => esc_html__('Secondary Menu', 'aqualuxe'),
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

        // Add support for custom background
        add_theme_support('custom-background', array(
            'default-color' => 'ffffff',
        ));

        // Add support for custom header
        add_theme_support('custom-header', array(
            'default-image' => '',
            'width' => 1920,
            'height' => 500,
            'flex-width' => true,
            'flex-height' => true,
        ));

        // Add support for WooCommerce if it's active
        if ($this->is_woocommerce_active) {
            $this->setup_woocommerce_support();
        }
    }

    /**
     * Setup WooCommerce support
     */
    private function setup_woocommerce_support() {
        add_theme_support('woocommerce');
        add_theme_support('wc-product-gallery-zoom');
        add_theme_support('wc-product-gallery-lightbox');
        add_theme_support('wc-product-gallery-slider');
    }

    /**
     * Register and enqueue scripts and styles
     */
    public function register_assets() {
        // Get the mix manifest file
        $mix_manifest = $this->get_mix_manifest();

        // Register and enqueue styles
        wp_enqueue_style(
            'aqualuxe-styles', 
            AQUALUXE_ASSETS_URI . 'css/main' . $this->get_asset_version('css/main.css', $mix_manifest) . '.css', 
            array(), 
            AQUALUXE_VERSION
        );

        // Register and enqueue scripts
        wp_enqueue_script(
            'aqualuxe-scripts', 
            AQUALUXE_ASSETS_URI . 'js/main' . $this->get_asset_version('js/main.js', $mix_manifest) . '.js', 
            array('jquery'), 
            AQUALUXE_VERSION, 
            true
        );

        // Add dark mode script
        wp_enqueue_script(
            'aqualuxe-dark-mode', 
            AQUALUXE_ASSETS_URI . 'js/dark-mode' . $this->get_asset_version('js/dark-mode.js', $mix_manifest) . '.js', 
            array('jquery'), 
            AQUALUXE_VERSION, 
            true
        );

        // Localize script for AJAX and translations
        wp_localize_script('aqualuxe-scripts', 'aqualuxeSettings', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe-nonce'),
            'isWooCommerceActive' => $this->is_woocommerce_active,
            'themeUri' => AQUALUXE_URI,
            'i18n' => array(
                'addToCart' => esc_html__('Add to cart', 'aqualuxe'),
                'viewCart' => esc_html__('View cart', 'aqualuxe'),
                'addToWishlist' => esc_html__('Add to wishlist', 'aqualuxe'),
                'removeFromWishlist' => esc_html__('Remove from wishlist', 'aqualuxe'),
                'quickView' => esc_html__('Quick view', 'aqualuxe'),
                'loadMore' => esc_html__('Load more', 'aqualuxe'),
                'noMoreProducts' => esc_html__('No more products to load', 'aqualuxe'),
                'darkMode' => esc_html__('Dark Mode', 'aqualuxe'),
                'lightMode' => esc_html__('Light Mode', 'aqualuxe'),
            ),
        ));

        // Add comment reply script on single posts
        if (is_singular() && comments_open() && get_option('thread_comments')) {
            wp_enqueue_script('comment-reply');
        }

        // Add WooCommerce specific scripts if active
        if ($this->is_woocommerce_active) {
            wp_enqueue_script(
                'aqualuxe-woocommerce', 
                AQUALUXE_ASSETS_URI . 'js/woocommerce' . $this->get_asset_version('js/woocommerce.js', $mix_manifest) . '.js', 
                array('jquery'), 
                AQUALUXE_VERSION, 
                true
            );
        }
    }

    /**
     * Get the mix manifest file
     * 
     * @return array
     */
    private function get_mix_manifest() {
        $manifest_path = AQUALUXE_DIR . 'assets/dist/mix-manifest.json';
        
        if (file_exists($manifest_path)) {
            return json_decode(file_get_contents($manifest_path), true);
        }
        
        return array();
    }

    /**
     * Get asset version from mix manifest
     * 
     * @param string $asset
     * @param array $manifest
     * @return string
     */
    private function get_asset_version($asset, $manifest) {
        if (isset($manifest['/' . $asset])) {
            return str_replace('.js', '', str_replace('.css', '', $manifest['/' . $asset]));
        }
        
        return '';
    }

    /**
     * Register widget areas
     */
    public function register_sidebars() {
        register_sidebar(array(
            'name'          => esc_html__('Blog Sidebar', 'aqualuxe'),
            'id'            => 'sidebar-1',
            'description'   => esc_html__('Add widgets here to appear in your blog sidebar.', 'aqualuxe'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        ));

        register_sidebar(array(
            'name'          => esc_html__('Shop Sidebar', 'aqualuxe'),
            'id'            => 'sidebar-shop',
            'description'   => esc_html__('Add widgets here to appear in your shop sidebar.', 'aqualuxe'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        ));

        register_sidebar(array(
            'name'          => esc_html__('Footer Widget Area 1', 'aqualuxe'),
            'id'            => 'footer-1',
            'description'   => esc_html__('Add widgets here to appear in footer column 1.', 'aqualuxe'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        ));

        register_sidebar(array(
            'name'          => esc_html__('Footer Widget Area 2', 'aqualuxe'),
            'id'            => 'footer-2',
            'description'   => esc_html__('Add widgets here to appear in footer column 2.', 'aqualuxe'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        ));

        register_sidebar(array(
            'name'          => esc_html__('Footer Widget Area 3', 'aqualuxe'),
            'id'            => 'footer-3',
            'description'   => esc_html__('Add widgets here to appear in footer column 3.', 'aqualuxe'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        ));

        register_sidebar(array(
            'name'          => esc_html__('Footer Widget Area 4', 'aqualuxe'),
            'id'            => 'footer-4',
            'description'   => esc_html__('Add widgets here to appear in footer column 4.', 'aqualuxe'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        ));
    }

    /**
     * Add custom classes to the body
     * 
     * @param array $classes
     * @return array
     */
    public function body_classes($classes) {
        // Add a class if WooCommerce is active
        if ($this->is_woocommerce_active) {
            $classes[] = 'woocommerce-active';
        } else {
            $classes[] = 'woocommerce-inactive';
        }

        // Add a class for dark mode
        if (aqualuxe_is_dark_mode()) {
            $classes[] = 'dark-mode';
        }

        // Add a class for the current language
        $current_lang = aqualuxe_get_current_language();
        if ($current_lang) {
            $classes[] = 'lang-' . $current_lang;
        }

        // Add a class for the current currency
        $current_currency = aqualuxe_get_current_currency();
        if ($current_currency) {
            $classes[] = 'currency-' . $current_currency;
        }

        return $classes;
    }
}

// Initialize the theme
AquaLuxe_Theme::instance();