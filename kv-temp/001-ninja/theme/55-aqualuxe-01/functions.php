<?php
/**
 * AquaLuxe Theme Functions
 *
 * This file sets up the theme by registering support for various features,
 * enqueuing scripts and styles, and including necessary files.
 *
 * @package AquaLuxe
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define theme constants
define('AQUALUXE_VERSION', '1.0.0');
define('AQUALUXE_DIR', get_template_directory());
define('AQUALUXE_URI', get_template_directory_uri());
define('AQUALUXE_ASSETS_URI', AQUALUXE_URI . '/assets/dist');
define('AQUALUXE_INC_DIR', AQUALUXE_DIR . '/inc');
define('AQUALUXE_CORE_DIR', AQUALUXE_DIR . '/core');
define('AQUALUXE_MODULES_DIR', AQUALUXE_DIR . '/modules');
define('AQUALUXE_TEMPLATES_DIR', AQUALUXE_DIR . '/templates');

/**
 * AquaLuxe Theme Class
 * 
 * Main theme class that initializes the theme.
 */
final class AquaLuxe_Theme {
    /**
     * Instance of the class
     *
     * @var AquaLuxe_Theme
     */
    private static $instance = null;

    /**
     * Theme modules
     *
     * @var array
     */
    public $modules = [];

    /**
     * Get instance of the class
     *
     * @return AquaLuxe_Theme
     */
    public static function get_instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        // Load core files
        $this->load_core_files();
        
        // Set up theme
        add_action('after_setup_theme', [$this, 'setup']);
        
        // Register widget areas
        add_action('widgets_init', [$this, 'widgets_init']);
        
        // Enqueue scripts and styles
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
        
        // Admin scripts and styles
        add_action('admin_enqueue_scripts', [$this, 'admin_enqueue_scripts']);
        
        // Load modules
        add_action('init', [$this, 'load_modules'], 5);
        
        // WooCommerce integration
        $this->woocommerce_integration();
    }

    /**
     * Load core files
     */
    private function load_core_files() {
        // Load helper functions
        require_once AQUALUXE_CORE_DIR . '/functions/helpers.php';
        require_once AQUALUXE_CORE_DIR . '/functions/template-functions.php';
        require_once AQUALUXE_CORE_DIR . '/functions/template-hooks.php';
        
        // Load classes
        require_once AQUALUXE_CORE_DIR . '/classes/class-aqualuxe-assets.php';
        require_once AQUALUXE_CORE_DIR . '/classes/class-aqualuxe-module.php';
        require_once AQUALUXE_CORE_DIR . '/classes/class-aqualuxe-customizer.php';
        require_once AQUALUXE_CORE_DIR . '/classes/class-aqualuxe-walker-nav-menu.php';
        
        // Load hooks
        require_once AQUALUXE_CORE_DIR . '/hooks/actions.php';
        require_once AQUALUXE_CORE_DIR . '/hooks/filters.php';
        
        // Load customizer
        require_once AQUALUXE_CORE_DIR . '/customizer/customizer.php';
    }

    /**
     * Theme setup
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
        add_image_size('aqualuxe-card', 600, 400, true);
        add_image_size('aqualuxe-thumbnail', 300, 300, true);
        
        // Register navigation menus
        register_nav_menus([
            'primary' => esc_html__('Primary Menu', 'aqualuxe'),
            'footer' => esc_html__('Footer Menu', 'aqualuxe'),
            'social' => esc_html__('Social Menu', 'aqualuxe'),
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
        
        // Add theme support for selective refresh for widgets
        add_theme_support('customize-selective-refresh-widgets');
        
        // Add support for editor styles
        add_theme_support('editor-styles');
        
        // Enqueue editor styles
        add_editor_style('assets/dist/css/editor-style.css');
        
        // Add support for responsive embeds
        add_theme_support('responsive-embeds');
        
        // Add support for custom logo
        add_theme_support('custom-logo', [
            'height' => 100,
            'width' => 400,
            'flex-height' => true,
            'flex-width' => true,
        ]);
        
        // Add support for full and wide align images
        add_theme_support('align-wide');
        
        // Add support for custom background
        add_theme_support('custom-background', [
            'default-color' => 'ffffff',
        ]);
        
        // Add support for custom header
        add_theme_support('custom-header', [
            'default-image' => '',
            'width' => 1920,
            'height' => 500,
            'flex-height' => true,
            'flex-width' => true,
            'header-text' => false,
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
        
        // Add support for block styles
        add_theme_support('wp-block-styles');
        
        // Add support for custom line height
        add_theme_support('custom-line-height');
        
        // Add support for custom spacing
        add_theme_support('custom-spacing');
        
        // Add support for custom units
        add_theme_support('custom-units');
        
        // Add support for editor color palette
        add_theme_support('editor-color-palette', [
            [
                'name' => esc_html__('Primary', 'aqualuxe'),
                'slug' => 'primary',
                'color' => '#0072B5',
            ],
            [
                'name' => esc_html__('Primary Light', 'aqualuxe'),
                'slug' => 'primary-light',
                'color' => '#0090E1',
            ],
            [
                'name' => esc_html__('Primary Dark', 'aqualuxe'),
                'slug' => 'primary-dark',
                'color' => '#005A8E',
            ],
            [
                'name' => esc_html__('Secondary', 'aqualuxe'),
                'slug' => 'secondary',
                'color' => '#00B2A9',
            ],
            [
                'name' => esc_html__('Secondary Light', 'aqualuxe'),
                'slug' => 'secondary-light',
                'color' => '#00D6CC',
            ],
            [
                'name' => esc_html__('Secondary Dark', 'aqualuxe'),
                'slug' => 'secondary-dark',
                'color' => '#008E87',
            ],
            [
                'name' => esc_html__('Accent', 'aqualuxe'),
                'slug' => 'accent',
                'color' => '#F8C630',
            ],
            [
                'name' => esc_html__('Accent Light', 'aqualuxe'),
                'slug' => 'accent-light',
                'color' => '#FFDA6A',
            ],
            [
                'name' => esc_html__('Accent Dark', 'aqualuxe'),
                'slug' => 'accent-dark',
                'color' => '#D9A400',
            ],
            [
                'name' => esc_html__('Luxe', 'aqualuxe'),
                'slug' => 'luxe',
                'color' => '#2C3E50',
            ],
            [
                'name' => esc_html__('Luxe Light', 'aqualuxe'),
                'slug' => 'luxe-light',
                'color' => '#3E5871',
            ],
            [
                'name' => esc_html__('Luxe Dark', 'aqualuxe'),
                'slug' => 'luxe-dark',
                'color' => '#1A2530',
            ],
            [
                'name' => esc_html__('White', 'aqualuxe'),
                'slug' => 'white',
                'color' => '#FFFFFF',
            ],
            [
                'name' => esc_html__('Black', 'aqualuxe'),
                'slug' => 'black',
                'color' => '#000000',
            ],
        ]);
        
        // Add support for editor font sizes
        add_theme_support('editor-font-sizes', [
            [
                'name' => esc_html__('Small', 'aqualuxe'),
                'size' => 14,
                'slug' => 'small',
            ],
            [
                'name' => esc_html__('Normal', 'aqualuxe'),
                'size' => 16,
                'slug' => 'normal',
            ],
            [
                'name' => esc_html__('Medium', 'aqualuxe'),
                'size' => 20,
                'slug' => 'medium',
            ],
            [
                'name' => esc_html__('Large', 'aqualuxe'),
                'size' => 24,
                'slug' => 'large',
            ],
            [
                'name' => esc_html__('Extra Large', 'aqualuxe'),
                'size' => 32,
                'slug' => 'extra-large',
            ],
            [
                'name' => esc_html__('Huge', 'aqualuxe'),
                'size' => 48,
                'slug' => 'huge',
            ],
        ]);
    }

    /**
     * Register widget areas
     */
    public function widgets_init() {
        register_sidebar([
            'name' => esc_html__('Sidebar', 'aqualuxe'),
            'id' => 'sidebar-1',
            'description' => esc_html__('Add widgets here to appear in your sidebar.', 'aqualuxe'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ]);
        
        register_sidebar([
            'name' => esc_html__('Footer 1', 'aqualuxe'),
            'id' => 'footer-1',
            'description' => esc_html__('Add widgets here to appear in footer column 1.', 'aqualuxe'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ]);
        
        register_sidebar([
            'name' => esc_html__('Footer 2', 'aqualuxe'),
            'id' => 'footer-2',
            'description' => esc_html__('Add widgets here to appear in footer column 2.', 'aqualuxe'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ]);
        
        register_sidebar([
            'name' => esc_html__('Footer 3', 'aqualuxe'),
            'id' => 'footer-3',
            'description' => esc_html__('Add widgets here to appear in footer column 3.', 'aqualuxe'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ]);
        
        register_sidebar([
            'name' => esc_html__('Footer 4', 'aqualuxe'),
            'id' => 'footer-4',
            'description' => esc_html__('Add widgets here to appear in footer column 4.', 'aqualuxe'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ]);
        
        // Shop sidebar (only if WooCommerce is active)
        if (aqualuxe_is_woocommerce_active()) {
            register_sidebar([
                'name' => esc_html__('Shop Sidebar', 'aqualuxe'),
                'id' => 'shop-sidebar',
                'description' => esc_html__('Add widgets here to appear in your shop sidebar.', 'aqualuxe'),
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<h3 class="widget-title">',
                'after_title' => '</h3>',
            ]);
        }
    }

    /**
     * Enqueue scripts and styles
     */
    public function enqueue_scripts() {
        // Get the asset manifest
        $manifest = aqualuxe_get_asset_manifest();
        
        // Enqueue Google Fonts
        wp_enqueue_style(
            'aqualuxe-google-fonts',
            'https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&family=JetBrains+Mono&display=swap',
            [],
            AQUALUXE_VERSION
        );
        
        // Enqueue main stylesheet
        wp_enqueue_style(
            'aqualuxe-style',
            aqualuxe_get_asset_url('css/main.css', $manifest),
            [],
            AQUALUXE_VERSION
        );
        
        // Enqueue Tailwind CSS
        wp_enqueue_style(
            'aqualuxe-tailwind',
            aqualuxe_get_asset_url('css/tailwind.css', $manifest),
            [],
            AQUALUXE_VERSION
        );
        
        // Enqueue WooCommerce styles if active
        if (aqualuxe_is_woocommerce_active()) {
            wp_enqueue_style(
                'aqualuxe-woocommerce',
                aqualuxe_get_asset_url('css/woocommerce.css', $manifest),
                [],
                AQUALUXE_VERSION
            );
        }
        
        // Enqueue main script
        wp_enqueue_script(
            'aqualuxe-script',
            aqualuxe_get_asset_url('js/app.js', $manifest),
            ['jquery'],
            AQUALUXE_VERSION,
            true
        );
        
        // Add script data
        wp_localize_script('aqualuxe-script', 'aqualuxeData', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'themeUrl' => AQUALUXE_URI,
            'assetsUrl' => AQUALUXE_ASSETS_URI,
            'nonce' => wp_create_nonce('aqualuxe-nonce'),
            'isRtl' => is_rtl(),
            'isWooCommerce' => aqualuxe_is_woocommerce_active(),
            'isMobile' => wp_is_mobile(),
            'i18n' => [
                'searchPlaceholder' => esc_html__('Search...', 'aqualuxe'),
                'searchNoResults' => esc_html__('No results found', 'aqualuxe'),
                'menuToggle' => esc_html__('Toggle Menu', 'aqualuxe'),
                'cartEmpty' => esc_html__('Your cart is empty', 'aqualuxe'),
                'addToCart' => esc_html__('Add to Cart', 'aqualuxe'),
                'addingToCart' => esc_html__('Adding...', 'aqualuxe'),
                'addedToCart' => esc_html__('Added to Cart', 'aqualuxe'),
                'viewCart' => esc_html__('View Cart', 'aqualuxe'),
                'checkout' => esc_html__('Checkout', 'aqualuxe'),
                'lightMode' => esc_html__('Light Mode', 'aqualuxe'),
                'darkMode' => esc_html__('Dark Mode', 'aqualuxe'),
            ],
        ]);
        
        // Add comment-reply script
        if (is_singular() && comments_open() && get_option('thread_comments')) {
            wp_enqueue_script('comment-reply');
        }
    }

    /**
     * Enqueue admin scripts and styles
     */
    public function admin_enqueue_scripts() {
        // Get the asset manifest
        $manifest = aqualuxe_get_asset_manifest();
        
        // Enqueue admin styles
        wp_enqueue_style(
            'aqualuxe-admin-style',
            aqualuxe_get_asset_url('css/admin.css', $manifest),
            [],
            AQUALUXE_VERSION
        );
        
        // Enqueue admin script
        wp_enqueue_script(
            'aqualuxe-admin-script',
            aqualuxe_get_asset_url('js/admin.js', $manifest),
            ['jquery'],
            AQUALUXE_VERSION,
            true
        );
        
        // Add admin script data
        wp_localize_script('aqualuxe-admin-script', 'aqualuxeAdminData', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'themeUrl' => AQUALUXE_URI,
            'assetsUrl' => AQUALUXE_ASSETS_URI,
            'nonce' => wp_create_nonce('aqualuxe-admin-nonce'),
        ]);
    }

    /**
     * Load modules
     */
    public function load_modules() {
        // Get all module directories
        $module_dirs = glob(AQUALUXE_MODULES_DIR . '/*', GLOB_ONLYDIR);
        
        foreach ($module_dirs as $module_dir) {
            $module_name = basename($module_dir);
            $module_file = $module_dir . '/module.php';
            
            // Skip if module file doesn't exist
            if (!file_exists($module_file)) {
                continue;
            }
            
            // Include module file
            require_once $module_file;
            
            // Get module class name
            $class_name = 'AquaLuxe_Module_' . str_replace('-', '_', $module_name);
            
            // Skip if class doesn't exist
            if (!class_exists($class_name)) {
                continue;
            }
            
            // Check if module is enabled
            $enabled = apply_filters('aqualuxe_module_enabled_' . $module_name, true);
            
            if ($enabled) {
                // Initialize module
                $this->modules[$module_name] = new $class_name();
            }
        }
        
        // Allow modules to initialize after all modules are loaded
        do_action('aqualuxe_modules_loaded');
    }

    /**
     * WooCommerce integration
     */
    private function woocommerce_integration() {
        // Check if WooCommerce is active
        if (!aqualuxe_is_woocommerce_active()) {
            return;
        }
        
        // Include WooCommerce compatibility file
        require_once AQUALUXE_CORE_DIR . '/functions/woocommerce.php';
        
        // Add theme support for WooCommerce
        add_theme_support('woocommerce');
        
        // Add support for WooCommerce features
        add_theme_support('wc-product-gallery-zoom');
        add_theme_support('wc-product-gallery-lightbox');
        add_theme_support('wc-product-gallery-slider');
    }
}

// Initialize the theme
AquaLuxe_Theme::get_instance();

/**
 * Check if WooCommerce is active
 *
 * @return bool
 */
function aqualuxe_is_woocommerce_active() {
    return class_exists('WooCommerce');
}

/**
 * Get asset URL from manifest
 *
 * @param string $path
 * @param array $manifest
 * @return string
 */
function aqualuxe_get_asset_url($path, $manifest = null) {
    if (is_null($manifest)) {
        $manifest = aqualuxe_get_asset_manifest();
    }
    
    $asset_path = isset($manifest[$path]) ? $manifest[$path] : $path;
    
    return AQUALUXE_ASSETS_URI . '/' . $asset_path;
}

/**
 * Get asset manifest
 *
 * @return array
 */
function aqualuxe_get_asset_manifest() {
    static $manifest = null;
    
    if (is_null($manifest)) {
        $manifest_path = AQUALUXE_DIR . '/assets/dist/mix-manifest.json';
        
        if (file_exists($manifest_path)) {
            $manifest = json_decode(file_get_contents($manifest_path), true);
            
            // Remove leading slash from keys
            if (is_array($manifest)) {
                $manifest = array_combine(
                    array_map(function($key) {
                        return ltrim($key, '/');
                    }, array_keys($manifest)),
                    array_map(function($value) {
                        return ltrim($value, '/');
                    }, array_values($manifest))
                );
            } else {
                $manifest = [];
            }
        } else {
            $manifest = [];
        }
    }
    
    return $manifest;
}