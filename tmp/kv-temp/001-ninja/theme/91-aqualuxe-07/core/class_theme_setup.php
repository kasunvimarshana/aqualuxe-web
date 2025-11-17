<?php
/**
 * Theme setup.
 *
 * @package AquaLuxe
 * @since   1.1.0
 */

namespace AquaLuxe\Core;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Theme_Setup
 */
class Theme_Setup {

	/**
	 * Theme_Setup constructor.
	 */
	public function __construct() {
		\add_action( 'after_setup_theme', [ $this, 'setup' ] );
	}

	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 */
	public function setup(): void {
		// Make theme available for translation.
		\load_theme_textdomain( 'aqualuxe', AQUALUXE_THEME_DIR . 'languages' );

		// Add default posts and comments RSS feed links to head.
		\add_theme_support( 'automatic-feed-links' );

		// Let WordPress manage the document title.
		\add_theme_support( 'title-tag' );

		// Enable support for Post Thumbnails on posts and pages.
		\add_theme_support( 'post-thumbnails' );

		// Register navigation menus.
		\register_nav_menus(
			[
				'primary' => \esc_html__( 'Primary Menu', 'aqualuxe' ),
				'footer'  => \esc_html__( 'Footer Menu', 'aqualuxe' ),
			]
		);

		// Switch default core markup for search form, comment form, and comments to output valid HTML5.
		\add_theme_support(
			'html5',
			[
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			]
		);

		// Set up the WordPress core custom background feature.
		\add_theme_support(
			'custom-background',
			\apply_filters(
				'aqualuxe_custom_background_args',
				[
					'default-color' => 'ffffff',
					'default-image' => '',
				]
			)
		);

		// Add theme support for selective refresh for widgets.
		\add_theme_support( 'customize-selective-refresh-widgets' );

		// Add support for core custom logo.
		\add_theme_support(
			'custom-logo',
			[
				'height'      => 250,
				'width'       => 250,
				'flex-width'  => true,
				'flex-height' => true,
			]
		);

		// Add support for WooCommerce.
		\add_theme_support( 'woocommerce' );
		\add_theme_support( 'wc-product-gallery-zoom' );
		\add_theme_support( 'wc-product-gallery-lightbox' );
		\add_theme_support( 'wc-product-gallery-slider' );

		// Add support for post formats.
		\add_theme_support(
			'post-formats',
			[
				'aside',
				'image',
				'video',
				'quote',
				'link',
				'gallery',
				'status',
				'audio',
				'chat',
			]
		);
	}
}
