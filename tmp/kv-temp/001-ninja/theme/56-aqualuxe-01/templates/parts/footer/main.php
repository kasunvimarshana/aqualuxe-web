<?php
/**
 * Template part for displaying the footer main
 *
 * @package AquaLuxe
 */

// Get footer style.
$footer_style = get_theme_mod( 'aqualuxe_footer_style', 'default' );
?>

<div class="footer-main py-12 bg-dark-800 text-light-500">
	<div class="container mx-auto px-4">
		<?php
		/**
		 * Hook: aqualuxe_footer_widgets.
		 *
		 * @hooked aqualuxe_footer_widgets - 10
		 */
		do_action( 'aqualuxe_footer_widgets' );
		?>
	</div>
</div>