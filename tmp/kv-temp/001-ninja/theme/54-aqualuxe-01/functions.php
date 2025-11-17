<?php
/**
 * AquaLuxe Theme functions and definitions
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
define('AQUALUXE_ASSETS_URI', AQUALUXE_URI . 'assets/dist/');
define('AQUALUXE_CORE_DIR', AQUALUXE_DIR . 'core/');
define('AQUALUXE_MODULES_DIR', AQUALUXE_DIR . 'modules/');
define('AQUALUXE_INC_DIR', AQUALUXE_DIR . 'inc/');
define('AQUALUXE_TEMPLATES_DIR', AQUALUXE_DIR . 'templates/');

/**
 * AquaLuxe Theme Class
 * 
 * Main theme class that bootstraps the theme functionality
 */
final class AquaLuxe_Theme {
    /**
     * Instance of this class
     *
     * @var AquaLuxe_Theme
     */
    private static $instance = null;

    /**
     * Active modules
     *
     * @var array
     */
    private $active_modules = [];

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
        // Load core files
        $this->load_core_files();
        
        // Initialize theme
        $this->init();
        
        // Load modules
        $this->load_modules();
    }

    /**
     * Load core files
     */
    private function load_core_files() {
        // Load autoloader
        require_once AQUALUXE_CORE_DIR . 'class-aqualuxe-autoloader.php';
        
        // Initialize autoloader
        new AquaLuxe_Autoloader();
        
        // Load core functions
        require_once AQUALUXE_CORE_DIR . 'functions.php';
        
        // Load helpers
        require_once AQUALUXE_CORE_DIR . 'helpers/template-helpers.php';
        require_once AQUALUXE_CORE_DIR . 'helpers/asset-helpers.php';
    }

    /**
     * Initialize theme
     */
    private function init() {
        // Setup theme
        add_action('after_setup_theme', [$this, 'setup_theme']);
        
        // Register widget areas
        add_action('widgets_init', [$this, 'register_widget_areas']);
        
        // Enqueue scripts and styles
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
        
        // Admin scripts and styles
        add_action('admin_enqueue_scripts', [$this, 'admin_assets']);
        
        // Initialize customizer
        add_action('customize_register', [$this, 'customize_register']);
    }

    /**
     * Setup theme
     */
    public function setup_theme() {
        // Load text domain
        load_theme_textdomain('aqualuxe', AQUALUXE_DIR . 'languages');
        
        // Add default posts and comments RSS feed links to head
        add_theme_support('automatic-feed-links');
        
        // Let WordPress manage the document title
        add_theme_support('title-tag');
        
        // Enable support for Post Thumbnails on posts and pages
        add_theme_support('post-thumbnails');
        
        // Add support for responsive embeds
        add_theme_support('responsive-embeds');
        
        // Add support for custom logo
        add_theme_support('custom-logo', [
            'height'      => 250,
            'width'       => 250,
            'flex-width'  => true,
            'flex-height' => true,
        ]);
        
        // Add support for editor styles
        add_theme_support('editor-styles');
        
        // Add support for HTML5 features
        add_theme_support('html5', [
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script',
        ]);
        
        // Add support for selective refresh for widgets
        add_theme_support('customize-selective-refresh-widgets');
        
        // Add support for full and wide align images
        add_theme_support('align-wide');
        
        // Add support for custom color palette
        add_theme_support('editor-color-palette', aqualuxe_get_color_palette());
        
        // Register nav menus
        register_nav_menus([
            'primary'   => esc_html__('Primary Menu', 'aqualuxe'),
            'secondary' => esc_html__('Secondary Menu', 'aqualuxe'),
            'footer'    => esc_html__('Footer Menu', 'aqualuxe'),
            'mobile'    => esc_html__('Mobile Menu', 'aqualuxe'),
        ]);
        
        // Set content width
        if (!isset($content_width)) {
            $content_width = 1200;
        }
        
        // Add image sizes
        add_image_size('aqualuxe-featured', 1200, 600, true);
        add_image_size('aqualuxe-thumbnail', 400, 300, true);
        add_image_size('aqualuxe-square', 600, 600, true);
    }

    /**
     * Register widget areas
     */
    public function register_widget_areas() {
        register_sidebar([
            'name'          => esc_html__('Sidebar', 'aqualuxe'),
            'id'            => 'sidebar-1',
            'description'   => esc_html__('Add widgets here to appear in your sidebar.', 'aqualuxe'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        ]);
        
        register_sidebar([
            'name'          => esc_html__('Footer 1', 'aqualuxe'),
            'id'            => 'footer-1',
            'description'   => esc_html__('Add widgets here to appear in footer column 1.', 'aqualuxe'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        ]);
        
        register_sidebar([
            'name'          => esc_html__('Footer 2', 'aqualuxe'),
            'id'            => 'footer-2',
            'description'   => esc_html__('Add widgets here to appear in footer column 2.', 'aqualuxe'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        ]);
        
        register_sidebar([
            'name'          => esc_html__('Footer 3', 'aqualuxe'),
            'id'            => 'footer-3',
            'description'   => esc_html__('Add widgets here to appear in footer column 3.', 'aqualuxe'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        ]);
        
        register_sidebar([
            'name'          => esc_html__('Footer 4', 'aqualuxe'),
            'id'            => 'footer-4',
            'description'   => esc_html__('Add widgets here to appear in footer column 4.', 'aqualuxe'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        ]);
    }

    /**
     * Enqueue scripts and styles
     */
    public function enqueue_assets() {
        // Get the mix manifest
        $manifest = aqualuxe_get_mix_manifest();
        
        // Enqueue main stylesheet
        wp_enqueue_style(
            'aqualuxe-styles',
            aqualuxe_mix('css/main.css', $manifest),
            [],
            AQUALUXE_VERSION
        );
        
        // Enqueue main script
        wp_enqueue_script(
            'aqualuxe-scripts',
            aqualuxe_mix('js/main.js', $manifest),
            ['jquery'],
            AQUALUXE_VERSION,
            true
        );
        
        // Localize script
        wp_localize_script('aqualuxe-scripts', 'aqualuxeData', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce'   => wp_create_nonce('aqualuxe-nonce'),
            'homeUrl' => home_url('/'),
            'isWooCommerceActive' => aqualuxe_is_woocommerce_active(),
            'themeSettings' => aqualuxe_get_theme_settings(),
        ]);
        
        // If WooCommerce is active, enqueue WooCommerce styles
        if (aqualuxe_is_woocommerce_active()) {
            wp_enqueue_style(
                'aqualuxe-woocommerce',
                aqualuxe_mix('css/woocommerce.css', $manifest),
                ['aqualuxe-styles'],
                AQUALUXE_VERSION
            );
            
            wp_enqueue_script(
                'aqualuxe-woocommerce',
                aqualuxe_mix('js/woocommerce.js', $manifest),
                ['aqualuxe-scripts'],
                AQUALUXE_VERSION,
                true
            );
        }
        
        // If the comments are open, enqueue the comment reply script
        if (is_singular() && comments_open() && get_option('thread_comments')) {
            wp_enqueue_script('comment-reply');
        }
    }

    /**
     * Enqueue admin scripts and styles
     */
    public function admin_assets() {
        // Get the mix manifest
        $manifest = aqualuxe_get_mix_manifest();
        
        // Enqueue admin stylesheet
        wp_enqueue_style(
            'aqualuxe-admin-styles',
            aqualuxe_mix('css/admin.css', $manifest),
            [],
            AQUALUXE_VERSION
        );
        
        // Enqueue admin script
        wp_enqueue_script(
            'aqualuxe-admin-scripts',
            aqualuxe_mix('js/admin.js', $manifest),
            ['jquery'],
            AQUALUXE_VERSION,
            true
        );
    }

    /**
     * Customize register
     */
    public function customize_register($wp_customize) {
        // Load customizer classes
        require_once AQUALUXE_CORE_DIR . 'classes/class-aqualuxe-customizer.php';
        
        // Initialize customizer
        new AquaLuxe_Customizer($wp_customize);
    }

    /**
     * Load modules
     */
    private function load_modules() {
        // Get active modules from options
        $this->active_modules = apply_filters('aqualuxe_active_modules', [
            'multilingual',
            'dark-mode',
            'multicurrency',
            'seo',
            'subscriptions',
            'auctions',
            'bookings',
            'events',
            'wholesale',
            'trade-ins',
            'services',
            'franchise',
            'sustainability',
            'affiliate',
        ]);
        
        // Load each active module
        foreach ($this->active_modules as $module) {
            $module_file = AQUALUXE_MODULES_DIR . $module . '/module.php';
            
            if (file_exists($module_file)) {
                require_once $module_file;
            }
        }
        
        // Fire action after modules are loaded
        do_action('aqualuxe_modules_loaded');
    }
}

// Initialize the theme
AquaLuxe_Theme::instance();

/**
 * WooCommerce compatibility
 */
if (aqualuxe_is_woocommerce_active()) {
    require_once AQUALUXE_INC_DIR . 'woocommerce.php';
}