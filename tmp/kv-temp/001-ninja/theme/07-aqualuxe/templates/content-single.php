<?php
/**
 * Template part for displaying single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'single-post' ); ?>>
	<header class="entry-header mb-8">
		<?php the_title( '<h1 class="entry-title text-3xl md:text-4xl lg:text-5xl font-serif font-bold text-gray-900 dark:text-gray-100 mb-4">', '</h1>' ); ?>
		
		<div class="entry-meta flex flex-wrap items-center text-sm text-gray-600 dark:text-gray-400 mb-6">
			<?php
			aqualuxe_posted_by();
			aqualuxe_posted_on();
			aqualuxe_post_categories();
			aqualuxe_reading_time();
			aqualuxe_comment_count();
			?>
		</div><!-- .entry-meta -->

		<?php if ( has_post_thumbnail() ) : ?>
			<div class="entry-thumbnail mb-8">
				<div class="aspect-w-16 aspect-h-9 overflow-hidden rounded-lg">
					<?php the_post_thumbnail( 'full', array( 'class' => 'w-full h-full object-cover' ) ); ?>
				</div>
				<?php if ( $caption = get_the_post_thumbnail_caption() ) : ?>
					<div class="thumbnail-caption text-sm text-gray-600 dark:text-gray-400 mt-2 text-center">
						<?php echo wp_kses_post( $caption ); ?>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</header><!-- .entry-header -->

	<div class="entry-content prose dark:prose-invert lg:prose-lg max-w-none">
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
				'before' => '<div class="page-links mt-6 pt-6 border-t border-gray-200 dark:border-dark-700">' . esc_html__( 'Pages:', 'aqualuxe' ),
				'after'  => '</div>',
			)
		);
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer mt-8 pt-6 border-t border-gray-200 dark:border-dark-700">
		<?php aqualuxe_entry_footer(); ?>
		
		<?php if ( get_theme_mod( 'aqualuxe_enable_post_author_box', true ) ) : ?>
			<div class="author-box mt-8 p-6 bg-gray-50 dark:bg-dark-800 rounded-lg">
				<div class="flex flex-col md:flex-row items-center md:items-start">
					<div class="author-avatar mb-4 md:mb-0 md:mr-6">
						<?php
						$author_avatar = get_avatar( get_the_author_meta( 'ID' ), 96, '', get_the_author(), array( 'class' => 'rounded-full' ) );
						if ( $author_avatar ) {
							echo $author_avatar;
						}
						?>
					</div>
					<div class="author-info text-center md:text-left">
						<h3 class="author-name text-xl font-medium text-gray-900 dark:text-gray-100 mb-2">
							<?php echo esc_html( get_the_author() ); ?>
						</h3>
						<?php if ( get_the_author_meta( 'description' ) ) : ?>
							<div class="author-description text-gray-600 dark:text-gray-400 mb-4">
								<?php echo wp_kses_post( get_the_author_meta( 'description' ) ); ?>
							</div>
						<?php endif; ?>
						<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" class="inline-flex items-center text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 font-medium transition-colors duration-300">
							<?php
							/* translators: %s: Author name */
							printf( esc_html__( 'View all posts by %s', 'aqualuxe' ), esc_html( get_the_author() ) );
							?>
							<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
							</svg>
						</a>
					</div>
				</div>
			</div>
		<?php endif; ?>
		
		<?php if ( get_theme_mod( 'aqualuxe_enable_post_share', true ) ) : ?>
			<div class="post-share mt-8">
				<h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
					<?php esc_html_e( 'Share This Post', 'aqualuxe' ); ?>
				</h3>
				<div class="flex flex-wrap gap-2">
					<a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo esc_url( get_permalink() ); ?>" target="_blank" rel="noopener noreferrer" class="inline-flex items-center px-4 py-2 bg-[#3b5998] text-white rounded-lg hover:bg-opacity-90 transition-colors duration-300">
						<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
							<path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/>
						</svg>
						<?php esc_html_e( 'Facebook', 'aqualuxe' ); ?>
					</a>
					<a href="https://twitter.com/intent/tweet?text=<?php echo esc_attr( get_the_title() ); ?>&url=<?php echo esc_url( get_permalink() ); ?>" target="_blank" rel="noopener noreferrer" class="inline-flex items-center px-4 py-2 bg-[#1da1f2] text-white rounded-lg hover:bg-opacity-90 transition-colors duration-300">
						<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
							<path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
						</svg>
						<?php esc_html_e( 'Twitter', 'aqualuxe' ); ?>
					</a>
					<a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo esc_url( get_permalink() ); ?>&title=<?php echo esc_attr( get_the_title() ); ?>" target="_blank" rel="noopener noreferrer" class="inline-flex items-center px-4 py-2 bg-[#0077b5] text-white rounded-lg hover:bg-opacity-90 transition-colors duration-300">
						<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
							<path d="M4.98 3.5c0 1.381-1.11 2.5-2.48 2.5s-2.48-1.119-2.48-2.5c0-1.38 1.11-2.5 2.48-2.5s2.48 1.12 2.48 2.5zm.02 4.5h-5v16h5v-16zm7.982 0h-4.968v16h4.969v-8.399c0-4.67 6.029-5.052 6.029 0v8.399h4.988v-10.131c0-7.88-8.922-7.593-11.018-3.714v-2.155z"/>
						</svg>
						<?php esc_html_e( 'LinkedIn', 'aqualuxe' ); ?>
					</a>
					<a href="https://pinterest.com/pin/create/button/?url=<?php echo esc_url( get_permalink() ); ?>&media=<?php echo esc_url( get_the_post_thumbnail_url( get_the_ID(), 'full' ) ); ?>&description=<?php echo esc_attr( get_the_title() ); ?>" target="_blank" rel="noopener noreferrer" class="inline-flex items-center px-4 py-2 bg-[#bd081c] text-white rounded-lg hover:bg-opacity-90 transition-colors duration-300">
						<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
							<path d="M12 0c-6.627 0-12 5.372-12 12 0 5.084 3.163 9.426 7.627 11.174-.105-.949-.2-2.405.042-3.441.218-.937 1.407-5.965 1.407-5.965s-.359-.719-.359-1.782c0-1.668.967-2.914 2.171-2.914 1.023 0 1.518.769 1.518 1.69 0 1.029-.655 2.568-.994 3.995-.283 1.194.599 2.169 1.777 2.169 2.133 0 3.772-2.249 3.772-5.495 0-2.873-2.064-4.882-5.012-4.882-3.414 0-5.418 2.561-5.418 5.207 0 1.031.397 2.138.893 2.738.098.119.112.224.083.345l-.333 1.36c-.053.22-.174.267-.402.161-1.499-.698-2.436-2.889-2.436-4.649 0-3.785 2.75-7.262 7.929-7.262 4.163 0 7.398 2.967 7.398 6.931 0 4.136-2.607 7.464-6.227 7.464-1.216 0-2.359-.631-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146 1.124.347 2.317.535 3.554.535 6.627 0 12-5.373 12-12 0-6.628-5.373-12-12-12z"/>
						</svg>
						<?php esc_html_e( 'Pinterest', 'aqualuxe' ); ?>
					</a>
					<a href="mailto:?subject=<?php echo esc_attr( get_the_title() ); ?>&body=<?php echo esc_url( get_permalink() ); ?>" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-opacity-90 transition-colors duration-300">
						<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
						</svg>
						<?php esc_html_e( 'Email', 'aqualuxe' ); ?>
					</a>
				</div>
			</div>
		<?php endif; ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->