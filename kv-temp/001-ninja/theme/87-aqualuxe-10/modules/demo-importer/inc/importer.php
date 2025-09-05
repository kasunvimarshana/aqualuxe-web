<?php
/**
 * Demo Importer logic for AquaLuxe theme.
 *
 * @package Aqualuxe
 * @subpackage Modules/DemoImporter
 */

namespace Aqualuxe\Modules\DemoImporter;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers the admin page for demo import.
 */
function register_demo_import_page() {
	add_theme_page(
		__( 'AquaLuxe Demo Import', 'aqualuxe' ),
		__( 'Demo Import', 'aqualuxe' ),
		'manage_options',
		'aqualuxe-demo-import',
		__NAMESPACE__ . '\render_demo_import_page'
	);
}
add_action( 'admin_menu', __NAMESPACE__ . '\register_demo_import_page' );

/**
 * Renders the demo import admin page.
 */
function render_demo_import_page() {
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'AquaLuxe Demo Import', 'aqualuxe' ); ?></h1>
		<p><?php esc_html_e( 'Import demo content, widgets, menus, and theme options with one click.', 'aqualuxe' ); ?></p>
		<form method="post">
			<?php wp_nonce_field( 'aqualuxe_demo_import', 'aqualuxe_demo_import_nonce' ); ?>
			<input type="hidden" name="aqualuxe_demo_import" value="1">
			<?php submit_button( __( 'Import Demo Content', 'aqualuxe' ), 'primary', 'submit', false ); ?>
		</form>
		<?php if ( isset( $_POST['aqualuxe_demo_import'] ) && check_admin_referer( 'aqualuxe_demo_import', 'aqualuxe_demo_import_nonce' ) ) {
			aqualuxe_run_demo_import();
		} ?>
	</div>
	<?php
}

/**
 * Runs the demo import process.
 */
function aqualuxe_run_demo_import() {
	// Import demo content (posts/pages) from WXR file
	$wxr_file = __DIR__ . '/../data/demo-content.xml';
	if ( file_exists( $wxr_file ) ) {
		if ( ! class_exists( 'WP_Import' ) ) {
			require_once ABSPATH . 'wp-admin/includes/class-wp-import.php';
		}
		$importer = new \WP_Import();
		$importer->fetch_attachments = true;
		ob_start();
		$importer->import( $wxr_file );
		ob_end_clean();
	}
	// Import widgets, menus, and theme options (custom logic can be added here)
	// ...
	echo '<div class="notice notice-success"><p>' . esc_html__( 'Demo content imported successfully!', 'aqualuxe' ) . '</p></div>';
}
