<?php
/**
 * AquaLuxe Configuration Service Provider
 *
 * @package AquaLuxe
 * @since   1.2.0
 */

namespace AquaLuxe\Providers;

use AquaLuxe\Core\Abstract_Service_Provider;
use AquaLuxe\Services\Config_Service;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Config_Service_Provider
 *
 * Manages application configuration
 */
class Config_Service_Provider extends Abstract_Service_Provider {

	/**
	 * Services provided by this provider
	 *
	 * @var array
	 */
	protected $provides = [
		'config',
	];

	/**
	 * Register configuration services
	 *
	 * @return void
	 */
	protected function register_services(): void {
		$this->singleton( 'config', Config_Service::class );
	}

	/**
	 * Boot configuration services
	 *
	 * @return void
	 */
	protected function boot_services(): void {
		// Load configuration files
		$config_service = $this->resolve( 'config' );
		$config_service->load_configurations();
	}
}
