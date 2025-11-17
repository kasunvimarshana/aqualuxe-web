<?php
/**
 * Template part for displaying the header top
 *
 * @package AquaLuxe
 */

// Check if header top is enabled.
$show_header_top = get_theme_mod( 'aqualuxe_show_header_top', true );
if ( ! $show_header_top ) {
	return;
}
?>

<div class="header-top bg-dark-800 text-light-500 py-2 hidden md:block">
	<div class="container mx-auto px-4">
		<div class="flex justify-between items-center">
			<div class="header-top-left">
				<?php
				/**
				 * Hook: aqualuxe_header_top_left.
				 *
				 * @hooked aqualuxe_header_contact_info - 10
				 */
				do_action( 'aqualuxe_header_top_left' );
				?>
			</div>
			<div class="header-top-right flex items-center">
				<?php
				/**
				 * Hook: aqualuxe_header_top_right.
				 *
				 * @hooked aqualuxe_header_social_icons - 10
				 * @hooked aqualuxe_header_account_links - 20
				 */
				do_action( 'aqualuxe_header_top_right' );
				?>
			</div>
		</div>
	</div>
</div>