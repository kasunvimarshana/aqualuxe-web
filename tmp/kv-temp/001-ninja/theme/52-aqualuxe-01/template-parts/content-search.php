<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('card bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden mb-8'); ?>>
	<?php if ( has_post_thumbnail() ) : ?>
		<div class="post-thumbnail">
			<a href="<?php the_permalink(); ?>">
				<?php the_post_thumbnail( 'medium', array( 'class' => 'w-full h-auto' ) ); ?>
			</a>
		</div>
	<?php endif; ?>

	<div class="card-body p-6">
		<header class="entry-header mb-4">
			<?php the_title( sprintf( '<h2 class="entry-title text-xl font-bold"><a href="%s" rel="bookmark" class="hover:text-primary transition-colors">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

			<?php if ( 'post' === get_post_type() ) : ?>
			<div class="entry-meta text-sm text-gray-600 dark:text-gray-400 mt-2">
				<?php
				aqualuxe_posted_on();
				aqualuxe_posted_by();
				?>
			</div><!-- .entry-meta -->
			<?php endif; ?>
		</header><!-- .entry-header -->

		<div class="entry-summary prose dark:prose-invert max-w-none">
			<?php the_excerpt(); ?>
		</div><!-- .entry-summary -->

		<footer class="entry-footer mt-4">
			<a href="<?php the_permalink(); ?>" class="btn btn-primary">
				<?php esc_html_e( 'Read More', 'aqualuxe' ); ?>
			</a>
		</footer><!-- .entry-footer -->
	</div>
</article><!-- #post-<?php the_ID(); ?> -->