<?php
/**
 * Theme Setup Functions
 *
 * Basic WordPress theme setup and configuration
 *
 * @package AquaLuxe
 * @since 2.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * AquaLuxe Theme Setup
 * Configure theme supports and basic functionality
 */
function aqualuxe_theme_setup() {
	// Make theme available for translation
	load_theme_textdomain( AQUALUXE_TEXTDOMAIN, get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head
	add_theme_support( 'automatic-feed-links' );

	// Let WordPress manage the document title
	add_theme_support( 'title-tag' );

	// Enable support for Post Thumbnails on posts and pages
	add_theme_support( 'post-thumbnails' );

	// Custom image sizes for AquaLuxe
	add_image_size( 'aqualuxe-hero', 1920, 1080, true );           // Hero images
	add_image_size( 'aqualuxe-featured', 800, 600, true );         // Featured images
	add_image_size( 'aqualuxe-card', 400, 300, true );            // Card images
	add_image_size( 'aqualuxe-thumbnail', 150, 150, true );       // Small thumbnails
	add_image_size( 'aqualuxe-product-large', 600, 600, false );  // WooCommerce product images
	add_image_size( 'aqualuxe-product-thumb', 200, 200, true );   // WooCommerce thumbnails

	// Navigation menus
	register_nav_menus( array(
		'primary'   => esc_html__( 'Primary Navigation', 'aqualuxe' ),
		'secondary' => esc_html__( 'Secondary Navigation', 'aqualuxe' ),
		'footer'    => esc_html__( 'Footer Navigation', 'aqualuxe' ),
		'mobile'    => esc_html__( 'Mobile Navigation', 'aqualuxe' ),
		'utility'   => esc_html__( 'Utility Navigation', 'aqualuxe' ),
	) );

	// HTML5 theme support
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
		'style',
		'script',
		'navigation-widgets',
	) );

	// Set up the WordPress core custom background feature
	add_theme_support( 'custom-background', apply_filters( 'aqualuxe_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );

	// Add theme support for selective refresh for widgets
	add_theme_support( 'customize-selective-refresh-widgets' );

	// Custom logo support
	add_theme_support( 'custom-logo', array(
		'height'               => 80,
		'width'                => 200,
		'flex-width'           => true,
		'flex-height'          => true,
		'unlink-homepage-logo' => false,
	) );

	// Wide and full width block editor support
	add_theme_support( 'align-wide' );

	// Block editor color palette
	add_theme_support( 'editor-color-palette', array(
		array(
			'name'  => esc_html__( 'Primary Blue', 'aqualuxe' ),
			'slug'  => 'primary-blue',
			'color' => '#3b82f6',
		),
		array(
			'name'  => esc_html__( 'Secondary Teal', 'aqualuxe' ),
			'slug'  => 'secondary-teal',
			'color' => '#14b8a6',
		),
		array(
			'name'  => esc_html__( 'Accent Gold', 'aqualuxe' ),
			'slug'  => 'accent-gold',
			'color' => '#f59e0b',
		),
		array(
			'name'  => esc_html__( 'Dark Gray', 'aqualuxe' ),
			'slug'  => 'dark-gray',
			'color' => '#374151',
		),
		array(
			'name'  => esc_html__( 'Light Gray', 'aqualuxe' ),
			'slug'  => 'light-gray',
			'color' => '#f3f4f6',
		),
	) );

	// Block editor font sizes
	add_theme_support( 'editor-font-sizes', array(
		array(
			'name' => esc_html__( 'Small', 'aqualuxe' ),
			'size' => 14,
			'slug' => 'small'
		),
		array(
			'name' => esc_html__( 'Normal', 'aqualuxe' ),
			'size' => 16,
			'slug' => 'normal'
		),
		array(
			'name' => esc_html__( 'Medium', 'aqualuxe' ),
			'size' => 20,
			'slug' => 'medium'
		),
		array(
			'name' => esc_html__( 'Large', 'aqualuxe' ),
			'size' => 36,
			'slug' => 'large'
		),
		array(
			'name' => esc_html__( 'Extra Large', 'aqualuxe' ),
			'size' => 48,
			'slug' => 'extra-large'
		),
	) );

	// Responsive embed support
	add_theme_support( 'responsive-embeds' );

	// Block styles support
	add_theme_support( 'wp-block-styles' );

	// Experimental theme.json support
	if ( function_exists( 'wp_theme_has_theme_json' ) ) {
		add_theme_support( 'appearance-tools' );
	}

	// WooCommerce support (conditional)
	if ( class_exists( 'WooCommerce' ) ) {
		add_theme_support( 'woocommerce' );
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );
	}

	// Content width for embedded content
	if ( ! isset( $content_width ) ) {
		$content_width = 1140;
	}
}
add_action( 'after_setup_theme', 'aqualuxe_theme_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet
 */
function aqualuxe_content_width() {
	global $content_width;
	
	// Default content width
	$content_width = 1140;
	
	// Wider content for full-width pages
	if ( is_page_template( 'templates/pages/full-width.php' ) ) {
		$content_width = 1920;
	}
	
	// Narrower content for sidebar layouts
	if ( is_active_sidebar( 'sidebar-1' ) && ! is_page_template( 'templates/pages/full-width.php' ) ) {
		$content_width = 720;
	}
}
add_action( 'template_redirect', 'aqualuxe_content_width', 0 );

/**
 * Add editor styles
 */
function aqualuxe_add_editor_styles() {
	// Add editor styles for Gutenberg
	add_theme_support( 'editor-styles' );
	add_editor_style( aqualuxe_get_asset_url( 'css/editor.css' ) );
}
add_action( 'after_setup_theme', 'aqualuxe_add_editor_styles' );

/**
 * Enqueue block editor assets
 */
function aqualuxe_block_editor_assets() {
	wp_enqueue_script(
		'aqualuxe-block-editor',
		aqualuxe_get_asset_url( 'js/block-editor.js' ),
		array( 'wp-blocks', 'wp-dom-ready', 'wp-edit-post' ),
		AQUALUXE_VERSION,
		true
	);
}
add_action( 'enqueue_block_editor_assets', 'aqualuxe_block_editor_assets' );

/**
 * Custom excerpt length
 */
function aqualuxe_excerpt_length( $length ) {
	return apply_filters( 'aqualuxe_excerpt_length', 25 );
}
add_filter( 'excerpt_length', 'aqualuxe_excerpt_length' );

/**
 * Custom excerpt more
 */
function aqualuxe_excerpt_more( $more ) {
	if ( is_admin() ) {
		return $more;
	}
	
	return apply_filters( 'aqualuxe_excerpt_more', '&hellip;' );
}
add_filter( 'excerpt_more', 'aqualuxe_excerpt_more' );

/**
 * Add custom body classes
 */
function aqualuxe_body_classes( $classes ) {
	// Add class for logged-in users
	if ( is_user_logged_in() ) {
		$classes[] = 'logged-in';
	}
	
	// Add class for WooCommerce pages
	if ( class_exists( 'WooCommerce' ) && is_woocommerce() ) {
		$classes[] = 'woocommerce-active';
	}
	
	// Add class for mobile detection (if available)
	if ( wp_is_mobile() ) {
		$classes[] = 'mobile-device';
	}
	
	// Add class for dark mode (will be managed by JS)
	$classes[] = 'theme-transition';
	
	// Add page template class
	if ( is_page_template() ) {
		$template = get_page_template_slug();
		$template_slug = str_replace( array( 'templates/', '.php' ), '', $template );
		$template_slug = str_replace( '/', '-', $template_slug );
		$classes[] = 'page-template-' . $template_slug;
	}
	
	return apply_filters( 'aqualuxe_body_classes', $classes );
}
add_filter( 'body_class', 'aqualuxe_body_classes' );

/**
 * Pingback header
 */
function aqualuxe_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'aqualuxe_pingback_header' );

/**
 * Remove WordPress version from head
 */
remove_action( 'wp_head', 'wp_generator' );

/**
 * Clean up WordPress head
 */
function aqualuxe_cleanup_head() {
	// Remove really simple discovery link
	remove_action( 'wp_head', 'rsd_link' );
	
	// Remove windows live writer manifest link
	remove_action( 'wp_head', 'wlwmanifest_link' );
	
	// Remove shortlink
	remove_action( 'wp_head', 'wp_shortlink_wp_head' );
	
	// Remove adjacent posts links
	remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10 );
}
add_action( 'init', 'aqualuxe_cleanup_head' );