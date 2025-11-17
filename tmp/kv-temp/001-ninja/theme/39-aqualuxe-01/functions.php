<?php
/**
 * AquaLuxe functions and definitions
 *
 * @package AquaLuxe
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define constants
define( 'AQUALUXE_VERSION', '1.0.0' );
define( 'AQUALUXE_THEME_DIR', get_template_directory() );
define( 'AQUALUXE_THEME_URI', get_template_directory_uri() );
define( 'AQUALUXE_ASSETS_URI', AQUALUXE_THEME_URI . '/assets/dist' );

/**
 * Theme setup
 */
function aqualuxe_setup() {
	// Add theme support
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'custom-logo' );
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
		'style',
		'script'
	) );
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	
	// WooCommerce support
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );

	// Register navigation menus
	register_nav_menus( array(
		'primary'   => esc_html__( 'Primary Menu', 'aqualuxe' ),
		'secondary' => esc_html__( 'Secondary Menu', 'aqualuxe' ),
		'footer'    => esc_html__( 'Footer Menu', 'aqualuxe' ),
		'mobile'    => esc_html__( 'Mobile Menu', 'aqualuxe' ),
	) );

	// Add custom image sizes
	add_image_size( 'aqualuxe-hero', 1920, 800, true );
	add_image_size( 'aqualuxe-featured', 800, 600, true );
	add_image_size( 'aqualuxe-thumbnail', 400, 300, true );
	add_image_size( 'aqualuxe-product-large', 600, 600, true );
	add_image_size( 'aqualuxe-product-medium', 400, 400, true );

	// Load text domain
	load_theme_textdomain( 'aqualuxe', AQUALUXE_THEME_DIR . '/languages' );
}
add_action( 'after_setup_theme', 'aqualuxe_setup' );

/**
 * Include theme files
 */
$includes = array(
	'inc/class-aqualuxe-theme.php',
	'inc/class-aqualuxe-assets.php',
	'inc/class-aqualuxe-customizer.php',
	'inc/class-aqualuxe-woocommerce.php',
	'inc/theme-functions.php',
	'inc/template-tags.php',
	'inc/customizer.php',
	'inc/woocommerce.php',
	'inc/admin.php',
	'inc/demo-content.php',
);

foreach ( $includes as $file ) {
	$file_path = AQUALUXE_THEME_DIR . '/' . $file;
	if ( file_exists( $file_path ) ) {
		require_once $file_path;
	}
}

/**
 * Initialize theme
 */
function aqualuxe_init() {
	// Initialize main theme class
	if ( class_exists( 'AquaLuxe_Theme' ) ) {
		AquaLuxe_Theme::get_instance();
	}
	
	// Initialize assets class
	if ( class_exists( 'AquaLuxe_Assets' ) ) {
		AquaLuxe_Assets::get_instance();
	}
	
	// Initialize customizer
	if ( class_exists( 'AquaLuxe_Customizer' ) ) {
		AquaLuxe_Customizer::get_instance();
	}
	
	// Initialize WooCommerce integration
	if ( class_exists( 'AquaLuxe_WooCommerce' ) && aqualuxe_is_woocommerce_active() ) {
		AquaLuxe_WooCommerce::get_instance();
	}
}
add_action( 'init', 'aqualuxe_init' );

/**
 * Content width
 */
function aqualuxe_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'aqualuxe_content_width', 1200 );
}
add_action( 'after_setup_theme', 'aqualuxe_content_width', 0 );

/**
 * Register widget areas
 */
function aqualuxe_widgets_init() {
	$widgets = array(
		array(
			'name'        => esc_html__( 'Sidebar', 'aqualuxe' ),
			'id'          => 'sidebar-1',
			'description' => esc_html__( 'Add widgets here.', 'aqualuxe' ),
		),
		array(
			'name'        => esc_html__( 'Shop Sidebar', 'aqualuxe' ),
			'id'          => 'shop-sidebar',
			'description' => esc_html__( 'Shop page sidebar widgets.', 'aqualuxe' ),
		),
		array(
			'name'        => esc_html__( 'Footer 1', 'aqualuxe' ),
			'id'          => 'footer-1',
			'description' => esc_html__( 'Footer widget area 1.', 'aqualuxe' ),
		),
		array(
			'name'        => esc_html__( 'Footer 2', 'aqualuxe' ),
			'id'          => 'footer-2',
			'description' => esc_html__( 'Footer widget area 2.', 'aqualuxe' ),
		),
		array(
			'name'        => esc_html__( 'Footer 3', 'aqualuxe' ),
			'id'          => 'footer-3',
			'description' => esc_html__( 'Footer widget area 3.', 'aqualuxe' ),
		),
		array(
			'name'        => esc_html__( 'Footer 4', 'aqualuxe' ),
			'id'          => 'footer-4',
			'description' => esc_html__( 'Footer widget area 4.', 'aqualuxe' ),
		),
	);

	foreach ( $widgets as $widget ) {
		register_sidebar( array_merge( $widget, array(
			'before_widget' => '<section id="%1$s" class="widget %2$s mb-8">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="widget-title text-xl font-semibold mb-4 text-gray-900 dark:text-white">',
			'after_title'   => '</h3>',
		) ) );
	}
}
add_action( 'widgets_init', 'aqualuxe_widgets_init' );