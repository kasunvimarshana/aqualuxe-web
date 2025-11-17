<?php
/**
 * My Account Dashboard
 *
 * Shows the first intro screen on the account dashboard.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/dashboard.php.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package AquaLuxe
 * @version 4.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$allowed_html = array(
	'a' => array(
		'href' => array(),
		'class' => array(),
	),
);
?>

<div class="woocommerce-MyAccount-dashboard bg-white dark:bg-dark-800 rounded-lg shadow-sm p-6">
	<div class="dashboard-welcome mb-8">
		<h2 class="text-2xl font-serif font-bold text-dark-900 dark:text-white mb-4">
			<?php
			printf(
				/* translators: %s: Customer first name */
				esc_html__( 'Hello %s,', 'aqualuxe' ),
				'<span class="text-primary-600 dark:text-primary-400">' . esc_html( $current_user->display_name ) . '</span>'
			);
			?>
		</h2>
		
		<p class="text-dark-700 dark:text-dark-300">
			<?php
			printf(
				/* translators: 1: Orders URL 2: Address URL 3: Account URL. */
				__( 'From your account dashboard you can view your <a href="%1$s" class="text-primary-600 dark:text-primary-400 hover:underline">recent orders</a>, manage your <a href="%2$s" class="text-primary-600 dark:text-primary-400 hover:underline">shipping and billing addresses</a>, and <a href="%3$s" class="text-primary-600 dark:text-primary-400 hover:underline">edit your password and account details</a>.', 'aqualuxe' ),
				esc_url( wc_get_endpoint_url( 'orders' ) ),
				esc_url( wc_get_endpoint_url( 'edit-address' ) ),
				esc_url( wc_get_endpoint_url( 'edit-account' ) )
			);
			?>
		</p>
	</div>

	<div class="dashboard-cards grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
		<div class="dashboard-card bg-gray-50 dark:bg-dark-700 rounded-lg p-6">
			<div class="card-icon mb-4 text-primary-600 dark:text-primary-400">
				<svg class="w-8 h-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
				</svg>
			</div>
			<h3 class="text-lg font-medium text-dark-900 dark:text-white mb-2"><?php esc_html_e( 'Recent Orders', 'aqualuxe' ); ?></h3>
			<p class="text-dark-600 dark:text-dark-400 text-sm mb-4"><?php esc_html_e( 'View and track your recent orders and check order status.', 'aqualuxe' ); ?></p>
			<a href="<?php echo esc_url( wc_get_endpoint_url( 'orders' ) ); ?>" class="inline-flex items-center text-primary-600 dark:text-primary-400 hover:underline text-sm">
				<?php esc_html_e( 'View Orders', 'aqualuxe' ); ?>
				<svg class="ml-1 w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
					<path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
				</svg>
			</a>
		</div>

		<div class="dashboard-card bg-gray-50 dark:bg-dark-700 rounded-lg p-6">
			<div class="card-icon mb-4 text-primary-600 dark:text-primary-400">
				<svg class="w-8 h-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
				</svg>
			</div>
			<h3 class="text-lg font-medium text-dark-900 dark:text-white mb-2"><?php esc_html_e( 'Addresses', 'aqualuxe' ); ?></h3>
			<p class="text-dark-600 dark:text-dark-400 text-sm mb-4"><?php esc_html_e( 'Manage your billing and shipping addresses for faster checkout.', 'aqualuxe' ); ?></p>
			<a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address' ) ); ?>" class="inline-flex items-center text-primary-600 dark:text-primary-400 hover:underline text-sm">
				<?php esc_html_e( 'Manage Addresses', 'aqualuxe' ); ?>
				<svg class="ml-1 w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
					<path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
				</svg>
			</a>
		</div>

		<div class="dashboard-card bg-gray-50 dark:bg-dark-700 rounded-lg p-6">
			<div class="card-icon mb-4 text-primary-600 dark:text-primary-400">
				<svg class="w-8 h-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
				</svg>
			</div>
			<h3 class="text-lg font-medium text-dark-900 dark:text-white mb-2"><?php esc_html_e( 'Account Details', 'aqualuxe' ); ?></h3>
			<p class="text-dark-600 dark:text-dark-400 text-sm mb-4"><?php esc_html_e( 'Update your account information and change your password.', 'aqualuxe' ); ?></p>
			<a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-account' ) ); ?>" class="inline-flex items-center text-primary-600 dark:text-primary-400 hover:underline text-sm">
				<?php esc_html_e( 'Edit Account', 'aqualuxe' ); ?>
				<svg class="ml-1 w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
					<path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
				</svg>
			</a>
		</div>
	</div>

	<?php
	/**
	 * My Account dashboard.
	 *
	 * @since 2.6.0
	 */
	do_action( 'woocommerce_account_dashboard' );

	/**
	 * Deprecated woocommerce_before_my_account action.
	 *
	 * @deprecated 2.6.0
	 */
	do_action( 'woocommerce_before_my_account' );

	/**
	 * Deprecated woocommerce_after_my_account action.
	 *
	 * @deprecated 2.6.0
	 */
	do_action( 'woocommerce_after_my_account' );
	?>

	<?php if ( function_exists( 'wc_get_customer_recent_orders' ) ) : 
		$recent_orders = wc_get_customer_recent_orders( get_current_user_id(), 5 );
		
		if ( $recent_orders ) : ?>
			<div class="recent-orders mt-8 pt-8 border-t border-gray-200 dark:border-dark-700">
				<h3 class="text-xl font-serif font-bold text-dark-900 dark:text-white mb-6"><?php esc_html_e( 'Recent Orders', 'aqualuxe' ); ?></h3>
				
				<div class="overflow-x-auto">
					<table class="woocommerce-orders-table woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table w-full">
						<thead>
							<tr>
								<th class="woocommerce-orders-table__header woocommerce-orders-table__header-order-number py-3 px-4 text-left bg-gray-50 dark:bg-dark-700 text-dark-700 dark:text-dark-300 text-sm font-medium rounded-tl-lg"><span class="nobr"><?php esc_html_e( 'Order', 'aqualuxe' ); ?></span></th>
								<th class="woocommerce-orders-table__header woocommerce-orders-table__header-order-date py-3 px-4 text-left bg-gray-50 dark:bg-dark-700 text-dark-700 dark:text-dark-300 text-sm font-medium"><span class="nobr"><?php esc_html_e( 'Date', 'aqualuxe' ); ?></span></th>
								<th class="woocommerce-orders-table__header woocommerce-orders-table__header-order-status py-3 px-4 text-left bg-gray-50 dark:bg-dark-700 text-dark-700 dark:text-dark-300 text-sm font-medium"><span class="nobr"><?php esc_html_e( 'Status', 'aqualuxe' ); ?></span></th>
								<th class="woocommerce-orders-table__header woocommerce-orders-table__header-order-total py-3 px-4 text-left bg-gray-50 dark:bg-dark-700 text-dark-700 dark:text-dark-300 text-sm font-medium"><span class="nobr"><?php esc_html_e( 'Total', 'aqualuxe' ); ?></span></th>
								<th class="woocommerce-orders-table__header woocommerce-orders-table__header-order-actions py-3 px-4 text-right bg-gray-50 dark:bg-dark-700 text-dark-700 dark:text-dark-300 text-sm font-medium rounded-tr-lg"><span class="nobr"><?php esc_html_e( 'Actions', 'aqualuxe' ); ?></span></th>
							</tr>
						</thead>

						<tbody>
							<?php foreach ( $recent_orders as $order ) : 
								$item_count = $order->get_item_count();
								?>
								<tr class="woocommerce-orders-table__row woocommerce-orders-table__row--status-<?php echo esc_attr( $order->get_status() ); ?> order border-b border-gray-200 dark:border-dark-700 last:border-0">
									<td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-order-number py-4 px-4 text-dark-900 dark:text-white" data-title="<?php esc_attr_e( 'Order', 'aqualuxe' ); ?>">
										<a href="<?php echo esc_url( $order->get_view_order_url() ); ?>" class="text-primary-600 dark:text-primary-400 hover:underline">
											<?php echo esc_html( _x( '#', 'hash before order number', 'aqualuxe' ) . $order->get_order_number() ); ?>
										</a>
									</td>
									<td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-order-date py-4 px-4 text-dark-700 dark:text-dark-300" data-title="<?php esc_attr_e( 'Date', 'aqualuxe' ); ?>">
										<time datetime="<?php echo esc_attr( $order->get_date_created()->date( 'c' ) ); ?>"><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></time>
									</td>
									<td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-order-status py-4 px-4" data-title="<?php esc_attr_e( 'Status', 'aqualuxe' ); ?>">
										<?php 
										$status = $order->get_status();
										$status_name = wc_get_order_status_name( $status );
										
										$status_classes = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium';
										
										switch ( $status ) {
											case 'completed':
												$status_classes .= ' bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200';
												break;
											case 'processing':
												$status_classes .= ' bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200';
												break;
											case 'on-hold':
												$status_classes .= ' bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200';
												break;
											case 'pending':
												$status_classes .= ' bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200';
												break;
											case 'cancelled':
											case 'refunded':
											case 'failed':
												$status_classes .= ' bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200';
												break;
											default:
												$status_classes .= ' bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200';
												break;
										}
										?>
										<span class="<?php echo esc_attr( $status_classes ); ?>">
											<?php echo esc_html( $status_name ); ?>
										</span>
									</td>
									<td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-order-total py-4 px-4 text-dark-900 dark:text-white" data-title="<?php esc_attr_e( 'Total', 'aqualuxe' ); ?>">
										<?php
										/* translators: 1: formatted order total 2: total order items */
										printf( _n( '%1$s for %2$s item', '%1$s for %2$s items', $item_count, 'aqualuxe' ), $order->get_formatted_order_total(), $item_count );
										?>
									</td>
									<td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-order-actions py-4 px-4 text-right" data-title="<?php esc_attr_e( 'Actions', 'aqualuxe' ); ?>">
										<?php
										$actions = wc_get_account_orders_actions( $order );
										
										if ( ! empty( $actions ) ) {
											foreach ( $actions as $key => $action ) {
												$action_classes = 'woocommerce-button btn btn-sm ' . ( $key === 'view' ? 'btn-primary' : 'btn-outline' );
												echo '<a href="' . esc_url( $action['url'] ) . '" class="' . esc_attr( $action_classes ) . '">' . esc_html( $action['name'] ) . '</a>';
											}
										}
										?>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
				
				<div class="text-center mt-6">
					<a href="<?php echo esc_url( wc_get_endpoint_url( 'orders' ) ); ?>" class="btn btn-outline">
						<?php esc_html_e( 'View All Orders', 'aqualuxe' ); ?>
					</a>
				</div>
			</div>
		<?php endif; ?>
	<?php endif; ?>
</div>