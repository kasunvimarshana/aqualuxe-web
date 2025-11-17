<?php
/**
 * Dark Mode Module
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Enqueue dark mode assets.
function aqualuxe_dark_mode_enqueue_assets() {
	// The main app.js and app.css files will include the dark mode assets
	// thanks to the updated webpack.mix.js configuration.
	// We just need to ensure the main stylesheets and scripts are enqueued.
}
add_action( 'wp_enqueue_scripts', 'aqualuxe_dark_mode_enqueue_assets' );

// Add dark mode toggle to the header.
function aqualuxe_dark_mode_toggle() {
	?>
	<button id="dark-mode-toggle" class="dark-mode-toggle">
		<span class="sun-icon">☀️</span>
		<span class="moon-icon">🌙</span>
	</button>
	<?php
}
add_action( 'aqualuxe_header_top', 'aqualuxe_dark_mode_toggle' );
