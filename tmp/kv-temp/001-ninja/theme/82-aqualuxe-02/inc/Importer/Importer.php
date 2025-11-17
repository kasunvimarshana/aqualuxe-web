<?php
namespace AquaLuxe\Importer;

class Importer {
	private array $created = [];

	public function run( array $opts ) : void {
		if ( ! empty( $opts['flush'] ) ) { $this->flush( $opts ); }
		if ( ! empty( $opts['pages'] ) ) { $this->pages(); }
		if ( ! empty( $opts['services'] ) ) { $this->services(); }
		if ( ! empty( $opts['events'] ) ) { $this->events(); }
		if ( ! empty( $opts['products'] ) && class_exists('WooCommerce') ) { $this->products(); }
		if ( ! empty( $opts['menus'] ) ) { $this->menus(); }
		$this->set_front_page();
	}

	private function flush( array $opts ) : void {
		$types = [ 'page','post','service','event' ];
		if ( class_exists('WooCommerce') && ! empty( $opts['products'] ) ) { $types[] = 'product'; }
		foreach ( $types as $t ) {
			$q = new \WP_Query([ 'post_type'=>$t, 'posts_per_page'=>-1, 'post_status'=>'any', 'fields'=>'ids' ]);
			foreach ( $q->posts as $id ) { wp_delete_post( $id, true ); }
		}
	}

	private function create_page( string $title, string $content='' ) : int {
		$existing = get_page_by_title( $title );
		if ( $existing ) return (int)$existing->ID;
		$id = wp_insert_post([
			'post_title' => $title,
			'post_type' => 'page',
			'post_status' => 'publish',
			'post_content' => $content,
		]);
		$this->created[] = $id; return (int)$id;
	}

	private function pages() : void {
		$this->create_page('Home', '<!-- wp:paragraph --><p>Welcome to AquaLuxe.</p><!-- /wp:paragraph -->');
		$this->create_page('About', '<p>Our mission: Bringing elegance to aquatic life – globally.</p>');
		$this->create_page('Services', '<p>Design, installation, maintenance, quarantine, training.</p>');
		$this->create_page('Blog', '');
	$this->create_page('Contact', '[alx_contact_form]');
		$this->create_page('FAQ', '<p>Shipping, care, exports and returns.</p>');
		$this->create_page('Privacy Policy','');
		$this->create_page('Terms & Conditions','');
		$this->create_page('Shipping & Returns','');
		$this->create_page('Cookie Policy','');
	}

	private function services() : void {
		$items = [
			['Aquarium Design & Installation','Custom luxury aquariums for residences and hotels.'],
			['Maintenance Services','Scheduled cleaning and water quality management.'],
			['Quarantine & Health','Disease prevention for export and retail.'],
			['Training & Consultancy','Hobbyist training and aquaculture consultancy.'],
		];
		foreach ($items as $it){
			wp_insert_post([
				'post_title'=>$it[0], 'post_type'=>'service', 'post_status'=>'publish', 'post_content'=>$it[1]
			]);
		}
	}

	private function events() : void {
		$items = [
			['Aquascaping Workshop','Hands-on scaping with experts.'],
			['Livestock Auction','Bidding for premium specimens.'],
		];
		foreach ($items as $it){
			wp_insert_post([
				'post_title'=>$it[0], 'post_type'=>'event', 'post_status'=>'publish', 'post_content'=>$it[1]
			]);
		}
	}

	private function products() : void {
		if ( ! function_exists('wc_get_product') ) return;
		$cats = [ 'Rare Fish','Aquatic Plants','Premium Equipment','Care Supplies' ];
		$term_ids=[]; foreach($cats as $c){ $t= term_exists($c,'product_cat'); if(!$t){ $t=wp_insert_term($c,'product_cat'); } $term_ids[] = (int)($t['term_id'] ?? $t); }
		for ($i=1; $i<=8; $i++){
			$p = new \WC_Product_Simple();
			$p->set_name( 'AquaLuxe Product ' . $i );
			$p->set_regular_price( (string) (10 + $i*3) );
			$p->set_catalog_visibility('visible');
			$p->set_manage_stock(false);
			$p->set_status('publish');
			$id = $p->save();
			wp_set_object_terms( $id, [ $term_ids[$i % count($term_ids)] ], 'product_cat' );
		}
	}

	private function menus() : void {
		$menu_id = wp_create_nav_menu('Primary');
		$home = get_page_by_title('Home');
		$about = get_page_by_title('About');
		$shop_url = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/');
		if ($home) wp_update_nav_menu_item( $menu_id, 0, [ 'menu-item-title'=>'Home', 'menu-item-object'=>'page','menu-item-object-id'=>$home->ID,'menu-item-type'=>'post_type','menu-item-status'=>'publish' ] );
		if ($about) wp_update_nav_menu_item( $menu_id, 0, [ 'menu-item-title'=>'About', 'menu-item-object'=>'page','menu-item-object-id'=>$about->ID,'menu-item-type'=>'post_type','menu-item-status'=>'publish' ] );
		wp_update_nav_menu_item( $menu_id, 0, [ 'menu-item-title'=>'Shop', 'menu-item-url'=>$shop_url, 'menu-item-status'=>'publish' ] );
		set_theme_mod( 'nav_menu_locations', array_merge( (array) get_theme_mod('nav_menu_locations'), [ 'primary'=>$menu_id ] ) );
	}

	private function set_front_page() : void {
		$home = get_page_by_title('Home'); if ( ! $home ) return;
		update_option('show_on_front','page');
		update_option('page_on_front', (int)$home->ID );
	}
}
