<?php
/**
 * Template part for displaying the sustainability section on the About page
 *
 * @package AquaLuxe
 */

?>

<section id="sustainability" class="about-sustainability py-16 bg-gray-50 dark:bg-dark-850">
	<div class="container mx-auto px-4">
		<div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
			<div class="about-sustainability-image">
				<div class="relative">
					<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/about-sustainability.jpg' ); ?>" alt="<?php esc_attr_e( 'AquaLuxe Sustainability', 'aqualuxe' ); ?>" class="rounded-lg shadow-lg w-full h-auto">
					<div class="absolute -bottom-6 -right-6 bg-secondary-600 text-white p-6 rounded-lg shadow-lg max-w-xs hidden md:block">
						<p class="text-lg font-medium mb-2"><?php esc_html_e( '"We believe luxury and sustainability can coexist beautifully."', 'aqualuxe' ); ?></p>
						<p class="text-sm text-white text-opacity-80"><?php esc_html_e( '— AquaLuxe Conservation Team', 'aqualuxe' ); ?></p>
					</div>
				</div>
			</div>
			
			<div class="about-sustainability-content">
				<span class="inline-block px-3 py-1 bg-secondary-100 dark:bg-secondary-900 text-secondary-600 dark:text-secondary-400 rounded-full text-sm font-medium mb-4">
					<?php esc_html_e( 'Sustainability', 'aqualuxe' ); ?>
				</span>
				<h2 class="text-3xl md:text-4xl font-serif font-medium mb-6">
					<?php esc_html_e( 'Our Commitment to the Planet', 'aqualuxe' ); ?>
				</h2>
				<div class="prose dark:prose-invert max-w-none mb-8">
					<p class="text-lg">
						<?php esc_html_e( 'Sustainability is at the core of everything we do at AquaLuxe. We recognize our responsibility to protect and preserve the natural environments that inspire our work.', 'aqualuxe' ); ?>
					</p>
					<p>
						<?php esc_html_e( 'Our comprehensive sustainability program encompasses ethical sourcing practices, conservation initiatives, and environmentally responsible operations. We work closely with marine biologists, conservation organizations, and local communities to ensure our business practices contribute positively to aquatic ecosystems worldwide.', 'aqualuxe' ); ?>
					</p>
				</div>
				
				<div class="sustainability-initiatives space-y-6">
					<div class="initiative flex">
						<div class="initiative-icon bg-secondary-100 dark:bg-secondary-900 text-secondary-600 dark:text-secondary-400 p-3 rounded-full inline-flex items-center justify-center mr-4 shrink-0">
							<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
							</svg>
						</div>
						<div>
							<h3 class="text-lg font-medium mb-2"><?php esc_html_e( 'Ethical Sourcing', 'aqualuxe' ); ?></h3>
							<p class="text-dark-600 dark:text-dark-300">
								<?php esc_html_e( 'We work exclusively with suppliers who adhere to strict ethical standards. Over 80% of our fish species are aquacultured rather than wild-caught, and all wild specimens are collected using sustainable methods that protect reef ecosystems.', 'aqualuxe' ); ?>
							</p>
						</div>
					</div>
					
					<div class="initiative flex">
						<div class="initiative-icon bg-secondary-100 dark:bg-secondary-900 text-secondary-600 dark:text-secondary-400 p-3 rounded-full inline-flex items-center justify-center mr-4 shrink-0">
							<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
							</svg>
						</div>
						<div>
							<h3 class="text-lg font-medium mb-2"><?php esc_html_e( 'Conservation Foundation', 'aqualuxe' ); ?></h3>
							<p class="text-dark-600 dark:text-dark-300">
								<?php esc_html_e( 'The AquaLuxe Conservation Foundation funds research, education, and direct action projects focused on marine conservation. We contribute 5% of all profits to these initiatives, supporting coral reef restoration, sustainable aquaculture research, and marine protected areas.', 'aqualuxe' ); ?>
							</p>
						</div>
					</div>
					
					<div class="initiative flex">
						<div class="initiative-icon bg-secondary-100 dark:bg-secondary-900 text-secondary-600 dark:text-secondary-400 p-3 rounded-full inline-flex items-center justify-center mr-4 shrink-0">
							<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
							</svg>
						</div>
						<div>
							<h3 class="text-lg font-medium mb-2"><?php esc_html_e( 'Carbon-Neutral Operations', 'aqualuxe' ); ?></h3>
							<p class="text-dark-600 dark:text-dark-300">
								<?php esc_html_e( 'We\'ve implemented comprehensive measures to reduce our carbon footprint, including energy-efficient facilities, renewable energy sources, and carbon offset programs for shipping. Our headquarters and all retail locations are certified carbon-neutral.', 'aqualuxe' ); ?>
							</p>
						</div>
					</div>
				</div>
				
				<div class="mt-8">
					<a href="<?php echo esc_url( home_url( '/about/sustainability' ) ); ?>" class="btn btn-secondary">
						<?php esc_html_e( 'Learn More About Our Initiatives', 'aqualuxe' ); ?>
					</a>
				</div>
			</div>
		</div>
	</div>
</section>