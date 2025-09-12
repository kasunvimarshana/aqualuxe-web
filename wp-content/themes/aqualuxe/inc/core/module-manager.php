<?php
/**
 * Module Manager
 *
 * Manages all modules in the AquaLuxe theme.
 * Handles module registration, dependency resolution, and lifecycle.
 *
 * @package AquaLuxe
 * @subpackage Core
 * @since 1.0.0
 * @author AquaLuxe Development Team
 */

namespace AquaLuxe\Core;

use AquaLuxe\Core\Interfaces\Module_Interface;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Module Manager Class
 *
 * Manages the lifecycle of all modules in the theme.
 * Handles registration, dependency resolution, and initialization.
 *
 * @since 1.0.0
 */
class Module_Manager {

    /**
     * The manager instance.
     *
     * @var Module_Manager
     */
    private static $instance = null;

    /**
     * Registered modules.
     *
     * @var array
     */
    private $modules = array();

    /**
     * Loaded modules.
     *
     * @var array
     */
    private $loaded_modules = array();

    /**
     * Failed modules.
     *
     * @var array
     */
    private $failed_modules = array();

    /**
     * Service container.
     *
     * @var Service_Container
     */
    private $container;

    /**
     * Private constructor.
     */
    private function __construct() {
        $this->container = Service_Container::get_instance();
    }

    /**
     * Get the manager instance.
     *
     * @return Module_Manager
     */
    public static function get_instance(): Module_Manager {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Register a module.
     *
     * @param string $name The module name.
     * @param string $class_name The module class name.
     * @param array  $config Module configuration.
     * @return bool True on success, false on failure.
     */
    public function register_module( string $name, string $class_name, array $config = array() ): bool {
        // Validate class exists
        if ( ! class_exists( $class_name ) ) {
            $this->log( "Module class not found: {$class_name}", 'error' );
            return false;
        }

        // Validate class implements Module_Interface
        if ( ! in_array( Module_Interface::class, class_implements( $class_name ) ) ) {
            $this->log( "Module class must implement Module_Interface: {$class_name}", 'error' );
            return false;
        }

        $this->modules[ $name ] = array(
            'class'  => $class_name,
            'config' => $config,
            'status' => 'registered',
        );

        $this->log( "Module registered: {$name}" );
        return true;
    }

    /**
     * Load all modules.
     *
     * @return void
     */
    public function load_modules(): void {
        // Resolve dependencies and load modules in correct order
        $sorted_modules = $this->resolve_dependencies();

        foreach ( $sorted_modules as $name ) {
            $this->load_module( $name );
        }

        // Log results
        $this->log_loading_results();
    }

    /**
     * Load a specific module.
     *
     * @param string $name The module name.
     * @return bool True on success, false on failure.
     */
    public function load_module( string $name ): bool {
        if ( ! isset( $this->modules[ $name ] ) ) {
            $this->log( "Module not registered: {$name}", 'error' );
            return false;
        }

        if ( isset( $this->loaded_modules[ $name ] ) ) {
            $this->log( "Module already loaded: {$name}", 'warning' );
            return true;
        }

        $module_data = $this->modules[ $name ];
        $class_name = $module_data['class'];

        try {
            // Create module instance
            $module = new $class_name( $this->container );

            // Check if module can be loaded
            if ( ! $module->can_load() ) {
                $this->failed_modules[ $name ] = 'Dependencies not met';
                $this->log( "Module cannot be loaded: {$name}", 'warning' );
                return false;
            }

            // Check if module is enabled
            if ( ! $module->is_enabled() ) {
                $this->log( "Module disabled: {$name}" );
                return false;
            }

            // Store the module instance
            $this->loaded_modules[ $name ] = $module;
            $this->modules[ $name ]['status'] = 'loaded';

            // Register with container
            $this->container->instance( $name, $module );

            $this->log( "Module loaded successfully: {$name}" );
            return true;

        } catch ( \Exception $e ) {
            $this->failed_modules[ $name ] = $e->getMessage();
            $this->modules[ $name ]['status'] = 'failed';
            $this->log( "Module failed to load: {$name} - {$e->getMessage()}", 'error' );
            return false;
        }
    }

    /**
     * Get a loaded module.
     *
     * @param string $name The module name.
     * @return Module_Interface|null The module instance or null if not found.
     */
    public function get_module( string $name ): ?Module_Interface {
        return isset( $this->loaded_modules[ $name ] ) ? $this->loaded_modules[ $name ] : null;
    }

    /**
     * Check if a module is loaded.
     *
     * @param string $name The module name.
     * @return bool True if loaded, false otherwise.
     */
    public function is_module_loaded( string $name ): bool {
        return isset( $this->loaded_modules[ $name ] );
    }

    /**
     * Get all registered modules.
     *
     * @return array The registered modules.
     */
    public function get_registered_modules(): array {
        return $this->modules;
    }

    /**
     * Get all loaded modules.
     *
     * @return array The loaded modules.
     */
    public function get_loaded_modules(): array {
        return $this->loaded_modules;
    }

    /**
     * Get failed modules.
     *
     * @return array The failed modules.
     */
    public function get_failed_modules(): array {
        return $this->failed_modules;
    }

    /**
     * Resolve module dependencies and return sorted array.
     *
     * @return array Sorted module names.
     */
    private function resolve_dependencies(): array {
        $resolved = array();
        $unresolved = array();

        foreach ( array_keys( $this->modules ) as $name ) {
            $this->resolve_module_dependencies( $name, $resolved, $unresolved );
        }

        return $resolved;
    }

    /**
     * Resolve dependencies for a specific module.
     *
     * @param string $name The module name.
     * @param array  $resolved Resolved modules (by reference).
     * @param array  $unresolved Unresolved modules (by reference).
     * @return void
     * @throws \Exception If circular dependency is detected.
     */
    private function resolve_module_dependencies( string $name, array &$resolved, array &$unresolved ): void {
        // Check for circular dependency
        if ( in_array( $name, $unresolved ) ) {
            throw new \Exception( "Circular dependency detected for module: {$name}" );
        }

        // Skip if already resolved
        if ( in_array( $name, $resolved ) ) {
            return;
        }

        // Add to unresolved
        $unresolved[] = $name;

        // Get module dependencies
        $dependencies = $this->get_module_dependencies( $name );

        // Resolve each dependency first
        foreach ( $dependencies as $dependency ) {
            if ( isset( $this->modules[ $dependency ] ) ) {
                $this->resolve_module_dependencies( $dependency, $resolved, $unresolved );
            }
        }

        // Remove from unresolved and add to resolved
        $unresolved = array_diff( $unresolved, array( $name ) );
        $resolved[] = $name;
    }

    /**
     * Get module dependencies.
     *
     * @param string $name The module name.
     * @return array The module dependencies.
     */
    private function get_module_dependencies( string $name ): array {
        if ( ! isset( $this->modules[ $name ] ) ) {
            return array();
        }

        $class_name = $this->modules[ $name ]['class'];

        // Create temporary instance to get dependencies
        try {
            $reflection = new \ReflectionClass( $class_name );
            $temp_instance = $reflection->newInstanceWithoutConstructor();
            
            if ( method_exists( $temp_instance, 'get_dependencies' ) ) {
                return $temp_instance->get_dependencies();
            }
        } catch ( \Exception $e ) {
            $this->log( "Could not get dependencies for module: {$name} - {$e->getMessage()}", 'warning' );
        }

        return array();
    }

    /**
     * Enable a module.
     *
     * @param string $name The module name.
     * @return bool True on success, false on failure.
     */
    public function enable_module( string $name ): bool {
        $module = $this->get_module( $name );
        
        if ( ! $module ) {
            // Try to load the module first
            if ( ! $this->load_module( $name ) ) {
                return false;
            }
            $module = $this->get_module( $name );
        }

        return $module ? $module->enable() : false;
    }

    /**
     * Disable a module.
     *
     * @param string $name The module name.
     * @return bool True on success, false on failure.
     */
    public function disable_module( string $name ): bool {
        $module = $this->get_module( $name );
        
        if ( ! $module ) {
            return false;
        }

        return $module->disable();
    }

    /**
     * Register default modules.
     *
     * @return void
     */
    public function register_default_modules(): void {
        $default_modules = array(
            'dark-mode'           => '\\AquaLuxe\\Modules\\Dark_Mode',
            'multilingual'        => '\\AquaLuxe\\Modules\\Multilingual',
            'performance'         => '\\AquaLuxe\\Modules\\Performance',
            'security'            => '\\AquaLuxe\\Modules\\Security',
            'seo'                 => '\\AquaLuxe\\Modules\\SEO',
            'custom-post-types'   => '\\AquaLuxe\\Modules\\Custom_Post_Types',
            'woocommerce'         => '\\AquaLuxe\\Modules\\WooCommerce_Integration',
            'demo-importer'       => '\\AquaLuxe\\Modules\\Demo_Content_Importer',
        );

        foreach ( $default_modules as $name => $class ) {
            if ( class_exists( $class ) ) {
                $this->register_module( $name, $class );
            }
        }
    }

    /**
     * Log loading results.
     *
     * @return void
     */
    private function log_loading_results(): void {
        $loaded_count = count( $this->loaded_modules );
        $failed_count = count( $this->failed_modules );
        $total_count = count( $this->modules );

        $this->log( "Module loading complete: {$loaded_count}/{$total_count} loaded, {$failed_count} failed" );

        if ( ! empty( $this->failed_modules ) ) {
            foreach ( $this->failed_modules as $name => $reason ) {
                $this->log( "Failed module '{$name}': {$reason}", 'warning' );
            }
        }
    }

    /**
     * Get module status report.
     *
     * @return array Module status report.
     */
    public function get_status_report(): array {
        return array(
            'total_registered' => count( $this->modules ),
            'total_loaded'     => count( $this->loaded_modules ),
            'total_failed'     => count( $this->failed_modules ),
            'modules'          => $this->modules,
            'failed_modules'   => $this->failed_modules,
        );
    }

    /**
     * Log message.
     *
     * @param string $message The message.
     * @param string $level The log level.
     * @return void
     */
    private function log( string $message, string $level = 'info' ): void {
        if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
            error_log( sprintf( '[AquaLuxe][ModuleManager][%s] %s', strtoupper( $level ), $message ) );
        }
    }
}