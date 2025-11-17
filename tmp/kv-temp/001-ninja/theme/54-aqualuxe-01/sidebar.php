<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if (!is_active_sidebar('sidebar-1')) {
    return;
}
?>

<aside id="secondary" class="widget-area w-full lg:w-1/3 px-4 mt-8 lg:mt-0">
    <?php dynamic_sidebar('sidebar-1'); ?>
</aside><!-- #secondary -->