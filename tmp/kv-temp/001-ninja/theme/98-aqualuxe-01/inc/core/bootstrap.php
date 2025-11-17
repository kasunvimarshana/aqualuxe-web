<?php
namespace AquaLuxe\Core;

/**
 * Core Bootstrap — initializes theme supports, assets, modules, CPTs, etc.
 */
final class Bootstrap {
	public static function init(): void {
		self::i18n();
		self::supports();
		self::register_hooks();
		Assets::init();
		Customizer::init();
		Security::init();
		SEO::init();
		Accessibility::init();
		CPT::init();
		Modules::init();
		\AquaLuxe\Admin\Settings::init();
		Roles::init();
	}

	private static function i18n(): void {
		\load_theme_textdomain( 'aqualuxe', AQUALUXE_PATH . 'languages' );
	}

	private static function supports(): void {
		\add_theme_support( 'title-tag' );
		\add_theme_support( 'post-thumbnails' );
		\add_theme_support( 'html5', [ 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' ] );
		\add_theme_support( 'automatic-feed-links' );
		\add_theme_support( 'custom-logo', [ 'height' => 80, 'width' => 240, 'flex-width' => true, 'flex-height' => true ] );
		\add_theme_support( 'customize-selective-refresh-widgets' );
		\add_theme_support( 'align-wide' );
		\add_theme_support( 'woocommerce' );
		\add_theme_support( 'responsive-embeds' );
	}

	private static function register_hooks(): void {
		\register_nav_menus( [
			'primary'   => \__( 'Primary Menu', 'aqualuxe' ),
			'footer'    => \__( 'Footer Menu', 'aqualuxe' ),
		] );

		\add_action( 'widgets_init', [ __CLASS__, 'widgets' ] );
	}

	public static function widgets(): void {
		\register_sidebar( [
			'name'          => \__( 'Sidebar', 'aqualuxe' ),
			'id'            => 'sidebar-1',
			'description'   => \__( 'Add widgets here.', 'aqualuxe' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		] );
	}
}
