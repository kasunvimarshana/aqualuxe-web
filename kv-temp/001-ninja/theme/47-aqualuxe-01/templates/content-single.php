<?php
/**
 * Template part for displaying single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('single-post'); ?>>
	<header class="entry-header mb-6">
		<?php the_title( '<h1 class="entry-title text-3xl md:text-4xl font-bold mb-4">', '</h1>' ); ?>
		
		<div class="entry-meta flex flex-wrap items-center text-sm text-gray-600 mb-4">
			<?php
			aqualuxe_posted_on();
			aqualuxe_posted_by();
			
			// Estimated reading time
			if ( function_exists( 'aqualuxe_reading_time' ) ) {
				echo '<span class="reading-time ml-4 flex items-center">';
				aqualuxe_svg_icon( 'clock', array( 'class' => 'w-4 h-4 mr-1' ) );
				aqualuxe_reading_time();
				echo '</span>';
			}
			?>
		</div><!-- .entry-meta -->

		<?php if ( has_post_thumbnail() ) : ?>
			<div class="entry-thumbnail mb-6">
				<?php the_post_thumbnail( 'aqualuxe-featured', array( 'class' => 'w-full h-auto rounded-lg' ) ); ?>
				
				<?php if ( $caption = get_the_post_thumbnail_caption() ) : ?>
					<div class="thumbnail-caption text-sm text-gray-600 mt-2 italic text-center">
						<?php echo wp_kses_post( $caption ); ?>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</header><!-- .entry-header -->

	<div class="entry-content prose max-w-none">
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
				'before' => '<div class="page-links mt-6 pt-6 border-t border-gray-200">' . esc_html__( 'Pages:', 'aqualuxe' ),
				'after'  => '</div>',
				'link_before' => '<span class="page-number">',
				'link_after'  => '</span>',
			)
		);
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer mt-8 pt-6 border-t border-gray-200">
		<?php aqualuxe_entry_footer(); ?>
		
		<?php if ( function_exists( 'aqualuxe_post_share_buttons' ) ) : ?>
			<div class="post-share-buttons mt-6">
				<h3 class="text-lg font-medium mb-3"><?php esc_html_e( 'Share this post', 'aqualuxe' ); ?></h3>
				<?php aqualuxe_post_share_buttons(); ?>
			</div>
		<?php endif; ?>
		
		<?php if ( function_exists( 'aqualuxe_author_bio' ) ) : ?>
			<div class="author-bio mt-8 pt-6 border-t border-gray-200">
				<?php aqualuxe_author_bio(); ?>
			</div>
		<?php endif; ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->