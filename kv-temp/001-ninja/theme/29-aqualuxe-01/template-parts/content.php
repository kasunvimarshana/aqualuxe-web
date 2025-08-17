<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('post-card mb-8'); ?>>
	<div class="overflow-hidden transition-all duration-300">
		<?php if ( has_post_thumbnail() ) : ?>
			<div class="post-thumbnail mb-4">
				<a href="<?php the_permalink(); ?>" class="block overflow-hidden rounded-lg">
					<?php the_post_thumbnail( 'large', array( 'class' => 'w-full h-auto hover:scale-105 transition-transform duration-500' ) ); ?>
				</a>
			</div>
		<?php endif; ?>

		<div class="p-6">
			<header class="entry-header mb-4">
				<?php
				if ( is_singular() ) :
					the_title( '<h1 class="entry-title text-3xl md:text-4xl font-serif font-medium mb-4">', '</h1>' );
				else :
					the_title( '<h2 class="entry-title text-xl md:text-2xl font-serif font-medium mb-2"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-200">', '</a></h2>' );
				endif;

				if ( 'post' === get_post_type() ) :
					?>
					<div class="post-meta flex items-center text-sm text-dark-500 dark:text-dark-300 space-x-4 mb-4">
						<div class="post-author flex items-center">
							<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
							</svg>
							<?php aqualuxe_posted_by(); ?>
						</div>
						
						<div class="post-date flex items-center">
							<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
							</svg>
							<?php aqualuxe_posted_on(); ?>
						</div>
						
						<?php if ( has_category() ) : ?>
							<div class="post-categories flex items-center">
								<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
								</svg>
								<?php aqualuxe_post_categories(); ?>
							</div>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</header><!-- .entry-header -->

			<div class="entry-content prose dark:prose-invert max-w-none">
				<?php
				if ( is_singular() ) :
					the_content(
						sprintf(
							wp_kses(
								/* translators: %s: Name of current post. Only visible to screen readers */
								__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'aqualuxe' ),
								array(
									'span' => array(
										'class' => array(),
									),
								)
							),
							wp_kses_post( get_the_title() )
						)
					);

					wp_link_pages(
						array(
							'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'aqualuxe' ),
							'after'  => '</div>',
						)
					);
				else :
					the_excerpt();
				?>
					<div class="mt-4">
						<a href="<?php the_permalink(); ?>" class="btn btn-primary">
							<?php esc_html_e( 'Read More', 'aqualuxe' ); ?>
							<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
							</svg>
						</a>
					</div>
				<?php
				endif;
				?>
			</div><!-- .entry-content -->

			<?php if ( is_singular() ) : ?>
				<footer class="entry-footer mt-8 pt-6 border-t border-gray-200 dark:border-dark-600">
					<?php aqualuxe_entry_footer(); ?>
				</footer><!-- .entry-footer -->
			<?php endif; ?>
		</div>
	</div>
</article><!-- #post-<?php the_ID(); ?> -->