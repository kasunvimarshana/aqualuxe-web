<?php
/**
 * AquaLuxe functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Define theme constants
 */
// Define theme version
if (!defined('AQUALUXE_VERSION')) {
    define('AQUALUXE_VERSION', '1.0.0');
}

// Define API version
if (!defined('AQUALUXE_API_VERSION')) {
    define('AQUALUXE_API_VERSION', '1.0.0');
}

define( 'AQUALUXE_DIR', trailingslashit( get_template_directory() ) );
define( 'AQUALUXE_URI', trailingslashit( get_template_directory_uri() ) );

/**
 * Load theme text domain for translations
 */
function aqualuxe_load_textdomain() {
    load_theme_textdomain( 'aqualuxe', AQUALUXE_DIR . 'languages' );
}
add_action( 'init', 'aqualuxe_load_textdomain' );

/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
function aqualuxe_setup() {
    // Add default posts and comments RSS feed links to head.
    add_theme_support( 'automatic-feed-links' );

    // Let WordPress manage the document title.
    add_theme_support( 'title-tag' );

    // Enable support for Post Thumbnails on posts and pages.
    add_theme_support( 'post-thumbnails' );

    // Register navigation menus
    register_nav_menus(
        array(
            'primary' => esc_html__( 'Primary Menu', 'aqualuxe' ),
            'footer'  => esc_html__( 'Footer Menu', 'aqualuxe' ),
            'social'  => esc_html__( 'Social Menu', 'aqualuxe' ),
        )
    );

    // Switch default core markup to output valid HTML5.
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

    // Add support for custom logo.
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

    // Add support for editor styles.
    add_theme_support( 'editor-styles' );

    // Add support for responsive embeds.
    add_theme_support( 'responsive-embeds' );

    // Add WooCommerce support - this is safe even if WooCommerce isn't active
    add_theme_support( 'woocommerce' );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
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
 * Load WooCommerce compatibility file.
 * This file contains all the centralized WooCommerce compatibility functions.
 */
require AQUALUXE_DIR . 'inc/woocommerce-compatibility.php';

/**
 * Custom template tags for this theme.
 */
require AQUALUXE_DIR . 'inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require AQUALUXE_DIR . 'inc/template-functions.php';

/**
 * Customizer additions.
 */
require AQUALUXE_DIR . 'inc/customizer/customizer.php';

/**
 * Script and Style loader.
 */
require AQUALUXE_DIR . 'inc/script-loader.php';

/**
 * Font loader.
 */
require AQUALUXE_DIR . 'inc/font-loader.php';

/**
 * Load WooCommerce compatibility files.
 */
if ( aqualuxe_is_woocommerce_active() ) {
    // Include WooCommerce compatibility file
    require AQUALUXE_DIR . 'inc/woocommerce.php';
}

// Always load WooCommerce fallbacks to ensure theme works without WooCommerce
require AQUALUXE_DIR . 'inc/woocommerce-fallbacks.php';

/**
 * Load custom post types.
 */
require AQUALUXE_DIR . 'inc/custom-post-types.php';

/**
 * Load custom taxonomies.
 */
require AQUALUXE_DIR . 'inc/custom-taxonomies.php';

/**
 * Load helper functions.
 */
require AQUALUXE_DIR . 'inc/helpers/helpers.php';

/**
 * Load widgets.
 */
require AQUALUXE_DIR . 'inc/widgets/widgets.php';

/**
 * Load custom shortcodes.
 */
require AQUALUXE_DIR . 'inc/shortcodes.php';

/**
 * Load custom blocks.
 */
require AQUALUXE_DIR . 'inc/blocks.php';

/**
 * Load multilingual support.
 */
require AQUALUXE_DIR . 'inc/multilingual.php';

/**
 * Load dark mode functionality.
 */
require AQUALUXE_DIR . 'inc/dark-mode.php';
require AQUALUXE_DIR . 'inc/recovery-mode-fix.php';

/**
 * Load custom post types.
 */
// Booking System
require AQUALUXE_DIR . 'inc/post-types/booking.php';

// Auction System
require AQUALUXE_DIR . 'inc/post-types/auction.php';

// Trade-In System
require AQUALUXE_DIR . 'inc/post-types/trade-in.php';

// Franchise Inquiry System
require AQUALUXE_DIR . 'inc/post-types/franchise.php';

// Care Guide System
require AQUALUXE_DIR . 'inc/post-types/care-guide.php';

// Subscription System
require AQUALUXE_DIR . 'inc/post-types/subscription.php';

// Analytics Dashboard
require AQUALUXE_DIR . 'inc/post-types/analytics-dashboard.php';

/**
 * Load function files.
 */
// Booking System Functions
require AQUALUXE_DIR . 'inc/booking-functions.php';

// Auction System Functions
require AQUALUXE_DIR . 'inc/auction-functions.php';

// Trade-In System Functions
require AQUALUXE_DIR . 'inc/trade-in-functions.php';

// Franchise Inquiry System Functions
require AQUALUXE_DIR . 'inc/franchise-functions.php';

// Care Guide System Functions
require AQUALUXE_DIR . 'inc/care-guide-functions.php';

// Water Calculator Functions
require AQUALUXE_DIR . 'inc/water-calculator-functions.php';

// Compatibility Checker Functions
require AQUALUXE_DIR . 'inc/compatibility-checker-functions.php';

// Subscription Functions
require AQUALUXE_DIR . 'inc/subscription-functions.php';

// Analytics Dashboard
// Include analytics wrapper first to ensure compatibility
require AQUALUXE_DIR . 'inc/analytics/class-aqualuxe-analytics-wrapper.php';

// Only include full analytics functionality if WooCommerce is active
if ( aqualuxe_is_woocommerce_active() ) {
    require AQUALUXE_DIR . 'inc/analytics/class-aqualuxe-analytics.php';
}

// API Admin
// Include API wrapper first to ensure compatibility
require AQUALUXE_DIR . 'inc/api/class-aqualuxe-api-wrapper.php';

// Include API admin
require AQUALUXE_DIR . 'inc/admin/class-aqualuxe-api-admin.php';

/**
 * Load demo content importer.
 */
require AQUALUXE_DIR . 'inc/demo-importer/demo-importer.php';