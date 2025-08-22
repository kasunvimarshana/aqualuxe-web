<?php
/**
 * AquaLuxe Module Base Class
 *
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * AquaLuxe Module Base Class
 */
abstract class AquaLuxe_Module {
    /**
     * Module ID
     *
     * @var string
     */
    protected $id = '';

    /**
     * Module name
     *
     * @var string
     */
    protected $name = '';

    /**
     * Module description
     *
     * @var string
     */
    protected $description = '';

    /**
     * Module version
     *
     * @var string
     */
    protected $version = '1.0.0';

    /**
     * Module dependencies
     *
     * @var array
     */
    protected $dependencies = [];

    /**
     * Module settings
     *
     * @var array
     */
    protected $settings = [];

    /**
     * Module directory
     *
     * @var string
     */
    protected $dir = '';

    /**
     * Module URI
     *
     * @var string
     */
    protected $uri = '';

    /**
     * Constructor
     */
    public function __construct() {
        // Set module directory and URI
        $this->dir = AQUALUXE_MODULES_DIR . $this->id . '/';
        $this->uri = AQUALUXE_URI . 'modules/' . $this->id . '/';

        // Load module settings
        $this->load_settings();
    }

    /**
     * Initialize module
     */
    public function init() {
        // Register assets
        add_action('wp_enqueue_scripts', [$this, 'register_assets']);
        
        // Register admin assets
        add_action('admin_enqueue_scripts', [$this, 'register_admin_assets']);
        
        // Register customizer settings
        add_action('customize_register', [$this, 'register_customizer_settings']);
    }

    /**
     * Check if module is active
     *
     * @return bool
     */
    public function is_active() {
        // Check if module is enabled in settings
        $enabled = $this->get_setting('enabled', true);
        
        // Check if dependencies are met
        if ($enabled && !empty($this->dependencies)) {
            foreach ($this->dependencies as $dependency) {
                if (!aqualuxe()->is_module_active($dependency)) {
                    return false;
                }
            }
        }
        
        return $enabled;
    }

    /**
     * Load module settings
     */
    protected function load_settings() {
        // Get settings from options
        $settings = get_option('aqualuxe_module_' . $this->id, []);
        
        // Merge with default settings
        $this->settings = wp_parse_args($settings, $this->get_default_settings());
    }

    /**
     * Get default settings
     *
     * @return array
     */
    protected function get_default_settings() {
        return [
            'enabled' => true,
        ];
    }

    /**
     * Get setting
     *
     * @param string $key Setting key
     * @param mixed $default Default value
     * @return mixed
     */
    public function get_setting($key, $default = null) {
        return isset($this->settings[$key]) ? $this->settings[$key] : $default;
    }

    /**
     * Update setting
     *
     * @param string $key Setting key
     * @param mixed $value Setting value
     */
    public function update_setting($key, $value) {
        $this->settings[$key] = $value;
        
        // Save settings
        update_option('aqualuxe_module_' . $this->id, $this->settings);
    }

    /**
     * Register assets
     */
    public function register_assets() {
        // Override in child class
    }

    /**
     * Register admin assets
     */
    public function register_admin_assets() {
        // Override in child class
    }

    /**
     * Register customizer settings
     *
     * @param WP_Customize_Manager $wp_customize Customizer manager
     */
    public function register_customizer_settings($wp_customize) {
        // Override in child class
    }

    /**
     * Get module ID
     *
     * @return string
     */
    public function get_id() {
        return $this->id;
    }

    /**
     * Get module name
     *
     * @return string
     */
    public function get_name() {
        return $this->name;
    }

    /**
     * Get module description
     *
     * @return string
     */
    public function get_description() {
        return $this->description;
    }

    /**
     * Get module version
     *
     * @return string
     */
    public function get_version() {
        return $this->version;
    }

    /**
     * Get module directory
     *
     * @return string
     */
    public function get_dir() {
        return $this->dir;
    }

    /**
     * Get module URI
     *
     * @return string
     */
    public function get_uri() {
        return $this->uri;
    }
}