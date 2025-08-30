<?php
/**
 * Demo Content Importer Plugins
 *
 * Handles plugin installation and activation.
 *
 * @package DemoContentImporter
 * @subpackage Plugins
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Demo Content Importer Plugins Class
 */
class Demo_Content_Importer_Plugins {

    /**
     * Logger instance.
     *
     * @var Demo_Content_Importer_Logger
     */
    protected $logger;

    /**
     * Constructor.
     */
    public function __construct() {
        $this->logger = new Demo_Content_Importer_Logger();
    }

    /**
     * Get plugin status.
     *
     * @param string $plugin_slug Plugin slug.
     * @return string Plugin status (not_installed, inactive, active).
     */
    public function get_plugin_status($plugin_slug) {
        // Get plugin file path
        $plugin_path = $this->get_plugin_path($plugin_slug);
        
        // Check if plugin is installed
        if (!$plugin_path) {
            return 'not_installed';
        }
        
        // Check if plugin is active
        if (is_plugin_active($plugin_path)) {
            return 'active';
        }
        
        return 'inactive';
    }

    /**
     * Get plugin path.
     *
     * @param string $plugin_slug Plugin slug.
     * @return string|false Plugin path or false if not found.
     */
    public function get_plugin_path($plugin_slug) {
        // Get all plugins
        $plugins = get_plugins();
        
        // Loop through plugins
        foreach ($plugins as $path => $plugin) {
            // Check if plugin slug matches
            if (strpos($path, $plugin_slug . '/') === 0) {
                return $path;
            }
        }
        
        return false;
    }

    /**
     * Install plugin.
     *
     * @param string $plugin_slug Plugin slug.
     * @return true|WP_Error True on success, WP_Error on failure.
     */
    public function install_plugin($plugin_slug) {
        $this->logger->info('Installing plugin: ' . $plugin_slug);
        
        // Check if plugin is already installed
        if ($this->get_plugin_status($plugin_slug) !== 'not_installed') {
            $this->logger->info('Plugin already installed: ' . $plugin_slug);
            return true;
        }
        
        // Include required files
        if (!function_exists('plugins_api')) {
            require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
        }
        
        if (!class_exists('WP_Upgrader')) {
            require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
        }
        
        // Get plugin information
        $api = plugins_api('plugin_information', array(
            'slug' => $plugin_slug,
            'fields' => array(
                'short_description' => false,
                'sections' => false,
                'requires' => false,
                'rating' => false,
                'ratings' => false,
                'downloaded' => false,
                'last_updated' => false,
                'added' => false,
                'tags' => false,
                'compatibility' => false,
                'homepage' => false,
                'donate_link' => false,
            ),
        ));
        
        if (is_wp_error($api)) {
            $this->logger->error('Failed to get plugin information: ' . $api->get_error_message());
            return $api;
        }
        
        // Install plugin
        $upgrader = new Plugin_Upgrader(new Automatic_Upgrader_Skin());
        $result = $upgrader->install($api->download_link);
        
        if (is_wp_error($result)) {
            $this->logger->error('Failed to install plugin: ' . $result->get_error_message());
            return $result;
        }
        
        if (is_null($result) || is_wp_error($result)) {
            $this->logger->error('Failed to install plugin: Unknown error');
            return new WP_Error('plugin_install_failed', __('Failed to install plugin', 'demo-content-importer'));
        }
        
        $this->logger->success('Plugin installed: ' . $plugin_slug);
        
        return true;
    }

    /**
     * Activate plugin.
     *
     * @param string $plugin_slug Plugin slug.
     * @return true|WP_Error True on success, WP_Error on failure.
     */
    public function activate_plugin($plugin_slug) {
        $this->logger->info('Activating plugin: ' . $plugin_slug);
        
        // Check if plugin is already active
        if ($this->get_plugin_status($plugin_slug) === 'active') {
            $this->logger->info('Plugin already active: ' . $plugin_slug);
            return true;
        }
        
        // Check if plugin is installed
        $plugin_path = $this->get_plugin_path($plugin_slug);
        
        if (!$plugin_path) {
            $this->logger->error('Plugin not installed: ' . $plugin_slug);
            return new WP_Error('plugin_not_installed', __('Plugin not installed', 'demo-content-importer'));
        }
        
        // Activate plugin
        $result = activate_plugin($plugin_path);
        
        if (is_wp_error($result)) {
            $this->logger->error('Failed to activate plugin: ' . $result->get_error_message());
            return $result;
        }
        
        $this->logger->success('Plugin activated: ' . $plugin_slug);
        
        return true;
    }

    /**
     * Get inactive required plugins.
     *
     * @param array $required_plugins Required plugins.
     * @return array Inactive required plugins.
     */
    public function get_inactive_required_plugins($required_plugins) {
        $inactive_plugins = array();
        
        foreach ($required_plugins as $plugin_slug => $plugin) {
            $status = $this->get_plugin_status($plugin_slug);
            
            if ($status !== 'active') {
                $inactive_plugins[$plugin_slug] = $plugin;
            }
        }
        
        return $inactive_plugins;
    }

    /**
     * Install and activate required plugins.
     *
     * @param array $required_plugins Required plugins.
     * @return true|WP_Error True on success, WP_Error on failure.
     */
    public function install_and_activate_required_plugins($required_plugins) {
        $this->logger->info('Installing and activating required plugins');
        
        foreach ($required_plugins as $plugin_slug => $plugin) {
            // Install plugin if not installed
            $status = $this->get_plugin_status($plugin_slug);
            
            if ($status === 'not_installed') {
                $result = $this->install_plugin($plugin_slug);
                
                if (is_wp_error($result)) {
                    return $result;
                }
            }
            
            // Activate plugin if not active
            $status = $this->get_plugin_status($plugin_slug);
            
            if ($status === 'inactive') {
                $result = $this->activate_plugin($plugin_slug);
                
                if (is_wp_error($result)) {
                    return $result;
                }
            }
        }
        
        return true;
    }

    /**
     * Get plugin information.
     *
     * @param string $plugin_slug Plugin slug.
     * @return object|WP_Error Plugin information or error.
     */
    public function get_plugin_info($plugin_slug) {
        // Include required files
        if (!function_exists('plugins_api')) {
            require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
        }
        
        // Get plugin information
        $api = plugins_api('plugin_information', array(
            'slug' => $plugin_slug,
            'fields' => array(
                'short_description' => true,
                'sections' => false,
                'requires' => true,
                'rating' => false,
                'ratings' => false,
                'downloaded' => false,
                'last_updated' => false,
                'added' => false,
                'tags' => false,
                'compatibility' => false,
                'homepage' => true,
                'donate_link' => false,
            ),
        ));
        
        if (is_wp_error($api)) {
            $this->logger->error('Failed to get plugin information: ' . $api->get_error_message());
            return $api;
        }
        
        return $api;
    }

    /**
     * Get installed plugins.
     *
     * @return array Installed plugins.
     */
    public function get_installed_plugins() {
        // Get all plugins
        $plugins = get_plugins();
        
        $installed_plugins = array();
        
        foreach ($plugins as $path => $plugin) {
            $slug = explode('/', $path)[0];
            
            $installed_plugins[$slug] = array(
                'name' => $plugin['Name'],
                'version' => $plugin['Version'],
                'description' => $plugin['Description'],
                'path' => $path,
                'status' => is_plugin_active($path) ? 'active' : 'inactive',
            );
        }
        
        return $installed_plugins;
    }
}

/**
 * Automatic Upgrader Skin class
 */
class Automatic_Upgrader_Skin extends WP_Upgrader_Skin {
    /**
     * Stores the result of an upgrade.
     *
     * @var bool|WP_Error
     */
    public $result = false;

    /**
     * Constructor.
     */
    public function __construct() {
        parent::__construct(array());
    }

    /**
     * Outputs the header.
     */
    public function header() {}

    /**
     * Outputs the footer.
     */
    public function footer() {}

    /**
     * Outputs JS.
     */
    public function feedback($string, ...$args) {}

    /**
     * Outputs error.
     *
     * @param string|WP_Error $errors Errors.
     */
    public function error($errors) {
        $this->result = $errors;
    }
}