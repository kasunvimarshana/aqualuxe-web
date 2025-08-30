<?php
/**
 * AquaLuxe Theme functions and definitions
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Define theme constants
define( 'AQUALUXE_VERSION', '1.0.0' );
define( 'AQUALUXE_DIR', trailingslashit( get_template_directory() ) );
define( 'AQUALUXE_URI', trailingslashit( get_template_directory_uri() ) );
define( 'AQUALUXE_ASSETS_URI', AQUALUXE_URI . 'assets/dist/' );
define( 'AQUALUXE_INC_DIR', AQUALUXE_DIR . 'inc/' );
define( 'AQUALUXE_MODULES_DIR', AQUALUXE_DIR . 'modules/' );
define( 'AQUALUXE_TEMPLATES_DIR', AQUALUXE_DIR . 'templates/' );

/**
 * AquaLuxe Theme Class
 * 
 * Main theme class that initializes the theme and loads all components
 */
final class AquaLuxe_Theme {
    /**
     * Instance of this class
     *
     * @var AquaLuxe_Theme
     */
    private static $instance = null;

    /**
     * Modules registry
     *
     * @var array
     */
    private $modules = [];

    /**
     * Get singleton instance
     *
     * @return AquaLuxe_Theme
     */
    public static function instance() {
        if ( is_null( self::$instance ) ) {
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
        add_action( 'after_setup_theme', [ $this, 'theme_setup' ] );
        add_action( 'after_setup_theme', [ $this, 'content_width' ], 0 );
        
        // Register and enqueue assets
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_assets' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_assets' ] );
        
        // Register widget areas
        add_action( 'widgets_init', [ $this, 'register_widget_areas' ] );
        
        // Load modules
        add_action( 'init', [ $this, 'load_modules' ], 5 );
        
        // WooCommerce integration
        add_action( 'after_setup_theme', [ $this, 'woocommerce_setup' ] );
        
        // Register navigation menus
        add_action( 'init', [ $this, 'register_nav_menus' ] );
    }

    /**
     * Load core files
     */
    private function load_core_files() {
        // Autoloader
        require_once AQUALUXE_INC_DIR . 'class-aqualuxe-autoloader.php';
        
        // Core functions
        require_once AQUALUXE_INC_DIR . 'core-functions.php';
        
        // Template functions
        require_once AQUALUXE_INC_DIR . 'template-functions.php';
        
        // Template tags
        require_once AQUALUXE_INC_DIR . 'template-tags.php';
        
        // Customizer
        require_once AQUALUXE_INC_DIR . 'customizer.php';
        
        // Custom hooks
        require_once AQUALUXE_INC_DIR . 'hooks.php';
        
        // Asset management
        require_once AQUALUXE_INC_DIR . 'class-aqualuxe-assets.php';
        
        // WooCommerce functions
        if ( class_exists( 'WooCommerce' ) ) {
            require_once AQUALUXE_INC_DIR . 'woocommerce-functions.php';
        }
    }

    /**
     * Theme setup
     */
    public function theme_setup() {
        // Load text domain
        load_theme_textdomain( 'aqualuxe', AQUALUXE_DIR . 'languages' );

        // Add default posts and comments RSS feed links to head
        add_theme_support( 'automatic-feed-links' );

        // Let WordPress manage the document title
        add_theme_support( 'title-tag' );

        // Enable support for Post Thumbnails
        add_theme_support( 'post-thumbnails' );

        // Switch default core markup to output valid HTML5
        add_theme_support(
            'html5',
            [
                'search-form',
                'comment-form',
                'comment-list',
                'gallery',
                'caption',
                'style',
                'script',
            ]
        );

        // Add theme support for selective refresh for widgets
        add_theme_support( 'customize-selective-refresh-widgets' );

        // Add support for editor styles
        add_theme_support( 'editor-styles' );
        
        // Add support for responsive embeds
        add_theme_support( 'responsive-embeds' );
        
        // Add support for block styles
        add_theme_support( 'wp-block-styles' );
        
        // Add support for full and wide align images
        add_theme_support( 'align-wide' );

        // Add support for custom logo
        add_theme_support(
            'custom-logo',
            [
                'height'      => 250,
                'width'       => 250,
                'flex-width'  => true,
                'flex-height' => true,
            ]
        );
    }

    /**
     * Set the content width in pixels
     */
    public function content_width() {
        $GLOBALS['content_width'] = apply_filters( 'aqualuxe_content_width', 1200 );
    }

    /**
     * Register widget areas
     */
    public function register_widget_areas() {
        register_sidebar(
            [
                'name'          => esc_html__( 'Sidebar', 'aqualuxe' ),
                'id'            => 'sidebar-1',
                'description'   => esc_html__( 'Add widgets here.', 'aqualuxe' ),
                'before_widget' => '<section id="%1$s" class="widget %2$s">',
                'after_widget'  => '</section>',
                'before_title'  => '<h2 class="widget-title">',
                'after_title'   => '</h2>',
            ]
        );
        
        register_sidebar(
            [
                'name'          => esc_html__( 'Footer 1', 'aqualuxe' ),
                'id'            => 'footer-1',
                'description'   => esc_html__( 'Add footer widgets here.', 'aqualuxe' ),
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<h3 class="widget-title">',
                'after_title'   => '</h3>',
            ]
        );
        
        register_sidebar(
            [
                'name'          => esc_html__( 'Footer 2', 'aqualuxe' ),
                'id'            => 'footer-2',
                'description'   => esc_html__( 'Add footer widgets here.', 'aqualuxe' ),
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<h3 class="widget-title">',
                'after_title'   => '</h3>',
            ]
        );
        
        register_sidebar(
            [
                'name'          => esc_html__( 'Footer 3', 'aqualuxe' ),
                'id'            => 'footer-3',
                'description'   => esc_html__( 'Add footer widgets here.', 'aqualuxe' ),
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<h3 class="widget-title">',
                'after_title'   => '</h3>',
            ]
        );
        
        register_sidebar(
            [
                'name'          => esc_html__( 'Footer 4', 'aqualuxe' ),
                'id'            => 'footer-4',
                'description'   => esc_html__( 'Add footer widgets here.', 'aqualuxe' ),
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<h3 class="widget-title">',
                'after_title'   => '</h3>',
            ]
        );
    }

    /**
     * Register navigation menus
     */
    public function register_nav_menus() {
        register_nav_menus(
            [
                'primary'   => esc_html__( 'Primary Menu', 'aqualuxe' ),
                'secondary' => esc_html__( 'Secondary Menu', 'aqualuxe' ),
                'footer'    => esc_html__( 'Footer Menu', 'aqualuxe' ),
                'mobile'    => esc_html__( 'Mobile Menu', 'aqualuxe' ),
            ]
        );
    }

    /**
     * Enqueue frontend scripts and styles
     */
    public function enqueue_assets() {
        // Get the asset manager instance
        $assets = AquaLuxe_Assets::instance();
        
        // Enqueue main stylesheet
        $assets->enqueue_style( 'aqualuxe-style', 'css/main.css' );
        
        // Enqueue main script
        $assets->enqueue_script( 'aqualuxe-script', 'js/main.js', [ 'jquery' ], true );
        
        // Localize script
        wp_localize_script(
            'aqualuxe-script',
            'aqualuxeData',
            [
                'ajaxUrl' => admin_url( 'admin-ajax.php' ),
                'nonce'   => wp_create_nonce( 'aqualuxe-nonce' ),
            ]
        );
        
        // Enqueue comment reply script if needed
        if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
            wp_enqueue_script( 'comment-reply' );
        }
    }

    /**
     * Enqueue admin scripts and styles
     */
    public function enqueue_admin_assets() {
        // Get the asset manager instance
        $assets = AquaLuxe_Assets::instance();
        
        // Enqueue admin stylesheet
        $assets->enqueue_style( 'aqualuxe-admin-style', 'css/admin.css' );
        
        // Enqueue admin script
        $assets->enqueue_script( 'aqualuxe-admin-script', 'js/admin.js', [ 'jquery' ], true );
    }

    /**
     * WooCommerce setup
     */
    public function woocommerce_setup() {
        // Check if WooCommerce is active
        if ( ! class_exists( 'WooCommerce' ) ) {
            return;
        }

        // Add theme support for WooCommerce
        add_theme_support( 'woocommerce' );
        add_theme_support( 'wc-product-gallery-zoom' );
        add_theme_support( 'wc-product-gallery-lightbox' );
        add_theme_support( 'wc-product-gallery-slider' );
        
        // Include WooCommerce compatibility file
        require_once AQUALUXE_INC_DIR . 'woocommerce.php';
    }

    /**
     * Load and initialize modules
     */
    public function load_modules() {
        // Get active modules from options
        $active_modules = get_option( 'aqualuxe_active_modules', [
            'multilingual' => true,
            'dark-mode'    => true,
            'demo-importer' => true,
        ] );
        
        // Core modules directory
        $modules_dir = AQUALUXE_MODULES_DIR;
        
        // Loop through modules directory
        foreach ( scandir( $modules_dir ) as $module ) {
            // Skip . and .. directories
            if ( $module === '.' || $module === '..' ) {
                continue;
            }
            
            // Check if module is active
            if ( isset( $active_modules[ $module ] ) && $active_modules[ $module ] ) {
                // Module main file path
                $module_file = $modules_dir . $module . '/module.php';
                
                // Check if module file exists
                if ( file_exists( $module_file ) ) {
                    // Include module file
                    require_once $module_file;
                    
                    // Module class name
                    $class_name = 'AquaLuxe_Module_' . str_replace( '-', '_', ucfirst( $module ) );
                    
                    // Check if module class exists
                    if ( class_exists( $class_name ) ) {
                        // Initialize module
                        $this->modules[ $module ] = new $class_name();
                    }
                }
            }
        }
        
        // Allow modules to be added programmatically
        $this->modules = apply_filters( 'aqualuxe_modules', $this->modules );
    }

    /**
     * Get a module instance
     *
     * @param string $module Module name
     * @return object|null Module instance or null if not found
     */
    public function get_module( $module ) {
        return isset( $this->modules[ $module ] ) ? $this->modules[ $module ] : null;
    }
}

// Initialize the theme
AquaLuxe_Theme::instance();