<?php
/**
 * AquaLuxe Module Class
 *
 * This is the base class for all modules in the AquaLuxe theme.
 *
 * @package AquaLuxe
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AquaLuxe Module Class
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
     * Module directory path
     *
     * @var string
     */
    protected $dir = '';

    /**
     * Module directory URL
     *
     * @var string
     */
    protected $url = '';

    /**
     * Module options
     *
     * @var array
     */
    protected $options = [];

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
        // Set module directory path and URL
        $this->dir = AQUALUXE_MODULES_DIR . '/' . $this->id;
        $this->url = AQUALUXE_URI . '/modules/' . $this->id;

        // Load module options
        $this->load_options();

        // Check dependencies
        if ($this->check_dependencies()) {
            // Initialize module
            $this->init();

            // Add module settings to customizer
            add_action('customize_register', [$this, 'customize_register']);

            // Register module assets
            add_action('wp_enqueue_scripts', [$this, 'register_assets']);
            add_action('admin_enqueue_scripts', [$this, 'register_admin_assets']);

            // Load module text domain
            add_action('after_setup_theme', [$this, 'load_textdomain']);
        }
    }

    /**
     * Initialize module
     *
     * This method should be overridden by child classes.
     */
    abstract protected function init();

    /**
     * Check if module is enabled
     *
     * @return bool
     */
    public function is_enabled() {
        return $this->get_option('enabled', true);
    }

    /**
     * Check module dependencies
     *
     * @return bool
     */
    protected function check_dependencies() {
        foreach ($this->dependencies as $dependency) {
            if (is_array($dependency)) {
                $type = isset($dependency['type']) ? $dependency['type'] : 'class';
                $name = isset($dependency['name']) ? $dependency['name'] : '';
                $message = isset($dependency['message']) ? $dependency['message'] : '';

                if ($type === 'class' && !class_exists($name)) {
                    $this->add_dependency_notice($name, $message);
                    return false;
                } elseif ($type === 'function' && !function_exists($name)) {
                    $this->add_dependency_notice($name, $message);
                    return false;
                } elseif ($type === 'plugin' && !$this->is_plugin_active($name)) {
                    $this->add_dependency_notice($name, $message);
                    return false;
                } elseif ($type === 'module' && !$this->is_module_active($name)) {
                    $this->add_dependency_notice($name, $message);
                    return false;
                }
            } elseif (is_string($dependency) && !class_exists($dependency)) {
                $this->add_dependency_notice($dependency);
                return false;
            }
        }

        return true;
    }

    /**
     * Add dependency notice
     *
     * @param string $dependency
     * @param string $message
     */
    protected function add_dependency_notice($dependency, $message = '') {
        if (empty($message)) {
            $message = sprintf(
                /* translators: %1$s: Module name, %2$s: Dependency name */
                __('The %1$s module requires %2$s to be available.', 'aqualuxe'),
                $this->name,
                $dependency
            );
        }

        add_action('admin_notices', function() use ($message) {
            echo '<div class="notice notice-error"><p>' . esc_html($message) . '</p></div>';
        });
    }

    /**
     * Check if plugin is active
     *
     * @param string $plugin
     * @return bool
     */
    protected function is_plugin_active($plugin) {
        if (!function_exists('is_plugin_active')) {
            include_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        return is_plugin_active($plugin);
    }

    /**
     * Check if module is active
     *
     * @param string $module
     * @return bool
     */
    protected function is_module_active($module) {
        global $aqualuxe_theme;

        if (isset($aqualuxe_theme->modules[$module])) {
            return true;
        }

        return false;
    }

    /**
     * Load module options
     */
    protected function load_options() {
        $options = get_option('aqualuxe_module_' . $this->id, []);
        $this->options = wp_parse_args($options, $this->get_default_options());
    }

    /**
     * Get default options
     *
     * @return array
     */
    protected function get_default_options() {
        return [
            'enabled' => true,
        ];
    }

    /**
     * Get option
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get_option($key, $default = '') {
        return isset($this->options[$key]) ? $this->options[$key] : $default;
    }

    /**
     * Set option
     *
     * @param string $key
     * @param mixed $value
     */
    public function set_option($key, $value) {
        $this->options[$key] = $value;
        $this->save_options();
    }

    /**
     * Save options
     */
    protected function save_options() {
        update_option('aqualuxe_module_' . $this->id, $this->options);
    }

    /**
     * Register module settings in customizer
     *
     * @param WP_Customize_Manager $wp_customize
     */
    public function customize_register($wp_customize) {
        // Add module section
        $wp_customize->add_section('aqualuxe_module_' . $this->id, [
            'title' => $this->name,
            'description' => $this->description,
            'panel' => 'aqualuxe_modules_panel',
            'priority' => 10,
        ]);

        // Add module enabled setting
        $wp_customize->add_setting('aqualuxe_module_' . $this->id . '[enabled]', [
            'default' => true,
            'type' => 'option',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        ]);

        // Add module enabled control
        $wp_customize->add_control('aqualuxe_module_' . $this->id . '_enabled', [
            'label' => __('Enable Module', 'aqualuxe'),
            'section' => 'aqualuxe_module_' . $this->id,
            'settings' => 'aqualuxe_module_' . $this->id . '[enabled]',
            'type' => 'checkbox',
            'priority' => 10,
        ]);
    }

    /**
     * Register module assets
     */
    public function register_assets() {
        // Check if module is enabled
        if (!$this->is_enabled()) {
            return;
        }

        // Register module styles
        $style_path = $this->dir . '/assets/css/style.css';
        if (file_exists($style_path)) {
            wp_enqueue_style(
                'aqualuxe-module-' . $this->id,
                $this->url . '/assets/css/style.css',
                [],
                $this->version
            );
        }

        // Register module scripts
        $script_path = $this->dir . '/assets/js/script.js';
        if (file_exists($script_path)) {
            wp_enqueue_script(
                'aqualuxe-module-' . $this->id,
                $this->url . '/assets/js/script.js',
                ['jquery'],
                $this->version,
                true
            );
        }
    }

    /**
     * Register module admin assets
     */
    public function register_admin_assets() {
        // Check if module is enabled
        if (!$this->is_enabled()) {
            return;
        }

        // Register module admin styles
        $admin_style_path = $this->dir . '/assets/css/admin.css';
        if (file_exists($admin_style_path)) {
            wp_enqueue_style(
                'aqualuxe-module-' . $this->id . '-admin',
                $this->url . '/assets/css/admin.css',
                [],
                $this->version
            );
        }

        // Register module admin scripts
        $admin_script_path = $this->dir . '/assets/js/admin.js';
        if (file_exists($admin_script_path)) {
            wp_enqueue_script(
                'aqualuxe-module-' . $this->id . '-admin',
                $this->url . '/assets/js/admin.js',
                ['jquery'],
                $this->version,
                true
            );
        }
    }

    /**
     * Load module text domain
     */
    public function load_textdomain() {
        load_theme_textdomain('aqualuxe', $this->dir . '/languages');
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
     * Get module directory path
     *
     * @return string
     */
    public function get_dir() {
        return $this->dir;
    }

    /**
     * Get module directory URL
     *
     * @return string
     */
    public function get_url() {
        return $this->url;
    }

    /**
     * Get template part
     *
     * @param string $slug
     * @param string $name
     * @param array $args
     */
    public function get_template_part($slug, $name = '', $args = []) {
        // Check if module is enabled
        if (!$this->is_enabled()) {
            return;
        }

        // Set up template file paths
        $template = '';
        $template_name = $name ? "-{$name}" : '';
        $template_path = "modules/{$this->id}/templates/{$slug}{$template_name}.php";
        $default_path = "{$this->dir}/templates/{$slug}{$template_name}.php";

        // Look for template in theme
        if (file_exists(AQUALUXE_DIR . '/' . $template_path)) {
            $template = AQUALUXE_DIR . '/' . $template_path;
        } elseif (file_exists($default_path)) {
            $template = $default_path;
        }

        // Allow third party plugins to filter template file from their plugin
        $template = apply_filters('aqualuxe_module_template_' . $this->id, $template, $slug, $name, $args);

        // If template is found, load it
        if ($template) {
            load_template($template, false, $args);
        }
    }

    /**
     * Locate template
     *
     * @param string $template_name
     * @return string
     */
    public function locate_template($template_name) {
        // Check if module is enabled
        if (!$this->is_enabled()) {
            return '';
        }

        // Set up template file paths
        $template = '';
        $template_path = "modules/{$this->id}/templates/{$template_name}";
        $default_path = "{$this->dir}/templates/{$template_name}";

        // Look for template in theme
        if (file_exists(AQUALUXE_DIR . '/' . $template_path)) {
            $template = AQUALUXE_DIR . '/' . $template_path;
        } elseif (file_exists($default_path)) {
            $template = $default_path;
        }

        // Allow third party plugins to filter template file from their plugin
        $template = apply_filters('aqualuxe_module_locate_template_' . $this->id, $template, $template_name);

        return $template;
    }

    /**
     * Get template
     *
     * @param string $template_name
     * @param array $args
     * @param bool $echo
     * @return string|void
     */
    public function get_template($template_name, $args = [], $echo = true) {
        // Check if module is enabled
        if (!$this->is_enabled()) {
            return '';
        }

        // Locate template
        $template = $this->locate_template($template_name);

        // Return if template not found
        if (!$template) {
            return '';
        }

        // Extract args
        if (!empty($args) && is_array($args)) {
            extract($args);
        }

        // Start output buffering
        ob_start();

        // Include template
        include $template;

        // Get output
        $output = ob_get_clean();

        // Echo or return output
        if ($echo) {
            echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        } else {
            return $output;
        }
    }
}