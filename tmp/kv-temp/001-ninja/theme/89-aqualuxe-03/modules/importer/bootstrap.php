<?php
/** Demo content importer - admin UI and endpoints (skeleton, safe defaults) */
namespace AquaLuxe\Modules\Importer;
if ( ! defined( 'ABSPATH' ) ) { exit; }

const PAGE_SLUG = 'aqualuxe_importer';

\add_action( 'admin_menu', function () {
	if ( \function_exists( 'add_management_page' ) ) {
		\call_user_func( 'add_management_page', \__( 'AquaLuxe Importer', 'aqualuxe' ), \__( 'AquaLuxe Importer', 'aqualuxe' ), 'manage_options', PAGE_SLUG, __NAMESPACE__ . '\\render_page' );
	}
} );

function render_page() {
	if ( isset( $_POST['alx_action'] ) && \check_admin_referer( 'alx_importer', '_alx_nonce' ) ) {
			$unslasher = \function_exists('wp_unslash') ? 'wp_unslash' : null;
			$raw = $unslasher ? (string) \call_user_func( $unslasher, $_POST['alx_action'] ) : (string) $_POST['alx_action'];
			$action = \sanitize_text_field( $raw );
		if ( 'flush' === $action ) {
			flush_demo();
			if ( \function_exists( 'add_settings_error' ) ) { \call_user_func( 'add_settings_error', 'aqualuxe_importer', 'flushed', \__( 'All demo content flushed.', 'aqualuxe' ), 'updated' ); }
		} elseif ( 'import' === $action ) {
			$summary = run_import( [ 'products' => true, 'pages' => true, 'posts' => true, 'cpts' => true ] );
			if ( \function_exists( 'add_settings_error' ) ) { \call_user_func( 'add_settings_error', 'aqualuxe_importer', 'imported', \sprintf( \__( 'Imported: %s', 'aqualuxe' ), \esc_html( \wp_json_encode( $summary ) ) ), 'updated' ); }
		}
	}
	if ( \function_exists( 'settings_errors' ) ) { \call_user_func( 'settings_errors', 'aqualuxe_importer' ); }
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'AquaLuxe Demo Importer', 'aqualuxe' ); ?></h1>
		<form method="post">
			<?php \wp_nonce_field( 'alx_importer', '_alx_nonce' ); ?>
			<p><button class="button button-primary" name="alx_action" value="import"><?php esc_html_e( 'Run Import', 'aqualuxe' ); ?></button>
			<button class="button" name="alx_action" value="flush" onclick="return confirm('Flush all demo content?');"><?php esc_html_e( 'Flush Demo', 'aqualuxe' ); ?></button></p>
		</form>
	</div>
	<?php
}

function flush_demo() {
	// Danger: In real world, precise tracking via meta to delete only demo entities.
	$types = [ 'service', 'event', 'testimonial', 'post', 'page', 'product' ];
	foreach ( $types as $type ) {
		$q = new \WP_Query( [ 'post_type' => $type, 'post_status' => 'any', 'posts_per_page' => -1, 'meta_key' => '_alx_demo', 'meta_value' => '1' ] );
		while ( $q->have_posts() ) { $q->the_post(); \wp_delete_post( get_the_ID(), true ); }
		\wp_reset_postdata();
	}
}

function run_import( array $opts = [] ): array {
	$created = [ 'pages' => 0, 'posts' => 0, 'services' => 0, 'events' => 0, 'testimonials' => 0, 'products' => 0 ];
	// Pages
	$pages = [
		'home'   => [ 'title' => 'Home', 'content' => '[alx_cta label="Shop Now" url="/shop"]' ],
		'about'  => [ 'title' => 'About', 'content' => 'Company history and sustainability.' ],
		'services' => [ 'title' => 'Services', 'content' => 'Aquarium design, maintenance, quarantine, breeding, consultation.' ],
		'contact'  => [ 'title' => 'Contact', 'content' => '[alx_contact_form]' ],
	];
	foreach ( $pages as $slug => $data ) {
		$id = \wp_insert_post( [ 'post_title' => $data['title'], 'post_name' => $slug, 'post_type' => 'page', 'post_status' => 'publish', 'post_content' => $data['content'], 'meta_input' => [ '_alx_demo' => '1' ] ] );
		if ( $id && ! \is_wp_error( $id ) ) { $created['pages']++; }
	}

	// Set static front page if created
	$home = \get_page_by_path( 'home' );
	if ( $home ) {
		\update_option( 'show_on_front', 'page' );
		\update_option( 'page_on_front', $home->ID );
	}

	// Ensure menus
	$primary_menu = \wp_get_nav_menu_object( 'Primary' );
	if ( ! $primary_menu ) { $menu_id = \wp_create_nav_menu( 'Primary' ); } else { $menu_id = $primary_menu->term_id; }
	if ( $menu_id && ! \is_wp_error( $menu_id ) ) {
		$home_id = $home ? $home->ID : 0;
		if ( $home_id ) { \wp_update_nav_menu_item( $menu_id, 0, [ 'menu-item-title' => 'Home', 'menu-item-object' => 'page', 'menu-item-object-id' => $home_id, 'menu-item-type' => 'post_type', 'menu-item-status' => 'publish' ] ); }
		\set_theme_mod( 'nav_menu_locations', array_merge( (array) \get_theme_mod( 'nav_menu_locations', [] ), [ 'primary' => (int) $menu_id ] ) );
	}
	// CPT samples
	$svc = \wp_insert_post( [ 'post_title' => 'Aquarium Design & Installation', 'post_type' => 'service', 'post_status' => 'publish', 'post_content' => 'Custom aquariums for homes, offices, hotels.', 'meta_input' => [ '_alx_demo' => '1' ] ] );
	if ( $svc && ! \is_wp_error( $svc ) ) { $created['services']++; }
	$ev = \wp_insert_post( [ 'post_title' => 'Aquascaping Competition', 'post_type' => 'event', 'post_status' => 'publish', 'post_content' => 'Join our annual event.', 'meta_input' => [ '_alx_demo' => '1' ] ] );
	if ( $ev && ! \is_wp_error( $ev ) ) { $created['events']++; }
	$ts = \wp_insert_post( [ 'post_title' => 'Luxe Hotel Dubai', 'post_type' => 'testimonial', 'post_status' => 'publish', 'post_content' => 'Flawless installation and service.', 'meta_input' => [ '_alx_demo' => '1' ] ] );
	if ( $ts && ! \is_wp_error( $ts ) ) { $created['testimonials']++; }
	// WooCommerce: categories + sample products
	if ( class_exists( '\\WC_Product_Simple' ) ) {
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
			}
		}
	}
	return $created;
}
