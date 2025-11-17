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

// Check if we're on a WooCommerce page and the shop sidebar is active
$is_shop_sidebar = aqualuxe_is_woocommerce_active() && 
                  (is_shop() || is_product_category() || is_product_tag() || is_product_taxonomy()) && 
                  is_active_sidebar( 'shop-sidebar' );

$sidebar_id = $is_shop_sidebar ? 'shop-sidebar' : 'sidebar-1';
?>

<aside id="secondary" class="widget-area bg-white p-6 rounded-lg shadow-sm">
	<?php dynamic_sidebar( $sidebar_id ); ?>
</aside><!-- #secondary -->