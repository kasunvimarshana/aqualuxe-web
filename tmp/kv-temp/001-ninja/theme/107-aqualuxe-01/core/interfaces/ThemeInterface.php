<?php
/**
 * Theme Interface
 *
 * Defines the contract that the main theme class must implement
 *
 * @package AquaLuxe\Core\Interfaces
 * @version 1.0.0
 */

namespace AquaLuxe\Core\Interfaces;

/**
 * Theme Interface
 */
interface ThemeInterface {
    
    /**
     * Initialize the theme
     *
     * @return void
     */
    public function init(): void;
    
    /**
     * Get theme instance
     *
     * @return mixed
     */
    public static function getInstance();
    
    /**
     * Check if theme is initialized
     *
     * @return bool
     */
    public function isInitialized(): bool;
}