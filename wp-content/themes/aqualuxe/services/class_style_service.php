<?php
/**
 * AquaLuxe Style Service
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
 * Class Style_Service
 *
 * Handles CSS optimization and loading strategies
 */
class Style_Service {

	/**
	 * Configuration
	 *
	 * @var array
	 */
	protected $config;

	/**
	 * Constructor
	 *
	 * @param array $config Configuration
	 */
	public function __construct( array $config = [] ) {
		$this->config = array_merge( $this->get_default_config(), $config );
	}

	/**
	 * Optimize style tag for performance
	 *
	 * @param string $tag Style tag
	 * @param string $handle Style handle
	 * @param string $href Style source URL
	 * @param string $media Media type
	 * @return string Optimized style tag
	 */
	public function optimize_style_tag( string $tag, string $handle, string $href, string $media ): string {
		// Skip optimization for admin or login pages
		if ( is_admin() || $GLOBALS['pagenow'] === 'wp-login.php' ) {
			return $tag;
		}

		// Critical CSS should load normally
		if ( $this->is_critical_css( $handle ) ) {
			return $tag;
		}

		// Non-critical CSS can be loaded asynchronously
		if ( $this->should_async_css( $handle, $media ) ) {
			// Convert to preload with onload fallback
			$tag = str_replace(
				"rel='stylesheet'",
				"rel='preload' as='style' onload=\"this.onload=null;this.rel='stylesheet'\"",
				$tag
			);

			// Add noscript fallback
			$noscript = str_replace( "rel='preload' as='style' onload=\"this.onload=null;this.rel='stylesheet'\"", "rel='stylesheet'", $tag );
			$tag .= "\n<noscript>{$noscript}</noscript>";
		}

		// Add preload for critical resources
		if ( $this->should_preload_css( $handle ) ) {
			add_action( 'wp_head', function() use ( $href ) {
				echo "<link rel='preload' href='{$href}' as='style'>\n";
			}, 1 );
		}

		return $tag;
	}

	/**
	 * Check if CSS is critical
	 *
	 * @param string $handle Style handle
	 * @return bool
	 */
	protected function is_critical_css( string $handle ): bool {
		$critical_styles = $this->config['critical_styles'] ?? [];
		return in_array( $handle, $critical_styles, true );
	}

	/**
	 * Check if CSS should be loaded asynchronously
	 *
	 * @param string $handle Style handle
	 * @param string $media Media type
	 * @return bool
	 */
	protected function should_async_css( string $handle, string $media ): bool {
		// Don't async load print stylesheets or critical CSS
		if ( $media === 'print' || $this->is_critical_css( $handle ) ) {
			return false;
		}

		$async_styles = $this->config['async_styles'] ?? [];
		return in_array( $handle, $async_styles, true );
	}

	/**
	 * Check if CSS should be preloaded
	 *
	 * @param string $handle Style handle
	 * @return bool
	 */
	protected function should_preload_css( string $handle ): bool {
		$preload_styles = $this->config['preload_styles'] ?? [];
		return in_array( $handle, $preload_styles, true );
	}

	/**
	 * Get default configuration
	 *
	 * @return array
	 */
	protected function get_default_config(): array {
		return [
			'critical_styles' => [
				'aqualuxe-main',
			],
			'async_styles' => [
				'aqualuxe-components',
				'aqualuxe-woocommerce',
			],
			'preload_styles' => [
				'aqualuxe-main',
			],
		];
	}
}
