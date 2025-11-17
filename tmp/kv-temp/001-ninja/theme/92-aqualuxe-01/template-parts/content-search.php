<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden'); ?>>
	<header class="entry-header px-4 py-5 sm:px-6">
		<?php the_title( sprintf( '<h2 class="entry-title text-2xl font-bold leading-tight"><a href="%s" rel="bookmark" class="text-gray-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

		<?php if ( 'post' === get_post_type() ) : ?>
		<div class="entry-meta text-sm text-gray-500 dark:text-gray-400 mt-2">
			<?php
			aqualuxe_posted_on();
			aqualuxe_posted_by();
			?>
		</div><!-- .entry-meta -->
		<?php endif; ?>
	</header><!-- .entry-header -->

	<?php aqualuxe_post_thumbnail(); ?>

	<div class="entry-summary prose dark:prose-invert max-w-none px-4 py-5 sm:px-6">
		<?php the_excerpt(); ?>
	</div><!-- .entry-summary -->

	<footer class="entry-footer px-4 py-4 sm:px-6 border-t border-gray-200 dark:border-gray-700">
		<?php aqualuxe_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->
