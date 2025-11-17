<?php
/**
 * Application Bootstrap
 *
 * @package AquaLuxe
 */

require_once AQUALUXE_APP_DIR . '/core/autoloader.php';

use App\Core\Application;

// Create the application instance.
$app = new Application();

// Register service providers.
$app->registerProviders( [
	\App\Providers\ThemeServiceProvider::class,
	\App\Providers\AssetServiceProvider::class,
	\App\Providers\CptServiceProvider::class,
	\App\Providers\CustomizerServiceProvider::class,
	\App\Providers\WooCommerceServiceProvider::class,
	\App\Providers\DarkModeServiceProvider::class,
	\App\Providers\DemoImporterServiceProvider::class,
	\App\Providers\MultilingualServiceProvider::class,
	\App\Providers\ShortcodeServiceProvider::class,
] );

// Boot the application.
$app->boot();
