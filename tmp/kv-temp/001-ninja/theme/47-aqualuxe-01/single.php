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
		<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
			<div class="lg:col-span-2">
				<?php
				while ( have_posts() ) :
					the_post();

					get_template_part( 'templates/content', 'single' );

					// If comments are open or we have at least one comment, load up the comment template.
					if ( comments_open() || get_comments_number() ) :
						comments_template();
					endif;

					// Post navigation
					$prev_post = get_previous_post();
					$next_post = get_next_post();

					if ( $prev_post || $next_post ) :
					?>
						<nav class="post-navigation my-8 border-t border-b border-gray-200 py-4">
							<div class="flex flex-wrap justify-between">
								<?php if ( $prev_post ) : ?>
									<div class="post-navigation-prev mb-4 md:mb-0">
										<span class="text-sm text-gray-500 block mb-1"><?php esc_html_e( 'Previous Post', 'aqualuxe' ); ?></span>
										<a href="<?php echo esc_url( get_permalink( $prev_post->ID ) ); ?>" class="font-medium hover:text-primary-600 transition-colors">
											<?php echo esc_html( get_the_title( $prev_post->ID ) ); ?>
										</a>
									</div>
								<?php endif; ?>

								<?php if ( $next_post ) : ?>
									<div class="post-navigation-next text-right">
										<span class="text-sm text-gray-500 block mb-1"><?php esc_html_e( 'Next Post', 'aqualuxe' ); ?></span>
										<a href="<?php echo esc_url( get_permalink( $next_post->ID ) ); ?>" class="font-medium hover:text-primary-600 transition-colors">
											<?php echo esc_html( get_the_title( $next_post->ID ) ); ?>
										</a>
									</div>
								<?php endif; ?>
							</div>
						</nav>
					<?php endif; ?>

					<?php
					// Related posts
					if ( function_exists( 'aqualuxe_related_posts' ) ) :
						aqualuxe_related_posts();
					endif;
					?>

				<?php endwhile; // End of the loop. ?>
			</div>

			<div class="sidebar-container">
				<?php get_sidebar(); ?>
			</div>
		</div>
	</main><!-- #main -->

<?php
get_footer();