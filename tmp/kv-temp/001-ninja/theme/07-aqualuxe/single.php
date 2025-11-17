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
				<nav class="post-navigation mt-8 pt-6 border-t border-gray-200 dark:border-dark-700">
					<h2 class="screen-reader-text"><?php esc_html_e( 'Post navigation', 'aqualuxe' ); ?></h2>
					<div class="flex flex-col md:flex-row justify-between">
						<?php if ( $prev_post ) : ?>
							<div class="post-navigation-prev mb-4 md:mb-0">
								<span class="text-sm text-gray-600 dark:text-gray-400 block mb-1"><?php esc_html_e( 'Previous Post', 'aqualuxe' ); ?></span>
								<a href="<?php echo esc_url( get_permalink( $prev_post->ID ) ); ?>" class="text-lg font-medium text-primary-700 dark:text-primary-400 hover:text-primary-600 dark:hover:text-primary-300 transition-colors duration-300">
									<?php echo esc_html( get_the_title( $prev_post->ID ) ); ?>
								</a>
							</div>
						<?php endif; ?>

						<?php if ( $next_post ) : ?>
							<div class="post-navigation-next text-right">
								<span class="text-sm text-gray-600 dark:text-gray-400 block mb-1"><?php esc_html_e( 'Next Post', 'aqualuxe' ); ?></span>
								<a href="<?php echo esc_url( get_permalink( $next_post->ID ) ); ?>" class="text-lg font-medium text-primary-700 dark:text-primary-400 hover:text-primary-600 dark:hover:text-primary-300 transition-colors duration-300">
									<?php echo esc_html( get_the_title( $next_post->ID ) ); ?>
								</a>
							</div>
						<?php endif; ?>
					</div>
				</nav>
				<?php
			endif;

			// Related posts
			if ( get_theme_mod( 'aqualuxe_enable_related_posts', true ) ) :
				get_template_part( 'templates/parts/related-posts' );
			endif;

		endwhile; // End of the loop.
		?>

	</main><!-- #main -->

<?php
get_sidebar();
get_footer();