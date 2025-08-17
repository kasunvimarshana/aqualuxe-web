<?php
/**
 * Template part for displaying the values section on the About page
 *
 * @package AquaLuxe
 */

?>

<section id="values" class="about-values py-16 bg-white dark:bg-dark-800">
	<div class="container mx-auto px-4">
		<div class="text-center mb-12">
			<span class="inline-block px-3 py-1 bg-accent-100 dark:bg-accent-900 text-accent-600 dark:text-accent-400 rounded-full text-sm font-medium mb-4">
				<?php esc_html_e( 'Our Values', 'aqualuxe' ); ?>
			</span>
			<h2 class="text-3xl md:text-4xl font-serif font-medium mb-4">
				<?php esc_html_e( 'Principles That Guide Us', 'aqualuxe' ); ?>
			</h2>
			<p class="text-lg text-dark-600 dark:text-dark-300 max-w-3xl mx-auto">
				<?php esc_html_e( 'At AquaLuxe, our core values shape every decision we make and every interaction we have with our customers, partners, and the environment.', 'aqualuxe' ); ?>
			</p>
		</div>
		
		<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
			<!-- Value 1 -->
			<div class="value-card bg-gray-50 dark:bg-dark-750 rounded-lg p-8 transition-transform duration-300 hover:-translate-y-2">
				<div class="value-icon bg-primary-100 dark:bg-primary-900 text-primary-600 dark:text-primary-400 p-4 rounded-full inline-flex items-center justify-center mb-6">
					<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
					</svg>
				</div>
				<h3 class="text-xl font-medium mb-3"><?php esc_html_e( 'Excellence', 'aqualuxe' ); ?></h3>
				<p class="text-dark-600 dark:text-dark-300">
					<?php esc_html_e( 'We are committed to excellence in everything we do, from the quality of our products to the service we provide. We continuously strive to exceed expectations and set new standards in the industry.', 'aqualuxe' ); ?>
				</p>
			</div>
			
			<!-- Value 2 -->
			<div class="value-card bg-gray-50 dark:bg-dark-750 rounded-lg p-8 transition-transform duration-300 hover:-translate-y-2">
				<div class="value-icon bg-secondary-100 dark:bg-secondary-900 text-secondary-600 dark:text-secondary-400 p-4 rounded-full inline-flex items-center justify-center mb-6">
					<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
					</svg>
				</div>
				<h3 class="text-xl font-medium mb-3"><?php esc_html_e( 'Sustainability', 'aqualuxe' ); ?></h3>
				<p class="text-dark-600 dark:text-dark-300">
					<?php esc_html_e( 'We are dedicated to environmental stewardship and sustainable practices. We carefully source our specimens, support conservation efforts, and minimize our ecological footprint in all aspects of our business.', 'aqualuxe' ); ?>
				</p>
			</div>
			
			<!-- Value 3 -->
			<div class="value-card bg-gray-50 dark:bg-dark-750 rounded-lg p-8 transition-transform duration-300 hover:-translate-y-2">
				<div class="value-icon bg-accent-100 dark:bg-accent-900 text-accent-600 dark:text-accent-400 p-4 rounded-full inline-flex items-center justify-center mb-6">
					<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
					</svg>
				</div>
				<h3 class="text-xl font-medium mb-3"><?php esc_html_e( 'Innovation', 'aqualuxe' ); ?></h3>
				<p class="text-dark-600 dark:text-dark-300">
					<?php esc_html_e( 'We embrace innovation and continuously seek new ways to enhance the aquatic experience. From cutting-edge technology to creative design solutions, we push boundaries to deliver exceptional results.', 'aqualuxe' ); ?>
				</p>
			</div>
			
			<!-- Value 4 -->
			<div class="value-card bg-gray-50 dark:bg-dark-750 rounded-lg p-8 transition-transform duration-300 hover:-translate-y-2">
				<div class="value-icon bg-primary-100 dark:bg-primary-900 text-primary-600 dark:text-primary-400 p-4 rounded-full inline-flex items-center justify-center mb-6">
					<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
					</svg>
				</div>
				<h3 class="text-xl font-medium mb-3"><?php esc_html_e( 'Community', 'aqualuxe' ); ?></h3>
				<p class="text-dark-600 dark:text-dark-300">
					<?php esc_html_e( 'We foster a global community of aquatic enthusiasts, sharing knowledge and passion. We believe in collaboration, education, and building meaningful relationships with our customers and partners.', 'aqualuxe' ); ?>
				</p>
			</div>
			
			<!-- Value 5 -->
			<div class="value-card bg-gray-50 dark:bg-dark-750 rounded-lg p-8 transition-transform duration-300 hover:-translate-y-2">
				<div class="value-icon bg-secondary-100 dark:bg-secondary-900 text-secondary-600 dark:text-secondary-400 p-4 rounded-full inline-flex items-center justify-center mb-6">
					<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
					</svg>
				</div>
				<h3 class="text-xl font-medium mb-3"><?php esc_html_e( 'Expertise', 'aqualuxe' ); ?></h3>
				<p class="text-dark-600 dark:text-dark-300">
					<?php esc_html_e( 'We pride ourselves on our deep knowledge and expertise in aquatic sciences. Our team of specialists brings decades of combined experience to ensure the health and beauty of every ecosystem we create.', 'aqualuxe' ); ?>
				</p>
			</div>
			
			<!-- Value 6 -->
			<div class="value-card bg-gray-50 dark:bg-dark-750 rounded-lg p-8 transition-transform duration-300 hover:-translate-y-2">
				<div class="value-icon bg-accent-100 dark:bg-accent-900 text-accent-600 dark:text-accent-400 p-4 rounded-full inline-flex items-center justify-center mb-6">
					<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
					</svg>
				</div>
				<h3 class="text-xl font-medium mb-3"><?php esc_html_e( 'Integrity', 'aqualuxe' ); ?></h3>
				<p class="text-dark-600 dark:text-dark-300">
					<?php esc_html_e( 'We conduct our business with the highest level of integrity and transparency. We are honest in our communications, ethical in our practices, and accountable for our actions and commitments.', 'aqualuxe' ); ?>
				</p>
			</div>
		</div>
	</div>
</section>