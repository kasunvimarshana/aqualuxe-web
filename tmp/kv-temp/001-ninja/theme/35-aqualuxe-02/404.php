<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package AquaLuxe
 */

get_header();
?>

	<main id="primary" class="site-main">

		<?php
		/**
		 * Hook: aqualuxe_404_header.
		 *
		 * @hooked aqualuxe_404_header_content - 10
		 */
		do_action( 'aqualuxe_404_header' );
		?>

		<?php
		/**
		 * Hook: aqualuxe_404_content.
		 *
		 * @hooked aqualuxe_404_content_output - 10
		 */
		do_action( 'aqualuxe_404_content' );
		?>

	</main><!-- #main -->

<?php
get_footer();