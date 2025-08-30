<?php
/**
 * AquaLuxe Theme bootstrap
 */

if (!defined('AQUALUXE_VERSION')) {
    define('AQUALUXE_VERSION', '1.0.0');
}

if (!defined('AQUALUXE_PATH')) {
    define('AQUALUXE_PATH', get_template_directory());
}

if (!defined('AQUALUXE_URI')) {
    define('AQUALUXE_URI', get_template_directory_uri());
}

// PSR-4 like autoloader for theme classes
spl_autoload_register(function ($class) {
    if (strpos($class, 'AquaLuxe\\') !== 0) {
        return;
    }
    $rel = str_replace('AquaLuxe\\', '', $class);
    $file = AQUALUXE_PATH . '/inc/' . str_replace('\\', '/', $rel) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

// Load theme core
require_once AQUALUXE_PATH . '/core/setup.php';
require_once AQUALUXE_PATH . '/core/assets.php';
require_once AQUALUXE_PATH . '/core/security.php';
require_once AQUALUXE_PATH . '/core/template-tags.php';
require_once AQUALUXE_PATH . '/core/customizer.php';
require_once AQUALUXE_PATH . '/core/demo-importer.php';
require_once AQUALUXE_PATH . '/core/compat-woocommerce.php';
require_once AQUALUXE_PATH . '/core/module-loader.php';
require_once AQUALUXE_PATH . '/core/seo.php';
require_once AQUALUXE_PATH . '/core/ajax.php';

// Initialize theme
add_action('after_setup_theme', ['AquaLuxe\\Core\\Setup', 'init']);
add_action('wp_enqueue_scripts', ['AquaLuxe\\Core\\Assets', 'enqueue']);
add_action('customize_register', ['AquaLuxe\\Core\\Customizer', 'register']);
add_action('init', ['AquaLuxe\\Core\\Security', 'init']);
add_action('init', ['AquaLuxe\\Core\\Module_Loader', 'init']);
add_action('init', ['AquaLuxe\\Core\\Ajax', 'init']);
add_action('after_setup_theme', ['AquaLuxe\\Core\\SEO', 'init']);

// Demo importer in admin only
if (is_admin()) {
    add_action('admin_menu', ['AquaLuxe\\Core\\Demo_Importer', 'register_page']);
}

// Register text domain
add_action('after_setup_theme', function(){
    load_theme_textdomain('aqualuxe', AQUALUXE_PATH . '/languages');
});
?>
