<?php
/**
 * AquaLuxe Functions
 *
 * @package AquaLuxe
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * AquaLuxe Theme Class
 */
class AquaLuxe_Theme {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Initialize theme
		$this->init();
	}

	/**
	 * Initialize theme
	 */
	public function init() {
		// Load required files
		$this->load_dependencies();
		
		// Add theme support
		add_action( 'after_setup_theme', array( $this, 'setup' ) );
		
		// Enqueue scripts and styles
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		
		// Add body classes
		add_filter( 'body_class', array( $this, 'body_classes' ) );
		
		// Add admin styles
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_styles' ) );
	}

	/**
	 * Load required files
	 */
	public function load_dependencies() {
		// Customizer
		require_once get_template_directory() . '/inc/customizer.php';
		
		// Demo content
		require_once get_template_directory() . '/inc/demo-content.php';
		
		// SEO
		require_once get_template_directory() . '/inc/seo.php';
		
		// Widgets
		require_once get_template_directory() . '/inc/widgets.php';
		
		// Accessibility
		require_once get_template_directory() . '/inc/accessibility.php';
		
		// Performance
		require_once get_template_directory() . '/inc/performance.php';
		
		// AJAX
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			require_once get_template_directory() . '/inc/ajax.php';
		}
	}

	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 */
	public function setup() {
		// Make theme available for translation
		load_theme_textdomain( 'aqualuxe', get_template_directory() . '/languages' );

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

		// Add theme support for selective refresh for widgets
		add_theme_support( 'customize-selective-refresh-widgets' );

		// Add support for WooCommerce
		add_theme_support( 'woocommerce' );
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );

		// Add support for responsive embedded content
		add_theme_support( 'responsive-embeds' );

		// Add support for custom line height
		add_theme_support( 'custom-line-height' );

		// Add support for experimental link color control
		add_theme_support( 'experimental-link-color' );

		// Add support for custom units
		add_theme_support( 'custom-units' );

		// Add support for editor styles
		add_theme_support( 'editor-styles' );

		// Register navigation menus
		register_nav_menus(
			array(
				'menu-1' => esc_html__( 'Primary', 'aqualuxe' ),
				'menu-2' => esc_html__( 'Secondary', 'aqualuxe' ),
				'menu-3' => esc_html__( 'Footer', 'aqualuxe' ),
			)
		);
	}

	/**
	 * Enqueue scripts and styles
	 */
	public function enqueue_scripts() {
		// Enqueue parent theme styles
		wp_enqueue_style( 'storefront-style', get_template_directory_uri() . '/style.css' );

		// Enqueue child theme styles
		wp_enqueue_style( 'aqualuxe-style', get_stylesheet_directory_uri() . '/style.css', array( 'storefront-style' ), wp_get_theme()->get( 'Version' ) );

		// Enqueue child theme scripts
		wp_enqueue_script( 'aqualuxe-scripts', get_stylesheet_directory_uri() . '/assets/js/scripts.js', array( 'jquery' ), wp_get_theme()->get( 'Version' ), true );

		// Enqueue WooCommerce scripts if WooCommerce is active
		if ( class_exists( 'WooCommerce' ) ) {
			wp_enqueue_script( 'aqualuxe-woocommerce', get_stylesheet_directory_uri() . '/assets/js/woocommerce.js', array( 'jquery' ), wp_get_theme()->get( 'Version' ), true );
		}

		// Localize script for AJAX
		wp_localize_script( 'aqualuxe-scripts', 'aqualuxe_ajax', array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'nonce'    => wp_create_nonce( 'aqualuxe_nonce' )
		) );
	}

	/**
	 * Add classes to body
	 */
	public function body_classes( $classes ) {
		// Adds a class of hfeed to non-singular pages
		if ( ! is_singular() ) {
			$classes[] = 'hfeed';
		}

		// Adds a class of no-sidebar when there is no sidebar present
		if ( ! is_active_sidebar( 'sidebar-1' ) ) {
			$classes[] = 'no-sidebar';
		}

		return $classes;
	}

	/**
	 * Add admin styles
	 */
	public function admin_styles() {
		wp_enqueue_style( 'aqualuxe-admin-style', get_stylesheet_directory_uri() . '/assets/css/admin.css', array(), wp_get_theme()->get( 'Version' ) );
	}
}

// Initialize the theme
new AquaLuxe_Theme();