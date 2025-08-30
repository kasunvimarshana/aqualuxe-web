<?php
/**
 * Template part for displaying the cart icon in the header
 *
 * @package AquaLuxe
 */

// Check if WooCommerce is active
if ( ! class_exists( 'WooCommerce' ) ) {
	return;
}

// Get cart settings from customizer or use defaults
$cart_icon_enable = get_theme_mod( 'aqualuxe_cart_icon_enable', true );
$cart_dropdown_enable = get_theme_mod( 'aqualuxe_cart_dropdown_enable', true );

// Only display the cart icon if it's enabled
if ( ! $cart_icon_enable ) {
	return;
}

// Get cart data
$cart_count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
$cart_total = WC()->cart ? WC()->cart->get_cart_total() : wc_price( 0 );
$cart_url = wc_get_cart_url();
$checkout_url = wc_get_checkout_url();
?>

<div class="header-cart">
	<a href="<?php echo esc_url( $cart_url ); ?>" class="cart-icon-link" aria-label="<?php esc_attr_e( 'View your shopping cart', 'aqualuxe' ); ?>">
		<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24">
			<path d="M2.25 2.25a.75.75 0 000 1.5h1.386c.17 0 .318.114.362.278l2.558 9.592a3.752 3.752 0 00-2.806 3.63c0 .414.336.75.75.75h15.75a.75.75 0 000-1.5H5.378A2.25 2.25 0 017.5 15h11.218a.75.75 0 00.674-.421 60.358 60.358 0 002.96-7.228.75.75 0 00-.525-.965A60.864 60.864 0 005.68 4.509l-.232-.867A1.875 1.875 0 003.636 2.25H2.25zM3.75 20.25a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0zM16.5 20.25a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0z" />
		</svg>
		<span class="cart-count"><?php echo esc_html( $cart_count ); ?></span>
	</a>
	
	<?php if ( $cart_dropdown_enable ) : ?>
		<div class="cart-dropdown">
			<div class="cart-dropdown-inner">
				<?php if ( $cart_count > 0 ) : ?>
					<div class="cart-items">
						<?php
						foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
							$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
							$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

							if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
								$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
								?>
								<div class="cart-item">
									<div class="cart-item-image">
										<?php
										$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

										if ( ! $product_permalink ) {
											echo $thumbnail; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
										} else {
											printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
										}
										?>
									</div>
									
									<div class="cart-item-details">
										<div class="cart-item-title">
											<?php
											if ( ! $product_permalink ) {
												echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;' );
											} else {
												echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
											}
											?>
										</div>
										
										<div class="cart-item-quantity-price">
											<span class="cart-item-quantity">
												<?php echo esc_html( $cart_item['quantity'] ); ?> &times;
											</span>
											<span class="cart-item-price">
												<?php
												echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
												?>
											</span>
										</div>
									</div>
									
									<div class="cart-item-remove">
										<?php
										echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
											'woocommerce_cart_item_remove_link',
											sprintf(
												'<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">
													<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" width="16" height="16">
														<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
													</svg>
												</a>',
												esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
												esc_html__( 'Remove this item', 'aqualuxe' ),
												esc_attr( $product_id ),
												esc_attr( $_product->get_sku() )
											),
											$cart_item_key
										);
										?>
									</div>
								</div>
								<?php
							}
						}
						?>
					</div>
					
					<div class="cart-subtotal">
						<span class="subtotal-label"><?php esc_html_e( 'Subtotal:', 'aqualuxe' ); ?></span>
						<span class="subtotal-value"><?php echo $cart_total; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					</div>
					
					<div class="cart-actions">
						<a href="<?php echo esc_url( $cart_url ); ?>" class="btn btn-outline-primary"><?php esc_html_e( 'View Cart', 'aqualuxe' ); ?></a>
						<a href="<?php echo esc_url( $checkout_url ); ?>" class="btn btn-primary"><?php esc_html_e( 'Checkout', 'aqualuxe' ); ?></a>
					</div>
				<?php else : ?>
					<div class="cart-empty">
						<div class="cart-empty-icon">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="48" height="48">
								<path d="M2.25 2.25a.75.75 0 000 1.5h1.386c.17 0 .318.114.362.278l2.558 9.592a3.752 3.752 0 00-2.806 3.63c0 .414.336.75.75.75h15.75a.75.75 0 000-1.5H5.378A2.25 2.25 0 017.5 15h11.218a.75.75 0 00.674-.421 60.358 60.358 0 002.96-7.228.75.75 0 00-.525-.965A60.864 60.864 0 005.68 4.509l-.232-.867A1.875 1.875 0 003.636 2.25H2.25zM3.75 20.25a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0zM16.5 20.25a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0z" />
							</svg>
						</div>
						<p><?php esc_html_e( 'Your cart is currently empty.', 'aqualuxe' ); ?></p>
						<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="btn btn-primary"><?php esc_html_e( 'Start Shopping', 'aqualuxe' ); ?></a>
					</div>
				<?php endif; ?>
			</div>
		</div>
	<?php endif; ?>
</div>