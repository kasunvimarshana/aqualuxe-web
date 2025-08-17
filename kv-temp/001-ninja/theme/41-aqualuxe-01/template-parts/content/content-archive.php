<?php
/**
 * Template part for displaying posts in archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('card overflow-hidden h-full flex flex-col'); ?>>
	<?php if ( has_post_thumbnail() ) : ?>
		<div class="post-thumbnail">
			<a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
				<?php the_post_thumbnail( 'medium_large', array( 'class' => 'w-full h-64 object-cover' ) ); ?>
			</a>
		</div>
	<?php endif; ?>

	<div class="entry-content p-6 flex-grow">
		<header class="entry-header mb-4">
			<?php
			the_title( '<h2 class="entry-title text-xl font-serif font-bold text-dark-900 dark:text-white"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors">', '</a></h2>' );

			if ( 'post' === get_post_type() ) :
				?>
				<div class="entry-meta flex flex-wrap items-center text-sm text-dark-500 dark:text-dark-400 mt-2">
					<?php
					aqualuxe_posted_on();
					?>
				</div><!-- .entry-meta -->
			<?php endif; ?>
		</header><!-- .entry-header -->

		<div class="entry-summary text-dark-600 dark:text-dark-300">
			<?php the_excerpt(); ?>
		</div><!-- .entry-summary -->
	</div>

	<footer class="entry-footer px-6 pb-6 mt-auto">
		<a href="<?php the_permalink(); ?>" class="inline-flex items-center text-primary-600 dark:text-primary-400 font-medium hover:underline">
			<?php esc_html_e( 'Read more', 'aqualuxe' ); ?>
			<svg class="ml-1 w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
				<path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
			</svg>
		</a>
	</footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->