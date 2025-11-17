<?php
/**
 * Review order table
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/review-order.php.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package AquaLuxe
 * @version 5.2.0
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="shop_table woocommerce-checkout-review-order-table">
	<div class="order-items-header flex justify-between pb-3 border-b border-gray-200 dark:border-dark-700 mb-4">
		<div class="text-dark-700 dark:text-dark-300 text-sm font-medium"><?php esc_html_e( 'Product', 'aqualuxe' ); ?></div>
		<div class="text-dark-700 dark:text-dark-300 text-sm font-medium"><?php esc_html_e( 'Subtotal', 'aqualuxe' ); ?></div>
	</div>
	
	<div class="order-items space-y-4 mb-6">
		<?php
		do_action( 'woocommerce_review_order_before_cart_contents' );

		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

			if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
				?>
				<div class="<?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?> flex justify-between">
					<div class="product-info flex-grow pr-4">
						<div class="product-name text-dark-900 dark:text-white">
							<?php echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) ) . '&nbsp;'; ?>
							<?php echo apply_filters( 'woocommerce_checkout_cart_item_quantity', ' <span class="product-quantity text-dark-500 dark:text-dark-400">× ' . $cart_item['quantity'] . '</span>', $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</div>
						<div class="product-meta text-sm text-dark-500 dark:text-dark-400">
							<?php echo wc_get_formatted_cart_item_data( $cart_item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</div>
					</div>
					<div class="product-total text-dark-900 dark:text-white font-medium">
						<?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
				</div>
				<?php
			}
		}

		do_action( 'woocommerce_review_order_after_cart_contents' );
		?>
	</div>
	
	<div class="order-totals space-y-3">
		<div class="cart-subtotal flex justify-between py-3 border-t border-gray-200 dark:border-dark-700">
			<div class="text-dark-700 dark:text-dark-300"><?php esc_html_e( 'Subtotal', 'aqualuxe' ); ?></div>
			<div class="text-dark-900 dark:text-white"><?php wc_cart_totals_subtotal_html(); ?></div>
		</div>

		<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
			<div class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?> flex justify-between">
				<div class="text-dark-700 dark:text-dark-300"><?php wc_cart_totals_coupon_label( $coupon ); ?></div>
				<div class="text-green-600 dark:text-green-400"><?php wc_cart_totals_coupon_html( $coupon ); ?></div>
			</div>
		<?php endforeach; ?>

		<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
			<div class="shipping-section py-3 border-t border-gray-200 dark:border-dark-700">
				<?php do_action( 'woocommerce_review_order_before_shipping' ); ?>
				<?php wc_cart_totals_shipping_html(); ?>
				<?php do_action( 'woocommerce_review_order_after_shipping' ); ?>
			</div>
		<?php endif; ?>

		<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
			<div class="fee flex justify-between">
				<div class="text-dark-700 dark:text-dark-300"><?php echo esc_html( $fee->name ); ?></div>
				<div class="text-dark-900 dark:text-white"><?php wc_cart_totals_fee_html( $fee ); ?></div>
			</div>
		<?php endforeach; ?>

		<?php if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) : ?>
			<?php if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) : ?>
				<?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited ?>
					<div class="tax-rate tax-rate-<?php echo esc_attr( sanitize_title( $code ) ); ?> flex justify-between">
						<div class="text-dark-700 dark:text-dark-300"><?php echo esc_html( $tax->label ); ?></div>
						<div class="text-dark-900 dark:text-white"><?php echo wp_kses_post( $tax->formatted_amount ); ?></div>
					</div>
				<?php endforeach; ?>
			<?php else : ?>
				<div class="tax-total flex justify-between">
					<div class="text-dark-700 dark:text-dark-300"><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></div>
					<div class="text-dark-900 dark:text-white"><?php wc_cart_totals_taxes_total_html(); ?></div>
				</div>
			<?php endif; ?>
		<?php endif; ?>

		<?php do_action( 'woocommerce_review_order_before_order_total' ); ?>

		<div class="order-total flex justify-between py-3 border-t border-gray-200 dark:border-dark-700">
			<div class="text-dark-900 dark:text-white font-bold"><?php esc_html_e( 'Total', 'aqualuxe' ); ?></div>
			<div class="text-dark-900 dark:text-white font-bold text-xl"><?php wc_cart_totals_order_total_html(); ?></div>
		</div>

		<?php do_action( 'woocommerce_review_order_after_order_total' ); ?>
	</div>
</div>