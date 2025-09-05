<?php
/**
 * Theme bootstrap and service container wiring.
 *
 * @package Aqualuxe
 */

// Prevent direct access.
if (!defined('ABSPATH')) { exit; }

// Define theme constants.
if (!defined('AQUALUXE_PATH')) {
	define('AQUALUXE_PATH', __DIR__ . '/');
}
if (!defined('AQUALUXE_URL')) {
	define('AQUALUXE_URL', get_stylesheet_directory_uri() . '/');
}
if (!defined('AQUALUXE_VERSION')) {
	define('AQUALUXE_VERSION', '1.0.0');
}

// Lightweight PSR-4 autoloader for Aqualuxe\* when Composer isn't available.
spl_autoload_register(static function ($class) {
	$prefix = 'Aqualuxe\\';
	$len = strlen($prefix);
	if (strncmp($prefix, $class, $len) !== 0) {
		return;
	}
	$relative = substr($class, $len);
	$relativePath = __DIR__ . '/src/' . str_replace('\\', '/', $relative) . '.php';
	if (file_exists($relativePath)) {
		require_once $relativePath;
	}
});

// Composer autoloader (optional, preferred in production).
$composer = __DIR__ . '/vendor/autoload.php';
if (file_exists($composer)) {
	require_once $composer;
}

// Translation support.
add_action('after_setup_theme', static function () {
	load_theme_textdomain('aqualuxe', AQUALUXE_PATH . 'languages');
});

// Initialize Application and register providers.
add_action('after_setup_theme', static function () {
	$app = Aqualuxe\Core\App::getInstance();

	$app->register(new Aqualuxe\Core\Providers\ThemeSupportProvider());
	$app->register(new Aqualuxe\Core\Providers\AssetsProvider());
	$app->register(new Aqualuxe\Core\Providers\SecurityProvider());
	$app->register(new Aqualuxe\Core\Providers\SeoProvider());
	$app->register(new Aqualuxe\Core\Providers\AdminProvider());
	$app->register(new Aqualuxe\Core\Providers\CacheProvider());
	$app->register(new Aqualuxe\Core\Providers\BreadcrumbProvider());

	if (Aqualuxe\Core\Features::enabled('vendors')) { $app->register(new Aqualuxe\Core\Providers\VendorProvider()); }
	if (Aqualuxe\Core\Features::enabled('languages')) { $app->register(new Aqualuxe\Core\Providers\LanguageProvider()); }
	if (Aqualuxe\Core\Features::enabled('classifieds')) { $app->register(new Aqualuxe\Core\Providers\ClassifiedsProvider()); }
	if (Aqualuxe\Core\Features::enabled('currency')) { $app->register(new Aqualuxe\Core\Providers\CurrencyProvider()); }
	if (Aqualuxe\Core\Features::enabled('listings')) { $app->register(new Aqualuxe\Core\Providers\CptProvider()); }

	$app->register(new Aqualuxe\Core\Providers\TenantProvider());
	$app->register(new Aqualuxe\Core\Providers\RestProvider());
	$app->register(new Aqualuxe\Core\Providers\ThemeVariantProvider());

	$app->boot();

	// Shortcodes
	if (Aqualuxe\Core\Features::enabled('vendors')) { (new Aqualuxe\Shortcodes\Vendors())->register(); }
	if (Aqualuxe\Core\Features::enabled('currency')) { (new Aqualuxe\Shortcodes\CurrencySwitcher())->register(); }
	if (Aqualuxe\Core\Features::enabled('listings')) { (new Aqualuxe\Shortcodes\Listings())->register(); }
}, 5);
