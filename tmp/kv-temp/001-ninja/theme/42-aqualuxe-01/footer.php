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

	<footer id="colophon" class="site-footer bg-primary-900 text-white pt-16 pb-8">
		<div class="container mx-auto px-4">
			<div class="footer-widgets grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
				<div class="footer-widget">
					<?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
						<?php dynamic_sidebar( 'footer-1' ); ?>
					<?php else : ?>
						<div class="footer-logo mb-4">
							<?php
							if ( has_custom_logo() ) :
								$custom_logo_id = get_theme_mod( 'custom_logo' );
								$logo = wp_get_attachment_image_src( $custom_logo_id, 'full' );
								?>
								<img src="<?php echo esc_url( $logo[0] ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" class="max-h-12">
							<?php else : ?>
								<h3 class="text-2xl font-bold text-white"><?php bloginfo( 'name' ); ?></h3>
							<?php endif; ?>
						</div>
						<div class="footer-about mb-4">
							<p><?php echo esc_html( get_theme_mod( 'aqualuxe_footer_about', 'AquaLuxe offers premium water-themed products and services with elegance and sophistication. Our commitment to quality and sustainability sets us apart in the industry.' ) ); ?></p>
						</div>
						<div class="footer-contact">
							<div class="flex items-start mb-2">
								<i class="fas fa-map-marker-alt mt-1 mr-3 text-primary-300"></i>
								<span><?php echo esc_html( get_theme_mod( 'aqualuxe_address', '123 Water Street, Oceanview, CA 90210' ) ); ?></span>
							</div>
							<div class="flex items-center mb-2">
								<i class="fas fa-phone-alt mr-3 text-primary-300"></i>
								<span><?php echo esc_html( get_theme_mod( 'aqualuxe_phone_number', '+1 (555) 123-4567' ) ); ?></span>
							</div>
							<div class="flex items-center">
								<i class="fas fa-envelope mr-3 text-primary-300"></i>
								<span><?php echo esc_html( get_theme_mod( 'aqualuxe_email', 'info@aqualuxe.com' ) ); ?></span>
							</div>
						</div>
					<?php endif; ?>
				</div>
				
				<div class="footer-widget">
					<?php if ( is_active_sidebar( 'footer-2' ) ) : ?>
						<?php dynamic_sidebar( 'footer-2' ); ?>
					<?php else : ?>
						<h3 class="text-xl font-bold mb-6 text-white"><?php esc_html_e( 'Quick Links', 'aqualuxe' ); ?></h3>
						<?php
						wp_nav_menu(
							array(
								'theme_location' => 'footer',
								'menu_id'        => 'footer-menu',
								'container'      => false,
								'menu_class'     => 'footer-links',
								'depth'          => 1,
								'fallback_cb'    => false,
							)
						);
						?>
					<?php endif; ?>
				</div>
				
				<div class="footer-widget">
					<?php if ( is_active_sidebar( 'footer-3' ) ) : ?>
						<?php dynamic_sidebar( 'footer-3' ); ?>
					<?php else : ?>
						<h3 class="text-xl font-bold mb-6 text-white"><?php esc_html_e( 'Our Services', 'aqualuxe' ); ?></h3>
						<ul class="footer-services">
							<li class="mb-3"><a href="#" class="hover:text-primary-300 transition-colors"><?php esc_html_e( 'Water Features', 'aqualuxe' ); ?></a></li>
							<li class="mb-3"><a href="#" class="hover:text-primary-300 transition-colors"><?php esc_html_e( 'Pool Design', 'aqualuxe' ); ?></a></li>
							<li class="mb-3"><a href="#" class="hover:text-primary-300 transition-colors"><?php esc_html_e( 'Spa Installation', 'aqualuxe' ); ?></a></li>
							<li class="mb-3"><a href="#" class="hover:text-primary-300 transition-colors"><?php esc_html_e( 'Fountain Maintenance', 'aqualuxe' ); ?></a></li>
							<li class="mb-3"><a href="#" class="hover:text-primary-300 transition-colors"><?php esc_html_e( 'Water Purification', 'aqualuxe' ); ?></a></li>
							<li><a href="#" class="hover:text-primary-300 transition-colors"><?php esc_html_e( 'Aquatic Landscaping', 'aqualuxe' ); ?></a></li>
						</ul>
					<?php endif; ?>
				</div>
				
				<div class="footer-widget">
					<?php if ( is_active_sidebar( 'footer-4' ) ) : ?>
						<?php dynamic_sidebar( 'footer-4' ); ?>
					<?php else : ?>
						<h3 class="text-xl font-bold mb-6 text-white"><?php esc_html_e( 'Newsletter', 'aqualuxe' ); ?></h3>
						<p class="mb-4"><?php esc_html_e( 'Subscribe to our newsletter to receive updates and special offers.', 'aqualuxe' ); ?></p>
						<form class="newsletter-form">
							<div class="flex flex-col space-y-3">
								<input type="email" placeholder="<?php esc_attr_e( 'Your Email Address', 'aqualuxe' ); ?>" class="bg-primary-800 border border-primary-700 rounded-md px-4 py-2 text-white placeholder-primary-400 focus:outline-none focus:ring-2 focus:ring-primary-300">
								<button type="submit" class="bg-primary-600 hover:bg-primary-500 text-white font-medium py-2 px-4 rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-primary-300">
									<?php esc_html_e( 'Subscribe', 'aqualuxe' ); ?>
								</button>
							</div>
						</form>
						
						<h3 class="text-xl font-bold mt-8 mb-4 text-white"><?php esc_html_e( 'Follow Us', 'aqualuxe' ); ?></h3>
						<div class="social-links flex space-x-4">
							<?php if ( get_theme_mod( 'aqualuxe_facebook' ) ) : ?>
								<a href="<?php echo esc_url( get_theme_mod( 'aqualuxe_facebook' ) ); ?>" target="_blank" rel="noopener noreferrer" class="bg-primary-800 hover:bg-primary-700 w-10 h-10 rounded-full flex items-center justify-center transition-colors">
									<i class="fab fa-facebook-f"></i>
								</a>
							<?php endif; ?>
							
							<?php if ( get_theme_mod( 'aqualuxe_twitter' ) ) : ?>
								<a href="<?php echo esc_url( get_theme_mod( 'aqualuxe_twitter' ) ); ?>" target="_blank" rel="noopener noreferrer" class="bg-primary-800 hover:bg-primary-700 w-10 h-10 rounded-full flex items-center justify-center transition-colors">
									<i class="fab fa-twitter"></i>
								</a>
							<?php endif; ?>
							
							<?php if ( get_theme_mod( 'aqualuxe_instagram' ) ) : ?>
								<a href="<?php echo esc_url( get_theme_mod( 'aqualuxe_instagram' ) ); ?>" target="_blank" rel="noopener noreferrer" class="bg-primary-800 hover:bg-primary-700 w-10 h-10 rounded-full flex items-center justify-center transition-colors">
									<i class="fab fa-instagram"></i>
								</a>
							<?php endif; ?>
							
							<?php if ( get_theme_mod( 'aqualuxe_pinterest' ) ) : ?>
								<a href="<?php echo esc_url( get_theme_mod( 'aqualuxe_pinterest' ) ); ?>" target="_blank" rel="noopener noreferrer" class="bg-primary-800 hover:bg-primary-700 w-10 h-10 rounded-full flex items-center justify-center transition-colors">
									<i class="fab fa-pinterest-p"></i>
								</a>
							<?php endif; ?>
							
							<?php if ( get_theme_mod( 'aqualuxe_youtube' ) ) : ?>
								<a href="<?php echo esc_url( get_theme_mod( 'aqualuxe_youtube' ) ); ?>" target="_blank" rel="noopener noreferrer" class="bg-primary-800 hover:bg-primary-700 w-10 h-10 rounded-full flex items-center justify-center transition-colors">
									<i class="fab fa-youtube"></i>
								</a>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
			
			<div class="footer-bottom pt-8 border-t border-primary-800">
				<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
					<div class="footer-copyright text-center md:text-left">
						<p>
							&copy; <?php echo esc_html( date( 'Y' ) ); ?> 
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="text-primary-300 hover:text-primary-200 transition-colors">
								<?php bloginfo( 'name' ); ?>
							</a>
							. <?php esc_html_e( 'All Rights Reserved.', 'aqualuxe' ); ?>
						</p>
					</div>
					<div class="footer-payment-methods text-center md:text-right">
						<?php if ( get_theme_mod( 'aqualuxe_show_payment_methods', true ) ) : ?>
							<div class="payment-methods flex justify-center md:justify-end space-x-2">
								<span class="bg-primary-800 p-1 rounded"><i class="fab fa-cc-visa text-xl"></i></span>
								<span class="bg-primary-800 p-1 rounded"><i class="fab fa-cc-mastercard text-xl"></i></span>
								<span class="bg-primary-800 p-1 rounded"><i class="fab fa-cc-amex text-xl"></i></span>
								<span class="bg-primary-800 p-1 rounded"><i class="fab fa-cc-paypal text-xl"></i></span>
								<span class="bg-primary-800 p-1 rounded"><i class="fab fa-cc-apple-pay text-xl"></i></span>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php if ( get_theme_mod( 'aqualuxe_back_to_top', true ) ) : ?>
	<button id="back-to-top" class="fixed bottom-8 right-8 bg-primary-600 hover:bg-primary-500 text-white w-12 h-12 rounded-full flex items-center justify-center shadow-lg transition-all opacity-0 invisible z-50" aria-label="<?php esc_attr_e( 'Back to top', 'aqualuxe' ); ?>">
		<i class="fas fa-chevron-up"></i>
	</button>
<?php endif; ?>

<?php wp_footer(); ?>

</body>
</html>