<?php
/**
 * Plugin Name: AquaLuxe Demo Importer
 * Description: Comprehensive, modular demo content importer for WordPress + WooCommerce with rollback, audit, scheduling, and external media providers.
 * Version: 0.1.0
 * Author: AquaLuxe
 * License: GPL-2.0-or-later
 * Text Domain: aqlx-demo-importer
 */

if (!defined('ABSPATH')) { exit; }

// PSR-4-like tiny loader
spl_autoload_register(function($class){
    if (strpos($class, 'AQLX\\DemoImporter\\') !== 0) return;
    $rel = str_replace('AQLX\\DemoImporter\\', '', $class);
    $rel = str_replace('\\', DIRECTORY_SEPARATOR, $rel);
    $path = __DIR__ . '/includes/' . $rel . '.php';
    if (file_exists($path)) require_once $path;
});

// Constants
define('AQLX_DI_VERSION', '0.1.0');
define('AQLX_DI_PATH', plugin_dir_path(__FILE__));
define('AQLX_DI_URL', plugin_dir_url(__FILE__));

// Bootstrap
add_action('plugins_loaded', function(){
    \AQLX\DemoImporter\Admin::init();
    \AQLX\DemoImporter\Rest::init();
    // Ensure cron hook is registered
    add_action('aqlxdi_cron_run', ['\\AQLX\\DemoImporter\\Rest', 'run_job']);
});

// Activation/Deactivation hooks
register_activation_hook(__FILE__, function(){
    // Ensure option container
    if (!get_option('aqlxdi_state')) { add_option('aqlxdi_state', [], '', false); }
});

register_deactivation_hook(__FILE__, function(){
    // Leave state; scheduling can be cleared manually
});
