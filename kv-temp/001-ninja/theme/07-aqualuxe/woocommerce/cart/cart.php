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
 * @version 7.0.1
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_cart' ); ?>

<div class="cart-wrapper bg-white dark:bg-dark-800 rounded-lg shadow-medium overflow-hidden">
	<form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
		<?php do_action( 'woocommerce_before_cart_table' ); ?>

		<div class="shop_table shop_table_responsive cart woocommerce-cart-form__contents p-6">
			<div class="cart-header border-b border-gray-200 dark:border-dark-700 pb-4 mb-4 hidden md:grid md:grid-cols-12 gap-4">
				<div class="product-thumbnail md:col-span-2 font-medium text-gray-700 dark:text-gray-300">
					<?php esc_html_e( 'Product', 'aqualuxe' ); ?>
				</div>
				<div class="product-name md:col-span-4 font-medium text-gray-700 dark:text-gray-300">
					<?php esc_html_e( 'Description', 'aqualuxe' ); ?>
				</div>
				<div class="product-price md:col-span-2 font-medium text-gray-700 dark:text-gray-300">
					<?php esc_html_e( 'Price', 'aqualuxe' ); ?>
				</div>
				<div class="product-quantity md:col-span-2 font-medium text-gray-700 dark:text-gray-300">
					<?php esc_html_e( 'Quantity', 'aqualuxe' ); ?>
				</div>
				<div class="product-subtotal md:col-span-2 font-medium text-gray-700 dark:text-gray-300 text-right">
					<?php esc_html_e( 'Subtotal', 'aqualuxe' ); ?>
				</div>
			</div>

			<?php do_action( 'woocommerce_before_cart_contents' ); ?>

			<?php
			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
				$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
				$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

				if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
					$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
					?>
					<div class="woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?> grid grid-cols-1 md:grid-cols-12 gap-4 py-4 border-b border-gray-200 dark:border-dark-700 last:border-0">

						<div class="product-thumbnail md:col-span-2">
							<div class="flex items-center">
								<div class="product-remove mr-2 md:hidden">
									<?php
									echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
										'woocommerce_cart_item_remove_link',
										sprintf(
											'<a href="%s" class="remove text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 transition-colors duration-300" aria-label="%s" data-product_id="%s" data-product_sku="%s">
												<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
													<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
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
								<?php
								$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image( 'thumbnail', array( 'class' => 'rounded-lg' ) ), $cart_item, $cart_item_key );

								if ( ! $product_permalink ) {
									echo $thumbnail; // PHPCS: XSS ok.
								} else {
									printf( '<a href="%s" class="block">%s</a>', esc_url( $product_permalink ), $thumbnail ); // PHPCS: XSS ok.
								}
								?>
							</div>
						</div>

						<div class="product-name md:col-span-4" data-title="<?php esc_attr_e( 'Product', 'aqualuxe' ); ?>">
							<div class="flex flex-col">
								<?php
								if ( ! $product_permalink ) {
									echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;' );
								} else {
									echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s" class="text-gray-900 dark:text-gray-100 font-medium hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-300">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
								}

								do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );

								// Meta data.
								echo wc_get_formatted_cart_item_data( $cart_item ); // PHPCS: XSS ok.

								// Backorder notification.
								if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
									echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification text-sm text-amber-600 dark:text-amber-400 mt-1">' . esc_html__( 'Available on backorder', 'aqualuxe' ) . '</p>', $product_id ) );
								}
								?>
								
								<div class="product-remove hidden md:block mt-2">
									<?php
									echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
										'woocommerce_cart_item_remove_link',
										sprintf(
											'<a href="%s" class="remove text-sm text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 transition-colors duration-300 inline-flex items-center" aria-label="%s" data-product_id="%s" data-product_sku="%s">
												<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
													<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
												</svg>
												%s
											</a>',
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

						<div class="product-price md:col-span-2" data-title="<?php esc_attr_e( 'Price', 'aqualuxe' ); ?>">
							<?php
								echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
							?>
						</div>

						<div class="product-quantity md:col-span-2" data-title="<?php esc_attr_e( 'Quantity', 'aqualuxe' ); ?>">
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
										'classes'      => apply_filters( 'woocommerce_quantity_input_classes', array( 'input-text', 'qty', 'text', 'w-16', 'p-2', 'border', 'border-gray-300', 'dark:border-dark-600', 'rounded-lg', 'bg-white', 'dark:bg-dark-700', 'text-gray-900', 'dark:text-gray-100', 'focus:ring-2', 'focus:ring-primary-500', 'focus:border-primary-500', 'transition-colors', 'duration-300' ), $product_id ),
									),
									$_product,
									false
								);
							}

							echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item ); // PHPCS: XSS ok.
							?>
						</div>

						<div class="product-subtotal md:col-span-2 text-right" data-title="<?php esc_attr_e( 'Subtotal', 'aqualuxe' ); ?>">
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

			<div class="actions flex flex-col md:flex-row justify-between items-start md:items-center pt-6">
				<?php if ( wc_coupons_enabled() ) { ?>
					<div class="coupon flex flex-col sm:flex-row mb-4 md:mb-0">
						<label for="coupon_code" class="sr-only"><?php esc_html_e( 'Coupon:', 'aqualuxe' ); ?></label>
						<input type="text" name="coupon_code" class="input-text w-full sm:w-auto mb-2 sm:mb-0 sm:mr-2 p-2 border border-gray-300 dark:border-dark-600 rounded-lg bg-white dark:bg-dark-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-300" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'aqualuxe' ); ?>" />
						<button type="submit" class="button w-full sm:w-auto px-4 py-2 bg-gray-600 dark:bg-gray-700 text-white rounded-lg hover:bg-gray-700 dark:hover:bg-gray-600 transition-colors duration-300" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'aqualuxe' ); ?>"><?php esc_attr_e( 'Apply coupon', 'aqualuxe' ); ?></button>
						<?php do_action( 'woocommerce_cart_coupon' ); ?>
					</div>
				<?php } ?>

				<button type="submit" class="button w-full md:w-auto px-4 py-2 bg-primary-600 dark:bg-primary-700 text-white rounded-lg hover:bg-primary-700 dark:hover:bg-primary-600 transition-colors duration-300" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'aqualuxe' ); ?>"><?php esc_html_e( 'Update cart', 'aqualuxe' ); ?></button>

				<?php do_action( 'woocommerce_cart_actions' ); ?>

				<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
			</div>

			<?php do_action( 'woocommerce_after_cart_contents' ); ?>
		</div>
		<?php do_action( 'woocommerce_after_cart_table' ); ?>
	</form>

	<?php do_action( 'woocommerce_before_cart_collaterals' ); ?>

	<div class="cart-collaterals p-6 bg-gray-50 dark:bg-dark-700 border-t border-gray-200 dark:border-dark-600">
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

<?php if ( wc_get_page_id( 'shop' ) > 0 ) : ?>
	<div class="continue-shopping mt-6">
		<a href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>" class="inline-flex items-center text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 transition-colors duration-300">
			<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
				<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
			</svg>
			<?php esc_html_e( 'Continue shopping', 'aqualuxe' ); ?>
		</a>
	</div>
<?php endif; ?>

<?php do_action( 'woocommerce_after_cart' ); ?>

<style>
	/* Cart page custom styles */
	.woocommerce-cart-form .product-thumbnail img {
		width: 80px;
		height: auto;
	}
	
	.woocommerce-cart-form .product-name a {
		font-weight: 500;
	}
	
	.woocommerce-cart-form .product-price,
	.woocommerce-cart-form .product-subtotal {
		font-weight: 500;
	}
	
	.woocommerce-cart-form .product-quantity .quantity {
		display: inline-flex;
	}
	
	.woocommerce-cart-form .actions {
		margin-top: 1.5rem;
	}
	
	.woocommerce-cart-form .actions .coupon {
		display: flex;
		align-items: center;
	}
	
	.woocommerce-cart-form .actions .coupon input {
		margin-right: 0.5rem;
	}
	
	.woocommerce .cart-collaterals .cart_totals,
	.woocommerce-page .cart-collaterals .cart_totals {
		float: right;
		width: 100%;
		max-width: 400px;
		margin-left: auto;
	}
	
	.woocommerce .cart-collaterals .cart_totals h2,
	.woocommerce-page .cart-collaterals .cart_totals h2 {
		font-size: 1.25rem;
		font-weight: 600;
		margin-bottom: 1rem;
	}
	
	.woocommerce .cart-collaterals .cart_totals table,
	.woocommerce-page .cart-collaterals .cart_totals table {
		width: 100%;
		margin-bottom: 1rem;
	}
	
	.woocommerce .cart-collaterals .cart_totals table th,
	.woocommerce-page .cart-collaterals .cart_totals table th {
		width: 40%;
		padding: 0.75rem 0;
		text-align: left;
		font-weight: 500;
		border-top: 1px solid #e5e7eb;
	}
	
	.dark .woocommerce .cart-collaterals .cart_totals table th,
	.dark .woocommerce-page .cart-collaterals .cart_totals table th {
		border-color: #374151;
	}
	
	.woocommerce .cart-collaterals .cart_totals table td,
	.woocommerce-page .cart-collaterals .cart_totals table td {
		padding: 0.75rem 0;
		text-align: right;
		border-top: 1px solid #e5e7eb;
	}
	
	.dark .woocommerce .cart-collaterals .cart_totals table td,
	.dark .woocommerce-page .cart-collaterals .cart_totals table td {
		border-color: #374151;
	}
	
	.woocommerce .cart-collaterals .cart_totals .order-total th,
	.woocommerce-page .cart-collaterals .cart_totals .order-total th,
	.woocommerce .cart-collaterals .cart_totals .order-total td,
	.woocommerce-page .cart-collaterals .cart_totals .order-total td {
		font-weight: 600;
	}
	
	.woocommerce .cart-collaterals .cart_totals .wc-proceed-to-checkout,
	.woocommerce-page .cart-collaterals .cart_totals .wc-proceed-to-checkout {
		padding: 1rem 0 0;
	}
	
	.woocommerce .cart-collaterals .cart_totals .wc-proceed-to-checkout a.checkout-button,
	.woocommerce-page .cart-collaterals .cart_totals .wc-proceed-to-checkout a.checkout-button {
		display: block;
		text-align: center;
		margin-bottom: 0;
		font-size: 1rem;
		padding: 0.75rem 1.5rem;
	}
	
	.woocommerce .cart-collaterals .cross-sells,
	.woocommerce-page .cart-collaterals .cross-sells {
		width: 100%;
		margin-bottom: 2rem;
	}
	
	.woocommerce .cart-collaterals .cross-sells h2,
	.woocommerce-page .cart-collaterals .cross-sells h2 {
		font-size: 1.25rem;
		font-weight: 600;
		margin-bottom: 1rem;
	}
	
	.woocommerce .cart-collaterals .cross-sells ul.products,
	.woocommerce-page .cart-collaterals .cross-sells ul.products {
		display: grid;
		grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
		gap: 1rem;
	}
	
	.woocommerce .cart-collaterals .cross-sells ul.products li.product,
	.woocommerce-page .cart-collaterals .cross-sells ul.products li.product {
		width: 100%;
		margin: 0;
	}
	
	/* Mobile styles */
	@media (max-width: 767px) {
		.woocommerce-cart-form__cart-item {
			position: relative;
			padding-top: 1rem;
			padding-bottom: 1rem;
		}
		
		.woocommerce-cart-form__cart-item .product-thumbnail {
			width: 80px;
			float: left;
			margin-right: 1rem;
		}
		
		.woocommerce-cart-form__cart-item .product-name {
			margin-bottom: 0.5rem;
		}
		
		.woocommerce-cart-form__cart-item .product-price {
			margin-bottom: 0.5rem;
		}
		
		.woocommerce-cart-form__cart-item .product-quantity {
			margin-bottom: 0.5rem;
		}
		
		.woocommerce-cart-form__cart-item .product-subtotal {
			font-weight: 600;
		}
		
		.woocommerce-cart-form__cart-item .product-remove {
			position: absolute;
			top: 1rem;
			right: 0;
		}
		
		.woocommerce-cart-form__cart-item::after {
			content: "";
			display: table;
			clear: both;
		}
		
		.woocommerce .cart-collaterals .cart_totals,
		.woocommerce-page .cart-collaterals .cart_totals {
			float: none;
			width: 100%;
			max-width: none;
		}
	}
</style>