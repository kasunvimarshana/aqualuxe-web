<?php
/**
 * AquaLuxe Hook Service Provider
 *
 * @package AquaLuxe
 * @since   1.2.0
 */

namespace AquaLuxe\Providers;

use AquaLuxe\Core\Abstract_Service_Provider;
use AquaLuxe\Services\Hook_Service;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Hook_Service_Provider
 *
 * Manages WordPress hooks and filters
 */
class Hook_Service_Provider extends Abstract_Service_Provider {

	/**
	 * Services provided by this provider
	 *
	 * @var array
	 */
	protected $provides = [
		'hooks',
	];

	/**
	 * Register hook services
	 *
	 * @return void
	 */
	protected function register_services(): void {
		$this->singleton( 'hooks', Hook_Service::class );
	}

	/**
	 * Boot hook services
	 *
	 * @return void
	 */
	protected function boot_services(): void {
		$hook_service = $this->resolve( 'hooks' );
		$hook_service->register_hooks();
	}
}
