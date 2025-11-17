<?php
/**
 * Main theme class
 *
 * @package AquaLuxe
 */

namespace AquaLuxe\Core;

use AquaLuxe\Core\Setup\Theme_Setup;
use AquaLuxe\Core\Setup\Assets;
use AquaLuxe\Core\Setup\Menus;
use AquaLuxe\Core\Setup\Sidebars;
use AquaLuxe\Core\Customizer\Customizer;
use AquaLuxe\Core\Admin\Admin;

/**
 * Main theme class
 */
class AquaLuxe_Theme {
    /**
     * Instance
     *
     * @var AquaLuxe_Theme
     */
    private static $instance = null;

    /**
     * Modules
     *
     * @var array
     */
    private $modules = [];

    /**
     * Constructor
     */
    private function __construct() {
        $this->load_core_files();
        $this->init_core();
        $this->load_modules();
    }

    /**
     * Get instance
     *
     * @return AquaLuxe_Theme
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Load core files
     *
     * @return void
     */
    private function load_core_files() {
        // Load core functions
        require_once AQUALUXE_INC_DIR . 'functions/template-functions.php';
        require_once AQUALUXE_INC_DIR . 'functions/template-tags.php';
        require_once AQUALUXE_INC_DIR . 'functions/helpers.php';
    }

    /**
     * Initialize core components
     *
     * @return void
     */
    private function init_core() {
        // Initialize core components
        new Theme_Setup();
        new Assets();
        new Menus();
        new Sidebars();
        new Customizer();
        new Admin();
    }

    /**
     * Load modules
     *
     * @return void
     */
    private function load_modules() {
        // Get all module directories
        $module_dirs = glob(AQUALUXE_MODULES_DIR . '*', GLOB_ONLYDIR);

        foreach ($module_dirs as $module_dir) {
            $module_name = basename($module_dir);
            $module_file = $module_dir . '/module.php';

            if (file_exists($module_file)) {
                require_once $module_file;
                
                // Module class name
                $module_class = '\\AquaLuxe\\Modules\\' . $this->get_class_name_from_module($module_name) . '\\Module';
                
                if (class_exists($module_class)) {
                    $this->modules[$module_name] = new $module_class();
                }
            }
        }
    }

    /**
     * Get class name from module name
     *
     * @param string $module_name Module name
     * @return string Class name
     */
    private function get_class_name_from_module($module_name) {
        return str_replace(' ', '_', ucwords(str_replace('-', ' ', $module_name)));
    }

    /**
     * Get module
     *
     * @param string $module_name Module name
     * @return mixed|null Module instance or null
     */
    public function get_module($module_name) {
        return isset($this->modules[$module_name]) ? $this->modules[$module_name] : null;
    }

    /**
     * Check if module is active
     *
     * @param string $module_name Module name
     * @return bool
     */
    public function is_module_active($module_name) {
        return isset($this->modules[$module_name]) && $this->modules[$module_name]->is_active();
    }
}