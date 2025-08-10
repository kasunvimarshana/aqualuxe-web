<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.9.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_cart' ); ?>

<div class="cart-wrapper">
	<form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
		<?php do_action( 'woocommerce_before_cart_table' ); ?>

		<div class="shop_table shop_table_responsive cart woocommerce-cart-form__contents bg-white dark:bg-dark-800 rounded-lg shadow-soft overflow-hidden">
			<div class="cart-header hidden md:grid grid-cols-12 gap-4 p-4 border-b border-dark-200 dark:border-dark-700 font-medium text-dark-700 dark:text-dark-300">
				<div class="col-span-6"><?php esc_html_e( 'Product', 'aqualuxe' ); ?></div>
				<div class="col-span-2 text-center"><?php esc_html_e( 'Price', 'aqualuxe' ); ?></div>
				<div class="col-span-2 text-center"><?php esc_html_e( 'Quantity', 'aqualuxe' ); ?></div>
				<div class="col-span-2 text-right"><?php esc_html_e( 'Subtotal', 'aqualuxe' ); ?></div>
			</div>

			<?php do_action( 'woocommerce_before_cart_contents' ); ?>

			<?php
			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
				$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
				$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

				if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
					$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
					?>
					<div class="woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?> grid grid-cols-1 md:grid-cols-12 gap-4 p-4 border-b border-dark-200 dark:border-dark-700 items-center">
						
						<!-- Product -->
						<div class="md:col-span-6 flex items-center">
							<!-- Remove button -->
							<div class="product-remove mr-4">
								<?php
								echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
									'woocommerce_cart_item_remove_link',
									sprintf(
										'<a href="%s" class="remove text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 text-xl" aria-label="%s" data-product_id="%s" data-product_sku="%s">&times;</a>',
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
							<div class="product-name" data-title="<?php esc_attr_e( 'Product', 'aqualuxe' ); ?>">
								<?php
								if ( ! $product_permalink ) {
									echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;' );
								} else {
									echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s" class="font-medium hover:text-primary-600 dark:hover:text-primary-400">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
								}

								do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );

								// Meta data.
								echo wc_get_formatted_cart_item_data( $cart_item ); // PHPCS: XSS ok.

								// Backorder notification.
								if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
									echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification text-sm text-amber-600 dark:text-amber-400 mt-1">' . esc_html__( 'Available on backorder', 'aqualuxe' ) . '</p>', $product_id ) );
								}
								?>
							</div>
						</div>

						<!-- Price -->
						<div class="product-price md:col-span-2 md:text-center" data-title="<?php esc_attr_e( 'Price', 'aqualuxe' ); ?>">
							<span class="block md:hidden font-medium mb-1"><?php esc_html_e( 'Price:', 'aqualuxe' ); ?></span>
							<?php
								echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
							?>
						</div>

						<!-- Quantity -->
						<div class="product-quantity md:col-span-2 md:text-center" data-title="<?php esc_attr_e( 'Quantity', 'aqualuxe' ); ?>">
							<span class="block md:hidden font-medium mb-1"><?php esc_html_e( 'Quantity:', 'aqualuxe' ); ?></span>
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
										'classes'      => apply_filters( 'woocommerce_quantity_input_classes', array( 'form-input w-20 mx-auto' ), $_product ),
									),
									$_product,
									false
								);
							}

							echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item ); // PHPCS: XSS ok.
							?>
						</div>

						<!-- Subtotal -->
						<div class="product-subtotal md:col-span-2 md:text-right" data-title="<?php esc_attr_e( 'Subtotal', 'aqualuxe' ); ?>">
							<span class="block md:hidden font-medium mb-1"><?php esc_html_e( 'Subtotal:', 'aqualuxe' ); ?></span>
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

			<div class="actions p-4 flex flex-wrap justify-between items-center">
				<?php if ( wc_coupons_enabled() ) { ?>
					<div class="coupon flex flex-wrap gap-2 mb-4 md:mb-0">
						<label for="coupon_code" class="sr-only"><?php esc_html_e( 'Coupon:', 'aqualuxe' ); ?></label>
						<input type="text" name="coupon_code" class="form-input" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'aqualuxe' ); ?>" />
						<button type="submit" class="btn-outline" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'aqualuxe' ); ?>"><?php esc_html_e( 'Apply coupon', 'aqualuxe' ); ?></button>
						<?php do_action( 'woocommerce_cart_coupon' ); ?>
					</div>
				<?php } ?>

				<button type="submit" class="btn-primary" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'aqualuxe' ); ?>"><?php esc_html_e( 'Update cart', 'aqualuxe' ); ?></button>

				<?php do_action( 'woocommerce_cart_actions' ); ?>

				<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
			</div>

			<?php do_action( 'woocommerce_after_cart_contents' ); ?>
		</div>
		<?php do_action( 'woocommerce_after_cart_table' ); ?>
	</form>

	<?php do_action( 'woocommerce_before_cart_collaterals' ); ?>

	<div class="cart-collaterals mt-8">
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