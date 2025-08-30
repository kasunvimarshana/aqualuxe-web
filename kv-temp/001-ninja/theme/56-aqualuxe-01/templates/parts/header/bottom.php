<?php
/**
 * Template part for displaying the header bottom
 *
 * @package AquaLuxe
 */

// Check if header bottom is enabled.
$show_header_bottom = get_theme_mod( 'aqualuxe_show_header_bottom', true );
if ( ! $show_header_bottom ) {
	return;
}
?>

<div class="header-bottom bg-gray-100 dark:bg-dark-700">
	<div class="container mx-auto px-4">
		<?php
		/**
		 * Hook: aqualuxe_header_bottom_content.
		 *
		 * @hooked aqualuxe_breadcrumbs - 10
		 */
		do_action( 'aqualuxe_header_bottom_content' );
		?>
	</div>
</div>