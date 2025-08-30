<?php
/**
 * Theme setup functions
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * AquaLuxe Theme Setup Class
 */
class AquaLuxe_Theme_Setup {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Set up theme defaults.
		add_action( 'after_setup_theme', array( $this, 'setup_theme_defaults' ) );
		
		// Register sidebars.
		add_action( 'widgets_init', array( $this, 'register_sidebars' ) );
		
		// Add body classes.
		add_filter( 'body_class', array( $this, 'body_classes' ) );
		
		// Add pingback header.
		add_action( 'wp_head', array( $this, 'pingback_header' ) );
		
		// Add custom image sizes.
		add_action( 'after_setup_theme', array( $this, 'add_image_sizes' ) );
		
		// Add excerpt support for pages.
		add_action( 'init', array( $this, 'add_excerpts_to_pages' ) );
		
		// Set content width.
		add_action( 'after_setup_theme', array( $this, 'content_width' ), 0 );
	}

	/**
	 * Setup theme defaults
	 */
	public function setup_theme_defaults() {
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
		add_editor_style( 'assets/dist/css/editor-style.css' );

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
					'color' => '#00afcc',
				),
				array(
					'name'  => esc_html__( 'Secondary', 'aqualuxe' ),
					'slug'  => 'secondary',
					'color' => '#7f7faf',
				),
				array(
					'name'  => esc_html__( 'Accent', 'aqualuxe' ),
					'slug'  => 'accent',
					'color' => '#eb7d23',
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

		// WooCommerce support.
		if ( class_exists( 'WooCommerce' ) ) {
			add_theme_support( 'woocommerce' );
			add_theme_support( 'wc-product-gallery-zoom' );
			add_theme_support( 'wc-product-gallery-lightbox' );
			add_theme_support( 'wc-product-gallery-slider' );
		}
	}

	/**
	 * Register sidebars
	 */
	public function register_sidebars() {
		// Main sidebar.
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

		// Footer widgets.
		$footer_columns = get_theme_mod( 'footer_columns', 4 );
		for ( $i = 1; $i <= $footer_columns; $i++ ) {
			register_sidebar(
				array(
					'name'          => sprintf( esc_html__( 'Footer %d', 'aqualuxe' ), $i ),
					'id'            => 'footer-' . $i,
					'description'   => sprintf( esc_html__( 'Add widgets here to appear in footer column %d.', 'aqualuxe' ), $i ),
					'before_widget' => '<section id="%1$s" class="widget %2$s">',
					'after_widget'  => '</section>',
					'before_title'  => '<h2 class="widget-title">',
					'after_title'   => '</h2>',
				)
			);
		}

		// WooCommerce sidebar.
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

	/**
	 * Add custom body classes
	 *
	 * @param array $classes Body classes.
	 * @return array
	 */
	public function body_classes( $classes ) {
		// Add a class if there is a custom header.
		if ( has_header_image() ) {
			$classes[] = 'has-header-image';
		}

		// Add a class if there is a custom background.
		if ( get_background_image() || get_background_color() !== 'ffffff' ) {
			$classes[] = 'has-custom-background';
		}

		// Add a class for the sidebar position.
		$sidebar_position = get_theme_mod( 'sidebar_position', 'right' );
		$classes[] = 'sidebar-' . $sidebar_position;

		// Add a class if WooCommerce is active.
		if ( class_exists( 'WooCommerce' ) ) {
			$classes[] = 'woocommerce-active';
		} else {
			$classes[] = 'woocommerce-inactive';
		}

		// Add a class for the header layout.
		$header_layout = get_theme_mod( 'header_layout', 'default' );
		$classes[] = 'header-layout-' . $header_layout;

		// Add a class for the footer layout.
		$footer_layout = get_theme_mod( 'footer_layout', 'default' );
		$classes[] = 'footer-layout-' . $footer_layout;

		return $classes;
	}

	/**
	 * Add pingback header
	 */
	public function pingback_header() {
		if ( is_singular() && pings_open() ) {
			printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
		}
	}

	/**
	 * Add custom image sizes
	 */
	public function add_image_sizes() {
		add_image_size( 'aqualuxe-featured', 1200, 675, true );
		add_image_size( 'aqualuxe-product', 600, 600, true );
		add_image_size( 'aqualuxe-thumbnail', 400, 300, true );
	}

	/**
	 * Add excerpt support for pages
	 */
	public function add_excerpts_to_pages() {
		add_post_type_support( 'page', 'excerpt' );
	}

	/**
	 * Set content width
	 */
	public function content_width() {
		$GLOBALS['content_width'] = apply_filters( 'aqualuxe_content_width', 1200 );
	}
}

// Initialize theme setup.
new AquaLuxe_Theme_Setup();