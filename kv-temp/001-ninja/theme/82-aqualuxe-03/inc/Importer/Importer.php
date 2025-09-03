<?php
namespace AquaLuxe\Importer;

class Importer {
	private array $created = [];
	private array $created_menu_items = [];
	private ?int $created_menu_id = null;
	private array $counts = [
		'pages'    => 0,
		'services' => 0,
		'events'   => 0,
		'products' => 0,
		'menus'    => 0,
		'media'    => 0,
		'users'    => 0,
		'classifieds' => 0,
	'widgets'  => 0,
	];

	public function run( array $opts ) : void {
		if ( ! empty( $opts['flush'] ) ) { $this->flush( $opts ); }
		if ( ! empty( $opts['pages'] ) ) { $this->pages(); }
		if ( ! empty( $opts['services'] ) ) { $this->services(); }
		if ( ! empty( $opts['events'] ) ) { $this->events(); }
		if ( ! empty( $opts['media'] ) ) { $this->media(); }
		if ( ! empty( $opts['users'] ) ) { $this->users(); }
		if ( ! empty( $opts['products'] ) && class_exists('WooCommerce') ) { $this->products(); }
		if ( ! empty( $opts['classifieds'] ) ) { $this->classifieds(); }
	if ( ! empty( $opts['widgets'] ) ) { $this->widgets(); }
		if ( ! empty( $opts['menus'] ) ) { $this->menus(); }
		$this->set_front_page();

		// Persist a ledger of created items for potential rollback.
		$ledger = [
			'posts'      => array_values(array_filter(array_map('intval', $this->created))),
			'menu_items' => array_values(array_filter(array_map('intval', $this->created_menu_items))),
			'menu_id'    => $this->created_menu_id ? (int) $this->created_menu_id : 0,
			'ts'         => time(),
			'counts'     => $this->counts,
		];
		update_option( 'alx_last_import_ledger', $ledger );
	}

	// Initialize transient ledger (for stepwise AJAX importer)
	public function init_from_ledger( array $ledger ) : void {
		$this->created = isset($ledger['posts']) && is_array($ledger['posts']) ? array_map('intval',$ledger['posts']) : [];
		$this->created_menu_items = isset($ledger['menu_items']) && is_array($ledger['menu_items']) ? array_map('intval',$ledger['menu_items']) : [];
		$this->created_menu_id = ! empty($ledger['menu_id']) ? (int)$ledger['menu_id'] : null;
	}

	public function get_ledger() : array {
		return [
			'posts'      => array_values(array_unique(array_filter(array_map('intval', $this->created)))),
			'menu_items' => array_values(array_unique(array_filter(array_map('intval', $this->created_menu_items)))),
			'menu_id'    => $this->created_menu_id ? (int) $this->created_menu_id : 0,
			'ts'         => time(),
			'counts'     => $this->counts,
		];
	}

	// Execute a single step and return a small status payload
	public function run_step( string $step, array $opts ) : array {
		$step = trim(strtolower($step));
        // Dry run: do not perform any write operations.
        if ( ! empty($opts['dry']) ) {
        	return [
        	    'ok' => true,
        	    'step' => $step,
        	    'counts' => [ 'posts' => count($this->created), 'menu_items' => count($this->created_menu_items) ],
        	    'counts_by_type' => $this->counts,
        	    'has_wc' => class_exists('WooCommerce'),
        	];
        }
		switch ( $step ) {
			case 'flush':
                if ( ! empty($opts['flush']) ) { $this->flush( $opts ); }
				break;
			case 'media':
				if ( ! empty($opts['media']) ) { $this->media(); }
				break;
			case 'users':
				if ( ! empty($opts['users']) ) { $this->users(); }
				break;
			case 'pages':
				if ( ! empty($opts['pages']) ) { $this->pages(); }
				break;
			case 'services':
				if ( ! empty($opts['services']) ) { $this->services(); }
				break;
			case 'events':
				if ( ! empty($opts['events']) ) { $this->events(); }
				break;
			case 'products':
				if ( ! empty($opts['products']) && class_exists('WooCommerce') ) { $this->products(); }
				break;
			case 'classifieds':
				if ( ! empty($opts['classifieds']) ) { $this->classifieds(); }
				break;
			case 'widgets':
				if ( ! empty($opts['widgets']) ) { $this->widgets(); }
				break;
			case 'menus':
				if ( ! empty($opts['menus']) ) { $this->menus(); }
				break;
			case 'frontpage':
				$this->set_front_page();
				break;
			case 'finalize':
				// No-op; handled outside (controller persists final ledger)
				break;
			default:
				// Unknown step; no-op
				break;
		}
		return [
			'ok' => true,
			'step' => $step,
			'counts' => [
				'posts' => count($this->created),
				'menu_items' => count($this->created_menu_items),
			],
			'counts_by_type' => $this->counts,
			'has_wc' => class_exists('WooCommerce'),
		];
	}

	private function widgets() : void {
		// Add a simple text widget and a recent posts widget if missing; mark as demo for rollback
		$sidebars = get_option('sidebars_widgets', []);
		if ( ! is_array($sidebars) ) { $sidebars = []; }
		$primary = isset($sidebars['sidebar-1']) && is_array($sidebars['sidebar-1']) ? $sidebars['sidebar-1'] : [];

		// Text widget
		$text_opt = get_option('widget_text', []);
		if ( ! is_array($text_opt) ) { $text_opt = []; }
		$existing_text = false;
		foreach ( $text_opt as $inst ) {
			if ( is_array($inst) && isset($inst['title']) && $inst['title'] === 'About AquaLuxe' ) { $existing_text = true; break; }
		}
		if ( ! $existing_text ) {
			// Compute next numeric index safely
			$next_idx = 1;
			foreach ( array_keys($text_opt) as $k ) { if ( is_numeric($k) ) { $next_idx = max($next_idx, ((int)$k)+1); } }
			$text_opt[$next_idx] = [ 'title' => 'About AquaLuxe', 'text' => 'Premium livestock, plants, equipment & services.', 'filter' => true, 'aqlx_demo' => 1 ];
			update_option('widget_text', $text_opt);
			$inst_id = 'text-' . $next_idx;
			if ( ! in_array($inst_id, $primary, true) ) { $primary[] = $inst_id; }
			$this->counts['widgets']++;
		}

		// Recent posts
		$rp_opt = get_option('widget_recent-posts', []);
		if ( ! is_array($rp_opt) ) { $rp_opt = []; }
		$has_rp = false;
		foreach ( $rp_opt as $inst ) { if ( is_array($inst) && isset($inst['aqlx_demo']) && (int)$inst['aqlx_demo'] === 1 ) { $has_rp = true; break; } }
		if ( ! $has_rp ) {
			$rp_idx = 1;
			foreach ( array_keys($rp_opt) as $k ) { if ( is_numeric($k) ) { $rp_idx = max($rp_idx, ((int)$k)+1); } }
			$rp_opt[$rp_idx] = [ 'title' => 'Latest', 'number' => 5, 'aqlx_demo' => 1 ];
			update_option('widget_recent-posts', $rp_opt);
			$rp_id = 'recent-posts-' . $rp_idx;
			if ( ! in_array($rp_id, $primary, true) ) { $primary[] = $rp_id; }
			$this->counts['widgets']++;
		}

		$sidebars['sidebar-1'] = $primary;
		update_option('sidebars_widgets', $sidebars);
	}

	private function flush( array $opts ) : void {
		// Safer: do not delete 'post' (blog posts)
		$types = [ 'page','service','event','classified' ];
		if ( class_exists('WooCommerce') && ! empty( $opts['products'] ) ) { $types[] = 'product'; }
		foreach ( $types as $t ) {
			$q = new \WP_Query([ 'post_type'=>$t, 'posts_per_page'=>-1, 'post_status'=>'any', 'fields'=>'ids' ]);
			foreach ( $q->posts as $id ) { wp_delete_post( $id, true ); }
		}
		// Only delete demo attachments we previously created (flagged with _alx_demo=1)
		$mq = new \WP_Query([
			'post_type' => 'attachment',
			'posts_per_page' => -1,
			'post_status' => 'any',
			'fields' => 'ids',
			'meta_key' => '_alx_demo',
			'meta_value' => '1',
		]);
		foreach ( $mq->posts as $mid ) { wp_delete_post( (int)$mid, true ); }

		// Remove demo widgets (marked with aqlx_demo=1) when flushing
		$to_remove = [];
		foreach ( [ 'text', 'recent-posts' ] as $base ) {
			$opt_key = 'widget_' . $base;
			$opts = get_option( $opt_key, [] );
			if ( is_array($opts) ) {
				foreach ( $opts as $idx => $cfg ) {
					if ( is_numeric($idx) && is_array($cfg) && ! empty($cfg['aqlx_demo']) ) {
						unset($opts[$idx]);
						$to_remove[] = $base . '-' . (int)$idx;
					}
				}
				update_option( $opt_key, $opts );
			}
		}
		if ( ! empty($to_remove) ) {
			$sidebars = get_option('sidebars_widgets', []);
			if ( is_array($sidebars) ) {
				foreach ( $sidebars as $sid => $arr ) {
					if ( $sid === 'wp_inactive_widgets' ) continue;
					if ( is_array($arr) ) { $sidebars[$sid] = array_values( array_diff( $arr, $to_remove ) ); }
				}
				update_option('sidebars_widgets', $sidebars);
			}
		}
	}

	private function classifieds() : void {
		if ( ! post_type_exists('classified') ) return;
		$items = [
			['Rare Tang – Trade or Sell','Healthy tang, captive-bred. Looking to trade for SPS frags or sell.'],
			['Shrimp Colony','Cherry shrimp colony, mixed ages. Pickup preferred.'],
			['Custom Nano Tank','10-gallon low-iron tank with LED and filter, lightly used.'],
		];
		foreach ($items as $it){
			$existing = $this->find_existing_by_title('classified', $it[0]);
			$is_new = $existing ? false : true;
			$id = $existing ?: wp_insert_post([
				'post_title'=>$it[0], 'post_type'=>'classified', 'post_status'=>'publish', 'post_content'=>$it[1]
			]);
			if ( $id && ! is_wp_error($id) ) {
				if ( $is_new ) { $this->created[] = (int)$id; $this->counts['classifieds']++; }
				if ( ! get_post_meta($id,'_alx_price', true) ) { update_post_meta($id,'_alx_price', '$' . rand(20,200)); }
				if ( ! get_post_meta($id,'_alx_contact', true) ) { update_post_meta($id,'_alx_contact', 'seller@example.com'); }
			}
		}
	}

	private function media() : void {
		// Attach a couple of local demo images if present
		$img_dir = trailingslashit( get_template_directory() ) . 'assets/src/images';
		$files = glob( $img_dir . '/*.{jpg,jpeg,png,webp}', GLOB_BRACE );
		if ( ! $files ) return;
		require_once ABSPATH . 'wp-admin/includes/image.php';
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/media.php';
		foreach ( array_slice($files,0,5) as $path ) {
			$fname = sanitize_file_name( basename($path) );
			$existing = get_posts([
				'post_type' => 'attachment',
				'post_status' => 'inherit',
				'posts_per_page' => 50,
				'meta_key' => '_alx_demo',
				'meta_value' => '1',
			]);
			$dup = false;
			foreach ( $existing as $eid ) {
				$att = get_post( is_object($eid) ? $eid->ID : (int)$eid );
				if ( $att && isset($att->post_title) && $att->post_title === $fname ) { $dup = true; break; }
			}
			if ( $dup ) { continue; }
			$bits = file_get_contents($path);
			if ( ! $bits ) continue;
			$upload = wp_upload_bits( basename($path), null, $bits );
			if ( ! empty($upload['error']) ) continue;
			$attachment = [
				'post_mime_type' => wp_check_filetype( $upload['file'] )['type'] ?? 'image/jpeg',
				'post_title' => sanitize_file_name( basename($path) ),
				'post_content' => '',
				'post_status' => 'inherit',
			];
			$att_id = wp_insert_attachment( $attachment, $upload['file'] );
			if ( is_wp_error($att_id) || ! $att_id ) continue;
			wp_update_attachment_metadata( $att_id, wp_generate_attachment_metadata( $att_id, $upload['file'] ) );
			update_post_meta( $att_id, '_alx_demo', '1' );
			$this->created[] = (int)$att_id; $this->counts['media']++;
		}
	}

	private function users() : void {
		// Create demo customer and vendor if not exists
		$has_shop_manager = get_role('shop_manager') !== null;
		$vendor_role = $has_shop_manager ? 'shop_manager' : ( get_role('editor') ? 'editor' : 'author' );
		$defs = [
			[ 'alx_customer', 'customer@example.com', 'Customer', 'subscriber' ],
			[ 'alx_vendor', 'vendor@example.com', 'Vendor', $vendor_role ],
		];
		foreach ($defs as $def){
			list($login,$email,$name,$role) = $def;
			$u = get_user_by('login', $login);
			if ( $u ) continue;
			$uid = wp_insert_user([
				'user_login' => $login,
				'user_pass'  => wp_generate_password(12, true),
				'user_email' => $email,
				'role'       => $role,
				'display_name'=> $name,
			]);
			if ( ! is_wp_error($uid) && $uid ) { $this->counts['users']++; }
		}
	}

	private function create_page( string $title, string $content='' ) : int {
		$existing_id = $this->find_existing_by_title('page', $title);
		if ( $existing_id ) return (int)$existing_id;
		$id = wp_insert_post([
			'post_title' => $title,
			'post_type' => 'page',
			'post_status' => 'publish',
			'post_content' => $content,
		]);
	if ( $id && ! is_wp_error( $id ) ) { $this->created[] = (int)$id; }
	if ( $id && ! is_wp_error( $id ) ) { $this->counts['pages']++; }
	return (int)$id;
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
			$existing = $this->find_existing_by_title('service', $it[0]);
			$is_new = $existing ? false : true;
			$id = $existing ?: wp_insert_post([
				'post_title'=>$it[0], 'post_type'=>'service', 'post_status'=>'publish', 'post_content'=>$it[1]
			]);
			if ( $id && ! is_wp_error( $id ) ) { if ( $is_new ) { $this->created[] = (int)$id; $this->counts['services']++; } }
		}
	}

	private function events() : void {
		$items = [
			['Aquascaping Workshop','Hands-on scaping with experts.'],
			['Livestock Auction','Bidding for premium specimens.'],
		];
		foreach ($items as $it){
			$existing = $this->find_existing_by_title('event', $it[0]);
			$is_new = $existing ? false : true;
			$id = $existing ?: wp_insert_post([
				'post_title'=>$it[0], 'post_type'=>'event', 'post_status'=>'publish', 'post_content'=>$it[1]
			]);
			if ( $id && ! is_wp_error( $id ) ) { if ( $is_new ) { $this->created[] = (int)$id; $this->counts['events']++; } }
		}
	}

	private function products() : void {
		if ( ! function_exists('wc_get_product') ) return;
		$cats = [ 'Rare Fish','Aquatic Plants','Premium Equipment','Care Supplies' ];
		$term_ids=[]; foreach($cats as $c){ $t= term_exists($c,'product_cat'); if(!$t){ $t=wp_insert_term($c,'product_cat'); } $term_ids[] = (int)($t['term_id'] ?? $t); }
		for ($i=1; $i<=8; $i++){
			$class = '\\WC_Product_Simple';
			if ( ! class_exists( $class ) ) { continue; }
			$title = 'AquaLuxe Product ' . $i;
			$existing = $this->find_existing_by_title('product', $title);
			if ( $existing ) { $id = $existing; $is_new = false; }
			else {
				$is_new = true;
				$p = new $class();
				$p->set_name( $title );
				$p->set_regular_price( (string) (10 + $i*3) );
				$p->set_catalog_visibility('visible');
				$p->set_manage_stock(false);
				$p->set_status('publish');
				$id = $p->save();
			}
			wp_set_object_terms( $id, [ $term_ids[$i % count($term_ids)] ], 'product_cat' );
			if ( $id && $is_new ) { $this->created[] = (int)$id; $this->counts['products']++; }
		}

		// Add one variable product with attributes/variations if it doesn't exist
		$var_title = 'AquaLuxe Reef Kit';
		$existing_var = $this->find_existing_by_title('product', $var_title);
		if ( ! $existing_var ) {
			$varClass = '\\WC_Product_Variable';
			$attrClass = '\\WC_Product_Attribute';
			$variationClass = '\\WC_Product_Variation';
			if ( class_exists($varClass) && class_exists($attrClass) && class_exists($variationClass) ) {
				// Ensure attributes exist: Size, Color
				if ( function_exists('wc_attribute_taxonomy_name') ) {
					$attrs = [
						[ 'label' => 'Size', 'slug' => 'size', 'terms' => [ 'Small','Medium','Large' ] ],
						[ 'label' => 'Color', 'slug' => 'color', 'terms' => [ 'Blue','Green' ] ],
					];
					$attr_ids = [];
					foreach ( $attrs as $a ) {
						$tax_name = function_exists('wc_attribute_taxonomy_name')
							? \call_user_func('wc_attribute_taxonomy_name', $a['slug'])
							: 'pa_' . sanitize_title($a['slug']); // safe fallback

						// Create attribute taxonomy if missing
						$taxonomies = function_exists('wc_get_attribute_taxonomies')
							? (array) \call_user_func('wc_get_attribute_taxonomies')
							: [];
						$exists = array_filter($taxonomies, function($o) use ($a){ return isset($o->attribute_name) && $o->attribute_name === $a['slug']; });
						if ( empty($exists) && function_exists('wc_create_attribute') ) {
							\call_user_func('wc_create_attribute', [
								'name' => $a['label'],
								'slug' => $a['slug'],
								'type' => 'select',
								'order_by' => 'menu_order',
								'has_archives' => false,
							]);
							delete_transient('wc_attribute_taxonomies');
						}
						// Ensure terms
						if ( taxonomy_exists( $tax_name ) ) {
							foreach ( $a['terms'] as $term_label ) {
								if ( ! term_exists( $term_label, $tax_name ) ) { wp_insert_term( $term_label, $tax_name ); }
							}
						}
						$attr_ids[$a['slug']] = $tax_name;
					}

					// Build variable product
					$vp = new $varClass();
					$vp->set_name( $var_title );
					$vp->set_status('publish');
					$vp->set_catalog_visibility('visible');
					$vp_id = $vp->save();
					if ( $vp_id ) {
						// Assign product attributes for variation
						$pas = [];
						foreach ( $attrs as $a ) {
							$tax_name = $attr_ids[$a['slug']];
							$attribute = new $attrClass();
							$attribute->set_id( 0 );
							$attribute->set_name( $tax_name );
							// Collect option term IDs for this taxonomy
							$terms = get_terms([ 'taxonomy' => $tax_name, 'hide_empty' => false, 'fields' => 'ids' ]);
							$attribute->set_options( array_map('intval', is_array($terms) ? $terms : []) );
							$attribute->set_visible( true );
							$attribute->set_variation( true );
							$pas[] = $attribute;
						}
						$vp->set_attributes( $pas );
						$vp->save();

						// Create variations: all combinations
						$sizes = [ 'Small','Medium','Large' ];
						$colors = [ 'Blue','Green' ];
						foreach ( $sizes as $si ) {
							foreach ( $colors as $co ) {
								$variation = new $variationClass();
								$variation->set_parent_id( $vp_id );
								$variation->set_attributes([
									$attr_ids['size'] => sanitize_title( $si ),
									$attr_ids['color'] => sanitize_title( $co ),
								]);
								$price = ( $si === 'Small' ? 99 : ( $si === 'Medium' ? 139 : 179 ) );
								$variation->set_regular_price( (string) $price );
								$variation->set_manage_stock( false );
								$variation->set_status('publish');
								$variation->save();
							}
						}
						if ( function_exists('wc_delete_product_transients') ) { \call_user_func('wc_delete_product_transients', $vp_id ); }
						if ( method_exists($vp,'save') ) { $vp->save(); }
						$this->created[] = (int)$vp_id; $this->counts['products']++;
					}
				}
			}
		}
	}

	// Utility: find an existing post by sanitized title/slug for the given post type
	private function find_existing_by_title( string $post_type, string $title ) : ?int {
		$slug = sanitize_title( $title );
		$post = get_page_by_path( $slug, OBJECT, $post_type );
		if ( $post && ! is_wp_error($post) ) { return (int)$post->ID; }
		$posts = get_posts([
			'post_type' => $post_type,
			'name' => $slug,
			'posts_per_page' => 1,
			'post_status' => 'any',
			'fields' => 'ids',
		]);
		if ( $posts ) { return (int)$posts[0]; }
		return null;
	}

	private function menus() : void {
		// Reuse existing "Primary" menu if present to avoid duplication.
		$existing = term_exists( 'Primary', 'nav_menu' );
		if ( $existing && ! is_wp_error( $existing ) ) {
			$menu_id = (int) ( is_array($existing) ? ($existing['term_id'] ?? 0) : $existing );
		} else {
			$menu_id = (int) wp_create_nav_menu('Primary');
			if ( $menu_id > 0 ) { $this->created_menu_id = $menu_id; }
		}
		$home_id = $this->find_existing_by_title('page','Home');
		$about_id = $this->find_existing_by_title('page','About');
		$shop_url = function_exists('wc_get_page_permalink') ? \wc_get_page_permalink('shop') : home_url('/');
		$existing_items = wp_get_nav_menu_items( $menu_id ) ?: [];
		$has_home = false; $has_about = false; $has_shop = false;
		foreach ( $existing_items as $mi ) {
			if ( $home_id && $mi->object === 'page' && (int)$mi->object_id === (int)$home_id ) { $has_home = true; }
			if ( $about_id && $mi->object === 'page' && (int)$mi->object_id === (int)$about_id ) { $has_about = true; }
			if ( ! empty($mi->url) && untrailingslashit($mi->url) === untrailingslashit($shop_url) ) { $has_shop = true; }
		}
		if ($home_id && ! $has_home) {
			$item_id = wp_update_nav_menu_item( $menu_id, 0, [ 'menu-item-title'=>'Home', 'menu-item-object'=>'page','menu-item-object-id'=>$home_id,'menu-item-type'=>'post_type','menu-item-status'=>'publish' ] );
			if ( $item_id && ! is_wp_error( $item_id ) ) { $this->created_menu_items[] = (int)$item_id; $this->counts['menus']++; }
		}
		if ($about_id && ! $has_about) {
			$item_id = wp_update_nav_menu_item( $menu_id, 0, [ 'menu-item-title'=>'About', 'menu-item-object'=>'page','menu-item-object-id'=>$about_id,'menu-item-type'=>'post_type','menu-item-status'=>'publish' ] );
			if ( $item_id && ! is_wp_error( $item_id ) ) { $this->created_menu_items[] = (int)$item_id; $this->counts['menus']++; }
		}
		if ( ! $has_shop ) {
			$shop_item_id = wp_update_nav_menu_item( $menu_id, 0, [ 'menu-item-title'=>'Shop', 'menu-item-url'=>$shop_url, 'menu-item-status'=>'publish' ] );
			if ( $shop_item_id && ! is_wp_error( $shop_item_id ) ) { $this->created_menu_items[] = (int)$shop_item_id; $this->counts['menus']++; }
		}
		set_theme_mod( 'nav_menu_locations', array_merge( (array) get_theme_mod('nav_menu_locations'), [ 'primary'=>$menu_id ] ) );
	}

	private function set_front_page() : void {
		$home_id = $this->find_existing_by_title('page','Home'); if ( ! $home_id ) return;
		update_option('show_on_front','page');
		update_option('page_on_front', (int)$home_id );
	}
}
