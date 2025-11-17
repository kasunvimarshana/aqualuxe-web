<?php
/** Front page template */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
?>
<section class="alx-hero relative" role="region" aria-label="Hero">
	<div class="alx-container py-16 text-center">
		<h1 class="text-4xl md:text-6xl font-extrabold mb-4"><?php esc_html_e( 'Bringing elegance to aquatic life – globally.', 'aqualuxe' ); ?></h1>
		<p class="text-lg md:text-xl text-gray-600 max-w-2xl mx-auto"><?php esc_html_e( 'Premium ornamental fish, plants, equipment, and bespoke aquatic experiences.', 'aqualuxe' ); ?></p>
		<div class="mt-6 flex gap-3 justify-center">
			<a class="px-5 py-3 bg-blue-600 text-white rounded" href="<?php echo esc_url( get_post_type_archive_link( 'product' ) ?: home_url( '/shop' ) ); ?>"><?php esc_html_e( 'Shop Now', 'aqualuxe' ); ?></a>
			<a class="px-5 py-3 border rounded" href="<?php echo esc_url( home_url( '/services' ) ); ?>"><?php esc_html_e( 'Book a Service', 'aqualuxe' ); ?></a>
		</div>
	</div>
</section>
<section class="alx-container py-10" aria-labelledby="featured-products">
	<h2 id="featured-products" class="text-2xl font-bold mb-4"><?php esc_html_e( 'Featured Products', 'aqualuxe' ); ?></h2>
	<?php if ( class_exists( 'WooCommerce' ) ) : ?>
		<?php echo do_shortcode('[products limit="4" columns="4" visibility="featured"]'); ?>
	<?php else : ?>
		<p class="text-gray-600"><?php esc_html_e( 'WooCommerce is not active. Explore our latest articles below.', 'aqualuxe' ); ?></p>
	<?php endif; ?>
</section>
<section class="alx-container py-10" aria-labelledby="testimonials">
	<h2 id="testimonials" class="text-2xl font-bold mb-4"><?php esc_html_e( 'What our clients say', 'aqualuxe' ); ?></h2>
	<?php
	$testimonials = new WP_Query( [ 'post_type' => 'testimonial', 'posts_per_page' => 3 ] );
	if ( $testimonials->have_posts() ) : ?>
		<div class="grid md:grid-cols-3 gap-6">
		<?php while ( $testimonials->have_posts() ) : $testimonials->the_post(); ?>
			<blockquote class="border rounded p-4 bg-white/60 dark:bg-gray-900/40">
				<p class="italic">“<?php echo esc_html( wp_trim_words( get_the_content(), 30 ) ); ?>”</p>
				<footer class="mt-2 text-sm font-semibold">— <?php the_title(); ?></footer>
			</blockquote>
		<?php endwhile; wp_reset_postdata(); ?>
		</div>
	<?php else : ?>
		<p><?php esc_html_e( 'Testimonials coming soon.', 'aqualuxe' ); ?></p>
	<?php endif; ?>
</section>
<?php get_footer(); ?>
