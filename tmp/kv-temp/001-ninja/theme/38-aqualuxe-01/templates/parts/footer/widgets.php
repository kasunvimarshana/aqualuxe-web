<?php
/**
 * Template part for displaying the footer widgets
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get the number of footer columns
$footer_columns = get_theme_mod( 'aqualuxe_footer_columns', '4' );

// Check if any footer widget area is active
$has_active_sidebar = false;
for ( $i = 1; $i <= $footer_columns; $i++ ) {
	if ( is_active_sidebar( 'footer-' . $i ) ) {
		$has_active_sidebar = true;
		break;
	}
}

// Return if no footer widget is active
if ( ! $has_active_sidebar ) {
	return;
}
?>

<div class="footer-widgets">
	<div class="container">
		<div class="footer-widgets-inner footer-widgets-columns-<?php echo esc_attr( $footer_columns ); ?>">
			<?php
			for ( $i = 1; $i <= $footer_columns; $i++ ) :
				if ( is_active_sidebar( 'footer-' . $i ) ) :
					?>
					<div class="footer-widget footer-widget-<?php echo esc_attr( $i ); ?>">
						<?php dynamic_sidebar( 'footer-' . $i ); ?>
					</div>
					<?php
				endif;
			endfor;
			?>
		</div>
	</div>
</div><!-- .footer-widgets -->