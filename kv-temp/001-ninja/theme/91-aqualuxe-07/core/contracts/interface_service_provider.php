<?php
/**
 * AquaLuxe Service Provider Interface
 *
 * @package AquaLuxe
 * @since   1.2.0
 */

namespace AquaLuxe\Core\Contracts;

use AquaLuxe\Core\Container;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Interface Service_Provider_Interface
 *
 * Contract for service providers following Interface Segregation Principle
 */
interface Service_Provider_Interface {

	/**
	 * Register services in the container
	 *
	 * @param Container $container The DI container
	 * @return void
	 */
	public function register( Container $container ): void;

	/**
	 * Bootstrap services after all providers are registered
	 *
	 * @param Container $container The DI container
	 * @return void
	 */
	public function boot( Container $container ): void;

	/**
	 * Get the services provided by this provider
	 *
	 * @return array
	 */
	public function provides(): array;
}
