<?php
/**
 * Abstract Module Base Class
 *
 * Base class for all AquaLuxe theme modules following SOLID principles
 *
 * @package AquaLuxe\Core
 * @since 1.0.0
 */

namespace AquaLuxe\Core;

/**
 * Abstract class AbstractModule
 *
 * Provides a consistent interface for all theme modules
 */
abstract class AbstractModule {
    
    /**
     * Module ID
     *
     * @var string
     */
    protected $module_id;

    /**
     * Module configuration
     *
     * @var array
     */
    protected $config;

    /**
     * Module assets
     *
     * @var array
     */
    protected $assets = array();

    /**
     * Constructor
     *
     * @param string $module_id Module identifier
     * @param array $config Module configuration
     */
    public function __construct($module_id, $config = array()) {
        $this->module_id = $module_id;
        $this->config = wp_parse_args($config, $this->get_default_config());
        
        // Initialize the module
        $this->init();
        
        // Setup hooks
        $this->setup_hooks();
        
        // Enqueue assets if enabled
        if ($this->should_load_assets()) {
            add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
            add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
        }
    }

    /**
     * Get module ID
     *
     * @return string
     */
    public function get_id() {
        return $this->module_id;
    }

    /**
     * Get module configuration
     *
     * @return array
     */
    public function get_config() {
        return $this->config;
    }

    /**
     * Check if module is enabled
     *
     * @return bool
     */
    public function is_enabled() {
        return isset($this->config['enabled']) ? (bool) $this->config['enabled'] : true;
    }

    /**
     * Get module dependencies
     *
     * @return array
     */
    public function get_dependencies() {
        return isset($this->config['dependencies']) ? $this->config['dependencies'] : array();
    }

    /**
     * Initialize the module
     *
     * Override this method in child classes for custom initialization
     */
    protected function init() {
        // Default implementation - override in child classes
    }

    /**
     * Setup WordPress hooks
     *
     * Override this method in child classes to register hooks
     */
    protected function setup_hooks() {
        // Default implementation - override in child classes
    }

    /**
     * Get default module configuration
     *
     * Override this method in child classes to provide default config
     *
     * @return array
     */
    protected function get_default_config() {
        return array(
            'name' => $this->module_id,
            'description' => '',
            'version' => '1.0.0',
            'enabled' => true,
            'dependencies' => array(),
            'assets' => array(),
            'admin_assets' => array(),
        );
    }

    /**
     * Check if assets should be loaded
     *
     * @return bool
     */
    protected function should_load_assets() {
        return $this->is_enabled() && (!empty($this->config['assets']) || !empty($this->config['admin_assets']));
    }

    /**
     * Enqueue frontend assets
     */
    public function enqueue_assets() {
        if (!$this->is_enabled()) {
            return;
        }

        $assets = $this->config['assets'] ?? array();
        
        foreach ($assets as $asset) {
            $this->enqueue_asset($asset);
        }
    }

    /**
     * Enqueue admin assets
     */
    public function enqueue_admin_assets() {
        if (!$this->is_enabled()) {
            return;
        }

        $admin_assets = $this->config['admin_assets'] ?? array();
        
        foreach ($admin_assets as $asset) {
            $this->enqueue_asset($asset);
        }
    }

    /**
     * Enqueue a single asset
     *
     * @param array $asset Asset configuration
     */
    protected function enqueue_asset($asset) {
        $type = $asset['type'] ?? 'script';
        $handle = $asset['handle'] ?? $this->module_id . '-' . $type;
        $file = $asset['file'] ?? '';
        $deps = $asset['deps'] ?? array();
        $version = $asset['version'] ?? AQUALUXE_VERSION;
        
        if (empty($file)) {
            return;
        }

        if ($type === 'style') {
            AssetManager::enqueue_style($handle, $file, $deps, $version);
        } else {
            AssetManager::enqueue_script($handle, $file, $deps, $version);
            
            // Localize script if localization data is provided
            if (isset($asset['localize']) && is_array($asset['localize'])) {
                wp_localize_script($handle, $asset['localize']['object_name'], $asset['localize']['data']);
            }
        }
    }

    /**
     * Register secure AJAX handler
     *
     * @param string $action AJAX action name
     * @param callable $callback Callback function
     * @param bool $require_auth Whether authentication is required
     * @param bool $rate_limit Whether to apply rate limiting
     */
    protected function register_ajax_handler($action, $callback, $require_auth = true, $rate_limit = true) {
        if (function_exists('aqualuxe_secure_ajax_handler')) {
            aqualuxe_secure_ajax_handler($action, $callback, $require_auth, $rate_limit);
        }
    }

    /**
     * Log module-specific events
     *
     * @param string $event Event name
     * @param array $details Event details
     */
    protected function log_event($event, $details = array()) {
        if (function_exists('aqualuxe_log_security_event')) {
            $details['module'] = $this->module_id;
            aqualuxe_log_security_event($event, $details);
        }
    }

    /**
     * Get module option
     *
     * @param string $option Option name
     * @param mixed $default Default value
     * @return mixed
     */
    protected function get_option($option, $default = null) {
        return get_option($this->module_id . '_' . $option, $default);
    }

    /**
     * Update module option
     *
     * @param string $option Option name
     * @param mixed $value Option value
     * @return bool
     */
    protected function update_option($option, $value) {
        return update_option($this->module_id . '_' . $option, $value);
    }

    /**
     * Delete module option
     *
     * @param string $option Option name
     * @return bool
     */
    protected function delete_option($option) {
        return delete_option($this->module_id . '_' . $option);
    }

    /**
     * Check if current context matches condition
     *
     * @param callable|string $condition Condition to check
     * @return bool
     */
    protected function check_condition($condition) {
        if (is_callable($condition)) {
            return call_user_func($condition);
        }

        if (is_string($condition)) {
            switch ($condition) {
                case 'is_admin':
                    return is_admin();
                case 'is_frontend':
                    return !is_admin();
                case 'is_woocommerce':
                    return class_exists('WooCommerce') && (is_woocommerce() || is_cart() || is_checkout());
                case 'is_shop':
                    return function_exists('is_shop') && is_shop();
                case 'is_product':
                    return function_exists('is_product') && is_product();
                case 'is_single':
                    return is_single();
                case 'is_page':
                    return is_page();
                case 'is_home':
                    return is_home();
                case 'is_front_page':
                    return is_front_page();
                default:
                    return false;
            }
        }

        return false;
    }

    /**
     * Abstract method to be implemented by child classes
     * for module-specific functionality
     */
    abstract public function run();

    /**
     * Activate the module
     */
    public function activate() {
        $this->config['enabled'] = true;
        $this->update_module_config();
        do_action('aqualuxe_module_activated', $this->module_id);
    }

    /**
     * Deactivate the module
     */
    public function deactivate() {
        $this->config['enabled'] = false;
        $this->update_module_config();
        do_action('aqualuxe_module_deactivated', $this->module_id);
    }

    /**
     * Update module configuration in database
     */
    private function update_module_config() {
        $enabled_modules = get_option('aqualuxe_enabled_modules', array());
        $enabled_modules[$this->module_id] = $this->config['enabled'];
        update_option('aqualuxe_enabled_modules', $enabled_modules);
    }
}