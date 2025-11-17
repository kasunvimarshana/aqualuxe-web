<?php
/** Services CPT Module */

declare( strict_types=1 );

namespace AquaLuxe\Modules\Services;

use AquaLuxe\Core\Base_Module;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Module extends Base_Module {
	public function id(): string { return 'services'; }

	public function register(): void {
		add_action( 'init', [ $this, 'register_cpt' ] );
		add_action( 'init', [ $this, 'register_taxonomies' ] );
	}

	public function boot(): void {}

	public function register_cpt(): void {
		$labels = [
			'name' => __( 'Services', 'aqualuxe' ),
			'singular_name' => __( 'Service', 'aqualuxe' )
		];
		$args = [
			'labels' => $labels,
			'public' => true,
			'has_archive' => true,
			'show_in_rest' => true,
			'supports' => [ 'title', 'editor', 'excerpt', 'thumbnail', 'revisions' ],
			'menu_icon' => 'dashicons-admin-tools'
		];
		register_post_type( 'aqlx_service', $args );
	}

	public function register_taxonomies(): void {
		register_taxonomy( 'aqlx_service_type', [ 'aqlx_service' ], [
			'label' => __( 'Service Types', 'aqualuxe' ),
			'hierarchical' => true,
			'show_in_rest' => true,
		] );
	}
}
