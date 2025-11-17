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

// Set content width
if ( ! isset( $content_width ) ) {
    $content_width = 1200;
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
function aqualuxe_setup() {
    // Load text domain for translation
    load_theme_textdomain( 'aqualuxe', AQUALUXE_DIR . 'languages' );

    // Add default posts and comments RSS feed links to head
    add_theme_support( 'automatic-feed-links' );

    // Let WordPress manage the document title
    add_theme_support( 'title-tag' );

    // Enable support for Post Thumbnails on posts and pages
    add_theme_support( 'post-thumbnails' );

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

    // Add theme support for selective refresh for widgets
    add_theme_support( 'customize-selective-refresh-widgets' );

    // Add support for full and wide align images
    add_theme_support( 'align-wide' );

    // Add support for responsive embeds
    add_theme_support( 'responsive-embeds' );

    // Add support for custom logo
    add_theme_support( 'custom-logo', array(
        'height'      => 250,
        'width'       => 250,
        'flex-width'  => true,
        'flex-height' => true,
    ) );

    // Add support for editor styles
    add_theme_support( 'editor-styles' );

    // Add support for custom color palette
    add_theme_support( 'editor-color-palette', array(
        array(
            'name'  => esc_html__( 'Primary', 'aqualuxe' ),
            'slug'  => 'primary',
            'color' => '#0073aa',
        ),
        array(
            'name'  => esc_html__( 'Secondary', 'aqualuxe' ),
            'slug'  => 'secondary',
            'color' => '#005177',
        ),
        array(
            'name'  => esc_html__( 'Dark', 'aqualuxe' ),
            'slug'  => 'dark',
            'color' => '#111111',
        ),
        array(
            'name'  => esc_html__( 'Light', 'aqualuxe' ),
            'slug'  => 'light',
            'color' => '#f8f9fa',
        ),
        array(
            'name'  => esc_html__( 'White', 'aqualuxe' ),
            'slug'  => 'white',
            'color' => '#ffffff',
        ),
    ) );
}
add_action( 'after_setup_theme', 'aqualuxe_setup' );

/**
 * Include required files
 */
$aqualuxe_includes = array(
    'inc/helpers.php',              // Helper functions
    'inc/template-functions.php',   // Functions for templates
    'inc/template-tags.php',        // Custom template tags
    'inc/customizer.php',           // Customizer functionality
    'inc/widgets.php',              // Custom widgets
    'inc/scripts.php',              // Scripts and styles
    'inc/hooks.php',                // Theme hooks
    'inc/custom-post-types.php',    // Custom post types
    'inc/custom-taxonomies.php',    // Custom taxonomies
    'inc/shortcodes.php',           // Shortcodes
    'inc/blocks.php',               // Custom blocks
    'inc/multilingual.php',         // Multilingual support
    'inc/multi-currency.php',       // Multi-currency support
    'inc/demo-importer.php',        // Demo content importer
    'inc/performance.php',          // Performance optimization
    'inc/accessibility.php',        // Accessibility features
    'inc/seo.php',                  // SEO enhancements
    'inc/security-audit.php',       // Security audit checklist
);

// Include WooCommerce compatibility file if WooCommerce is active
if ( class_exists( 'WooCommerce' ) ) {
    $aqualuxe_includes[] = 'inc/woocommerce.php';
    $aqualuxe_includes[] = 'inc/woocommerce-filters.php';
    $aqualuxe_includes[] = 'inc/woocommerce-checkout.php';
    $aqualuxe_includes[] = 'inc/woocommerce-stock-notification.php';
    $aqualuxe_includes[] = 'inc/related-products.php';
    $aqualuxe_includes[] = 'inc/review-system.php';
} else {
    $aqualuxe_includes[] = 'inc/woocommerce-fallback.php';
}

// Load required files
foreach ( $aqualuxe_includes as $file ) {
    if ( file_exists( AQUALUXE_DIR . $file ) ) {
        require_once AQUALUXE_DIR . $file;
    }
}

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
        'description'   => esc_html__( 'Add footer widgets here.', 'aqualuxe' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ) );

    register_sidebar( array(
        'name'          => esc_html__( 'Footer 2', 'aqualuxe' ),
        'id'            => 'footer-2',
        'description'   => esc_html__( 'Add footer widgets here.', 'aqualuxe' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ) );

    register_sidebar( array(
        'name'          => esc_html__( 'Footer 3', 'aqualuxe' ),
        'id'            => 'footer-3',
        'description'   => esc_html__( 'Add footer widgets here.', 'aqualuxe' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ) );

    register_sidebar( array(
        'name'          => esc_html__( 'Footer 4', 'aqualuxe' ),
        'id'            => 'footer-4',
        'description'   => esc_html__( 'Add footer widgets here.', 'aqualuxe' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ) );

    // Shop sidebar (only register if WooCommerce is active)
    if ( class_exists( 'WooCommerce' ) ) {
        register_sidebar( array(
            'name'          => esc_html__( 'Shop Sidebar', 'aqualuxe' ),
            'id'            => 'shop-sidebar',
            'description'   => esc_html__( 'Add shop widgets here.', 'aqualuxe' ),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        ) );
    }
}
add_action( 'widgets_init', 'aqualuxe_widgets_init' );