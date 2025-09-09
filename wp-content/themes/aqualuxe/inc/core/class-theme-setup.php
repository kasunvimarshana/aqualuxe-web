<?php
/**
 * Theme setup.
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Handles theme setup.
 */
class AquaLuxe_Theme_Setup {

	/**
	 * Initialize theme setup.
	 */
	public static function init() {
		self::add_theme_supports();
		self::register_nav_menus();
		self::add_image_sizes();
		self::register_widget_areas();
	}

	/**
	 * Add theme supports.
	 */
	private static function add_theme_supports() {
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );
		add_theme_support( 'customize-selective-refresh-widgets' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'custom-logo', array(
			'height'      => 100,
			'width'       => 400,
			'flex-height' => true,
			'flex-width'  => true,
		) );
		add_theme_support( 'woocommerce' );
        // Add support for block styles.
        add_theme_support( 'wp-block-styles' );
        // Add support for full and wide align images.
        add_theme_support( 'align-wide' );
        // Add support for editor styles.
        add_theme_support( 'editor-styles' );
        // Enqueue editor styles.
        add_editor_style( 'assets/dist/css/app.css' );
	}

	/**
	 * Register navigation menus.
	 */
	private static function register_nav_menus() {
		register_nav_menus( array(
			'menu-1' => esc_html__( 'Primary', 'aqualuxe' ),
			'footer' => esc_html__( 'Footer Menu', 'aqualuxe' ),
		) );
	}

	/**
	 * Add custom image sizes.
	 */
	private static function add_image_sizes() {
		add_image_size( 'aqualuxe-featured-image', 1200, 600, true );
	}

	/**
	 * Register widget areas.
	 */
	public static function register_widget_areas() {
		register_sidebar( array(
			'name'          => esc_html__( 'Footer 1', 'aqualuxe' ),
			'id'            => 'footer-1',
			'description'   => esc_html__( 'Add widgets here.', 'aqualuxe' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		) );
		register_sidebar( array(
			'name'          => esc_html__( 'Footer 2', 'aqualuxe' ),
			'id'            => 'footer-2',
			'description'   => esc_html__( 'Add widgets here.', 'aqualuxe' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		) );
		register_sidebar( array(
			'name'          => esc_html__( 'Footer 3', 'aqualuxe' ),
			'id'            => 'footer-3',
			'description'   => esc_html__( 'Add widgets here.', 'aqualuxe' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		) );
	}
}

add_action( 'widgets_init', array( 'AquaLuxe_Theme_Setup', 'register_widget_areas' ) );
