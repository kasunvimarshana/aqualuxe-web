<?php
/**
 * Template part for displaying the history section on the About page
 *
 * @package AquaLuxe
 */

?>

<section id="history" class="about-history py-16 bg-gray-50 dark:bg-dark-850">
	<div class="container mx-auto px-4">
		<div class="text-center mb-12">
			<span class="inline-block px-3 py-1 bg-secondary-100 dark:bg-secondary-900 text-secondary-600 dark:text-secondary-400 rounded-full text-sm font-medium mb-4">
				<?php esc_html_e( 'Our History', 'aqualuxe' ); ?>
			</span>
			<h2 class="text-3xl md:text-4xl font-serif font-medium mb-4">
				<?php esc_html_e( 'The AquaLuxe Journey', 'aqualuxe' ); ?>
			</h2>
			<p class="text-lg text-dark-600 dark:text-dark-300 max-w-3xl mx-auto">
				<?php esc_html_e( 'From a small local aquarium shop to a global leader in luxury aquatics, our journey has been defined by passion, innovation, and excellence.', 'aqualuxe' ); ?>
			</p>
		</div>
		
		<div class="timeline relative">
			<!-- Timeline line -->
			<div class="timeline-line absolute left-1/2 transform -translate-x-1/2 w-1 bg-primary-200 dark:bg-primary-800 h-full"></div>
			
			<!-- Timeline items -->
			<div class="timeline-items relative z-10">
				<!-- Item 1 -->
				<div class="timeline-item flex flex-col md:flex-row items-center mb-16">
					<div class="timeline-content md:w-1/2 md:pr-12 md:text-right mb-6 md:mb-0">
						<span class="timeline-year inline-block px-4 py-1 bg-primary-600 text-white rounded-full text-sm font-medium mb-3">2010</span>
						<h3 class="text-xl font-medium mb-2"><?php esc_html_e( 'The Beginning', 'aqualuxe' ); ?></h3>
						<p class="text-dark-600 dark:text-dark-300">
							<?php esc_html_e( 'AquaLuxe was founded by marine biologist Dr. Elena Marino in San Francisco, starting as a boutique aquarium design service for high-end residential clients.', 'aqualuxe' ); ?>
						</p>
					</div>
					<div class="timeline-marker bg-white dark:bg-dark-700 border-4 border-primary-600 rounded-full h-6 w-6 absolute left-1/2 transform -translate-x-1/2"></div>
					<div class="timeline-image md:w-1/2 md:pl-12">
						<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/history-1.jpg' ); ?>" alt="<?php esc_attr_e( 'AquaLuxe Founding', 'aqualuxe' ); ?>" class="rounded-lg shadow-md w-full h-auto">
					</div>
				</div>
				
				<!-- Item 2 -->
				<div class="timeline-item flex flex-col md:flex-row-reverse items-center mb-16">
					<div class="timeline-content md:w-1/2 md:pl-12 mb-6 md:mb-0">
						<span class="timeline-year inline-block px-4 py-1 bg-primary-600 text-white rounded-full text-sm font-medium mb-3">2013</span>
						<h3 class="text-xl font-medium mb-2"><?php esc_html_e( 'First Retail Location', 'aqualuxe' ); ?></h3>
						<p class="text-dark-600 dark:text-dark-300">
							<?php esc_html_e( 'Opening our flagship store in San Francisco\'s design district, we expanded our offerings to include rare and exotic aquatic species, premium equipment, and maintenance services.', 'aqualuxe' ); ?>
						</p>
					</div>
					<div class="timeline-marker bg-white dark:bg-dark-700 border-4 border-primary-600 rounded-full h-6 w-6 absolute left-1/2 transform -translate-x-1/2"></div>
					<div class="timeline-image md:w-1/2 md:pr-12 md:text-right">
						<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/history-2.jpg' ); ?>" alt="<?php esc_attr_e( 'First Retail Location', 'aqualuxe' ); ?>" class="rounded-lg shadow-md w-full h-auto">
					</div>
				</div>
				
				<!-- Item 3 -->
				<div class="timeline-item flex flex-col md:flex-row items-center mb-16">
					<div class="timeline-content md:w-1/2 md:pr-12 md:text-right mb-6 md:mb-0">
						<span class="timeline-year inline-block px-4 py-1 bg-primary-600 text-white rounded-full text-sm font-medium mb-3">2016</span>
						<h3 class="text-xl font-medium mb-2"><?php esc_html_e( 'International Expansion', 'aqualuxe' ); ?></h3>
						<p class="text-dark-600 dark:text-dark-300">
							<?php esc_html_e( 'AquaLuxe launched its e-commerce platform and began shipping internationally, bringing our premium products and expertise to enthusiasts around the world.', 'aqualuxe' ); ?>
						</p>
					</div>
					<div class="timeline-marker bg-white dark:bg-dark-700 border-4 border-primary-600 rounded-full h-6 w-6 absolute left-1/2 transform -translate-x-1/2"></div>
					<div class="timeline-image md:w-1/2 md:pl-12">
						<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/history-3.jpg' ); ?>" alt="<?php esc_attr_e( 'International Expansion', 'aqualuxe' ); ?>" class="rounded-lg shadow-md w-full h-auto">
					</div>
				</div>
				
				<!-- Item 4 -->
				<div class="timeline-item flex flex-col md:flex-row-reverse items-center mb-16">
					<div class="timeline-content md:w-1/2 md:pl-12 mb-6 md:mb-0">
						<span class="timeline-year inline-block px-4 py-1 bg-primary-600 text-white rounded-full text-sm font-medium mb-3">2019</span>
						<h3 class="text-xl font-medium mb-2"><?php esc_html_e( 'Conservation Initiative', 'aqualuxe' ); ?></h3>
						<p class="text-dark-600 dark:text-dark-300">
							<?php esc_html_e( 'We launched the AquaLuxe Conservation Foundation, dedicating a portion of all sales to marine conservation efforts and sustainable aquaculture research.', 'aqualuxe' ); ?>
						</p>
					</div>
					<div class="timeline-marker bg-white dark:bg-dark-700 border-4 border-primary-600 rounded-full h-6 w-6 absolute left-1/2 transform -translate-x-1/2"></div>
					<div class="timeline-image md:w-1/2 md:pr-12 md:text-right">
						<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/history-4.jpg' ); ?>" alt="<?php esc_attr_e( 'Conservation Initiative', 'aqualuxe' ); ?>" class="rounded-lg shadow-md w-full h-auto">
					</div>
				</div>
				
				<!-- Item 5 -->
				<div class="timeline-item flex flex-col md:flex-row items-center">
					<div class="timeline-content md:w-1/2 md:pr-12 md:text-right mb-6 md:mb-0">
						<span class="timeline-year inline-block px-4 py-1 bg-primary-600 text-white rounded-full text-sm font-medium mb-3">2023</span>
						<h3 class="text-xl font-medium mb-2"><?php esc_html_e( 'Global Recognition', 'aqualuxe' ); ?></h3>
						<p class="text-dark-600 dark:text-dark-300">
							<?php esc_html_e( 'AquaLuxe was recognized as the leading luxury aquatic retailer worldwide, with showrooms in major cities and a network of certified installation partners across six continents.', 'aqualuxe' ); ?>
						</p>
					</div>
					<div class="timeline-marker bg-white dark:bg-dark-700 border-4 border-primary-600 rounded-full h-6 w-6 absolute left-1/2 transform -translate-x-1/2"></div>
					<div class="timeline-image md:w-1/2 md:pl-12">
						<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/history-5.jpg' ); ?>" alt="<?php esc_attr_e( 'Global Recognition', 'aqualuxe' ); ?>" class="rounded-lg shadow-md w-full h-auto">
					</div>
				</div>
			</div>
		</div>
	</div>
</section>