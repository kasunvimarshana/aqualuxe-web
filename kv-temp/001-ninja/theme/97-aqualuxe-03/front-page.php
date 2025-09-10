<?php
/**
 * The template for displaying the homepage
 *
 * @package AquaLuxe
 */

get_header();
?>

	<main id="primary" class="site-main">

		<?php // Hero Section ?>
		<section class="hero-section bg-blue-700 text-white text-center py-20">
			<div class="container mx-auto">
				<h1 class="text-5xl font-bold mb-4"><?php bloginfo( 'name' ); ?></h1>
				<p class="text-xl"><?php bloginfo( 'description' ); ?></p>
				<a href="#featured-products" class="mt-8 inline-block bg-white text-blue-700 font-bold py-3 px-6 rounded hover:bg-blue-100"><?php esc_html_e( 'Explore Our Collection', 'aqualuxe' ); ?></a>
			</div>
		</section>

		<?php // Featured Products Section ?>
		<?php if ( class_exists( 'WooCommerce' ) ) : ?>
			<section id="featured-products" class="featured-products py-16">
				<div class="container mx-auto">
					<h2 class="text-3xl font-bold text-center mb-8"><?php esc_html_e( 'Featured Products', 'aqualuxe' ); ?></h2>
					<?php
					$featured_products_args = array(
						'post_type'      => 'product',
						'posts_per_page' => 4,
						'tax_query'      => array(
							array(
								'taxonomy' => 'product_visibility',
								'field'    => 'name',
								'terms'    => 'featured',
							),
						),
					);
					$featured_products = new WP_Query( $featured_products_args );
					if ( $featured_products->have_posts() ) :
						echo '<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">';
						while ( $featured_products->have_posts() ) :
							$featured_products->the_post();
							wc_get_template_part( 'content', 'product' );
						endwhile;
						echo '</div>';
					endif;
					wp_reset_postdata();
					?>
				</div>
			</section>
		<?php endif; ?>

		<?php // Testimonials Section ?>
		<?php
		$testimonials_args = array(
			'post_type'      => 'testimonial',
			'posts_per_page' => 3,
		);
		$testimonials = new WP_Query( $testimonials_args );
		if ( $testimonials->have_posts() ) :
			?>
			<section class="testimonials-section bg-gray-100 dark:bg-gray-800 py-16">
				<div class="container mx-auto">
					<h2 class="text-3xl font-bold text-center mb-8"><?php esc_html_e( 'What Our Clients Say', 'aqualuxe' ); ?></h2>
					<div class="grid grid-cols-1 md:grid-cols-3 gap-8">
						<?php
						while ( $testimonials->have_posts() ) :
							$testimonials->the_post();
							?>
							<div class="bg-white dark:bg-gray-700 p-6 rounded-lg shadow-md">
								<blockquote class="text-lg italic mb-4">"<?php the_content(); ?>"</blockquote>
								<cite class="font-bold not-italic"><?php the_title(); ?></cite>
							</div>
							<?php
						endwhile;
						?>
					</div>
				</div>
			</section>
			<?php
		endif;
		wp_reset_postdata();
		?>

		<?php // Newsletter Signup Section ?>
		<section class="newsletter-section bg-blue-500 text-white py-16">
			<div class="container mx-auto text-center">
				<h2 class="text-3xl font-bold mb-4"><?php esc_html_e( 'Subscribe to our Newsletter', 'aqualuxe' ); ?></h2>
				<p class="mb-8"><?php esc_html_e( 'Get the latest updates on new products and upcoming sales', 'aqualuxe' ); ?></p>
				<form class="max-w-md mx-auto">
					<div class="flex items-center">
						<input type="email" placeholder="<?php esc_attr_e( 'Your email address', 'aqualuxe' ); ?>" class="w-full p-3 rounded-l-md text-gray-800">
						<button type="submit" class="bg-blue-700 text-white font-bold py-3 px-6 rounded-r-md hover:bg-blue-800"><?php esc_html_e( 'Subscribe', 'aqualuxe' ); ?></button>
					</div>
				</form>
			</div>
		</section>

	</main><!-- #main -->

<?php
get_footer();
