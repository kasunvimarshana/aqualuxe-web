<?php
/**
 * AquaLuxe functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package AquaLuxe
 */

// Define theme constants
define('AQUALUXE_VERSION', '1.0.0');
define('AQUALUXE_THEME_DIR', trailingslashit(get_template_directory()));
define('AQUALUXE_THEME_URI', trailingslashit(get_template_directory_uri()));
define('AQUALUXE_ASSETS_URI', trailingslashit(AQUALUXE_THEME_URI . 'assets/dist'));
define('AQUALUXE_CORE_DIR', trailingslashit(AQUALUXE_THEME_DIR . 'core'));
define('AQUALUXE_MODULES_DIR', trailingslashit(AQUALUXE_THEME_DIR . 'modules'));
define('AQUALUXE_INC_DIR', trailingslashit(AQUALUXE_THEME_DIR . 'inc'));

/**
 * Load the Composer autoloader if it exists.
 */
if (file_exists(AQUALUXE_THEME_DIR . 'vendor/autoload.php')) {
    require_once AQUALUXE_THEME_DIR . 'vendor/autoload.php';
}

/**
 * Load core functionality
 */
require_once AQUALUXE_CORE_DIR . 'setup/theme-setup.php';
require_once AQUALUXE_CORE_DIR . 'setup/theme-supports.php';
require_once AQUALUXE_CORE_DIR . 'setup/enqueue-assets.php';
require_once AQUALUXE_CORE_DIR . 'setup/nav-menus.php';
require_once AQUALUXE_CORE_DIR . 'setup/sidebars.php';
require_once AQUALUXE_CORE_DIR . 'setup/image-sizes.php';

/**
 * Load helper functions
 */
require_once AQUALUXE_INC_DIR . 'helpers/general-helpers.php';
require_once AQUALUXE_INC_DIR . 'helpers/template-helpers.php';

/**
 * Load template functions and tags
 */
require_once AQUALUXE_INC_DIR . 'template-functions/general.php';
require_once AQUALUXE_INC_DIR . 'template-functions/header.php';
require_once AQUALUXE_INC_DIR . 'template-functions/footer.php';
require_once AQUALUXE_INC_DIR . 'template-tags/general.php';
require_once AQUALUXE_INC_DIR . 'template-tags/post.php';

/**
 * Load Customizer functionality
 */
require_once AQUALUXE_INC_DIR . 'customizer/customizer.php';

/**
 * Load WooCommerce compatibility file if WooCommerce is active
 */
if (class_exists('WooCommerce')) {
    require_once AQUALUXE_INC_DIR . 'woocommerce/woocommerce-setup.php';
    require_once AQUALUXE_INC_DIR . 'woocommerce/woocommerce-functions.php';
    require_once AQUALUXE_INC_DIR . 'woocommerce/woocommerce-hooks.php';
}

/**
 * Load Module Base Class
 */
require_once AQUALUXE_CORE_DIR . 'classes/class-aqualuxe-module.php';

/**
 * Module Loader
 * 
 * Dynamically loads enabled modules from the modules directory
 */
require_once AQUALUXE_CORE_DIR . 'classes/class-aqualuxe-module-loader.php';

/**
 * Initialize the Module Loader
 */
function aqualuxe_initialize_modules() {
    $module_loader = AquaLuxe_Module_Loader::get_instance();
    
    // Hook for other plugins/themes to register modules before initialization
    do_action('aqualuxe_before_modules_init', $module_loader);
    
    // Initialize modules
    add_action('after_setup_theme', function() use ($module_loader) {
        // Hook for other plugins/themes to register modules
        do_action('aqualuxe_register_modules', $module_loader);
    }, 5);
}
aqualuxe_initialize_modules();

/**
 * Get module loader instance
 * 
 * @return AquaLuxe_Module_Loader Module loader instance
 */
function aqualuxe_module_loader() {
    return AquaLuxe_Module_Loader::get_instance();
}

/**
 * Check if a module is active
 * 
 * @param string $module_id Module ID
 * @return bool Whether the module is active
 */
function aqualuxe_is_module_active($module_id) {
    return aqualuxe_module_loader()->is_module_enabled($module_id);
}

/**
 * Get module instance
 * 
 * @param string $module_id Module ID
 * @return mixed Module instance or false
 */
function aqualuxe_get_module($module_id) {
    return aqualuxe_module_loader()->get_module_instance($module_id);
}