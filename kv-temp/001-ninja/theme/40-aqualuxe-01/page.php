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
		<div class="container">
			<?php if ( aqualuxe_has_sidebar() && is_active_sidebar( 'sidebar-1' ) ) : ?>
				<div class="row has-sidebar">
					<div class="col-content">
			<?php else : ?>
				<div class="row">
					<div class="col-full">
			<?php endif; ?>

			<?php
			while ( have_posts() ) :
				the_post();

				get_template_part( 'template-parts/content', 'page' );

				// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;

			endwhile; // End of the loop.
			?>

					</div><!-- .col-content or .col-full -->
					
					<?php if ( aqualuxe_has_sidebar() && is_active_sidebar( 'sidebar-1' ) ) : ?>
						<div class="col-sidebar">
							<?php get_sidebar(); ?>
						</div>
					<?php endif; ?>
				</div><!-- .row -->
		</div><!-- .container -->
	</main><!-- #main -->

<?php
get_footer();