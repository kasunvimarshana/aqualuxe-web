<?php
namespace AquaLuxe\Admin;

class Admin {
	public static function init() : void {
		add_action( 'admin_menu', [ __CLASS__, 'menu' ] );
	add_action( 'admin_notices', [ __CLASS__, 'maybe_show_build_notice' ] );
	}

	public static function menu() : void {
		add_menu_page(
			__( 'AquaLuxe', 'aqualuxe' ),
			'AquaLuxe',
			'manage_options',
			'aqualuxe',
			[ __CLASS__, 'render' ],
			'dashicons-water',
			3
		);
	}

	public static function render() : void {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		echo '<div class="wrap"><h1>' . esc_html__( 'AquaLuxe Controls', 'aqualuxe' ) . '</h1>';
		echo '<p>' . esc_html__( 'Manage theme modules, importer, and settings.', 'aqualuxe' ) . '</p>';
		echo '</div>';
	}

	public static function maybe_show_build_notice() : void {
		if ( ! current_user_can('manage_options') ) return;
		$manifest = trailingslashit( get_template_directory() ) . 'assets/dist/mix-manifest.json';
		if ( ! file_exists( $manifest ) ) {
			echo '<div class="notice notice-warning"><p>' . esc_html__( 'AquaLuxe assets not built. Run npm install && npm run build inside the theme folder to generate assets/dist.', 'aqualuxe' ) . '</p></div>';
		}
	}
}
