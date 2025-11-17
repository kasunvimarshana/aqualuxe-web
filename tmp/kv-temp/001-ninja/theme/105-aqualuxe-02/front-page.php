<?php
/**
 * The template for displaying the front page
 *
 * This is the template that displays the front page or home page.
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

get_header(); ?>

<main id="primary" class="site-main">

	<?php
	while ( have_posts() ) :
		the_post();

		get_template_part( 'template-parts/content/content', 'front-page' );

	endwhile; // End of the loop.
	?>

</main><!-- #main -->

<?php
get_footer();