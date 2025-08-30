<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('py-6 first:pt-0 last:pb-0'); ?>>
	<div class="flex flex-col md:flex-row md:items-start">
		<?php if ( has_post_thumbnail() ) : ?>
			<div class="post-thumbnail md:w-1/4 md:mr-6 mb-4 md:mb-0 flex-shrink-0">
				<a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
					<?php the_post_thumbnail( 'thumbnail', array( 'class' => 'w-full h-auto rounded-lg' ) ); ?>
				</a>
			</div>
		<?php endif; ?>

		<div class="flex-grow">
			<header class="entry-header mb-2">
				<?php the_title( sprintf( '<h2 class="entry-title text-xl font-serif font-bold text-dark-900 dark:text-white"><a href="%s" rel="bookmark" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

				<?php if ( 'post' === get_post_type() ) : ?>
					<div class="entry-meta text-sm text-dark-500 dark:text-dark-400 mt-1">
						<?php
						aqualuxe_posted_on();
						aqualuxe_posted_by();
						?>
					</div><!-- .entry-meta -->
				<?php elseif ( 'page' !== get_post_type() ) : ?>
					<div class="entry-meta text-sm text-dark-500 dark:text-dark-400 mt-1">
						<span class="post-type">
							<?php echo esc_html( get_post_type_object( get_post_type() )->labels->singular_name ); ?>
						</span>
					</div>
				<?php endif; ?>
			</header><!-- .entry-header -->

			<div class="entry-summary text-dark-600 dark:text-dark-300">
				<?php the_excerpt(); ?>
			</div><!-- .entry-summary -->

			<footer class="entry-footer mt-3">
				<a href="<?php the_permalink(); ?>" class="inline-flex items-center text-primary-600 dark:text-primary-400 font-medium hover:underline text-sm">
					<?php 
					if ( 'product' === get_post_type() && aqualuxe_is_woocommerce_active() ) {
						esc_html_e( 'View Product', 'aqualuxe' );
					} else {
						esc_html_e( 'Read more', 'aqualuxe' );
					}
					?>
					<svg class="ml-1 w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
						<path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
					</svg>
				</a>
			</footer><!-- .entry-footer -->
		</div>
	</div>
</article><!-- #post-<?php the_ID(); ?> -->