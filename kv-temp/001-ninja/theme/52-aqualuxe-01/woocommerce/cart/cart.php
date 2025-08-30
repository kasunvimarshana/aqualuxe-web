<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.9.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_cart' ); ?>

<div class="woocommerce-cart-wrapper bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
	<form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
		<?php do_action( 'woocommerce_before_cart_table' ); ?>

		<div class="shop_table shop_table_responsive cart woocommerce-cart-form__contents p-6">
			<div class="hidden md:flex border-b border-gray-200 dark:border-gray-700 pb-4 mb-4">
				<div class="w-1/2"><?php esc_html_e( 'Product', 'aqualuxe' ); ?></div>
				<div class="w-1/6 text-center"><?php esc_html_e( 'Price', 'aqualuxe' ); ?></div>
				<div class="w-1/6 text-center"><?php esc_html_e( 'Quantity', 'aqualuxe' ); ?></div>
				<div class="w-1/6 text-center"><?php esc_html_e( 'Subtotal', 'aqualuxe' ); ?></div>
			</div>

			<?php do_action( 'woocommerce_before_cart_contents' ); ?>

			<?php
			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
				$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
				$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

				if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
					$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
					?>
					<div class="woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?> flex flex-wrap items-center py-4 border-b border-gray-200 dark:border-gray-700">

						<!-- Product -->
						<div class="w-full md:w-1/2 flex items-center mb-4 md:mb-0">
							<!-- Remove button -->
							<div class="product-remove mr-4">
								<?php
								echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
									'woocommerce_cart_item_remove_link',
									sprintf(
										'<a href="%s" class="remove text-red-500 hover:text-red-700 text-xl" aria-label="%s" data-product_id="%s" data-product_sku="%s">&times;</a>',
										esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
										esc_html__( 'Remove this item', 'aqualuxe' ),
										esc_attr( $product_id ),
										esc_attr( $_product->get_sku() )
									),
									$cart_item_key
								);
								?>
							</div>

							<!-- Thumbnail -->
							<div class="product-thumbnail mr-4">
								<?php
								$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image( 'thumbnail', array( 'class' => 'w-16 h-16 object-cover rounded' ) ), $cart_item, $cart_item_key );

								if ( ! $product_permalink ) {
									echo $thumbnail; // PHPCS: XSS ok.
								} else {
									printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail ); // PHPCS: XSS ok.
								}
								?>
							</div>

							<!-- Product name -->
							<div class="product-name">
								<?php
								if ( ! $product_permalink ) {
									echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;' );
								} else {
									echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s" class="font-medium text-gray-900 dark:text-white hover:text-primary">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
								}

								do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );

								// Meta data.
								echo wc_get_formatted_cart_item_data( $cart_item ); // PHPCS: XSS ok.

								// Backorder notification.
								if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
									echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification text-sm text-yellow-600">' . esc_html__( 'Available on backorder', 'aqualuxe' ) . '</p>', $product_id ) );
								}
								?>
							</div>
						</div>

						<!-- Price -->
						<div class="product-price w-full md:w-1/6 text-center mb-4 md:mb-0">
							<span class="md:hidden font-medium mr-2"><?php esc_html_e( 'Price:', 'aqualuxe' ); ?></span>
							<?php
								echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
							?>
						</div>

						<!-- Quantity -->
						<div class="product-quantity w-full md:w-1/6 text-center mb-4 md:mb-0">
							<span class="md:hidden font-medium mr-2"><?php esc_html_e( 'Quantity:', 'aqualuxe' ); ?></span>
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
										'classes'      => 'w-16 mx-auto',
									),
									$_product,
									false
								);
							}

							echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item ); // PHPCS: XSS ok.
							?>
						</div>

						<!-- Subtotal -->
						<div class="product-subtotal w-full md:w-1/6 text-center font-medium">
							<span class="md:hidden font-medium mr-2"><?php esc_html_e( 'Subtotal:', 'aqualuxe' ); ?></span>
							<?php
								echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
							?>
						</div>
					</div>
					<?php
				}
			}
			?>

			<?php do_action( 'woocommerce_cart_contents' ); ?>

			<div class="actions flex flex-wrap justify-between items-center pt-6">
				<?php if ( wc_coupons_enabled() ) { ?>
					<div class="coupon flex flex-wrap w-full md:w-auto mb-4 md:mb-0">
						<input type="text" name="coupon_code" class="input-text w-full md:w-auto mb-2 md:mb-0 md:mr-2" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'aqualuxe' ); ?>" />
						<button type="submit" class="button btn btn-secondary" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'aqualuxe' ); ?>"><?php esc_html_e( 'Apply coupon', 'aqualuxe' ); ?></button>
						<?php do_action( 'woocommerce_cart_coupon' ); ?>
					</div>
				<?php } ?>

				<button type="submit" class="button btn btn-primary" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'aqualuxe' ); ?>"><?php esc_html_e( 'Update cart', 'aqualuxe' ); ?></button>

				<?php do_action( 'woocommerce_cart_actions' ); ?>

				<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
			</div>

			<?php do_action( 'woocommerce_after_cart_contents' ); ?>
		</div>

		<?php do_action( 'woocommerce_after_cart_table' ); ?>
	</form>

	<?php do_action( 'woocommerce_before_cart_collaterals' ); ?>

	<div class="cart-collaterals p-6 bg-gray-50 dark:bg-gray-700">
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

<?php do_action( 'woocommerce_after_cart' ); ?>