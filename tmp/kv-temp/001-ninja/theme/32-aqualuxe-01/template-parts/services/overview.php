<?php
/**
 * Template part for displaying the services overview section
 *
 * @package AquaLuxe
 */

?>

<section id="overview" class="services-overview py-16 bg-white dark:bg-dark-800">
	<div class="container mx-auto px-4">
		<div class="text-center mb-12">
			<span class="inline-block px-3 py-1 bg-primary-100 dark:bg-primary-900 text-primary-600 dark:text-primary-400 rounded-full text-sm font-medium mb-4">
				<?php esc_html_e( 'Our Expertise', 'aqualuxe' ); ?>
			</span>
			<h2 class="text-3xl md:text-4xl font-serif font-medium mb-4">
				<?php esc_html_e( 'Comprehensive Aquatic Services', 'aqualuxe' ); ?>
			</h2>
			<p class="text-lg text-dark-600 dark:text-dark-300 max-w-3xl mx-auto">
				<?php esc_html_e( 'From concept to creation and ongoing care, we provide end-to-end solutions for aquatic environments of all sizes and complexities.', 'aqualuxe' ); ?>
			</p>
		</div>
		
		<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
			<!-- Service 1 -->
			<div class="service-card bg-gray-50 dark:bg-dark-750 rounded-lg p-8 transition-transform duration-300 hover:-translate-y-2 shadow-soft">
				<div class="service-icon bg-primary-100 dark:bg-primary-900 text-primary-600 dark:text-primary-400 p-4 rounded-full inline-flex items-center justify-center mb-6">
					<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
					</svg>
				</div>
				<h3 class="text-xl font-medium mb-3"><?php esc_html_e( 'Custom Design', 'aqualuxe' ); ?></h3>
				<p class="text-dark-600 dark:text-dark-300 mb-4">
					<?php esc_html_e( 'Bespoke aquarium design and installation services tailored to your space, style, and vision. From concept to creation, we bring your aquatic dreams to life.', 'aqualuxe' ); ?>
				</p>
				<a href="#design" class="inline-flex items-center text-primary-600 dark:text-primary-400 font-medium hover:text-primary-700 dark:hover:text-primary-300 transition-colors">
					<?php esc_html_e( 'Learn More', 'aqualuxe' ); ?>
					<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
					</svg>
				</a>
			</div>
			
			<!-- Service 2 -->
			<div class="service-card bg-gray-50 dark:bg-dark-750 rounded-lg p-8 transition-transform duration-300 hover:-translate-y-2 shadow-soft">
				<div class="service-icon bg-secondary-100 dark:bg-secondary-900 text-secondary-600 dark:text-secondary-400 p-4 rounded-full inline-flex items-center justify-center mb-6">
					<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
					</svg>
				</div>
				<h3 class="text-xl font-medium mb-3"><?php esc_html_e( 'Maintenance', 'aqualuxe' ); ?></h3>
				<p class="text-dark-600 dark:text-dark-300 mb-4">
					<?php esc_html_e( 'Comprehensive maintenance programs to ensure your aquatic ecosystem remains pristine and healthy. Our expert technicians handle everything from water quality to equipment care.', 'aqualuxe' ); ?>
				</p>
				<a href="#maintenance" class="inline-flex items-center text-secondary-600 dark:text-secondary-400 font-medium hover:text-secondary-700 dark:hover:text-secondary-300 transition-colors">
					<?php esc_html_e( 'Learn More', 'aqualuxe' ); ?>
					<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
					</svg>
				</a>
			</div>
			
			<!-- Service 3 -->
			<div class="service-card bg-gray-50 dark:bg-dark-750 rounded-lg p-8 transition-transform duration-300 hover:-translate-y-2 shadow-soft">
				<div class="service-icon bg-accent-100 dark:bg-accent-900 text-accent-600 dark:text-accent-400 p-4 rounded-full inline-flex items-center justify-center mb-6">
					<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
					</svg>
				</div>
				<h3 class="text-xl font-medium mb-3"><?php esc_html_e( 'Consultation', 'aqualuxe' ); ?></h3>
				<p class="text-dark-600 dark:text-dark-300 mb-4">
					<?php esc_html_e( 'Expert advice and guidance from our team of marine biologists and aquarium specialists. Whether you\'re a beginner or experienced enthusiast, we provide personalized recommendations.', 'aqualuxe' ); ?>
				</p>
				<a href="#consultation" class="inline-flex items-center text-accent-600 dark:text-accent-400 font-medium hover:text-accent-700 dark:hover:text-accent-300 transition-colors">
					<?php esc_html_e( 'Learn More', 'aqualuxe' ); ?>
					<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
					</svg>
				</a>
			</div>
			
			<!-- Service 4 -->
			<div class="service-card bg-gray-50 dark:bg-dark-750 rounded-lg p-8 transition-transform duration-300 hover:-translate-y-2 shadow-soft">
				<div class="service-icon bg-primary-100 dark:bg-primary-900 text-primary-600 dark:text-primary-400 p-4 rounded-full inline-flex items-center justify-center mb-6">
					<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
					</svg>
				</div>
				<h3 class="text-xl font-medium mb-3"><?php esc_html_e( 'Breeding Programs', 'aqualuxe' ); ?></h3>
				<p class="text-dark-600 dark:text-dark-300 mb-4">
					<?php esc_html_e( 'Specialized breeding services for rare and exotic species. Our controlled environments and expert care ensure successful reproduction and healthy offspring.', 'aqualuxe' ); ?>
				</p>
				<a href="#breeding" class="inline-flex items-center text-primary-600 dark:text-primary-400 font-medium hover:text-primary-700 dark:hover:text-primary-300 transition-colors">
					<?php esc_html_e( 'Learn More', 'aqualuxe' ); ?>
					<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
					</svg>
				</a>
			</div>
			
			<!-- Service 5 -->
			<div class="service-card bg-gray-50 dark:bg-dark-750 rounded-lg p-8 transition-transform duration-300 hover:-translate-y-2 shadow-soft">
				<div class="service-icon bg-secondary-100 dark:bg-secondary-900 text-secondary-600 dark:text-secondary-400 p-4 rounded-full inline-flex items-center justify-center mb-6">
					<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
					</svg>
				</div>
				<h3 class="text-xl font-medium mb-3"><?php esc_html_e( 'Quarantine Services', 'aqualuxe' ); ?></h3>
				<p class="text-dark-600 dark:text-dark-300 mb-4">
					<?php esc_html_e( 'Professional quarantine facilities and protocols to ensure the health and safety of your aquatic specimens. We prevent disease transmission and acclimate new additions properly.', 'aqualuxe' ); ?>
				</p>
				<a href="#quarantine" class="inline-flex items-center text-secondary-600 dark:text-secondary-400 font-medium hover:text-secondary-700 dark:hover:text-secondary-300 transition-colors">
					<?php esc_html_e( 'Learn More', 'aqualuxe' ); ?>
					<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
					</svg>
				</a>
			</div>
			
			<!-- Service 6 -->
			<div class="service-card bg-gray-50 dark:bg-dark-750 rounded-lg p-8 transition-transform duration-300 hover:-translate-y-2 shadow-soft">
				<div class="service-icon bg-accent-100 dark:bg-accent-900 text-accent-600 dark:text-accent-400 p-4 rounded-full inline-flex items-center justify-center mb-6">
					<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
					</svg>
				</div>
				<h3 class="text-xl font-medium mb-3"><?php esc_html_e( 'Custom Pricing Plans', 'aqualuxe' ); ?></h3>
				<p class="text-dark-600 dark:text-dark-300 mb-4">
					<?php esc_html_e( 'Flexible pricing options tailored to your specific needs and budget. From one-time services to comprehensive care packages, we offer solutions for every client.', 'aqualuxe' ); ?>
				</p>
				<a href="#pricing" class="inline-flex items-center text-accent-600 dark:text-accent-400 font-medium hover:text-accent-700 dark:hover:text-accent-300 transition-colors">
					<?php esc_html_e( 'Learn More', 'aqualuxe' ); ?>
					<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
					</svg>
				</a>
			</div>
		</div>
	</div>
</section>