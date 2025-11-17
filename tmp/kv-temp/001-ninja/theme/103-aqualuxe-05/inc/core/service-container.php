<?php
/**
 * Service Container
 *
 * A simple dependency injection container for the AquaLuxe theme.
 * Manages service instances and dependencies following SOLID principles.
 *
 * @package AquaLuxe
 * @subpackage Core
 * @since 1.0.0
 * @author AquaLuxe Development Team
 */

namespace AquaLuxe\Core;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Service Container Class
 *
 * Implements a simple dependency injection container.
 * Manages service registration, instantiation, and dependency resolution.
 *
 * @since 1.0.0
 */
class Service_Container {

    /**
     * The container instance.
     *
     * @var Service_Container
     */
    private static $instance = null;

    /**
     * Registered services.
     *
     * @var array
     */
    private $services = [];

    /**
     * Service instances.
     *
     * @var array
     */
    private $instances = [];

    /**
     * Service definitions.
     *
     * @var array
     */
    private $definitions = [];

    /**
     * Private constructor to prevent direct instantiation.
     */
    private function __construct() {}

    /**
     * Get the container instance.
     *
     * @return Service_Container
     */
    public static function get_instance(): Service_Container {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Register a service.
     *
     * @param string $name The service name.
     * @param mixed  $definition The service definition (class name or callable).
     * @param bool   $singleton Whether the service should be a singleton.
     * @return void
     */
    public function register( string $name, $definition, bool $singleton = true ): void {
        $this->definitions[ $name ] = [
            'definition' => $definition,
            'singleton'  => $singleton,
        ];
    }

    /**
     * Get a service instance.
     *
     * @param string $name The service name.
     * @return mixed The service instance.
     * @throws \Exception If service is not registered.
     */
    public function get( string $name ) {
        if ( ! $this->has( $name ) ) {
            throw new \Exception( "Service '{$name}' is not registered." );
        }

        $definition = $this->definitions[ $name ];

        // Return existing instance if singleton
        if ( $definition['singleton'] && isset( $this->instances[ $name ] ) ) {
            return $this->instances[ $name ];
        }

        // Create new instance
        $instance = $this->create_instance( $definition['definition'] );

        // Store instance if singleton
        if ( $definition['singleton'] ) {
            $this->instances[ $name ] = $instance;
        }

        return $instance;
    }

    /**
     * Check if a service is registered.
     *
     * @param string $name The service name.
     * @return bool True if registered, false otherwise.
     */
    public function has( string $name ): bool {
        return isset( $this->definitions[ $name ] );
    }

    /**
     * Register a singleton service.
     *
     * @param string $name The service name.
     * @param mixed  $definition The service definition.
     * @return void
     */
    public function singleton( string $name, $definition ): void {
        $this->register( $name, $definition, true );
    }

    /**
     * Register a transient service.
     *
     * @param string $name The service name.
     * @param mixed  $definition The service definition.
     * @return void
     */
    public function transient( string $name, $definition ): void {
        $this->register( $name, $definition, false );
    }

    /**
     * Bind an instance to a service name.
     *
     * @param string $name The service name.
     * @param mixed  $instance The service instance.
     * @return void
     */
    public function instance( string $name, $instance ): void {
        $this->instances[ $name ] = $instance;
        $this->definitions[ $name ] = [
            'definition' => $instance,
            'singleton'  => true,
        ];
    }

    /**
     * Create a service instance.
     *
     * @param mixed $definition The service definition.
     * @return mixed The service instance.
     */
    private function create_instance( $definition ) {
        // If it's already an instance, return it
        if ( is_object( $definition ) && ! is_callable( $definition ) ) {
            return $definition;
        }

        // If it's a callable, call it
        if ( is_callable( $definition ) ) {
            return call_user_func( $definition, $this );
        }

        // If it's a class name, instantiate it
        if ( is_string( $definition ) && class_exists( $definition ) ) {
            return $this->resolve_class( $definition );
        }

        throw new \Exception( "Unable to resolve service definition: " . print_r( $definition, true ) );
    }

    /**
     * Resolve a class with dependency injection.
     *
     * @param string $class_name The class name.
     * @return object The class instance.
     * @throws \Exception If class cannot be resolved.
     */
    private function resolve_class( string $class_name ) {
        $reflection = new \ReflectionClass( $class_name );

        if ( ! $reflection->isInstantiable() ) {
            throw new \Exception( "Class '{$class_name}' is not instantiable." );
        }

        $constructor = $reflection->getConstructor();

        // If no constructor, just instantiate
        if ( null === $constructor ) {
            return new $class_name();
        }

        $parameters = $constructor->getParameters();
        $dependencies = [];

        foreach ( $parameters as $parameter ) {
            $type = $parameter->getType();

            if ( null === $type ) {
                // No type hint, check if it has a default value
                if ( $parameter->isDefaultValueAvailable() ) {
                    $dependencies[] = $parameter->getDefaultValue();
                } else {
                    throw new \Exception( "Cannot resolve parameter '{$parameter->getName()}' for class '{$class_name}'." );
                }
                continue;
            }

            $type_name = $type->getName();

            // Try to resolve from container
            if ( $this->has( $type_name ) ) {
                $dependencies[] = $this->get( $type_name );
                continue;
            }

            // Try to resolve as class
            if ( class_exists( $type_name ) ) {
                $dependencies[] = $this->resolve_class( $type_name );
                continue;
            }

            // Check if parameter is optional
            if ( $parameter->isDefaultValueAvailable() ) {
                $dependencies[] = $parameter->getDefaultValue();
                continue;
            }

            throw new \Exception( "Cannot resolve dependency '{$type_name}' for class '{$class_name}'." );
        }

        return $reflection->newInstanceArgs( $dependencies );
    }

    /**
     * Get all registered services.
     *
     * @return array The registered services.
     */
    public function get_services(): array {
        return array_keys( $this->definitions );
    }

    /**
     * Clear all services.
     *
     * @return void
     */
    public function clear(): void {
        $this->services = [];
        $this->instances = [];
        $this->definitions = [];
    }

    /**
     * Make an instance without registering it.
     *
     * @param string $class_name The class name.
     * @return mixed The instance.
     */
    public function make( string $class_name ) {
        return $this->resolve_class( $class_name );
    }

    /**
     * Call a method with dependency injection.
     *
     * @param mixed  $callback The callback to call.
     * @param array  $parameters Additional parameters.
     * @return mixed The result of the method call.
     */
    public function call( $callback, array $parameters = [] ) {
        if ( is_array( $callback ) ) {
            list( $class, $method ) = $callback;
            
            if ( is_string( $class ) ) {
                $class = $this->make( $class );
            }
            
            $reflection = new \ReflectionMethod( $class, $method );
        } elseif ( is_callable( $callback ) ) {
            $reflection = new \ReflectionFunction( $callback );
        } else {
            throw new \Exception( "Invalid callback provided." );
        }

        $dependencies = [];
        foreach ( $reflection->getParameters() as $parameter ) {
            $name = $parameter->getName();
            
            // Use provided parameter if available
            if ( isset( $parameters[ $name ] ) ) {
                $dependencies[] = $parameters[ $name ];
                continue;
            }

            $type = $parameter->getType();
            if ( $type ) {
                $type_name = $type->getName();
                
                if ( $this->has( $type_name ) ) {
                    $dependencies[] = $this->get( $type_name );
                    continue;
                }
                
                if ( class_exists( $type_name ) ) {
                    $dependencies[] = $this->make( $type_name );
                    continue;
                }
            }

            if ( $parameter->isDefaultValueAvailable() ) {
                $dependencies[] = $parameter->getDefaultValue();
                continue;
            }

            throw new \Exception( "Cannot resolve parameter '{$parameter->getName()}'." );
        }

        return call_user_func_array( $callback, $dependencies );
    }
}