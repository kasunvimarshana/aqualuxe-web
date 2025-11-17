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

// Define theme constants.
define( 'AQUALUXE_VERSION', '1.0.0' );
define( 'AQUALUXE_DIR', trailingslashit( get_template_directory() ) );
define( 'AQUALUXE_URI', trailingslashit( get_template_directory_uri() ) );

/**
 * AquaLuxe setup.
 *
 * Sets up theme defaults and registers support for various WordPress features.
 */
function aqualuxe_setup() {
	// Load text domain for translation.
	load_theme_textdomain( 'aqualuxe', AQUALUXE_DIR . 'languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	// Let WordPress manage the document title.
	add_theme_support( 'title-tag' );

	// Enable support for Post Thumbnails on posts and pages.
	add_theme_support( 'post-thumbnails' );

	// Register navigation menus.
	register_nav_menus(
		array(
			'primary'   => esc_html__( 'Primary Menu', 'aqualuxe' ),
			'secondary' => esc_html__( 'Secondary Menu', 'aqualuxe' ),
			'footer'    => esc_html__( 'Footer Menu', 'aqualuxe' ),
			'social'    => esc_html__( 'Social Links Menu', 'aqualuxe' ),
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

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	// Add support for editor styles.
	add_theme_support( 'editor-styles' );
	add_editor_style( 'assets/css/editor.css' );

	// Add support for responsive embeds.
	add_theme_support( 'responsive-embeds' );

	// Add support for full and wide align images.
	add_theme_support( 'align-wide' );

	// Add support for custom logo.
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 80,
			'width'       => 240,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);

	// Add support for custom colors.
	add_theme_support(
		'editor-color-palette',
		array(
			array(
				'name'  => esc_html__( 'Primary', 'aqualuxe' ),
				'slug'  => 'primary',
				'color' => '#0073aa',
			),
			array(
				'name'  => esc_html__( 'Secondary', 'aqualuxe' ),
				'slug'  => 'secondary',
				'color' => '#23282d',
			),
			array(
				'name'  => esc_html__( 'Accent', 'aqualuxe' ),
				'slug'  => 'accent',
				'color' => '#00a0d2',
			),
			array(
				'name'  => esc_html__( 'Dark', 'aqualuxe' ),
				'slug'  => 'dark',
				'color' => '#333333',
			),
			array(
				'name'  => esc_html__( 'Light', 'aqualuxe' ),
				'slug'  => 'light',
				'color' => '#f8f9fa',
			),
		)
	);

	// Add support for custom font sizes.
	add_theme_support(
		'editor-font-sizes',
		array(
			array(
				'name'      => esc_html__( 'Small', 'aqualuxe' ),
				'shortName' => esc_html_x( 'S', 'Font size', 'aqualuxe' ),
				'size'      => 14,
				'slug'      => 'small',
			),
			array(
				'name'      => esc_html__( 'Normal', 'aqualuxe' ),
				'shortName' => esc_html_x( 'M', 'Font size', 'aqualuxe' ),
				'size'      => 16,
				'slug'      => 'normal',
			),
			array(
				'name'      => esc_html__( 'Large', 'aqualuxe' ),
				'shortName' => esc_html_x( 'L', 'Font size', 'aqualuxe' ),
				'size'      => 20,
				'slug'      => 'large',
			),
			array(
				'name'      => esc_html__( 'Extra Large', 'aqualuxe' ),
				'shortName' => esc_html_x( 'XL', 'Font size', 'aqualuxe' ),
				'size'      => 24,
				'slug'      => 'extra-large',
			),
		)
	);

	// Register image sizes.
	add_image_size( 'aqualuxe-featured', 1200, 675, true );
	add_image_size( 'aqualuxe-product', 600, 600, true );
	add_image_size( 'aqualuxe-thumbnail', 400, 300, true );
}
add_action( 'after_setup_theme', 'aqualuxe_setup' );

/**
 * Set the content width in pixels.
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
			'description'   => esc_html__( 'Add widgets here to appear in the first footer column.', 'aqualuxe' ),
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
			'description'   => esc_html__( 'Add widgets here to appear in the second footer column.', 'aqualuxe' ),
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
			'description'   => esc_html__( 'Add widgets here to appear in the third footer column.', 'aqualuxe' ),
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
			'description'   => esc_html__( 'Add widgets here to appear in the fourth footer column.', 'aqualuxe' ),
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
				'description'   => esc_html__( 'Add widgets here to appear in your shop sidebar.', 'aqualuxe' ),
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

// Core functionality.
require_once AQUALUXE_DIR . 'inc/core/i18n.php';
require_once AQUALUXE_DIR . 'inc/core/accessibility.php';

// Theme setup.
require_once AQUALUXE_DIR . 'inc/setup/theme-setup.php';

// Asset management.
require_once AQUALUXE_DIR . 'inc/assets/enqueue.php';

// Security hardening.
require_once AQUALUXE_DIR . 'inc/security/hardening.php';

// SEO optimization.
require_once AQUALUXE_DIR . 'inc/seo/schema.php';

// Customizer settings.
require_once AQUALUXE_DIR . 'inc/customizer/register.php';

// Hook system.
require_once AQUALUXE_DIR . 'inc/hooks/hooks.php';

// Utility functions.
require_once AQUALUXE_DIR . 'inc/utils/template-tags.php';

// Custom Gutenberg blocks.
require_once AQUALUXE_DIR . 'inc/blocks/class-aqualuxe-blocks.php';

// WooCommerce integration.
if ( class_exists( 'WooCommerce' ) ) {
	require_once AQUALUXE_DIR . 'inc/integrations/woocommerce.php';
} else {
	require_once AQUALUXE_DIR . 'inc/integrations/woocommerce-fallback.php';
}