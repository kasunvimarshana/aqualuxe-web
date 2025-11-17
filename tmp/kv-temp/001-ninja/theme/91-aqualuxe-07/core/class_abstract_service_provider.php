<?php
/**
 * AquaLuxe Abstract Service Provider
 *
 * @package AquaLuxe
 * @since   1.2.0
 */

namespace AquaLuxe\Core;

use AquaLuxe\Core\Contracts\Service_Provider_Interface;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Abstract_Service_Provider
 *
 * Base implementation for service providers following Template Method Pattern
 */
abstract class Abstract_Service_Provider implements Service_Provider_Interface {

	/**
	 * Container instance
	 *
	 * @var Container
	 */
	protected $container;

	/**
	 * Services provided by this provider
	 *
	 * @var array
	 */
	protected $provides = [];

	/**
	 * Whether the provider has been booted
	 *
	 * @var bool
	 */
	protected $booted = false;

	/**
	 * Register services in the container
	 *
	 * @param Container $container The DI container
	 * @return void
	 */
	public function register( Container $container ): void {
		$this->container = $container;
		$this->register_services();
	}

	/**
	 * Bootstrap services after all providers are registered
	 *
	 * @param Container $container The DI container
	 * @return void
	 */
	public function boot( Container $container ): void {
		if ( $this->booted ) {
			return;
		}

		$this->container = $container;
		$this->boot_services();
		$this->booted = true;
	}

	/**
	 * Get the services provided by this provider
	 *
	 * @return array
	 */
	public function provides(): array {
		return $this->provides;
	}

	/**
	 * Determine if the provider is deferred
	 *
	 * @return bool
	 */
	public function is_deferred(): bool {
		return ! empty( $this->provides );
	}

	/**
	 * Register specific services
	 * To be implemented by concrete providers
	 *
	 * @return void
	 */
	abstract protected function register_services(): void;

	/**
	 * Bootstrap specific services
	 * Can be overridden by concrete providers
	 *
	 * @return void
	 */
	protected function boot_services(): void {
		// Optional implementation in concrete providers
	}

	/**
	 * Helper method to bind singleton services
	 *
	 * @param string $abstract Service identifier
	 * @param callable|string|null $concrete Service implementation
	 * @return void
	 */
	protected function singleton( string $abstract, $concrete = null ): void {
		$this->container->singleton( $abstract, $concrete );
	}

	/**
	 * Helper method to bind services
	 *
	 * @param string $abstract Service identifier
	 * @param callable|string|null $concrete Service implementation
	 * @return void
	 */
	protected function bind( string $abstract, $concrete = null ): void {
		$this->container->bind( $abstract, $concrete );
	}

	/**
	 * Helper method to resolve services
	 *
	 * @param string $abstract Service identifier
	 * @return mixed
	 */
	protected function resolve( string $abstract ) {
		return $this->container->resolve( $abstract );
	}
}
