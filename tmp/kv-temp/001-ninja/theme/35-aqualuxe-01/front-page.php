<?php
/**
 * The template for displaying the front page
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

			if ( 'page' === get_post_type() ) {
				get_template_part( 'templates/parts/content', 'page' );
			} else {
				get_template_part( 'templates/parts/content', get_post_type() );
			}

			/**
			 * Hook: aqualuxe_after_page_content.
			 *
			 * @hooked none
			 */
			do_action( 'aqualuxe_after_page_content' );

			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

		endwhile; // End of the loop.
		?>

	</main><!-- #main -->

<?php
get_footer();