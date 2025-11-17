<?php
/**
 * Base Module Class
 *
 * Abstract base class providing common functionality for all modules
 *
 * @package AquaLuxe\Core
 * @since 1.0.0
 */

namespace AquaLuxe\Core;

use AquaLuxe\Core\ModuleInterface;

/**
 * Abstract Class BaseModule
 *
 * Provides common functionality and structure for all modules
 */
abstract class BaseModule implements ModuleInterface {
    
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
     * Module configuration
     *
     * @var array
     */
    protected $config = array();

    /**
     * Module dependencies
     *
     * @var array
     */
    protected $dependencies = array();

    /**
     * Whether the module is enabled
     *
     * @var bool
     */
    protected $enabled = true;

    /**
     * Module assets
     *
     * @var array
     */
    protected $assets = array();

    /**
     * Admin settings
     *
     * @var array
     */
    protected $admin_settings = array();

    /**
     * Whether the module has been initialized
     *
     * @var bool
     */
    protected $initialized = false;

    /**
     * Constructor
     */
    public function __construct() {
        $this->set_properties();
        $this->load_config();
        
        if ($this->is_enabled() && $this->can_load()) {
            add_action('init', array($this, 'init'), 10);
        }
    }

    /**
     * Set module properties (to be overridden by child classes)
     */
    abstract protected function set_properties();

    /**
     * Initialize module hooks (to be overridden by child classes)
     */
    abstract protected function init_hooks();

    /**
     * Get the module name
     *
     * @return string
     */
    public function get_name() {
        return $this->name;
    }

    /**
     * Get the module version
     *
     * @return string
     */
    public function get_version() {
        return $this->version;
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
     * Check if the module is enabled
     *
     * @return bool
     */
    public function is_enabled() {
        // Check if globally disabled
        if (!$this->enabled) {
            return false;
        }

        // Check theme option
        $enabled_modules = get_theme_mod('aqualuxe_enabled_modules', array());
        
        // If no specific config, default to enabled
        if (empty($enabled_modules)) {
            return true;
        }

        return in_array($this->name, $enabled_modules, true);
    }

    /**
     * Initialize the module
     */
    public function init() {
        if ($this->initialized) {
            return;
        }

        $this->init_hooks();
        $this->register_assets();
        $this->register_admin_settings();
        
        $this->initialized = true;

        // Fire action for other plugins/themes to hook into
        do_action('aqualuxe_module_initialized', $this->name, $this);
    }

    /**
     * Get module dependencies
     *
     * @return array
     */
    public function get_dependencies() {
        return $this->dependencies;
    }

    /**
     * Check if module can be loaded (dependencies satisfied)
     *
     * @return bool
     */
    public function can_load() {
        // Check PHP version requirements
        if (isset($this->config['min_php_version'])) {
            if (version_compare(PHP_VERSION, $this->config['min_php_version'], '<')) {
                return false;
            }
        }

        // Check WordPress version requirements
        if (isset($this->config['min_wp_version'])) {
            if (version_compare(get_bloginfo('version'), $this->config['min_wp_version'], '<')) {
                return false;
            }
        }

        // Check plugin dependencies
        if (!empty($this->dependencies)) {
            foreach ($this->dependencies as $dependency) {
                if ($dependency === 'woocommerce' && !class_exists('WooCommerce')) {
                    return false;
                }
                // Add more dependency checks as needed
            }
        }

        return true;
    }

    /**
     * Activate the module
     *
     * @return bool
     */
    public function activate() {
        $enabled_modules = get_theme_mod('aqualuxe_enabled_modules', array());
        
        if (!in_array($this->name, $enabled_modules, true)) {
            $enabled_modules[] = $this->name;
            set_theme_mod('aqualuxe_enabled_modules', $enabled_modules);
        }

        // Run activation hooks
        do_action('aqualuxe_module_activated', $this->name, $this);

        return true;
    }

    /**
     * Deactivate the module
     *
     * @return bool
     */
    public function deactivate() {
        $enabled_modules = get_theme_mod('aqualuxe_enabled_modules', array());
        $key = array_search($this->name, $enabled_modules, true);
        
        if ($key !== false) {
            unset($enabled_modules[$key]);
            set_theme_mod('aqualuxe_enabled_modules', array_values($enabled_modules));
        }

        // Run deactivation hooks
        do_action('aqualuxe_module_deactivated', $this->name, $this);

        return true;
    }

    /**
     * Get module assets
     *
     * @return array
     */
    public function get_assets() {
        return $this->assets;
    }

    /**
     * Get module admin settings
     *
     * @return array
     */
    public function get_admin_settings() {
        return $this->admin_settings;
    }

    /**
     * Load module configuration
     */
    protected function load_config() {
        // Allow filtering of config
        $this->config = apply_filters("aqualuxe_module_config_{$this->name}", $this->config);
    }

    /**
     * Register module assets
     */
    protected function register_assets() {
        if (empty($this->assets)) {
            return;
        }

        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_assets'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
    }

    /**
     * Enqueue frontend assets
     */
    public function enqueue_frontend_assets() {
        if (!$this->should_load_assets()) {
            return;
        }

        foreach ($this->assets as $type => $files) {
            if ($type === 'css') {
                foreach ($files as $handle => $file) {
                    if (is_array($file)) {
                        AssetManager::enqueue_style(
                            $handle,
                            $file['src'],
                            $file['deps'] ?? array(),
                            $file['version'] ?? $this->version,
                            $file['media'] ?? 'all'
                        );
                    } else {
                        AssetManager::enqueue_style("{$this->name}-{$handle}", $file);
                    }
                }
            } elseif ($type === 'js') {
                foreach ($files as $handle => $file) {
                    if (is_array($file)) {
                        AssetManager::enqueue_script(
                            $handle,
                            $file['src'],
                            $file['deps'] ?? array('jquery'),
                            $file['version'] ?? $this->version,
                            $file['in_footer'] ?? true
                        );
                    } else {
                        AssetManager::enqueue_script("{$this->name}-{$handle}", $file, array('jquery'));
                    }
                }
            }
        }
    }

    /**
     * Enqueue admin assets
     */
    public function enqueue_admin_assets() {
        // Override in child classes if admin assets are needed
    }

    /**
     * Check if assets should be loaded on current page
     *
     * @return bool
     */
    protected function should_load_assets() {
        // Default: load on all frontend pages
        // Override in child classes for conditional loading
        return !is_admin();
    }

    /**
     * Register admin settings
     */
    protected function register_admin_settings() {
        if (empty($this->admin_settings)) {
            return;
        }

        // Add to WordPress Customizer
        add_action('customize_register', array($this, 'customize_register'));
    }

    /**
     * Add settings to WordPress Customizer
     *
     * @param WP_Customize_Manager $wp_customize
     */
    public function customize_register($wp_customize) {
        // Create section for this module
        $wp_customize->add_section("aqualuxe_module_{$this->name}", array(
            'title'    => sprintf(__('%s Settings', 'aqualuxe'), ucfirst($this->name)),
            'priority' => 200,
        ));

        foreach ($this->admin_settings as $setting_id => $setting) {
            $setting_name = "aqualuxe_{$this->name}_{$setting_id}";
            
            // Add setting
            $wp_customize->add_setting($setting_name, array(
                'default'           => $setting['default'] ?? '',
                'sanitize_callback' => $setting['sanitize'] ?? 'sanitize_text_field',
                'transport'         => $setting['transport'] ?? 'refresh',
            ));

            // Add control
            $control_args = array(
                'label'       => $setting['label'] ?? ucfirst($setting_id),
                'section'     => "aqualuxe_module_{$this->name}",
                'description' => $setting['description'] ?? '',
            );

            if (isset($setting['type'])) {
                $control_args['type'] = $setting['type'];
            }

            if (isset($setting['choices'])) {
                $control_args['choices'] = $setting['choices'];
            }

            $wp_customize->add_control($setting_name, $control_args);
        }
    }

    /**
     * Get a specific module setting
     *
     * @param string $setting_id
     * @param mixed $default
     * @return mixed
     */
    protected function get_setting($setting_id, $default = null) {
        $setting_name = "aqualuxe_{$this->name}_{$setting_id}";
        return get_theme_mod($setting_name, $default);
    }

    /**
     * Create a secure AJAX handler
     *
     * @param string $action
     * @param callable $callback
     * @param bool $logged_in_only
     * @param bool $public_access
     */
    protected function register_ajax_handler($action, $callback, $logged_in_only = false, $public_access = true) {
        $action_name = "aqualuxe_{$this->name}_{$action}";
        
        if ($logged_in_only) {
            add_action("wp_ajax_{$action_name}", $callback);
        } else {
            add_action("wp_ajax_{$action_name}", $callback);
            
            if ($public_access) {
                add_action("wp_ajax_nopriv_{$action_name}", $callback);
            }
        }
    }

    /**
     * Verify AJAX request security
     *
     * @param string $action
     * @return bool
     */
    protected function verify_ajax_request($action = '') {
        $action_name = $action ?: "aqualuxe_{$this->name}_ajax";
        
        if (!isset($_POST['nonce'])) {
            return false;
        }

        return aqualuxe_verify_ajax_nonce($_POST['nonce'], $action_name);
    }

    /**
     * Send JSON response
     *
     * @param mixed $data
     * @param bool $success
     * @param int $status_code
     */
    protected function json_response($data, $success = true, $status_code = 200) {
        if ($success) {
            wp_send_json_success($data, $status_code);
        } else {
            wp_send_json_error($data, $status_code);
        }
    }

    /**
     * Log module messages
     *
     * @param string $message
     * @param string $level
     */
    protected function log($message, $level = 'info') {
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log("AquaLuxe Module [{$this->name}] [{$level}]: {$message}");
        }
    }
}