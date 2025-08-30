<?php
/**
 * Module interface
 *
 * @package AquaLuxe
 */

namespace AquaLuxe\Core;

/**
 * Module interface
 */
interface Module {
    /**
     * Initialize module
     *
     * @return void
     */
    public function init();

    /**
     * Get module name
     *
     * @return string
     */
    public function get_name();

    /**
     * Get module title
     *
     * @return string
     */
    public function get_title();

    /**
     * Get module description
     *
     * @return string
     */
    public function get_description();

    /**
     * Get module version
     *
     * @return string
     */
    public function get_version();

    /**
     * Get module author
     *
     * @return string
     */
    public function get_author();

    /**
     * Get module author URI
     *
     * @return string
     */
    public function get_author_uri();

    /**
     * Get required modules
     *
     * @return array
     */
    public function get_requires();

    /**
     * Get conflicting modules
     *
     * @return array
     */
    public function get_conflicts();

    /**
     * Check if module is active
     *
     * @return bool
     */
    public function is_active();

    /**
     * Set module active status
     *
     * @param bool $active Active status
     * @return void
     */
    public function set_active($active);
}