<?php
/**
 * AquaLuxe functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Define constants
define( 'AQUALUXE_VERSION', '1.0.0' );
define( 'AQUALUXE_DIR', trailingslashit( get_template_directory() ) );
define( 'AQUALUXE_URI', trailingslashit( get_template_directory_uri() ) );

// Minimum PHP version check
if ( version_compare( PHP_VERSION, '7.4', '<' ) ) {
    require get_template_directory() . '/inc/back-compat.php';
    return;
}

/**
 * AquaLuxe Theme Class
 * 
 * Main class for the theme following OOP principles
 */
final class AquaLuxe_Theme {
    /**
     * Instance
     * 
     * @var AquaLuxe_Theme The single instance of the class.
     */
    private static $_instance = null;

    /**
     * Instance
     * 
     * Ensures only one instance of the class is loaded or can be loaded.
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Constructor
     */
    public function __construct() {
        // Load admin options classes
        require_once AQUALUXE_DIR . 'inc/admin/class-aqualuxe-theme-options.php';
        require_once AQUALUXE_DIR . 'inc/admin/class-aqualuxe-homepage-options.php';
        require_once AQUALUXE_DIR . 'inc/admin/class-aqualuxe-about-options.php';
        $this->includes();
        $this->init_hooks();
    }

    /**
     * Include required files
     */
    private function includes() {
        // Core functionality
        require_once AQUALUXE_DIR . 'inc/helpers/template-functions.php';
        require_once AQUALUXE_DIR . 'inc/helpers/template-tags.php';
        
        // Theme setup and features
        require_once AQUALUXE_DIR . 'inc/class-aqualuxe-setup.php';
        require_once AQUALUXE_DIR . 'inc/class-aqualuxe-assets.php';
        require_once AQUALUXE_DIR . 'inc/class-aqualuxe-nav-menus.php';
        require_once AQUALUXE_DIR . 'inc/class-aqualuxe-performance.php';
        require_once AQUALUXE_DIR . 'inc/class-aqualuxe-security.php';
        
        // Customizer
        require_once AQUALUXE_DIR . 'inc/customizer/class-aqualuxe-customizer.php';
        
        // Widgets
        require_once AQUALUXE_DIR . 'inc/widgets/class-aqualuxe-widgets.php';
        
        // WooCommerce integration
        if ( class_exists( 'WooCommerce' ) ) {
            require_once AQUALUXE_DIR . 'inc/woocommerce/class-aqualuxe-woocommerce.php';
            require_once AQUALUXE_DIR . 'inc/woocommerce/class-aqualuxe-multi-currency.php';
            require_once AQUALUXE_DIR . 'inc/woocommerce/class-aqualuxe-international-shipping.php';
        }
    }

    /**
     * Initialize hooks
     */
    private function init_hooks() {
        add_action( 'after_setup_theme', array( $this, 'setup_theme' ) );
        add_action( 'widgets_init', array( $this, 'register_sidebars' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
    }

    /**
     * Theme setup
     */
    public function setup_theme() {
        // Make theme available for translation
        load_theme_textdomain( 'aqualuxe', AQUALUXE_DIR . 'languages' );

        // Add default posts and comments RSS feed links to head
        add_theme_support( 'automatic-feed-links' );

        // Let WordPress manage the document title
        add_theme_support( 'title-tag' );

        // Enable support for Post Thumbnails on posts and pages
        add_theme_support( 'post-thumbnails' );

        // Add theme support for selective refresh for widgets
        add_theme_support( 'customize-selective-refresh-widgets' );

        // Switch default core markup to output valid HTML5
        add_theme_support(
            'html5',
            array(
                'search-form',
                'comment-form',
                'comment-list',
                'gallery',
                'caption',
                'style',
                'script',
            )
        );

        // Add support for core custom logo
        add_theme_support(
            'custom-logo',
            array(
                'height'      => 250,
                'width'       => 250,
                'flex-width'  => true,
                'flex-height' => true,
            )
        );

        // Add support for editor styles
        add_theme_support( 'editor-styles' );

        // Add support for responsive embeds
        add_theme_support( 'responsive-embeds' );

        // Add support for full and wide align images
        add_theme_support( 'align-wide' );

        // Register nav menus
        register_nav_menus(
            array(
                'primary'   => esc_html__( 'Primary Menu', 'aqualuxe' ),
                'secondary' => esc_html__( 'Secondary Menu', 'aqualuxe' ),
                'footer'    => esc_html__( 'Footer Menu', 'aqualuxe' ),
            )
        );

        // Set content width
        if ( ! isset( $content_width ) ) {
            $content_width = 1200;
        }
    }

    /**
     * Register widget areas
     */
    public function register_sidebars() {
        register_sidebar(
            array(
                'name'          => esc_html__( 'Sidebar', 'aqualuxe' ),
                'id'            => 'sidebar-1',
                'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'aqualuxe' ),
                'before_widget' => '<section id="%1$s" class="widget %2$s">',
                'after_widget'  => '</section>',
                'before_title'  => '<h2 class="widget-title">',
                'after_title'   => '</h2>',
            )
        );

        register_sidebar(
            array(
                'name'          => esc_html__( 'Footer 1', 'aqualuxe' ),
                'id'            => 'footer-1',
                'description'   => esc_html__( 'Add widgets here to appear in footer column 1.', 'aqualuxe' ),
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<h3 class="widget-title">',
                'after_title'   => '</h3>',
            )
        );

        register_sidebar(
            array(
                'name'          => esc_html__( 'Footer 2', 'aqualuxe' ),
                'id'            => 'footer-2',
                'description'   => esc_html__( 'Add widgets here to appear in footer column 2.', 'aqualuxe' ),
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<h3 class="widget-title">',
                'after_title'   => '</h3>',
            )
        );

        register_sidebar(
            array(
                'name'          => esc_html__( 'Footer 3', 'aqualuxe' ),
                'id'            => 'footer-3',
                'description'   => esc_html__( 'Add widgets here to appear in footer column 3.', 'aqualuxe' ),
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<h3 class="widget-title">',
                'after_title'   => '</h3>',
            )
        );

        register_sidebar(
            array(
                'name'          => esc_html__( 'Footer 4', 'aqualuxe' ),
                'id'            => 'footer-4',
                'description'   => esc_html__( 'Add widgets here to appear in footer column 4.', 'aqualuxe' ),
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<h3 class="widget-title">',
                'after_title'   => '</h3>',
            )
        );

        // WooCommerce specific sidebars
        if ( class_exists( 'WooCommerce' ) ) {
            register_sidebar(
                array(
                    'name'          => esc_html__( 'Shop Sidebar', 'aqualuxe' ),
                    'id'            => 'shop-sidebar',
                    'description'   => esc_html__( 'Add widgets here to appear in shop sidebar.', 'aqualuxe' ),
                    'before_widget' => '<div id="%1$s" class="widget %2$s">',
                    'after_widget'  => '</div>',
                    'before_title'  => '<h3 class="widget-title">',
                    'after_title'   => '</h3>',
                )
            );
        }
    }

    /**
     * Enqueue scripts and styles
     */
    public function enqueue_scripts() {
        // Enqueue Google Fonts
        wp_enqueue_style(
            'aqualuxe-fonts',
            'https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600&display=swap',
            array(),
            AQUALUXE_VERSION
        );

        // Enqueue main stylesheet
        wp_enqueue_style(
            'aqualuxe-style',
            AQUALUXE_URI . 'assets/css/main.css',
            array(),
            AQUALUXE_VERSION
        );

        // Enqueue responsive styles
        wp_enqueue_style(
            'aqualuxe-responsive',
            AQUALUXE_URI . 'assets/css/responsive.css',
            array( 'aqualuxe-style' ),
            AQUALUXE_VERSION
        );

        // Enqueue main JavaScript
        wp_enqueue_script(
            'aqualuxe-script',
            AQUALUXE_URI . 'assets/js/main.js',
            array( 'jquery' ),
            AQUALUXE_VERSION,
            true
        );

        // Enqueue comment reply script
        if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
            wp_enqueue_script( 'comment-reply' );
        }

        // Localize script
        wp_localize_script(
            'aqualuxe-script',
            'aqualuxeVars',
            array(
                'ajaxUrl' => admin_url( 'admin-ajax.php' ),
                'nonce'   => wp_create_nonce( 'aqualuxe-nonce' ),
            )
        );
    }
}

// Initialize the theme
function aqualuxe_theme() {
    return AquaLuxe_Theme::instance();
}

// Start the theme
aqualuxe_theme();