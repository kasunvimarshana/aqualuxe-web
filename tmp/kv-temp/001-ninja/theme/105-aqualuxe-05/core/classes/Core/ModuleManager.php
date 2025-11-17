<?php
/**
 * Module Manager Class
 *
 * Handles loading and management of theme modules
 *
 * @package AquaLuxe\Core
 * @since 1.0.0
 */

namespace AquaLuxe\Core;

/**
 * Class ModuleManager
 *
 * Manages theme modules with dependency resolution
 */
class ModuleManager {
    
    /**
     * Single instance of the class
     *
     * @var ModuleManager
     */
    private static $instance = null;

    /**
     * Loaded modules
     *
     * @var array
     */
    private $modules = array();

    /**
     * Module configurations
     *
     * @var array
     */
    private $module_configs = array();

    /**
     * Get instance
     *
     * @return ModuleManager
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        $this->load_module_configs();
        $this->init_modules();
    }

    /**
     * Load module configurations
     */
    private function load_module_configs() {
        // Define all available modules with their configurations
        $this->module_configs = array(
            // Core modules
            'multilingual' => array(
                'name' => __('Multilingual Support', 'aqualuxe'),
                'description' => __('Provides multilingual functionality with language switching and RTL support', 'aqualuxe'),
                'enabled' => true,
                'dependencies' => array(),
                'file' => 'multilingual/module.php',
                'version' => '1.0.0',
                'category' => 'core',
            ),
            'dark_mode' => array(
                'name' => __('Dark Mode', 'aqualuxe'),
                'description' => __('Toggleable dark mode with persistent user preference and system detection', 'aqualuxe'),
                'enabled' => true,
                'dependencies' => array(),
                'file' => 'dark-mode/module.php',
                'version' => '1.0.0',
                'category' => 'ui',
            ),
            
            // Service modules
            'services' => array(
                'name' => __('Professional Services', 'aqualuxe'),
                'description' => __('Service management, booking, and consultation functionality', 'aqualuxe'),
                'enabled' => true,
                'dependencies' => array(),
                'file' => 'services/module.php',
                'version' => '1.0.0',
                'category' => 'business',
            ),
            'bookings' => array(
                'name' => __('Bookings & Scheduling', 'aqualuxe'),
                'description' => __('Advanced booking system with calendar integration and availability management', 'aqualuxe'),
                'enabled' => true,
                'dependencies' => array('services'),
                'file' => 'bookings/module.php',
                'version' => '1.0.0',
                'category' => 'business',
            ),
            'events' => array(
                'name' => __('Events & Ticketing', 'aqualuxe'),
                'description' => __('Event management, workshop booking, and ticketing system', 'aqualuxe'),
                'enabled' => true,
                'dependencies' => array('bookings'),
                'file' => 'events/module.php',
                'version' => '1.0.0',
                'category' => 'business',
            ),
            
            // Advanced business modules
            'wholesale' => array(
                'name' => __('Wholesale/B2B', 'aqualuxe'),
                'description' => __('Wholesale and B2B functionality for bulk orders and trade accounts', 'aqualuxe'),
                'enabled' => true,
                'dependencies' => array(),
                'file' => 'wholesale/module.php',
                'version' => '1.0.0',
                'category' => 'business',
            ),
            'franchise' => array(
                'name' => __('Franchise/Licensing', 'aqualuxe'),
                'description' => __('Franchise inquiries and partner portal functionality', 'aqualuxe'),
                'enabled' => true,
                'dependencies' => array(),
                'file' => 'franchise/module.php',
                'version' => '1.0.0',
                'category' => 'business',
            ),
            'auctions' => array(
                'name' => __('Auctions/Trade-ins', 'aqualuxe'),
                'description' => __('Auction and trade-in functionality for premium aquatic items', 'aqualuxe'),
                'enabled' => true,
                'dependencies' => array(),
                'file' => 'auctions/module.php',
                'version' => '1.0.0',
                'category' => 'business',
            ),
            'subscriptions' => array(
                'name' => __('Subscriptions/Memberships', 'aqualuxe'),
                'description' => __('Subscription and membership management system', 'aqualuxe'),
                'enabled' => true,
                'dependencies' => array(),
                'file' => 'subscriptions/module.php',
                'version' => '1.0.0',
                'category' => 'business',
            ),
            'affiliates' => array(
                'name' => __('Affiliate/Referrals', 'aqualuxe'),
                'description' => __('Affiliate program and referral system management', 'aqualuxe'),
                'enabled' => true,
                'dependencies' => array(),
                'file' => 'affiliates/module.php',
                'version' => '1.0.0',
                'category' => 'marketing',
            ),
            'sustainability' => array(
                'name' => __('R&D/Sustainability', 'aqualuxe'),
                'description' => __('Research, development, and sustainability initiatives tracking', 'aqualuxe'),
                'enabled' => true,
                'dependencies' => array(),
                'file' => 'sustainability/module.php',
                'version' => '1.0.0',
                'category' => 'content',
            ),
        );

        // Get enabled modules from options with fallback to defaults
        $saved_modules = get_option('aqualuxe_enabled_modules', array());
        
        // Apply enabled status from saved options
        foreach ($this->module_configs as $module_id => &$config) {
            if (isset($saved_modules[$module_id])) {
                $config['enabled'] = (bool) $saved_modules[$module_id];
            }
        }

        // Allow modules to be filtered by themes or plugins
        $this->module_configs = apply_filters('aqualuxe_module_configs', $this->module_configs);
        
        // Filter out modules that don't have files
        foreach ($this->module_configs as $module_id => $config) {
            $module_file = AQUALUXE_MODULES_DIR . '/' . $config['file'];
            if (!file_exists($module_file)) {
                unset($this->module_configs[$module_id]);
            }
        }
        
        // Validate and resolve dependencies
        $this->resolve_dependencies();
    }

    /**
     * Resolve module dependencies and determine load order
     */
    private function resolve_dependencies() {
        $resolved = array();
        $unresolved = array();
        
        foreach ($this->module_configs as $module_id => $config) {
            if ($config['enabled']) {
                $this->resolve_dependency($module_id, $resolved, $unresolved);
            }
        }
        
        // Update module configs with resolved order
        $ordered_configs = array();
        foreach ($resolved as $module_id) {
            if (isset($this->module_configs[$module_id])) {
                $ordered_configs[$module_id] = $this->module_configs[$module_id];
            }
        }
        $this->module_configs = $ordered_configs;
    }

    /**
     * Resolve dependencies for a specific module
     */
    private function resolve_dependency($module_id, &$resolved, &$unresolved) {
        $unresolved[] = $module_id;
        
        $config = $this->module_configs[$module_id] ?? array();
        $dependencies = $config['dependencies'] ?? array();
        
        foreach ($dependencies as $dependency) {
            if (!in_array($dependency, $resolved)) {
                if (in_array($dependency, $unresolved)) {
                    // Circular dependency detected
                    error_log("AquaLuxe: Circular dependency detected between {$module_id} and {$dependency}");
                    continue;
                }
                
                if (isset($this->module_configs[$dependency]) && $this->module_configs[$dependency]['enabled']) {
                    $this->resolve_dependency($dependency, $resolved, $unresolved);
                } else {
                    // Dependency not available or disabled
                    error_log("AquaLuxe: Module {$module_id} requires {$dependency} which is not available or disabled");
                    $this->module_configs[$module_id]['enabled'] = false;
                    return;
                }
            }
        }
        
        $resolved[] = $module_id;
        unset($unresolved[array_search($module_id, $unresolved)]);
    }

    /**
     * Initialize modules
     */
    private function init_modules() {
        // Load each module in dependency order
        foreach ($this->module_configs as $module_id => $config) {
            if ($config['enabled']) {
                $this->load_module($module_id);
            }
        }

        // Fire action after all modules are loaded
        do_action('aqualuxe_modules_loaded', $this->modules);
    }

    /**
     * Sort modules by dependencies
     *
     * @return array
     */
    private function sort_modules_by_dependencies() {
        $sorted = array();
        $visited = array();
        $visiting = array();

        foreach ($this->module_configs as $module_id => $config) {
            if (!$config['enabled']) {
                continue;
            }
            $this->visit_module($module_id, $sorted, $visited, $visiting);
        }

        return $sorted;
    }

    /**
     * Visit module for dependency sorting
     *
     * @param string $module_id Module ID
     * @param array $sorted Sorted modules array
     * @param array $visited Visited modules array
     * @param array $visiting Currently visiting modules array
     */
    private function visit_module($module_id, &$sorted, &$visited, &$visiting) {
        if (isset($visited[$module_id])) {
            return;
        }

        if (isset($visiting[$module_id])) {
            // Circular dependency detected
            return;
        }

        $visiting[$module_id] = true;

        $config = $this->module_configs[$module_id];
        if (isset($config['dependencies'])) {
            foreach ($config['dependencies'] as $dependency) {
                if (isset($this->module_configs[$dependency]) && $this->module_configs[$dependency]['enabled']) {
                    $this->visit_module($dependency, $sorted, $visited, $visiting);
                }
            }
        }

        unset($visiting[$module_id]);
        $visited[$module_id] = true;
        $sorted[] = $module_id;
    }

    /**
     * Load a module
     *
     * @param string $module_id Module ID
     * @return bool
     */
    private function load_module($module_id) {
        if (isset($this->modules[$module_id])) {
            return true;
        }

        if (!isset($this->module_configs[$module_id])) {
            return false;
        }

        $config = $this->module_configs[$module_id];
        
        if (!$config['enabled']) {
            return false;
        }

        $module_file = AQUALUXE_MODULES_DIR . '/' . $config['file'];
        
        if (!file_exists($module_file)) {
            return false;
        }

        // Load the module
        require_once $module_file;

        // Mark module as loaded
        $this->modules[$module_id] = $config;

        // Fire action for module loaded
        do_action('aqualuxe_module_loaded', $module_id, $config);

        return true;
    }

    /**
     * Check if module is loaded
     *
     * @param string $module_id Module ID
     * @return bool
     */
    public function is_module_loaded($module_id) {
        return isset($this->modules[$module_id]);
    }

    /**
     * Get loaded modules
     *
     * @return array
     */
    public function get_loaded_modules() {
        return $this->modules;
    }

    /**
     * Get module config
     *
     * @param string $module_id Module ID
     * @return array|null
     */
    public function get_module_config($module_id) {
        return isset($this->module_configs[$module_id]) ? $this->module_configs[$module_id] : null;
    }

    /**
     * Enable module
     *
     * @param string $module_id Module ID
     * @return bool
     */
    public function enable_module($module_id) {
        if (!isset($this->module_configs[$module_id])) {
            return false;
        }

        $this->module_configs[$module_id]['enabled'] = true;
        
        // Save to options
        $enabled_modules = get_option('aqualuxe_enabled_modules', array());
        $enabled_modules[$module_id] = true;
        update_option('aqualuxe_enabled_modules', $enabled_modules);

        return true;
    }

    /**
     * Disable module
     *
     * @param string $module_id Module ID
     * @return bool
     */
    public function disable_module($module_id) {
        if (!isset($this->module_configs[$module_id])) {
            return false;
        }

        $this->module_configs[$module_id]['enabled'] = false;
        
        // Remove from loaded modules
        unset($this->modules[$module_id]);
        
        // Save to options
        $enabled_modules = get_option('aqualuxe_enabled_modules', array());
        unset($enabled_modules[$module_id]);
        update_option('aqualuxe_enabled_modules', $enabled_modules);

        return true;
    }
}