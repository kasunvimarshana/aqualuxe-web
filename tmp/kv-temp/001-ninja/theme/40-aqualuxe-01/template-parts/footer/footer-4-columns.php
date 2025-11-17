<?php
/**
 * Template part for displaying the 4-columns footer
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package AquaLuxe
 */

?>

<div class="footer-widgets">
	<div class="container">
		<div class="footer-widgets-inner">
			<div class="footer-widget-area footer-widget-area-1">
				<?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
					<?php dynamic_sidebar( 'footer-1' ); ?>
				<?php else : ?>
					<div class="widget widget_text">
						<h3 class="widget-title"><?php esc_html_e( 'About AquaLuxe', 'aqualuxe' ); ?></h3>
						<div class="textwidget">
							<p><?php esc_html_e( 'AquaLuxe is a premium aquatic retailer specializing in rare and exotic fish species, high-quality aquarium equipment, and professional aquascaping services.', 'aqualuxe' ); ?></p>
							<div class="footer-logo">
								<?php if ( has_custom_logo() ) : ?>
									<?php the_custom_logo(); ?>
								<?php else : ?>
									<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
								<?php endif; ?>
							</div>
						</div>
					</div>
				<?php endif; ?>
			</div><!-- .footer-widget-area-1 -->

			<div class="footer-widget-area footer-widget-area-2">
				<?php if ( is_active_sidebar( 'footer-2' ) ) : ?>
					<?php dynamic_sidebar( 'footer-2' ); ?>
				<?php else : ?>
					<div class="widget widget_nav_menu">
						<h3 class="widget-title"><?php esc_html_e( 'Quick Links', 'aqualuxe' ); ?></h3>
						<div class="menu-footer-menu-container">
							<ul id="menu-footer-menu" class="menu">
								<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'aqualuxe' ); ?></a></li>
								<li><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>"><?php esc_html_e( 'About Us', 'aqualuxe' ); ?></a></li>
								<li><a href="<?php echo esc_url( home_url( '/services/' ) ); ?>"><?php esc_html_e( 'Services', 'aqualuxe' ); ?></a></li>
								<li><a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>"><?php esc_html_e( 'Blog', 'aqualuxe' ); ?></a></li>
								<li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Contact', 'aqualuxe' ); ?></a></li>
							</ul>
						</div>
					</div>
				<?php endif; ?>
			</div><!-- .footer-widget-area-2 -->

			<div class="footer-widget-area footer-widget-area-3">
				<?php if ( is_active_sidebar( 'footer-3' ) ) : ?>
					<?php dynamic_sidebar( 'footer-3' ); ?>
				<?php else : ?>
					<div class="widget widget_nav_menu">
						<h3 class="widget-title"><?php esc_html_e( 'Shop', 'aqualuxe' ); ?></h3>
						<div class="menu-footer-shop-menu-container">
							<ul id="menu-footer-shop-menu" class="menu">
								<?php if ( aqualuxe_is_woocommerce_active() ) : ?>
									<li><a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>"><?php esc_html_e( 'All Products', 'aqualuxe' ); ?></a></li>
									<li><a href="<?php echo esc_url( get_term_link( 'fish', 'product_cat' ) ); ?>"><?php esc_html_e( 'Fish', 'aqualuxe' ); ?></a></li>
									<li><a href="<?php echo esc_url( get_term_link( 'plants', 'product_cat' ) ); ?>"><?php esc_html_e( 'Plants', 'aqualuxe' ); ?></a></li>
									<li><a href="<?php echo esc_url( get_term_link( 'equipment', 'product_cat' ) ); ?>"><?php esc_html_e( 'Equipment', 'aqualuxe' ); ?></a></li>
									<li><a href="<?php echo esc_url( get_term_link( 'accessories', 'product_cat' ) ); ?>"><?php esc_html_e( 'Accessories', 'aqualuxe' ); ?></a></li>
								<?php else : ?>
									<li><a href="<?php echo esc_url( home_url( '/shop/' ) ); ?>"><?php esc_html_e( 'All Products', 'aqualuxe' ); ?></a></li>
									<li><a href="<?php echo esc_url( home_url( '/product-category/fish/' ) ); ?>"><?php esc_html_e( 'Fish', 'aqualuxe' ); ?></a></li>
									<li><a href="<?php echo esc_url( home_url( '/product-category/plants/' ) ); ?>"><?php esc_html_e( 'Plants', 'aqualuxe' ); ?></a></li>
									<li><a href="<?php echo esc_url( home_url( '/product-category/equipment/' ) ); ?>"><?php esc_html_e( 'Equipment', 'aqualuxe' ); ?></a></li>
									<li><a href="<?php echo esc_url( home_url( '/product-category/accessories/' ) ); ?>"><?php esc_html_e( 'Accessories', 'aqualuxe' ); ?></a></li>
								<?php endif; ?>
							</ul>
						</div>
					</div>
				<?php endif; ?>
			</div><!-- .footer-widget-area-3 -->

			<div class="footer-widget-area footer-widget-area-4">
				<?php if ( is_active_sidebar( 'footer-4' ) ) : ?>
					<?php dynamic_sidebar( 'footer-4' ); ?>
				<?php else : ?>
					<div class="widget widget_contact_info">
						<h3 class="widget-title"><?php esc_html_e( 'Contact Us', 'aqualuxe' ); ?></h3>
						<div class="contact-info">
							<p class="contact-address">
								<i class="icon-map-marker"></i>
								<?php esc_html_e( '123 Aquarium Street, Ocean City, CA 90210', 'aqualuxe' ); ?>
							</p>
							<p class="contact-phone">
								<i class="icon-phone"></i>
								<a href="tel:+1234567890"><?php esc_html_e( '+1 (234) 567-890', 'aqualuxe' ); ?></a>
							</p>
							<p class="contact-email">
								<i class="icon-envelope"></i>
								<a href="mailto:info@aqualuxe.com"><?php esc_html_e( 'info@aqualuxe.com', 'aqualuxe' ); ?></a>
							</p>
							<p class="contact-hours">
								<i class="icon-clock"></i>
								<?php esc_html_e( 'Mon - Fri: 9:00 AM - 6:00 PM', 'aqualuxe' ); ?>
							</p>
						</div>
						<div class="social-icons">
							<a href="#" target="_blank" rel="noopener noreferrer"><i class="icon-facebook"></i></a>
							<a href="#" target="_blank" rel="noopener noreferrer"><i class="icon-twitter"></i></a>
							<a href="#" target="_blank" rel="noopener noreferrer"><i class="icon-instagram"></i></a>
							<a href="#" target="_blank" rel="noopener noreferrer"><i class="icon-youtube"></i></a>
						</div>
					</div>
				<?php endif; ?>
			</div><!-- .footer-widget-area-4 -->
		</div><!-- .footer-widgets-inner -->
	</div><!-- .container -->
</div><!-- .footer-widgets -->

<?php if ( get_theme_mod( 'aqualuxe_footer_newsletter', true ) ) : ?>
	<div class="footer-newsletter">
		<div class="container">
			<div class="footer-newsletter-inner">
				<div class="newsletter-content">
					<h3><?php esc_html_e( 'Subscribe to Our Newsletter', 'aqualuxe' ); ?></h3>
					<p><?php esc_html_e( 'Stay updated with our latest products, special offers, and aquatic care tips.', 'aqualuxe' ); ?></p>
				</div>
				<div class="newsletter-form">
					<form action="#" method="post" class="newsletter-form-inner">
						<input type="email" name="email" placeholder="<?php esc_attr_e( 'Your email address', 'aqualuxe' ); ?>" required>
						<button type="submit"><?php esc_html_e( 'Subscribe', 'aqualuxe' ); ?></button>
					</form>
				</div>
			</div><!-- .footer-newsletter-inner -->
		</div><!-- .container -->
	</div><!-- .footer-newsletter -->
<?php endif; ?>