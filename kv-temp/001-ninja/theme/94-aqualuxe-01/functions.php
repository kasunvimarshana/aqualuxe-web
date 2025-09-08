<?php
/**
 * AquaLuxe Theme bootstrap
 *
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit;
}

define('AQUALUXE_VERSION', '1.0.0');
define('AQUALUXE_TEXT_DOMAIN', 'aqualuxe');

define('AQUALUXE_PATH', trailingslashit(get_template_directory()));
define('AQUALUXE_URI', trailingslashit(get_template_directory_uri()));

define('AQUALUXE_ASSETS_DIST_URI', AQUALUXE_URI . 'assets/dist/');

require_once AQUALUXE_PATH . 'inc/core/helpers.php';
require_once AQUALUXE_PATH . 'inc/core/class_autoloader.php';
require_once AQUALUXE_PATH . 'inc/core/class_assets.php';
require_once AQUALUXE_PATH . 'inc/core/class_theme.php';
require_once AQUALUXE_PATH . 'inc/core/class_modules.php';
require_once AQUALUXE_PATH . 'inc/core/class_customizer.php';
require_once AQUALUXE_PATH . 'inc/core/class_cpts.php';
require_once AQUALUXE_PATH . 'inc/core/class_seo.php';
require_once AQUALUXE_PATH . 'inc/core/class_roles.php';
require_once AQUALUXE_PATH . 'inc/core/class_security.php';

// Initialize theme
AquaLuxe\Core\Theme::init();
AquaLuxe\Core\Assets::init();
AquaLuxe\Core\Modules::init();
AquaLuxe\Core\Customizer::init();
AquaLuxe\Core\CPTs::init();
AquaLuxe\Core\SEO::init();
AquaLuxe\Core\Roles::init();
AquaLuxe\Core\Security::init();
