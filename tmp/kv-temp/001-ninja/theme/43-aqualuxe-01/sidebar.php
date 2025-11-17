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

// If we're on a WooCommerce page and the shop sidebar is active, use that instead
if ( class_exists( 'WooCommerce' ) && is_woocommerce() && is_active_sidebar( 'shop-sidebar' ) ) {
    $sidebar_id = 'shop-sidebar';
} else {
    $sidebar_id = 'sidebar-1';
}
?>

<aside id="secondary" class="widget-area sidebar">
    <?php dynamic_sidebar( $sidebar_id ); ?>
</aside><!-- #secondary -->