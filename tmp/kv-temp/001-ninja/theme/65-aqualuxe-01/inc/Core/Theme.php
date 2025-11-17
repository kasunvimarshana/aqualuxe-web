<?php
/**
 * Main Theme Class
 *
 * @package AquaLuxe
 */

namespace AquaLuxe\Core;

/**
 * Theme Class
 * 
 * Singleton class that bootstraps the theme
 */
class Theme {
    /**
     * Instance of this class
     *
     * @var Theme
     */
    private static $instance = null;

    /**
     * Active modules
     *
     * @var array
     */
    private $active_modules = [];

    /**
     * Get the singleton instance
     *
     * @return Theme
     */
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        // Initialize core components
        $this->init_core_components();
        
        // Initialize modules
        $this->init_modules();
        
        // Setup theme
        $this->setup_theme();
        
        // Register hooks
        $this->register_hooks();
    }

    /**
     * Initialize core components
     */
    private function init_core_components() {
        // Load core classes
        require_once AQUALUXE_INC_DIR . 'Core/Assets.php';
        require_once AQUALUXE_INC_DIR . 'Core/Setup.php';
        require_once AQUALUXE_INC_DIR . 'Core/Customizer.php';
        require_once AQUALUXE_INC_DIR . 'Core/Template.php';
        require_once AQUALUXE_INC_DIR . 'Core/Hooks.php';
        require_once AQUALUXE_INC_DIR . 'Core/Multilingual.php';
        require_once AQUALUXE_INC_DIR . 'Core/DarkMode.php';
        
        // Initialize WooCommerce if active
        if ( $this->is_woocommerce_active() ) {
            require_once AQUALUXE_INC_DIR . 'Core/WooCommerce.php';
        }
    }

    /**
     * Initialize modules
     */
    private function init_modules() {
        // Get active modules from options
        $active_modules = get_option( 'aqualuxe_active_modules', [
            'bookings' => true,
            'events' => true,
            'subscriptions' => true,
            'franchise' => true,
            'wholesale' => true,
            'auctions' => true,
            'affiliate' => true,
            'services' => true,
        ] );
        
        // Filter active modules
        $active_modules = apply_filters( 'aqualuxe_active_modules', $active_modules );
        
        // Load active modules
        foreach ( $active_modules as $module => $active ) {
            if ( $active && file_exists( AQUALUXE_MODULES_DIR . ucfirst( $module ) . '/Module.php' ) ) {
                require_once AQUALUXE_MODULES_DIR . ucfirst( $module ) . '/Module.php';
                $class_name = 'AquaLuxe\\Modules\\' . ucfirst( $module ) . '\\Module';
                if ( class_exists( $class_name ) ) {
                    $this->active_modules[ $module ] = new $class_name();
                }
            }
        }
    }

    /**
     * Setup theme
     */
    private function setup_theme() {
        // Initialize core classes
        Assets::get_instance();
        Setup::get_instance();
        Customizer::get_instance();
        Template::get_instance();
        Hooks::get_instance();
        Multilingual::get_instance();
        DarkMode::get_instance();
        
        // Initialize WooCommerce if active
        if ( $this->is_woocommerce_active() ) {
            WooCommerce::get_instance();
        }
    }

    /**
     * Register hooks
     */
    private function register_hooks() {
        // After setup theme
        add_action( 'after_setup_theme', [ $this, 'after_setup_theme' ] );
        
        // Init action
        add_action( 'init', [ $this, 'init' ] );
        
        // Widgets init
        add_action( 'widgets_init', [ $this, 'widgets_init' ] );
    }

    /**
     * After setup theme
     */
    public function after_setup_theme() {
        // Theme supports
        add_theme_support( 'automatic-feed-links' );
        add_theme_support( 'title-tag' );
        add_theme_support( 'post-thumbnails' );
        add_theme_support( 'html5', [
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script',
        ] );
        add_theme_support( 'customize-selective-refresh-widgets' );
        add_theme_support( 'custom-logo', [
            'height'      => 250,
            'width'       => 250,
            'flex-width'  => true,
            'flex-height' => true,
        ] );
        add_theme_support( 'editor-styles' );
        add_theme_support( 'responsive-embeds' );
        add_theme_support( 'align-wide' );
        add_theme_support( 'wp-block-styles' );
        
        // WooCommerce support
        if ( $this->is_woocommerce_active() ) {
            add_theme_support( 'woocommerce' );
            add_theme_support( 'wc-product-gallery-zoom' );
            add_theme_support( 'wc-product-gallery-lightbox' );
            add_theme_support( 'wc-product-gallery-slider' );
        }
        
        // Register nav menus
        register_nav_menus( [
            'primary' => esc_html__( 'Primary Menu', 'aqualuxe' ),
            'footer'  => esc_html__( 'Footer Menu', 'aqualuxe' ),
            'mobile'  => esc_html__( 'Mobile Menu', 'aqualuxe' ),
        ] );
        
        // Load theme textdomain
        load_theme_textdomain( 'aqualuxe', AQUALUXE_DIR . 'languages' );
    }

    /**
     * Init action
     */
    public function init() {
        // Register custom post types and taxonomies
        $this->register_post_types();
        $this->register_taxonomies();
    }

    /**
     * Widgets init
     */
    public function widgets_init() {
        register_sidebar( [
            'name'          => esc_html__( 'Sidebar', 'aqualuxe' ),
            'id'            => 'sidebar-1',
            'description'   => esc_html__( 'Add widgets here.', 'aqualuxe' ),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        ] );
        
        register_sidebar( [
            'name'          => esc_html__( 'Footer 1', 'aqualuxe' ),
            'id'            => 'footer-1',
            'description'   => esc_html__( 'Add footer widgets here.', 'aqualuxe' ),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        ] );
        
        register_sidebar( [
            'name'          => esc_html__( 'Footer 2', 'aqualuxe' ),
            'id'            => 'footer-2',
            'description'   => esc_html__( 'Add footer widgets here.', 'aqualuxe' ),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        ] );
        
        register_sidebar( [
            'name'          => esc_html__( 'Footer 3', 'aqualuxe' ),
            'id'            => 'footer-3',
            'description'   => esc_html__( 'Add footer widgets here.', 'aqualuxe' ),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        ] );
        
        register_sidebar( [
            'name'          => esc_html__( 'Footer 4', 'aqualuxe' ),
            'id'            => 'footer-4',
            'description'   => esc_html__( 'Add footer widgets here.', 'aqualuxe' ),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        ] );
    }

    /**
     * Register custom post types
     */
    private function register_post_types() {
        // Register custom post types here
    }

    /**
     * Register taxonomies
     */
    private function register_taxonomies() {
        // Register taxonomies here
    }

    /**
     * Check if WooCommerce is active
     *
     * @return bool
     */
    public function is_woocommerce_active() {
        return class_exists( 'WooCommerce' );
    }

    /**
     * Get active modules
     *
     * @return array
     */
    public function get_active_modules() {
        return $this->active_modules;
    }

    /**
     * Check if a module is active
     *
     * @param string $module Module name
     * @return bool
     */
    public function is_module_active( $module ) {
        return isset( $this->active_modules[ $module ] );
    }
}