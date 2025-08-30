<?php
/**
 * Template Name: Homepage
 *
 * @package AquaLuxe
 */

get_header(); ?>

<main id="primary" class="site-main">

	<!-- Hero Section -->
	<section class="hero-section">
		<div class="hero-content">
			<h1 class="hero-title"><?php echo esc_html( get_theme_mod( 'hero_title', __( 'Premium Ornamental Fish for Discerning Collectors', 'aqualuxe' ) ) ); ?></h1>
			<p class="hero-description"><?php echo esc_html( get_theme_mod( 'hero_description', __( 'Discover our exclusive collection of rare and exotic aquatic species, expertly bred and sustainably sourced for the most discerning collectors.', 'aqualuxe' ) ) ); ?></p>
			<a href="<?php echo esc_url( get_theme_mod( 'hero_button_url', '#' ) ); ?>" class="hero-button"><?php echo esc_html( get_theme_mod( 'hero_button_text', __( 'Explore Our Collection', 'aqualuxe' ) ) ); ?></a>
		</div>
	</section>

	<!-- Featured Products -->
	<section class="featured-products">
		<div class="container">
			<h2 class="section-title"><?php echo esc_html( get_theme_mod( 'featured_products_title', __( 'Featured Products', 'aqualuxe' ) ) ); ?></h2>
			<div class="products-grid">
				<?php
				// Display featured products
				$featured_products = get_theme_mod( 'featured_products', array() );
				if ( ! empty( $featured_products ) ) :
					foreach ( $featured_products as $product_id ) :
						$product = wc_get_product( $product_id );
						if ( $product ) :
							?>
							<div class="product-item">
								<a href="<?php echo esc_url( $product->get_permalink() ); ?>">
									<?php echo wp_kses_post( $product->get_image() ); ?>
									<h3 class="product-title"><?php echo esc_html( $product->get_name() ); ?></h3>
									<div class="product-price"><?php echo wp_kses_post( $product->get_price_html() ); ?></div>
								</a>
							</div>
							<?php
						endif;
					endforeach;
				endif;
				?>
			</div>
		</div>
	</section>

	<!-- Testimonials -->
	<section class="testimonials">
		<div class="container">
			<h2 class="section-title"><?php echo esc_html( get_theme_mod( 'testimonials_title', __( 'What Our Customers Say', 'aqualuxe' ) ) ); ?></h2>
			<div class="testimonials-slider">
				<?php
				// Display testimonials
				$testimonials = get_theme_mod( 'testimonials', array() );
				if ( ! empty( $testimonials ) ) :
					foreach ( $testimonials as $testimonial ) :
						?>
						<div class="testimonial-item">
							<div class="testimonial-content">
								<p><?php echo esc_html( $testimonial['content'] ); ?></p>
								<div class="testimonial-author">
									<h4><?php echo esc_html( $testimonial['author'] ); ?></h4>
									<span><?php echo esc_html( $testimonial['location'] ); ?></span>
								</div>
							</div>
						</div>
						<?php
					endforeach;
				endif;
				?>
			</div>
		</div>
	</section>

	<!-- Newsletter Signup -->
	<section class="newsletter">
		<div class="container">
			<h2 class="section-title"><?php echo esc_html( get_theme_mod( 'newsletter_title', __( 'Join Our Community', 'aqualuxe' ) ) ); ?></h2>
			<p class="newsletter-description"><?php echo esc_html( get_theme_mod( 'newsletter_description', __( 'Subscribe to our newsletter for exclusive offers, care tips, and the latest arrivals.', 'aqualuxe' ) ) ); ?></p>
			<?php echo do_shortcode( get_theme_mod( 'newsletter_shortcode', '' ) ); ?>
		</div>
	</section>

</main><!-- #main -->

<?php
get_footer();