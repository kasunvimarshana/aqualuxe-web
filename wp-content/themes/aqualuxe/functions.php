<?php
/**
 * AquaLuxe Theme bootstrap.
 *
 * @package aqualuxe
 */

declare(strict_types=1);

// Prevent direct access.
if (! defined('ABSPATH')) {
    exit;
}

// Define constants.
define('AQUALUXE_VERSION', '1.0.0');
define('AQUALUXE_THEME_DIR', trailingslashit(get_template_directory()));
define('AQUALUXE_THEME_URI', trailingslashit(get_template_directory_uri()));
define('AQUALUXE_ASSETS_DIST', AQUALUXE_THEME_DIR . 'assets/dist/');
define('AQUALUXE_ASSETS_URI', AQUALUXE_THEME_URI . 'assets/dist/');
define('AQUALUXE_LANG_DIR', AQUALUXE_THEME_DIR . 'languages');

// Register autoloader.
require_once AQUALUXE_THEME_DIR . 'inc/core/autoload.php';
// Load shims for static analysis outside WP runtime (no-ops if WP provides functions).
require_once AQUALUXE_THEME_DIR . 'inc/core/Shims.php';

// Initialize theme.
add_action('after_setup_theme', static function () {
    \AquaLuxe\Core\Theme::init();
    \AquaLuxe\Core\SEO::init();
    \AquaLuxe\Core\Security::init();
});

// Load modules based on configuration.
add_action('init', static function () {
    \AquaLuxe\Core\Modules::boot();
}, 8);

// Enqueue assets.
add_action('wp_enqueue_scripts', static function () {
    \AquaLuxe\Core\Assets::enqueue();
});

// Admin assets if needed.
add_action('admin_enqueue_scripts', static function () {
    \AquaLuxe\Core\Assets::enqueue_admin();
});

// Register REST routes for importer and utilities.
add_action('rest_api_init', static function () {
    \AquaLuxe\Core\REST::register_routes();
});
