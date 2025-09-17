<?php
/**
 * Base Module Interface
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

namespace AquaLuxe\Core\Interfaces;

defined('ABSPATH') || exit;

/**
 * Module Interface
 */
interface Module_Interface {

    /**
     * Get instance
     *
     * @return mixed
     */
    public static function get_instance();

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
     * Get module version
     *
     * @return string
     */
    public function get_version();

    /**
     * Check if module is enabled
     *
     * @return bool
     */
    public function is_enabled();
}