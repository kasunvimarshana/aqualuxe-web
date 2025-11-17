<?php
/**
 * Module Interface
 *
 * Defines the contract that all theme modules must implement
 *
 * @package AquaLuxe\Core\Interfaces
 * @version 1.0.0
 */

namespace AquaLuxe\Core\Interfaces;

/**
 * Module Interface
 */
interface ModuleInterface {
    
    /**
     * Initialize the module
     *
     * @return void
     */
    public function init(): void;
    
    /**
     * Get module name
     *
     * @return string
     */
    public function getName(): string;
    
    /**
     * Get module version
     *
     * @return string
     */
    public function getVersion(): string;
    
    /**
     * Check if module is enabled
     *
     * @return bool
     */
    public function isEnabled(): bool;
    
    /**
     * Enable the module
     *
     * @return void
     */
    public function enable(): void;
    
    /**
     * Disable the module
     *
     * @return void
     */
    public function disable(): void;
    
    /**
     * Get module dependencies
     *
     * @return array
     */
    public function getDependencies(): array;
}