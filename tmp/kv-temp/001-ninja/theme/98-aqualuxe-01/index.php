<?php
/**
 * Main index template — graceful fallback.
 * @package aqualuxe
 */
get_header(); ?>
<div class="container mx-auto px-4 py-8">
	<?php if ( have_posts() ) : ?>
		<div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
			<?php while ( have_posts() ) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class('rounded-lg shadow bg-white dark:bg-slate-800'); ?> aria-labelledby="post-title-<?php the_ID(); ?>">
					<header class="p-4">
						<h2 id="post-title-<?php the_ID(); ?>" class="text-xl font-semibold"><a href="<?php the_permalink(); ?>" class="hover:underline"><?php the_title(); ?></a></h2>
					</header>
					<div class="prose dark:prose-invert p-4">
						<?php the_excerpt(); ?>
					</div>
				</article>
			<?php endwhile; ?>
		</div>
		<nav class="mt-8" aria-label="Pagination"><?php the_posts_pagination(); ?></nav>
	<?php else : ?>
		<p><?php esc_html_e( 'No content found.', 'aqualuxe' ); ?></p>
	<?php endif; ?>
</div>
<?php get_footer(); ?>
