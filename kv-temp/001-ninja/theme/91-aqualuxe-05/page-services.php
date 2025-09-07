<?php
/**
 * The template for displaying the services page.
 *
 * Template Name: Services Page
 *
 * @package AquaLuxe
 */

get_header();
?>

	<main id="primary" class="site-main">

		<?php
		while ( have_posts() ) :
			the_post();
			the_title( '<h1 class="entry-title text-center text-4xl font-bold my-8">', '</h1>' );
			the_content();
		endwhile;
		?>

        <?php get_template_part( 'template-parts/pages/services/services-grid' ); ?>
	</main><!-- #main -->

<?php
get_footer();
