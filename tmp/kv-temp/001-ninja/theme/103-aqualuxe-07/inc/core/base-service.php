<?php
/**
 * Base Service Class
 *
 * Abstract base class for all services in the AquaLuxe theme.
 * Implements common functionality shared by all services.
 *
 * @package AquaLuxe
 * @subpackage Core
 * @since 1.0.0
 * @author AquaLuxe Development Team
 */

namespace AquaLuxe\Core;

use AquaLuxe\Core\Interfaces\Service_Interface;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Base Service Class
 *
 * Provides common functionality for all services.
 * Services should extend this class and implement the abstract methods.
 *
 * @since 1.0.0
 */
abstract class Base_Service implements Service_Interface {

    /**
     * Service instance.
     *
     * @var static
     */
    protected static $instance = null;

    /**
     * Service initialization status.
     *
     * @var bool
     */
    protected $initialized = false;

    /**
     * Service options.
     *
     * @var array
     */
    protected $options = array();

    /**
     * Get the service instance.
     *
     * @return static
     */
    public static function get_instance() {
        if ( null === static::$instance ) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    /**
     * Private constructor to prevent direct instantiation.
     */
    protected function __construct() {
        $this->load_options();
    }

    /**
     * Initialize the service.
     *
     * @return void
     */
    public function init(): void {
        if ( $this->initialized ) {
            return;
        }

        // Service-specific setup
        $this->setup();

        $this->initialized = true;

        // Log service initialization in debug mode
        if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
            error_log( sprintf( 'AquaLuxe Service initialized: %s', $this->get_name() ) );
        }
    }

    /**
     * Load service options.
     *
     * @return void
     */
    protected function load_options(): void {
        $this->options = get_option( 'aqualuxe_service_' . $this->get_slug(), array() );
    }

    /**
     * Save service options.
     *
     * @return bool True on success, false on failure.
     */
    protected function save_options(): bool {
        return update_option( 'aqualuxe_service_' . $this->get_slug(), $this->options );
    }

    /**
     * Get service option.
     *
     * @param string $key The option key.
     * @param mixed  $default The default value.
     * @return mixed The option value.
     */
    protected function get_option( string $key, $default = null ) {
        return isset( $this->options[ $key ] ) ? $this->options[ $key ] : $default;
    }

    /**
     * Set service option.
     *
     * @param string $key The option key.
     * @param mixed  $value The option value.
     * @return void
     */
    protected function set_option( string $key, $value ): void {
        $this->options[ $key ] = $value;
    }

    /**
     * Update service option.
     *
     * @param string $key The option key.
     * @param mixed  $value The option value.
     * @return bool True on success, false on failure.
     */
    protected function update_option( string $key, $value ): bool {
        $this->set_option( $key, $value );
        return $this->save_options();
    }

    /**
     * Delete service option.
     *
     * @param string $key The option key.
     * @return bool True on success, false on failure.
     */
    protected function delete_option( string $key ): bool {
        if ( isset( $this->options[ $key ] ) ) {
            unset( $this->options[ $key ] );
            return $this->save_options();
        }
        return true;
    }

    /**
     * Get the service slug.
     *
     * @return string The service slug.
     */
    public function get_slug(): string {
        return sanitize_title( $this->get_name() );
    }

    /**
     * Check if service is enabled.
     *
     * @return bool True if enabled, false otherwise.
     */
    public function is_enabled(): bool {
        return (bool) $this->get_option( 'enabled', true );
    }

    /**
     * Enable the service.
     *
     * @return bool True on success, false on failure.
     */
    public function enable(): bool {
        return $this->update_option( 'enabled', true );
    }

    /**
     * Disable the service.
     *
     * @return bool True on success, false on failure.
     */
    public function disable(): bool {
        return $this->update_option( 'enabled', false );
    }

    /**
     * Service-specific setup.
     * Override this method in child classes.
     *
     * @return void
     */
    protected function setup(): void {
        // Override in child classes
    }

    /**
     * Service-specific cleanup.
     * Override this method in child classes.
     *
     * @return void
     */
    protected function cleanup(): void {
        // Override in child classes
    }

    /**
     * Validate option value.
     *
     * @param string $key The option key.
     * @param mixed  $value The option value.
     * @return mixed The validated value.
     */
    protected function validate_option( string $key, $value ) {
        // Override in child classes for custom validation
        return $value;
    }

    /**
     * Get default options.
     *
     * @return array The default options.
     */
    protected function get_default_options(): array {
        return array(
            'enabled' => true,
        );
    }

    /**
     * Reset options to defaults.
     *
     * @return bool True on success, false on failure.
     */
    public function reset_options(): bool {
        $this->options = $this->get_default_options();
        return $this->save_options();
    }

    /**
     * Get all options.
     *
     * @return array The options.
     */
    public function get_options(): array {
        return $this->options;
    }

    /**
     * Set all options.
     *
     * @param array $options The options.
     * @return bool True on success, false on failure.
     */
    public function set_options( array $options ): bool {
        // Validate each option
        foreach ( $options as $key => $value ) {
            $options[ $key ] = $this->validate_option( $key, $value );
        }

        $this->options = $options;
        return $this->save_options();
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

    /**
     * Handle errors gracefully.
     *
     * @param string $message The error message.
     * @param \Exception $exception The exception (optional).
     * @return void
     */
    protected function handle_error( string $message, \Exception $exception = null ): void {
        $this->log( $message, 'error' );
        
        if ( $exception ) {
            $this->log( $exception->getMessage(), 'error' );
            
            if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
                $this->log( $exception->getTraceAsString(), 'debug' );
            }
        }
    }

    /**
     * Prevent cloning.
     */
    private function __clone() {}

    /**
     * Prevent unserialization.
     */
    public function __wakeup() {
        throw new \Exception( 'Cannot unserialize singleton' );
    }
}