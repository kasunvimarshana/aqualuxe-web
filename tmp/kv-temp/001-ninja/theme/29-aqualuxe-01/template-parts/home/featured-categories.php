<?php
/**
 * Template part for displaying featured categories on the homepage
 *
 * @package AquaLuxe
 */

?>

<section class="featured-categories py-16 bg-gray-50 dark:bg-dark-850">
	<div class="container mx-auto px-4">
		<div class="text-center mb-12">
			<h2 class="text-3xl md:text-4xl font-serif font-medium mb-4"><?php esc_html_e( 'Explore Our Collections', 'aqualuxe' ); ?></h2>
			<p class="text-lg text-dark-600 dark:text-dark-300 max-w-3xl mx-auto"><?php esc_html_e( 'Discover our curated selection of premium aquatic categories, each offering a unique underwater experience.', 'aqualuxe' ); ?></p>
		</div>
		
		<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
			<!-- Category 1 -->
			<div class="category-card group">
				<a href="#" class="block relative overflow-hidden rounded-lg shadow-md">
					<div class="aspect-w-4 aspect-h-3">
						<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/category-1.jpg' ); ?>" alt="<?php esc_attr_e( 'Exotic Fish', 'aqualuxe' ); ?>" class="object-cover w-full h-full transition-transform duration-500 group-hover:scale-110">
					</div>
					<div class="absolute inset-0 bg-gradient-to-t from-dark-900 to-transparent opacity-70"></div>
					<div class="absolute bottom-0 left-0 right-0 p-6 text-white">
						<h3 class="text-2xl font-serif font-medium mb-2"><?php esc_html_e( 'Exotic Fish', 'aqualuxe' ); ?></h3>
						<p class="mb-4 text-white text-opacity-90"><?php esc_html_e( 'Rare and vibrant species from around the world', 'aqualuxe' ); ?></p>
						<span class="inline-flex items-center text-sm font-medium transition-colors group-hover:text-primary-400">
							<?php esc_html_e( 'Explore Collection', 'aqualuxe' ); ?>
							<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
							</svg>
						</span>
					</div>
				</a>
			</div>
			
			<!-- Category 2 -->
			<div class="category-card group">
				<a href="#" class="block relative overflow-hidden rounded-lg shadow-md">
					<div class="aspect-w-4 aspect-h-3">
						<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/category-2.jpg' ); ?>" alt="<?php esc_attr_e( 'Aquatic Plants', 'aqualuxe' ); ?>" class="object-cover w-full h-full transition-transform duration-500 group-hover:scale-110">
					</div>
					<div class="absolute inset-0 bg-gradient-to-t from-dark-900 to-transparent opacity-70"></div>
					<div class="absolute bottom-0 left-0 right-0 p-6 text-white">
						<h3 class="text-2xl font-serif font-medium mb-2"><?php esc_html_e( 'Aquatic Plants', 'aqualuxe' ); ?></h3>
						<p class="mb-4 text-white text-opacity-90"><?php esc_html_e( 'Lush greenery to create stunning underwater landscapes', 'aqualuxe' ); ?></p>
						<span class="inline-flex items-center text-sm font-medium transition-colors group-hover:text-primary-400">
							<?php esc_html_e( 'Explore Collection', 'aqualuxe' ); ?>
							<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
							</svg>
						</span>
					</div>
				</a>
			</div>
			
			<!-- Category 3 -->
			<div class="category-card group">
				<a href="#" class="block relative overflow-hidden rounded-lg shadow-md">
					<div class="aspect-w-4 aspect-h-3">
						<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/category-3.jpg' ); ?>" alt="<?php esc_attr_e( 'Premium Aquariums', 'aqualuxe' ); ?>" class="object-cover w-full h-full transition-transform duration-500 group-hover:scale-110">
					</div>
					<div class="absolute inset-0 bg-gradient-to-t from-dark-900 to-transparent opacity-70"></div>
					<div class="absolute bottom-0 left-0 right-0 p-6 text-white">
						<h3 class="text-2xl font-serif font-medium mb-2"><?php esc_html_e( 'Premium Aquariums', 'aqualuxe' ); ?></h3>
						<p class="mb-4 text-white text-opacity-90"><?php esc_html_e( 'Designer tanks and complete ecosystem solutions', 'aqualuxe' ); ?></p>
						<span class="inline-flex items-center text-sm font-medium transition-colors group-hover:text-primary-400">
							<?php esc_html_e( 'Explore Collection', 'aqualuxe' ); ?>
							<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
							</svg>
						</span>
					</div>
				</a>
			</div>
		</div>
		
		<div class="text-center mt-12">
			<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="btn btn-outline">
				<?php esc_html_e( 'View All Categories', 'aqualuxe' ); ?>
			</a>
		</div>
	</div>
</section>