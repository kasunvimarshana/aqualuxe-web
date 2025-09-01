<?php
/**
 * AquaLuxe Theme bootstrap
 *
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) { exit; }

// Define constants
if (!defined('AQUALUXE_VERSION')) define('AQUALUXE_VERSION', '1.0.0');
if (!defined('AQUALUXE_DIR')) define('AQUALUXE_DIR', trailingslashit(get_template_directory()));
if (!defined('AQUALUXE_URI')) define('AQUALUXE_URI', trailingslashit(get_template_directory_uri()));
if (!defined('AQUALUXE_ASSETS_DIST')) define('AQUALUXE_ASSETS_DIST', AQUALUXE_DIR . 'assets/dist/');
if (!defined('AQUALUXE_ASSETS_URI')) define('AQUALUXE_ASSETS_URI', AQUALUXE_URI . 'assets/dist/');

// Composer autoload if present
$composer = AQUALUXE_DIR . 'vendor/autoload.php';
if (file_exists($composer)) { require_once $composer; }

// Core includes
require_once AQUALUXE_DIR . 'inc/helpers.php';
require_once AQUALUXE_DIR . 'inc/security.php';
require_once AQUALUXE_DIR . 'inc/customizer.php';
require_once AQUALUXE_DIR . 'inc/setup.php';
require_once AQUALUXE_DIR . 'inc/assets.php';
require_once AQUALUXE_DIR . 'inc/cpt.php';
require_once AQUALUXE_DIR . 'inc/woocommerce-compat.php';
require_once AQUALUXE_DIR . 'inc/seo.php';
require_once AQUALUXE_DIR . 'inc/breadcrumbs.php';
require_once AQUALUXE_DIR . 'inc/admin-ui.php';
require_once AQUALUXE_DIR . 'inc/rest.php';
require_once AQUALUXE_DIR . 'inc/i18n.php';
require_once AQUALUXE_DIR . 'templates/shortcodes.php';

// Modules loader
require_once AQUALUXE_DIR . 'core/modules-loader.php';

// Demo importer (admin only)
if (is_admin()) {
    require_once AQUALUXE_DIR . 'importer/class-aqualuxe-importer.php';
}
