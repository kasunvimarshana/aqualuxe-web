<?php

namespace App\Providers;

use App\Core\ServiceProvider;

class DemoImporterServiceProvider extends ServiceProvider {
	public function register() {
		require_once AQUALUXE_APP_DIR . '/modules/vendor/demo-importer/class-aqualuxe-demo-importer.php';
		add_action( 'init', function() {
			new \AquaLuxe_Demo_Importer();
		});
	}
}
