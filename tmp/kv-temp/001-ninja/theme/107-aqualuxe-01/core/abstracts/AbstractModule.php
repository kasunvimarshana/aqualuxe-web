<?php
/**
 * Abstract Base Module
 *
 * Base class that all modules should extend
 *
 * @package AquaLuxe\Core\Abstracts
 * @version 1.0.0
 */

namespace AquaLuxe\Core\Abstracts;

use AquaLuxe\Core\Interfaces\ModuleInterface;

/**
 * Abstract Module Class
 */
abstract class AbstractModule implements ModuleInterface {
    
    /**
     * Module name
     *
     * @var string
     */
    protected $name;
    
    /**
     * Module version
     *
     * @var string
     */
    protected $version = '1.0.0';
    
    /**
     * Module enabled status
     *
     * @var bool
     */
    protected $enabled = false;
    
    /**
     * Module dependencies
     *
     * @var array
     */
    protected $dependencies = [];
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->name = $this->get_module_name();
        $this->enabled = $this->is_module_enabled();
    }
    
    /**
     * Get module name from class name
     *
     * @return string
     */
    protected function get_module_name(): string {
        $class_name = get_class($this);
        $parts = explode('\\', $class_name);
        $module_name = end($parts);
        
        // Convert PascalCase to kebab-case
        return strtolower(preg_replace('/([a-z])([A-Z])/', '$1-$2', $module_name));
    }
    
    /**
     * Check if module is enabled
     *
     * @return bool
     */
    protected function is_module_enabled(): bool {
        return get_theme_mod('module_' . str_replace('-', '_', $this->name) . '_enabled', false);
    }
    
    /**
     * Get module name
     *
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }
    
    /**
     * Get module version
     *
     * @return string
     */
    public function getVersion(): string {
        return $this->version;
    }
    
    /**
     * Check if module is enabled
     *
     * @return bool
     */
    public function isEnabled(): bool {
        return $this->enabled;
    }
    
    /**
     * Enable the module
     *
     * @return void
     */
    public function enable(): void {
        $this->enabled = true;
        set_theme_mod('module_' . str_replace('-', '_', $this->name) . '_enabled', true);
    }
    
    /**
     * Disable the module
     *
     * @return void
     */
    public function disable(): void {
        $this->enabled = false;
        set_theme_mod('module_' . str_replace('-', '_', $this->name) . '_enabled', false);
    }
    
    /**
     * Get module dependencies
     *
     * @return array
     */
    public function getDependencies(): array {
        return $this->dependencies;
    }
    
    /**
     * Register module hooks
     *
     * Override this method in child classes to register hooks
     *
     * @return void
     */
    protected function register_hooks(): void {
        // To be implemented by child classes
    }
    
    /**
     * Module activation hook
     *
     * Override this method in child classes for activation logic
     *
     * @return void
     */
    protected function on_activate(): void {
        // To be implemented by child classes
    }
    
    /**
     * Module deactivation hook
     *
     * Override this method in child classes for deactivation logic
     *
     * @return void
     */
    protected function on_deactivate(): void {
        // To be implemented by child classes
    }
}