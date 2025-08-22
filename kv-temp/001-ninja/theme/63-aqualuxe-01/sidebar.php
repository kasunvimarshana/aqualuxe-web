<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package AquaLuxe
 */

if (!is_active_sidebar('sidebar-1') && !is_active_sidebar('shop-sidebar')) {
    return;
}
?>

<div id="sidebar" class="widget-area">
    <?php
    if (aqualuxe_is_woocommerce_page() && is_active_sidebar('shop-sidebar')) {
        dynamic_sidebar('shop-sidebar');
    } else {
        dynamic_sidebar('sidebar-1');
    }
    ?>
</div><!-- #sidebar -->