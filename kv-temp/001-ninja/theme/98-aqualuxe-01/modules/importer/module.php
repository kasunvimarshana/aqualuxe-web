<?php
namespace AquaLuxe\Module\Importer;

final class Module {
	public static function init(): void {
		\add_action( 'admin_menu', [ __CLASS__, 'menu' ] );
		\add_action( 'wp_ajax_aqualuxe_import', [ __CLASS__, 'handle_import' ] );
		\add_action( 'wp_ajax_aqualuxe_flush', [ __CLASS__, 'handle_flush' ] );
	}

	public static function menu(): void {
		\add_submenu_page( 'aqualuxe', __( 'Demo Importer', 'aqualuxe' ), __( 'Demo Importer', 'aqualuxe' ), 'manage_options', 'aqualuxe-importer', [ __CLASS__, 'render' ] );
	}

	public static function render(): void {
		$nonce = \wp_create_nonce('aqualuxe_nonce');
		?>
		<div class="wrap" id="alx-importer">
			<h1><?php echo esc_html__( 'AquaLuxe Demo Importer', 'aqualuxe' ); ?></h1>
			<p class="description"><?php esc_html_e( 'Import demo content or flush existing content for a clean slate.', 'aqualuxe' ); ?></p>
			<div class="actions">
				<button class="button button-primary" id="alx-import" data-nonce="<?php echo esc_attr($nonce); ?>"><?php esc_html_e('Run Import', 'aqualuxe'); ?></button>
				<button class="button" id="alx-flush" data-nonce="<?php echo esc_attr($nonce); ?>"><?php esc_html_e('Flush Content', 'aqualuxe'); ?></button>
			</div>
			<div id="alx-log" class="notice notice-info" style="display:none;"><p></p></div>
		</div>
		<script>
		(function(){
			const $ = document.querySelector.bind(document);
			function log(msg){ const box = $('#alx-log'); box.style.display='block'; box.querySelector('p').textContent = msg; }
			function post(action, nonce){
				return fetch(ajaxurl, { method:'POST', headers:{'Content-Type':'application/x-www-form-urlencoded'}, body:new URLSearchParams({ action, _ajax_nonce: nonce })})
				.then(r=>r.json());
			}
			$('#alx-import').addEventListener('click', async (e)=>{
				const n = e.target.dataset.nonce; log('Import running...');
				const res = await post('aqualuxe_import', n); log(res.message||'Done');
			});
			$('#alx-flush').addEventListener('click', async (e)=>{
				if(!confirm('This will delete posts, pages, products. Continue?')) return;
				const n = e.target.dataset.nonce; log('Flushing...');
				const res = await post('aqualuxe_flush', n); log(res.message||'Done');
			});
		})();
		</script>
		<?php
	}

	public static function handle_import(): void {
		if ( ! \current_user_can('manage_options') || ! \check_ajax_referer('aqualuxe_nonce', '_ajax_nonce', false) ) {
			\wp_send_json_error( [ 'message' => __( 'Unauthorized.', 'aqualuxe' ) ] );
		}
		// Minimal seed: pages and menu.
		$pages = [ 'Home', 'Shop', 'About', 'Services', 'Blog', 'Contact' ];
		foreach ( $pages as $title ) {
			if ( ! \get_page_by_title( $title ) ) {
				\wp_insert_post( [ 'post_title' => $title, 'post_type' => 'page', 'post_status' => 'publish' ] );
			}
		}
		\wp_send_json_success( [ 'message' => __( 'Demo content imported.', 'aqualuxe' ) ] );
	}

	public static function handle_flush(): void {
		if ( ! \current_user_can('manage_options') || ! \check_ajax_referer('aqualuxe_nonce', '_ajax_nonce', false) ) {
			\wp_send_json_error( [ 'message' => __( 'Unauthorized.', 'aqualuxe' ) ] );
		}
		// Delete posts/pages/products (basic example; extend as needed).
		foreach ( [ 'post', 'page', 'product' ] as $type ) {
			$items = \get_posts( [ 'post_type' => $type, 'numberposts' => -1, 'post_status' => 'any' ] );
			foreach ( $items as $it ) { \wp_delete_post( $it->ID, true ); }
		}
		\wp_send_json_success( [ 'message' => __( 'Content flushed.', 'aqualuxe' ) ] );
	}
}
