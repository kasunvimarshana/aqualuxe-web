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
 * Define Constants
 */
define( 'AQUALUXE_VERSION', '1.0.0' );
define( 'AQUALUXE_DIR', trailingslashit( get_template_directory() ) );
define( 'AQUALUXE_URI', trailingslashit( get_template_directory_uri() ) );
define( 'AQUALUXE_ASSETS_URI', trailingslashit( AQUALUXE_URI . 'assets' ) );
define( 'AQUALUXE_ASSETS_DIR', trailingslashit( AQUALUXE_DIR . 'assets' ) );
define( 'AQUALUXE_INC_DIR', trailingslashit( AQUALUXE_DIR . 'inc' ) );
define( 'AQUALUXE_INC_URI', trailingslashit( AQUALUXE_URI . 'inc' ) );
define( 'AQUALUXE_DEMO_DATA_DIR', trailingslashit( AQUALUXE_INC_DIR . 'demo-data' ) );
define( 'AQUALUXE_DEMO_DATA_URI', trailingslashit( AQUALUXE_INC_URI . 'demo-data' ) );

/**
 * AquaLuxe Theme Setup
 */
require_once AQUALUXE_INC_DIR . 'core/class-aqualuxe-theme.php';

/**
 * Initialize the theme
 */
function aqualuxe_init() {
	$theme = new AquaLuxe\Core\Theme();
	$theme->initialize();
}
add_action( 'after_setup_theme', 'aqualuxe_init' );

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

	// Register navigation menus.
	register_nav_menus(
		array(
			'primary' => esc_html__( 'Primary Menu', 'aqualuxe' ),
			'mobile'  => esc_html__( 'Mobile Menu', 'aqualuxe' ),
			'footer'  => esc_html__( 'Footer Menu', 'aqualuxe' ),
		)
	);

	// Switch default core markup for search form, comment form, and comments to output valid HTML5.
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

	// Add support for Block Styles.
	add_theme_support( 'wp-block-styles' );

	// Add support for full and wide align images.
	add_theme_support( 'align-wide' );

	// Add support for responsive embeds.
	add_theme_support( 'responsive-embeds' );

	// Add support for custom line height controls.
	add_theme_support( 'custom-line-height' );

	// Add support for custom units.
	add_theme_support( 'custom-units' );

	// Add support for experimental link color control.
	add_theme_support( 'experimental-link-color' );

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

	// Add editor styles.
	add_theme_support( 'editor-styles' );
	add_editor_style( 'assets/css/editor-style.css' );

	// Add support for WooCommerce.
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
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

	// Register WooCommerce specific widget areas if WooCommerce is active.
	if ( class_exists( 'WooCommerce' ) ) {
		register_sidebar(
			array(
				'name'          => esc_html__( 'Shop Sidebar', 'aqualuxe' ),
				'id'            => 'shop-sidebar',
				'description'   => esc_html__( 'Add shop widgets here.', 'aqualuxe' ),
				'before_widget' => '<section id="%1$s" class="widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => '<h2 class="widget-title">',
				'after_title'   => '</h2>',
			)
		);

		register_sidebar(
			array(
				'name'          => esc_html__( 'Product Sidebar', 'aqualuxe' ),
				'id'            => 'product-sidebar',
				'description'   => esc_html__( 'Add product widgets here.', 'aqualuxe' ),
				'before_widget' => '<section id="%1$s" class="widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => '<h2 class="widget-title">',
				'after_title'   => '</h2>',
			)
		);
	}
}
add_action( 'widgets_init', 'aqualuxe_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function aqualuxe_scripts() {
	// Enqueue styles.
	wp_enqueue_style( 'aqualuxe-style', AQUALUXE_ASSETS_URI . 'css/main.css', array(), AQUALUXE_VERSION );
	
	// Add RTL support.
	wp_style_add_data( 'aqualuxe-style', 'rtl', 'replace' );

	// Enqueue scripts.
	wp_enqueue_script( 'aqualuxe-navigation', AQUALUXE_ASSETS_URI . 'js/navigation.js', array(), AQUALUXE_VERSION, true );
	wp_enqueue_script( 'aqualuxe-skip-link-focus-fix', AQUALUXE_ASSETS_URI . 'js/skip-link-focus-fix.js', array(), AQUALUXE_VERSION, true );
	wp_enqueue_script( 'aqualuxe-keyboard-navigation', AQUALUXE_ASSETS_URI . 'js/keyboard-navigation.js', array(), AQUALUXE_VERSION, true );
	wp_enqueue_script( 'aqualuxe-accessibility', AQUALUXE_ASSETS_URI . 'js/accessibility.js', array(), AQUALUXE_VERSION, true );
	wp_enqueue_script( 'aqualuxe-main', AQUALUXE_ASSETS_URI . 'js/main.js', array('jquery'), AQUALUXE_VERSION, true );

	// Localize the script with new data.
	wp_localize_script(
		'aqualuxe-main',
		'aqualuxeVars',
		array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'aqualuxe-nonce' ),
		)
	);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	// Enqueue WooCommerce specific styles and scripts if WooCommerce is active.
	if ( class_exists( 'WooCommerce' ) ) {
		wp_enqueue_style( 'aqualuxe-woocommerce', AQUALUXE_ASSETS_URI . 'css/woocommerce.css', array('aqualuxe-style'), AQUALUXE_VERSION );
		wp_enqueue_script( 'aqualuxe-woocommerce', AQUALUXE_ASSETS_URI . 'js/woocommerce.js', array('jquery'), AQUALUXE_VERSION, true );
	}
}
add_action( 'wp_enqueue_scripts', 'aqualuxe_scripts' );

/**
 * Implement the Custom Header feature.
 */
require AQUALUXE_INC_DIR . 'core/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require AQUALUXE_INC_DIR . 'core/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require AQUALUXE_INC_DIR . 'core/template-functions.php';

/**
 * Customizer additions.
 */
require AQUALUXE_INC_DIR . 'customizer/customizer.php';

/**
 * Load helper functions.
 */
require AQUALUXE_INC_DIR . 'helpers/helpers.php';

/**
 * Load WooCommerce compatibility file.
 */
if ( class_exists( 'WooCommerce' ) ) {
	require AQUALUXE_INC_DIR . 'woocommerce/woocommerce.php';
}

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require AQUALUXE_INC_DIR . 'core/jetpack.php';
}