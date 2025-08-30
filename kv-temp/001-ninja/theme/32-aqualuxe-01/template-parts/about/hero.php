<?php
/**
 * Template part for displaying the hero section on the About page
 *
 * @package AquaLuxe
 */

?>

<section class="about-hero relative py-24 bg-cover bg-center" style="background-image: url('<?php echo esc_url( get_template_directory_uri() . '/assets/images/about-hero.jpg' ); ?>');">
	<div class="absolute inset-0 bg-dark-900 bg-opacity-60"></div>
	<div class="container mx-auto px-4 relative z-10">
		<div class="max-w-3xl mx-auto text-center text-white">
			<h1 class="text-4xl md:text-5xl lg:text-6xl font-serif font-medium mb-6 animate-on-scroll" data-animation="fadeInUp" data-delay="0.2s">
				<?php esc_html_e( 'About AquaLuxe', 'aqualuxe' ); ?>
			</h1>
			<p class="text-xl md:text-2xl mb-8 text-white text-opacity-90 animate-on-scroll" data-animation="fadeInUp" data-delay="0.4s">
				<?php esc_html_e( 'Bringing elegance to aquatic life – globally', 'aqualuxe' ); ?>
			</p>
			<div class="flex justify-center space-x-4 animate-on-scroll" data-animation="fadeInUp" data-delay="0.6s">
				<a href="#mission" class="btn btn-primary">
					<?php esc_html_e( 'Our Mission', 'aqualuxe' ); ?>
				</a>
				<a href="#team" class="btn btn-outline text-white border-white hover:bg-white hover:text-dark-800">
					<?php esc_html_e( 'Meet Our Team', 'aqualuxe' ); ?>
				</a>
			</div>
		</div>
	</div>
</section>