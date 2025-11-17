<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package AquaLuxe
 */

?>

	</div><!-- #content -->

	<?php if ( get_theme_mod( 'aqualuxe_enable_newsletter', true ) ) : ?>
	<section id="newsletter" class="bg-primary-700 text-white py-12">
		<div class="container mx-auto px-4">
			<div class="max-w-3xl mx-auto text-center">
				<h3 class="text-2xl md:text-3xl font-bold mb-4"><?php echo esc_html( get_theme_mod( 'aqualuxe_newsletter_title', __( 'Subscribe to Our Newsletter', 'aqualuxe' ) ) ); ?></h3>
				<p class="text-lg mb-6"><?php echo esc_html( get_theme_mod( 'aqualuxe_newsletter_text', __( 'Stay updated with our latest products, special offers, and aquarium care tips.', 'aqualuxe' ) ) ); ?></p>
				
				<div class="newsletter-form">
					<?php 
					$newsletter_shortcode = get_theme_mod( 'aqualuxe_newsletter_shortcode' );
					if ( $newsletter_shortcode ) {
						echo do_shortcode( $newsletter_shortcode );
					} else {
					?>
					<form class="flex flex-col sm:flex-row gap-4 justify-center">
						<input type="email" placeholder="<?php esc_attr_e( 'Your email address', 'aqualuxe' ); ?>" class="px-4 py-3 rounded-md w-full sm:w-auto flex-grow text-gray-900">
						<button type="submit" class="bg-white text-primary-700 hover:bg-gray-100 px-6 py-3 rounded-md font-medium"><?php esc_html_e( 'Subscribe', 'aqualuxe' ); ?></button>
					</form>
					<?php } ?>
				</div>
			</div>
		</div>
	</section>
	<?php endif; ?>

	<footer id="colophon" class="site-footer bg-gray-900 text-white pt-12 pb-6">
		<div class="container mx-auto px-4">
			<div class="footer-widgets grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
				<div class="footer-widget">
					<?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
						<?php dynamic_sidebar( 'footer-1' ); ?>
					<?php else : ?>
						<div class="widget">
							<?php if ( has_custom_logo() ) : ?>
								<div class="footer-logo mb-4">
									<?php the_custom_logo(); ?>
								</div>
							<?php else : ?>
								<h4 class="text-xl font-bold mb-4"><?php bloginfo( 'name' ); ?></h4>
							<?php endif; ?>
							<p class="mb-4"><?php echo esc_html( get_theme_mod( 'aqualuxe_footer_about', __( 'Premium aquarium products and services for fish enthusiasts around the world.', 'aqualuxe' ) ) ); ?></p>
							
							<?php if ( get_theme_mod( 'aqualuxe_enable_social_icons', true ) ) : ?>
							<div class="social-icons flex space-x-3 mt-4">
								<?php if ( get_theme_mod( 'aqualuxe_social_facebook' ) ) : ?>
								<a href="<?php echo esc_url( get_theme_mod( 'aqualuxe_social_facebook' ) ); ?>" target="_blank" rel="noopener noreferrer" class="hover:text-primary-400">
									<span class="sr-only"><?php esc_html_e( 'Facebook', 'aqualuxe' ); ?></span>
									<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
										<path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/>
									</svg>
								</a>
								<?php endif; ?>
								
								<?php if ( get_theme_mod( 'aqualuxe_social_instagram' ) ) : ?>
								<a href="<?php echo esc_url( get_theme_mod( 'aqualuxe_social_instagram' ) ); ?>" target="_blank" rel="noopener noreferrer" class="hover:text-primary-400">
									<span class="sr-only"><?php esc_html_e( 'Instagram', 'aqualuxe' ); ?></span>
									<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
										<path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
									</svg>
								</a>
								<?php endif; ?>
								
								<?php if ( get_theme_mod( 'aqualuxe_social_twitter' ) ) : ?>
								<a href="<?php echo esc_url( get_theme_mod( 'aqualuxe_social_twitter' ) ); ?>" target="_blank" rel="noopener noreferrer" class="hover:text-primary-400">
									<span class="sr-only"><?php esc_html_e( 'Twitter', 'aqualuxe' ); ?></span>
									<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
										<path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
									</svg>
								</a>
								<?php endif; ?>
								
								<?php if ( get_theme_mod( 'aqualuxe_social_youtube' ) ) : ?>
								<a href="<?php echo esc_url( get_theme_mod( 'aqualuxe_social_youtube' ) ); ?>" target="_blank" rel="noopener noreferrer" class="hover:text-primary-400">
									<span class="sr-only"><?php esc_html_e( 'YouTube', 'aqualuxe' ); ?></span>
									<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
										<path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/>
									</svg>
								</a>
								<?php endif; ?>
							</div>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				</div>
				
				<div class="footer-widget">
					<?php if ( is_active_sidebar( 'footer-2' ) ) : ?>
						<?php dynamic_sidebar( 'footer-2' ); ?>
					<?php else : ?>
						<div class="widget">
							<h4 class="text-xl font-bold mb-4"><?php esc_html_e( 'Quick Links', 'aqualuxe' ); ?></h4>
							<?php
							wp_nav_menu(
								array(
									'theme_location' => 'footer',
									'menu_id'        => 'footer-menu',
									'container'      => false,
									'depth'          => 1,
									'menu_class'     => 'footer-links space-y-2',
									'fallback_cb'    => false,
								)
							);
							?>
						</div>
					<?php endif; ?>
				</div>
				
				<div class="footer-widget">
					<?php if ( is_active_sidebar( 'footer-3' ) ) : ?>
						<?php dynamic_sidebar( 'footer-3' ); ?>
					<?php else : ?>
						<div class="widget">
							<h4 class="text-xl font-bold mb-4"><?php esc_html_e( 'Contact Us', 'aqualuxe' ); ?></h4>
							<ul class="footer-contact space-y-3">
								<?php if ( get_theme_mod( 'aqualuxe_contact_address' ) ) : ?>
								<li class="flex">
									<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
									</svg>
									<span><?php echo nl2br( esc_html( get_theme_mod( 'aqualuxe_contact_address' ) ) ); ?></span>
								</li>
								<?php endif; ?>
								
								<?php if ( get_theme_mod( 'aqualuxe_contact_phone' ) ) : ?>
								<li class="flex">
									<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
									</svg>
									<span><?php echo esc_html( get_theme_mod( 'aqualuxe_contact_phone' ) ); ?></span>
								</li>
								<?php endif; ?>
								
								<?php if ( get_theme_mod( 'aqualuxe_contact_email' ) ) : ?>
								<li class="flex">
									<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
									</svg>
									<a href="mailto:<?php echo esc_attr( get_theme_mod( 'aqualuxe_contact_email' ) ); ?>" class="hover:text-primary-400">
										<?php echo esc_html( get_theme_mod( 'aqualuxe_contact_email' ) ); ?>
									</a>
								</li>
								<?php endif; ?>
								
								<?php if ( get_theme_mod( 'aqualuxe_contact_hours' ) ) : ?>
								<li class="flex">
									<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
									</svg>
									<span><?php echo nl2br( esc_html( get_theme_mod( 'aqualuxe_contact_hours' ) ) ); ?></span>
								</li>
								<?php endif; ?>
							</ul>
						</div>
					<?php endif; ?>
				</div>
				
				<div class="footer-widget">
					<?php if ( is_active_sidebar( 'footer-4' ) ) : ?>
						<?php dynamic_sidebar( 'footer-4' ); ?>
					<?php else : ?>
						<div class="widget">
							<h4 class="text-xl font-bold mb-4"><?php esc_html_e( 'Payment Methods', 'aqualuxe' ); ?></h4>
							<div class="payment-methods flex flex-wrap gap-2">
								<div class="payment-method p-2 bg-gray-800 rounded">
									<svg class="h-8 w-auto" viewBox="0 0 24 24" fill="currentColor">
										<path d="M20 4H4c-1.11 0-1.99.89-1.99 2L2 18c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z"/>
									</svg>
								</div>
								<div class="payment-method p-2 bg-gray-800 rounded">
									<svg class="h-8 w-auto" viewBox="0 0 24 24" fill="currentColor">
										<path d="M20 4H4c-1.11 0-1.99.89-1.99 2L2 18c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm-1 14H5c-.55 0-1-.45-1-1V8h16v9c0 .55-.45 1-1 1z"/>
									</svg>
								</div>
								<div class="payment-method p-2 bg-gray-800 rounded">
									<svg class="h-8 w-auto" viewBox="0 0 24 24" fill="currentColor">
										<path d="M19 14V6c0-1.1-.9-2-2-2H3c-1.1 0-2 .9-2 2v8c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zm-2 0H3V6h14v8zm-7-7c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3zm13 0v11c0 1.1-.9 2-2 2H4v-2h17V7h2z"/>
									</svg>
								</div>
								<div class="payment-method p-2 bg-gray-800 rounded">
									<svg class="h-8 w-auto" viewBox="0 0 24 24" fill="currentColor">
										<path d="M20 4H4c-1.11 0-1.99.89-1.99 2L2 18c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z"/>
									</svg>
								</div>
							</div>
							
							<?php if ( get_theme_mod( 'aqualuxe_enable_shipping_info', true ) ) : ?>
							<div class="shipping-info mt-6">
								<h5 class="font-bold mb-2"><?php esc_html_e( 'Shipping', 'aqualuxe' ); ?></h5>
								<p class="text-sm text-gray-400"><?php echo esc_html( get_theme_mod( 'aqualuxe_shipping_text', __( 'We ship worldwide with express and standard options available.', 'aqualuxe' ) ) ); ?></p>
							</div>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
			
			<div class="footer-bottom pt-6 border-t border-gray-800">
				<div class="flex flex-col md:flex-row justify-between items-center">
					<div class="copyright text-sm text-gray-400 mb-4 md:mb-0">
						<?php
						$copyright_text = get_theme_mod( 'aqualuxe_copyright_text', '© {year} {site_name}. All rights reserved.' );
						$copyright_text = str_replace( '{year}', date( 'Y' ), $copyright_text );
						$copyright_text = str_replace( '{site_name}', get_bloginfo( 'name' ), $copyright_text );
						echo wp_kses_post( $copyright_text );
						?>
					</div>
					
					<?php if ( get_theme_mod( 'aqualuxe_enable_footer_menu', true ) ) : ?>
					<div class="footer-menu">
						<ul class="flex flex-wrap gap-x-6 gap-y-2 text-sm text-gray-400">
							<?php if ( get_privacy_policy_url() ) : ?>
							<li><a href="<?php echo esc_url( get_privacy_policy_url() ); ?>" class="hover:text-white"><?php esc_html_e( 'Privacy Policy', 'aqualuxe' ); ?></a></li>
							<?php endif; ?>
							
							<li><a href="<?php echo esc_url( home_url( '/terms-conditions/' ) ); ?>" class="hover:text-white"><?php esc_html_e( 'Terms & Conditions', 'aqualuxe' ); ?></a></li>
							
							<li><a href="<?php echo esc_url( home_url( '/shipping-returns/' ) ); ?>" class="hover:text-white"><?php esc_html_e( 'Shipping & Returns', 'aqualuxe' ); ?></a></li>
							
							<li><a href="<?php echo esc_url( home_url( '/faq/' ) ); ?>" class="hover:text-white"><?php esc_html_e( 'FAQ', 'aqualuxe' ); ?></a></li>
						</ul>
					</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>