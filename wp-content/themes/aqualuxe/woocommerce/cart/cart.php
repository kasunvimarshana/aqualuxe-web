<?php
defined( 'ABSPATH' ) || exit;
if ( function_exists( 'do_action' ) ) {
	call_user_func( 'do_action', 'woocommerce_before_cart' );
}
// Force plugin template to avoid recursion into this override
if ( function_exists( 'wc_get_template' ) && defined( 'WC_PLUGIN_FILE' ) ) {
	$base             = dirname( constant( 'WC_PLUGIN_FILE' ) );
	$plugin_templates = rtrim( $base, '/\\' ) . '/templates/';
	call_user_func( 'wc_get_template', 'cart/cart.php', array(), '', $plugin_templates );
} else {
	echo '<div class="container mx-auto px-4 py-8">Cart unavailable.</div>';
}
if ( function_exists( 'do_action' ) ) {
	call_user_func( 'do_action', 'woocommerce_after_cart' );
}
