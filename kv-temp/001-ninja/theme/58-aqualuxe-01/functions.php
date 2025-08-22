<?php
/**
 * AquaLuxe Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Define theme constants
define('AQUALUXE_VERSION', '1.0.0');
define('AQUALUXE_DIR', get_template_directory());
define('AQUALUXE_URI', get_template_directory_uri());
define('AQUALUXE_ASSETS_URI', AQUALUXE_URI . '/assets/dist');

// Minimum PHP version check
if (version_compare(PHP_VERSION, '7.4', '<')) {
    require_once AQUALUXE_DIR . '/core/functions/php-compat.php';
    return;
}

/**
 * Load the core framework
 */
require_once AQUALUXE_DIR . '/core/classes/class-aqualuxe-core.php';

/**
 * Initialize the theme
 */
function aqualuxe_init() {
    // Initialize the core framework
    $aqualuxe = AquaLuxe_Core::get_instance();
    $aqualuxe->init();
}
add_action('after_setup_theme', 'aqualuxe_init');

/**
 * Load helper functions
 */
require_once AQUALUXE_DIR . '/inc/helpers/template-functions.php';
require_once AQUALUXE_DIR . '/inc/helpers/sanitize-functions.php';

/**
 * Load theme setup functions
 */
require_once AQUALUXE_DIR . '/core/functions/setup.php';
require_once AQUALUXE_DIR . '/core/functions/assets.php';
require_once AQUALUXE_DIR . '/core/functions/template-tags.php';
require_once AQUALUXE_DIR . '/core/functions/customizer.php';

/**
 * Load WooCommerce compatibility file if WooCommerce is active
 */
if (class_exists('WooCommerce')) {
    require_once AQUALUXE_DIR . '/core/functions/woocommerce.php';
}

/**
 * Load module loader
 */
require_once AQUALUXE_DIR . '/core/classes/class-aqualuxe-module-loader.php';

/**
 * Load widgets
 */
require_once AQUALUXE_DIR . '/inc/widgets/widget-loader.php';

/**
 * Load Customizer
 */
require_once AQUALUXE_DIR . '/inc/customizer/customizer.php';

/**
 * Load demo content importer
 */
require_once AQUALUXE_DIR . '/inc/demo-import/demo-import.php';

/**
 * Load theme hooks
 */
require_once AQUALUXE_DIR . '/core/hooks/hooks.php';