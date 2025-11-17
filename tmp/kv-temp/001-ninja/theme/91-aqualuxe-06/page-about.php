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

			get_template_part( 'template-parts/pages/about/company-history' );
			get_template_part( 'template-parts/pages/about/mission-vision-values' );
			get_template_part( 'template-parts/pages/about/team' );
		endwhile; // End of the loop.
		?>

	</main><!-- #main -->

<?php
get_footer();
