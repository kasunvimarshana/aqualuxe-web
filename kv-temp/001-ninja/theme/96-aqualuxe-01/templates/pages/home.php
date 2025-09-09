<?php
/**
 * The template for displaying the home page
 *
 * @package AquaLuxe
 */

?>

<div class="home-page">

	<!-- Hero Section -->
	<section class="py-20 text-center text-white bg-gray-900 hero-section">
		<h1 class="mb-4 text-5xl font-bold">Immerse Yourself in the Aquatic World</h1>
		<p class="mb-8 text-xl">Discover the beauty and tranquility of underwater life with AquaLuxe.</p>
		<a href="<?php echo esc_url( get_permalink( get_page_by_path( 'shop' ) ) ); ?>" class="px-4 py-2 font-bold text-white bg-blue-500 rounded hover:bg-blue-700">
			Explore Our Collection
		</a>
	</section>

	<!-- Featured Products Section -->
	<section class="py-16 featured-products-section">
		<div class="container mx-auto">
			<h2 class="mb-8 text-3xl font-bold text-center">Featured Products</h2>
			<div class="grid grid-cols-1 gap-8 md:grid-cols-3">
				<?php
				$args = array(
					'post_type'      => 'product',
					'posts_per_page' => 3,
					'meta_query'     => array(
						array(
							'key'     => '_featured',
							'value'   => 'yes',
							'compare' => '=',
						),
					),
				);
				$featured_query = new WP_Query( $args );
				if ( $featured_query->have_posts() ) :
					while ( $featured_query->have_posts() ) :
						$featured_query->the_post();
						wc_get_template_part( 'content', 'product' );
					endwhile;
				endif;
				wp_reset_postdata();
				?>
			</div>
		</div>
	</section>

	<!-- Services Section -->
	<section class="py-16 bg-gray-100 services-section">
		<div class="container mx-auto">
			<h2 class="mb-8 text-3xl font-bold text-center">Our Services</h2>
			<div class="grid grid-cols-1 gap-8 md:grid-cols-3">
				<?php
				$args = array(
					'post_type'      => 'service',
					'posts_per_page' => 3,
				);
				$services_query = new WP_Query( $args );
				if ( $services_query->have_posts() ) :
					while ( $services_query->have_posts() ) :
						$services_query->the_post();
						?>
						<div class="p-6 text-center bg-white rounded-lg shadow-md service-item">
							<h3 class="mb-2 text-xl font-bold"><?php the_title(); ?></h3>
							<div class="entry-content">
								<?php the_excerpt(); ?>
							</div>
						</div>
						<?php
					endwhile;
				endif;
				wp_reset_postdata();
				?>
			</div>
		</div>
	</section>

	<!-- Blog Section -->
	<section class="py-16 blog-section">
		<div class="container mx-auto">
			<h2 class="mb-8 text-3xl font-bold text-center">From the Blog</h2>
			<div class="grid grid-cols-1 gap-8 md:grid-cols-3">
				<?php
				$args = array(
					'post_type'      => 'post',
					'posts_per_page' => 3,
				);
				$blog_query = new WP_Query( $args );
				if ( $blog_query->have_posts() ) :
					while ( $blog_query->have_posts() ) :
						$blog_query->the_post();
						get_template_part( 'templates/parts/content', get_post_type() );
					endwhile;
				endif;
				wp_reset_postdata();
				?>
			</div>
		</div>
	</section>

</div>
