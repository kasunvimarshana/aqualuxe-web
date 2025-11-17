<?php
/**
 * Template part for displaying posts in archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'archive-post bg-white dark:bg-dark-800 rounded-lg shadow-soft overflow-hidden transition-shadow duration-300 hover:shadow-medium' ); ?>>
	<?php if ( has_post_thumbnail() ) : ?>
		<div class="entry-thumbnail">
			<a href="<?php the_permalink(); ?>" class="block aspect-w-16 aspect-h-9 overflow-hidden">
				<?php the_post_thumbnail( 'medium_large', array( 'class' => 'w-full h-full object-cover transition-transform duration-300 hover:scale-105' ) ); ?>
			</a>
		</div>
	<?php endif; ?>

	<div class="entry-content p-5">
		<header class="entry-header mb-3">
			<?php
			the_title( '<h2 class="entry-title text-xl font-serif font-bold text-gray-900 dark:text-gray-100 mb-2"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-300">', '</a></h2>' );

			if ( 'post' === get_post_type() ) :
				?>
				<div class="entry-meta flex flex-wrap items-center text-xs text-gray-600 dark:text-gray-400">
					<?php
					aqualuxe_posted_on();
					aqualuxe_comment_count();
					?>
				</div><!-- .entry-meta -->
			<?php endif; ?>
		</header><!-- .entry-header -->

		<div class="entry-summary text-gray-700 dark:text-gray-300 text-sm mb-4">
			<?php
			if ( get_theme_mod( 'aqualuxe_archive_content_type', 'excerpt' ) === 'excerpt' ) {
				the_excerpt();
			} else {
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
			}
			?>
		</div><!-- .entry-summary -->

		<footer class="entry-footer flex items-center justify-between">
			<div class="post-categories text-xs">
				<?php aqualuxe_post_categories(); ?>
			</div>
			<div class="read-more">
				<a href="<?php the_permalink(); ?>" class="inline-flex items-center text-sm text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 font-medium transition-colors duration-300">
					<?php esc_html_e( 'Read More', 'aqualuxe' ); ?>
					<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
					</svg>
				</a>
			</div>
		</footer><!-- .entry-footer -->
	</div>
</article><!-- #post-<?php the_ID(); ?> -->