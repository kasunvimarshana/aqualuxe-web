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

	<main id="primary" class="site-main py-12">
		<div class="container mx-auto px-4">
			<?php
			while ( have_posts() ) :
				the_post();

				// Breadcrumbs
				if ( function_exists( 'yoast_breadcrumb' ) ) {
					yoast_breadcrumb( '<div class="breadcrumbs text-sm text-gray-600 mb-6">', '</div>' );
				} else {
					aqualuxe_breadcrumbs();
				}
				?>

				<div class="content-area flex flex-wrap">
					<div class="primary-content w-full lg:w-2/3 lg:pr-8">
						<?php
						get_template_part( 'template-parts/content', 'single' );

						// If comments are open or we have at least one comment, load up the comment template.
						if ( comments_open() || get_comments_number() ) :
							comments_template();
						endif;
						?>

						<div class="post-navigation mt-8 pt-6 border-t border-gray-200">
							<div class="flex flex-wrap justify-between">
								<div class="prev-post w-full md:w-1/2 md:pr-4 mb-4 md:mb-0">
									<?php
									$prev_post = get_previous_post();
									if ( ! empty( $prev_post ) ) :
										?>
										<span class="text-sm text-gray-500 block mb-1"><?php esc_html_e( 'Previous Post', 'aqualuxe' ); ?></span>
										<a href="<?php echo esc_url( get_permalink( $prev_post->ID ) ); ?>" class="text-lg font-medium text-primary-700 hover:text-primary-600 transition-colors">
											<?php echo esc_html( get_the_title( $prev_post->ID ) ); ?>
										</a>
									<?php endif; ?>
								</div>
								<div class="next-post w-full md:w-1/2 md:pl-4 text-left md:text-right">
									<?php
									$next_post = get_next_post();
									if ( ! empty( $next_post ) ) :
										?>
										<span class="text-sm text-gray-500 block mb-1"><?php esc_html_e( 'Next Post', 'aqualuxe' ); ?></span>
										<a href="<?php echo esc_url( get_permalink( $next_post->ID ) ); ?>" class="text-lg font-medium text-primary-700 hover:text-primary-600 transition-colors">
											<?php echo esc_html( get_the_title( $next_post->ID ) ); ?>
										</a>
									<?php endif; ?>
								</div>
							</div>
						</div>

						<?php
						// Related posts
						aqualuxe_related_posts();
						?>
					</div>

					<div class="sidebar w-full lg:w-1/3 mt-8 lg:mt-0">
						<?php get_sidebar(); ?>
					</div>
				</div>

			<?php endwhile; // End of the loop. ?>
		</div>
	</main><!-- #main -->

<?php
get_footer();