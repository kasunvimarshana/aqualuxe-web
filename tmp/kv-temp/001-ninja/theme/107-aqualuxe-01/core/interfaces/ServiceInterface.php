<?php
/**
 * Service Interface
 *
 * Defines the contract that all theme services must implement
 *
 * @package AquaLuxe\Core\Interfaces
 * @version 1.0.0
 */

namespace AquaLuxe\Core\Interfaces;

/**
 * Service Interface
 */
interface ServiceInterface {
    
    /**
     * Initialize the service
     *
     * @return void
     */
    public function init(): void;
    
    /**
     * Get service name
     *
     * @return string
     */
    public function getName(): string;
}