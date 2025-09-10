<?php
/**
 * Assets Manager Class
 *
 * Handles all asset management for the AquaLuxe theme including:
 * - CSS and JavaScript enqueuing with proper versioning
 * - Laravel Mix manifest integration
 * - Asset optimization and minification
 * - Critical CSS handling
 * - Lazy loading implementation
 *
 * @package AquaLuxe
 * @subpackage Core
 * @since 2.0.0
 * @author Kasun Vimarshana <kasunv.com@gmail.com>
 */

namespace AquaLuxe\Core;

use AquaLuxe\Core\Interfaces\Singleton_Interface;
use AquaLuxe\Core\Traits\Singleton_Trait;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Assets Manager Class
 *
 * Manages all theme assets with advanced features like versioning,
 * optimization, and performance enhancements.
 *
 * @since 2.0.0
 * @implements Singleton_Interface
 */
class Assets implements Singleton_Interface {

	use Singleton_Trait;

	/**
	 * Laravel Mix manifest data.
	 *
	 * @since 2.0.0
	 * @var array|null
	 */
	private ?array $mix_manifest = null;

	/**
	 * Theme bootstrap instance.
	 *
	 * @since 2.0.0
	 * @var Bootstrap
	 */
	private Bootstrap $bootstrap;

	/**
	 * Registered stylesheets.
	 *
	 * @since 2.0.0
	 * @var array<string, array>
	 */
	private array $stylesheets = [];

	/**
	 * Registered scripts.
	 *
	 * @since 2.0.0
	 * @var array<string, array>
	 */
	private array $scripts = [];

	/**
	 * Critical CSS content.
	 *
	 * @since 2.0.0
	 * @var string|null
	 */
	private ?string $critical_css = null;

	/**
	 * Constructor.
	 *
	 * @since 2.0.0
	 * @param Bootstrap $bootstrap The bootstrap instance.
	 */
	public function __construct( Bootstrap $bootstrap ) {
		$this->bootstrap = $bootstrap;
		$this->load_mix_manifest();
		$this->init_hooks();
	}

	/**
	 * Initialize WordPress hooks.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	private function init_hooks(): void {
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_frontend_assets' ], 10 );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_assets' ], 10 );
		add_action( 'enqueue_block_editor_assets', [ $this, 'enqueue_editor_assets' ], 10 );
		add_action( 'wp_head', [ $this, 'output_critical_css' ], 1 );
		add_action( 'wp_head', [ $this, 'add_preload_hints' ], 2 );
		
		// Asset optimization filters
		add_filter( 'style_loader_src', [ $this, 'remove_version_from_assets' ], 15, 1 );
		add_filter( 'script_loader_src', [ $this, 'remove_version_from_assets' ], 15, 1 );
		
		// Performance enhancements
		if ( $this->bootstrap->get_config( 'assets.lazy_load_images' ) ) {
			add_filter( 'wp_get_attachment_image_attributes', [ $this, 'add_lazy_loading' ], 10, 3 );
			add_filter( 'the_content', [ $this, 'add_lazy_loading_to_content' ], 10 );
		}
	}

	/**
	 * Load Laravel Mix manifest file.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	private function load_mix_manifest(): void {
		$manifest_path = AQUALUXE_DIST_DIR . 'mix-manifest.json';
		
		if ( file_exists( $manifest_path ) ) {
			$manifest_content = file_get_contents( $manifest_path );
			$this->mix_manifest = json_decode( $manifest_content, true );
		}
	}

	/**
	 * Get asset URL with proper versioning.
	 *
	 * @since 2.0.0
	 * @param string $asset_path The asset path relative to the dist directory.
	 * @return string The full asset URL with version.
	 */
	public function get_asset_url( string $asset_path ): string {
		// Ensure asset path starts with /
		if ( ! str_starts_with( $asset_path, '/' ) ) {
			$asset_path = '/' . $asset_path;
		}

		// Check if we have a mix manifest
		if ( $this->mix_manifest && isset( $this->mix_manifest[ $asset_path ] ) ) {
			$versioned_path = $this->mix_manifest[ $asset_path ];
			return AQUALUXE_DIST_URI . ltrim( $versioned_path, '/' );
		}

		// Fallback to file modification time versioning
		$full_path = AQUALUXE_DIST_DIR . ltrim( $asset_path, '/' );
		$version = file_exists( $full_path ) ? filemtime( $full_path ) : AQUALUXE_VERSION;
		
		return add_query_arg( 'ver', $version, AQUALUXE_DIST_URI . ltrim( $asset_path, '/' ) );
	}

	/**
	 * Register a stylesheet.
	 *
	 * @since 2.0.0
	 * @param string $handle The stylesheet handle.
	 * @param string $src The stylesheet source path.
	 * @param array  $deps Array of dependencies.
	 * @param string $media The media type.
	 * @param bool   $critical Whether this is critical CSS.
	 * @return void
	 */
	public function register_stylesheet( string $handle, string $src, array $deps = [], string $media = 'all', bool $critical = false ): void {
		$this->stylesheets[ $handle ] = [
			'src'      => $src,
			'deps'     => $deps,
			'media'    => $media,
			'critical' => $critical,
		];
	}

	/**
	 * Register a script.
	 *
	 * @since 2.0.0
	 * @param string $handle The script handle.
	 * @param string $src The script source path.
	 * @param array  $deps Array of dependencies.
	 * @param bool   $in_footer Whether to enqueue in footer.
	 * @param array  $localize_data Data to localize to the script.
	 * @return void
	 */
	public function register_script( string $handle, string $src, array $deps = [], bool $in_footer = true, array $localize_data = [] ): void {
		$this->scripts[ $handle ] = [
			'src'           => $src,
			'deps'          => $deps,
			'in_footer'     => $in_footer,
			'localize_data' => $localize_data,
		];
	}

	/**
	 * Enqueue frontend assets.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function enqueue_frontend_assets(): void {
		// Register core stylesheets
		$this->register_core_stylesheets();
		
		// Register core scripts
		$this->register_core_scripts();
		
		// Enqueue registered assets
		$this->enqueue_registered_assets();
		
		// Add inline styles for customizer settings
		$this->add_customizer_styles();
		
		// Add theme specific localization
		$this->localize_theme_data();
	}

	/**
	 * Register core theme stylesheets.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	private function register_core_stylesheets(): void {
		// Main theme stylesheet
		$this->register_stylesheet(
			'aqualuxe-main',
			'/css/app.css',
			[],
			'all',
			true
		);

		// WooCommerce styles (if enabled)
		if ( $this->bootstrap->is_module_enabled( 'woocommerce' ) && class_exists( 'WooCommerce' ) ) {
			$this->register_stylesheet(
				'aqualuxe-woocommerce',
				'/css/woocommerce.css',
				[ 'aqualuxe-main' ]
			);
		}

		// Print styles
		$this->register_stylesheet(
			'aqualuxe-print',
			'/css/print.css',
			[ 'aqualuxe-main' ],
			'print'
		);

		// Dark mode styles (if enabled)
		if ( $this->bootstrap->is_module_enabled( 'dark_mode' ) ) {
			$this->register_stylesheet(
				'aqualuxe-dark-mode',
				'/css/dark-mode.css',
				[ 'aqualuxe-main' ]
			);
		}
	}

	/**
	 * Register core theme scripts.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	private function register_core_scripts(): void {
		// Main theme script
		$this->register_script(
			'aqualuxe-main',
			'/js/app.js',
			[],
			true,
			[
				'ajaxUrl'    => admin_url( 'admin-ajax.php' ),
				'nonce'      => wp_create_nonce( 'aqualuxe_nonce' ),
				'textDomain' => AQUALUXE_TEXT_DOMAIN,
			]
		);

		// Navigation script
		$this->register_script(
			'aqualuxe-navigation',
			'/js/navigation.js',
			[ 'aqualuxe-main' ],
			true
		);

		// Dark mode script (if enabled)
		if ( $this->bootstrap->is_module_enabled( 'dark_mode' ) ) {
			$this->register_script(
				'aqualuxe-dark-mode',
				'/js/dark-mode.js',
				[ 'aqualuxe-main' ],
				true
			);
		}

		// Comments script (if needed)
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}

	/**
	 * Enqueue registered assets.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	private function enqueue_registered_assets(): void {
		// Enqueue stylesheets
		foreach ( $this->stylesheets as $handle => $stylesheet ) {
			wp_enqueue_style(
				$handle,
				$this->get_asset_url( $stylesheet['src'] ),
				$stylesheet['deps'],
				null,
				$stylesheet['media']
			);
		}

		// Enqueue scripts
		foreach ( $this->scripts as $handle => $script ) {
			wp_enqueue_script(
				$handle,
				$this->get_asset_url( $script['src'] ),
				$script['deps'],
				null,
				$script['in_footer']
			);

			// Add localized data if provided
			if ( ! empty( $script['localize_data'] ) ) {
				wp_localize_script( $handle, 'aqualuxeData', $script['localize_data'] );
			}
		}
	}

	/**
	 * Enqueue admin assets.
	 *
	 * @since 2.0.0
	 * @param string $hook_suffix The current admin page hook suffix.
	 * @return void
	 */
	public function enqueue_admin_assets( string $hook_suffix ): void {
		// Admin-specific styles
		wp_enqueue_style(
			'aqualuxe-admin',
			$this->get_asset_url( '/css/admin.css' ),
			[],
			null
		);

		// Admin-specific scripts
		wp_enqueue_script(
			'aqualuxe-admin',
			$this->get_asset_url( '/js/admin.js' ),
			[ 'jquery' ],
			null,
			true
		);

		// Customizer-specific assets
		if ( 'customize.php' === $hook_suffix ) {
			wp_enqueue_script(
				'aqualuxe-customizer',
				$this->get_asset_url( '/js/customizer.js' ),
				[ 'jquery', 'customize-controls' ],
				null,
				true
			);
		}
	}

	/**
	 * Enqueue block editor assets.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function enqueue_editor_assets(): void {
		// Editor styles
		wp_enqueue_style(
			'aqualuxe-editor',
			$this->get_asset_url( '/css/editor.css' ),
			[],
			null
		);

		// Editor scripts
		wp_enqueue_script(
			'aqualuxe-editor',
			$this->get_asset_url( '/js/editor.js' ),
			[ 'wp-blocks', 'wp-dom-ready', 'wp-edit-post' ],
			null,
			true
		);
	}

	/**
	 * Output critical CSS inline.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function output_critical_css(): void {
		if ( ! $this->bootstrap->get_config( 'assets.enable_critical_css' ) ) {
			return;
		}

		$critical_css = $this->get_critical_css();
		if ( $critical_css ) {
			echo '<style id="aqualuxe-critical-css">' . wp_strip_all_tags( $critical_css ) . '</style>';
		}
	}

	/**
	 * Get critical CSS content.
	 *
	 * @since 2.0.0
	 * @return string|null The critical CSS content.
	 */
	private function get_critical_css(): ?string {
		if ( null !== $this->critical_css ) {
			return $this->critical_css;
		}

		$critical_css_file = AQUALUXE_DIST_DIR . 'css/critical.css';
		if ( file_exists( $critical_css_file ) ) {
			$this->critical_css = file_get_contents( $critical_css_file );
		}

		return $this->critical_css;
	}

	/**
	 * Add preload hints for critical resources.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function add_preload_hints(): void {
		if ( ! $this->bootstrap->get_config( 'performance.preload_critical_resources' ) ) {
			return;
		}

		// Preload critical CSS
		echo '<link rel="preload" href="' . esc_url( $this->get_asset_url( '/css/app.css' ) ) . '" as="style">';
		
		// Preload critical JavaScript
		echo '<link rel="preload" href="' . esc_url( $this->get_asset_url( '/js/app.js' ) ) . '" as="script">';
		
		// Preload web fonts
		$this->preload_web_fonts();
	}

	/**
	 * Preload web fonts.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	private function preload_web_fonts(): void {
		$fonts_dir = AQUALUXE_DIST_DIR . 'fonts/';
		if ( is_dir( $fonts_dir ) ) {
			$font_files = glob( $fonts_dir . '*.woff2' );
			foreach ( array_slice( $font_files, 0, 3 ) as $font_file ) { // Limit to 3 fonts
				$font_url = str_replace( AQUALUXE_DIST_DIR, AQUALUXE_DIST_URI, $font_file );
				echo '<link rel="preload" href="' . esc_url( $font_url ) . '" as="font" type="font/woff2" crossorigin>';
			}
		}
	}

	/**
	 * Remove version parameters from asset URLs for caching.
	 *
	 * @since 2.0.0
	 * @param string $src The asset source URL.
	 * @return string The modified asset URL.
	 */
	public function remove_version_from_assets( string $src ): string {
		if ( strpos( $src, AQUALUXE_DIST_URI ) !== false ) {
			return remove_query_arg( 'ver', $src );
		}
		return $src;
	}

	/**
	 * Add lazy loading attributes to images.
	 *
	 * @since 2.0.0
	 * @param array $attr Image attributes.
	 * @param object $attachment The attachment object.
	 * @param string $size The image size.
	 * @return array Modified image attributes.
	 */
	public function add_lazy_loading( array $attr, $attachment, string $size ): array {
		// Don't lazy load images in admin or if it's the main content image
		if ( is_admin() || is_feed() ) {
			return $attr;
		}

		$attr['loading'] = 'lazy';
		$attr['decoding'] = 'async';

		return $attr;
	}

	/**
	 * Add lazy loading to images in content.
	 *
	 * @since 2.0.0
	 * @param string $content The post content.
	 * @return string Modified content with lazy loading.
	 */
	public function add_lazy_loading_to_content( string $content ): string {
		if ( is_admin() || is_feed() ) {
			return $content;
		}

		// Add loading="lazy" to images that don't have it
		$content = preg_replace(
			'/<img(?![^>]*loading=)([^>]+)>/i',
			'<img loading="lazy" decoding="async" $1>',
			$content
		);

		return $content;
	}

	/**
	 * Add customizer styles.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	private function add_customizer_styles(): void {
		$customizer = $this->bootstrap->get_core_component( 'customizer' );
		if ( $customizer && method_exists( $customizer, 'get_css' ) ) {
			$custom_css = $customizer->get_css();
			if ( $custom_css ) {
				wp_add_inline_style( 'aqualuxe-main', $custom_css );
			}
		}
	}

	/**
	 * Localize theme data for JavaScript.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	private function localize_theme_data(): void {
		$theme_data = [
			'version'     => AQUALUXE_VERSION,
			'textDomain'  => AQUALUXE_TEXT_DOMAIN,
			'ajaxUrl'     => admin_url( 'admin-ajax.php' ),
			'nonce'       => wp_create_nonce( 'aqualuxe_nonce' ),
			'isRTL'       => is_rtl(),
			'breakpoints' => [
				'mobile'  => 640,
				'tablet'  => 768,
				'desktop' => 1024,
				'wide'    => 1280,
			],
			'modules'     => array_keys( array_filter( $this->bootstrap->get_config( 'modules' ) ) ),
		];

		wp_localize_script( 'aqualuxe-main', 'aqualuxeTheme', $theme_data );
	}

	/**
	 * Get all registered stylesheets.
	 *
	 * @since 2.0.0
	 * @return array<string, array> The registered stylesheets.
	 */
	public function get_stylesheets(): array {
		return $this->stylesheets;
	}

	/**
	 * Get all registered scripts.
	 *
	 * @since 2.0.0
	 * @return array<string, array> The registered scripts.
	 */
	public function get_scripts(): array {
		return $this->scripts;
	}
}
