<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package AquaLuxe
 */

if (!is_active_sidebar('sidebar-1') && (!class_exists('WooCommerce') || !is_active_sidebar('shop-sidebar') || !is_woocommerce())) {
    return;
}

// Determine which sidebar to display
$sidebar = 'sidebar-1';
if (class_exists('WooCommerce') && is_woocommerce() && is_active_sidebar('shop-sidebar')) {
    $sidebar = 'shop-sidebar';
}
?>

<aside id="secondary" class="widget-area w-full lg:w-1/3 mt-8 lg:mt-0">
    <div class="sidebar-inner">
        <?php dynamic_sidebar($sidebar); ?>
    </div>
</aside><!-- #secondary -->