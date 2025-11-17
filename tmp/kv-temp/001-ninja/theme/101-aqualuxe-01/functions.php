<?php
/**
 * AquaLuxe functions and definitions
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

/**
 * AquaLuxe only works in WordPress 5.0 or later.
 */
if ( version_compare( $GLOBALS['wp_version'], '5.0', '<' ) ) {
    require AQUALUXE_THEME_DIR . '/inc/back-compat.php';
    return;
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
function aqualuxe_setup() {
    // Make theme available for translation
    load_theme_textdomain( 'aqualuxe', AQUALUXE_THEME_DIR . '/languages' );

    // Add default posts and comments RSS feed links to head
    add_theme_support( 'automatic-feed-links' );

    // Let WordPress manage the document title
    add_theme_support( 'title-tag' );

    // Enable support for Post Thumbnails on posts and pages
    add_theme_support( 'post-thumbnails' );

    // Switch default core markup for search form, comment form, and comments to output valid HTML5
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

    // Add support for responsive embedded content
    add_theme_support( 'responsive-embeds' );

    // Add support for editor styles
    add_theme_support( 'editor-styles' );

    // Add support for wide alignment
    add_theme_support( 'align-wide' );

    // Add support for block editor color palette
    add_theme_support( 'editor-color-palette', array(
        array(
            'name'  => esc_html__( 'Primary', 'aqualuxe' ),
            'slug'  => 'primary',
            'color' => '#007cba',
        ),
        array(
            'name'  => esc_html__( 'Secondary', 'aqualuxe' ),
            'slug'  => 'secondary',
            'color' => '#006ba1',
        ),
        array(
            'name'  => esc_html__( 'Dark Gray', 'aqualuxe' ),
            'slug'  => 'dark-gray',
            'color' => '#111111',
        ),
        array(
            'name'  => esc_html__( 'Light Gray', 'aqualuxe' ),
            'slug'  => 'light-gray',
            'color' => '#767676',
        ),
        array(
            'name'  => esc_html__( 'White', 'aqualuxe' ),
            'slug'  => 'white',
            'color' => '#FFFFFF',
        ),
    ) );

    // Register navigation menus
    register_nav_menus( array(
        'primary' => esc_html__( 'Primary Navigation', 'aqualuxe' ),
        'footer'  => esc_html__( 'Footer Navigation', 'aqualuxe' ),
        'mobile'  => esc_html__( 'Mobile Navigation', 'aqualuxe' ),
    ) );
}
add_action( 'after_setup_theme', 'aqualuxe_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 */
function aqualuxe_content_width() {
    $GLOBALS['content_width'] = apply_filters( 'aqualuxe_content_width', 1200 );
}
add_action( 'after_setup_theme', 'aqualuxe_content_width', 0 );

/**
 * Register widget area.
 */
function aqualuxe_widgets_init() {
    register_sidebar( array(
        'name'          => esc_html__( 'Sidebar', 'aqualuxe' ),
        'id'            => 'sidebar-1',
        'description'   => esc_html__( 'Add widgets here.', 'aqualuxe' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ) );

    register_sidebar( array(
        'name'          => esc_html__( 'Footer 1', 'aqualuxe' ),
        'id'            => 'footer-1',
        'description'   => esc_html__( 'Add widgets here to appear in your footer.', 'aqualuxe' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ) );

    register_sidebar( array(
        'name'          => esc_html__( 'Footer 2', 'aqualuxe' ),
        'id'            => 'footer-2',
        'description'   => esc_html__( 'Add widgets here to appear in your footer.', 'aqualuxe' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ) );

    register_sidebar( array(
        'name'          => esc_html__( 'Footer 3', 'aqualuxe' ),
        'id'            => 'footer-3',
        'description'   => esc_html__( 'Add widgets here to appear in your footer.', 'aqualuxe' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ) );
}
add_action( 'widgets_init', 'aqualuxe_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function aqualuxe_scripts() {
    // Get the theme version
    $theme_version = wp_get_theme()->get( 'Version' );
    
    // Load compiled assets if they exist, otherwise fallback to source files
    $manifest_path = AQUALUXE_THEME_DIR . '/assets/dist/mix-manifest.json';
    
    if ( file_exists( $manifest_path ) ) {
        $manifest = json_decode( file_get_contents( $manifest_path ), true );
        
        // Enqueue compiled CSS
        if ( isset( $manifest['/css/app.css'] ) ) {
            wp_enqueue_style( 'aqualuxe-style', AQUALUXE_THEME_URI . '/assets/dist' . $manifest['/css/app.css'], array(), $theme_version );
        }
        
        // Enqueue compiled JS
        if ( isset( $manifest['/js/app.js'] ) ) {
            wp_enqueue_script( 'aqualuxe-script', AQUALUXE_THEME_URI . '/assets/dist' . $manifest['/js/app.js'], array(), $theme_version, true );
        }
    } else {
        // Fallback to main style.css
        wp_enqueue_style( 'aqualuxe-style', get_stylesheet_uri(), array(), $theme_version );
    }

    // Enqueue WordPress comment reply script if needed
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }

    // Localize script for AJAX
    wp_localize_script( 'aqualuxe-script', 'aqualuxe', array(
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
        'nonce'   => wp_create_nonce( 'aqualuxe_nonce' ),
    ) );
}
add_action( 'wp_enqueue_scripts', 'aqualuxe_scripts' );

/**
 * Include required files.
 */
require AQUALUXE_THEME_DIR . '/inc/customizer.php';
require AQUALUXE_THEME_DIR . '/inc/template-tags.php';
require AQUALUXE_THEME_DIR . '/inc/template-functions.php';

// Include test file for development (remove in production)
if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
    require AQUALUXE_THEME_DIR . '/inc/test-media-import.php';
}

// Load WooCommerce compatibility file if WooCommerce is active
if ( class_exists( 'WooCommerce' ) ) {
    require AQUALUXE_THEME_DIR . '/inc/woocommerce.php';
}

// Load modular architecture
require AQUALUXE_THEME_DIR . '/core/class-aqualuxe-theme.php';

/**
 * Initialize the theme
 */
function aqualuxe_init() {
    if ( class_exists( 'AquaLuxe_Theme' ) ) {
        AquaLuxe_Theme::get_instance();
    }
}
add_action( 'init', 'aqualuxe_init' );