<?php
/**
 * My Account page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/my-account.php.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package AquaLuxe
 * @version 3.5.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * My Account navigation.
 *
 * @since 2.6.0
 */
do_action( 'woocommerce_account_navigation' ); ?>

<div class="woocommerce-MyAccount-content">
	<?php
		/**
		 * My Account content.
		 *
		 * @since 2.6.0
		 */
		do_action( 'woocommerce_account_content' );
	?>
	
	<?php
	// Add custom dashboard widgets if we're on the dashboard page
	if ( is_wc_endpoint_url( '' ) || is_wc_endpoint_url( 'dashboard' ) ) :
	?>
	<div class="dashboard-widgets mt-8">
		<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
			<!-- Recent Orders Widget -->
			<div class="dashboard-widget bg-white p-6 rounded-lg shadow-sm">
				<h3 class="text-lg font-medium mb-4 pb-2 border-b border-gray-200"><?php esc_html_e( 'Recent Orders', 'aqualuxe' ); ?></h3>
				<div class="dashboard-widget-content">
					<?php
					$customer_orders = wc_get_orders( array(
						'customer' => get_current_user_id(),
						'limit'    => 5,
					) );

					if ( $customer_orders ) :
					?>
					<ul class="recent-orders-list">
						<?php
						foreach ( $customer_orders as $customer_order ) :
							$order = wc_get_order( $customer_order );
							$item_count = $order->get_item_count();
						?>
						<li class="mb-3 pb-3 border-b border-gray-200 last:border-b-0 last:mb-0 last:pb-0">
							<div class="flex justify-between">
								<div>
									<span class="order-number font-medium"><?php echo esc_html( _x( '#', 'hash before order number', 'aqualuxe' ) . $order->get_order_number() ); ?></span>
									<span class="order-date text-sm text-gray-600 ml-2"><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></span>
								</div>
								<div class="order-status">
									<?php echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?>
								</div>
							</div>
							<div class="flex justify-between items-center mt-2">
								<div class="order-items text-sm text-gray-600">
									<?php
									/* translators: 1: formatted order total 2: total order items */
									printf( _n( '%1$s for %2$s item', '%1$s for %2$s items', $item_count, 'aqualuxe' ), $order->get_formatted_order_total(), $item_count );
									?>
								</div>
								<div>
									<a href="<?php echo esc_url( $order->get_view_order_url() ); ?>" class="text-sm text-primary hover:text-primary-dark"><?php esc_html_e( 'View', 'aqualuxe' ); ?></a>
								</div>
							</div>
						</li>
						<?php endforeach; ?>
					</ul>
					<?php else : ?>
					<p><?php esc_html_e( 'No order has been made yet.', 'aqualuxe' ); ?></p>
					<?php endif; ?>
					
					<div class="mt-4 text-center">
						<a href="<?php echo esc_url( wc_get_endpoint_url( 'orders' ) ); ?>" class="inline-block py-2 px-4 bg-primary hover:bg-primary-dark text-white text-sm font-medium rounded-md transition-colors duration-200"><?php esc_html_e( 'View All Orders', 'aqualuxe' ); ?></a>
					</div>
				</div>
			</div>
			
			<!-- Account Details Widget -->
			<div class="dashboard-widget bg-white p-6 rounded-lg shadow-sm">
				<h3 class="text-lg font-medium mb-4 pb-2 border-b border-gray-200"><?php esc_html_e( 'Account Details', 'aqualuxe' ); ?></h3>
				<div class="dashboard-widget-content">
					<?php
					$customer = new WC_Customer( get_current_user_id() );
					?>
					<div class="mb-4">
						<p class="mb-1"><strong><?php esc_html_e( 'Name:', 'aqualuxe' ); ?></strong> <?php echo esc_html( $customer->get_first_name() . ' ' . $customer->get_last_name() ); ?></p>
						<p class="mb-1"><strong><?php esc_html_e( 'Email:', 'aqualuxe' ); ?></strong> <?php echo esc_html( $customer->get_email() ); ?></p>
						
						<?php if ( $customer->get_billing_phone() ) : ?>
						<p class="mb-1"><strong><?php esc_html_e( 'Phone:', 'aqualuxe' ); ?></strong> <?php echo esc_html( $customer->get_billing_phone() ); ?></p>
						<?php endif; ?>
					</div>
					
					<div class="mt-4 text-center">
						<a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-account' ) ); ?>" class="inline-block py-2 px-4 bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-medium rounded-md transition-colors duration-200"><?php esc_html_e( 'Edit Account Details', 'aqualuxe' ); ?></a>
					</div>
				</div>
			</div>
			
			<!-- Shipping Address Widget -->
			<div class="dashboard-widget bg-white p-6 rounded-lg shadow-sm">
				<h3 class="text-lg font-medium mb-4 pb-2 border-b border-gray-200"><?php esc_html_e( 'Shipping Address', 'aqualuxe' ); ?></h3>
				<div class="dashboard-widget-content">
					<address class="mb-4">
						<?php
						$shipping_address = array(
							'first_name' => $customer->get_shipping_first_name(),
							'last_name'  => $customer->get_shipping_last_name(),
							'company'    => $customer->get_shipping_company(),
							'address_1'  => $customer->get_shipping_address_1(),
							'address_2'  => $customer->get_shipping_address_2(),
							'city'       => $customer->get_shipping_city(),
							'state'      => $customer->get_shipping_state(),
							'postcode'   => $customer->get_shipping_postcode(),
							'country'    => $customer->get_shipping_country(),
						);

						$formatted_address = WC()->countries->get_formatted_address( $shipping_address );
						
						if ( $formatted_address ) {
							echo wp_kses_post( $formatted_address );
						} else {
							esc_html_e( 'You have not set up a shipping address yet.', 'aqualuxe' );
						}
						?>
					</address>
					
					<div class="mt-4 text-center">
						<a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address', 'shipping' ) ); ?>" class="inline-block py-2 px-4 bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-medium rounded-md transition-colors duration-200"><?php esc_html_e( 'Edit Shipping Address', 'aqualuxe' ); ?></a>
					</div>
				</div>
			</div>
			
			<!-- Billing Address Widget -->
			<div class="dashboard-widget bg-white p-6 rounded-lg shadow-sm">
				<h3 class="text-lg font-medium mb-4 pb-2 border-b border-gray-200"><?php esc_html_e( 'Billing Address', 'aqualuxe' ); ?></h3>
				<div class="dashboard-widget-content">
					<address class="mb-4">
						<?php
						$billing_address = array(
							'first_name' => $customer->get_billing_first_name(),
							'last_name'  => $customer->get_billing_last_name(),
							'company'    => $customer->get_billing_company(),
							'address_1'  => $customer->get_billing_address_1(),
							'address_2'  => $customer->get_billing_address_2(),
							'city'       => $customer->get_billing_city(),
							'state'      => $customer->get_billing_state(),
							'postcode'   => $customer->get_billing_postcode(),
							'country'    => $customer->get_billing_country(),
						);

						$formatted_address = WC()->countries->get_formatted_address( $billing_address );
						
						if ( $formatted_address ) {
							echo wp_kses_post( $formatted_address );
						} else {
							esc_html_e( 'You have not set up a billing address yet.', 'aqualuxe' );
						}
						?>
					</address>
					
					<div class="mt-4 text-center">
						<a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address', 'billing' ) ); ?>" class="inline-block py-2 px-4 bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-medium rounded-md transition-colors duration-200"><?php esc_html_e( 'Edit Billing Address', 'aqualuxe' ); ?></a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php endif; ?>
</div>