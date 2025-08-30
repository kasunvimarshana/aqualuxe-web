<?php
/**
 * Template part for displaying the footer bottom
 *
 * @package AquaLuxe
 */

// Get footer style.
$footer_style = get_theme_mod( 'aqualuxe_footer_style', 'default' );
?>

<div class="footer-bottom py-4 bg-dark-900 text-light-500 text-sm">
	<div class="container mx-auto px-4">
		<?php if ( 'centered' === $footer_style ) : ?>
			<div class="flex flex-col items-center">
				<?php
				/**
				 * Hook: aqualuxe_footer_copyright.
				 *
				 * @hooked aqualuxe_footer_copyright - 10
				 */
				do_action( 'aqualuxe_footer_copyright' );
				?>
				
				<?php
				/**
				 * Hook: aqualuxe_footer_menu.
				 *
				 * @hooked aqualuxe_footer_menu - 10
				 */
				do_action( 'aqualuxe_footer_menu' );
				?>
			</div>
		<?php elseif ( 'minimal' === $footer_style ) : ?>
			<div class="flex flex-col md:flex-row justify-between items-center">
				<?php
				/**
				 * Hook: aqualuxe_footer_copyright.
				 *
				 * @hooked aqualuxe_footer_copyright - 10
				 */
				do_action( 'aqualuxe_footer_copyright' );
				?>
			</div>
		<?php else : // Default footer style. ?>
			<div class="flex flex-col md:flex-row justify-between items-center">
				<?php
				/**
				 * Hook: aqualuxe_footer_copyright.
				 *
				 * @hooked aqualuxe_footer_copyright - 10
				 */
				do_action( 'aqualuxe_footer_copyright' );
				?>
				
				<?php
				/**
				 * Hook: aqualuxe_footer_menu.
				 *
				 * @hooked aqualuxe_footer_menu - 10
				 */
				do_action( 'aqualuxe_footer_menu' );
				?>
			</div>
		<?php endif; ?>
	</div>
</div>