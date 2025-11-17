<?php
/**
 * Theme functions and definitions
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define theme constants
define( 'AQUALUXE_VERSION', '1.0.0' );
define( 'AQUALUXE_THEME_DIR', get_template_directory() );
define( 'AQUALUXE_THEME_URI', get_template_directory_uri() );
define( 'AQUALUXE_INC_DIR', AQUALUXE_THEME_DIR . '/inc' );
define( 'AQUALUXE_MODULES_DIR', AQUALUXE_THEME_DIR . '/modules' );
define( 'AQUALUXE_ASSETS_URI', AQUALUXE_THEME_URI . '/assets/dist' );

// Require the autoloader
require_once AQUALUXE_INC_DIR . '/core/class-autoloader.php';

// Initialize the theme
require_once AQUALUXE_INC_DIR . '/core/class-bootstrap.php';

/**
 * Initialize AquaLuxe Theme
 *
 * @since 1.0.0
 */
function aqualuxe_init() {
    $bootstrap = new \AquaLuxe\Core\Bootstrap();
    $bootstrap->init();
}
add_action( 'after_setup_theme', 'aqualuxe_init' );

/**
 * Theme setup function
 *
 * @since 1.0.0
 */
function aqualuxe_setup() {
    // Load text domain
    load_theme_textdomain( 'aqualuxe', get_template_directory() . '/languages' );

    // Add default posts and comments RSS feed links to head
    add_theme_support( 'automatic-feed-links' );

    // Let WordPress manage the document title
    add_theme_support( 'title-tag' );

    // Enable support for Post Thumbnails on posts and pages
    add_theme_support( 'post-thumbnails' );

    // Enable support for responsive embedded content
    add_theme_support( 'responsive-embeds' );

    // Add support for core custom logo
    add_theme_support( 'custom-logo', array(
        'height'      => 100,
        'width'       => 400,
        'flex-width'  => true,
        'flex-height' => true,
    ) );

    // Enable support for HTML5 markup
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

    // Add support for editor styles
    add_theme_support( 'editor-styles' );

    // Enqueue editor styles
    add_editor_style( 'assets/dist/css/editor.css' );

    // Add support for full and wide align images
    add_theme_support( 'align-wide' );

    // Add support for WooCommerce
    add_theme_support( 'woocommerce' );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );

    // Register navigation menus
    register_nav_menus( array(
        'primary'   => esc_html__( 'Primary Menu', 'aqualuxe' ),
        'footer'    => esc_html__( 'Footer Menu', 'aqualuxe' ),
        'mobile'    => esc_html__( 'Mobile Menu', 'aqualuxe' ),
    ) );
}
add_action( 'after_setup_theme', 'aqualuxe_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function aqualuxe_content_width() {
    $GLOBALS['content_width'] = apply_filters( 'aqualuxe_content_width', 1140 );
}
add_action( 'after_setup_theme', 'aqualuxe_content_width', 0 );

/**
 * Register widget area.
 *
 * @since 1.0.0
 */
function aqualuxe_widgets_init() {
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
        'name'          => esc_html__( 'Footer Widgets', 'aqualuxe' ),
        'id'            => 'footer-widgets',
        'description'   => esc_html__( 'Add widgets here to appear in the footer.', 'aqualuxe' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ) );
}
add_action( 'widgets_init', 'aqualuxe_widgets_init' );

/**
 * Enqueue scripts and styles.
 *
 * @since 1.0.0
 */
function aqualuxe_scripts() {
    // Get asset manifest for cache busting
    $manifest_path = AQUALUXE_THEME_DIR . '/assets/dist/mix-manifest.json';
    $manifest = array();
    
    if ( file_exists( $manifest_path ) ) {
        $manifest = json_decode( file_get_contents( $manifest_path ), true );
    }
    
    // Main stylesheet
    $main_css = isset( $manifest['/css/app.css'] ) ? $manifest['/css/app.css'] : '/css/app.css';
    wp_enqueue_style( 'aqualuxe-style', AQUALUXE_ASSETS_URI . $main_css, array(), AQUALUXE_VERSION );
    
    // Main JavaScript
    $main_js = isset( $manifest['/js/app.js'] ) ? $manifest['/js/app.js'] : '/js/app.js';
    wp_enqueue_script( 'aqualuxe-script', AQUALUXE_ASSETS_URI . $main_js, array( 'jquery' ), AQUALUXE_VERSION, true );
    
    // Localize script for AJAX
    wp_localize_script( 'aqualuxe-script', 'aqualuxe_ajax', array(
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'nonce'    => wp_create_nonce( 'aqualuxe_nonce' ),
    ) );
    
    // Comments script
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}
add_action( 'wp_enqueue_scripts', 'aqualuxe_scripts' );

/**
 * Enqueue admin scripts and styles.
 *
 * @since 1.0.0
 */
function aqualuxe_admin_scripts() {
    // Get asset manifest for cache busting
    $manifest_path = AQUALUXE_THEME_DIR . '/assets/dist/mix-manifest.json';
    $manifest = array();
    
    if ( file_exists( $manifest_path ) ) {
        $manifest = json_decode( file_get_contents( $manifest_path ), true );
    }
    
    // Admin stylesheet
    $admin_css = isset( $manifest['/css/admin.css'] ) ? $manifest['/css/admin.css'] : '/css/admin.css';
    wp_enqueue_style( 'aqualuxe-admin-style', AQUALUXE_ASSETS_URI . $admin_css, array(), AQUALUXE_VERSION );
    
    // Admin JavaScript
    $admin_js = isset( $manifest['/js/admin.js'] ) ? $manifest['/js/admin.js'] : '/js/admin.js';
    wp_enqueue_script( 'aqualuxe-admin-script', AQUALUXE_ASSETS_URI . $admin_js, array( 'jquery' ), AQUALUXE_VERSION, true );
}
add_action( 'admin_enqueue_scripts', 'aqualuxe_admin_scripts' );

/**
 * Add preconnect for Google Fonts
 *
 * @since 1.0.0
 */
function aqualuxe_resource_hints( $urls, $relation_type ) {
    if ( wp_style_is( 'aqualuxe-fonts', 'queue' ) && 'preconnect' === $relation_type ) {
        $urls[] = array(
            'href' => 'https://fonts.gstatic.com',
            'crossorigin',
        );
    }

    return $urls;
}
add_filter( 'wp_resource_hints', 'aqualuxe_resource_hints', 10, 2 );

/**
 * Custom template tags for this theme.
 */
require AQUALUXE_INC_DIR . '/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require AQUALUXE_INC_DIR . '/template-functions.php';

/**
 * Customizer additions.
 */
require AQUALUXE_INC_DIR . '/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
    require AQUALUXE_INC_DIR . '/jetpack.php';
}