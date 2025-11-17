<?php
/**
 * Centralized Module Script Manager
 *
 * Manages module script loading efficiently to prevent JavaScript conflicts
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class AquaLuxe_Module_Script_Manager
 *
 * Centralized management of module scripts
 */
class AquaLuxe_Module_Script_Manager {
    
    /**
     * Single instance
     *
     * @var AquaLuxe_Module_Script_Manager
     */
    private static $instance = null;

    /**
     * Loaded modules
     *
     * @var array
     */
    private $loaded_modules = array();

    /**
     * Get instance
     *
     * @return AquaLuxe_Module_Script_Manager
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
        add_action('wp_enqueue_scripts', array($this, 'enqueue_module_scripts'), 25);
    }

    /**
     * Register a module for conditional loading
     *
     * @param string $module Module name
     * @param callable $condition Condition function
     * @param array $config Module configuration
     */
    public function register_module($module, $condition, $config) {
        $this->loaded_modules[$module] = array(
            'condition' => $condition,
            'config' => $config
        );
    }

    /**
     * Enqueue module scripts based on conditions
     */
    public function enqueue_module_scripts() {
        $active_modules = array();
        
        foreach ($this->loaded_modules as $module => $data) {
            if (is_callable($data['condition']) && call_user_func($data['condition'])) {
                $active_modules[$module] = $data['config'];
                
                // Enqueue module script if it exists
                $script_path = "js/modules/{$module}.js";
                if (AssetManager::asset_exists($script_path)) {
                    AssetManager::enqueue_script(
                        "aqualuxe-{$module}",
                        $script_path,
                        array('jquery', 'aqualuxe-app'),
                        AQUALUXE_VERSION,
                        true
                    );
                }
            }
        }

        // Localize a single script with all active module data
        if (!empty($active_modules)) {
            wp_localize_script('aqualuxe-app', 'aqualuxe_modules', array(
                'ajax_url' => esc_url(admin_url('admin-ajax.php')),
                'nonce' => wp_create_nonce('aqualuxe_modules'),
                'active_modules' => array_keys($active_modules),
                'modules_config' => $this->optimize_modules_config($active_modules)
            ));
        }
    }

    /**
     * Optimize module configuration for JavaScript
     *
     * @param array $modules Modules configuration
     * @return array Optimized configuration
     */
    private function optimize_modules_config($modules) {
        $optimized = array();
        
        foreach ($modules as $module => $config) {
            $optimized[$module] = array(
                'enabled' => true,
                'ajax_action' => "aqualuxe_{$module}",
                'messages' => isset($config['messages']) ? $config['messages'] : array()
            );
            
            // Add only essential config data
            $essential_keys = array('current_currency', 'current_language', 'api_key');
            foreach ($essential_keys as $key) {
                if (isset($config[$key])) {
                    $optimized[$module][$key] = $config[$key];
                }
            }
        }
        
        return $optimized;
    }

    /**
     * Check if module is loaded
     *
     * @param string $module Module name
     * @return bool
     */
    public function is_module_loaded($module) {
        return isset($this->loaded_modules[$module]);
    }

    /**
     * Get loaded modules
     *
     * @return array
     */
    public function get_loaded_modules() {
        return array_keys($this->loaded_modules);
    }
}

/**
 * Initialize module script manager
 */
function aqualuxe_init_module_script_manager() {
    return AquaLuxe_Module_Script_Manager::get_instance();
}

/**
 * Register module with script manager
 *
 * @param string $module Module name
 * @param callable $condition Condition function
 * @param array $config Module configuration
 */
function aqualuxe_register_module_script($module, $condition, $config = array()) {
    $manager = aqualuxe_init_module_script_manager();
    $manager->register_module($module, $condition, $config);
}

// Initialize the manager
aqualuxe_init_module_script_manager();