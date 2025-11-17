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

/**
 * Hook: aqualuxe_before_sidebar.
 *
 * @hooked none
 */
do_action( 'aqualuxe_before_sidebar' );
?>

<aside id="secondary" class="widget-area">
	<?php dynamic_sidebar( 'sidebar-1' ); ?>
</aside><!-- #secondary -->

<?php
/**
 * Hook: aqualuxe_after_sidebar.
 *
 * @hooked none
 */
do_action( 'aqualuxe_after_sidebar' );