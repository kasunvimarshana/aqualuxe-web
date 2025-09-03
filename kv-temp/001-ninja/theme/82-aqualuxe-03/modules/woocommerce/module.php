<?php
// WooCommerce-specific tweaks. Only load if WooCommerce exists.
if ( class_exists( 'WooCommerce' ) ) {
	add_theme_support( 'woocommerce' );
	// Quick view/wishlist modules can hook later.
}
