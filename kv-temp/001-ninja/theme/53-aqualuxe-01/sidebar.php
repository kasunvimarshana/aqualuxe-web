<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package AquaLuxe
 */

if ( ! aqualuxe_has_sidebar() ) {
	return;
}
?>

<aside id="secondary" class="<?php echo esc_attr( aqualuxe_get_sidebar_class() ); ?>">
	<?php dynamic_sidebar( 'sidebar-1' ); ?>
</aside><!-- #secondary -->