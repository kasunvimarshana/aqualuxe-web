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

// Check if the sidebar should be displayed based on theme options
$sidebar_position = get_theme_mod( 'aqualuxe_sidebar_position', 'right' );
if ( $sidebar_position === 'none' ) {
	return;
}

// Check if the current page has a custom sidebar setting
$disable_sidebar = get_post_meta( get_the_ID(), 'aqualuxe_disable_sidebar', true );
if ( $disable_sidebar === 'yes' ) {
	return;
}
?>

<aside id="secondary" class="widget-area">
	<?php dynamic_sidebar( 'sidebar-1' ); ?>
</aside><!-- #secondary -->