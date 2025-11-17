<?php
/**
 * Module Base Class
 *
 * @package AquaLuxe
 */

namespace AquaLuxe;

/**
 * Module Base Class
 * 
 * This class serves as the base for all modules in the theme.
 * Each module should extend this class and implement its methods.
 */
abstract class Module {
    /**
     * Module ID
     *
     * @var string
     */
    protected $id;

    /**
     * Module name
     *
     * @var string
     */
    protected $name;

    /**
     * Module description
     *
     * @var string
     */
    protected $description;

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
     * Module path
     *
     * @var string
     */
    protected $path;

    /**
     * Module URI
     *
     * @var string
     */
    protected $uri;

    /**
     * Constructor
     */
    public function __construct() {
        // Set module path and URI
        $this->path = AQUALUXE_MODULES_DIR . $this->id . '/';
        $this->uri = AQUALUXE_URI . 'modules/' . $this->id . '/';
        
        // Initialize module
        $this->init();
        
        // Register activation and deactivation hooks
        add_action('aqualuxe_module_activated_' . $this->id, [$this, 'activate']);
        add_action('aqualuxe_module_deactivated_' . $this->id, [$this, 'deactivate']);
    }

    /**
     * Initialize module
     * 
     * This method should be implemented by each module to register hooks and load required files.
     */
    abstract public function init();

    /**
     * Activate module
     * 
     * This method is called when the module is activated.
     */
    public function activate() {
        // Default implementation does nothing
    }

    /**
     * Deactivate module
     * 
     * This method is called when the module is deactivated.
     */
    public function deactivate() {
        // Default implementation does nothing
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
     * Get module dependencies
     *
     * @return array
     */
    public function get_dependencies() {
        return $this->dependencies;
    }

    /**
     * Get module path
     *
     * @return string
     */
    public function get_path() {
        return $this->path;
    }

    /**
     * Get module URI
     *
     * @return string
     */
    public function get_uri() {
        return $this->uri;
    }

    /**
     * Check if the module has a specific dependency
     *
     * @param string $dependency
     * @return bool
     */
    public function has_dependency($dependency) {
        return in_array($dependency, $this->dependencies);
    }

    /**
     * Check if all dependencies are met
     *
     * @return bool
     */
    public function dependencies_met() {
        $theme = \AquaLuxe\Theme::get_instance();
        $active_modules = $theme->get_active_modules();
        
        foreach ($this->dependencies as $dependency) {
            if (!isset($active_modules[$dependency])) {
                return false;
            }
        }
        
        return true;
    }

    /**
     * Get template part
     *
     * @param string $slug
     * @param string $name
     * @param array $args
     */
    public function get_template_part($slug, $name = null, $args = []) {
        $template = '';
        
        // Look in module/templates/slug-name.php
        if ($name) {
            $template = $this->path . 'templates/' . $slug . '-' . $name . '.php';
        }
        
        // If template doesn't exist, look in module/templates/slug.php
        if (!$template || !file_exists($template)) {
            $template = $this->path . 'templates/' . $slug . '.php';
        }
        
        // If template exists, include it
        if (file_exists($template)) {
            extract($args);
            include $template;
        }
    }

    /**
     * Enqueue module styles
     *
     * @param string $handle
     * @param string $src
     * @param array $deps
     * @param string|bool $ver
     * @param string $media
     */
    public function enqueue_style($handle, $src = '', $deps = [], $ver = false, $media = 'all') {
        if (!$ver) {
            $ver = $this->version;
        }
        
        wp_enqueue_style($handle, $this->uri . $src, $deps, $ver, $media);
    }

    /**
     * Enqueue module scripts
     *
     * @param string $handle
     * @param string $src
     * @param array $deps
     * @param string|bool $ver
     * @param bool $in_footer
     */
    public function enqueue_script($handle, $src = '', $deps = [], $ver = false, $in_footer = true) {
        if (!$ver) {
            $ver = $this->version;
        }
        
        wp_enqueue_script($handle, $this->uri . $src, $deps, $ver, $in_footer);
    }

    /**
     * Register module settings
     *
     * @param array $settings
     */
    public function register_settings($settings) {
        foreach ($settings as $setting) {
            register_setting(
                'aqualuxe_module_' . $this->id,
                $setting['option_name'],
                $setting['args'] ?? []
            );
        }
    }

    /**
     * Get module setting
     *
     * @param string $option_name
     * @param mixed $default
     * @return mixed
     */
    public function get_setting($option_name, $default = false) {
        return get_option('aqualuxe_module_' . $this->id . '_' . $option_name, $default);
    }

    /**
     * Update module setting
     *
     * @param string $option_name
     * @param mixed $value
     * @return bool
     */
    public function update_setting($option_name, $value) {
        return update_option('aqualuxe_module_' . $this->id . '_' . $option_name, $value);
    }
}