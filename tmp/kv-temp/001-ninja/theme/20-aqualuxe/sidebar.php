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

<aside id="secondary" class="widget-area">
	<?php aqualuxe_before_sidebar(); ?>
	<?php dynamic_sidebar( 'sidebar-1' ); ?>
	<?php aqualuxe_after_sidebar(); ?>
</aside><!-- #secondary -->