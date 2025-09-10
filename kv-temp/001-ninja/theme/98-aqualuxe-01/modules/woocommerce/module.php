<?php
namespace AquaLuxe\Module\Woocommerce;

final class Module {
	public static function init(): void {
		if ( ! class_exists( 'WooCommerce' ) ) {
			return; // Dual-state architecture: gracefully skip when WC not active.
		}
		\add_action( 'after_setup_theme', function(){ \add_theme_support( 'woocommerce' ); }, 5 );
		\add_filter( 'woocommerce_enqueue_styles', '__return_false' ); // Use theme styles only.
	}
}
