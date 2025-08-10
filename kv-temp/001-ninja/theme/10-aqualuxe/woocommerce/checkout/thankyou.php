<?php
/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package AquaLuxe
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="woocommerce-order">

	<?php
	if ( $order ) :

		do_action( 'woocommerce_before_thankyou', $order->get_id() );
		?>

		<?php if ( $order->has_status( 'failed' ) ) : ?>

			<div class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed">
				<div class="notice-icon">
					<i class="fas fa-exclamation-circle"></i>
				</div>
				<div class="notice-content">
					<h2><?php esc_html_e( 'Order Failed', 'aqualuxe' ); ?></h2>
					<p><?php esc_html_e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'aqualuxe' ); ?></p>
				</div>
			</div>

			<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed-actions">
				<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay"><?php esc_html_e( 'Pay', 'aqualuxe' ); ?></a>
				<?php if ( is_user_logged_in() ) : ?>
					<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="button pay"><?php esc_html_e( 'My account', 'aqualuxe' ); ?></a>
				<?php endif; ?>
			</p>

		<?php else : ?>

			<div class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received">
				<div class="success-animation">
					<svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
						<circle class="checkmark-circle" cx="26" cy="26" r="25" fill="none"/>
						<path class="checkmark-check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
					</svg>
				</div>
				<h2><?php esc_html_e( 'Thank you for your order!', 'aqualuxe' ); ?></h2>
				<p><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', esc_html__( 'Your order has been received and is now being processed.', 'aqualuxe' ), $order ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
			</div>

			<div class="order-confirmation-details">
				<div class="order-confirmation-header">
					<h3><?php esc_html_e( 'Order Details', 'aqualuxe' ); ?></h3>
				</div>

				<ul class="woocommerce-order-overview woocommerce-thankyou-order-details order_details">

					<li class="woocommerce-order-overview__order order">
						<div class="overview-label"><?php esc_html_e( 'Order number:', 'aqualuxe' ); ?></div>
						<div class="overview-value"><?php echo $order->get_order_number(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
					</li>

					<li class="woocommerce-order-overview__date date">
						<div class="overview-label"><?php esc_html_e( 'Date:', 'aqualuxe' ); ?></div>
						<div class="overview-value"><?php echo wc_format_datetime( $order->get_date_created() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
					</li>

					<?php if ( is_user_logged_in() && $order->get_user_id() === get_current_user_id() && $order->get_billing_email() ) : ?>
						<li class="woocommerce-order-overview__email email">
							<div class="overview-label"><?php esc_html_e( 'Email:', 'aqualuxe' ); ?></div>
							<div class="overview-value"><?php echo $order->get_billing_email(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
						</li>
					<?php endif; ?>

					<li class="woocommerce-order-overview__total total">
						<div class="overview-label"><?php esc_html_e( 'Total:', 'aqualuxe' ); ?></div>
						<div class="overview-value"><?php echo $order->get_formatted_order_total(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
					</li>

					<?php if ( $order->get_payment_method_title() ) : ?>
						<li class="woocommerce-order-overview__payment-method method">
							<div class="overview-label"><?php esc_html_e( 'Payment method:', 'aqualuxe' ); ?></div>
							<div class="overview-value"><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></div>
						</li>
					<?php endif; ?>

				</ul>
			</div>

			<?php
			// Check if order contains live fish products
			$has_live_fish = false;
			foreach ( $order->get_items() as $item ) {
				$product = $item->get_product();
				if ( $product && has_term( 'live-fish', 'product_cat', $product->get_id() ) ) {
					$has_live_fish = true;
					break;
				}
			}
			
			if ( $has_live_fish ) :
			?>
			<div class="live-fish-notice">
				<div class="notice-icon">
					<i class="fas fa-fish"></i>
				</div>
				<div class="notice-content">
					<h3><?php esc_html_e( 'Live Fish Shipping Information', 'aqualuxe' ); ?></h3>
					<p><?php esc_html_e( 'Your order contains live fish which will be carefully packaged with appropriate heating/cooling packs based on your location and current weather conditions.', 'aqualuxe' ); ?></p>
					<p><?php esc_html_e( 'We will contact you before shipping to confirm delivery details and ensure someone will be available to receive the package.', 'aqualuxe' ); ?></p>
				</div>
			</div>
			<?php endif; ?>

			<div class="order-details-wrapper">
				<?php do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() ); ?>
				
				<div class="order-details-container">
					<?php do_action( 'woocommerce_thankyou', $order->get_id() ); ?>
				</div>
			</div>

			<div class="order-actions">
				<?php if ( is_user_logged_in() ) : ?>
					<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) . 'orders/' ); ?>" class="button view-order-button">
						<i class="fas fa-clipboard-list"></i> <?php esc_html_e( 'View Order History', 'aqualuxe' ); ?>
					</a>
				<?php endif; ?>
				
				<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="button continue-shopping-button">
					<i class="fas fa-shopping-bag"></i> <?php esc_html_e( 'Continue Shopping', 'aqualuxe' ); ?>
				</a>
			</div>

		<?php endif; ?>

	<?php else : ?>

		<div class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received">
			<div class="success-animation">
				<svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
					<circle class="checkmark-circle" cx="26" cy="26" r="25" fill="none"/>
					<path class="checkmark-check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
				</svg>
			</div>
			<h2><?php esc_html_e( 'Thank you for your order!', 'aqualuxe' ); ?></h2>
			<p><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', esc_html__( 'Your order has been received and is now being processed.', 'aqualuxe' ), null ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
		</div>

	<?php endif; ?>

</div>

<?php
// Display recommended products
$cross_sells = array();
if ( $order ) {
	foreach ( $order->get_items() as $item ) {
		$product_id = $item->get_product_id();
		$cross_sell_ids = get_post_meta( $product_id, '_crosssell_ids', true );
		if ( is_array( $cross_sell_ids ) && ! empty( $cross_sell_ids ) ) {
			$cross_sells = array_merge( $cross_sells, $cross_sell_ids );
		}
	}
}

if ( ! empty( $cross_sells ) ) :
	$cross_sells = array_unique( $cross_sells );
	$args = array(
		'posts_per_page' => 4,
		'columns'        => 4,
		'orderby'        => 'rand',
		'post_type'      => 'product',
		'post__in'       => $cross_sells,
	);
	
	$products = new WP_Query( $args );
	
	if ( $products->have_posts() ) :
	?>
	<section class="related-products">
		<h2><?php esc_html_e( 'You Might Also Like', 'aqualuxe' ); ?></h2>
		
		<ul class="products columns-4">
			<?php while ( $products->have_posts() ) : $products->the_post(); ?>
				<?php wc_get_template_part( 'content', 'product' ); ?>
			<?php endwhile; ?>
		</ul>
	</section>
	<?php
	endif;
	
	wp_reset_postdata();
endif;
?>