<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package AquaLuxe
 */

if ( ! is_active_sidebar( 'sidebar-1' ) ) {
	return;
}

// Check if we're on a WooCommerce page
$sidebar = aqualuxe_is_woocommerce_active() && ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ) ? 'shop-sidebar' : 'sidebar-1';

// Check if the sidebar is active
if ( ! is_active_sidebar( $sidebar ) ) {
	return;
}
?>

<aside id="secondary" class="widget-area">
	<?php dynamic_sidebar( $sidebar ); ?>
</aside><!-- #secondary -->