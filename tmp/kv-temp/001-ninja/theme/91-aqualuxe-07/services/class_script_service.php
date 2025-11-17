<?php
/**
 * AquaLuxe Script Service
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
 * Class Script_Service
 *
 * Handles JavaScript optimization and loading strategies
 */
class Script_Service {

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
	 * Optimize script tag for performance
	 *
	 * @param string $tag Script tag
	 * @param string $handle Script handle
	 * @param string $src Script source URL
	 * @return string Optimized script tag
	 */
	public function optimize_script_tag( string $tag, string $handle, string $src ): string {
		// Skip optimization for admin or login pages
		if ( is_admin() || $GLOBALS['pagenow'] === 'wp-login.php' ) {
			return $tag;
		}

		// Skip optimization for certain critical scripts
		$critical_scripts = [
			'jquery-core',
			'jquery-migrate',
			'wp-polyfill',
		];

		if ( in_array( $handle, $critical_scripts, true ) ) {
			return $tag;
		}

		// Add async/defer attributes for performance
		if ( $this->should_defer_script( $handle ) ) {
			$tag = str_replace( ' src', ' defer src', $tag );
		} elseif ( $this->should_async_script( $handle ) ) {
			$tag = str_replace( ' src', ' async src', $tag );
		}

		// Add preload for critical resources
		if ( $this->should_preload_script( $handle ) ) {
			add_action( 'wp_head', function() use ( $src ) {
				echo "<link rel='preload' href='{$src}' as='script'>\n";
			}, 1 );
		}

		return $tag;
	}

	/**
	 * Check if script should be deferred
	 *
	 * @param string $handle Script handle
	 * @return bool
	 */
	protected function should_defer_script( string $handle ): bool {
		$defer_scripts = $this->config['defer_scripts'] ?? [];
		return in_array( $handle, $defer_scripts, true );
	}

	/**
	 * Check if script should be loaded asynchronously
	 *
	 * @param string $handle Script handle
	 * @return bool
	 */
	protected function should_async_script( string $handle ): bool {
		$async_scripts = $this->config['async_scripts'] ?? [];
		return in_array( $handle, $async_scripts, true );
	}

	/**
	 * Check if script should be preloaded
	 *
	 * @param string $handle Script handle
	 * @return bool
	 */
	protected function should_preload_script( string $handle ): bool {
		$preload_scripts = $this->config['preload_scripts'] ?? [];
		return in_array( $handle, $preload_scripts, true );
	}

	/**
	 * Get default configuration
	 *
	 * @return array
	 */
	protected function get_default_config(): array {
		return [
			'defer_scripts' => [
				'aqualuxe-main',
				'aqualuxe-components',
			],
			'async_scripts' => [
				'google-analytics',
				'facebook-pixel',
			],
			'preload_scripts' => [
				'aqualuxe-main',
			],
		];
	}
}
