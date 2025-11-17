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

<div class="cart-container">
	<form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
		<?php do_action( 'woocommerce_before_cart_table' ); ?>

		<div class="cart-items bg-white dark:bg-dark-800 rounded-lg shadow-sm p-6 mb-8">
			<h2 class="text-2xl font-serif font-bold text-dark-900 dark:text-white mb-6"><?php esc_html_e( 'Shopping Cart', 'aqualuxe' ); ?></h2>
			
			<div class="shop_table shop_table_responsive cart woocommerce-cart-form__contents">
				<div class="cart-header hidden md:grid grid-cols-12 gap-4 pb-4 border-b border-gray-200 dark:border-dark-700 text-dark-500 dark:text-dark-400 text-sm font-medium">
					<div class="col-span-6"><?php esc_html_e( 'Product', 'aqualuxe' ); ?></div>
					<div class="col-span-2 text-center"><?php esc_html_e( 'Price', 'aqualuxe' ); ?></div>
					<div class="col-span-2 text-center"><?php esc_html_e( 'Quantity', 'aqualuxe' ); ?></div>
					<div class="col-span-2 text-right"><?php esc_html_e( 'Subtotal', 'aqualuxe' ); ?></div>
				</div>

				<div class="cart-items-list divide-y divide-gray-200 dark:divide-dark-700">
					<?php do_action( 'woocommerce_before_cart_contents' ); ?>

					<?php
					foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
						$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
						$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

						if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
							$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
							?>
							<div class="woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?> py-6 grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
								
								<div class="product-thumbnail-and-name col-span-1 md:col-span-6 flex items-center">
									<div class="product-thumbnail mr-4">
										<?php
										$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image( 'thumbnail', array( 'class' => 'w-20 h-20 object-cover rounded-md' ) ), $cart_item, $cart_item_key );

										if ( ! $product_permalink ) {
											echo $thumbnail; // PHPCS: XSS ok.
										} else {
											printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail ); // PHPCS: XSS ok.
										}
										?>
									</div>

									<div class="product-name">
										<?php
										if ( ! $product_permalink ) {
											echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;' );
										} else {
											echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s" class="text-dark-900 dark:text-white font-medium hover:text-primary-600 dark:hover:text-primary-400">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
										}

										do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );

										// Meta data.
										echo wc_get_formatted_cart_item_data( $cart_item ); // PHPCS: XSS ok.

										// Backorder notification.
										if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
											echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification text-sm text-accent-600 dark:text-accent-400 mt-1">' . esc_html__( 'Available on backorder', 'aqualuxe' ) . '</p>', $product_id ) );
										}
										?>
									</div>
								</div>

								<div class="product-price col-span-1 md:col-span-2 text-left md:text-center">
									<span class="block md:hidden text-sm text-dark-500 dark:text-dark-400 mb-1"><?php esc_html_e( 'Price', 'aqualuxe' ); ?></span>
									<?php
										echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
									?>
								</div>

								<div class="product-quantity col-span-1 md:col-span-2 text-left md:text-center">
									<span class="block md:hidden text-sm text-dark-500 dark:text-dark-400 mb-1"><?php esc_html_e( 'Quantity', 'aqualuxe' ); ?></span>
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
												'classes'      => apply_filters( 'woocommerce_quantity_input_classes', array( 'form-input', 'w-16', 'text-center' ), $_product ),
											),
											$_product,
											false
										);
									}

									echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item ); // PHPCS: XSS ok.
									?>
								</div>

								<div class="product-subtotal col-span-1 md:col-span-2 text-left md:text-right">
									<span class="block md:hidden text-sm text-dark-500 dark:text-dark-400 mb-1"><?php esc_html_e( 'Subtotal', 'aqualuxe' ); ?></span>
									<?php
										echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
									?>
									
									<div class="product-remove mt-2 text-right">
										<?php
											echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
												'woocommerce_cart_item_remove_link',
												sprintf(
													'<a href="%s" class="remove text-sm text-red-600 dark:text-red-400 hover:underline" aria-label="%s" data-product_id="%s" data-product_sku="%s">%s</a>',
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

					<div class="actions py-6">
						<?php if ( wc_coupons_enabled() ) { ?>
							<div class="coupon flex flex-col sm:flex-row gap-4 mb-6">
								<input type="text" name="coupon_code" class="form-input" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'aqualuxe' ); ?>" />
								<button type="submit" class="btn btn-outline" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'aqualuxe' ); ?>"><?php esc_html_e( 'Apply coupon', 'aqualuxe' ); ?></button>
								<?php do_action( 'woocommerce_cart_coupon' ); ?>
							</div>
						<?php } ?>

						<div class="flex justify-between items-center">
							<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="inline-flex items-center text-primary-600 dark:text-primary-400 hover:underline">
								<svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
								</svg>
								<?php esc_html_e( 'Continue Shopping', 'aqualuxe' ); ?>
							</a>
							
							<button type="submit" class="btn btn-primary" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'aqualuxe' ); ?>"><?php esc_html_e( 'Update cart', 'aqualuxe' ); ?></button>
						</div>

						<?php do_action( 'woocommerce_cart_actions' ); ?>

						<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
					</div>

					<?php do_action( 'woocommerce_after_cart_contents' ); ?>
				</div>
			</div>
		</div>

		<?php do_action( 'woocommerce_after_cart_table' ); ?>
	</form>

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

<?php do_action( 'woocommerce_after_cart' ); ?>