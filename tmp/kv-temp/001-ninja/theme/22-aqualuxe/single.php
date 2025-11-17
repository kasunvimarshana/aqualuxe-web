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

	<main id="primary" class="site-main py-8">
		<div class="container-fluid max-w-screen-xl mx-auto">
			<div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
				<div class="lg:col-span-8">
					<?php
					while ( have_posts() ) :
						the_post();

						get_template_part( 'template-parts/content/content', 'single' );

						// Previous/next post navigation.
						the_post_navigation(
							array(
								'prev_text' => '<span class="nav-subtitle text-sm font-medium text-gray-500">' . esc_html__( 'Previous:', 'aqualuxe' ) . '</span> <span class="nav-title text-lg font-bold">%title</span>',
								'next_text' => '<span class="nav-subtitle text-sm font-medium text-gray-500">' . esc_html__( 'Next:', 'aqualuxe' ) . '</span> <span class="nav-title text-lg font-bold">%title</span>',
								'class'     => 'post-navigation flex flex-col md:flex-row justify-between py-6 my-8 border-t border-b border-gray-200 dark:border-gray-700',
							)
						);

						// If comments are open or we have at least one comment, load up the comment template.
						if ( comments_open() || get_comments_number() ) :
							comments_template();
						endif;

					endwhile; // End of the loop.
					?>
				</div>
				
				<div class="lg:col-span-4">
					<?php get_sidebar(); ?>
				</div>
			</div>
		</div>
	</main><!-- #main -->

<?php
get_footer();