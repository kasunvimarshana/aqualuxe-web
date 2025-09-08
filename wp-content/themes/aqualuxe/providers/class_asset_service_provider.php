<?php
/**
 * AquaLuxe Asset Service Provider
 *
 * @package AquaLuxe
 * @since   1.2.0
 */

namespace AquaLuxe\Providers;

use AquaLuxe\Core\Abstract_Service_Provider;
use AquaLuxe\Services\Asset_Service;
use AquaLuxe\Services\Script_Service;
use AquaLuxe\Services\Style_Service;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Asset_Service_Provider
 *
 * Manages asset compilation, enqueuing, and optimization
 */
class Asset_Service_Provider extends Abstract_Service_Provider {

	/**
	 * Services provided by this provider
	 *
	 * @var array
	 */
	protected $provides = [
		'assets',
		'scripts',
		'styles',
	];

	/**
	 * Register asset services
	 *
	 * @return void
	 */
	protected function register_services(): void {
		// Asset service
		$this->singleton( 'assets', Asset_Service::class );

		// Script service
		$this->singleton( 'scripts', Script_Service::class );

		// Style service
		$this->singleton( 'styles', Style_Service::class );
	}

	/**
	 * Boot asset services
	 *
	 * @return void
	 */
	protected function boot_services(): void {
		// Setup asset hooks
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_frontend_assets' ], 10 );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_assets' ], 10 );
		add_action( 'login_enqueue_scripts', [ $this, 'enqueue_login_assets' ], 10 );

		// Asset optimization hooks
		add_filter( 'script_loader_tag', [ $this, 'optimize_script_tags' ], 10, 3 );
		add_filter( 'style_loader_tag', [ $this, 'optimize_style_tags' ], 10, 4 );
	}

	/**
	 * Enqueue frontend assets
	 *
	 * @return void
	 */
	public function enqueue_frontend_assets(): void {
		$asset_service = $this->resolve( 'assets' );
		$asset_service->enqueue_frontend_assets();
	}

	/**
	 * Enqueue admin assets
	 *
	 * @return void
	 */
	public function enqueue_admin_assets(): void {
		$asset_service = $this->resolve( 'assets' );
		$asset_service->enqueue_admin_assets();
	}

	/**
	 * Enqueue login page assets
	 *
	 * @return void
	 */
	public function enqueue_login_assets(): void {
		$asset_service = $this->resolve( 'assets' );
		$asset_service->enqueue_login_assets();
	}

	/**
	 * Optimize script tags for performance
	 *
	 * @param string $tag Script tag
	 * @param string $handle Script handle
	 * @param string $src Script source URL
	 * @return string
	 */
	public function optimize_script_tags( string $tag, string $handle, string $src ): string {
		$script_service = $this->resolve( 'scripts' );
		return $script_service->optimize_script_tag( $tag, $handle, $src );
	}

	/**
	 * Optimize style tags for performance
	 *
	 * @param string $tag Style tag
	 * @param string $handle Style handle
	 * @param string $href Style source URL
	 * @param string $media Media type
	 * @return string
	 */
	public function optimize_style_tags( string $tag, string $handle, string $href, string $media ): string {
		$style_service = $this->resolve( 'styles' );
		return $style_service->optimize_style_tag( $tag, $handle, $href, $media );
	}
}
