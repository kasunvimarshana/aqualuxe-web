<?php
/**
 * AquaLuxe Theme Service
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
 * Class Theme_Service
 *
 * Handles core theme functionality following Single Responsibility Principle
 */
class Theme_Service {

	/**
	 * Theme configuration
	 *
	 * @var array
	 */
	protected $config;

	/**
	 * Constructor
	 *
	 * @param array $config Theme configuration
	 */
	public function __construct( array $config = [] ) {
		$this->config = array_merge( $this->get_default_config(), $config );
	}

	/**
	 * Setup theme support features
	 *
	 * @return void
	 */
	public function setup_theme_support(): void {
		// Post thumbnails
		\add_theme_support( 'post-thumbnails' );

		// Custom image sizes
		$this->register_image_sizes();

		// HTML5 support
		\add_theme_support( 'html5', [
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		] );

		// Title tag support
		\add_theme_support( 'title-tag' );

		// Custom logo
		\add_theme_support( 'custom-logo', [
			'height'      => 100,
			'width'       => 400,
			'flex-height' => true,
			'flex-width'  => true,
		] );

		// Custom background
		\add_theme_support( 'custom-background', [
			'default-color' => 'ffffff',
		] );

		// Post formats
		\add_theme_support( 'post-formats', [
			'aside',
			'gallery',
			'quote',
			'image',
			'video',
		] );

		// Editor styles
		\add_theme_support( 'editor-styles' );
		\add_editor_style( 'assets/dist/css/editor-style.css' );

		// Wide alignment
		\add_theme_support( 'align-wide' );

		// Custom line height
		\add_theme_support( 'custom-line-height' );

		// Custom spacing
		\add_theme_support( 'custom-spacing' );

		// Custom units
		\add_theme_support( 'custom-units' );

		// WooCommerce support
		if ( \class_exists( 'WooCommerce' ) ) {
			$this->setup_woocommerce_support();
		}
	}

	/**
	 * Setup WooCommerce support
	 *
	 * @return void
	 */
	protected function setup_woocommerce_support(): void {
		\add_theme_support( 'woocommerce' );
		\add_theme_support( 'wc-product-gallery-zoom' );
		\add_theme_support( 'wc-product-gallery-lightbox' );
		\add_theme_support( 'wc-product-gallery-slider' );
	}

	/**
	 * Register custom image sizes
	 *
	 * @return void
	 */
	protected function register_image_sizes(): void {
		$image_sizes = $this->config['image_sizes'] ?? [];

		foreach ( $image_sizes as $name => $size ) {
			\add_image_size(
				$name,
				$size['width'] ?? 300,
				$size['height'] ?? 300,
				$size['crop'] ?? false
			);
		}
	}

	/**
	 * Enqueue frontend assets
	 *
	 * @return void
	 */
	public function enqueue_frontend_assets(): void {
		// Main theme styles
		\wp_enqueue_style(
			'aqualuxe-main',
			\get_template_directory_uri() . '/assets/dist/css/main.css',
			[],
			$this->get_asset_version()
		);

		// Main theme script
		\wp_enqueue_script(
			'aqualuxe-main',
			\get_template_directory_uri() . '/assets/dist/js/main.js',
			[ 'jquery' ],
			$this->get_asset_version(),
			true
		);

		// Localize script data
		\wp_localize_script( 'aqualuxe-main', 'aqualuxe', [
			'ajax_url' => \admin_url( 'admin-ajax.php' ),
			'nonce' => \wp_create_nonce( 'aqualuxe_nonce' ),
			'theme_url' => \get_template_directory_uri(),
			'is_rtl' => \is_rtl(),
			'locale' => \get_locale(),
		] );

		// Conditional assets
		if ( \is_singular() ) {
			\wp_enqueue_script( 'comment-reply' );
		}
	}

	/**
	 * Enqueue admin assets
	 *
	 * @return void
	 */
	public function enqueue_admin_assets(): void {
		// Admin styles
		\wp_enqueue_style(
			'aqualuxe-admin',
			\get_template_directory_uri() . '/assets/dist/css/admin.css',
			[],
			$this->get_asset_version()
		);

		// Admin scripts
		\wp_enqueue_script(
			'aqualuxe-admin',
			\get_template_directory_uri() . '/assets/dist/js/admin.js',
			[ 'jquery' ],
			$this->get_asset_version(),
			true
		);
	}

	/**
	 * Get asset version for cache busting
	 *
	 * @return string
	 */
	protected function get_asset_version(): string {
		if ( \defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			return \time();
		}

		return $this->config['version'] ?? '1.2.0';
	}

	/**
	 * Get default theme configuration
	 *
	 * @return array
	 */
	protected function get_default_config(): array {
		return [
			'version' => '1.2.0',
			'image_sizes' => [
				'aqualuxe-small' => [
					'width' => 300,
					'height' => 200,
					'crop' => true,
				],
				'aqualuxe-medium' => [
					'width' => 600,
					'height' => 400,
					'crop' => true,
				],
				'aqualuxe-large' => [
					'width' => 1200,
					'height' => 800,
					'crop' => true,
				],
				'aqualuxe-hero' => [
					'width' => 1920,
					'height' => 1080,
					'crop' => true,
				],
			],
		];
	}
}
