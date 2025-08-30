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

	<main id="primary" class="site-main container mx-auto px-4 py-8">

		<?php
		while ( have_posts() ) :
			the_post();

			// Display breadcrumbs if function exists
			if ( function_exists( 'aqualuxe_breadcrumbs' ) ) :
				aqualuxe_breadcrumbs();
			endif;
			?>

			<div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
				<div class="lg:col-span-8">
					<?php
					get_template_part( 'template-parts/content/content', get_post_type() );

					// If comments are open or we have at least one comment, load up the comment template.
					if ( comments_open() || get_comments_number() ) :
						comments_template();
					endif;
					?>

					<div class="post-navigation border-t border-b border-gray-200 dark:border-dark-700 py-6 my-8">
						<div class="flex flex-col sm:flex-row justify-between">
							<div class="prev-post mb-4 sm:mb-0">
								<?php
								$prev_post = get_previous_post();
								if ( ! empty( $prev_post ) ) :
									?>
									<span class="block text-sm text-gray-500 dark:text-gray-400 mb-1"><?php esc_html_e( 'Previous Post', 'aqualuxe' ); ?></span>
									<a href="<?php echo esc_url( get_permalink( $prev_post->ID ) ); ?>" class="text-lg font-medium text-dark-800 dark:text-white hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-200">
										<?php echo esc_html( get_the_title( $prev_post->ID ) ); ?>
									</a>
								<?php endif; ?>
							</div>
							
							<div class="next-post text-right">
								<?php
								$next_post = get_next_post();
								if ( ! empty( $next_post ) ) :
									?>
									<span class="block text-sm text-gray-500 dark:text-gray-400 mb-1"><?php esc_html_e( 'Next Post', 'aqualuxe' ); ?></span>
									<a href="<?php echo esc_url( get_permalink( $next_post->ID ) ); ?>" class="text-lg font-medium text-dark-800 dark:text-white hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-200">
										<?php echo esc_html( get_the_title( $next_post->ID ) ); ?>
									</a>
								<?php endif; ?>
							</div>
						</div>
					</div>

					<?php
					// Related posts section
					if ( function_exists( 'aqualuxe_related_posts' ) ) :
						aqualuxe_related_posts();
					endif;
					?>
				</div>

				<div class="lg:col-span-4">
					<?php get_sidebar(); ?>
				</div>
			</div>

		<?php endwhile; // End of the loop. ?>

	</main><!-- #main -->

<?php
get_footer();