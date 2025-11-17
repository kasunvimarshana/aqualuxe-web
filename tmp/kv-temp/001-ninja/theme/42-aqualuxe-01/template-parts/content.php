<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'mb-12 pb-12 border-b border-gray-200 last:border-0 last:mb-0 last:pb-0' ); ?>>
	<header class="entry-header mb-6">
		<?php
		if ( is_singular() ) :
			the_title( '<h1 class="entry-title text-3xl font-bold text-primary-800 mb-3">', '</h1>' );
		else :
			the_title( '<h2 class="entry-title text-2xl font-bold text-primary-800 mb-3"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark" class="hover:text-primary-600 transition-colors">', '</a></h2>' );
		endif;

		if ( 'post' === get_post_type() ) :
			?>
			<div class="entry-meta flex flex-wrap items-center text-sm text-gray-600 mb-4">
				<?php
				aqualuxe_posted_by();
				aqualuxe_posted_on();
				
				// Comments count
				if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
					echo '<span class="comments-link ml-4">';
					echo '<i class="fas fa-comment-alt mr-1"></i>';
					comments_popup_link(
						sprintf(
							wp_kses(
								/* translators: %s: post title */
								__( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'aqualuxe' ),
								array(
									'span' => array(
										'class' => array(),
									),
								)
							),
							wp_kses_post( get_the_title() )
						)
					);
					echo '</span>';
				}
				
				// Edit link
				edit_post_link(
					sprintf(
						wp_kses(
							/* translators: %s: Name of current post. Only visible to screen readers */
							__( 'Edit <span class="screen-reader-text">%s</span>', 'aqualuxe' ),
							array(
								'span' => array(
									'class' => array(),
								),
							)
						),
						wp_kses_post( get_the_title() )
					),
					'<span class="edit-link ml-4"><i class="fas fa-edit mr-1"></i>',
					'</span>'
				);
				?>
			</div><!-- .entry-meta -->
		<?php endif; ?>
	</header><!-- .entry-header -->

	<?php if ( has_post_thumbnail() ) : ?>
		<div class="entry-thumbnail mb-6">
			<a href="<?php the_permalink(); ?>" class="block overflow-hidden rounded-lg shadow-lg">
				<?php the_post_thumbnail( 'large', array( 'class' => 'w-full h-auto hover:scale-105 transition-transform duration-300' ) ); ?>
			</a>
		</div>
	<?php endif; ?>

	<div class="entry-content prose max-w-none">
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
					'before' => '<div class="page-links mt-6 pt-6 border-t border-gray-200">' . esc_html__( 'Pages:', 'aqualuxe' ),
					'after'  => '</div>',
				)
			);
		else :
			the_excerpt();
			?>
			<div class="read-more mt-4">
				<a href="<?php the_permalink(); ?>" class="inline-flex items-center text-primary-600 hover:text-primary-800 font-medium transition-colors">
					<?php esc_html_e( 'Read More', 'aqualuxe' ); ?> <i class="fas fa-arrow-right ml-2"></i>
				</a>
			</div>
		<?php endif; ?>
	</div><!-- .entry-content -->

	<?php if ( is_singular() ) : ?>
		<footer class="entry-footer mt-8 pt-6 border-t border-gray-200">
			<?php aqualuxe_entry_footer(); ?>
		</footer><!-- .entry-footer -->
	<?php endif; ?>
</article><!-- #post-<?php the_ID(); ?> -->