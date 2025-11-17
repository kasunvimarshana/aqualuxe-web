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
define( 'AQUALUXE_VERSION', '1.0.0' );
define( 'AQUALUXE_DIR', trailingslashit( get_template_directory() ) );
define( 'AQUALUXE_URI', trailingslashit( get_template_directory_uri() ) );
define( 'AQUALUXE_ASSETS_URI', trailingslashit( AQUALUXE_URI . 'assets/dist' ) );
define( 'AQUALUXE_ASSETS_DIR', trailingslashit( AQUALUXE_DIR . 'assets/dist' ) );
define( 'AQUALUXE_INC_DIR', trailingslashit( AQUALUXE_DIR . 'inc' ) );
define( 'AQUALUXE_MODULES_DIR', trailingslashit( AQUALUXE_DIR . 'modules' ) );
define( 'AQUALUXE_TEMPLATES_DIR', trailingslashit( AQUALUXE_DIR . 'templates' ) );

/**
 * AquaLuxe Theme Class
 * 
 * Main theme class that initializes the theme and loads all required files.
 */
final class AquaLuxe {
    /**
     * Instance of the class
     *
     * @var AquaLuxe
     */
    private static $instance = null;

    /**
     * Modules registry
     *
     * @var array
     */
    private $modules = [];

    /**
     * Get the singleton instance of the class
     *
     * @return AquaLuxe
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

        // Initialize hooks
        $this->init_hooks();

        // Initialize modules
        $this->init_modules();

        // Initialize WooCommerce if active
        $this->init_woocommerce();
    }

    /**
     * Load core files
     */
    private function load_core_files() {
        // Load autoloader
        require_once AQUALUXE_INC_DIR . 'class-autoloader.php';

        // Core functionality
        require_once AQUALUXE_INC_DIR . 'core/class-assets.php';
        require_once AQUALUXE_INC_DIR . 'core/class-setup.php';
        require_once AQUALUXE_INC_DIR . 'core/class-template-loader.php';
        require_once AQUALUXE_INC_DIR . 'core/class-customizer.php';
        require_once AQUALUXE_INC_DIR . 'core/class-hooks.php';
        require_once AQUALUXE_INC_DIR . 'core/class-helpers.php';
        require_once AQUALUXE_INC_DIR . 'core/class-module-loader.php';

        // Template functions
        require_once AQUALUXE_INC_DIR . 'template-functions.php';
        require_once AQUALUXE_INC_DIR . 'template-hooks.php';

        // Custom post types and taxonomies
        require_once AQUALUXE_INC_DIR . 'post-types/class-post-types.php';
        require_once AQUALUXE_INC_DIR . 'taxonomies/class-taxonomies.php';

        // Widgets
        require_once AQUALUXE_INC_DIR . 'widgets/class-widgets.php';

        // Admin
        if ( is_admin() ) {
            require_once AQUALUXE_INC_DIR . 'admin/class-admin.php';
            require_once AQUALUXE_INC_DIR . 'admin/class-demo-importer.php';
            require_once AQUALUXE_INC_DIR . 'admin/class-plugin-activation.php';
        }
    }

    /**
     * Initialize hooks
     */
    private function init_hooks() {
        // Theme setup
        add_action( 'after_setup_theme', [ $this, 'theme_setup' ] );
        
        // Register widget areas
        add_action( 'widgets_init', [ $this, 'register_widget_areas' ] );
        
        // Enqueue scripts and styles
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
        
        // Admin scripts and styles
        add_action( 'admin_enqueue_scripts', [ $this, 'admin_scripts' ] );
        
        // Editor styles
        add_action( 'enqueue_block_editor_assets', [ $this, 'editor_scripts' ] );
        
        // Content width
        add_action( 'template_redirect', [ $this, 'content_width' ] );
    }

    /**
     * Initialize modules
     */
    private function init_modules() {
        // Get module loader
        $module_loader = new AquaLuxe\Core\Module_Loader();
        
        // Load and register modules
        $this->modules = $module_loader->load_modules();
    }

    /**
     * Initialize WooCommerce if active
     */
    private function init_woocommerce() {
        if ( class_exists( 'WooCommerce' ) ) {
            require_once AQUALUXE_INC_DIR . 'woocommerce/class-woocommerce.php';
            new AquaLuxe\WooCommerce\WooCommerce();
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

        // Enable support for Post Thumbnails on posts and pages
        add_theme_support( 'post-thumbnails' );

        // Set post thumbnail size
        set_post_thumbnail_size( 1200, 9999 );

        // Add custom image sizes
        add_image_size( 'aqualuxe-featured', 1600, 900, true );
        add_image_size( 'aqualuxe-card', 600, 400, true );
        add_image_size( 'aqualuxe-thumbnail', 300, 300, true );

        // Register navigation menus
        register_nav_menus(
            [
                'primary'   => esc_html__( 'Primary Menu', 'aqualuxe' ),
                'secondary' => esc_html__( 'Secondary Menu', 'aqualuxe' ),
                'footer'    => esc_html__( 'Footer Menu', 'aqualuxe' ),
                'social'    => esc_html__( 'Social Menu', 'aqualuxe' ),
            ]
        );

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

        // Enqueue editor styles
        add_editor_style( 'assets/dist/css/editor-style.css' );

        // Add support for responsive embeds
        add_theme_support( 'responsive-embeds' );

        // Add support for full and wide align images
        add_theme_support( 'align-wide' );

        // Add support for custom line height controls
        add_theme_support( 'custom-line-height' );

        // Add support for experimental link color control
        add_theme_support( 'experimental-link-color' );

        // Add support for custom units
        add_theme_support( 'custom-units' );

        // Add support for custom spacing
        add_theme_support( 'custom-spacing' );

        // Add support for custom logo
        add_theme_support(
            'custom-logo',
            [
                'height'      => 100,
                'width'       => 350,
                'flex-width'  => true,
                'flex-height' => true,
            ]
        );

        // Add support for WooCommerce
        add_theme_support( 'woocommerce' );
        add_theme_support( 'wc-product-gallery-zoom' );
        add_theme_support( 'wc-product-gallery-lightbox' );
        add_theme_support( 'wc-product-gallery-slider' );
    }

    /**
     * Register widget areas
     */
    public function register_widget_areas() {
        register_sidebar(
            [
                'name'          => esc_html__( 'Sidebar', 'aqualuxe' ),
                'id'            => 'sidebar-1',
                'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'aqualuxe' ),
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
                'description'   => esc_html__( 'Add widgets here to appear in footer column 1.', 'aqualuxe' ),
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
                'description'   => esc_html__( 'Add widgets here to appear in footer column 2.', 'aqualuxe' ),
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
                'description'   => esc_html__( 'Add widgets here to appear in footer column 3.', 'aqualuxe' ),
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
                'description'   => esc_html__( 'Add widgets here to appear in footer column 4.', 'aqualuxe' ),
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<h3 class="widget-title">',
                'after_title'   => '</h3>',
            ]
        );

        // WooCommerce shop sidebar
        if ( class_exists( 'WooCommerce' ) ) {
            register_sidebar(
                [
                    'name'          => esc_html__( 'Shop Sidebar', 'aqualuxe' ),
                    'id'            => 'shop-sidebar',
                    'description'   => esc_html__( 'Add widgets here to appear in shop sidebar.', 'aqualuxe' ),
                    'before_widget' => '<section id="%1$s" class="widget %2$s">',
                    'after_widget'  => '</section>',
                    'before_title'  => '<h2 class="widget-title">',
                    'after_title'   => '</h2>',
                ]
            );
        }
    }

    /**
     * Enqueue scripts and styles
     */
    public function enqueue_scripts() {
        // Get the asset manifest
        $manifest_path = AQUALUXE_ASSETS_DIR . 'mix-manifest.json';
        $manifest = file_exists( $manifest_path ) ? json_decode( file_get_contents( $manifest_path ), true ) : [];

        // Helper function to get versioned asset URL
        $get_asset = function( $path ) use ( $manifest ) {
            $versioned_path = isset( $manifest[ $path ] ) ? $manifest[ $path ] : $path;
            return AQUALUXE_ASSETS_URI . ltrim( $versioned_path, '/' );
        };

        // Enqueue main stylesheet
        wp_enqueue_style(
            'aqualuxe-style',
            $get_asset( '/css/main.css' ),
            [],
            AQUALUXE_VERSION
        );

        // Enqueue main script
        wp_enqueue_script(
            'aqualuxe-script',
            $get_asset( '/js/main.js' ),
            [ 'jquery' ],
            AQUALUXE_VERSION,
            true
        );

        // Add localized script data
        wp_localize_script(
            'aqualuxe-script',
            'aqualuxeData',
            [
                'ajaxUrl'    => admin_url( 'admin-ajax.php' ),
                'nonce'      => wp_create_nonce( 'aqualuxe-nonce' ),
                'homeUrl'    => home_url(),
                'themeUri'   => AQUALUXE_URI,
                'assetsUri'  => AQUALUXE_ASSETS_URI,
                'isRtl'      => is_rtl(),
                'i18n'       => [
                    'searchPlaceholder' => esc_html__( 'Search...', 'aqualuxe' ),
                    'menuToggle'        => esc_html__( 'Toggle Menu', 'aqualuxe' ),
                    'closeMenu'         => esc_html__( 'Close Menu', 'aqualuxe' ),
                    'expandMenu'        => esc_html__( 'Expand Menu', 'aqualuxe' ),
                    'collapseMenu'      => esc_html__( 'Collapse Menu', 'aqualuxe' ),
                    'darkMode'          => esc_html__( 'Dark Mode', 'aqualuxe' ),
                    'lightMode'         => esc_html__( 'Light Mode', 'aqualuxe' ),
                ],
            ]
        );

        // Enqueue WooCommerce styles if active
        if ( class_exists( 'WooCommerce' ) ) {
            wp_enqueue_style(
                'aqualuxe-woocommerce',
                $get_asset( '/css/woocommerce.css' ),
                [ 'aqualuxe-style' ],
                AQUALUXE_VERSION
            );

            wp_enqueue_script(
                'aqualuxe-woocommerce',
                $get_asset( '/js/modules/woocommerce.js' ),
                [ 'jquery', 'aqualuxe-script' ],
                AQUALUXE_VERSION,
                true
            );
        }

        // Load comment reply script
        if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
            wp_enqueue_script( 'comment-reply' );
        }
    }

    /**
     * Enqueue admin scripts and styles
     */
    public function admin_scripts() {
        // Get the asset manifest
        $manifest_path = AQUALUXE_ASSETS_DIR . 'mix-manifest.json';
        $manifest = file_exists( $manifest_path ) ? json_decode( file_get_contents( $manifest_path ), true ) : [];

        // Helper function to get versioned asset URL
        $get_asset = function( $path ) use ( $manifest ) {
            $versioned_path = isset( $manifest[ $path ] ) ? $manifest[ $path ] : $path;
            return AQUALUXE_ASSETS_URI . ltrim( $versioned_path, '/' );
        };

        // Enqueue admin stylesheet
        wp_enqueue_style(
            'aqualuxe-admin',
            $get_asset( '/css/admin.css' ),
            [],
            AQUALUXE_VERSION
        );

        // Enqueue admin script
        wp_enqueue_script(
            'aqualuxe-admin',
            $get_asset( '/js/admin.js' ),
            [ 'jquery' ],
            AQUALUXE_VERSION,
            true
        );
    }

    /**
     * Enqueue editor scripts and styles
     */
    public function editor_scripts() {
        // Get the asset manifest
        $manifest_path = AQUALUXE_ASSETS_DIR . 'mix-manifest.json';
        $manifest = file_exists( $manifest_path ) ? json_decode( file_get_contents( $manifest_path ), true ) : [];

        // Helper function to get versioned asset URL
        $get_asset = function( $path ) use ( $manifest ) {
            $versioned_path = isset( $manifest[ $path ] ) ? $manifest[ $path ] : $path;
            return AQUALUXE_ASSETS_URI . ltrim( $versioned_path, '/' );
        };

        // Enqueue editor stylesheet
        wp_enqueue_style(
            'aqualuxe-editor-style',
            $get_asset( '/css/editor-style.css' ),
            [],
            AQUALUXE_VERSION
        );

        // Enqueue editor script
        wp_enqueue_script(
            'aqualuxe-editor',
            $get_asset( '/js/customizer.js' ),
            [ 'jquery' ],
            AQUALUXE_VERSION,
            true
        );
    }

    /**
     * Set the content width
     */
    public function content_width() {
        $GLOBALS['content_width'] = apply_filters( 'aqualuxe_content_width', 1200 );
    }
}

// Initialize the theme
AquaLuxe::instance();