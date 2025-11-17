<?php
/**
 * AquaLuxe Theme bootstrap
 */

declare(strict_types=1);

// Prevent direct access.
if (! defined('ABSPATH')) {
    exit;
}

// Define constants.
define('AQUALUXE_VERSION', '1.0.0');
define('AQUALUXE_DIR', trailingslashit(get_template_directory()));
define('AQUALUXE_URI', trailingslashit(get_template_directory_uri()));
define('AQUALUXE_ASSETS_URI', AQUALUXE_URI . 'assets/dist/');
define('AQUALUXE_ASSETS_DIR', AQUALUXE_DIR . 'assets/dist/');
define('AQUALUXE_INC', AQUALUXE_DIR . 'inc/');
define('AQUALUXE_MODULES', AQUALUXE_DIR . 'modules/');

// PSR-4 like autoloader for inc/ classes.
spl_autoload_register(static function ($class): void {
    if (strpos($class, 'Aqualuxe\\') !== 0) {
        return;
    }
    $path = AQUALUXE_INC . 'classes/' . str_replace(['Aqualuxe\\', '\\'], ['', '/'], $class) . '.php';
    if (file_exists($path)) {
        require_once $path;
    }
});

require_once AQUALUXE_INC . 'helpers.php';
require_once AQUALUXE_INC . 'security.php';
require_once AQUALUXE_INC . 'template-tags.php';
require_once AQUALUXE_INC . 'customizer.php';
require_once AQUALUXE_INC . 'seo.php';
require_once AQUALUXE_INC . 'setup.php';
require_once AQUALUXE_INC . 'enqueue.php';
require_once AQUALUXE_INC . 'modules.php';
require_once AQUALUXE_INC . 'admin.php';
require_once AQUALUXE_INC . 'shortcodes.php';
require_once AQUALUXE_INC . 'rest.php';

// WooCommerce integration (dual-state): load only if active.
if (class_exists('WooCommerce')) {
    require_once AQUALUXE_INC . 'woocommerce.php';
}

// CLI commands: Importer and reset tools when WP-CLI is present.
if (defined('WP_CLI')) {
    require_once AQUALUXE_INC . 'cli.php';
}
