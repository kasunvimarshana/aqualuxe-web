<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if ( ! is_active_sidebar( 'sidebar-1' ) ) {
	return;
}

// Get the layout setting
$layout = get_theme_mod( 'aqualuxe_layout', 'right-sidebar' );

// Set the sidebar class based on the layout
$sidebar_class = 'right-sidebar' === $layout ? 'widget-area sidebar-right' : 'widget-area sidebar-left';
?>

<aside id="secondary" class="<?php echo esc_attr( $sidebar_class ); ?>">
	<?php dynamic_sidebar( 'sidebar-1' ); ?>
</aside><!-- #secondary -->