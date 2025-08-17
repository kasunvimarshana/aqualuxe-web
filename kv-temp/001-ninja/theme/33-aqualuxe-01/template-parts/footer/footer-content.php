<?php
/**
 * Template part for displaying the footer content
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package AquaLuxe
 */

// Get footer layout from theme mod
$aqualuxe_footer_layout = get_theme_mod( 'aqualuxe_footer_layout', '4-columns' );

// Set column classes based on layout
switch ( $aqualuxe_footer_layout ) {
	case '1-column':
		$aqualuxe_column_class = 'grid-cols-1';
		break;
	case '2-columns':
		$aqualuxe_column_class = 'grid-cols-1 md:grid-cols-2';
		break;
	case '3-columns':
		$aqualuxe_column_class = 'grid-cols-1 md:grid-cols-3';
		break;
	case '4-columns':
	default:
		$aqualuxe_column_class = 'grid-cols-1 md:grid-cols-2 lg:grid-cols-4';
		break;
}

// Get footer background options
$aqualuxe_footer_bg_color = get_theme_mod( 'aqualuxe_footer_bg_color', 'dark' );
$aqualuxe_footer_bg_class = 'bg-dark-900 text-white';

if ( 'light' === $aqualuxe_footer_bg_color ) {
	$aqualuxe_footer_bg_class = 'bg-white text-dark-800';
} elseif ( 'primary' === $aqualuxe_footer_bg_color ) {
	$aqualuxe_footer_bg_class = 'bg-primary-900 text-white';
}

// Get footer widget areas
$aqualuxe_footer_widget_areas = array(
	'footer-1' => is_active_sidebar( 'footer-1' ),
	'footer-2' => is_active_sidebar( 'footer-2' ),
	'footer-3' => is_active_sidebar( 'footer-3' ),
	'footer-4' => is_active_sidebar( 'footer-4' ),
);

// Count active widget areas
$aqualuxe_active_widget_areas = count( array_filter( $aqualuxe_footer_widget_areas ) );

// Adjust column class if fewer widget areas are active
if ( $aqualuxe_active_widget_areas < 4 && $aqualuxe_active_widget_areas > 0 ) {
	switch ( $aqualuxe_active_widget_areas ) {
		case 1:
			$aqualuxe_column_class = 'grid-cols-1';
			break;
		case 2:
			$aqualuxe_column_class = 'grid-cols-1 md:grid-cols-2';
			break;
		case 3:
			$aqualuxe_column_class = 'grid-cols-1 md:grid-cols-3';
			break;
	}
}
?>

<div class="footer-widgets py-12 <?php echo esc_attr( $aqualuxe_footer_bg_class ); ?>">
	<div class="container mx-auto px-4">
		<div class="grid <?php echo esc_attr( $aqualuxe_column_class ); ?> gap-8">
			<?php
			// Display footer widgets if active
			if ( $aqualuxe_active_widget_areas > 0 ) :
				foreach ( $aqualuxe_footer_widget_areas as $widget_area => $is_active ) :
					if ( $is_active ) :
						?>
						<div class="footer-widget">
							<?php dynamic_sidebar( $widget_area ); ?>
						</div>
						<?php
					endif;
				endforeach;
			else :
				// Default footer content if no widgets are active
				?>
				<!-- Footer Widget Area 1 -->
				<div class="footer-widget">
					<div class="site-info">
						<div class="footer-logo mb-4">
							<?php
							if ( has_custom_logo() ) :
								$custom_logo_id = get_theme_mod( 'custom_logo' );
								$logo = wp_get_attachment_image_src( $custom_logo_id, 'full' );
								?>
								<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
									<img src="<?php echo esc_url( $logo[0] ); ?>" alt="<?php bloginfo( 'name' ); ?>" class="h-12 w-auto">
								</a>
							<?php else : ?>
								<h3 class="text-xl font-serif font-bold">
									<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="text-white hover:text-primary-400 transition-colors duration-200">
										<?php bloginfo( 'name' ); ?>
									</a>
								</h3>
							<?php endif; ?>
						</div>
						<p class="text-gray-300 mb-4"><?php echo get_bloginfo( 'description' ); ?></p>
						<?php if ( function_exists( 'aqualuxe_social_links' ) ) : ?>
							<div class="social-links flex space-x-3 mt-4">
								<?php aqualuxe_social_links(); ?>
							</div>
						<?php endif; ?>
					</div>
				</div>

				<!-- Footer Widget Area 2 -->
				<div class="footer-widget">
					<h3 class="text-lg font-bold mb-4"><?php esc_html_e( 'Quick Links', 'aqualuxe' ); ?></h3>
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'footer',
							'menu_id'        => 'footer-menu',
							'container'      => false,
							'depth'          => 1,
							'menu_class'     => 'footer-menu space-y-2',
							'fallback_cb'    => false,
						)
					);
					?>
				</div>

				<!-- Footer Widget Area 3 -->
				<div class="footer-widget">
					<h3 class="text-lg font-bold mb-4"><?php esc_html_e( 'Contact Us', 'aqualuxe' ); ?></h3>
					<ul class="contact-info space-y-3 text-gray-300">
						<?php if ( get_theme_mod( 'aqualuxe_contact_address', '123 Aquarium Street, Ocean City, AC 12345' ) ) : ?>
							<li class="flex items-start">
								<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 mt-0.5 text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
								</svg>
								<span><?php echo esc_html( get_theme_mod( 'aqualuxe_contact_address', '123 Aquarium Street, Ocean City, AC 12345' ) ); ?></span>
							</li>
						<?php endif; ?>
						
						<?php if ( get_theme_mod( 'aqualuxe_contact_phone', '+1 (555) 123-4567' ) ) : ?>
							<li class="flex items-center">
								<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
								</svg>
								<span><?php echo esc_html( get_theme_mod( 'aqualuxe_contact_phone', '+1 (555) 123-4567' ) ); ?></span>
							</li>
						<?php endif; ?>
						
						<?php if ( get_theme_mod( 'aqualuxe_contact_email', 'info@aqualuxe.com' ) ) : ?>
							<li class="flex items-center">
								<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
								</svg>
								<a href="mailto:<?php echo esc_attr( get_theme_mod( 'aqualuxe_contact_email', 'info@aqualuxe.com' ) ); ?>" class="hover:text-primary-400 transition-colors duration-200">
									<?php echo esc_html( get_theme_mod( 'aqualuxe_contact_email', 'info@aqualuxe.com' ) ); ?>
								</a>
							</li>
						<?php endif; ?>
						
						<?php if ( get_theme_mod( 'aqualuxe_contact_hours', 'Mon-Fri: 9AM - 6PM' ) ) : ?>
							<li class="flex items-center">
								<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
								</svg>
								<span><?php echo esc_html( get_theme_mod( 'aqualuxe_contact_hours', 'Mon-Fri: 9AM - 6PM' ) ); ?></span>
							</li>
						<?php endif; ?>
					</ul>
				</div>

				<!-- Footer Widget Area 4 -->
				<div class="footer-widget">
					<h3 class="text-lg font-bold mb-4"><?php esc_html_e( 'Newsletter', 'aqualuxe' ); ?></h3>
					<p class="text-gray-300 mb-4"><?php esc_html_e( 'Subscribe to our newsletter for the latest updates and offers.', 'aqualuxe' ); ?></p>
					
					<form class="newsletter-form mt-4">
						<div class="flex flex-col sm:flex-row gap-2">
							<input type="email" placeholder="<?php esc_attr_e( 'Your email address', 'aqualuxe' ); ?>" class="bg-dark-800 border border-dark-700 rounded px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-primary-500 text-white" required>
							<button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded transition-colors duration-200 whitespace-nowrap">
								<?php esc_html_e( 'Subscribe', 'aqualuxe' ); ?>
							</button>
						</div>
					</form>
					
					<?php if ( class_exists( 'WooCommerce' ) ) : ?>
						<div class="payment-methods mt-6">
							<h4 class="text-sm font-semibold mb-2 text-gray-300"><?php esc_html_e( 'We Accept', 'aqualuxe' ); ?></h4>
							<div class="flex flex-wrap gap-2">
								<?php
								$payment_methods = array( 'visa', 'mastercard', 'amex', 'paypal', 'apple-pay', 'google-pay' );
								foreach ( $payment_methods as $method ) :
									echo '<span class="payment-icon ' . esc_attr( $method ) . ' bg-dark-800 border border-dark-700 rounded p-1 inline-flex items-center justify-center">';
									echo '<img src="' . esc_url( get_template_directory_uri() . '/assets/images/payment/' . $method . '.svg' ) . '" alt="' . esc_attr( $method ) . '" class="h-6 w-auto">';
									echo '</span>';
								endforeach;
								?>
							</div>
						</div>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>

<div class="footer-bottom py-6 <?php echo esc_attr( $aqualuxe_footer_bg_class ); ?> border-t border-dark-700">
	<div class="container mx-auto px-4">
		<div class="flex flex-col md:flex-row justify-between items-center">
			<div class="copyright text-gray-400 text-sm mb-4 md:mb-0">
				<?php
				$aqualuxe_copyright_text = get_theme_mod( 'aqualuxe_copyright_text', '© {year} {site_title}. All rights reserved.' );
				$aqualuxe_copyright_text = str_replace( '{year}', date_i18n( 'Y' ), $aqualuxe_copyright_text );
				$aqualuxe_copyright_text = str_replace( '{site_title}', get_bloginfo( 'name' ), $aqualuxe_copyright_text );
				echo wp_kses_post( $aqualuxe_copyright_text );
				?>
			</div>
			
			<?php if ( has_nav_menu( 'legal' ) ) : ?>
				<div class="legal-links">
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'legal',
							'menu_id'        => 'legal-menu',
							'container'      => false,
							'depth'          => 1,
							'menu_class'     => 'flex flex-wrap gap-x-6 gap-y-2 text-sm text-gray-400',
							'fallback_cb'    => false,
							'link_before'    => '<span class="hover:text-primary-400 transition-colors duration-200">',
							'link_after'     => '</span>',
						)
					);
					?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>

<?php if ( get_theme_mod( 'aqualuxe_show_back_to_top', true ) && function_exists( 'aqualuxe_back_to_top' ) ) : ?>
	<?php aqualuxe_back_to_top(); ?>
<?php endif; ?>