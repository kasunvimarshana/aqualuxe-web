<?php
/**
 * The sidebar containing the product widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package AquaLuxe
 */

// Exit if WooCommerce is not active
if ( ! function_exists( 'aqualuxe_is_woocommerce_active' ) || ! aqualuxe_is_woocommerce_active() ) {
	return;
}

// Exit if the sidebar is not active
if ( ! is_active_sidebar( 'product-sidebar' ) ) {
	return;
}
?>

<aside id="product-sidebar" class="widget-area lg:col-span-4">
	<div class="sticky top-6">
		<?php dynamic_sidebar( 'product-sidebar' ); ?>
	</div>
</aside><!-- #product-sidebar -->