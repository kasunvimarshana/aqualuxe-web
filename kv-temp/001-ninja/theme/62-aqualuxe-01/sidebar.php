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

// Get sidebar class
$sidebar_class = aqualuxe_get_sidebar_class();
?>

<aside id="secondary" class="<?php echo esc_attr( $sidebar_class ); ?>">
	<?php dynamic_sidebar( 'sidebar-1' ); ?>
</aside><!-- #secondary -->