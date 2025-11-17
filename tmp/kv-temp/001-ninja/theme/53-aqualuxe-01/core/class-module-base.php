<?php
/**
 * Module base class
 *
 * @package AquaLuxe
 */

namespace AquaLuxe\Core;

/**
 * Module base class
 */
abstract class Module_Base {
    /**
     * Module name
     *
     * @var string
     */
    protected $name = '';

    /**
     * Module title
     *
     * @var string
     */
    protected $title = '';

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
     * Module author
     *
     * @var string
     */
    protected $author = 'AquaLuxe';

    /**
     * Module author URI
     *
     * @var string
     */
    protected $author_uri = 'https://aqualuxe.com';

    /**
     * Required modules
     *
     * @var array
     */
    protected $requires = [];

    /**
     * Conflicting modules
     *
     * @var array
     */
    protected $conflicts = [];

    /**
     * Module options
     *
     * @var array
     */
    protected $options = [];

    /**
     * Module active status
     *
     * @var bool
     */
    protected $active = true;

    /**
     * Constructor
     */
    public function __construct() {
        $this->init();
    }

    /**
     * Initialize module
     *
     * @return void
     */
    abstract public function init();

    /**
     * Get module name
     *
     * @return string
     */
    public function get_name() {
        return $this->name;
    }

    /**
     * Get module title
     *
     * @return string
     */
    public function get_title() {
        return $this->title;
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
     * Get module author
     *
     * @return string
     */
    public function get_author() {
        return $this->author;
    }

    /**
     * Get module author URI
     *
     * @return string
     */
    public function get_author_uri() {
        return $this->author_uri;
    }

    /**
     * Get required modules
     *
     * @return array
     */
    public function get_requires() {
        return $this->requires;
    }

    /**
     * Get conflicting modules
     *
     * @return array
     */
    public function get_conflicts() {
        return $this->conflicts;
    }

    /**
     * Check if module is active
     *
     * @return bool
     */
    public function is_active() {
        return $this->active;
    }

    /**
     * Set module active status
     *
     * @param bool $active Active status
     * @return void
     */
    public function set_active($active) {
        $this->active = (bool) $active;
    }

    /**
     * Get module option
     *
     * @param string $option Option name
     * @param mixed  $default Default value
     * @return mixed
     */
    public function get_option($option, $default = '') {
        $options = $this->get_options();
        return isset($options[$option]) ? $options[$option] : $default;
    }

    /**
     * Get module options
     *
     * @return array
     */
    public function get_options() {
        if (empty($this->options)) {
            $this->options = get_option('aqualuxe_module_' . $this->name, []);
        }

        return $this->options;
    }

    /**
     * Update module option
     *
     * @param string $option Option name
     * @param mixed  $value Option value
     * @return bool
     */
    public function update_option($option, $value) {
        $options = $this->get_options();
        $options[$option] = $value;
        return $this->update_options($options);
    }

    /**
     * Update module options
     *
     * @param array $options Options
     * @return bool
     */
    public function update_options($options) {
        $this->options = $options;
        return update_option('aqualuxe_module_' . $this->name, $options);
    }

    /**
     * Delete module option
     *
     * @param string $option Option name
     * @return bool
     */
    public function delete_option($option) {
        $options = $this->get_options();
        if (isset($options[$option])) {
            unset($options[$option]);
            return $this->update_options($options);
        }
        return false;
    }

    /**
     * Get module URL
     *
     * @param string $path Path
     * @return string
     */
    public function get_url($path = '') {
        return aqualuxe_get_module_url($this->name, $path);
    }

    /**
     * Get module directory
     *
     * @param string $path Path
     * @return string
     */
    public function get_dir($path = '') {
        return aqualuxe_get_module_dir($this->name, $path);
    }

    /**
     * Get module template part
     *
     * @param string $slug Template slug
     * @param string $name Template name
     * @param array  $args Template arguments
     * @return void
     */
    public function get_template_part($slug, $name = null, $args = []) {
        aqualuxe_get_module_template_part($this->name, $slug, $name, $args);
    }

    /**
     * Get module template HTML
     *
     * @param string $slug Template slug
     * @param string $name Template name
     * @param array  $args Template arguments
     * @return string
     */
    public function get_template_html($slug, $name = null, $args = []) {
        return aqualuxe_get_module_template_html($this->name, $slug, $name, $args);
    }

    /**
     * Get module settings URL
     *
     * @return string
     */
    public function get_settings_url() {
        return aqualuxe_get_module_settings_url($this->name);
    }
}