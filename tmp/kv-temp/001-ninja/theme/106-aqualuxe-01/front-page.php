<?php
/** Front Page Template */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_template_part( 'templates/header' );
?>
<section class="hero relative overflow-hidden py-24 text-center text-white bg-gradient-to-br from-brand to-accent">
	<div class="container mx-auto px-6">
		<h1 class="text-4xl md:text-6xl font-extrabold mb-4"><?php echo esc_html( get_bloginfo('name') ); ?></h1>
		<p class="text-lg md:text-2xl max-w-2xl mx-auto mb-8"><?php bloginfo('description'); ?></p>
		<a href="<?php echo esc_url( get_post_type_archive_link('product') ?: home_url('#shop') ); ?>" class="inline-block bg-white text-brand font-semibold px-6 py-3 rounded shadow hover:shadow-lg transition"><?php esc_html_e( 'Shop Now', 'aqualuxe' ); ?></a>
	</div>
</section>
<section class="featured container mx-auto px-6 py-16">
	<h2 class="text-3xl font-bold mb-6"><?php esc_html_e( 'Featured Content', 'aqualuxe' ); ?></h2>
	<div class="grid gap-6 md:grid-cols-3">
		<?php
		$query = new WP_Query( [ 'posts_per_page' => 3 ] );
		if ( $query->have_posts() ) :
			while ( $query->have_posts() ) : $query->the_post(); ?>
				<article class="p-4 border rounded shadow-sm bg-white dark:bg-slate-800">
					<h3 class="font-semibold text-xl mb-2"><a href="<?php the_permalink(); ?>" class="hover:underline"><?php the_title(); ?></a></h3>
					<div class="text-sm opacity-80"><?php the_excerpt(); ?></div>
				</article>
			<?php endwhile; wp_reset_postdata(); endif; ?>
	</div>
</section>
<?php get_template_part( 'templates/footer' ); ?>
