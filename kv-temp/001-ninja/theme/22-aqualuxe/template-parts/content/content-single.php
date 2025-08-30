<?php
/**
 * Template part for displaying single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header mb-8">
		<?php the_title( '<h1 class="entry-title text-4xl font-serif font-bold mb-4">', '</h1>' ); ?>
		
		<div class="entry-meta flex flex-wrap items-center text-sm text-gray-600 dark:text-gray-400">
			<?php
			aqualuxe_posted_by();
			aqualuxe_posted_on();
			?>
			
			<?php if ( get_comments_number() ) : ?>
				<span class="comments-link ml-4 flex items-center">
					<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
					</svg>
					<?php comments_popup_link(); ?>
				</span>
			<?php endif; ?>
		</div><!-- .entry-meta -->
	</header><!-- .entry-header -->

	<?php if ( has_post_thumbnail() ) : ?>
		<div class="entry-thumbnail mb-8">
			<div class="overflow-hidden rounded-lg">
				<?php the_post_thumbnail( 'aqualuxe-featured', array( 'class' => 'w-full h-auto' ) ); ?>
				
				<?php if ( $caption = get_the_post_thumbnail_caption() ) : ?>
					<div class="thumbnail-caption text-sm text-gray-600 dark:text-gray-400 mt-2 italic text-center">
						<?php echo wp_kses_post( $caption ); ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	<?php endif; ?>

	<div class="entry-content prose dark:prose-invert max-w-none">
		<?php
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
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
		<?php aqualuxe_entry_footer(); ?>
		
		<?php if ( aqualuxe_get_option( 'show_author_bio', true ) && get_the_author_meta( 'description' ) ) : ?>
			<div class="author-bio mt-8 p-6 bg-gray-50 dark:bg-gray-800 rounded-lg">
				<div class="flex items-start">
					<div class="author-avatar mr-4">
						<?php echo get_avatar( get_the_author_meta( 'ID' ), 80, '', '', array( 'class' => 'rounded-full' ) ); ?>
					</div>
					<div class="author-info">
						<h3 class="author-name text-lg font-medium mb-2">
							<?php echo esc_html( get_the_author_meta( 'display_name' ) ); ?>
						</h3>
						<div class="author-description text-gray-600 dark:text-gray-400">
							<?php echo wpautop( get_the_author_meta( 'description' ) ); ?>
						</div>
						<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" class="author-link inline-flex items-center mt-3 text-primary-500 hover:text-primary-600 transition-colors duration-200">
							<?php
							/* translators: %s: Author name */
							printf( esc_html__( 'View all posts by %s', 'aqualuxe' ), esc_html( get_the_author() ) );
							?>
							<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 20 20" fill="currentColor">
								<path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
							</svg>
						</a>
					</div>
				</div>
			</div>
		<?php endif; ?>
		
		<?php
		// If comments are open or we have at least one comment, load up the comment template.
		if ( comments_open() || get_comments_number() ) :
			comments_template();
		endif;
		?>
	</footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->