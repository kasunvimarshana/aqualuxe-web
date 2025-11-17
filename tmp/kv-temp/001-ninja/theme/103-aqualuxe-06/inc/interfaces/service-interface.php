<?php
/**
 * Service Interface
 *
 * Defines the contract for all services in the AquaLuxe theme.
 * This interface ensures consistent service behavior.
 *
 * @package AquaLuxe
 * @subpackage Core\Interfaces
 * @since 1.0.0
 * @author AquaLuxe Development Team
 */

namespace AquaLuxe\Core\Interfaces;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Service Interface
 *
 * Provides a contract for implementing services within the AquaLuxe theme.
 * Services are singleton classes that provide specific functionality.
 *
 * @since 1.0.0
 */
interface Service_Interface {

    /**
     * Get the service instance.
     *
     * @since 1.0.0
     * @return static The service instance.
     */
    public static function get_instance();

    /**
     * Initialize the service.
     *
     * @since 1.0.0
     * @return void
     */
    public function init(): void;

    /**
     * Get the service name.
     *
     * @since 1.0.0
     * @return string The service name.
     */
    public function get_name(): string;
}