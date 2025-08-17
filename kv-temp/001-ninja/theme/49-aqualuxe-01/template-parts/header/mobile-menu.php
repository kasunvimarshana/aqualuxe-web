<?php
/**
 * Template part for displaying the mobile menu
 *
 * @package AquaLuxe
 */

?>

<div class="mobile-menu-container">
	<div class="mobile-menu-header">
		<div class="mobile-menu-logo">
			<?php aqualuxe_site_logo(); ?>
		</div>
		<button class="mobile-menu-close">
			<svg class="icon icon-close" aria-hidden="true" focusable="false"><use xlink:href="#icon-close"></use></svg>
			<span class="screen-reader-text"><?php esc_html_e( 'Close menu', 'aqualuxe' ); ?></span>
		</button>
	</div>

	<div class="mobile-search">
		<?php get_search_form(); ?>
	</div>

	<nav class="mobile-navigation">
		<?php
		wp_nav_menu(
			array(
				'theme_location' => 'mobile',
				'menu_id'        => 'mobile-menu',
				'container'      => false,
				'fallback_cb'    => function() {
					wp_nav_menu(
						array(
							'theme_location' => 'primary',
							'menu_id'        => 'mobile-menu',
							'container'      => false,
							'fallback_cb'    => false,
						)
					);
				},
			)
		);
		?>
	</nav>

	<div class="mobile-extras">
		<?php
		// Language switcher
		if ( get_theme_mod( 'aqualuxe_header_language_switcher', true ) && function_exists( 'aqualuxe_language_switcher' ) ) {
			echo '<div class="mobile-language-switcher">';
			aqualuxe_language_switcher();
			echo '</div>';
		}

		// Currency switcher
		if ( get_theme_mod( 'aqualuxe_header_currency_switcher', true ) && function_exists( 'aqualuxe_currency_switcher' ) && aqualuxe_is_woocommerce_active() ) {
			echo '<div class="mobile-currency-switcher">';
			aqualuxe_currency_switcher();
			echo '</div>';
		}

		// Dark mode toggle
		if ( get_theme_mod( 'aqualuxe_show_dark_mode_toggle', true ) && function_exists( 'aqualuxe_dark_mode_toggle' ) ) {
			echo '<div class="mobile-dark-mode-toggle">';
			aqualuxe_dark_mode_toggle();
			echo '</div>';
		}
		?>
	</div>

	<?php if ( aqualuxe_is_woocommerce_active() ) : ?>
		<div class="mobile-account-links">
			<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="mobile-account-link">
				<svg class="icon icon-user" aria-hidden="true" focusable="false"><use xlink:href="#icon-user"></use></svg>
				<span><?php esc_html_e( 'My Account', 'aqualuxe' ); ?></span>
			</a>

			<?php if ( get_theme_mod( 'aqualuxe_wishlist', true ) && function_exists( 'aqualuxe_get_wishlist_url' ) ) : ?>
				<a href="<?php echo esc_url( aqualuxe_get_wishlist_url() ); ?>" class="mobile-wishlist-link">
					<svg class="icon icon-heart" aria-hidden="true" focusable="false"><use xlink:href="#icon-heart"></use></svg>
					<span><?php esc_html_e( 'Wishlist', 'aqualuxe' ); ?></span>
					<?php if ( function_exists( 'aqualuxe_get_wishlist_count' ) ) : ?>
						<span class="wishlist-count"><?php echo esc_html( aqualuxe_get_wishlist_count() ); ?></span>
					<?php endif; ?>
				</a>
			<?php endif; ?>

			<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="mobile-cart-link">
				<svg class="icon icon-cart" aria-hidden="true" focusable="false"><use xlink:href="#icon-cart"></use></svg>
				<span><?php esc_html_e( 'Cart', 'aqualuxe' ); ?></span>
				<span class="cart-count"><?php echo esc_html( WC()->cart->get_cart_contents_count() ); ?></span>
			</a>
		</div>
	<?php endif; ?>

	<div class="mobile-contact-info">
		<?php
		$phone = get_theme_mod( 'aqualuxe_contact_phone', '+1 (555) 123-4567' );
		$email = get_theme_mod( 'aqualuxe_contact_email', 'info@aqualuxe.com' );
		
		if ( $phone ) {
			echo '<div class="mobile-contact-phone">';
			echo '<svg class="icon icon-phone" aria-hidden="true" focusable="false"><use xlink:href="#icon-phone"></use></svg>';
			echo '<a href="tel:' . esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ) . '">' . esc_html( $phone ) . '</a>';
			echo '</div>';
		}
		
		if ( $email ) {
			echo '<div class="mobile-contact-email">';
			echo '<svg class="icon icon-envelope" aria-hidden="true" focusable="false"><use xlink:href="#icon-envelope"></use></svg>';
			echo '<a href="mailto:' . esc_attr( $email ) . '">' . esc_html( $email ) . '</a>';
			echo '</div>';
		}
		?>
	</div>

	<div class="mobile-social-links">
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
				echo '<a href="' . esc_url( $url ) . '" target="_blank" rel="noopener noreferrer" class="mobile-social-link mobile-social-' . esc_attr( $platform ) . '">';
				echo '<svg class="icon icon-' . esc_attr( $data['icon'] ) . '" aria-hidden="true" focusable="false"><use xlink:href="#icon-' . esc_attr( $data['icon'] ) . '"></use></svg>';
				echo '<span class="screen-reader-text">' . esc_html( $data['label'] ) . '</span>';
				echo '</a>';
			}
		}
		?>
	</div>
</div>

<div class="mobile-menu-overlay"></div>