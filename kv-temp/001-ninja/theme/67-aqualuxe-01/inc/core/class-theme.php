<?php
/**
 * Main Theme Class
 *
 * @package AquaLuxe
 * @subpackage Core
 * @since 1.0.0
 */

namespace AquaLuxe\Core;

/**
 * Main Theme Class
 * 
 * This class is responsible for bootstrapping the theme.
 * It follows the singleton pattern to ensure only one instance exists.
 */
class Theme {
    /**
     * Instance of this class
     *
     * @var Theme
     */
    private static $instance = null;

    /**
     * List of core classes to initialize
     *
     * @var array
     */
    private $core_classes = [
        'AquaLuxe\\Core\\Assets',
        'AquaLuxe\\Core\\Setup',
        'AquaLuxe\\Core\\Template_Hooks',
        'AquaLuxe\\Customizer\\Customizer',
        'AquaLuxe\\Core\\Multilingual',
        'AquaLuxe\\Core\\Dark_Mode',
    ];

    /**
     * List of active modules
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
        $this->load_core_files();
        $this->init_core_classes();
        $this->load_modules();
        $this->init_hooks();
    }

    /**
     * Load core files
     *
     * @return void
     */
    private function load_core_files() {
        $core_files = [
            'core/class-assets.php',
            'core/class-setup.php',
            'core/class-template-hooks.php',
            'customizer/class-customizer.php',
            'core/class-multilingual.php',
            'core/class-dark-mode.php',
            'helpers/template-functions.php',
            'helpers/template-hooks.php',
            'helpers/woocommerce-functions.php',
        ];

        foreach ( $core_files as $file ) {
            require_once AQUALUXE_INC_DIR . $file;
        }
    }

    /**
     * Initialize core classes
     *
     * @return void
     */
    private function init_core_classes() {
        foreach ( $this->core_classes as $class ) {
            if ( class_exists( $class ) ) {
                $instance = call_user_func( [ $class, 'get_instance' ] );
            }
        }
    }

    /**
     * Load modules
     *
     * @return void
     */
    private function load_modules() {
        // Get active modules from options
        $active_modules = get_option( 'aqualuxe_active_modules', [
            'bookings',
            'events',
            'subscriptions',
            'franchise',
            'wholesale',
            'auctions',
            'affiliate',
            'services',
        ] );

        // Load each active module
        foreach ( $active_modules as $module ) {
            $module_file = AQUALUXE_MODULES_DIR . $module . '/module.php';
            if ( file_exists( $module_file ) ) {
                require_once $module_file;
                $class_name = 'AquaLuxe\\Modules\\' . ucfirst( $module ) . '\\Module';
                if ( class_exists( $class_name ) ) {
                    $this->active_modules[ $module ] = call_user_func( [ $class_name, 'get_instance' ] );
                }
            }
        }
    }

    /**
     * Initialize hooks
     *
     * @return void
     */
    private function init_hooks() {
        // Check if WooCommerce is active
        add_action( 'plugins_loaded', [ $this, 'check_woocommerce' ] );
        
        // After setup theme
        add_action( 'after_setup_theme', [ $this, 'setup_theme' ] );
        
        // Register widget areas
        add_action( 'widgets_init', [ $this, 'register_widget_areas' ] );
    }

    /**
     * Check if WooCommerce is active and load WooCommerce integration
     *
     * @return void
     */
    public function check_woocommerce() {
        if ( class_exists( 'WooCommerce' ) ) {
            require_once AQUALUXE_INC_DIR . 'core/class-woocommerce.php';
            $woocommerce = call_user_func( [ 'AquaLuxe\\Core\\WooCommerce', 'get_instance' ] );
        }
    }

    /**
     * Setup theme
     *
     * @return void
     */
    public function setup_theme() {
        // Load text domain
        load_theme_textdomain( 'aqualuxe', AQUALUXE_DIR . 'languages' );

        // Add default posts and comments RSS feed links to head
        add_theme_support( 'automatic-feed-links' );

        // Let WordPress manage the document title
        add_theme_support( 'title-tag' );

        // Enable support for Post Thumbnails on posts and pages
        add_theme_support( 'post-thumbnails' );

        // Add theme support for selective refresh for widgets
        add_theme_support( 'customize-selective-refresh-widgets' );

        // Add support for responsive embeds
        add_theme_support( 'responsive-embeds' );

        // Add support for full and wide align images
        add_theme_support( 'align-wide' );

        // Add support for editor styles
        add_theme_support( 'editor-styles' );

        // Add support for HTML5
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

        // Register nav menus
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
     * Register widget areas
     *
     * @return void
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
                'before_widget' => '<section id="%1$s" class="widget %2$s">',
                'after_widget'  => '</section>',
                'before_title'  => '<h2 class="widget-title">',
                'after_title'   => '</h2>',
            ]
        );

        register_sidebar(
            [
                'name'          => esc_html__( 'Footer 2', 'aqualuxe' ),
                'id'            => 'footer-2',
                'description'   => esc_html__( 'Add footer widgets here.', 'aqualuxe' ),
                'before_widget' => '<section id="%1$s" class="widget %2$s">',
                'after_widget'  => '</section>',
                'before_title'  => '<h2 class="widget-title">',
                'after_title'   => '</h2>',
            ]
        );

        register_sidebar(
            [
                'name'          => esc_html__( 'Footer 3', 'aqualuxe' ),
                'id'            => 'footer-3',
                'description'   => esc_html__( 'Add footer widgets here.', 'aqualuxe' ),
                'before_widget' => '<section id="%1$s" class="widget %2$s">',
                'after_widget'  => '</section>',
                'before_title'  => '<h2 class="widget-title">',
                'after_title'   => '</h2>',
            ]
        );

        register_sidebar(
            [
                'name'          => esc_html__( 'Footer 4', 'aqualuxe' ),
                'id'            => 'footer-4',
                'description'   => esc_html__( 'Add footer widgets here.', 'aqualuxe' ),
                'before_widget' => '<section id="%1$s" class="widget %2$s">',
                'after_widget'  => '</section>',
                'before_title'  => '<h2 class="widget-title">',
                'after_title'   => '</h2>',
            ]
        );

        // WooCommerce shop sidebar
        if ( class_exists( 'WooCommerce' ) ) {
            register_sidebar(
                [
                    'name'          => esc_html__( 'Shop Sidebar', 'aqualuxe' ),
                    'id'            => 'shop-sidebar',
                    'description'   => esc_html__( 'Add shop widgets here.', 'aqualuxe' ),
                    'before_widget' => '<section id="%1$s" class="widget %2$s">',
                    'after_widget'  => '</section>',
                    'before_title'  => '<h2 class="widget-title">',
                    'after_title'   => '</h2>',
                ]
            );
        }
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
     * @param string $module Module name.
     * @return boolean
     */
    public function is_module_active( $module ) {
        return isset( $this->active_modules[ $module ] );
    }
}