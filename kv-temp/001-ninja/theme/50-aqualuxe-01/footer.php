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

	<footer id="colophon" class="site-footer">
		<div class="container">
			<div class="footer-widgets">
				<div class="footer-widget">
					<?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
						<?php dynamic_sidebar( 'footer-1' ); ?>
					<?php else : ?>
						<h3 class="widget-title"><?php esc_html_e( 'About AquaLuxe', 'aqualuxe' ); ?></h3>
						<p><?php esc_html_e( 'Bringing elegance to aquatic life – globally. Premium ornamental fish, aquatic plants, and custom aquarium solutions for collectors and enthusiasts.', 'aqualuxe' ); ?></p>
						<?php if ( function_exists( 'aqualuxe_social_icons' ) ) : ?>
							<?php aqualuxe_social_icons(); ?>
						<?php endif; ?>
					<?php endif; ?>
				</div>
				
				<div class="footer-widget">
					<?php if ( is_active_sidebar( 'footer-2' ) ) : ?>
						<?php dynamic_sidebar( 'footer-2' ); ?>
					<?php else : ?>
						<h3 class="widget-title"><?php esc_html_e( 'Quick Links', 'aqualuxe' ); ?></h3>
						<ul>
							<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'aqualuxe' ); ?></a></li>
							<li><a href="<?php echo esc_url( home_url( '/about' ) ); ?>"><?php esc_html_e( 'About Us', 'aqualuxe' ); ?></a></li>
							<li><a href="<?php echo esc_url( home_url( '/services' ) ); ?>"><?php esc_html_e( 'Services', 'aqualuxe' ); ?></a></li>
							<li><a href="<?php echo esc_url( home_url( '/blog' ) ); ?>"><?php esc_html_e( 'Blog', 'aqualuxe' ); ?></a></li>
							<li><a href="<?php echo esc_url( home_url( '/contact' ) ); ?>"><?php esc_html_e( 'Contact', 'aqualuxe' ); ?></a></li>
							<?php if ( class_exists( 'WooCommerce' ) ) : ?>
								<li><a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>"><?php esc_html_e( 'Shop', 'aqualuxe' ); ?></a></li>
							<?php endif; ?>
						</ul>
					<?php endif; ?>
				</div>
				
				<div class="footer-widget">
					<?php if ( is_active_sidebar( 'footer-3' ) ) : ?>
						<?php dynamic_sidebar( 'footer-3' ); ?>
					<?php else : ?>
						<h3 class="widget-title"><?php esc_html_e( 'Contact Us', 'aqualuxe' ); ?></h3>
						<ul class="contact-info">
							<li>
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
									<path fill-rule="evenodd" d="M11.54 22.351l.07.04.028.016a.76.76 0 00.723 0l.028-.015.071-.041a16.975 16.975 0 001.144-.742 19.58 19.58 0 002.683-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 00-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.58 19.58 0 002.682 2.282 16.975 16.975 0 001.145.742zM12 13.5a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
								</svg>
								<?php esc_html_e( '123 Aquarium Street, Palo Alto, CA 94301', 'aqualuxe' ); ?>
							</li>
							<li>
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
									<path fill-rule="evenodd" d="M1.5 4.5a3 3 0 013-3h1.372c.86 0 1.61.586 1.819 1.42l1.105 4.423a1.875 1.875 0 01-.694 1.955l-1.293.97c-.135.101-.164.249-.126.352a11.285 11.285 0 006.697 6.697c.103.038.25.009.352-.126l.97-1.293a1.875 1.875 0 011.955-.694l4.423 1.105c.834.209 1.42.959 1.42 1.82V19.5a3 3 0 01-3 3h-2.25C8.552 22.5 1.5 15.448 1.5 6.75V4.5z" clip-rule="evenodd" />
								</svg>
								<?php esc_html_e( '+1 (555) 123-4567', 'aqualuxe' ); ?>
							</li>
							<li>
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
									<path d="M1.5 8.67v8.58a3 3 0 003 3h15a3 3 0 003-3V8.67l-8.928 5.493a3 3 0 01-3.144 0L1.5 8.67z" />
									<path d="M22.5 6.908V6.75a3 3 0 00-3-3h-15a3 3 0 00-3 3v.158l9.714 5.978a1.5 1.5 0 001.572 0L22.5 6.908z" />
								</svg>
								<a href="mailto:info@aqualuxe.com"><?php esc_html_e( 'info@aqualuxe.com', 'aqualuxe' ); ?></a>
							</li>
						</ul>
					<?php endif; ?>
				</div>
				
				<div class="footer-widget">
					<?php if ( is_active_sidebar( 'footer-4' ) ) : ?>
						<?php dynamic_sidebar( 'footer-4' ); ?>
					<?php else : ?>
						<h3 class="widget-title"><?php esc_html_e( 'Newsletter', 'aqualuxe' ); ?></h3>
						<p><?php esc_html_e( 'Subscribe to our newsletter for the latest updates on rare fish arrivals, aquascaping tips, and exclusive offers.', 'aqualuxe' ); ?></p>
						<form class="newsletter-form">
							<input type="email" placeholder="<?php esc_attr_e( 'Your email address', 'aqualuxe' ); ?>" required>
							<button type="submit"><?php esc_html_e( 'Subscribe', 'aqualuxe' ); ?></button>
						</form>
					<?php endif; ?>
				</div>
			</div><!-- .footer-widgets -->
			
			<div class="footer-bottom">
				<div class="footer-copyright">
					<?php
					/* translators: %s: Current year and site name */
					printf( esc_html__( '© %s AquaLuxe. All rights reserved.', 'aqualuxe' ), date_i18n( 'Y' ) );
					?>
				</div>
				
				<div class="footer-menu">
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'footer',
							'menu_id'        => 'footer-menu',
							'container'      => false,
							'depth'          => 1,
							'fallback_cb'    => false,
						)
					);
					?>
				</div>
			</div><!-- .footer-bottom -->
		</div><!-- .container -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php if ( class_exists( 'WooCommerce' ) && function_exists( 'aqualuxe_quick_view_modal' ) ) : ?>
	<?php aqualuxe_quick_view_modal(); ?>
<?php endif; ?>

<?php wp_footer(); ?>

</body>
</html>