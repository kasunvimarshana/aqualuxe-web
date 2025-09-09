<?php
/**
 * Theme Service Provider
 *
 * @package AquaLuxe
 */

namespace App\Providers;

use App\Core\ServiceProvider;

class ThemeServiceProvider extends ServiceProvider {
	public function register() {
		// Register theme support, nav menus, etc.
		add_action( 'after_setup_theme', [ $this, 'theme_support' ] );
	}

	public function theme_support() {
		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		// Let WordPress manage the document title.
		add_theme_support( 'title-tag' );

		// Enable support for Post Thumbnails on posts and pages.
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in two locations.
		register_nav_menus(
			[
				'primary' => esc_html__( 'Primary Menu', 'aqualuxe' ),
				'footer'  => esc_html__( 'Footer Menu', 'aqualuxe' ),
			]
		);

		// Switch default core markup for search form, comment form, and comments to output valid HTML5.
		add_theme_support(
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
		add_theme_support(
			'custom-background',
			apply_filters(
				'aqualuxe_custom_background_args',
				[
					'default-color' => 'ffffff',
					'default-image' => '',
				]
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		// Add support for core custom logo.
		add_theme_support(
			'custom-logo',
			[
				'height'      => 250,
				'width'       => 250,
				'flex-width'  => true,
				'flex-height' => true,
			]
		);
	}
}
