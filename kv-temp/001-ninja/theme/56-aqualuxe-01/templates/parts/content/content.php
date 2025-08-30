<?php
/**
 * Template part for displaying posts
 *
 * @package AquaLuxe
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'card bg-white dark:bg-dark-700 rounded-lg shadow-soft overflow-hidden mb-8 group' ); ?>>
	<?php aqualuxe_post_thumbnail(); ?>
	
	<div class="card-content p-6">
		<header class="entry-header mb-4">
			<?php
			if ( is_singular() ) :
				the_title( '<h1 class="entry-title text-3xl md:text-4xl lg:text-5xl font-serif font-bold text-dark-600 dark:text-light-500 mb-4">', '</h1>' );
			else :
				the_title( '<h2 class="entry-title text-xl md:text-2xl font-serif font-bold mb-2"><a href="' . esc_url( get_permalink() ) . '" class="text-dark-600 dark:text-light-500 hover:text-primary-600 dark:hover:text-primary-500" rel="bookmark">', '</a></h2>' );
			endif;

			if ( 'post' === get_post_type() ) :
				?>
				<div class="entry-meta flex flex-wrap items-center text-sm text-gray-600 dark:text-gray-400">
					<?php
					// Author.
					$show_author = get_theme_mod( 'aqualuxe_show_post_author', true );
					if ( $show_author ) :
						?>
						<span class="byline mr-4 mb-2">
							<svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
							</svg>
							<span class="author vcard"><a class="url fn n hover:text-primary-600" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><?php echo esc_html( get_the_author() ); ?></a></span>
						</span>
					<?php endif; ?>

					<?php
					// Date.
					$show_date = get_theme_mod( 'aqualuxe_show_post_date', true );
					if ( $show_date ) :
						?>
						<span class="posted-on mr-4 mb-2">
							<svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
							</svg>
							<a class="hover:text-primary-600" href="<?php echo esc_url( get_permalink() ); ?>" rel="bookmark"><?php echo esc_html( get_the_date() ); ?></a>
						</span>
					<?php endif; ?>

					<?php
					// Categories.
					$show_categories = get_theme_mod( 'aqualuxe_show_post_categories', true );
					if ( $show_categories && has_category() ) :
						?>
						<span class="cat-links mr-4 mb-2">
							<svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
							</svg>
							<?php echo get_the_category_list( ', ' ); ?>
						</span>
					<?php endif; ?>

					<?php
					// Comments.
					$show_comments = get_theme_mod( 'aqualuxe_show_post_comments', true );
					if ( $show_comments && ! post_password_required() && ( comments_open() || get_comments_number() ) ) :
						?>
						<span class="comments-link mb-2">
							<svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
							</svg>
							<?php comments_popup_link(); ?>
						</span>
					<?php endif; ?>
				</div><!-- .entry-meta -->
			<?php endif; ?>
		</header><!-- .entry-header -->

		<div class="entry-content text-gray-600 dark:text-gray-400">
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
					<a href="<?php echo esc_url( get_permalink() ); ?>" class="inline-flex items-center font-medium text-primary-600 hover:text-primary-700 dark:text-primary-500 dark:hover:text-primary-400">
						<?php esc_html_e( 'Read More', 'aqualuxe' ); ?>
						<svg class="ml-1 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
						</svg>
					</a>
				</div>
			<?php endif; ?>
		</div><!-- .entry-content -->

		<?php if ( is_singular() ) : ?>
			<footer class="entry-footer mt-8">
				<?php
				// Tags.
				$show_tags = get_theme_mod( 'aqualuxe_show_post_tags', true );
				if ( $show_tags && has_tag() ) :
					?>
					<div class="entry-tags mb-6">
						<span class="tags-links text-sm text-gray-600 dark:text-gray-400">
							<span class="font-medium"><?php esc_html_e( 'Tags:', 'aqualuxe' ); ?></span> <?php echo get_the_tag_list( '', ', ' ); ?>
						</span>
					</div>
				<?php endif; ?>
			</footer><!-- .entry-footer -->
		<?php endif; ?>
	</div><!-- .card-content -->
</article><!-- #post-<?php the_ID(); ?> -->