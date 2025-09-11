<?php
/**
 * AquaLuxe Theme Functions
 *
 * @package AquaLuxe
 * @version 1.0.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Define theme constants.
 */
define( 'AQUALUXE_VERSION', '1.0.0' );
define( 'AQUALUXE_THEME_DIR', get_template_directory() );
define( 'AQUALUXE_THEME_URI', get_template_directory_uri() );
define( 'AQUALUXE_ASSETS_DIR', AQUALUXE_THEME_DIR . '/assets' );
define( 'AQUALUXE_ASSETS_URI', AQUALUXE_THEME_URI . '/assets' );

/**
 * Theme Setup Class
 */
if ( ! class_exists( 'AquaLuxe_Theme' ) ) {
    class AquaLuxe_Theme {
        
        /**
         * Instance of this class.
         *
         * @var object
         */
        protected static $instance = null;
        
        /**
         * Initialize the theme.
         */
        public function __construct() {
            add_action( 'after_setup_theme', array( $this, 'setup' ) );
            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
            add_action( 'init', array( $this, 'init' ) );
            
            // Load core functionality
            $this->load_core();
            
            // Load modules
            $this->load_modules();
            
            // WooCommerce integration
            $this->woocommerce_integration();
        }
        
        /**
         * Return an instance of this class.
         *
         * @return object A single instance of this class.
         */
        public static function get_instance() {
            if ( null === self::$instance ) {
                self::$instance = new self();
            }
            return self::$instance;
        }
        
        /**
         * Theme setup.
         */
        public function setup() {
            // Make theme available for translation
            load_theme_textdomain( 'aqualuxe', AQUALUXE_THEME_DIR . '/languages' );
            
            // Add default posts and comments RSS feed links to head
            add_theme_support( 'automatic-feed-links' );
            
            // Let WordPress manage the document title
            add_theme_support( 'title-tag' );
            
            // Enable support for Post Thumbnails on posts and pages
            add_theme_support( 'post-thumbnails' );
            
            // Enable support for custom logo
            add_theme_support( 'custom-logo', array(
                'height'      => 100,
                'width'       => 400,
                'flex-height' => true,
                'flex-width'  => true,
            ) );
            
            // Register navigation menus
            register_nav_menus( array(
                'primary'   => esc_html__( 'Primary Menu', 'aqualuxe' ),
                'secondary' => esc_html__( 'Secondary Menu', 'aqualuxe' ),
                'footer'    => esc_html__( 'Footer Menu', 'aqualuxe' ),
                'mobile'    => esc_html__( 'Mobile Menu', 'aqualuxe' ),
            ) );
            
            // Switch default core markup to output valid HTML5
            add_theme_support( 'html5', array(
                'search-form',
                'comment-form',
                'comment-list',
                'gallery',
                'caption',
                'style',
                'script',
            ) );
            
            // Set up the WordPress core custom background feature
            add_theme_support( 'custom-background', apply_filters( 'aqualuxe_custom_background_args', array(
                'default-color' => 'ffffff',
                'default-image' => '',
            ) ) );
            
            // Add theme support for selective refresh for widgets
            add_theme_support( 'customize-selective-refresh-widgets' );
            
            // Add support for core custom logo
            add_theme_support( 'custom-logo', array(
                'height'      => 250,
                'width'       => 250,
                'flex-width'  => true,
                'flex-height' => true,
            ) );
            
            // Enable support for wide align blocks
            add_theme_support( 'align-wide' );
            
            // Add support for responsive embedded content
            add_theme_support( 'responsive-embeds' );
            
            // Add support for editor styles
            add_theme_support( 'editor-styles' );
            add_editor_style( 'assets/dist/css/editor.css' );
            
            // Add support for block styles
            add_theme_support( 'wp-block-styles' );
            
            // Add support for full and wide align images
            add_theme_support( 'align-wide' );
            
            // Add support for custom line height controls
            add_theme_support( 'custom-line-height' );
            
            // Add support for custom units
            add_theme_support( 'custom-units' );
            
            // Add support for custom spacing
            add_theme_support( 'custom-spacing' );
            
            // Add support for link color controls
            add_theme_support( 'link-color' );
            
            // Add support for experimental cover block spacing
            add_theme_support( 'custom-spacing' );
            
            // Define content width
            if ( ! isset( $content_width ) ) {
                $content_width = 1200;
            }
        }
        
        /**
         * Initialize theme.
         */
        public function init() {
            // Register widget areas
            $this->register_widget_areas();
            
            // Register custom post types
            $this->register_post_types();
            
            // Register custom taxonomies
            $this->register_taxonomies();
        }
        
        /**
         * Enqueue scripts and styles.
         */
        public function enqueue_scripts() {
            // Get mix manifest for cache busting
            $mix_manifest = $this->get_mix_manifest();
            
            // Main theme stylesheet
            wp_enqueue_style( 
                'aqualuxe-style', 
                $this->get_asset_url( 'css/app.css', $mix_manifest ), 
                array(), 
                AQUALUXE_VERSION 
            );
            
            // Main theme script
            wp_enqueue_script( 
                'aqualuxe-script', 
                $this->get_asset_url( 'js/app.js', $mix_manifest ), 
                array( 'jquery' ), 
                AQUALUXE_VERSION, 
                true 
            );
            
            // Localize script
            wp_localize_script( 'aqualuxe-script', 'aqualuxe_ajax', array(
                'ajax_url' => admin_url( 'admin-ajax.php' ),
                'nonce'    => wp_create_nonce( 'aqualuxe_nonce' ),
                'strings'  => array(
                    'loading' => esc_html__( 'Loading...', 'aqualuxe' ),
                    'error'   => esc_html__( 'An error occurred. Please try again.', 'aqualuxe' ),
                ),
            ) );
            
            // Comment reply script
            if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
                wp_enqueue_script( 'comment-reply' );
            }
        }
        
        /**
         * Load core functionality.
         */
        private function load_core() {
            $core_files = array(
                'core/class-assets.php',
                'core/class-customizer.php',
                'core/class-post-types.php',
                'core/class-taxonomies.php',
                'core/class-widgets.php',
                'core/class-admin.php',
                'core/class-performance.php',
                'core/class-security.php',
                'core/class-seo.php',
            );
            
            foreach ( $core_files as $file ) {
                $file_path = AQUALUXE_THEME_DIR . '/' . $file;
                if ( file_exists( $file_path ) ) {
                    require_once $file_path;
                }
            }
        }
        
        /**
         * Load theme modules.
         */
        private function load_modules() {
            $enabled_modules = $this->get_enabled_modules();
            
            foreach ( $enabled_modules as $module ) {
                $module_file = AQUALUXE_THEME_DIR . '/modules/' . $module . '/bootstrap.php';
                if ( file_exists( $module_file ) ) {
                    require_once $module_file;
                }
            }
        }
        
        /**
         * Get enabled modules.
         *
         * @return array
         */
        private function get_enabled_modules() {
            $default_modules = array(
                'dark-mode',
                'multilingual', 
                'demo-importer',
                'marketplace',
                'classifieds',
                'multicurrency',
                'performance',
                'seo',
                'security',
            );
            
            // Allow modules to be filtered
            return apply_filters( 'aqualuxe_enabled_modules', $default_modules );
        }
        
        /**
         * WooCommerce integration.
         */
        private function woocommerce_integration() {
            if ( class_exists( 'WooCommerce' ) ) {
                require_once AQUALUXE_THEME_DIR . '/inc/integrations/woocommerce.php';
            } else {
                // Load fallback functionality
                require_once AQUALUXE_THEME_DIR . '/inc/integrations/woocommerce-fallback.php';
            }
        }
        
        /**
         * Register widget areas.
         */
        private function register_widget_areas() {
            register_sidebar( array(
                'name'          => esc_html__( 'Primary Sidebar', 'aqualuxe' ),
                'id'            => 'sidebar-1',
                'description'   => esc_html__( 'Add widgets here.', 'aqualuxe' ),
                'before_widget' => '<section id="%1$s" class="widget %2$s">',
                'after_widget'  => '</section>',
                'before_title'  => '<h3 class="widget-title">',
                'after_title'   => '</h3>',
            ) );
            
            register_sidebar( array(
                'name'          => esc_html__( 'Footer 1', 'aqualuxe' ),
                'id'            => 'footer-1',
                'description'   => esc_html__( 'Add widgets here.', 'aqualuxe' ),
                'before_widget' => '<section id="%1$s" class="widget %2$s">',
                'after_widget'  => '</section>',
                'before_title'  => '<h3 class="widget-title">',
                'after_title'   => '</h3>',
            ) );
            
            register_sidebar( array(
                'name'          => esc_html__( 'Footer 2', 'aqualuxe' ),
                'id'            => 'footer-2',
                'description'   => esc_html__( 'Add widgets here.', 'aqualuxe' ),
                'before_widget' => '<section id="%1$s" class="widget %2$s">',
                'after_widget'  => '</section>',
                'before_title'  => '<h3 class="widget-title">',
                'after_title'   => '</h3>',
            ) );
            
            register_sidebar( array(
                'name'          => esc_html__( 'Footer 3', 'aqualuxe' ),
                'id'            => 'footer-3',
                'description'   => esc_html__( 'Add widgets here.', 'aqualuxe' ),
                'before_widget' => '<section id="%1$s" class="widget %2$s">',
                'after_widget'  => '</section>',
                'before_title'  => '<h3 class="widget-title">',
                'after_title'   => '</h3>',
            ) );
            
            register_sidebar( array(
                'name'          => esc_html__( 'Footer 4', 'aqualuxe' ),
                'id'            => 'footer-4',
                'description'   => esc_html__( 'Add widgets here.', 'aqualuxe' ),
                'before_widget' => '<section id="%1$s" class="widget %2$s">',
                'after_widget'  => '</section>',
                'before_title'  => '<h3 class="widget-title">',
                'after_title'   => '</h3>',
            ) );
        }
        
        /**
         * Register custom post types.
         */
        private function register_post_types() {
            // Services post type
            register_post_type( 'service', array(
                'labels' => array(
                    'name'               => _x( 'Services', 'post type general name', 'aqualuxe' ),
                    'singular_name'      => _x( 'Service', 'post type singular name', 'aqualuxe' ),
                    'menu_name'          => _x( 'Services', 'admin menu', 'aqualuxe' ),
                    'name_admin_bar'     => _x( 'Service', 'add new on admin bar', 'aqualuxe' ),
                    'add_new'            => _x( 'Add New', 'service', 'aqualuxe' ),
                    'add_new_item'       => __( 'Add New Service', 'aqualuxe' ),
                    'new_item'           => __( 'New Service', 'aqualuxe' ),
                    'edit_item'          => __( 'Edit Service', 'aqualuxe' ),
                    'view_item'          => __( 'View Service', 'aqualuxe' ),
                    'all_items'          => __( 'All Services', 'aqualuxe' ),
                    'search_items'       => __( 'Search Services', 'aqualuxe' ),
                    'parent_item_colon'  => __( 'Parent Services:', 'aqualuxe' ),
                    'not_found'          => __( 'No services found.', 'aqualuxe' ),
                    'not_found_in_trash' => __( 'No services found in Trash.', 'aqualuxe' ),
                ),
                'public'             => true,
                'publicly_queryable' => true,
                'show_ui'            => true,
                'show_in_menu'       => true,
                'query_var'          => true,
                'rewrite'            => array( 'slug' => 'services' ),
                'capability_type'    => 'post',
                'has_archive'        => true,
                'hierarchical'       => false,
                'menu_position'      => null,
                'menu_icon'          => 'dashicons-admin-tools',
                'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
                'show_in_rest'       => true,
            ) );
            
            // Testimonials post type
            register_post_type( 'testimonial', array(
                'labels' => array(
                    'name'               => _x( 'Testimonials', 'post type general name', 'aqualuxe' ),
                    'singular_name'      => _x( 'Testimonial', 'post type singular name', 'aqualuxe' ),
                    'menu_name'          => _x( 'Testimonials', 'admin menu', 'aqualuxe' ),
                    'name_admin_bar'     => _x( 'Testimonial', 'add new on admin bar', 'aqualuxe' ),
                    'add_new'            => _x( 'Add New', 'testimonial', 'aqualuxe' ),
                    'add_new_item'       => __( 'Add New Testimonial', 'aqualuxe' ),
                    'new_item'           => __( 'New Testimonial', 'aqualuxe' ),
                    'edit_item'          => __( 'Edit Testimonial', 'aqualuxe' ),
                    'view_item'          => __( 'View Testimonial', 'aqualuxe' ),
                    'all_items'          => __( 'All Testimonials', 'aqualuxe' ),
                    'search_items'       => __( 'Search Testimonials', 'aqualuxe' ),
                    'parent_item_colon'  => __( 'Parent Testimonials:', 'aqualuxe' ),
                    'not_found'          => __( 'No testimonials found.', 'aqualuxe' ),
                    'not_found_in_trash' => __( 'No testimonials found in Trash.', 'aqualuxe' ),
                ),
                'public'             => true,
                'publicly_queryable' => true,
                'show_ui'            => true,
                'show_in_menu'       => true,
                'query_var'          => true,
                'rewrite'            => array( 'slug' => 'testimonials' ),
                'capability_type'    => 'post',
                'has_archive'        => true,
                'hierarchical'       => false,
                'menu_position'      => null,
                'menu_icon'          => 'dashicons-format-quote',
                'supports'           => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
                'show_in_rest'       => true,
            ) );
        }
        
        /**
         * Register custom taxonomies.
         */
        private function register_taxonomies() {
            // Service categories
            register_taxonomy( 'service_category', array( 'service' ), array(
                'hierarchical'      => true,
                'labels' => array(
                    'name'              => _x( 'Service Categories', 'taxonomy general name', 'aqualuxe' ),
                    'singular_name'     => _x( 'Service Category', 'taxonomy singular name', 'aqualuxe' ),
                    'search_items'      => __( 'Search Service Categories', 'aqualuxe' ),
                    'all_items'         => __( 'All Service Categories', 'aqualuxe' ),
                    'parent_item'       => __( 'Parent Service Category', 'aqualuxe' ),
                    'parent_item_colon' => __( 'Parent Service Category:', 'aqualuxe' ),
                    'edit_item'         => __( 'Edit Service Category', 'aqualuxe' ),
                    'update_item'       => __( 'Update Service Category', 'aqualuxe' ),
                    'add_new_item'      => __( 'Add New Service Category', 'aqualuxe' ),
                    'new_item_name'     => __( 'New Service Category Name', 'aqualuxe' ),
                    'menu_name'         => __( 'Service Category', 'aqualuxe' ),
                ),
                'show_ui'           => true,
                'show_admin_column' => true,
                'query_var'         => true,
                'rewrite'           => array( 'slug' => 'service-category' ),
                'show_in_rest'      => true,
            ) );
        }
        
        /**
         * Get mix manifest for asset versioning.
         *
         * @return array
         */
        private function get_mix_manifest() {
            $manifest_path = AQUALUXE_ASSETS_DIR . '/dist/mix-manifest.json';
            
            if ( file_exists( $manifest_path ) ) {
                return json_decode( file_get_contents( $manifest_path ), true );
            }
            
            return array();
        }
        
        /**
         * Get asset URL with proper versioning.
         *
         * @param string $asset
         * @param array  $manifest
         * @return string
         */
        private function get_asset_url( $asset, $manifest = array() ) {
            $asset_path = '/' . $asset;
            
            if ( isset( $manifest[ $asset_path ] ) ) {
                return AQUALUXE_ASSETS_URI . '/dist' . $manifest[ $asset_path ];
            }
            
            return AQUALUXE_ASSETS_URI . '/dist' . $asset_path;
        }
    }
}

/**
 * Initialize the theme.
 */
function aqualuxe_theme() {
    return AquaLuxe_Theme::get_instance();
}

// Initialize theme
aqualuxe_theme();

/**
 * Include additional functionality
 */
require_once AQUALUXE_THEME_DIR . '/inc/template-functions.php';
require_once AQUALUXE_THEME_DIR . '/inc/template-tags.php';
require_once AQUALUXE_THEME_DIR . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
    require_once AQUALUXE_THEME_DIR . '/inc/jetpack.php';
}