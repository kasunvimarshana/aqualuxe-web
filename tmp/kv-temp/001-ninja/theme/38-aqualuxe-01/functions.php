<?php
/**
 * AquaLuxe functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Define Constants
 */
define( 'AQUALUXE_VERSION', '1.0.0' );
define( 'AQUALUXE_THEME_DIR', trailingslashit( get_template_directory() ) );
define( 'AQUALUXE_THEME_URI', trailingslashit( get_template_directory_uri() ) );
define( 'AQUALUXE_ASSETS_URI', AQUALUXE_THEME_URI . 'assets/dist/' );
define( 'AQUALUXE_INC_DIR', AQUALUXE_THEME_DIR . 'inc/' );
define( 'AQUALUXE_TEMPLATES_DIR', AQUALUXE_THEME_DIR . 'templates/' );

/**
 * Include core files
 */
require_once AQUALUXE_INC_DIR . 'core/constants.php';
require_once AQUALUXE_INC_DIR . 'setup/theme-setup.php';
require_once AQUALUXE_INC_DIR . 'setup/menus.php';
require_once AQUALUXE_INC_DIR . 'assets/enqueue.php';
require_once AQUALUXE_INC_DIR . 'security/hardening.php';
require_once AQUALUXE_INC_DIR . 'seo/schema.php';
require_once AQUALUXE_INC_DIR . 'utils/template-tags.php';
require_once AQUALUXE_INC_DIR . 'hooks/hooks.php';
require_once AQUALUXE_INC_DIR . 'customizer/register.php';

/**
 * Include WooCommerce integration if WooCommerce is active
 */
if ( class_exists( 'WooCommerce' ) ) {
	require_once AQUALUXE_INC_DIR . 'integrations/woocommerce.php';
}

/**
 * Set up theme defaults and register support for various WordPress features.
 */
function aqualuxe_setup() {
	// Load text domain for internationalization
	load_theme_textdomain( 'aqualuxe', AQUALUXE_THEME_DIR . 'languages' );

	// Add default posts and comments RSS feed links to head
	add_theme_support( 'automatic-feed-links' );

	// Let WordPress manage the document title
	add_theme_support( 'title-tag' );

	// Enable support for Post Thumbnails on posts and pages
	add_theme_support( 'post-thumbnails' );

	// Switch default core markup to output valid HTML5
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

	// Add support for core custom logo
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);

	// Add support for editor styles
	add_theme_support( 'editor-styles' );
	add_editor_style( 'assets/dist/css/editor-style.css' );

	// Add support for wide and full-width blocks
	add_theme_support( 'align-wide' );

	// Add support for responsive embeds
	add_theme_support( 'responsive-embeds' );

	// Add support for custom color palette
	add_theme_support(
		'editor-color-palette',
		array(
			array(
				'name'  => esc_html__( 'Primary', 'aqualuxe' ),
				'slug'  => 'primary',
				'color' => '#0891b2', // Cyan-600
			),
			array(
				'name'  => esc_html__( 'Secondary', 'aqualuxe' ),
				'slug'  => 'secondary',
				'color' => '#6366f1', // Indigo-500
			),
			array(
				'name'  => esc_html__( 'Accent', 'aqualuxe' ),
				'slug'  => 'accent',
				'color' => '#8b5cf6', // Violet-500
			),
			array(
				'name'  => esc_html__( 'Dark', 'aqualuxe' ),
				'slug'  => 'dark',
				'color' => '#0f172a', // Slate-900
			),
			array(
				'name'  => esc_html__( 'Light', 'aqualuxe' ),
				'slug'  => 'light',
				'color' => '#f8fafc', // Slate-50
			),
		)
	);

	// Add support for custom font sizes
	add_theme_support(
		'editor-font-sizes',
		array(
			array(
				'name' => esc_html__( 'Small', 'aqualuxe' ),
				'size' => 14,
				'slug' => 'small',
			),
			array(
				'name' => esc_html__( 'Normal', 'aqualuxe' ),
				'size' => 16,
				'slug' => 'normal',
			),
			array(
				'name' => esc_html__( 'Medium', 'aqualuxe' ),
				'size' => 20,
				'slug' => 'medium',
			),
			array(
				'name' => esc_html__( 'Large', 'aqualuxe' ),
				'size' => 24,
				'slug' => 'large',
			),
			array(
				'name' => esc_html__( 'Extra Large', 'aqualuxe' ),
				'size' => 32,
				'slug' => 'extra-large',
			),
		)
	);

	// Add support for custom line heights
	add_theme_support( 'custom-line-height' );

	// Add support for custom spacing
	add_theme_support( 'custom-spacing' );

	// Add support for custom units
	add_theme_support( 'custom-units' );

	// Add support for experimental link color control
	add_theme_support( 'experimental-link-color' );

	// Add support for experimental cover block spacing
	add_theme_support( 'custom-spacing' );

	// Add support for selective refresh for widgets
	add_theme_support( 'customize-selective-refresh-widgets' );

	// Register nav menus
	register_nav_menus(
		array(
			'primary'   => esc_html__( 'Primary Menu', 'aqualuxe' ),
			'secondary' => esc_html__( 'Secondary Menu', 'aqualuxe' ),
			'footer'    => esc_html__( 'Footer Menu', 'aqualuxe' ),
			'social'    => esc_html__( 'Social Links Menu', 'aqualuxe' ),
		)
	);

	// Register image sizes
	add_image_size( 'aqualuxe-featured', 1200, 675, true );
	add_image_size( 'aqualuxe-card', 600, 400, true );
	add_image_size( 'aqualuxe-thumbnail', 300, 300, true );
	add_image_size( 'aqualuxe-hero', 1920, 1080, true );
}
add_action( 'after_setup_theme', 'aqualuxe_setup' );

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
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer 2', 'aqualuxe' ),
			'id'            => 'footer-2',
			'description'   => esc_html__( 'Add widgets here to appear in footer column 2.', 'aqualuxe' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer 3', 'aqualuxe' ),
			'id'            => 'footer-3',
			'description'   => esc_html__( 'Add widgets here to appear in footer column 3.', 'aqualuxe' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer 4', 'aqualuxe' ),
			'id'            => 'footer-4',
			'description'   => esc_html__( 'Add widgets here to appear in footer column 4.', 'aqualuxe' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);

	// Register WooCommerce shop sidebar if WooCommerce is active
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