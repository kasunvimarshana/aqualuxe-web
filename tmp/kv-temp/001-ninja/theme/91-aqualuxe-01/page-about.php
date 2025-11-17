<?php
/**
 * The template for displaying the about page.
 *
 * Template Name: About Page
 *
 * @package AquaLuxe
 */

get_header();
?>

	<main id="primary" class="site-main">

		<?php
		while ( have_posts() ) :
			the_post();

			get_template_part( 'templates/partials/content', 'page' );

            // Company History Section
            // Mission, Vision, Values Section
            // Team Section

		endwhile; // End of the loop.
		?>

	</main><!-- #main -->

<?php
get_footer();
