<?php
/**
 * Service Container for Dependency Injection
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

/**
 * Service Container Class
 */
class AquaLuxe_Service_Container {

    /**
     * Instance
     *
     * @var AquaLuxe_Service_Container
     */
    private static $instance = null;

    /**
     * Services registry
     *
     * @var array
     */
    private $services = array();

    /**
     * Service instances
     *
     * @var array
     */
    private $instances = array();

    /**
     * Get instance
     *
     * @return AquaLuxe_Service_Container
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Private constructor
     */
    private function __construct() {
        // Prevent direct instantiation
    }

    /**
     * Register a service
     *
     * @param string $name Service name
     * @param mixed  $definition Service definition (class name, closure, or instance)
     * @param bool   $singleton Whether to create as singleton
     */
    public function register($name, $definition, $singleton = true) {
        $this->services[$name] = array(
            'definition' => $definition,
            'singleton'  => $singleton,
        );
    }

    /**
     * Get a service
     *
     * @param string $name Service name
     * @return mixed
     * @throws Exception If service not found
     */
    public function get($name) {
        if (!isset($this->services[$name])) {
            throw new Exception("Service '{$name}' not found in container.");
        }

        $service = $this->services[$name];

        // Return existing instance if singleton
        if ($service['singleton'] && isset($this->instances[$name])) {
            return $this->instances[$name];
        }

        $instance = $this->create_instance($service['definition']);

        // Store instance if singleton
        if ($service['singleton']) {
            $this->instances[$name] = $instance;
        }

        return $instance;
    }

    /**
     * Check if service exists
     *
     * @param string $name Service name
     * @return bool
     */
    public function has($name) {
        return isset($this->services[$name]);
    }

    /**
     * Create instance from definition
     *
     * @param mixed $definition Service definition
     * @return mixed
     */
    private function create_instance($definition) {
        if (is_string($definition)) {
            // Class name
            if (class_exists($definition)) {
                return new $definition();
            }
            
            // Global variable or constant
            if (isset($GLOBALS[$definition])) {
                return $GLOBALS[$definition];
            }
            
            throw new Exception("Class or global '{$definition}' not found.");
        }

        if (is_callable($definition)) {
            // Closure or callable
            return call_user_func($definition, $this);
        }

        // Return as-is (already an instance)
        return $definition;
    }

    /**
     * Remove a service
     *
     * @param string $name Service name
     */
    public function remove($name) {
        unset($this->services[$name], $this->instances[$name]);
    }

    /**
     * Get all registered service names
     *
     * @return array
     */
    public function get_service_names() {
        return array_keys($this->services);
    }

    /**
     * Clear all services
     */
    public function clear() {
        $this->services = array();
        $this->instances = array();
    }
}