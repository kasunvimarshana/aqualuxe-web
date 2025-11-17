<?php
/**
 * Theme setup functions
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'aqualuxe_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function aqualuxe_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 */
		load_theme_textdomain( 'aqualuxe', AQUALUXE_DIR . 'languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// Set default thumbnail size.
		set_post_thumbnail_size( 1200, 9999 );

		// Add custom image sizes.
		add_image_size( 'aqualuxe-featured', 1600, 900, true );
		add_image_size( 'aqualuxe-card', 600, 400, true );
		add_image_size( 'aqualuxe-thumbnail', 300, 300, true );

		// Register navigation menus.
		register_nav_menus(
			array(
				'primary'   => esc_html__( 'Primary Menu', 'aqualuxe' ),
				'secondary' => esc_html__( 'Secondary Menu', 'aqualuxe' ),
				'footer'    => esc_html__( 'Footer Menu', 'aqualuxe' ),
				'social'    => esc_html__( 'Social Menu', 'aqualuxe' ),
			)
		);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
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
				'navigation-widgets',
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		// Add support for editor styles.
		add_theme_support( 'editor-styles' );

		// Add support for responsive embeds.
		add_theme_support( 'responsive-embeds' );

		// Add support for full and wide align images.
		add_theme_support( 'align-wide' );

		// Add support for custom logo.
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 100,
				'width'       => 350,
				'flex-width'  => true,
				'flex-height' => true,
			)
		);

		// Add support for custom background.
		add_theme_support(
			'custom-background',
			array(
				'default-color' => 'ffffff',
			)
		);

		// Add support for custom header.
		add_theme_support(
			'custom-header',
			array(
				'default-image'      => '',
				'default-text-color' => '000000',
				'width'              => 1600,
				'height'             => 500,
				'flex-width'         => true,
				'flex-height'        => true,
			)
		);

		// Add support for custom colors in the editor.
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
					'color' => '#7e22ce', // Purple-700
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
				array(
					'name'  => esc_html__( 'Accent', 'aqualuxe' ),
					'slug'  => 'accent',
					'color' => '#f59e0b', // Amber-500
				),
				array(
					'name'  => esc_html__( 'White', 'aqualuxe' ),
					'slug'  => 'white',
					'color' => '#ffffff',
				),
			)
		);

		// Add WooCommerce support if active.
		if ( class_exists( 'WooCommerce' ) ) {
			add_theme_support( 'woocommerce' );
			add_theme_support( 'wc-product-gallery-zoom' );
			add_theme_support( 'wc-product-gallery-lightbox' );
			add_theme_support( 'wc-product-gallery-slider' );
		}
	}
endif;
add_action( 'after_setup_theme', 'aqualuxe_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
if (! function_exists('aqualuxe_content_width')) :
    function aqualuxe_content_width()
    {
        $GLOBALS['content_width'] = apply_filters('aqualuxe_content_width', 1200);
    }
endif;
add_action( 'after_setup_theme', 'aqualuxe_content_width', 0 );