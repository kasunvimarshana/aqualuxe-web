<?php
/**
 * The sidebar containing the shop widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if (!aqualuxe_is_woocommerce_active() || !is_active_sidebar('sidebar-shop')) {
    return;
}
?>

<aside id="secondary" class="widget-area shop-sidebar">
    <?php dynamic_sidebar('sidebar-shop'); ?>
</aside><!-- #secondary -->