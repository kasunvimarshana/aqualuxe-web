<?php
/**
 * Template part for displaying single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('bg-white rounded-lg shadow-md overflow-hidden'); ?>>
	<?php if ( has_post_thumbnail() && get_theme_mod( 'aqualuxe_single_featured_image', true ) ) : ?>
		<div class="post-thumbnail">
			<?php the_post_thumbnail( 'full', array( 'class' => 'w-full h-auto' ) ); ?>
		</div>
	<?php endif; ?>

	<div class="entry-content-wrapper p-6 md:p-8">
		<header class="entry-header mb-6">
			<?php the_title( '<h1 class="entry-title text-3xl md:text-4xl font-bold mb-4">', '</h1>' ); ?>
			
			<div class="entry-meta text-sm text-gray-600 mb-4">
				<?php
				aqualuxe_posted_by();
				aqualuxe_posted_on();
				?>
			</div><!-- .entry-meta -->
			
			<?php
			// Display categories
			$categories_list = get_the_category_list( esc_html__( ', ', 'aqualuxe' ) );
			if ( $categories_list ) {
				echo '<div class="entry-categories mb-4">';
				echo '<span class="cat-links flex flex-wrap gap-2">';
				
				$categories = explode( ', ', wp_strip_all_tags( $categories_list ) );
				foreach ( $categories as $category ) {
					echo '<span class="bg-primary bg-opacity-10 text-primary px-3 py-1 rounded-full text-xs font-medium">' . esc_html( $category ) . '</span>';
				}
				
				echo '</span>';
				echo '</div>';
			}
			?>
		</header><!-- .entry-header -->

		<div class="entry-content prose max-w-none mb-8">
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

		<footer class="entry-footer pt-6 border-t border-gray-200">
			<?php aqualuxe_entry_footer(); ?>
			
			<?php if ( get_theme_mod( 'aqualuxe_author_bio', true ) && get_the_author_meta( 'description' ) ) : ?>
				<div class="author-bio mt-8 p-6 bg-gray-50 rounded-lg">
					<div class="flex items-center mb-4">
						<?php echo get_avatar( get_the_author_meta( 'ID' ), 60, '', '', array( 'class' => 'rounded-full mr-4' ) ); ?>
						<div>
							<h3 class="author-name text-lg font-bold mb-1"><?php echo esc_html( get_the_author() ); ?></h3>
							<?php if ( get_the_author_meta( 'user_url' ) ) : ?>
								<a href="<?php echo esc_url( get_the_author_meta( 'user_url' ) ); ?>" class="author-website text-sm text-primary hover:underline" target="_blank" rel="nofollow">
									<?php echo esc_html( get_the_author_meta( 'user_url' ) ); ?>
								</a>
							<?php endif; ?>
						</div>
					</div>
					<div class="author-description prose">
						<?php echo wpautop( get_the_author_meta( 'description' ) ); ?>
					</div>
				</div>
			<?php endif; ?>
			
			<?php
			// Social sharing
			if ( get_theme_mod( 'aqualuxe_social_sharing', true ) ) :
				$share_url = urlencode( get_permalink() );
				$share_title = urlencode( get_the_title() );
				$share_summary = urlencode( get_the_excerpt() );
				$share_image = has_post_thumbnail() ? urlencode( get_the_post_thumbnail_url( get_the_ID(), 'large' ) ) : '';
				?>
				<div class="social-sharing mt-8">
					<h3 class="text-lg font-bold mb-4"><?php esc_html_e( 'Share this post', 'aqualuxe' ); ?></h3>
					<div class="flex flex-wrap gap-2">
						<a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $share_url; ?>" class="share-button facebook bg-[#3b5998] text-white px-4 py-2 rounded-md inline-flex items-center" target="_blank" rel="noopener noreferrer">
							<i class="fab fa-facebook-f mr-2" aria-hidden="true"></i>
							<span><?php esc_html_e( 'Facebook', 'aqualuxe' ); ?></span>
						</a>
						<a href="https://twitter.com/intent/tweet?url=<?php echo $share_url; ?>&text=<?php echo $share_title; ?>" class="share-button twitter bg-[#1da1f2] text-white px-4 py-2 rounded-md inline-flex items-center" target="_blank" rel="noopener noreferrer">
							<i class="fab fa-twitter mr-2" aria-hidden="true"></i>
							<span><?php esc_html_e( 'Twitter', 'aqualuxe' ); ?></span>
						</a>
						<a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo $share_url; ?>&title=<?php echo $share_title; ?>&summary=<?php echo $share_summary; ?>" class="share-button linkedin bg-[#0077b5] text-white px-4 py-2 rounded-md inline-flex items-center" target="_blank" rel="noopener noreferrer">
							<i class="fab fa-linkedin-in mr-2" aria-hidden="true"></i>
							<span><?php esc_html_e( 'LinkedIn', 'aqualuxe' ); ?></span>
						</a>
						<a href="https://pinterest.com/pin/create/button/?url=<?php echo $share_url; ?>&media=<?php echo $share_image; ?>&description=<?php echo $share_title; ?>" class="share-button pinterest bg-[#bd081c] text-white px-4 py-2 rounded-md inline-flex items-center" target="_blank" rel="noopener noreferrer">
							<i class="fab fa-pinterest-p mr-2" aria-hidden="true"></i>
							<span><?php esc_html_e( 'Pinterest', 'aqualuxe' ); ?></span>
						</a>
						<a href="mailto:?subject=<?php echo $share_title; ?>&body=<?php echo $share_url; ?>" class="share-button email bg-gray-700 text-white px-4 py-2 rounded-md inline-flex items-center">
							<i class="fas fa-envelope mr-2" aria-hidden="true"></i>
							<span><?php esc_html_e( 'Email', 'aqualuxe' ); ?></span>
						</a>
					</div>
				</div>
			<?php endif; ?>
		</footer><!-- .entry-footer -->
	</div>
</article><!-- #post-<?php the_ID(); ?> -->