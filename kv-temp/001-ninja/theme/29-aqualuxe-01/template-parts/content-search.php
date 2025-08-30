<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('bg-white dark:bg-dark-700 rounded-lg shadow-soft mb-6 overflow-hidden'); ?>>
	<div class="p-6">
		<header class="entry-header mb-4">
			<?php the_title( sprintf( '<h2 class="entry-title text-xl font-serif font-medium mb-2"><a href="%s" rel="bookmark" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-200">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

			<?php if ( 'post' === get_post_type() ) : ?>
				<div class="post-meta flex items-center text-sm text-dark-500 dark:text-dark-300 space-x-4">
					<div class="post-date flex items-center">
						<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
						</svg>
						<?php aqualuxe_posted_on(); ?>
					</div>
					
					<?php if ( has_category() ) : ?>
						<div class="post-categories flex items-center">
							<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
							</svg>
							<?php aqualuxe_post_categories(); ?>
						</div>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</header><!-- .entry-header -->

		<div class="entry-summary prose dark:prose-invert max-w-none">
			<?php the_excerpt(); ?>
		</div><!-- .entry-summary -->

		<footer class="entry-footer mt-4">
			<a href="<?php the_permalink(); ?>" class="inline-flex items-center text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-300 font-medium transition-colors duration-200">
				<?php esc_html_e( 'Read More', 'aqualuxe' ); ?>
				<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
				</svg>
			</a>
		</footer><!-- .entry-footer -->
	</div>
</article><!-- #post-<?php the_ID(); ?> -->