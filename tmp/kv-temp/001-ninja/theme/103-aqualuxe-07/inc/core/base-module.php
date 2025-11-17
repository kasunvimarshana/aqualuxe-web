<?php
/**
 * Base Module Class
 *
 * Abstract base class for all modules in the AquaLuxe theme.
 * Implements common functionality shared by all modules.
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
 * Base Module Class
 *
 * Provides common functionality for all modules.
 * Modules should extend this class and implement the abstract methods.
 *
 * @since 1.0.0
 */
abstract class Base_Module implements Module_Interface {

    /**
     * Module enabled status.
     *
     * @var bool
     */
    protected $enabled = true;

    /**
     * Module initialization status.
     *
     * @var bool
     */
    protected $initialized = false;

    /**
     * Service container instance.
     *
     * @var Service_Container
     */
    protected $container;

    /**
     * Constructor.
     *
     * @param Service_Container $container The service container.
     */
    public function __construct( Service_Container $container = null ) {
        $this->container = $container ?: Service_Container::get_instance();
        
        // Check if module can be loaded
        if ( $this->can_load() ) {
            $this->init();
        }
    }

    /**
     * Initialize the module.
     *
     * @return void
     */
    public function init(): void {
        if ( $this->initialized ) {
            return;
        }

        // Hook into WordPress initialization
        add_action( 'init', array( $this, 'on_init' ), 10 );
        add_action( 'wp_loaded', array( $this, 'on_wp_loaded' ), 10 );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ), 10 );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ), 10 );

        // Module-specific initialization
        $this->setup();

        $this->initialized = true;

        // Log module initialization in debug mode
        if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
            error_log( sprintf( 'AquaLuxe Module initialized: %s', $this->get_name() ) );
        }
    }

    /**
     * Check if the module can be loaded.
     *
     * @return bool True if the module can be loaded, false otherwise.
     */
    public function can_load(): bool {
        // Check dependencies
        $dependencies = $this->get_dependencies();
        
        foreach ( $dependencies as $dependency ) {
            if ( ! $this->is_dependency_available( $dependency ) ) {
                return false;
            }
        }

        // Check WordPress version
        if ( ! $this->is_wp_version_compatible() ) {
            return false;
        }

        // Check PHP version
        if ( ! $this->is_php_version_compatible() ) {
            return false;
        }

        return true;
    }

    /**
     * Enable the module.
     *
     * @return bool True on success, false on failure.
     */
    public function enable(): bool {
        if ( ! $this->can_load() ) {
            return false;
        }

        $this->enabled = true;
        
        if ( ! $this->initialized ) {
            $this->init();
        }

        // Store enabled status
        $this->update_module_option( 'enabled', true );

        return true;
    }

    /**
     * Disable the module.
     *
     * @return bool True on success, false on failure.
     */
    public function disable(): bool {
        $this->enabled = false;
        
        // Store disabled status
        $this->update_module_option( 'enabled', false );

        // Call module-specific cleanup
        $this->cleanup();

        return true;
    }

    /**
     * Check if the module is enabled.
     *
     * @return bool True if enabled, false otherwise.
     */
    public function is_enabled(): bool {
        // Check stored option first
        $stored = $this->get_module_option( 'enabled' );
        
        if ( null !== $stored ) {
            $this->enabled = (bool) $stored;
        }

        return $this->enabled;
    }

    /**
     * Get module option.
     *
     * @param string $key The option key.
     * @param mixed  $default The default value.
     * @return mixed The option value.
     */
    protected function get_module_option( string $key, $default = null ) {
        $options = get_option( 'aqualuxe_module_' . $this->get_slug(), array() );
        
        return isset( $options[ $key ] ) ? $options[ $key ] : $default;
    }

    /**
     * Update module option.
     *
     * @param string $key The option key.
     * @param mixed  $value The option value.
     * @return bool True on success, false on failure.
     */
    protected function update_module_option( string $key, $value ): bool {
        $options = get_option( 'aqualuxe_module_' . $this->get_slug(), array() );
        $options[ $key ] = $value;
        
        return update_option( 'aqualuxe_module_' . $this->get_slug(), $options );
    }

    /**
     * Get the module slug.
     *
     * @return string The module slug.
     */
    public function get_slug(): string {
        return sanitize_title( $this->get_name() );
    }

    /**
     * Check if a dependency is available.
     *
     * @param string $dependency The dependency name.
     * @return bool True if available, false otherwise.
     */
    protected function is_dependency_available( string $dependency ): bool {
        // Check for WordPress plugins
        if ( strpos( $dependency, '/' ) !== false ) {
            return is_plugin_active( $dependency );
        }

        // Check for WordPress functions
        if ( function_exists( $dependency ) ) {
            return true;
        }

        // Check for classes
        if ( class_exists( $dependency ) ) {
            return true;
        }

        // Check for other modules
        if ( $this->container->has( $dependency ) ) {
            return true;
        }

        return false;
    }

    /**
     * Check WordPress version compatibility.
     *
     * @return bool True if compatible, false otherwise.
     */
    protected function is_wp_version_compatible(): bool {
        global $wp_version;
        
        $required_version = $this->get_required_wp_version();
        
        if ( empty( $required_version ) ) {
            return true;
        }

        return version_compare( $wp_version, $required_version, '>=' );
    }

    /**
     * Check PHP version compatibility.
     *
     * @return bool True if compatible, false otherwise.
     */
    protected function is_php_version_compatible(): bool {
        $required_version = $this->get_required_php_version();
        
        if ( empty( $required_version ) ) {
            return true;
        }

        return version_compare( PHP_VERSION, $required_version, '>=' );
    }

    /**
     * Get required WordPress version.
     *
     * @return string The required WordPress version.
     */
    protected function get_required_wp_version(): string {
        return '6.0';
    }

    /**
     * Get required PHP version.
     *
     * @return string The required PHP version.
     */
    protected function get_required_php_version(): string {
        return '8.0';
    }

    /**
     * Module-specific setup.
     * Override this method in child classes.
     *
     * @return void
     */
    protected function setup(): void {
        // Override in child classes
    }

    /**
     * Module-specific cleanup.
     * Override this method in child classes.
     *
     * @return void
     */
    protected function cleanup(): void {
        // Override in child classes
    }

    /**
     * Called on WordPress 'init' action.
     * Override this method in child classes.
     *
     * @return void
     */
    public function on_init(): void {
        // Override in child classes
    }

    /**
     * Called on WordPress 'wp_loaded' action.
     * Override this method in child classes.
     *
     * @return void
     */
    public function on_wp_loaded(): void {
        // Override in child classes
    }

    /**
     * Enqueue frontend assets.
     * Override this method in child classes.
     *
     * @return void
     */
    public function enqueue_assets(): void {
        // Override in child classes
    }

    /**
     * Enqueue admin assets.
     * Override this method in child classes.
     *
     * @return void
     */
    public function enqueue_admin_assets(): void {
        // Override in child classes
    }

    /**
     * Load template file.
     *
     * @param string $template The template name.
     * @param array  $vars Variables to pass to the template.
     * @param bool   $return Whether to return the output.
     * @return string|void Template output if $return is true.
     */
    protected function load_template( string $template, array $vars = array(), bool $return = false ) {
        $template_path = $this->get_template_path( $template );
        
        if ( ! file_exists( $template_path ) ) {
            return $return ? '' : null;
        }

        if ( ! empty( $vars ) ) {
            extract( $vars );
        }

        if ( $return ) {
            ob_start();
            include $template_path;
            return ob_get_clean();
        }

        include $template_path;
    }

    /**
     * Get template path.
     *
     * @param string $template The template name.
     * @return string The template path.
     */
    protected function get_template_path( string $template ): string {
        $module_dir = dirname( ( new \ReflectionClass( $this ) )->getFileName() );
        
        return $module_dir . '/templates/' . $template . '.php';
    }

    /**
     * Log message in debug mode.
     *
     * @param string $message The message to log.
     * @param string $level The log level.
     * @return void
     */
    protected function log( string $message, string $level = 'info' ): void {
        if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
            error_log( sprintf( '[AquaLuxe][%s][%s] %s', strtoupper( $level ), $this->get_name(), $message ) );
        }
    }
}