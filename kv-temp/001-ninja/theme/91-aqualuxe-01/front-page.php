<?php
/**
 * The template for displaying the front page.
 *
 * @package AquaLuxe
 */

get_header();
?>

	<main id="primary" class="site-main">

		<?php
		// Hero Section
		get_template_part( 'templates/partials/front-page/hero' );

		// Featured Products/Services Section
		get_template_part( 'templates/partials/front-page/featured' );

		// Testimonials Section
		get_template_part( 'templates/partials/front-page/testimonials' );

		// Newsletter Signup Section
		get_template_part( 'templates/partials/front-page/newsletter' );

		// Display page content if any
		while ( have_posts() ) :
			the_post();
			the_content();
		endwhile;
		?>

	</main><!-- #main -->

<?php
get_footer();
