<?php
/**
 * The template for displaying the footer
 *
 * @package AquaLuxe
 */
?>

	<footer id="colophon" class="site-footer bg-gray-900 text-white">
		<!-- Main Footer -->
		<div class="footer-main py-16">
			<div class="container mx-auto px-4">
				<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
					<!-- Footer Widget 1 -->
					<div class="footer-widget">
						<?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
							<?php dynamic_sidebar( 'footer-1' ); ?>
						<?php else : ?>
							<div class="widget">
								<h3 class="widget-title text-xl font-semibold mb-4"><?php bloginfo( 'name' ); ?></h3>
								<p class="text-gray-300 mb-6"><?php echo esc_html( get_bloginfo( 'description' ) ); ?></p>
								<?php echo aqualuxe_social_icons(); ?>
							</div>
						<?php endif; ?>
					</div>

					<!-- Footer Widget 2 -->
					<div class="footer-widget">
						<?php if ( is_active_sidebar( 'footer-2' ) ) : ?>
							<?php dynamic_sidebar( 'footer-2' ); ?>
						<?php else : ?>
							<div class="widget">
								<h3 class="widget-title text-xl font-semibold mb-4"><?php esc_html_e( 'Quick Links', 'aqualuxe' ); ?></h3>
								<ul class="space-y-2 text-gray-300">
									<li><a href="<?php echo esc_url( home_url( '/about' ) ); ?>" class="hover:text-primary-400 transition-colors duration-200"><?php esc_html_e( 'About Us', 'aqualuxe' ); ?></a></li>
									<?php if ( aqualuxe_is_woocommerce_active() ) : ?>
									<li><a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="hover:text-primary-400 transition-colors duration-200"><?php esc_html_e( 'Shop', 'aqualuxe' ); ?></a></li>
									<?php endif; ?>
									<li><a href="<?php echo esc_url( home_url( '/services' ) ); ?>" class="hover:text-primary-400 transition-colors duration-200"><?php esc_html_e( 'Services', 'aqualuxe' ); ?></a></li>
									<li><a href="<?php echo esc_url( home_url( '/contact' ) ); ?>" class="hover:text-primary-400 transition-colors duration-200"><?php esc_html_e( 'Contact', 'aqualuxe' ); ?></a></li>
								</ul>
							</div>
						<?php endif; ?>
					</div>

					<!-- Footer Widget 3 -->
					<div class="footer-widget">
						<?php if ( is_active_sidebar( 'footer-3' ) ) : ?>
							<?php dynamic_sidebar( 'footer-3' ); ?>
						<?php else : ?>
							<?php if ( aqualuxe_is_woocommerce_active() ) : ?>
							<div class="widget">
								<h3 class="widget-title text-xl font-semibold mb-4"><?php esc_html_e( 'Categories', 'aqualuxe' ); ?></h3>
								<ul class="space-y-2 text-gray-300">
									<?php
									$product_categories = get_terms( array(
										'taxonomy' => 'product_cat',
										'hide_empty' => true,
										'number' => 6,
									) );
									
									if ( ! is_wp_error( $product_categories ) && ! empty( $product_categories ) ) :
										foreach ( $product_categories as $category ) :
									?>
									<li><a href="<?php echo esc_url( get_term_link( $category ) ); ?>" class="hover:text-primary-400 transition-colors duration-200"><?php echo esc_html( $category->name ); ?></a></li>
									<?php
										endforeach;
									endif;
									?>
								</ul>
							</div>
							<?php else : ?>
							<div class="widget">
								<h3 class="widget-title text-xl font-semibold mb-4"><?php esc_html_e( 'Services', 'aqualuxe' ); ?></h3>
								<ul class="space-y-2 text-gray-300">
									<li><a href="#" class="hover:text-primary-400 transition-colors duration-200"><?php esc_html_e( 'Aquarium Design', 'aqualuxe' ); ?></a></li>
									<li><a href="#" class="hover:text-primary-400 transition-colors duration-200"><?php esc_html_e( 'Maintenance', 'aqualuxe' ); ?></a></li>
									<li><a href="#" class="hover:text-primary-400 transition-colors duration-200"><?php esc_html_e( 'Consultation', 'aqualuxe' ); ?></a></li>
									<li><a href="#" class="hover:text-primary-400 transition-colors duration-200"><?php esc_html_e( 'Export Services', 'aqualuxe' ); ?></a></li>
								</ul>
							</div>
							<?php endif; ?>
						<?php endif; ?>
					</div>

					<!-- Footer Widget 4 -->
					<div class="footer-widget">
						<?php if ( is_active_sidebar( 'footer-4' ) ) : ?>
							<?php dynamic_sidebar( 'footer-4' ); ?>
						<?php else : ?>
							<div class="widget">
								<h3 class="widget-title text-xl font-semibold mb-4"><?php esc_html_e( 'Contact Info', 'aqualuxe' ); ?></h3>
								<div class="space-y-4 text-gray-300">
									<?php $phone = aqualuxe_get_option( 'contact_phone' ); ?>
									<?php if ( $phone ) : ?>
									<div class="flex items-start space-x-3">
										<svg class="w-5 h-5 mt-1 text-primary-400" fill="currentColor" viewBox="0 0 20 20">
											<path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
										</svg>
										<a href="tel:<?php echo esc_attr( $phone ); ?>" class="hover:text-primary-400 transition-colors duration-200"><?php echo esc_html( $phone ); ?></a>
									</div>
									<?php endif; ?>
									
									<?php $email = aqualuxe_get_option( 'contact_email' ); ?>
									<?php if ( $email ) : ?>
									<div class="flex items-start space-x-3">
										<svg class="w-5 h-5 mt-1 text-primary-400" fill="currentColor" viewBox="0 0 20 20">
											<path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
											<path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
										</svg>
										<a href="mailto:<?php echo esc_attr( $email ); ?>" class="hover:text-primary-400 transition-colors duration-200"><?php echo esc_html( $email ); ?></a>
									</div>
									<?php endif; ?>
									
									<?php $address = aqualuxe_get_option( 'contact_address' ); ?>
									<?php if ( $address ) : ?>
									<div class="flex items-start space-x-3">
										<svg class="w-5 h-5 mt-1 text-primary-400" fill="currentColor" viewBox="0 0 20 20">
											<path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
										</svg>
										<address class="not-italic"><?php echo wp_kses_post( nl2br( $address ) ); ?></address>
									</div>
									<?php endif; ?>
								</div>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>

		<!-- Newsletter Section -->
		<?php if ( aqualuxe_get_option( 'show_newsletter', true ) ) : ?>
		<div class="newsletter-section bg-primary-600 py-12">
			<div class="container mx-auto px-4">
				<div class="max-w-2xl mx-auto text-center">
					<h3 class="text-2xl font-bold text-white mb-2"><?php echo esc_html( aqualuxe_get_option( 'newsletter_title', __( 'Stay Updated', 'aqualuxe' ) ) ); ?></h3>
					<p class="text-primary-100 mb-6"><?php echo esc_html( aqualuxe_get_option( 'newsletter_description', __( 'Get the latest news about aquatic care, new arrivals, and exclusive offers.', 'aqualuxe' ) ) ); ?></p>
					
					<form class="newsletter-form flex flex-col sm:flex-row gap-3 max-w-md mx-auto" action="#" method="post">
						<input 
							type="email" 
							name="email" 
							placeholder="<?php esc_attr_e( 'Enter your email', 'aqualuxe' ); ?>" 
							class="flex-1 px-4 py-3 rounded-lg border-0 text-gray-900 placeholder-gray-500 focus:ring-2 focus:ring-white focus:ring-opacity-20 focus:outline-none"
							required
						>
						<button 
							type="submit" 
							class="px-6 py-3 bg-white text-primary-600 font-semibold rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-white focus:ring-opacity-20 transition-colors duration-200"
						>
							<?php esc_html_e( 'Subscribe', 'aqualuxe' ); ?>
						</button>
						<?php wp_nonce_field( 'aqualuxe_newsletter', 'newsletter_nonce' ); ?>
					</form>
				</div>
			</div>
		</div>
		<?php endif; ?>

		<!-- Footer Bottom -->
		<div class="footer-bottom bg-gray-800 py-6 border-t border-gray-700">
			<div class="container mx-auto px-4">
				<div class="flex flex-col md:flex-row items-center justify-between">
					<div class="copyright text-gray-400 text-sm mb-4 md:mb-0">
						<?php
						printf(
							/* translators: 1: Copyright symbol, 2: Current year, 3: Site name */
							esc_html__( '%1$s %2$s %3$s. All rights reserved.', 'aqualuxe' ),
							'&copy;',
							date( 'Y' ),
							get_bloginfo( 'name' )
						);
						?>
					</div>
					
					<?php
					wp_nav_menu( array(
						'theme_location' => 'footer',
						'menu_id'        => 'footer-menu',
						'menu_class'     => 'footer-menu flex flex-wrap items-center space-x-6 text-sm',
						'container'      => false,
						'fallback_cb'    => false,
					) );
					?>
				</div>
			</div>
		</div>
	</footer><!-- #colophon -->

	<!-- Back to Top -->
	<button 
		type="button" 
		class="back-to-top fixed bottom-6 right-6 bg-primary-600 text-white p-3 rounded-full shadow-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-opacity-50 transition-all duration-300 transform translate-y-16 opacity-0"
		aria-label="<?php esc_attr_e( 'Back to top', 'aqualuxe' ); ?>"
		x-data="{ show: false }"
		x-show="show"
		x-transition
		@scroll.window="show = window.pageYOffset > 300"
		@click="window.scrollTo({ top: 0, behavior: 'smooth' })"
	>
		<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
			<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
		</svg>
	</button>
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>