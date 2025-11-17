<?php

namespace App\Providers;

use App\Core\ServiceProvider;

class DarkModeServiceProvider extends ServiceProvider {
	public function register() {
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		add_action( 'wp_footer', [ $this, 'add_dark_mode_toggle' ] );
	}

	public function enqueue_scripts() {
		wp_enqueue_script( 'aqualuxe-dark-mode', get_template_directory_uri() . '/app/modules/vendor/dark-mode/assets/js/dark-mode.js', [], AQUALUXE_VERSION, true );
		wp_enqueue_style( 'aqualuxe-dark-mode', get_template_directory_uri() . '/app/modules/vendor/dark-mode/assets/css/dark-mode.css', [], AQUALUXE_VERSION );
	}

	public function add_dark_mode_toggle() {
		?>
		<div class="dark-mode-toggle">
			<input type="checkbox" id="dark-mode-switch" name="dark-mode-switch">
			<label for="dark-mode-switch"></label>
		</div>
		<?php
	}
}
