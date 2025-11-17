<?php
/**
 * AquaLuxe functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Define theme constants
 */
define( 'AQUALUXE_VERSION', '1.0.0' );
define( 'AQUALUXE_DIR', get_template_directory() );
define( 'AQUALUXE_URI', get_template_directory_uri() );

/**
 * Load autoloader
 */
require_once AQUALUXE_DIR . '/core/Autoloader.php';

/**
 * Initialize autoloader
 */
$autoloader = new \AquaLuxe\Core\Autoloader();
$autoloader->register();
$autoloader->add_namespace( 'AquaLuxe\\Core', AQUALUXE_DIR . '/core' );
$autoloader->add_namespace( 'AquaLuxe\\Modules', AQUALUXE_DIR . '/modules' );

/**
 * Include core files
 */
require_once AQUALUXE_DIR . '/inc/template-functions.php';
require_once AQUALUXE_DIR . '/inc/template-hooks.php';
require_once AQUALUXE_DIR . '/inc/module-functions.php';
require_once AQUALUXE_DIR . '/inc/customizer.php';

/**
 * Include WooCommerce compatibility file if WooCommerce is active
 */
if ( class_exists( 'WooCommerce' ) ) {
    require_once AQUALUXE_DIR . '/inc/woocommerce.php';
}

/**
 * Theme setup
 */
function aqualuxe_setup() {
    /*
     * Make theme available for translation.
     * Translations can be filed in the /languages/ directory.
     */
    load_theme_textdomain( 'aqualuxe', AQUALUXE_DIR . '/languages' );

    // Add default posts and comments RSS feed links to head.
    add_theme_support( 'automatic-feed-links' );

    /*
     * Let WordPress manage the document title.
     * By adding theme support, we declare that this theme does not use a
     * hard-coded <title> tag in the document head, and expect WordPress to
     * provide it for us.
     */
    add_theme_support( 'title-tag' );

    /*
     * Enable support for Post Thumbnails on posts and pages.
     *
     * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
     */
    add_theme_support( 'post-thumbnails' );

    // Register navigation menus
    register_nav_menus(
        array(
            'primary'   => esc_html__( 'Primary Menu', 'aqualuxe' ),
            'secondary' => esc_html__( 'Secondary Menu', 'aqualuxe' ),
            'footer'    => esc_html__( 'Footer Menu', 'aqualuxe' ),
        )
    );

    /*
     * Switch default core markup for search form, comment form, and comments
     * to output valid HTML5.
     */
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

    // Set up the WordPress core custom background feature.
    add_theme_support(
        'custom-background',
        apply_filters(
            'aqualuxe_custom_background_args',
            array(
                'default-color' => 'ffffff',
                'default-image' => '',
            )
        )
    );

    // Add theme support for selective refresh for widgets.
    add_theme_support( 'customize-selective-refresh-widgets' );

    /**
     * Add support for core custom logo.
     *
     * @link https://codex.wordpress.org/Theme_Logo
     */
    add_theme_support(
        'custom-logo',
        array(
            'height'      => 250,
            'width'       => 250,
            'flex-width'  => true,
            'flex-height' => true,
        )
    );

    // Add support for full and wide align images.
    add_theme_support( 'align-wide' );

    // Add support for responsive embeds.
    add_theme_support( 'responsive-embeds' );

    // Add support for editor styles.
    add_theme_support( 'editor-styles' );

    // Add support for block templates.
    add_theme_support( 'block-templates' );
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
    $GLOBALS['content_width'] = apply_filters( 'aqualuxe_content_width', 1200 );
}
add_action( 'after_setup_theme', 'aqualuxe_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function aqualuxe_widgets_init() {
    register_sidebar(
        array(
            'name'          => esc_html__( 'Sidebar', 'aqualuxe' ),
            'id'            => 'sidebar-1',
            'description'   => esc_html__( 'Add widgets here.', 'aqualuxe' ),
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
            'description'   => esc_html__( 'Add footer widgets here.', 'aqualuxe' ),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        )
    );

    register_sidebar(
        array(
            'name'          => esc_html__( 'Footer 2', 'aqualuxe' ),
            'id'            => 'footer-2',
            'description'   => esc_html__( 'Add footer widgets here.', 'aqualuxe' ),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        )
    );

    register_sidebar(
        array(
            'name'          => esc_html__( 'Footer 3', 'aqualuxe' ),
            'id'            => 'footer-3',
            'description'   => esc_html__( 'Add footer widgets here.', 'aqualuxe' ),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        )
    );

    register_sidebar(
        array(
            'name'          => esc_html__( 'Footer 4', 'aqualuxe' ),
            'id'            => 'footer-4',
            'description'   => esc_html__( 'Add footer widgets here.', 'aqualuxe' ),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        )
    );
}
add_action( 'widgets_init', 'aqualuxe_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function aqualuxe_scripts() {
    // Enqueue styles
    wp_enqueue_style( 'aqualuxe-style', get_stylesheet_uri(), array(), AQUALUXE_VERSION );
    wp_enqueue_style( 'aqualuxe-main', AQUALUXE_URI . '/assets/dist/css/main.css', array(), AQUALUXE_VERSION );
    
    // Enqueue scripts
    wp_enqueue_script( 'aqualuxe-app', AQUALUXE_URI . '/assets/dist/js/app.js', array('jquery'), AQUALUXE_VERSION, true );

    // Enqueue WooCommerce styles and scripts if WooCommerce is active
    if ( class_exists( 'WooCommerce' ) ) {
        wp_enqueue_style( 'aqualuxe-woocommerce', AQUALUXE_URI . '/assets/dist/css/woocommerce.css', array(), AQUALUXE_VERSION );
        wp_enqueue_script( 'aqualuxe-woocommerce', AQUALUXE_URI . '/assets/dist/js/woocommerce.js', array('jquery', 'aqualuxe-app'), AQUALUXE_VERSION, true );
    }

    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}
add_action( 'wp_enqueue_scripts', 'aqualuxe_scripts' );

/**
 * Initialize the module loader
 */
function aqualuxe_init_modules() {
    // Initialize the module loader
    \AquaLuxe\Core\ModuleLoader::get_instance();
}
add_action( 'after_setup_theme', 'aqualuxe_init_modules', 20 );