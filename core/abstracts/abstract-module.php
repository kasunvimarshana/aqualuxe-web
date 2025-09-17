<?php
/**
 * Abstract Base Module
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

namespace AquaLuxe\Core\Abstracts;

use AquaLuxe\Core\Interfaces\Module_Interface;

defined('ABSPATH') || exit;

/**
 * Abstract Module Class
 */
abstract class Abstract_Module implements Module_Interface {

    /**
     * Module name
     *
     * @var string
     */
    protected $name = '';

    /**
     * Module version
     *
     * @var string
     */
    protected $version = '1.0.0';

    /**
     * Module enabled
     *
     * @var bool
     */
    protected $enabled = true;

    /**
     * Module path
     *
     * @var string
     */
    protected $path = '';

    /**
     * Module URL
     *
     * @var string
     */
    protected $url = '';

    /**
     * Constructor
     */
    public function __construct() {
        $this->path = get_template_directory() . '/modules/' . $this->get_module_slug();
        $this->url = get_template_directory_uri() . '/modules/' . $this->get_module_slug();
        
        if ($this->is_enabled()) {
            $this->init();
        }
    }

    /**
     * Initialize module
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
     * Get module version
     *
     * @return string
     */
    public function get_version() {
        return $this->version;
    }

    /**
     * Check if module is enabled
     *
     * @return bool
     */
    public function is_enabled() {
        return $this->enabled && get_theme_mod('aqualuxe_enable_' . $this->get_module_slug(), true);
    }

    /**
     * Get module slug
     *
     * @return string
     */
    protected function get_module_slug() {
        return strtolower(str_replace(' ', '_', $this->name));
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
     * Get module URL
     *
     * @return string
     */
    public function get_url() {
        return $this->url;
    }

    /**
     * Load template
     *
     * @param string $template Template name
     * @param array  $args     Template arguments
     * @param bool   $return   Return template content
     * @return string|void
     */
    public function load_template($template, $args = array(), $return = false) {
        $template_path = $this->path . '/templates/' . $template . '.php';
        
        if (file_exists($template_path)) {
            if ($return) {
                ob_start();
            }
            
            extract($args);
            include $template_path;
            
            if ($return) {
                return ob_get_clean();
            }
        }
    }

    /**
     * Enqueue module styles
     *
     * @param string $handle  Style handle
     * @param string $file    Style file
     * @param array  $deps    Dependencies
     * @param string $version Version
     */
    protected function enqueue_style($handle, $file, $deps = array(), $version = null) {
        $version = $version ?: $this->version;
        wp_enqueue_style($handle, $this->url . '/assets/' . $file, $deps, $version);
    }

    /**
     * Enqueue module scripts
     *
     * @param string $handle    Script handle
     * @param string $file      Script file
     * @param array  $deps      Dependencies
     * @param string $version   Version
     * @param bool   $in_footer In footer
     */
    protected function enqueue_script($handle, $file, $deps = array(), $version = null, $in_footer = true) {
        $version = $version ?: $this->version;
        wp_enqueue_script($handle, $this->url . '/assets/' . $file, $deps, $version, $in_footer);
    }
}