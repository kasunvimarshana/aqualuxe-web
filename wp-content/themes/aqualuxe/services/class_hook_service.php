<?php
/**
 * AquaLuxe Hook Service
 *
 * @package AquaLuxe
 * @since   1.2.0
 */

namespace AquaLuxe\Services;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Hook_Service
 *
 * Manages WordPress hooks and filters centrally
 */
class Hook_Service {

	/**
	 * Registered hooks
	 *
	 * @var array
	 */
	protected $hooks = [];

	/**
	 * Register all theme hooks
	 *
	 * @return void
	 */
	public function register_hooks(): void {
		// Theme setup hooks
		\add_action( 'after_setup_theme', [ $this, 'setup_navigation_menus' ] );
		\add_action( 'widgets_init', [ $this, 'register_widget_areas' ] );
		
		// Content filters
		\add_filter( 'the_content', [ $this, 'wrap_content_with_container' ] );
		\add_filter( 'excerpt_length', [ $this, 'custom_excerpt_length' ] );
		\add_filter( 'excerpt_more', [ $this, 'custom_excerpt_more' ] );
		
		// Admin hooks
		\add_action( 'admin_enqueue_scripts', [ $this, 'admin_styles' ] );
		
		// Performance hooks
		\add_action( 'wp_head', [ $this, 'add_preconnect_links' ], 1 );
		\add_filter( 'style_loader_tag', [ $this, 'add_style_attributes' ], 10, 4 );
		
		// Security hooks
		\remove_action( 'wp_head', 'wp_generator' );
		\add_filter( 'the_generator', '__return_empty_string' );
	}

	/**
	 * Setup navigation menus
	 *
	 * @return void
	 */
	public function setup_navigation_menus(): void {
		\register_nav_menus( [
			'primary' => \__( 'Primary Navigation', 'aqualuxe' ),
			'secondary' => \__( 'Secondary Navigation', 'aqualuxe' ),
			'footer' => \__( 'Footer Navigation', 'aqualuxe' ),
			'mobile' => \__( 'Mobile Navigation', 'aqualuxe' ),
		] );
	}

	/**
	 * Register widget areas
	 *
	 * @return void
	 */
	public function register_widget_areas(): void {
		$widget_areas = [
			'sidebar-main' => [
				'name' => __( 'Main Sidebar', 'aqualuxe' ),
				'description' => __( 'Main sidebar widget area', 'aqualuxe' ),
			],
			'sidebar-shop' => [
				'name' => __( 'Shop Sidebar', 'aqualuxe' ),
				'description' => __( 'Shop and product pages sidebar', 'aqualuxe' ),
			],
			'footer-1' => [
				'name' => __( 'Footer Column 1', 'aqualuxe' ),
				'description' => __( 'First footer widget area', 'aqualuxe' ),
			],
			'footer-2' => [
				'name' => __( 'Footer Column 2', 'aqualuxe' ),
				'description' => __( 'Second footer widget area', 'aqualuxe' ),
			],
			'footer-3' => [
				'name' => __( 'Footer Column 3', 'aqualuxe' ),
				'description' => __( 'Third footer widget area', 'aqualuxe' ),
			],
			'footer-4' => [
				'name' => __( 'Footer Column 4', 'aqualuxe' ),
				'description' => __( 'Fourth footer widget area', 'aqualuxe' ),
			],
		];

		foreach ( $widget_areas as $id => $args ) {
			\register_sidebar( \array_merge( [
				'id' => $id,
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget' => '</div>',
				'before_title' => '<h3 class="widget-title">',
				'after_title' => '</h3>',
			], $args ) );
		}
	}

	/**
	 * Wrap content with container
	 *
	 * @param string $content Post content
	 * @return string Modified content
	 */
	public function wrap_content_with_container( string $content ): string {
		if ( \is_singular() && \is_main_query() ) {
			$content = '<div class="content-wrapper">' . $content . '</div>';
		}

		return $content;
	}

	/**
	 * Custom excerpt length
	 *
	 * @param int $length Current length
	 * @return int New length
	 */
	public function custom_excerpt_length( int $length ): int {
		return 25;
	}

	/**
	 * Custom excerpt more text
	 *
	 * @param string $more Current more text
	 * @return string New more text
	 */
	public function custom_excerpt_more( string $more ): string {
		return '...';
	}

	/**
	 * Add admin styles
	 *
	 * @return void
	 */
	public function admin_styles(): void {
		\wp_enqueue_style(
			'aqualuxe-admin',
			\get_template_directory_uri() . '/assets/dist/css/admin.css',
			[],
			'1.2.0'
		);
	}

	/**
	 * Add preconnect links for performance
	 *
	 * @return void
	 */
	public function add_preconnect_links(): void {
		echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
		echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
	}

	/**
	 * Add attributes to style tags
	 *
	 * @param string $tag Style tag
	 * @param string $handle Style handle
	 * @param string $href Style URL
	 * @param string $media Media type
	 * @return string Modified tag
	 */
	public function add_style_attributes( string $tag, string $handle, string $href, string $media ): string {
		// Add crossorigin for external fonts
		if ( \strpos( $href, 'fonts.googleapis.com' ) !== false ) {
			$tag = \str_replace( '>', ' crossorigin>', $tag );
		}

		return $tag;
	}
}
