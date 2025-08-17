<?php
/**
 * Template part for displaying the mission section on the About page
 *
 * @package AquaLuxe
 */

?>

<section id="mission" class="about-mission py-16 bg-white dark:bg-dark-800">
	<div class="container mx-auto px-4">
		<div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
			<div class="about-mission-content">
				<span class="inline-block px-3 py-1 bg-primary-100 dark:bg-primary-900 text-primary-600 dark:text-primary-400 rounded-full text-sm font-medium mb-4">
					<?php esc_html_e( 'Our Mission', 'aqualuxe' ); ?>
				</span>
				<h2 class="text-3xl md:text-4xl font-serif font-medium mb-6">
					<?php esc_html_e( 'Elevating Aquatic Experiences Through Excellence', 'aqualuxe' ); ?>
				</h2>
				<div class="prose dark:prose-invert max-w-none mb-8">
					<p class="text-lg">
						<?php esc_html_e( 'At AquaLuxe, our mission is to transform aquatic environments into living works of art that inspire wonder and connection with the natural world.', 'aqualuxe' ); ?>
					</p>
					<p>
						<?php esc_html_e( 'We are dedicated to providing the highest quality aquatic specimens, premium equipment, and expert services to enthusiasts, collectors, and institutions worldwide. Through ethical sourcing, sustainable practices, and unparalleled expertise, we strive to set the global standard for luxury aquatic retail.', 'aqualuxe' ); ?>
					</p>
					<p>
						<?php esc_html_e( 'Our commitment extends beyond commerce to conservation, education, and the advancement of aquatic sciences. We believe that by fostering appreciation for the beauty and complexity of aquatic ecosystems, we contribute to their preservation for future generations.', 'aqualuxe' ); ?>
					</p>
				</div>
				<div class="flex flex-wrap gap-4">
					<div class="mission-stat bg-gray-50 dark:bg-dark-750 rounded-lg p-6 text-center flex-1">
						<span class="block text-3xl font-bold text-primary-600 dark:text-primary-400 mb-2">15+</span>
						<span class="block text-dark-600 dark:text-dark-300"><?php esc_html_e( 'Years of Experience', 'aqualuxe' ); ?></span>
					</div>
					<div class="mission-stat bg-gray-50 dark:bg-dark-750 rounded-lg p-6 text-center flex-1">
						<span class="block text-3xl font-bold text-primary-600 dark:text-primary-400 mb-2">60+</span>
						<span class="block text-dark-600 dark:text-dark-300"><?php esc_html_e( 'Countries Served', 'aqualuxe' ); ?></span>
					</div>
					<div class="mission-stat bg-gray-50 dark:bg-dark-750 rounded-lg p-6 text-center flex-1">
						<span class="block text-3xl font-bold text-primary-600 dark:text-primary-400 mb-2">5000+</span>
						<span class="block text-dark-600 dark:text-dark-300"><?php esc_html_e( 'Species Available', 'aqualuxe' ); ?></span>
					</div>
				</div>
			</div>
			<div class="about-mission-image">
				<div class="relative">
					<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/about-mission.jpg' ); ?>" alt="<?php esc_attr_e( 'AquaLuxe Mission', 'aqualuxe' ); ?>" class="rounded-lg shadow-lg w-full h-auto">
					<div class="absolute -bottom-6 -left-6 bg-primary-600 text-white p-6 rounded-lg shadow-lg max-w-xs hidden md:block">
						<p class="text-lg font-medium mb-2"><?php esc_html_e( '"Our passion is bringing the ocean\'s wonders to your doorstep."', 'aqualuxe' ); ?></p>
						<p class="text-sm text-white text-opacity-80"><?php esc_html_e( '— Dr. Elena Marino, Founder', 'aqualuxe' ); ?></p>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>