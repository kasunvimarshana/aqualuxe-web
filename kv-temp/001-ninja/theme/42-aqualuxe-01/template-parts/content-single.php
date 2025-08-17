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
		<?php the_title( '<h1 class="entry-title text-4xl font-bold text-primary-800 mb-4">', '</h1>' ); ?>
		
		<div class="entry-meta flex flex-wrap items-center text-sm text-gray-600 mb-6">
			<?php
			aqualuxe_posted_by();
			aqualuxe_posted_on();
			
			// Reading time
			$content = get_post_field( 'post_content', get_the_ID() );
			$word_count = str_word_count( strip_tags( $content ) );
			$reading_time = ceil( $word_count / 200 ); // Assuming 200 words per minute reading speed
			?>
			<span class="reading-time ml-4">
				<i class="fas fa-clock mr-1"></i>
				<?php 
				/* translators: %d: Reading time in minutes */
				printf( esc_html( _n( '%d min read', '%d min read', $reading_time, 'aqualuxe' ) ), $reading_time ); 
				?>
			</span>
			
			<?php
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
		
		<?php if ( has_post_thumbnail() ) : ?>
			<div class="entry-thumbnail mb-8">
				<div class="overflow-hidden rounded-lg shadow-lg">
					<?php the_post_thumbnail( 'full', array( 'class' => 'w-full h-auto' ) ); ?>
				</div>
				<?php if ( get_the_post_thumbnail_caption() ) : ?>
					<div class="thumbnail-caption text-sm text-gray-500 mt-2 text-center">
						<?php the_post_thumbnail_caption(); ?>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</header><!-- .entry-header -->

	<div class="entry-content prose prose-lg max-w-none">
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
			)
		);
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer mt-8 pt-6 border-t border-gray-200">
		<div class="post-tags mb-6">
			<?php aqualuxe_entry_footer(); ?>
		</div>
		
		<div class="author-bio bg-gray-50 rounded-lg p-6 flex flex-col md:flex-row items-start md:items-center">
			<div class="author-avatar mb-4 md:mb-0 md:mr-6">
				<?php
				$author_bio_avatar_size = apply_filters( 'aqualuxe_author_bio_avatar_size', 96 );
				echo get_avatar( get_the_author_meta( 'user_email' ), $author_bio_avatar_size, '', '', array( 'class' => 'rounded-full' ) );
				?>
			</div>
			<div class="author-info">
				<h3 class="author-title text-lg font-bold text-primary-800 mb-2">
					<?php
					/* translators: %s: Author name */
					printf( esc_html__( 'About %s', 'aqualuxe' ), get_the_author() );
					?>
				</h3>
				<div class="author-description prose prose-sm">
					<?php the_author_meta( 'description' ); ?>
				</div>
				<a class="author-link mt-3 inline-flex items-center text-primary-600 hover:text-primary-800 transition-colors" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>">
					<?php
					/* translators: %s: Author name */
					printf( esc_html__( 'View all posts by %s', 'aqualuxe' ), get_the_author() );
					?>
					<i class="fas fa-arrow-right ml-2"></i>
				</a>
			</div>
		</div>
	</footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->