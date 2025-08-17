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
?>

<aside id="secondary" class="widget-area bg-white dark:bg-dark-800 p-6 rounded-lg shadow-sm border border-gray-100 dark:border-dark-700">
	<?php dynamic_sidebar( 'sidebar-1' ); ?>
</aside><!-- #secondary -->