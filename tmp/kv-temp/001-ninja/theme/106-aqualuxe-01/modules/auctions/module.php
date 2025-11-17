<?php
/** Auctions CPT Module */

declare( strict_types=1 );

namespace AquaLuxe\Modules\Auctions;

use AquaLuxe\Core\Base_Module;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Module extends Base_Module {
	public function id(): string { return 'auctions'; }

	public function register(): void { add_action( 'init', [ $this, 'register_cpt' ] ); }
	public function boot(): void {}

	public function register_cpt(): void {
		$labels = [ 'name' => __( 'Auctions', 'aqualuxe' ), 'singular_name' => __( 'Auction', 'aqualuxe' ) ];
		$args = [
			'labels' => $labels,
			'public' => true,
			'has_archive' => true,
			'show_in_rest' => true,
			'supports' => [ 'title', 'editor', 'excerpt', 'thumbnail', 'comments', 'revisions' ],
			'menu_icon' => 'dashicons-hammer'
		];
		register_post_type( 'aqlx_auction', $args );
	}
}
