<?php
/**
 * The sidebar containing the WooCommerce widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package AquaLuxe
 */

if ( ! is_active_sidebar( 'shop' ) ) {
	return;
}

/**
 * Hook: aqualuxe_before_sidebar.
 *
 * @hooked none
 */
do_action( 'aqualuxe_before_sidebar' );
?>

<aside id="secondary" class="widget-area widget-area-shop">
	<?php dynamic_sidebar( 'shop' ); ?>
</aside><!-- #secondary -->

<?php
/**
 * Hook: aqualuxe_after_sidebar.
 *
 * @hooked none
 */
do_action( 'aqualuxe_after_sidebar' );