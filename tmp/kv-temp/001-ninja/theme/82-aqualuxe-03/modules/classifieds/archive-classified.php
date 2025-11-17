<?php
get_header();
?>
<main id="primary" class="site-main container mx-auto px-4 py-8">
	<header class="mb-6">
		<h1 class="text-2xl font-semibold"><?php esc_html_e('Classifieds','aqualuxe'); ?></h1>
	</header>
	<?php if ( have_posts() ) : ?>
		<div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
		<?php while ( have_posts() ) : the_post(); ?>
			<article <?php post_class('border rounded overflow-hidden bg-white dark:bg-slate-900'); ?>>
				<a href="<?php the_permalink(); ?>" class="block">
					<?php if ( has_post_thumbnail() ) { the_post_thumbnail('alx-card'); } ?>
					<div class="p-4">
						<h2 class="text-lg font-semibold mb-1"><?php the_title(); ?></h2>
						<p class="text-sm opacity-80 line-clamp-2"><?php echo wp_trim_words( get_the_excerpt(), 18 ); ?></p>
						<div class="mt-2 text-sm">
							<strong><?php esc_html_e('Price','aqualuxe'); ?>:</strong>
							<?php echo esc_html( get_post_meta(get_the_ID(),'_alx_price', true) ?: __('Contact','aqualuxe') ); ?>
						</div>
					</div>
				</a>
			</article>
		<?php endwhile; ?>
		</div>
		<div class="mt-8"><?php the_posts_pagination(); ?></div>
	<?php else : ?>
		<p><?php esc_html_e('No listings yet.','aqualuxe'); ?></p>
	<?php endif; ?>
</main>
<?php get_footer(); ?>
