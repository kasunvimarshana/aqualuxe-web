<?php
/**
 * AquaLuxe Dependency Injection Container
 *
 * @package AquaLuxe
 * @since   1.2.0
 */

namespace AquaLuxe\Core;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Container
 * 
 * Simple dependency injection container following SOLID principles
 */
class Container {

	/**
	 * Container bindings
	 *
	 * @var array
	 */
	private array $bindings = [];

	/**
	 * Resolved instances
	 *
	 * @var array
	 */
	private array $instances = [];

	/**
	 * Singleton instances
	 *
	 * @var array
	 */
	private array $singletons = [];

	/**
	 * The container instance
	 *
	 * @var Container|null
	 */
	private static ?Container $instance = null;

	/**
	 * Get the container instance
	 *
	 * @return Container
	 */
	public static function get_instance(): Container {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Bind a service to the container
	 *
	 * @param string   $abstract The service identifier
	 * @param callable|string|null $concrete The service factory or class name
	 * @param bool     $singleton Whether to treat as singleton
	 * @return void
	 */
	public function bind( string $abstract, $concrete = null, bool $singleton = false ): void {
		// If no concrete provided, use abstract as class name
		if ( null === $concrete ) {
			$concrete = $abstract;
		}

		$this->bindings[ $abstract ] = [
			'concrete'  => $concrete,
			'singleton' => $singleton,
		];
	}

	/**
	 * Bind a singleton service
	 *
	 * @param string   $abstract The service identifier
	 * @param callable|string|null $concrete The service factory or class name
	 * @return void
	 */
	public function singleton( string $abstract, $concrete = null ): void {
		$this->bind( $abstract, $concrete, true );
	}

	/**
	 * Resolve a service from the container
	 *
	 * @param string $abstract The service identifier
	 * @return mixed
	 * @throws \InvalidArgumentException If service not found
	 */
	public function resolve( string $abstract ) {
		// Return singleton if already resolved
		if ( isset( $this->singletons[ $abstract ] ) ) {
			return $this->singletons[ $abstract ];
		}

		// Check if binding exists
		if ( ! isset( $this->bindings[ $abstract ] ) ) {
			// Try to auto-resolve if class exists
			if ( class_exists( $abstract ) ) {
				return $this->auto_resolve( $abstract );
			}
			
			throw new \InvalidArgumentException( "Service [{$abstract}] not found in container" );
		}

		$binding = $this->bindings[ $abstract ];
		$concrete = $binding['concrete'];

		// Handle different concrete types
		if ( is_callable( $concrete ) ) {
			// Callable factory
			$instance = $concrete( $this );
		} elseif ( is_string( $concrete ) && class_exists( $concrete ) ) {
			// Class name - auto-resolve
			$instance = $this->auto_resolve( $concrete );
		} else {
			throw new \InvalidArgumentException( "Invalid concrete type for service [{$abstract}]" );
		}

		// Store singleton instance
		if ( $binding['singleton'] ) {
			$this->singletons[ $abstract ] = $instance;
		}

		return $instance;
	}

	/**
	 * Auto-resolve a class using reflection
	 *
	 * @param string $abstract The class name
	 * @return object
	 * @throws \ReflectionException
	 */
	private function auto_resolve( string $abstract ): object {
		$reflection = new \ReflectionClass( $abstract );
		
		if ( ! $reflection->isInstantiable() ) {
			throw new \InvalidArgumentException( "Class [{$abstract}] is not instantiable" );
		}

		$constructor = $reflection->getConstructor();

		if ( null === $constructor ) {
			return new $abstract();
		}

		$parameters = $constructor->getParameters();
		$dependencies = [];

		foreach ( $parameters as $parameter ) {
			$type = $parameter->getType();
			
			if ( null === $type || $type->isBuiltin() ) {
				if ( $parameter->isDefaultValueAvailable() ) {
					$dependencies[] = $parameter->getDefaultValue();
				} else {
					throw new \InvalidArgumentException( "Cannot resolve parameter [{$parameter->getName()}] for class [{$abstract}]" );
				}
			} else {
				$dependencies[] = $this->resolve( $type->getName() );
			}
		}

		return $reflection->newInstanceArgs( $dependencies );
	}

	/**
	 * Check if a service is bound
	 *
	 * @param string $abstract The service identifier
	 * @return bool
	 */
	public function has( string $abstract ): bool {
		return isset( $this->bindings[ $abstract ] ) || class_exists( $abstract );
	}

	/**
	 * Get all registered services
	 *
	 * @return array
	 */
	public function get_bindings(): array {
		return array_keys( $this->bindings );
	}
}
