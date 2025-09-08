<?php
/**
 * AquaLuxe Asset Service
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
 * Class Asset_Service
 *
 * Manages asset compilation, enqueuing, and optimization
 */
class Asset_Service {

	/**
	 * Asset configuration
	 *
	 * @var array
	 */
	protected $config;

	/**
	 * Registered assets
	 *
	 * @var array
	 */
	protected $assets = [
		'styles' => [],
		'scripts' => [],
	];

	/**
	 * Constructor
	 *
	 * @param array $config Asset configuration
	 */
	public function __construct( array $config = [] ) {
		$this->config = array_merge( $this->get_default_config(), $config );
		$this->register_default_assets();
	}

	/**
	 * Register a stylesheet
	 *
	 * @param string $handle Asset handle
	 * @param string $src Asset source URL
	 * @param array $deps Dependencies
	 * @param string|null $version Asset version
	 * @param string $media Media type
	 * @return void
	 */
	public function register_style( string $handle, string $src, array $deps = [], string $version = null, string $media = 'all' ): void {
		$this->assets['styles'][ $handle ] = [
			'src' => $src,
			'deps' => $deps,
			'version' => $version ?? $this->get_asset_version(),
			'media' => $media,
		];
	}

	/**
	 * Register a script
	 *
	 * @param string $handle Asset handle
	 * @param string $src Asset source URL
	 * @param array $deps Dependencies
	 * @param string|null $version Asset version
	 * @param bool $in_footer Load in footer
	 * @return void
	 */
	public function register_script( string $handle, string $src, array $deps = [], string $version = null, bool $in_footer = true ): void {
		$this->assets['scripts'][ $handle ] = [
			'src' => $src,
			'deps' => $deps,
			'version' => $version ?? $this->get_asset_version(),
			'in_footer' => $in_footer,
		];
	}

	/**
	 * Enqueue frontend assets
	 *
	 * @return void
	 */
	public function enqueue_frontend_assets(): void {
		// Core styles
		$this->enqueue_style( 'aqualuxe-main' );
		$this->enqueue_style( 'aqualuxe-components' );

		// Core scripts
		$this->enqueue_script( 'aqualuxe-main' );

		// Conditional assets
		if ( \is_front_page() ) {
			$this->enqueue_style( 'aqualuxe-home' );
			$this->enqueue_script( 'aqualuxe-home' );
		}

		// Check if WooCommerce functions exist before using them
		if ( \function_exists( 'is_shop' ) && ( \is_shop() || \is_product() || \is_product_category() ) ) {
			$this->enqueue_style( 'aqualuxe-woocommerce' );
			// Note: WooCommerce JS not included in current build
			// $this->enqueue_script( 'aqualuxe-woocommerce' );
		}

		// Responsive images polyfill for older browsers
		// Note: Picturefill not included in current build
		// if ( ! \wp_script_is( 'picturefill', 'enqueued' ) ) {
		//     $this->enqueue_script( 'aqualuxe-picturefill' );
		// }
	}

	/**
	 * Enqueue admin assets
	 *
	 * @return void
	 */
	public function enqueue_admin_assets(): void {
		$this->enqueue_style( 'aqualuxe-admin' );
		$this->enqueue_script( 'aqualuxe-admin' );

		// Customizer assets
		if ( \is_customize_preview() ) {
			$this->enqueue_style( 'aqualuxe-customizer' );
			$this->enqueue_script( 'aqualuxe-customizer' );
		}
	}

	/**
	 * Enqueue login page assets
	 *
	 * @return void
	 */
	public function enqueue_login_assets(): void {
		$this->enqueue_style( 'aqualuxe-login' );
	}

	/**
	 * Enqueue a registered style
	 *
	 * @param string $handle Asset handle
	 * @return void
	 */
	public function enqueue_style( string $handle ): void {
		if ( ! isset( $this->assets['styles'][ $handle ] ) ) {
			return;
		}

		$asset = $this->assets['styles'][ $handle ];

		\wp_enqueue_style(
			$handle,
			$asset['src'],
			$asset['deps'],
			$asset['version'],
			$asset['media']
		);
	}

	/**
	 * Enqueue a registered script
	 *
	 * @param string $handle Asset handle
	 * @return void
	 */
	public function enqueue_script( string $handle ): void {
		if ( ! isset( $this->assets['scripts'][ $handle ] ) ) {
			return;
		}

		$asset = $this->assets['scripts'][ $handle ];

		\wp_enqueue_script(
			$handle,
			$asset['src'],
			$asset['deps'],
			$asset['version'],
			$asset['in_footer']
		);

		// Add localization data if exists
		$this->maybe_localize_script( $handle );
	}

	/**
	 * Add script localization data
	 *
	 * @param string $handle Script handle
	 * @return void
	 */
	protected function maybe_localize_script( string $handle ): void {
		$localization = $this->get_script_localization( $handle );

		if ( ! empty( $localization ) ) {
			\wp_localize_script( $handle, $localization['object'], $localization['data'] );
		}
	}

	/**
	 * Get script localization data
	 *
	 * @param string $handle Script handle
	 * @return array
	 */
	protected function get_script_localization( string $handle ): array {
		$localizations = [
			'aqualuxe-main' => [
				'object' => 'aqualuxe',
				'data' => [
					'ajax_url' => \admin_url( 'admin-ajax.php' ),
					'nonce' => \wp_create_nonce( 'aqualuxe_nonce' ),
					'theme_url' => \get_template_directory_uri(),
					'is_rtl' => \is_rtl(),
					'locale' => \get_locale(),
					'debug' => $this->config['debug'] ?? false,
				],
			],
			'aqualuxe-admin' => [
				'object' => 'aqualuxe_admin',
				'data' => [
					'ajax_url' => \admin_url( 'admin-ajax.php' ),
					'nonce' => \wp_create_nonce( 'aqualuxe_admin_nonce' ),
				],
			],
		];

		return $localizations[ $handle ] ?? [];
	}

	/**
	 * Register default theme assets
	 *
	 * @return void
	 */
	protected function register_default_assets(): void {
		$theme_uri = \get_template_directory_uri();
		$version = $this->get_asset_version();

		// Styles (only register assets that actually exist)
		$this->register_style( 'aqualuxe-main', $theme_uri . '/assets/dist/css/main.css' );
		$this->register_style( 'aqualuxe-app', $theme_uri . '/assets/dist/css/app.css' );
		$this->register_style( 'aqualuxe-components', $theme_uri . '/assets/dist/css/components.css' );
		$this->register_style( 'aqualuxe-custom', $theme_uri . '/assets/dist/css/custom.css' );
		$this->register_style( 'aqualuxe-woocommerce', $theme_uri . '/assets/dist/css/woocommerce.css' );
		$this->register_style( 'aqualuxe-admin', $theme_uri . '/assets/dist/css/admin.css' );
		$this->register_style( 'aqualuxe-customizer', $theme_uri . '/assets/dist/css/customizer.css' );
		$this->register_style( 'aqualuxe-editor', $theme_uri . '/assets/dist/css/editor.css' );

		// Scripts (only register assets that actually exist)
		$this->register_script( 'aqualuxe-main', $theme_uri . '/assets/dist/js/main.js', [ 'jquery' ] );
		$this->register_script( 'aqualuxe-app', $theme_uri . '/assets/dist/js/app.js', [ 'jquery' ] );
		$this->register_script( 'aqualuxe-admin', $theme_uri . '/assets/dist/js/admin.js', [ 'jquery' ] );
		$this->register_script( 'aqualuxe-customizer', $theme_uri . '/assets/dist/js/customizer.js', [ 'jquery', 'customize-controls' ] );
		$this->register_script( 'aqualuxe-ui-ux', $theme_uri . '/assets/dist/js/ui-ux.js', [ 'jquery' ] );
		$this->register_script( 'aqualuxe-dark-mode', $theme_uri . '/assets/dist/js/dark-mode.js', [ 'jquery' ] );
		$this->register_script( 'aqualuxe-animations', $theme_uri . '/assets/dist/js/animations.js', [ 'jquery' ] );
	}

	/**
	 * Get asset version for cache busting
	 *
	 * @return string
	 */
	protected function get_asset_version(): string {
		if ( $this->config['debug'] ?? false ) {
			return time();
		}

		return $this->config['version'] ?? '1.2.0';
	}

	/**
	 * Get default configuration
	 *
	 * @return array
	 */
	protected function get_default_config(): array {
		return [
			'version' => '1.2.0',
			'debug' => \defined( 'WP_DEBUG' ) && WP_DEBUG,
			'minify' => ! ( \defined( 'WP_DEBUG' ) && WP_DEBUG ),
		];
	}
}
