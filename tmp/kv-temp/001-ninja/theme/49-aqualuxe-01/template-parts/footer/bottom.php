<?php
/**
 * Template part for displaying the footer bottom section
 *
 * @package AquaLuxe
 */

// Get copyright text
$copyright_text = aqualuxe_translate_theme_mod( 'aqualuxe_copyright_text', sprintf( __( '© %s AquaLuxe. All rights reserved.', 'aqualuxe' ), date( 'Y' ) ) );

// Check if payment icons should be displayed
$show_payment_icons = get_theme_mod( 'aqualuxe_show_payment_icons', true );

// Check if social icons should be displayed
$show_social_icons = get_theme_mod( 'aqualuxe_show_social_icons', true );

// Get footer logo
$footer_logo_id = get_theme_mod( 'aqualuxe_footer_logo' );
$has_footer_logo = $footer_logo_id ? true : false;
?>

<div class="footer-bottom">
	<div class="container">
		<div class="footer-bottom-inner">
			<?php if ( $has_footer_logo ) : ?>
				<div class="footer-logo">
					<?php
					$footer_logo = wp_get_attachment_image_src( $footer_logo_id, 'full' );
					
					if ( $footer_logo ) {
						echo '<a href="' . esc_url( home_url( '/' ) ) . '" class="footer-logo-link" rel="home">';
						echo '<img src="' . esc_url( $footer_logo[0] ) . '" alt="' . esc_attr( get_bloginfo( 'name' ) ) . '" class="footer-logo-img" width="' . esc_attr( $footer_logo[1] ) . '" height="' . esc_attr( $footer_logo[2] ) . '">';
						echo '</a>';
					}
					?>
				</div>
			<?php endif; ?>

			<div class="footer-info">
				<?php if ( $copyright_text ) : ?>
					<div class="copyright-text">
						<?php echo wp_kses_post( $copyright_text ); ?>
					</div>
				<?php endif; ?>

				<?php
				// Footer menu
				if ( has_nav_menu( 'footer' ) ) {
					wp_nav_menu(
						array(
							'theme_location' => 'footer',
							'menu_id'        => 'footer-menu',
							'container'      => 'nav',
							'container_class' => 'footer-navigation',
							'depth'          => 1,
						)
					);
				}
				?>
			</div>

			<div class="footer-extras">
				<?php if ( $show_social_icons ) : ?>
					<div class="footer-social">
						<?php
						$social_platforms = array(
							'facebook'  => array(
								'label' => __( 'Facebook', 'aqualuxe' ),
								'icon'  => 'facebook',
							),
							'twitter'   => array(
								'label' => __( 'Twitter', 'aqualuxe' ),
								'icon'  => 'twitter',
							),
							'instagram' => array(
								'label' => __( 'Instagram', 'aqualuxe' ),
								'icon'  => 'instagram',
							),
							'youtube'   => array(
								'label' => __( 'YouTube', 'aqualuxe' ),
								'icon'  => 'youtube',
							),
							'linkedin'  => array(
								'label' => __( 'LinkedIn', 'aqualuxe' ),
								'icon'  => 'linkedin',
							),
							'pinterest' => array(
								'label' => __( 'Pinterest', 'aqualuxe' ),
								'icon'  => 'pinterest',
							),
						);
						
						foreach ( $social_platforms as $platform => $data ) {
							$url = get_theme_mod( 'aqualuxe_social_' . $platform, '' );
							
							if ( $url ) {
								echo '<a href="' . esc_url( $url ) . '" target="_blank" rel="noopener noreferrer" class="social-link social-' . esc_attr( $platform ) . '">';
								echo '<svg class="icon icon-' . esc_attr( $data['icon'] ) . '" aria-hidden="true" focusable="false"><use xlink:href="#icon-' . esc_attr( $data['icon'] ) . '"></use></svg>';
								echo '<span class="screen-reader-text">' . esc_html( $data['label'] ) . '</span>';
								echo '</a>';
							}
						}
						?>
					</div>
				<?php endif; ?>

				<?php if ( $show_payment_icons && aqualuxe_is_woocommerce_active() ) : ?>
					<div class="footer-payment">
						<?php
						$payment_methods = array(
							'visa'       => __( 'Visa', 'aqualuxe' ),
							'mastercard' => __( 'Mastercard', 'aqualuxe' ),
							'amex'       => __( 'American Express', 'aqualuxe' ),
							'discover'   => __( 'Discover', 'aqualuxe' ),
							'paypal'     => __( 'PayPal', 'aqualuxe' ),
							'apple-pay'  => __( 'Apple Pay', 'aqualuxe' ),
							'google-pay' => __( 'Google Pay', 'aqualuxe' ),
						);
						
						foreach ( $payment_methods as $method => $label ) {
							echo '<span class="payment-icon payment-' . esc_attr( $method ) . '">';
							echo '<svg class="icon icon-' . esc_attr( $method ) . '" aria-hidden="true" focusable="false"><use xlink:href="#icon-' . esc_attr( $method ) . '"></use></svg>';
							echo '<span class="screen-reader-text">' . esc_html( $label ) . '</span>';
							echo '</span>';
						}
						?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>