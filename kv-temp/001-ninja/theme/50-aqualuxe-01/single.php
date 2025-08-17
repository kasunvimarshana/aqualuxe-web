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
			<div class="content-sidebar">
				<div class="content-area">
					<?php
					while ( have_posts() ) :
						the_post();

						get_template_part( 'templates/content/content', 'single' );

						the_post_navigation(
							array(
								'prev_text' => '<span class="nav-subtitle">' . esc_html__( 'Previous:', 'aqualuxe' ) . '</span> <span class="nav-title">%title</span>',
								'next_text' => '<span class="nav-subtitle">' . esc_html__( 'Next:', 'aqualuxe' ) . '</span> <span class="nav-title">%title</span>',
							)
						);

						// If comments are open or we have at least one comment, load up the comment template.
						if ( comments_open() || get_comments_number() ) :
							comments_template();
						endif;

					endwhile; // End of the loop.
					?>
				</div><!-- .content-area -->
				
				<div class="widget-area">
					<?php get_sidebar(); ?>
				</div><!-- .widget-area -->
			</div><!-- .content-sidebar -->
		</div><!-- .container -->
	</main><!-- #main -->

<?php
get_footer();