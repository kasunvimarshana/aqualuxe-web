<?php

namespace App\Providers;

use App\Core\ServiceProvider;

class MultilingualServiceProvider extends ServiceProvider {
	public function register() {
		add_action( 'init', [ $this, 'load_textdomain' ] );
	}

	public function load_textdomain() {
		load_theme_textdomain( 'aqualuxe', get_template_directory() . '/languages' );
	}
}
