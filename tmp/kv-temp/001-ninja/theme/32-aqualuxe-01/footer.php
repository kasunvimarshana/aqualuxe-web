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

	<footer id="colophon" class="site-footer bg-dark-800 text-white py-8 sm:py-12" role="contentinfo">
		<div class="container mx-auto px-4">
			<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
				<div class="footer-widget">
					<?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
						<?php dynamic_sidebar( 'footer-1' ); ?>
					<?php else : ?>
						<div class="footer-branding">
							<?php if ( has_custom_logo() ) : ?>
								<?php the_custom_logo(); ?>
							<?php else : ?>
								<h2 class="text-xl sm:text-2xl font-serif font-bold text-white mb-4"><?php bloginfo( 'name' ); ?></h2>
							<?php endif; ?>
							<p class="text-dark-200 mb-6"><?php bloginfo( 'description' ); ?></p>
						</div>
						<div class="footer-social flex space-x-4">
							<a href="#" class="text-dark-200 hover:text-primary-400 transition-colors duration-200" aria-label="<?php esc_attr_e( 'Facebook', 'aqualuxe' ); ?>">
								<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
									<path d="M22.675 0h-21.35c-.732 0-1.325.593-1.325 1.325v21.351c0 .731.593 1.324 1.325 1.324h11.495v-9.294h-3.128v-3.622h3.128v-2.671c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.12v9.293h6.116c.73 0 1.323-.593 1.323-1.325v-21.35c0-.732-.593-1.325-1.325-1.325z"/>
								</svg>
							</a>
							<a href="#" class="text-dark-200 hover:text-primary-400 transition-colors duration-200" aria-label="<?php esc_attr_e( 'Instagram', 'aqualuxe' ); ?>">
								<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
									<path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
								</svg>
							</a>
							<a href="#" class="text-dark-200 hover:text-primary-400 transition-colors duration-200" aria-label="<?php esc_attr_e( 'Twitter', 'aqualuxe' ); ?>">
								<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
									<path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
								</svg>
							</a>
							<a href="#" class="text-dark-200 hover:text-primary-400 transition-colors duration-200" aria-label="<?php esc_attr_e( 'YouTube', 'aqualuxe' ); ?>">
								<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
									<path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/>
								</svg>
							</a>
						</div>
					<?php endif; ?>
				</div>
				
				<div class="footer-widget">
					<?php if ( is_active_sidebar( 'footer-2' ) ) : ?>
						<?php dynamic_sidebar( 'footer-2' ); ?>
					<?php else : ?>
						<h2 class="text-lg font-medium mb-4 pb-2 border-b border-dark-700"><?php esc_html_e( 'Quick Links', 'aqualuxe' ); ?></h2>
						<ul class="space-y-2">
							<li><a href="#" class="text-dark-200 hover:text-primary-400 transition-colors duration-200"><?php esc_html_e( 'About Us', 'aqualuxe' ); ?></a></li>
							<li><a href="#" class="text-dark-200 hover:text-primary-400 transition-colors duration-200"><?php esc_html_e( 'Services', 'aqualuxe' ); ?></a></li>
							<li><a href="#" class="text-dark-200 hover:text-primary-400 transition-colors duration-200"><?php esc_html_e( 'Blog', 'aqualuxe' ); ?></a></li>
							<li><a href="#" class="text-dark-200 hover:text-primary-400 transition-colors duration-200"><?php esc_html_e( 'Contact', 'aqualuxe' ); ?></a></li>
							<li><a href="#" class="text-dark-200 hover:text-primary-400 transition-colors duration-200"><?php esc_html_e( 'FAQ', 'aqualuxe' ); ?></a></li>
						</ul>
					<?php endif; ?>
				</div>
				
				<div class="footer-widget">
					<?php if ( is_active_sidebar( 'footer-3' ) ) : ?>
						<?php dynamic_sidebar( 'footer-3' ); ?>
					<?php else : ?>
						<h2 class="text-lg font-medium mb-4 pb-2 border-b border-dark-700"><?php esc_html_e( 'Shop', 'aqualuxe' ); ?></h2>
						<ul class="space-y-2">
							<li><a href="#" class="text-dark-200 hover:text-primary-400 transition-colors duration-200"><?php esc_html_e( 'All Products', 'aqualuxe' ); ?></a></li>
							<li><a href="#" class="text-dark-200 hover:text-primary-400 transition-colors duration-200"><?php esc_html_e( 'Featured Products', 'aqualuxe' ); ?></a></li>
							<li><a href="#" class="text-dark-200 hover:text-primary-400 transition-colors duration-200"><?php esc_html_e( 'New Arrivals', 'aqualuxe' ); ?></a></li>
							<li><a href="#" class="text-dark-200 hover:text-primary-400 transition-colors duration-200"><?php esc_html_e( 'Sale Items', 'aqualuxe' ); ?></a></li>
							<li><a href="#" class="text-dark-200 hover:text-primary-400 transition-colors duration-200"><?php esc_html_e( 'My Account', 'aqualuxe' ); ?></a></li>
						</ul>
					<?php endif; ?>
				</div>
				
				<div class="footer-widget">
					<?php if ( is_active_sidebar( 'footer-4' ) ) : ?>
						<?php dynamic_sidebar( 'footer-4' ); ?>
					<?php else : ?>
						<h2 class="text-lg font-medium mb-4 pb-2 border-b border-dark-700"><?php esc_html_e( 'Contact Us', 'aqualuxe' ); ?></h2>
						<ul class="space-y-4">
							<li class="flex items-start">
								<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary-400 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
								</svg>
								<span class="text-dark-200">123 Aquarium Street, Ocean City, CA 90210</span>
							</li>
							<li class="flex items-start">
								<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary-400 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
								</svg>
								<a href="mailto:info@aqualuxe.example.com" class="text-dark-200 hover:text-primary-400 transition-colors duration-200 break-all">info@aqualuxe.example.com</a>
							</li>
							<li class="flex items-start">
								<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary-400 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
								</svg>
								<a href="tel:+15551234567" class="text-dark-200 hover:text-primary-400 transition-colors duration-200">+1 (555) 123-4567</a>
							</li>
							<li class="flex items-start">
								<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary-400 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
								</svg>
								<div class="text-dark-200">
									<p class="mb-1"><?php esc_html_e( 'Monday - Friday:', 'aqualuxe' ); ?> 9am - 6pm</p>
									<p class="mb-1"><?php esc_html_e( 'Saturday:', 'aqualuxe' ); ?> 10am - 4pm</p>
									<p><?php esc_html_e( 'Sunday:', 'aqualuxe' ); ?> <?php esc_html_e( 'Closed', 'aqualuxe' ); ?></p>
								</div>
							</li>
						</ul>
					<?php endif; ?>
				</div>
			</div>
			
			<div class="border-t border-dark-700 mt-8 pt-8">
				<div class="flex flex-col md:flex-row justify-between items-center">
					<div class="footer-copyright text-dark-300 text-sm mb-4 md:mb-0 text-center md:text-left">
						<?php
						/* translators: %1$s: current year, %2$s: site name */
						printf( esc_html__( '© %1$s %2$s. All rights reserved.', 'aqualuxe' ), date_i18n( 'Y' ), get_bloginfo( 'name' ) );
						?>
					</div>
					
					<nav class="footer-menu" aria-label="<?php esc_attr_e( 'Footer Navigation', 'aqualuxe' ); ?>">
						<?php
						wp_nav_menu(
							array(
								'theme_location' => 'footer',
								'menu_id'        => 'footer-menu',
								'container'      => false,
								'menu_class'     => 'flex flex-wrap justify-center space-x-4 text-sm',
								'fallback_cb'    => false,
								'depth'          => 1,
							)
						);
						?>
					</nav>
				</div>
			</div>
		</div>
		
		<!-- Back to top button -->
		<button id="back-to-top" class="back-to-top fixed bottom-8 right-8 bg-primary-600 text-white p-2 rounded-full shadow-lg opacity-0 invisible transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-primary-400 focus:ring-offset-2" aria-label="<?php esc_attr_e( 'Back to top', 'aqualuxe' ); ?>">
			<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
				<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
			</svg>
		</button>
	</footer><!-- #colophon -->

<?php if ( class_exists( 'WooCommerce' ) && function_exists( 'aqualuxe_quick_view_modal' ) ) : ?>
	<?php aqualuxe_quick_view_modal(); ?>
<?php endif; ?>

<?php wp_footer(); ?>

</body>
</html>