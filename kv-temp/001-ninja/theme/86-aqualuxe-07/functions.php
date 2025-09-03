<?php
/**
 * Theme bootstrap for AquaLuxe
 *
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Define constants
define('AQUALUXE_VERSION', '1.0.0');
define('AQUALUXE_MIN_WP', '6.0');
define('AQUALUXE_MIN_PHP', '8.0');
define('AQUALUXE_DIR', get_template_directory());
define('AQUALUXE_URI', get_template_directory_uri());
define('AQUALUXE_ASSETS_DIST', AQUALUXE_DIR . '/assets/dist');
define('AQUALUXE_ASSETS_URI', AQUALUXE_URI . '/assets/dist');
define('AQUALUXE_INC', AQUALUXE_DIR . '/inc');
define('AQUALUXE_MODULES', AQUALUXE_DIR . '/modules');
define('AQUALUXE_TEMPLATES', AQUALUXE_DIR . '/templates');

// Composer-like simple autoloader for theme classes
require_once AQUALUXE_INC . '/autoload.php';
require_once AQUALUXE_INC . '/helpers.php';
require_once AQUALUXE_INC . '/security.php';
require_once AQUALUXE_INC . '/template-tags.php';
require_once AQUALUXE_INC . '/shortcodes.php';
require_once AQUALUXE_INC . '/admin/importer.php';
require_once AQUALUXE_INC . '/integrations/woocommerce.php';

// Initialize theme core
\AquaLuxe\Core\Theme::init();

// Load modules
\AquaLuxe\Core\Modules::bootstrap();

// Admin tools (demo importer)
\AquaLuxe\Admin\Importer::init();

// WooCommerce integration (dual-state)
\AquaLuxe\Integrations\WooCommerce::init();
