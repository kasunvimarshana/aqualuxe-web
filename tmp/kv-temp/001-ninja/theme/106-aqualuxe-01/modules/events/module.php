<?php
/** Events CPT Module */

declare( strict_types=1 );

namespace AquaLuxe\Modules\Events;

use AquaLuxe\Core\Base_Module;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Module extends Base_Module {
	public function id(): string { return 'events'; }

	public function register(): void {
		add_action( 'init', [ $this, 'register_cpt' ] );
	}
	public function boot(): void {}

	public function register_cpt(): void {
		$labels = [ 'name' => __( 'Events', 'aqualuxe' ), 'singular_name' => __( 'Event', 'aqualuxe' ) ];
		$args = [
			'labels' => $labels,
			'public' => true,
			'has_archive' => true,
			'show_in_rest' => true,
			'supports' => [ 'title', 'editor', 'excerpt', 'thumbnail', 'revisions' ],
			'menu_icon' => 'dashicons-calendar'
		];
		register_post_type( 'aqlx_event', $args );
	}
}
