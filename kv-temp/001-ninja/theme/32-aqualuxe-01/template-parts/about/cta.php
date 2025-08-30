<?php
/**
 * Template part for displaying the call-to-action section on the About page
 *
 * @package AquaLuxe
 */

?>

<section class="about-cta py-16 bg-primary-900 text-white">
	<div class="container mx-auto px-4">
		<div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
			<div class="about-cta-content">
				<h2 class="text-3xl md:text-4xl font-serif font-medium mb-6">
					<?php esc_html_e( 'Ready to Transform Your Space with Aquatic Elegance?', 'aqualuxe' ); ?>
				</h2>
				<p class="text-lg text-white text-opacity-90 mb-8">
					<?php esc_html_e( 'Whether you\'re looking for a stunning centerpiece for your home, a captivating installation for your business, or expert advice on aquatic ecosystems, our team is here to help.', 'aqualuxe' ); ?>
				</p>
				<div class="flex flex-wrap gap-4">
					<a href="<?php echo esc_url( home_url( '/services' ) ); ?>" class="btn bg-white text-primary-900 hover:bg-gray-100">
						<?php esc_html_e( 'Explore Our Services', 'aqualuxe' ); ?>
					</a>
					<a href="<?php echo esc_url( home_url( '/contact' ) ); ?>" class="btn btn-outline text-white border-white hover:bg-white hover:text-primary-900">
						<?php esc_html_e( 'Contact Us', 'aqualuxe' ); ?>
					</a>
				</div>
			</div>
			
			<div class="about-cta-testimonial">
				<div class="bg-white bg-opacity-10 rounded-lg p-8 backdrop-blur-sm">
					<div class="testimonial-rating flex text-amber-400 mb-4">
						<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
							<path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
						</svg>
						<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
							<path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
						</svg>
						<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
							<path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
						</svg>
						<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
							<path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
						</svg>
						<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
							<path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
						</svg>
					</div>
					
					<blockquote class="mb-6">
						<p class="text-lg italic mb-4">
							"Working with AquaLuxe was an extraordinary experience. Their team transformed our hotel lobby with a breathtaking reef installation that has become the centerpiece of our space. The attention to detail, expertise, and ongoing support have exceeded all expectations."
						</p>
						<footer class="flex items-center">
							<div class="testimonial-avatar mr-4">
								<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/testimonial-featured.jpg' ); ?>" alt="<?php esc_attr_e( 'Robert Anderson', 'aqualuxe' ); ?>" class="w-12 h-12 rounded-full object-cover">
							</div>
							<div>
								<cite class="font-medium block not-italic"><?php esc_html_e( 'Robert Anderson', 'aqualuxe' ); ?></cite>
								<span class="text-sm text-white text-opacity-80"><?php esc_html_e( 'General Manager, The Grand Azure Hotel', 'aqualuxe' ); ?></span>
							</div>
						</footer>
					</blockquote>
					
					<a href="<?php echo esc_url( home_url( '/testimonials' ) ); ?>" class="inline-flex items-center text-white font-medium hover:text-primary-200 transition-colors">
						<?php esc_html_e( 'Read More Success Stories', 'aqualuxe' ); ?>
						<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
						</svg>
					</a>
				</div>
			</div>
		</div>
	</div>
</section>