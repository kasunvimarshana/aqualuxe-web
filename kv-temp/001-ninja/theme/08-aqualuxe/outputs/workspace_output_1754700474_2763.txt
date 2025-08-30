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

// Define theme constants.
define( 'AQUALUXE_VERSION', '1.0.0' );
define( 'AQUALUXE_DIR', get_template_directory() );
define( 'AQUALUXE_URI', get_template_directory_uri() );

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

	// Set default thumbnail size.
	set_post_thumbnail_size( 1200, 9999 );

	// Add custom image sizes.
	add_image_size( 'aqualuxe-featured', 1600, 900, true );
	add_image_size( 'aqualuxe-card', 600, 400, true );
	add_image_size( 'aqualuxe-thumbnail', 300, 300, true );

	// Register nav menus.
	register_nav_menus(
		array(
			'menu-1' => esc_html__( 'Primary Menu', 'aqualuxe' ),
			'footer' => esc_html__( 'Footer Menu', 'aqualuxe' ),
			'social' => esc_html__( 'Social Menu', 'aqualuxe' ),
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

	// Add support for editor styles.
	add_theme_support( 'editor-styles' );

	// Add support for responsive embeds.
	add_theme_support( 'responsive-embeds' );

	// Add support for full and wide align images.
	add_theme_support( 'align-wide' );

	// Add support for WooCommerce.
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
 * Register widget areas.
 */
function aqualuxe_widgets_init() {
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
			'description'   => esc_html__( 'Add widgets here to appear in footer column 2.', 'aqualuxe' ),
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
			'description'   => esc_html__( 'Add widgets here to appear in footer column 3.', 'aqualuxe' ),
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
			'description'   => esc_html__( 'Add widgets here to appear in footer column 4.', 'aqualuxe' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

	if ( class_exists( 'WooCommerce' ) ) {
		register_sidebar(
			array(
				'name'          => esc_html__( 'Shop Sidebar', 'aqualuxe' ),
				'id'            => 'shop-sidebar',
				'description'   => esc_html__( 'Add widgets here to appear in shop sidebar.', 'aqualuxe' ),
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
 * Include required files.
 */
require_once AQUALUXE_DIR . '/inc/scripts.php';

// Include template functions.
require_once AQUALUXE_DIR . '/inc/template-functions.php';
require_once AQUALUXE_DIR . '/inc/template-tags.php';

// Include customizer options.
require_once AQUALUXE_DIR . '/inc/customizer.php';

// Include custom post types.
require_once AQUALUXE_DIR . '/inc/custom-post-types.php';

// Include WooCommerce compatibility file if WooCommerce is active.
if ( class_exists( 'WooCommerce' ) ) {
	require_once AQUALUXE_DIR . '/inc/woocommerce.php';
}

// Include AJAX handlers.
require_once AQUALUXE_DIR . '/inc/ajax-handlers.php';

// Include theme options.
require_once AQUALUXE_DIR . '/inc/theme-options.php';

// Include custom widgets.
require_once AQUALUXE_DIR . '/inc/widgets.php';

// Include multilingual support.
require_once AQUALUXE_DIR . '/inc/multilingual.php';

// Include schema markup.
require_once AQUALUXE_DIR . '/inc/schema-markup.php';

// Include demo importer.
require_once AQUALUXE_DIR . '/inc/demo-importer.php';