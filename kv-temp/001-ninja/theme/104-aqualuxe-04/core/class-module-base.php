<?php
/**
 * Module Base Class
 * 
 * Provides common functionality for all theme modules
 * 
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit;
}

abstract class AquaLuxe_Module_Base {
    
    /**
     * Module name/slug
     */
    protected $module_name;
    
    /**
     * Module version
     */
    protected $version = AQUALUXE_VERSION;
    
    /**
     * Whether module assets are enqueued
     */
    private $assets_enqueued = false;
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->module_name = $this->get_module_name();
        $this->init_hooks();
    }
    
    /**
     * Get module name from class name
     */
    private function get_module_name() {
        $class_name = get_class($this);
        $name = str_replace(['AquaLuxe_', '_Module'], '', $class_name);
        return strtolower(str_replace('_', '-', $name));
    }
    
    /**
     * Initialize hooks
     */
    protected function init_hooks() {
        add_action('init', [$this, 'init']);
        add_action('wp_enqueue_scripts', [$this, 'conditional_enqueue_assets']);
        
        // Only add admin hooks if module has admin functionality
        if ($this->has_admin_interface()) {
            add_action('admin_menu', [$this, 'add_admin_menu']);
            add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);
        }
    }
    
    /**
     * Initialize module (must be implemented by child classes)
     */
    abstract public function init();
    
    /**
     * Check if module has admin interface
     */
    protected function has_admin_interface() {
        return method_exists($this, 'add_admin_menu');
    }
    
    /**
     * Conditionally enqueue assets based on context
     */
    public function conditional_enqueue_assets() {
        if (!$this->should_load_assets()) {
            return;
        }
        
        if (!$this->assets_enqueued) {
            $this->enqueue_module_assets();
            $this->assets_enqueued = true;
        }
    }
    
    /**
     * Check if module assets should be loaded
     */
    protected function should_load_assets() {
        // Override in child classes for specific conditions
        return true;
    }
    
    /**
     * Enqueue module-specific assets
     */
    protected function enqueue_module_assets() {
        // Add module variables to main script
        $this->add_module_script_vars();
        
        // Add module-specific inline styles
        $this->add_module_inline_styles();
    }
    
    /**
     * Add module variables to existing script
     */
    protected function add_module_script_vars() {
        $script_vars = $this->get_script_vars();
        if (!empty($script_vars)) {
            $var_name = 'aqualuxe_' . str_replace('-', '_', $this->module_name) . '_vars';
            wp_localize_script('aqualuxe-script', $var_name, $script_vars);
        }
    }
    
    /**
     * Get script variables for module
     */
    protected function get_script_vars() {
        return [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe_' . $this->module_name . '_nonce'),
            'module_name' => $this->module_name,
        ];
    }
    
    /**
     * Add module-specific inline styles
     */
    protected function add_module_inline_styles() {
        $custom_css = $this->get_inline_styles();
        if (!empty($custom_css)) {
            wp_add_inline_style('aqualuxe-style', $custom_css);
        }
    }
    
    /**
     * Get inline styles for module
     */
    protected function get_inline_styles() {
        return '';
    }
    
    /**
     * Enqueue admin assets (override in child classes if needed)
     */
    public function enqueue_admin_assets($hook) {
        // Override in child classes
    }
    
    /**
     * Check if current page has module shortcode
     */
    protected function has_module_shortcode($shortcodes = []) {
        global $post;
        
        if (!$post || empty($shortcodes)) {
            return false;
        }
        
        foreach ($shortcodes as $shortcode) {
            if (has_shortcode($post->post_content, $shortcode)) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Sanitize and validate input
     */
    protected function sanitize_input($input, $type = 'text') {
        switch ($type) {
            case 'email':
                return sanitize_email($input);
            case 'textarea':
                return sanitize_textarea_field($input);
            case 'url':
                return esc_url_raw($input);
            case 'int':
                return intval($input);
            case 'float':
                return floatval($input);
            case 'bool':
                return boolval($input);
            default:
                return sanitize_text_field($input);
        }
    }
    
    /**
     * Verify nonce for AJAX requests
     */
    protected function verify_ajax_nonce($action = null) {
        $nonce_action = $action ?: 'aqualuxe_' . $this->module_name . '_nonce';
        
        if (!wp_verify_nonce($_POST['nonce'] ?? '', $nonce_action)) {
            wp_send_json_error('Security check failed.');
        }
    }
    
    /**
     * Get module option
     */
    protected function get_option($option, $default = '') {
        return get_option('aqualuxe_' . $this->module_name . '_' . $option, $default);
    }
    
    /**
     * Update module option
     */
    protected function update_option($option, $value) {
        return update_option('aqualuxe_' . $this->module_name . '_' . $option, $value);
    }
    
    /**
     * Delete module option
     */
    protected function delete_option($option) {
        return delete_option('aqualuxe_' . $this->module_name . '_' . $option);
    }
    
    /**
     * Log module error
     */
    protected function log_error($message, $data = []) {
        if (WP_DEBUG && WP_DEBUG_LOG) {
            error_log(sprintf(
                '[AquaLuxe %s Module] %s: %s',
                ucfirst($this->module_name),
                $message,
                !empty($data) ? print_r($data, true) : ''
            ));
        }
    }
}