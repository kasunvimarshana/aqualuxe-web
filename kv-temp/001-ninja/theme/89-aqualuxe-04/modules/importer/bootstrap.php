<?php
/** Demo content importer - admin UI and endpoints (skeleton, safe defaults) */
namespace AquaLuxe\Modules\Importer;
if ( ! defined( 'ABSPATH' ) ) { exit; }
use function AquaLuxe\Core\log_error;
use function AquaLuxe\Core\log_info;

const PAGE_SLUG = 'aqualuxe_importer';

\add_action( 'admin_menu', function () {
	if ( \function_exists( 'add_management_page' ) ) {
		\call_user_func( 'add_management_page', \__( 'AquaLuxe Importer', 'aqualuxe' ), \__( 'AquaLuxe Importer', 'aqualuxe' ), 'manage_options', PAGE_SLUG, __NAMESPACE__ . '\\render_page' );
	}
} );

function render_page() {
	$export_json = '';
	if ( isset( $_POST['alx_action'] ) && \check_admin_referer( 'alx_importer', '_alx_nonce' ) ) {
		$unslasher = \function_exists('wp_unslash') ? 'wp_unslash' : null;
		$raw = $unslasher ? (string) \call_user_func( $unslasher, $_POST['alx_action'] ) : (string) $_POST['alx_action'];
		$action = \sanitize_text_field( $raw );
		if ( 'flush' === $action ) {
			$types = isset($_POST['alx_flush_types']) && is_array($_POST['alx_flush_types']) ? array_map('sanitize_text_field', (array) $_POST['alx_flush_types']) : [];
			flush_demo( $types );
			if ( \function_exists( 'add_settings_error' ) ) { \call_user_func( 'add_settings_error', 'aqualuxe_importer', 'flushed', \__( 'Selected demo content flushed.', 'aqualuxe' ), 'updated' ); }
		} elseif ( 'import' === $action ) {
			$opts = [
				'pages' => ! empty( $_POST['alx_pages'] ),
				'services' => ! empty( $_POST['alx_services'] ),
				'events' => ! empty( $_POST['alx_events'] ),
				'testimonials' => ! empty( $_POST['alx_testimonials'] ),
				'products' => ! empty( $_POST['alx_products'] ),
			];
			try {
				$summary = run_import( $opts );
				log_info('Importer finished', $summary);
				if ( \function_exists( 'add_settings_error' ) ) { \call_user_func( 'add_settings_error', 'aqualuxe_importer', 'imported', \sprintf( \__( 'Imported: %s', 'aqualuxe' ), \esc_html( \wp_json_encode( $summary ) ) ), 'updated' ); }
			} catch ( \Throwable $e ) {
				log_error('Importer failed', [ 'error' => $e->getMessage() ]);
				if ( \function_exists( 'add_settings_error' ) ) { \call_user_func( 'add_settings_error', 'aqualuxe_importer', 'import_error', \__( 'Import failed. Rolled back. Check logs.', 'aqualuxe' ), 'error' ); }
			}
		} elseif ( 'export' === $action ) {
			$export_json = export_demo();
			if ( \function_exists( 'add_settings_error' ) ) { \call_user_func( 'add_settings_error', 'aqualuxe_importer', 'exported', \__( 'Export ready below.', 'aqualuxe' ), 'updated' ); }
		}
	}
	if ( \function_exists( 'settings_errors' ) ) { \call_user_func( 'settings_errors', 'aqualuxe_importer' ); }
	?>
	<div class="wrap">
		<h1><?php \esc_html_e( 'AquaLuxe Demo Importer', 'aqualuxe' ); ?></h1>
		<form method="post">
			<?php \wp_nonce_field( 'alx_importer', '_alx_nonce' ); ?>
			<h2><?php \esc_html_e('Import Options','aqualuxe'); ?></h2>
			<label><input type="checkbox" name="alx_pages" checked> <?php \esc_html_e('Pages','aqualuxe'); ?></label>
			<label><input type="checkbox" name="alx_services" checked> <?php \esc_html_e('Services','aqualuxe'); ?></label>
			<label><input type="checkbox" name="alx_events" checked> <?php \esc_html_e('Events','aqualuxe'); ?></label>
			<label><input type="checkbox" name="alx_testimonials" checked> <?php \esc_html_e('Testimonials','aqualuxe'); ?></label>
			<label><input type="checkbox" name="alx_products" checked> <?php \esc_html_e('Products (WooCommerce)','aqualuxe'); ?></label>
			<p><button class="button button-primary" name="alx_action" value="import"><?php \esc_html_e( 'Run Import', 'aqualuxe' ); ?></button></p>
			<hr />
			<h2><?php \esc_html_e('Flush Demo Content','aqualuxe'); ?></h2>
			<label><input type="checkbox" name="alx_flush_types[]" value="page"> <?php \esc_html_e('Pages','aqualuxe'); ?></label>
			<label><input type="checkbox" name="alx_flush_types[]" value="service"> <?php \esc_html_e('Services','aqualuxe'); ?></label>
			<label><input type="checkbox" name="alx_flush_types[]" value="event"> <?php \esc_html_e('Events','aqualuxe'); ?></label>
			<label><input type="checkbox" name="alx_flush_types[]" value="testimonial"> <?php \esc_html_e('Testimonials','aqualuxe'); ?></label>
			<label><input type="checkbox" name="alx_flush_types[]" value="product"> <?php \esc_html_e('Products','aqualuxe'); ?></label>
			<p><button class="button" name="alx_action" value="flush" onclick="return confirm('Flush selected demo content?');"><?php \esc_html_e( 'Flush Selected', 'aqualuxe' ); ?></button></p>
			<hr />
			<h2><?php \esc_html_e('Export Demo JSON','aqualuxe'); ?></h2>
			<p><button class="button" name="alx_action" value="export"><?php \esc_html_e( 'Generate Export', 'aqualuxe' ); ?></button></p>
			<?php if ($export_json): ?>
				<textarea rows="10" style="width:100%" readonly><?php
					$__alx_val = \function_exists('esc_textarea')
						? \call_user_func('esc_textarea', $export_json)
						: \htmlspecialchars((string)$export_json, ENT_QUOTES, 'UTF-8');
					echo $__alx_val;
				?></textarea>
			<?php endif; ?>
		</form>
	</div>
	<?php
}

function flush_demo( array $types = [] ) {
	// Delete only entities marked with meta _alx_demo=1
	$types = $types ?: [ 'service', 'event', 'testimonial', 'post', 'page', 'product' ];
	foreach ( $types as $type ) {
		try {
			$q = new \WP_Query( [ 'post_type' => $type, 'post_status' => 'any', 'posts_per_page' => -1, 'meta_key' => '_alx_demo', 'meta_value' => '1' ] );
			while ( $q->have_posts() ) { $q->the_post(); \wp_delete_post( \get_the_ID(), true ); }
			\wp_reset_postdata();
		} catch ( \Throwable $e ) { log_error('Flush error', [ 'type' => $type, 'error' => $e->getMessage() ] ); }
	}
}

function run_import( array $opts = [] ): array {
	$created = [ 'pages' => 0, 'posts' => 0, 'services' => 0, 'events' => 0, 'testimonials' => 0, 'products' => 0 ];
	$created_ids = [];
	log_info('Importer started');
	try {
		// Pages
		if ( !empty($opts['pages']) ) {
			$pages = [
				'home'   => [ 'title' => 'Home', 'content' => '[alx_cta label="Shop Now" url="/shop"]' ],
				'about'  => [ 'title' => 'About', 'content' => 'Company history and sustainability.' ],
				'services' => [ 'title' => 'Services', 'content' => 'Aquarium design, maintenance, quarantine, breeding, consultation.' ],
				'contact'  => [ 'title' => 'Contact', 'content' => '[alx_contact_form]' ],
			];
			foreach ( $pages as $slug => $data ) {
				$id = \wp_insert_post( [ 'post_title' => $data['title'], 'post_name' => $slug, 'post_type' => 'page', 'post_status' => 'publish', 'post_content' => $data['content'], 'meta_input' => [ '_alx_demo' => '1' ] ] );
				if ( $id && ! \is_wp_error( $id ) ) { $created['pages']++; $created_ids[] = (int) $id; }
			}
			// Set static front page if created
			$home = \get_page_by_path( 'home' );
			if ( $home ) {
				\update_option( 'show_on_front', 'page' );
				\update_option( 'page_on_front', $home->ID );
			}
			// Ensure primary menu
			$primary_menu = \wp_get_nav_menu_object( 'Primary' );
			if ( ! $primary_menu ) { $menu_id = \wp_create_nav_menu( 'Primary' ); } else { $menu_id = $primary_menu->term_id; }
			if ( $menu_id && ! \is_wp_error( $menu_id ) ) {
				$home_id = isset($home->ID) ? $home->ID : 0;
				if ( $home_id ) { \wp_update_nav_menu_item( $menu_id, 0, [ 'menu-item-title' => 'Home', 'menu-item-object' => 'page', 'menu-item-object-id' => $home_id, 'menu-item-type' => 'post_type', 'menu-item-status' => 'publish' ] ); }
				\set_theme_mod( 'nav_menu_locations', array_merge( (array) \get_theme_mod( 'nav_menu_locations', [] ), [ 'primary' => (int) $menu_id ] ) );
			}
		}

		// CPT samples
		if ( !empty($opts['services']) ) {
			$svc = \wp_insert_post( [ 'post_title' => 'Aquarium Design & Installation', 'post_type' => 'service', 'post_status' => 'publish', 'post_content' => 'Custom aquariums for homes, offices, hotels.', 'meta_input' => [ '_alx_demo' => '1' ] ] );
			if ( $svc && ! \is_wp_error( $svc ) ) { $created['services']++; $created_ids[] = (int) $svc; }
		}
		if ( !empty($opts['events']) ) {
			$ev = \wp_insert_post( [ 'post_title' => 'Aquascaping Competition', 'post_type' => 'event', 'post_status' => 'publish', 'post_content' => 'Join our annual event.', 'meta_input' => [ '_alx_demo' => '1' ] ] );
			if ( $ev && ! \is_wp_error( $ev ) ) { $created['events']++; $created_ids[] = (int) $ev; }
		}
		if ( !empty($opts['testimonials']) ) {
			$ts = \wp_insert_post( [ 'post_title' => 'Luxe Hotel Dubai', 'post_type' => 'testimonial', 'post_status' => 'publish', 'post_content' => 'Flawless installation and service.', 'meta_input' => [ '_alx_demo' => '1' ] ] );
			if ( $ts && ! \is_wp_error( $ts ) ) { $created['testimonials']++; $created_ids[] = (int) $ts; }
		}

		// WooCommerce products
		if ( !empty($opts['products']) && class_exists( '\\WC_Product_Simple' ) ) {
			$cats = [ 'Rare Fish Species', 'Aquatic Plants', 'Premium Equipment', 'Care Supplies' ];
			$cat_ids = [];
			foreach ( $cats as $c ) {
				$term = \term_exists( $c, 'product_cat' );
				if ( 0 === $term || null === $term ) { $term = \wp_insert_term( $c, 'product_cat' ); }
				if ( ! \is_wp_error( $term ) ) { $cat_ids[] = (int) ( $term['term_id'] ?? $term ); }
			}
			$products = [
				[ 'name' => 'Blue Phantom Pleco', 'price' => '299.00', 'cat' => $cat_ids[0] ?? 0 ],
				[ 'name' => 'Anubias Nana Tissue Culture', 'price' => '14.00', 'cat' => $cat_ids[1] ?? 0 ],
				[ 'name' => 'Titanium Heater 300W', 'price' => '89.00', 'cat' => $cat_ids[2] ?? 0 ],
				[ 'name' => 'Premium Koi Feed 1kg', 'price' => '24.00', 'cat' => $cat_ids[3] ?? 0 ],
			];
			foreach ( $products as $pd ) {
				$p = new \WC_Product_Simple();
				$p->set_name( $pd['name'] );
				$p->set_regular_price( $pd['price'] );
				$p->set_catalog_visibility( 'visible' );
				$p->set_status( 'publish' );
				$id = $p->save();
				if ( $id ) {
					\update_post_meta( $id, '_alx_demo', '1' );
					if ( $pd['cat'] ) { \wp_set_object_terms( $id, [ $pd['cat'] ], 'product_cat' ); }
					$created['products']++;
					$created_ids[] = (int) $id;
				}
			}
		}
	} catch ( \Throwable $e ) {
		// Rollback created entities on error
		foreach ( $created_ids as $cid ) { \wp_delete_post( $cid, true ); }
		log_error('Importer rollback', [ 'error' => $e->getMessage() ]);
		throw $e;
	}
	return $created;
}

function export_demo(): string {
	$out = [ 'pages' => [], 'services' => [], 'events' => [], 'testimonials' => [], 'products' => [] ];
	$types = [ 'page' => 'pages', 'service' => 'services', 'event' => 'events', 'testimonial' => 'testimonials', 'product' => 'products' ];
	foreach ( $types as $ptype => $key ) {
		$q = new \WP_Query( [ 'post_type' => $ptype, 'post_status' => 'any', 'posts_per_page' => -1, 'meta_key' => '_alx_demo', 'meta_value' => '1' ] );
		while ( $q->have_posts() ) { $q->the_post();
			$out[$key][] = [ 'id' => \get_the_ID(), 'title' => \get_the_title(), 'type' => $ptype ];
		}
		\wp_reset_postdata();
	}
	return (string) \wp_json_encode( $out );
}
