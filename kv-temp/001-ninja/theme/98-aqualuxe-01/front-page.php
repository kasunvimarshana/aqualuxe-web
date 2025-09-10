<?php
/** Front page shows hero and latest posts/products */
get_header(); ?>
<?php get_template_part('templates/parts/hero'); ?>
<section class="container mx-auto px-4 py-12">
	<h2 class="text-2xl font-semibold mb-6"><?php esc_html_e('Latest Articles', 'aqualuxe'); ?></h2>
	<div class="grid gap-8 md:grid-cols-3">
		<?php
		$latest = new WP_Query( [ 'posts_per_page' => 3 ] );
		if ( $latest->have_posts() ) :
			while ( $latest->have_posts() ) : $latest->the_post(); ?>
				<article <?php post_class('rounded-lg shadow bg-white dark:bg-slate-800 p-4'); ?>>
					<h3 class="text-lg font-semibold mb-2"><a href="<?php the_permalink(); ?>" class="hover:underline"><?php the_title(); ?></a></h3>
					<?php the_excerpt(); ?>
				</article>
			<?php endwhile; wp_reset_postdata(); endif; ?>
	</div>
</section>
<?php get_footer(); ?>
