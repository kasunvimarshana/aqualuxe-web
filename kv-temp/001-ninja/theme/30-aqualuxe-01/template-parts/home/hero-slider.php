<?php
/**
 * Template part for displaying the hero slider on the homepage
 *
 * @package AquaLuxe
 */

?>

<section class="hero-slider relative overflow-hidden">
	<div class="swiper-container hero-swiper">
		<div class="swiper-wrapper">
			<!-- Slide 1 -->
			<div class="swiper-slide">
				<div class="hero-slide relative h-[600px]">
					<div class="absolute inset-0 bg-cover bg-center" style="background-image: url('<?php echo esc_url( get_template_directory_uri() . '/assets/images/hero-1.jpg' ); ?>');">
						<div class="absolute inset-0 bg-dark-900 bg-opacity-40"></div>
					</div>
					<div class="container mx-auto px-4 h-full flex items-center relative z-10">
						<div class="max-w-xl text-white">
							<h2 class="text-4xl md:text-5xl lg:text-6xl font-serif font-medium mb-4 animate-on-scroll" data-animation="fadeInUp" data-delay="0.2s">
								<?php esc_html_e( 'Discover Aquatic Elegance', 'aqualuxe' ); ?>
							</h2>
							<p class="text-lg md:text-xl mb-8 animate-on-scroll" data-animation="fadeInUp" data-delay="0.4s">
								<?php esc_html_e( 'Explore our premium collection of rare and exotic aquatic species, crafted for the discerning enthusiast.', 'aqualuxe' ); ?>
							</p>
							<div class="flex flex-wrap gap-4 animate-on-scroll" data-animation="fadeInUp" data-delay="0.6s">
								<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="btn btn-primary">
									<?php esc_html_e( 'Shop Collection', 'aqualuxe' ); ?>
								</a>
								<a href="<?php echo esc_url( home_url( '/about' ) ); ?>" class="btn btn-outline text-white border-white hover:bg-white hover:text-dark-800">
									<?php esc_html_e( 'Learn More', 'aqualuxe' ); ?>
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<!-- Slide 2 -->
			<div class="swiper-slide">
				<div class="hero-slide relative h-[600px]">
					<div class="absolute inset-0 bg-cover bg-center" style="background-image: url('<?php echo esc_url( get_template_directory_uri() . '/assets/images/hero-2.jpg' ); ?>');">
						<div class="absolute inset-0 bg-dark-900 bg-opacity-40"></div>
					</div>
					<div class="container mx-auto px-4 h-full flex items-center relative z-10">
						<div class="max-w-xl text-white">
							<h2 class="text-4xl md:text-5xl lg:text-6xl font-serif font-medium mb-4 animate-on-scroll" data-animation="fadeInUp" data-delay="0.2s">
								<?php esc_html_e( 'Premium Aquarium Designs', 'aqualuxe' ); ?>
							</h2>
							<p class="text-lg md:text-xl mb-8 animate-on-scroll" data-animation="fadeInUp" data-delay="0.4s">
								<?php esc_html_e( 'Custom-crafted aquatic environments that blend seamlessly with your luxury interior spaces.', 'aqualuxe' ); ?>
							</p>
							<div class="flex flex-wrap gap-4 animate-on-scroll" data-animation="fadeInUp" data-delay="0.6s">
								<a href="<?php echo esc_url( home_url( '/services' ) ); ?>" class="btn btn-primary">
									<?php esc_html_e( 'Our Services', 'aqualuxe' ); ?>
								</a>
								<a href="<?php echo esc_url( home_url( '/gallery' ) ); ?>" class="btn btn-outline text-white border-white hover:bg-white hover:text-dark-800">
									<?php esc_html_e( 'View Gallery', 'aqualuxe' ); ?>
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<!-- Slide 3 -->
			<div class="swiper-slide">
				<div class="hero-slide relative h-[600px]">
					<div class="absolute inset-0 bg-cover bg-center" style="background-image: url('<?php echo esc_url( get_template_directory_uri() . '/assets/images/hero-3.jpg' ); ?>');">
						<div class="absolute inset-0 bg-dark-900 bg-opacity-40"></div>
					</div>
					<div class="container mx-auto px-4 h-full flex items-center relative z-10">
						<div class="max-w-xl text-white">
							<h2 class="text-4xl md:text-5xl lg:text-6xl font-serif font-medium mb-4 animate-on-scroll" data-animation="fadeInUp" data-delay="0.2s">
								<?php esc_html_e( 'Expert Maintenance Services', 'aqualuxe' ); ?>
							</h2>
							<p class="text-lg md:text-xl mb-8 animate-on-scroll" data-animation="fadeInUp" data-delay="0.4s">
								<?php esc_html_e( 'Professional care and maintenance to ensure your aquatic ecosystem thrives year-round.', 'aqualuxe' ); ?>
							</p>
							<div class="flex flex-wrap gap-4 animate-on-scroll" data-animation="fadeInUp" data-delay="0.6s">
								<a href="<?php echo esc_url( home_url( '/services/maintenance' ) ); ?>" class="btn btn-primary">
									<?php esc_html_e( 'Maintenance Plans', 'aqualuxe' ); ?>
								</a>
								<a href="<?php echo esc_url( home_url( '/contact' ) ); ?>" class="btn btn-outline text-white border-white hover:bg-white hover:text-dark-800">
									<?php esc_html_e( 'Contact Us', 'aqualuxe' ); ?>
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<!-- Navigation -->
		<div class="swiper-button-prev absolute left-4 z-10 text-white">
			<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
				<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
			</svg>
		</div>
		<div class="swiper-button-next absolute right-4 z-10 text-white">
			<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
				<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
			</svg>
		</div>
		
		<!-- Pagination -->
		<div class="swiper-pagination"></div>
	</div>
</section>