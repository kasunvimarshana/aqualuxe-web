<?php
/**
 * Template part for displaying posts in an archive
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'archive-post bg-white rounded-lg shadow-md overflow-hidden transition-shadow hover:shadow-lg' ); ?>>
	<?php if ( has_post_thumbnail() ) : ?>
		<div class="entry-thumbnail">
			<a href="<?php the_permalink(); ?>" class="block overflow-hidden">
				<?php the_post_thumbnail( 'medium_large', array( 'class' => 'w-full h-64 object-cover hover:scale-105 transition-transform duration-300' ) ); ?>
			</a>
		</div>
	<?php endif; ?>

	<div class="entry-content p-6">
		<header class="entry-header mb-4">
			<?php
			the_title( '<h2 class="entry-title text-xl font-bold text-primary-800 mb-2"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark" class="hover:text-primary-600 transition-colors">', '</a></h2>' );

			if ( 'post' === get_post_type() ) :
				?>
				<div class="entry-meta flex flex-wrap items-center text-xs text-gray-600">
					<?php
					aqualuxe_posted_on();
					
					// Category
					$categories_list = get_the_category_list( esc_html__( ', ', 'aqualuxe' ) );
					if ( $categories_list ) {
						/* translators: 1: list of categories. */
						printf( '<span class="cat-links ml-3"><i class="fas fa-folder-open mr-1"></i> %1$s</span>', $categories_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					}
					
					// Comments count
					if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
						echo '<span class="comments-link ml-3">';
						echo '<i class="fas fa-comment-alt mr-1"></i>';
						comments_popup_link(
							sprintf(
								wp_kses(
									/* translators: %s: post title */
									__( '0', 'aqualuxe' ),
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
					?>
				</div><!-- .entry-meta -->
			<?php endif; ?>
		</header><!-- .entry-header -->

		<div class="entry-summary prose prose-sm max-w-none mb-4">
			<?php
			if ( has_excerpt() ) {
				the_excerpt();
			} else {
				echo wp_trim_words( get_the_content(), 20, '...' );
			}
			?>
		</div><!-- .entry-summary -->

		<div class="entry-footer">
			<a href="<?php the_permalink(); ?>" class="inline-flex items-center text-primary-600 hover:text-primary-800 font-medium transition-colors">
				<?php esc_html_e( 'Read More', 'aqualuxe' ); ?> <i class="fas fa-arrow-right ml-2"></i>
			</a>
		</div><!-- .entry-footer -->
	</div>
</article><!-- #post-<?php the_ID(); ?> -->