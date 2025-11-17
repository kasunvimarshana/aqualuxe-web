<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

get_header();
?>

	<main id="primary" class="site-main">

		<?php
		while ( have_posts() ) :
			the_post();

			get_template_part( 'templates/parts/content', 'page' );

			/**
			 * Hook: aqualuxe_after_page_content.
			 *
			 * @hooked none
			 */
			do_action( 'aqualuxe_after_page_content' );

			/**
			 * Hook: aqualuxe_comments_before.
			 *
			 * @hooked none
			 */
			do_action( 'aqualuxe_comments_before' );

			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

			/**
			 * Hook: aqualuxe_comments_after.
			 *
			 * @hooked none
			 */
			do_action( 'aqualuxe_comments_after' );

		endwhile; // End of the loop.
		?>

	</main><!-- #main -->

<?php
get_footer();