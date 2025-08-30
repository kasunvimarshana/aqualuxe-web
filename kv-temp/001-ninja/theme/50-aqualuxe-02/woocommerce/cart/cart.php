<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package AquaLuxe
 * @version 7.9.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_cart' ); ?>

<div class="aqualuxe-cart-layout">
	<div class="cart-main">
		<form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
			<?php do_action( 'woocommerce_before_cart_table' ); ?>

			<div class="shop_table shop_table_responsive cart woocommerce-cart-form__contents">
				<div class="cart-header">
					<div class="product-col"><?php esc_html_e( 'Product', 'aqualuxe' ); ?></div>
					<div class="price-col"><?php esc_html_e( 'Price', 'aqualuxe' ); ?></div>
					<div class="quantity-col"><?php esc_html_e( 'Quantity', 'aqualuxe' ); ?></div>
					<div class="subtotal-col"><?php esc_html_e( 'Subtotal', 'aqualuxe' ); ?></div>
				</div>

				<?php do_action( 'woocommerce_before_cart_contents' ); ?>

				<div class="cart-items">
					<?php
					foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
						$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
						$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

						if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
							$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
							?>
							<div class="woocommerce-cart-form__cart-item cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
								<div class="product-col">
									<div class="product-thumbnail">
										<?php
										$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

										if ( ! $product_permalink ) {
											echo $thumbnail; // PHPCS: XSS ok.
										} else {
											printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail ); // PHPCS: XSS ok.
										}
										?>
									</div>

									<div class="product-info">
										<div class="product-name" data-title="<?php esc_attr_e( 'Product', 'aqualuxe' ); ?>">
											<?php
											if ( ! $product_permalink ) {
												echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;' );
											} else {
												echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
											}

											do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );

											// Meta data.
											echo wc_get_formatted_cart_item_data( $cart_item ); // PHPCS: XSS ok.

											// Backorder notification.
											if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
												echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'aqualuxe' ) . '</p>', $product_id ) );
											}
											?>
										</div>

										<div class="product-remove">
											<?php
												echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
													'woocommerce_cart_item_remove_link',
													sprintf(
														'<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">%s</a>',
														esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
														esc_html__( 'Remove this item', 'aqualuxe' ),
														esc_attr( $product_id ),
														esc_attr( $_product->get_sku() ),
														esc_html__( 'Remove', 'aqualuxe' )
													),
													$cart_item_key
												);
											?>
										</div>
									</div>
								</div>

								<div class="price-col" data-title="<?php esc_attr_e( 'Price', 'aqualuxe' ); ?>">
									<?php
										echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
									?>
								</div>

								<div class="quantity-col" data-title="<?php esc_attr_e( 'Quantity', 'aqualuxe' ); ?>">
									<?php
									if ( $_product->is_sold_individually() ) {
										$product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
									} else {
										$product_quantity = woocommerce_quantity_input(
											array(
												'input_name'   => "cart[{$cart_item_key}][qty]",
												'input_value'  => $cart_item['quantity'],
												'max_value'    => $_product->get_max_purchase_quantity(),
												'min_value'    => '0',
												'product_name' => $_product->get_name(),
											),
											$_product,
											false
										);
									}

									echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item ); // PHPCS: XSS ok.
									?>
								</div>

								<div class="subtotal-col" data-title="<?php esc_attr_e( 'Subtotal', 'aqualuxe' ); ?>">
									<?php
										echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
									?>
								</div>
							</div>
							<?php
						}
					}
					?>
				</div>

				<?php do_action( 'woocommerce_cart_contents' ); ?>

				<div class="actions">
					<?php if ( wc_coupons_enabled() ) { ?>
						<div class="coupon">
							<label for="coupon_code"><?php esc_html_e( 'Coupon:', 'aqualuxe' ); ?></label>
							<div class="coupon-field">
								<input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'aqualuxe' ); ?>" />
								<button type="submit" class="button" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'aqualuxe' ); ?>"><?php esc_attr_e( 'Apply', 'aqualuxe' ); ?></button>
								<?php do_action( 'woocommerce_cart_coupon' ); ?>
							</div>
						</div>
					<?php } ?>

					<button type="submit" class="button update-cart" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'aqualuxe' ); ?>"><?php esc_html_e( 'Update cart', 'aqualuxe' ); ?></button>

					<?php do_action( 'woocommerce_cart_actions' ); ?>

					<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
				</div>

				<?php do_action( 'woocommerce_after_cart_contents' ); ?>
			</div>

			<?php do_action( 'woocommerce_after_cart_table' ); ?>
		</form>

		<?php do_action( 'woocommerce_before_cart_collaterals' ); ?>
	</div>

	<div class="cart-sidebar">
		<div class="cart-collaterals">
			<?php
				/**
				 * Cart collaterals hook.
				 *
				 * @hooked woocommerce_cross_sell_display
				 * @hooked woocommerce_cart_totals - 10
				 */
				do_action( 'woocommerce_cart_collaterals' );
			?>
		</div>

		<div class="cart-additional-info">
			<div class="cart-secure-checkout">
				<h3><?php esc_html_e( 'Secure Checkout', 'aqualuxe' ); ?></h3>
				<p><?php esc_html_e( 'Your payment information is processed securely.', 'aqualuxe' ); ?></p>
				<div class="payment-icons">
					<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/dist/images/payment-visa.svg' ); ?>" alt="<?php esc_attr_e( 'Visa', 'aqualuxe' ); ?>">
					<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/dist/images/payment-mastercard.svg' ); ?>" alt="<?php esc_attr_e( 'Mastercard', 'aqualuxe' ); ?>">
					<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/dist/images/payment-amex.svg' ); ?>" alt="<?php esc_attr_e( 'American Express', 'aqualuxe' ); ?>">
					<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/dist/images/payment-paypal.svg' ); ?>" alt="<?php esc_attr_e( 'PayPal', 'aqualuxe' ); ?>">
				</div>
			</div>

			<div class="cart-shipping-info">
				<h3><?php esc_html_e( 'Shipping Information', 'aqualuxe' ); ?></h3>
				<ul>
					<li>
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="16" height="16">
							<path d="M3.375 4.5C2.339 4.5 1.5 5.34 1.5 6.375V13.5h12V6.375c0-1.036-.84-1.875-1.875-1.875h-8.25zM13.5 15h-12v2.625c0 1.035.84 1.875 1.875 1.875h.375a3 3 0 116 0h3a.75.75 0 00.75-.75V15z" />
							<path d="M8.25 19.5a1.5 1.5 0 10-3 0 1.5 1.5 0 003 0zM15.75 6.75a.75.75 0 00-.75.75v11.25c0 .087.015.17.042.248a3 3 0 015.958.464c.853-.175 1.522-.935 1.464-1.883a18.659 18.659 0 00-3.732-10.104 1.837 1.837 0 00-1.47-.725H15.75z" />
							<path d="M19.5 19.5a1.5 1.5 0 10-3 0 1.5 1.5 0 003 0z" />
						</svg>
						<?php esc_html_e( 'Free shipping on orders over $100', 'aqualuxe' ); ?>
					</li>
					<li>
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="16" height="16">
							<path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zM12.75 6a.75.75 0 00-1.5 0v6c0 .414.336.75.75.75h4.5a.75.75 0 000-1.5h-3.75V6z" clip-rule="evenodd" />
						</svg>
						<?php esc_html_e( 'Delivery in 3-5 business days', 'aqualuxe' ); ?>
					</li>
					<li>
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="16" height="16">
							<path fill-rule="evenodd" d="M12.516 2.17a.75.75 0 00-1.032 0 11.209 11.209 0 01-7.877 3.08.75.75 0 00-.722.515A12.74 12.74 0 002.25 9.75c0 5.942 4.064 10.933 9.563 12.348a.749.749 0 00.374 0c5.499-1.415 9.563-6.406 9.563-12.348 0-1.39-.223-2.73-.635-3.985a.75.75 0 00-.722-.516l-.143.001c-2.996 0-5.717-1.17-7.734-3.08zm3.094 8.016a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd" />
						</svg>
						<?php esc_html_e( 'Special care for live aquatic species', 'aqualuxe' ); ?>
					</li>
				</ul>
			</div>

			<div class="cart-return-policy">
				<h3><?php esc_html_e( 'Return Policy', 'aqualuxe' ); ?></h3>
				<p><?php esc_html_e( 'Non-living products: 30-day return policy. Living organisms: 7-day guarantee (DOA policy applies).', 'aqualuxe' ); ?></p>
				<a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'terms' ) ) ); ?>" class="return-policy-link"><?php esc_html_e( 'View Return Policy', 'aqualuxe' ); ?></a>
			</div>
		</div>
	</div>
</div>

<?php do_action( 'woocommerce_after_cart' ); ?>