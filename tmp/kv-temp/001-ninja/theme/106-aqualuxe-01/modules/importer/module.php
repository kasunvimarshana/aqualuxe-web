<?php
/** Demo Content Importer (scaffold) */

declare( strict_types=1 );

namespace AquaLuxe\Modules\Importer;

use AquaLuxe\Core\Base_Module;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Module extends Base_Module {
	public function id(): string { return 'importer'; }

	public function register(): void {
		add_action( 'admin_menu', [ $this, 'register_admin_page' ] );
		add_action( 'wp_ajax_aqlx_import_demo', [ $this, 'ajax_import' ] );
	}
	public function boot(): void {}

	public function register_admin_page(): void {
		add_menu_page( __( 'AquaLuxe Importer', 'aqualuxe' ), __( 'AquaLuxe Importer', 'aqualuxe' ), 'manage_options', 'aqlx-importer', [ $this, 'render_page' ], 'dashicons-database-import', 58 );
	}

	public function render_page(): void {
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'AquaLuxe Demo Content Importer', 'aqualuxe' ); ?></h1>
			<p><?php esc_html_e( 'This tool will import demo content (pages, custom post types, taxonomy terms).', 'aqualuxe' ); ?></p>
			<button id="aqlx-start-import" class="button button-primary"><?php esc_html_e( 'Start Import', 'aqualuxe' ); ?></button>
			<pre id="aqlx-import-log" style="max-height:300px;overflow:auto;background:#111;color:#0f0;padding:1em;"></pre>
		</div>
		<script>
		(function($){
			$('#aqlx-start-import').on('click', function(){
				const log = $('#aqlx-import-log');
				log.text('Starting...');
				$.post(ajaxurl, { action: 'aqlx_import_demo', _wpnonce: '<?php echo wp_create_nonce( 'aqlx_import' ); ?>' }, function(resp){
					if(resp.success){ log.append('\nDone. Imported entities: '+resp.data.count); }
					else { log.append('\nError: '+ (resp.data && resp.data.message ? resp.data.message : 'Unknown')); }
				});
			});
		})(jQuery);
		</script>
		<?php
	}

	public function ajax_import(): void {
		aqualuxe_verify_nonce_or_die( 'aqlx_import' );
		// Simplified placeholder importer logic.
		$count = 0;
		$pages = [ 'Home', 'About', 'Services', 'Blog', 'Contact', 'FAQ', 'Privacy Policy', 'Terms & Conditions' ];
		foreach ( $pages as $title ) {
			if ( ! get_page_by_title( $title ) ) {
				wp_insert_post( [ 'post_title' => $title, 'post_type' => 'page', 'post_status' => 'publish' ] );
				$count++;
			}
		}
		wp_send_json_success( [ 'count' => $count ] );
	}
}
