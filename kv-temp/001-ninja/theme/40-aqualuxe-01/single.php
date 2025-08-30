<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
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

				get_template_part( 'template-parts/content', 'single' );

				aqualuxe_post_navigation();

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