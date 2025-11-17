<?php
/**
 * Template part for displaying the footer widgets
 *
 * @package AquaLuxe
 */

// Get footer layout
$footer_layout = get_theme_mod( 'aqualuxe_footer_layout', '4-columns' );

// Set column class based on layout
switch ( $footer_layout ) {
	case '1-column':
		$column_class = 'footer-column col-12';
		$columns = 1;
		break;
	case '2-columns':
		$column_class = 'footer-column col-md-6';
		$columns = 2;
		break;
	case '3-columns':
		$column_class = 'footer-column col-md-4';
		$columns = 3;
		break;
	case '4-columns':
	default:
		$column_class = 'footer-column col-md-3';
		$columns = 4;
		break;
}

// Check if any footer widget area is active
$has_active_sidebar = false;
for ( $i = 1; $i <= $columns; $i++ ) {
	if ( is_active_sidebar( 'footer-' . $i ) ) {
		$has_active_sidebar = true;
		break;
	}
}

// Return if no active sidebar
if ( ! $has_active_sidebar ) {
	return;
}
?>

<div class="footer-widgets">
	<div class="container">
		<div class="footer-widgets-inner row">
			<?php
			// Loop through footer widget areas
			for ( $i = 1; $i <= $columns; $i++ ) {
				if ( is_active_sidebar( 'footer-' . $i ) ) {
					echo '<div class="' . esc_attr( $column_class ) . '">';
					dynamic_sidebar( 'footer-' . $i );
					echo '</div>';
				}
			}
			?>
		</div>
	</div>
</div>