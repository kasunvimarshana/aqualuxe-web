<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package AquaLuxe
 * @version 7.0.1
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_cart' ); ?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
	<div class="lg:col-span-2">
		<form class="woocommerce-cart-form bg-white dark:bg-dark-700 rounded-lg shadow-soft overflow-hidden" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
			<?php do_action( 'woocommerce_before_cart_table' ); ?>

			<div class="shop_table shop_table_responsive cart woocommerce-cart-form__contents p-6">
				<div class="cart-header border-b border-gray-200 dark:border-dark-600 pb-4 hidden md:grid md:grid-cols-12 gap-4 font-medium">
					<div class="md:col-span-6"><?php esc_html_e( 'Product', 'aqualuxe' ); ?></div>
					<div class="md:col-span-2 text-center"><?php esc_html_e( 'Price', 'aqualuxe' ); ?></div>
					<div class="md:col-span-2 text-center"><?php esc_html_e( 'Quantity', 'aqualuxe' ); ?></div>
					<div class="md:col-span-2 text-right"><?php esc_html_e( 'Subtotal', 'aqualuxe' ); ?></div>
				</div>

				<?php do_action( 'woocommerce_before_cart_contents' ); ?>

				<?php
				foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
					$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
					$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

					if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
						$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
						?>
						<div class="woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?> py-6 border-b border-gray-200 dark:border-dark-600 last:border-0 grid grid-cols-1 md:grid-cols-12 gap-4 items-center">

							<div class="md:col-span-6">
								<div class="flex items-center">
									<div class="product-thumbnail mr-4">
										<?php
										$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image( 'thumbnail', array( 'class' => 'rounded-md' ) ), $cart_item, $cart_item_key );

										if ( ! $product_permalink ) {
											echo $thumbnail; // PHPCS: XSS ok.
										} else {
											printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail ); // PHPCS: XSS ok.
										}
										?>
									</div>

									<div class="product-name" data-title="<?php esc_attr_e( 'Product', 'aqualuxe' ); ?>">
										<?php
										if ( ! $product_permalink ) {
											echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;' );
										} else {
											echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s" class="font-medium hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-200">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
										}

										do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );

										// Meta data.
										echo wc_get_formatted_cart_item_data( $cart_item ); // PHPCS: XSS ok.

										// Backorder notification.
										if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
											echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification text-sm text-amber-600 dark:text-amber-400">' . esc_html__( 'Available on backorder', 'aqualuxe' ) . '</p>', $product_id ) );
										}
										?>
									</div>
								</div>
							</div>

							<div class="product-price md:col-span-2 md:text-center" data-title="<?php esc_attr_e( 'Price', 'aqualuxe' ); ?>">
								<?php
									echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
								?>
							</div>

							<div class="product-quantity md:col-span-2 md:text-center" data-title="<?php esc_attr_e( 'Quantity', 'aqualuxe' ); ?>">
								<div class="quantity-wrapper inline-flex">
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
							</div>

							<div class="product-subtotal md:col-span-2 md:text-right" data-title="<?php esc_attr_e( 'Subtotal', 'aqualuxe' ); ?>">
								<?php
									echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
								?>
								
								<div class="product-remove mt-2 text-right">
									<?php
										echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
											'woocommerce_cart_item_remove_link',
											sprintf(
												'<a href="%s" class="remove text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 text-sm" aria-label="%s" data-product_id="%s" data-product_sku="%s">%s</a>',
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
						<?php
					}
				}
				?>

				<?php do_action( 'woocommerce_cart_contents' ); ?>

				<div class="actions mt-6 flex flex-wrap justify-between">
					<?php if ( wc_coupons_enabled() ) { ?>
						<div class="coupon flex flex-wrap mb-4 md:mb-0">
							<label for="coupon_code" class="sr-only"><?php esc_html_e( 'Coupon:', 'aqualuxe' ); ?></label>
							<input type="text" name="coupon_code" class="input-text rounded-l-md rounded-r-none" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'aqualuxe' ); ?>" />
							<button type="submit" class="button btn-primary rounded-l-none" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'aqualuxe' ); ?>"><?php esc_html_e( 'Apply', 'aqualuxe' ); ?></button>
							<?php do_action( 'woocommerce_cart_coupon' ); ?>
						</div>
					<?php } ?>

					<button type="submit" class="button btn-outline" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'aqualuxe' ); ?>"><?php esc_html_e( 'Update cart', 'aqualuxe' ); ?></button>

					<?php do_action( 'woocommerce_cart_actions' ); ?>

					<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
				</div>

				<?php do_action( 'woocommerce_after_cart_contents' ); ?>
			</div>

			<?php do_action( 'woocommerce_after_cart_table' ); ?>
		</form>
	</div>

	<div class="lg:col-span-1">
		<?php do_action( 'woocommerce_before_cart_collaterals' ); ?>

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
	</div>
</div>

<?php do_action( 'woocommerce_after_cart' ); ?>