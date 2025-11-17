<?php
/**
 * The sidebar containing the shop widget area
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
if ( ! is_active_sidebar( 'shop-sidebar' ) ) {
	return;
}
?>

<aside id="shop-sidebar" class="widget-area lg:col-span-4">
	<div class="sticky top-6">
		<?php dynamic_sidebar( 'shop-sidebar' ); ?>
	</div>
</aside><!-- #shop-sidebar -->