<?php
/**
 * Template part for displaying a promotional banner on the homepage
 *
 * @package AquaLuxe
 */

?>

<section class="promo-banner relative py-24 bg-cover bg-center" style="background-image: url('<?php echo esc_url( get_template_directory_uri() . '/assets/images/promo-bg.jpg' ); ?>');">
	<div class="absolute inset-0 bg-primary-900 bg-opacity-80"></div>
	<div class="container mx-auto px-4 relative z-10">
		<div class="max-w-3xl mx-auto text-center text-white">
			<span class="inline-block px-4 py-1 bg-white bg-opacity-20 rounded-full text-sm font-medium mb-6">
				<?php esc_html_e( 'Limited Time Offer', 'aqualuxe' ); ?>
			</span>
			<h2 class="text-3xl md:text-4xl lg:text-5xl font-serif font-medium mb-6">
				<?php esc_html_e( 'Exclusive Collection of Rare Tropical Species', 'aqualuxe' ); ?>
			</h2>
			<p class="text-lg md:text-xl mb-8 text-white text-opacity-90">
				<?php esc_html_e( 'Discover our newly arrived collection of rare tropical species, sourced from the most pristine waters around the globe. Limited availability.', 'aqualuxe' ); ?>
			</p>
			<div class="flex flex-col sm:flex-row justify-center gap-4">
				<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="btn btn-accent">
					<?php esc_html_e( 'Shop the Collection', 'aqualuxe' ); ?>
				</a>
				<a href="<?php echo esc_url( home_url( '/about/sustainability' ) ); ?>" class="btn btn-outline text-white border-white hover:bg-white hover:text-primary-900">
					<?php esc_html_e( 'Our Sustainability Commitment', 'aqualuxe' ); ?>
				</a>
			</div>
			
			<div class="mt-12 grid grid-cols-2 md:grid-cols-4 gap-6">
				<div class="promo-feature text-center">
					<div class="bg-white bg-opacity-20 rounded-full p-4 inline-flex items-center justify-center mb-4">
						<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
						</svg>
					</div>
					<h3 class="text-lg font-medium mb-2"><?php esc_html_e( 'Ethically Sourced', 'aqualuxe' ); ?></h3>
					<p class="text-sm text-white text-opacity-80"><?php esc_html_e( 'Responsibly collected from sustainable sources', 'aqualuxe' ); ?></p>
				</div>
				
				<div class="promo-feature text-center">
					<div class="bg-white bg-opacity-20 rounded-full p-4 inline-flex items-center justify-center mb-4">
						<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
						</svg>
					</div>
					<h3 class="text-lg font-medium mb-2"><?php esc_html_e( 'Global Shipping', 'aqualuxe' ); ?></h3>
					<p class="text-sm text-white text-opacity-80"><?php esc_html_e( 'Specialized delivery to over 60 countries', 'aqualuxe' ); ?></p>
				</div>
				
				<div class="promo-feature text-center">
					<div class="bg-white bg-opacity-20 rounded-full p-4 inline-flex items-center justify-center mb-4">
						<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
						</svg>
					</div>
					<h3 class="text-lg font-medium mb-2"><?php esc_html_e( 'Expert Support', 'aqualuxe' ); ?></h3>
					<p class="text-sm text-white text-opacity-80"><?php esc_html_e( 'Personalized guidance from marine biologists', 'aqualuxe' ); ?></p>
				</div>
				
				<div class="promo-feature text-center">
					<div class="bg-white bg-opacity-20 rounded-full p-4 inline-flex items-center justify-center mb-4">
						<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
						</svg>
					</div>
					<h3 class="text-lg font-medium mb-2"><?php esc_html_e( 'Limited Availability', 'aqualuxe' ); ?></h3>
					<p class="text-sm text-white text-opacity-80"><?php esc_html_e( 'Exclusive species with limited quantities', 'aqualuxe' ); ?></p>
				</div>
			</div>
		</div>
	</div>
</section>