<?php
/**
 * Template part for displaying features on the homepage
 *
 * @package AquaLuxe
 */

?>

<section class="features py-16 bg-white dark:bg-dark-800">
	<div class="container mx-auto px-4">
		<div class="text-center mb-12">
			<h2 class="text-3xl md:text-4xl font-serif font-medium mb-4"><?php esc_html_e( 'Why Choose AquaLuxe', 'aqualuxe' ); ?></h2>
			<p class="text-lg text-dark-600 dark:text-dark-300 max-w-3xl mx-auto"><?php esc_html_e( 'Discover the unique advantages that set us apart in the world of luxury aquatics.', 'aqualuxe' ); ?></p>
		</div>
		
		<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
			<!-- Feature 1 -->
			<div class="feature-card bg-gray-50 dark:bg-dark-750 rounded-lg p-8 transition-transform duration-300 hover:-translate-y-2">
				<div class="feature-icon bg-primary-100 dark:bg-primary-900 text-primary-600 dark:text-primary-400 p-4 rounded-full inline-flex items-center justify-center mb-6">
					<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
					</svg>
				</div>
				<h3 class="text-xl font-medium mb-3"><?php esc_html_e( 'Ethical Sourcing', 'aqualuxe' ); ?></h3>
				<p class="text-dark-600 dark:text-dark-300 mb-4"><?php esc_html_e( 'All our aquatic species are ethically sourced from sustainable populations, with a focus on conservation and environmental responsibility.', 'aqualuxe' ); ?></p>
				<a href="<?php echo esc_url( home_url( '/about/sustainability' ) ); ?>" class="inline-flex items-center text-primary-600 dark:text-primary-400 font-medium hover:text-primary-700 dark:hover:text-primary-300 transition-colors">
					<?php esc_html_e( 'Learn More', 'aqualuxe' ); ?>
					<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
					</svg>
				</a>
			</div>
			
			<!-- Feature 2 -->
			<div class="feature-card bg-gray-50 dark:bg-dark-750 rounded-lg p-8 transition-transform duration-300 hover:-translate-y-2">
				<div class="feature-icon bg-secondary-100 dark:bg-secondary-900 text-secondary-600 dark:text-secondary-400 p-4 rounded-full inline-flex items-center justify-center mb-6">
					<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
					</svg>
				</div>
				<h3 class="text-xl font-medium mb-3"><?php esc_html_e( 'Custom Design', 'aqualuxe' ); ?></h3>
				<p class="text-dark-600 dark:text-dark-300 mb-4"><?php esc_html_e( 'Our expert designers create bespoke aquatic environments tailored to your space, style, and preferences, ensuring a perfect integration with your interior.', 'aqualuxe' ); ?></p>
				<a href="<?php echo esc_url( home_url( '/services/design' ) ); ?>" class="inline-flex items-center text-secondary-600 dark:text-secondary-400 font-medium hover:text-secondary-700 dark:hover:text-secondary-300 transition-colors">
					<?php esc_html_e( 'Learn More', 'aqualuxe' ); ?>
					<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
					</svg>
				</a>
			</div>
			
			<!-- Feature 3 -->
			<div class="feature-card bg-gray-50 dark:bg-dark-750 rounded-lg p-8 transition-transform duration-300 hover:-translate-y-2">
				<div class="feature-icon bg-accent-100 dark:bg-accent-900 text-accent-600 dark:text-accent-400 p-4 rounded-full inline-flex items-center justify-center mb-6">
					<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
					</svg>
				</div>
				<h3 class="text-xl font-medium mb-3"><?php esc_html_e( 'Expert Support', 'aqualuxe' ); ?></h3>
				<p class="text-dark-600 dark:text-dark-300 mb-4"><?php esc_html_e( 'Our team of marine biologists and aquatic specialists provides ongoing support and guidance to ensure your aquatic ecosystem thrives.', 'aqualuxe' ); ?></p>
				<a href="<?php echo esc_url( home_url( '/services/support' ) ); ?>" class="inline-flex items-center text-accent-600 dark:text-accent-400 font-medium hover:text-accent-700 dark:hover:text-accent-300 transition-colors">
					<?php esc_html_e( 'Learn More', 'aqualuxe' ); ?>
					<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
					</svg>
				</a>
			</div>
			
			<!-- Feature 4 -->
			<div class="feature-card bg-gray-50 dark:bg-dark-750 rounded-lg p-8 transition-transform duration-300 hover:-translate-y-2">
				<div class="feature-icon bg-primary-100 dark:bg-primary-900 text-primary-600 dark:text-primary-400 p-4 rounded-full inline-flex items-center justify-center mb-6">
					<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
					</svg>
				</div>
				<h3 class="text-xl font-medium mb-3"><?php esc_html_e( 'Global Shipping', 'aqualuxe' ); ?></h3>
				<p class="text-dark-600 dark:text-dark-300 mb-4"><?php esc_html_e( 'Our specialized shipping methods ensure safe delivery of live specimens to over 60 countries, with temperature-controlled packaging and express delivery.', 'aqualuxe' ); ?></p>
				<a href="<?php echo esc_url( home_url( '/shipping' ) ); ?>" class="inline-flex items-center text-primary-600 dark:text-primary-400 font-medium hover:text-primary-700 dark:hover:text-primary-300 transition-colors">
					<?php esc_html_e( 'Learn More', 'aqualuxe' ); ?>
					<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
					</svg>
				</a>
			</div>
			
			<!-- Feature 5 -->
			<div class="feature-card bg-gray-50 dark:bg-dark-750 rounded-lg p-8 transition-transform duration-300 hover:-translate-y-2">
				<div class="feature-icon bg-secondary-100 dark:bg-secondary-900 text-secondary-600 dark:text-secondary-400 p-4 rounded-full inline-flex items-center justify-center mb-6">
					<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
					</svg>
				</div>
				<h3 class="text-xl font-medium mb-3"><?php esc_html_e( 'Maintenance Services', 'aqualuxe' ); ?></h3>
				<p class="text-dark-600 dark:text-dark-300 mb-4"><?php esc_html_e( 'Our professional maintenance team ensures your aquatic ecosystem remains pristine and healthy with regular service visits tailored to your specific needs.', 'aqualuxe' ); ?></p>
				<a href="<?php echo esc_url( home_url( '/services/maintenance' ) ); ?>" class="inline-flex items-center text-secondary-600 dark:text-secondary-400 font-medium hover:text-secondary-700 dark:hover:text-secondary-300 transition-colors">
					<?php esc_html_e( 'Learn More', 'aqualuxe' ); ?>
					<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
					</svg>
				</a>
			</div>
			
			<!-- Feature 6 -->
			<div class="feature-card bg-gray-50 dark:bg-dark-750 rounded-lg p-8 transition-transform duration-300 hover:-translate-y-2">
				<div class="feature-icon bg-accent-100 dark:bg-accent-900 text-accent-600 dark:text-accent-400 p-4 rounded-full inline-flex items-center justify-center mb-6">
					<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
					</svg>
				</div>
				<h3 class="text-xl font-medium mb-3"><?php esc_html_e( 'Exclusive Collections', 'aqualuxe' ); ?></h3>
				<p class="text-dark-600 dark:text-dark-300 mb-4"><?php esc_html_e( 'Access to rare and exclusive species not available elsewhere, with limited quantities to ensure the highest quality and care for each specimen.', 'aqualuxe' ); ?></p>
				<a href="<?php echo esc_url( home_url( '/collections/exclusive' ) ); ?>" class="inline-flex items-center text-accent-600 dark:text-accent-400 font-medium hover:text-accent-700 dark:hover:text-accent-300 transition-colors">
					<?php esc_html_e( 'Learn More', 'aqualuxe' ); ?>
					<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
					</svg>
				</a>
			</div>
		</div>
	</div>
</section>