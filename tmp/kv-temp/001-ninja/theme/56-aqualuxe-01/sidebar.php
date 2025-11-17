<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package AquaLuxe
 */

if ( ! is_active_sidebar( 'sidebar-1' ) && ! is_active_sidebar( 'shop-sidebar' ) ) {
	return;
}
?>

<aside id="secondary" class="widget-area">
	<?php
	/**
	 * Hook: aqualuxe_sidebar_before.
	 */
	do_action( 'aqualuxe_sidebar_before' );
	?>

	<div class="sidebar-inner bg-white dark:bg-dark-700 rounded-lg shadow-soft p-6">
		<?php
		if ( class_exists( 'WooCommerce' ) && ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ) && is_active_sidebar( 'shop-sidebar' ) ) {
			dynamic_sidebar( 'shop-sidebar' );
		} else {
			dynamic_sidebar( 'sidebar-1' );
		}
		?>
	</div>

	<?php
	/**
	 * Hook: aqualuxe_sidebar_after.
	 */
	do_action( 'aqualuxe_sidebar_after' );
	?>
</aside><!-- #secondary -->