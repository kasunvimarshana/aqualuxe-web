<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if (!is_active_sidebar('sidebar-1') || is_page_template('templates/full-width.php')) {
    return;
}
?>

<aside id="secondary" class="widget-area">
    <div class="sidebar-inner p-6 bg-white dark:bg-gray-800 rounded-lg shadow-md">
        <?php dynamic_sidebar('sidebar-1'); ?>
    </div>
</aside><!-- #secondary -->