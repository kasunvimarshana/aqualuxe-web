<?php
/**
 * The front page template file
 *
 * @package AquaLuxe
 */

get_header(); ?>

<main id="primary" class="site-main">
	<!-- Hero Section -->
	<section class="hero-section relative min-h-screen flex items-center justify-center bg-gradient-to-br from-primary-50 to-aqua-50 dark:from-gray-900 dark:to-gray-800 overflow-hidden">
		<div class="hero-background absolute inset-0">
			<div class="absolute inset-0 bg-black bg-opacity-20 dark:bg-opacity-40"></div>
			<?php $hero_image = aqualuxe_get_option( 'hero_background_image' ); ?>
			<?php if ( $hero_image ) : ?>
			<img src="<?php echo esc_url( wp_get_attachment_image_url( $hero_image, 'full' ) ); ?>" alt="<?php esc_attr_e( 'AquaLuxe Hero Background', 'aqualuxe' ); ?>" class="w-full h-full object-cover">
			<?php else : ?>
			<img src="https://images.pexels.com/photos/1629781/pexels-photo-1629781.jpeg?auto=compress&cs=tinysrgb&w=1920&h=1080&fit=crop" alt="<?php esc_attr_e( 'Beautiful aquarium with tropical fish', 'aqualuxe' ); ?>" class="w-full h-full object-cover">
			<?php endif; ?>
		</div>
		
		<div class="hero-content relative z-10 text-center text-white px-4">
			<h1 class="hero-title text-5xl lg:text-7xl font-bold mb-6 slide-up">
				<?php echo esc_html( aqualuxe_get_option( 'hero_title', __( 'Bringing Elegance to Aquatic Life', 'aqualuxe' ) ) ); ?>
			</h1>
			<p class="hero-subtitle text-xl lg:text-2xl mb-8 slide-up max-w-3xl mx-auto">
				<?php echo esc_html( aqualuxe_get_option( 'hero_subtitle', __( 'Premium ornamental fish, custom aquarium design, and expert aquatic services - globally.', 'aqualuxe' ) ) ); ?>
			</p>
			<div class="hero-actions flex flex-col sm:flex-row gap-4 justify-center slide-up">
				<?php if ( aqualuxe_is_woocommerce_active() ) : ?>
				<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="btn btn-primary btn-lg">
					<?php esc_html_e( 'Shop Now', 'aqualuxe' ); ?>
				</a>
				<?php endif; ?>
				<a href="<?php echo esc_url( home_url( '/services' ) ); ?>" class="btn btn-outline btn-lg border-white text-white hover:bg-white hover:text-primary-600">
					<?php esc_html_e( 'Our Services', 'aqualuxe' ); ?>
				</a>
			</div>
		</div>

		<!-- Scroll indicator -->
		<div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 text-white animate-bounce">
			<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
				<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
			</svg>
		</div>
	</section>

	<!-- Features Section -->
	<section class="features-section py-20 bg-white dark:bg-gray-900">
		<div class="container mx-auto px-4">
			<div class="text-center mb-16">
				<h2 class="text-4xl font-bold text-gray-900 dark:text-white mb-4 slide-up">
					<?php esc_html_e( 'Why Choose AquaLuxe?', 'aqualuxe' ); ?>
				</h2>
				<p class="text-xl text-gray-600 dark:text-gray-300 max-w-2xl mx-auto slide-up">
					<?php esc_html_e( 'We combine luxury with expertise to deliver the finest aquatic experiences worldwide.', 'aqualuxe' ); ?>
				</p>
			</div>

			<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
				<?php
				$features = array(
					array(
						'icon' => '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>',
						'title' => __( 'Quality Guaranteed', 'aqualuxe' ),
						'description' => __( 'Premium quality fish and plants with health certification and lifetime support.', 'aqualuxe' )
					),
					array(
						'icon' => '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v18m-4-9l4-4 4 4"></path></svg>',
						'title' => __( 'Global Shipping', 'aqualuxe' ),
						'description' => __( 'Safe and secure delivery worldwide with temperature-controlled logistics.', 'aqualuxe' )
					),
					array(
						'icon' => '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>',
						'title' => __( 'Expert Care', 'aqualuxe' ),
						'description' => __( 'Professional aquarium design, maintenance, and consultation services.', 'aqualuxe' )
					),
					array(
						'icon' => '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>',
						'title' => __( 'Passion-Driven', 'aqualuxe' ),
						'description' => __( 'Over 20 years of dedication to the aquatic hobby and sustainable practices.', 'aqualuxe' )
					)
				);

				foreach ( $features as $feature ) :
				?>
				<div class="feature-card text-center fade-in">
					<div class="feature-icon w-16 h-16 mx-auto mb-6 flex items-center justify-center bg-primary-100 dark:bg-primary-900 text-primary-600 dark:text-primary-400 rounded-full">
						<?php echo $feature['icon']; ?>
					</div>
					<h3 class="feature-title text-xl font-semibold text-gray-900 dark:text-white mb-3">
						<?php echo esc_html( $feature['title'] ); ?>
					</h3>
					<p class="feature-description text-gray-600 dark:text-gray-300">
						<?php echo esc_html( $feature['description'] ); ?>
					</p>
				</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<?php if ( aqualuxe_is_woocommerce_active() ) : ?>
	<!-- Featured Products Section -->
	<section class="featured-products-section py-20 bg-gray-50 dark:bg-gray-800">
		<div class="container mx-auto px-4">
			<div class="text-center mb-16">
				<h2 class="text-4xl font-bold text-gray-900 dark:text-white mb-4 slide-up">
					<?php esc_html_e( 'Featured Products', 'aqualuxe' ); ?>
				</h2>
				<p class="text-xl text-gray-600 dark:text-gray-300 max-w-2xl mx-auto slide-up">
					<?php esc_html_e( 'Discover our hand-picked selection of premium aquatic life and equipment.', 'aqualuxe' ); ?>
				</p>
			</div>

			<div class="product-slider swiper fade-in">
				<div class="swiper-wrapper">
					<?php
					$featured_products = wc_get_featured_product_ids();
					if ( ! empty( $featured_products ) ) :
						$args = array(
							'post_type' => 'product',
							'posts_per_page' => 8,
							'post__in' => array_slice( $featured_products, 0, 8 ),
						);
						$products = new WP_Query( $args );

						if ( $products->have_posts() ) :
							while ( $products->have_posts() ) : $products->the_post();
								global $product;
					?>
					<div class="swiper-slide">
						<div class="product-card card h-full">
							<div class="product-image relative">
								<a href="<?php the_permalink(); ?>" class="block aspect-square overflow-hidden">
									<?php echo woocommerce_get_product_thumbnail( 'aqualuxe-product-medium', array( 'class' => 'w-full h-full object-cover transform hover:scale-105 transition-transform duration-500' ) ); ?>
								</a>
								<?php if ( $product->is_on_sale() ) : ?>
								<div class="sale-badge absolute top-2 left-2 bg-accent-500 text-white px-2 py-1 text-xs font-semibold rounded">
									<?php esc_html_e( 'Sale', 'aqualuxe' ); ?>
								</div>
								<?php endif; ?>
								<button class="wishlist-btn absolute top-2 right-2 w-8 h-8 bg-white dark:bg-gray-800 rounded-full flex items-center justify-center text-gray-600 dark:text-gray-400 hover:text-red-500 transition-colors duration-200">
									<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
									</svg>
								</button>
							</div>
							<div class="product-content p-4">
								<h3 class="product-title text-lg font-semibold text-gray-900 dark:text-white mb-2">
									<a href="<?php the_permalink(); ?>" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-200">
										<?php the_title(); ?>
									</a>
								</h3>
								<div class="product-price text-primary-600 dark:text-primary-400 font-bold text-xl mb-3">
									<?php echo $product->get_price_html(); ?>
								</div>
								<button class="btn btn-primary w-full">
									<?php esc_html_e( 'Add to Cart', 'aqualuxe' ); ?>
								</button>
							</div>
						</div>
					</div>
					<?php
							endwhile;
							wp_reset_postdata();
						endif;
					endif;
					?>
				</div>
				<div class="swiper-pagination mt-8"></div>
				<div class="swiper-button-prev"></div>
				<div class="swiper-button-next"></div>
			</div>

			<div class="text-center mt-12">
				<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="btn btn-primary btn-lg">
					<?php esc_html_e( 'View All Products', 'aqualuxe' ); ?>
				</a>
			</div>
		</div>
	</section>
	<?php endif; ?>

	<!-- Services Section -->
	<section class="services-section py-20 bg-white dark:bg-gray-900">
		<div class="container mx-auto px-4">
			<div class="text-center mb-16">
				<h2 class="text-4xl font-bold text-gray-900 dark:text-white mb-4 slide-up">
					<?php esc_html_e( 'Our Services', 'aqualuxe' ); ?>
				</h2>
				<p class="text-xl text-gray-600 dark:text-gray-300 max-w-2xl mx-auto slide-up">
					<?php esc_html_e( 'From design to maintenance, we offer comprehensive aquatic solutions for every need.', 'aqualuxe' ); ?>
				</p>
			</div>

			<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
				<?php
				$services = array(
					array(
						'image' => 'https://images.pexels.com/photos/1002703/pexels-photo-1002703.jpeg?auto=compress&cs=tinysrgb&w=400',
						'title' => __( 'Custom Aquarium Design', 'aqualuxe' ),
						'description' => __( 'Bespoke aquarium solutions tailored to your space, style, and aquatic preferences.', 'aqualuxe' ),
						'link' => home_url( '/services/aquarium-design' )
					),
					array(
						'image' => 'https://images.pexels.com/photos/3244513/pexels-photo-3244513.jpeg?auto=compress&cs=tinysrgb&w=400',
						'title' => __( 'Maintenance Services', 'aqualuxe' ),
						'description' => __( 'Professional cleaning, water testing, and ongoing care to keep your aquarium thriving.', 'aqualuxe' ),
						'link' => home_url( '/services/maintenance' )
					),
					array(
						'image' => 'https://images.pexels.com/photos/1179229/pexels-photo-1179229.jpeg?auto=compress&cs=tinysrgb&w=400',
						'title' => __( 'Expert Consultation', 'aqualuxe' ),
						'description' => __( 'Get professional advice on fish care, equipment selection, and aquarium optimization.', 'aqualuxe' ),
						'link' => home_url( '/services/consultation' )
					),
					array(
						'image' => 'https://images.pexels.com/photos/544731/pexels-photo-544731.jpeg?auto=compress&cs=tinysrgb&w=400',
						'title' => __( 'Quarantine Services', 'aqualuxe' ),
						'description' => __( 'Safe acclimatization and health monitoring for new aquatic arrivals.', 'aqualuxe' ),
						'link' => home_url( '/services/quarantine' )
					),
					array(
						'image' => 'https://images.pexels.com/photos/2138922/pexels-photo-2138922.jpeg?auto=compress&cs=tinysrgb&w=400',
						'title' => __( 'Export Services', 'aqualuxe' ),
						'description' => __( 'International shipping with full certification and compliance support.', 'aqualuxe' ),
						'link' => home_url( '/services/export' )
					),
					array(
						'image' => 'https://images.pexels.com/photos/1098735/pexels-photo-1098735.jpeg?auto=compress&cs=tinysrgb&w=400',
						'title' => __( 'Training Programs', 'aqualuxe' ),
						'description' => __( 'Learn proper aquarium care, breeding techniques, and business fundamentals.', 'aqualuxe' ),
						'link' => home_url( '/services/training' )
					)
				);

				foreach ( $services as $index => $service ) :
				?>
				<div class="service-card card fade-in">
					<div class="service-image">
						<img src="<?php echo esc_url( $service['image'] ); ?>" alt="<?php echo esc_attr( $service['title'] ); ?>" class="w-full h-48 object-cover">
					</div>
					<div class="service-content p-6">
						<h3 class="service-title text-xl font-semibold text-gray-900 dark:text-white mb-3">
							<?php echo esc_html( $service['title'] ); ?>
						</h3>
						<p class="service-description text-gray-600 dark:text-gray-300 mb-4">
							<?php echo esc_html( $service['description'] ); ?>
						</p>
						<a href="<?php echo esc_url( $service['link'] ); ?>" class="inline-flex items-center text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 font-medium transition-colors duration-200">
							<?php esc_html_e( 'Learn More', 'aqualuxe' ); ?>
							<svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
							</svg>
						</a>
					</div>
				</div>
				<?php endforeach; ?>
			</div>

			<div class="text-center mt-12">
				<a href="<?php echo esc_url( home_url( '/services' ) ); ?>" class="btn btn-primary btn-lg">
					<?php esc_html_e( 'View All Services', 'aqualuxe' ); ?>
				</a>
			</div>
		</div>
	</section>

	<!-- Statistics Section -->
	<section class="statistics-section py-20 bg-primary-600 text-white">
		<div class="container mx-auto px-4">
			<div class="grid grid-cols-2 lg:grid-cols-4 gap-8">
				<?php
				$stats = array(
					array(
						'number' => '15000',
						'label' => __( 'Happy Customers', 'aqualuxe' ),
						'suffix' => '+'
					),
					array(
						'number' => '500',
						'label' => __( 'Fish Species', 'aqualuxe' ),
						'suffix' => '+'
					),
					array(
						'number' => '50',
						'label' => __( 'Countries Served', 'aqualuxe' ),
						'suffix' => '+'
					),
					array(
						'number' => '20',
						'label' => __( 'Years Experience', 'aqualuxe' ),
						'suffix' => '+'
					)
				);

				foreach ( $stats as $stat ) :
				?>
				<div class="stat-item text-center fade-in">
					<div class="stat-number text-4xl lg:text-5xl font-bold mb-2">
						<span class="counter" data-target="<?php echo esc_attr( $stat['number'] ); ?>">0</span><?php echo esc_html( $stat['suffix'] ); ?>
					</div>
					<div class="stat-label text-lg text-primary-100">
						<?php echo esc_html( $stat['label'] ); ?>
					</div>
				</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<!-- Testimonials Section -->
	<section class="testimonials-section py-20 bg-gray-50 dark:bg-gray-800">
		<div class="container mx-auto px-4">
			<div class="text-center mb-16">
				<h2 class="text-4xl font-bold text-gray-900 dark:text-white mb-4 slide-up">
					<?php esc_html_e( 'What Our Customers Say', 'aqualuxe' ); ?>
				</h2>
				<p class="text-xl text-gray-600 dark:text-gray-300 max-w-2xl mx-auto slide-up">
					<?php esc_html_e( 'Trusted by aquarium enthusiasts and professionals worldwide.', 'aqualuxe' ); ?>
				</p>
			</div>

			<div class="testimonial-slider swiper fade-in">
				<div class="swiper-wrapper">
					<?php
					$testimonials = array(
						array(
							'name' => 'Sarah Johnson',
							'location' => 'New York, USA',
							'rating' => 5,
							'text' => 'AquaLuxe transformed my living room with a stunning custom aquarium. The fish are healthy and beautiful, and their maintenance service is exceptional.',
							'image' => 'https://images.pexels.com/photos/774909/pexels-photo-774909.jpeg?auto=compress&cs=tinysrgb&w=150&h=150&fit=crop&crop=face'
						),
						array(
							'name' => 'Marcus Chen',
							'location' => 'Singapore',
							'rating' => 5,
							'text' => 'As a hotel manager, I needed reliable aquatic displays. AquaLuxe delivered beyond expectations with their professional service and exotic fish selection.',
							'image' => 'https://images.pexels.com/photos/1043471/pexels-photo-1043471.jpeg?auto=compress&cs=tinysrgb&w=150&h=150&fit=crop&crop=face'
						),
						array(
							'name' => 'Emma Rodriguez',
							'location' => 'Madrid, Spain',
							'rating' => 5,
							'text' => 'The breeding program consultation helped me establish a successful aquaculture business. Their expertise in genetics and care is unmatched.',
							'image' => 'https://images.pexels.com/photos/1036622/pexels-photo-1036622.jpeg?auto=compress&cs=tinysrgb&w=150&h=150&fit=crop&crop=face'
						),
						array(
							'name' => 'Ahmed Al-Rashid',
							'location' => 'Dubai, UAE',
							'rating' => 5,
							'text' => 'International shipping was seamless, and all fish arrived healthy. The quarantine service gave me confidence in the quality and care.',
							'image' => 'https://images.pexels.com/photos/1222271/pexels-photo-1222271.jpeg?auto=compress&cs=tinysrgb&w=150&h=150&fit=crop&crop=face'
						)
					);

					foreach ( $testimonials as $testimonial ) :
					?>
					<div class="swiper-slide">
						<div class="testimonial-card card text-center p-8">
							<div class="testimonial-rating flex justify-center mb-4">
								<?php for ( $i = 0; $i < $testimonial['rating']; $i++ ) : ?>
								<svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
									<path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
								</svg>
								<?php endfor; ?>
							</div>
							<blockquote class="testimonial-text text-gray-600 dark:text-gray-300 text-lg mb-6 italic">
								"<?php echo esc_html( $testimonial['text'] ); ?>"
							</blockquote>
							<div class="testimonial-author">
								<img src="<?php echo esc_url( $testimonial['image'] ); ?>" alt="<?php echo esc_attr( $testimonial['name'] ); ?>" class="w-16 h-16 rounded-full mx-auto mb-4 object-cover">
								<div class="author-name font-semibold text-gray-900 dark:text-white">
									<?php echo esc_html( $testimonial['name'] ); ?>
								</div>
								<div class="author-location text-gray-600 dark:text-gray-400 text-sm">
									<?php echo esc_html( $testimonial['location'] ); ?>
								</div>
							</div>
						</div>
					</div>
					<?php endforeach; ?>
				</div>
				<div class="swiper-pagination mt-8"></div>
			</div>
		</div>
	</section>

	<!-- CTA Section -->
	<section class="cta-section py-20 bg-gradient-to-r from-primary-600 to-aqua-600 text-white">
		<div class="container mx-auto px-4 text-center">
			<h2 class="text-4xl font-bold mb-4 slide-up">
				<?php esc_html_e( 'Ready to Start Your Aquatic Journey?', 'aqualuxe' ); ?>
			</h2>
			<p class="text-xl mb-8 slide-up max-w-2xl mx-auto">
				<?php esc_html_e( 'Join thousands of satisfied customers worldwide. Let us help you create the aquarium of your dreams.', 'aqualuxe' ); ?>
			</p>
			<div class="flex flex-col sm:flex-row gap-4 justify-center slide-up">
				<a href="<?php echo esc_url( home_url( '/contact' ) ); ?>" class="btn btn-lg bg-white text-primary-600 hover:bg-gray-100">
					<?php esc_html_e( 'Get Started', 'aqualuxe' ); ?>
				</a>
				<?php if ( aqualuxe_is_woocommerce_active() ) : ?>
				<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="btn btn-outline btn-lg border-white text-white hover:bg-white hover:text-primary-600">
					<?php esc_html_e( 'Browse Products', 'aqualuxe' ); ?>
				</a>
				<?php endif; ?>
			</div>
		</div>
	</section>
</main>

<?php
get_footer();