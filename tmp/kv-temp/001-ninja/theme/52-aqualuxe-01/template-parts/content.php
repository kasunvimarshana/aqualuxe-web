<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('card bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden mb-8'); ?>>
	<?php if ( has_post_thumbnail() ) : ?>
		<div class="post-thumbnail">
			<a href="<?php the_permalink(); ?>">
				<?php the_post_thumbnail( 'medium_large', array( 'class' => 'w-full h-auto' ) ); ?>
			</a>
		</div>
	<?php endif; ?>

	<div class="card-body p-6">
		<header class="entry-header mb-4">
			<?php
			if ( is_singular() ) :
				the_title( '<h1 class="entry-title text-2xl font-bold">', '</h1>' );
			else :
				the_title( '<h2 class="entry-title text-xl font-bold"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark" class="hover:text-primary transition-colors">', '</a></h2>' );
			endif;

			if ( 'post' === get_post_type() ) :
				?>
				<div class="entry-meta text-sm text-gray-600 dark:text-gray-400 mt-2">
					<?php
					aqualuxe_posted_on();
					aqualuxe_posted_by();
					?>
				</div><!-- .entry-meta -->
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
				<p class="mt-4">
					<a href="<?php the_permalink(); ?>" class="btn btn-primary">
						<?php esc_html_e( 'Read More', 'aqualuxe' ); ?>
					</a>
				</p>
			<?php endif; ?>
		</div><!-- .entry-content -->

		<footer class="entry-footer mt-4 text-sm text-gray-600 dark:text-gray-400">
			<?php aqualuxe_entry_footer(); ?>
		</footer><!-- .entry-footer -->
	</div>
</article><!-- #post-<?php the_ID(); ?> -->