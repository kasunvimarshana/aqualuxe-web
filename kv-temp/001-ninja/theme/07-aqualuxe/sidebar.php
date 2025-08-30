<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// Check if sidebar should be displayed
$sidebar_position = get_theme_mod( 'aqualuxe_sidebar_position', 'right' );

if ( 'none' === $sidebar_position ) {
	return;
}

// Check if the current page should have a sidebar
if ( is_page() ) {
	$page_layout = get_theme_mod( 'aqualuxe_page_layout', 'full-width' );
	if ( 'full-width' === $page_layout ) {
		return;
	}
}

// Check if we're on a WooCommerce page and should use the shop sidebar
$sidebar_id = 'sidebar-1';
if ( class_exists( 'WooCommerce' ) && ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ) ) {
	$sidebar_id = 'shop-sidebar';
}

// Check if the sidebar is active
if ( ! is_active_sidebar( $sidebar_id ) ) {
	return;
}
?>

<aside id="secondary" class="widget-area">
	<div class="sidebar-inner space-y-8">
		<?php dynamic_sidebar( $sidebar_id ); ?>
	</div>
</aside><!-- #secondary -->