<?php
/**
 * AquaLuxe Demo Importer UI
 *
 * @package AquaLuxe
 * @subpackage Modules
 */

namespace AquaLuxe\Modules\Demo_Importer;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Importer_UI
 *
 * Handles the UI for the demo importer.
 */
class Importer_UI {

	/**
	 * Add the admin page.
	 */
	public static function add_admin_page() {
		add_theme_page(
			esc_html__( 'AquaLuxe Demo Importer', 'aqualuxe' ),
			esc_html__( 'Demo Importer', 'aqualuxe' ),
			'manage_options',
			'aqualuxe-demo-importer',
			array( __CLASS__, 'render_admin_page' )
		);
	}

	/**
	 * Render the admin page.
	 */
	public static function render_admin_page() {
		?>
		<div class="wrap aqualuxe-importer-wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			<div class="aqualuxe-importer-main">
				<div class="aqualuxe-importer-panel">
					<h2><span class="dashicons dashicons-download"></span> <?php esc_html_e( 'Import Demo Data', 'aqualuxe' ); ?></h2>
					<p><?php esc_html_e( 'Import the AquaLuxe demo content, widgets, and customizer settings. This will provide you with a starting point for your site.', 'aqualuxe' ); ?></p>
					<button id="aqualuxe-import-btn" class="button button-primary"><?php esc_html_e( 'Import Demo Data', 'aqualuxe' ); ?></button>
				</div>
				<div class="aqualuxe-importer-panel">
					<h2><span class="dashicons dashicons-trash"></span> <?php esc_html_e( 'Flush Data', 'aqualuxe' ); ?></h2>
					<p><?php esc_html_e( 'This will erase all posts, pages, custom post types, comments, and terms from your database. It will reset your site to a clean installation. This action cannot be undone.', 'aqualuxe' ); ?></p>
					<button id="aqualuxe-flush-btn" class="button button-secondary"><?php esc_html_e( 'Flush All Data', 'aqualuxe' ); ?></button>
				</div>
			</div>
			<div id="aqualuxe-importer-progress" class="aqualuxe-importer-progress" style="display:none;">
				<div class="spinner is-active"></div>
				<p class="progress-text"></p>
			</div>
			<div id="aqualuxe-importer-log" class="aqualuxe-importer-log" style="display:none;">
				<h3><?php esc_html_e( 'Import Log', 'aqualuxe' ); ?></h3>
				<pre></pre>
			</div>
		</div>
		<?php
	}
}
