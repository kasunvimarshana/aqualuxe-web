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
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$allowed_html = array(
	'a' => array(
		'href' => array(),
	),
);
?>

<div class="woocommerce-MyAccount-dashboard bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
	<div class="dashboard-welcome mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
		<h2 class="text-2xl font-bold mb-4"><?php esc_html_e( 'Dashboard', 'aqualuxe' ); ?></h2>
		
		<p class="welcome-message">
			<?php
			printf(
				/* translators: 1: user display name 2: logout url */
				wp_kses( __( 'Hello %1$s (not %1$s? <a href="%2$s">Log out</a>)', 'aqualuxe' ), $allowed_html ),
				'<strong>' . esc_html( $current_user->display_name ) . '</strong>',
				esc_url( wc_logout_url() )
			);
			?>
		</p>

		<p>
			<?php
			/* translators: 1: Orders URL 2: Address URL 3: Account URL. */
			$dashboard_desc = __( 'From your account dashboard you can view your <a href="%1$s">recent orders</a>, manage your <a href="%2$s">shipping and billing addresses</a>, and <a href="%3$s">edit your password and account details</a>.', 'aqualuxe' );
			if ( wc_shipping_enabled() ) {
				/* translators: 1: Orders URL 2: Addresses URL 3: Account URL. */
				$dashboard_desc = __( 'From your account dashboard you can view your <a href="%1$s">recent orders</a>, manage your <a href="%2$s">shipping and billing addresses</a>, and <a href="%3$s">edit your password and account details</a>.', 'aqualuxe' );
			}
			printf(
				wp_kses( $dashboard_desc, $allowed_html ),
				esc_url( wc_get_endpoint_url( 'orders' ) ),
				esc_url( wc_get_endpoint_url( 'edit-address' ) ),
				esc_url( wc_get_endpoint_url( 'edit-account' ) )
			);
			?>
		</p>
	</div>

	<div class="dashboard-quick-links grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
		<a href="<?php echo esc_url( wc_get_endpoint_url( 'orders' ) ); ?>" class="dashboard-link bg-gray-50 dark:bg-gray-700 p-4 rounded-md hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors duration-200 flex flex-col items-center text-center">
			<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mb-2 text-primary-600 dark:text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
				<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
			</svg>
			<span class="font-medium"><?php esc_html_e( 'Orders', 'aqualuxe' ); ?></span>
		</a>
		
		<a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address' ) ); ?>" class="dashboard-link bg-gray-50 dark:bg-gray-700 p-4 rounded-md hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors duration-200 flex flex-col items-center text-center">
			<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mb-2 text-primary-600 dark:text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
				<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
				<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
			</svg>
			<span class="font-medium"><?php esc_html_e( 'Addresses', 'aqualuxe' ); ?></span>
		</a>
		
		<a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-account' ) ); ?>" class="dashboard-link bg-gray-50 dark:bg-gray-700 p-4 rounded-md hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors duration-200 flex flex-col items-center text-center">
			<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mb-2 text-primary-600 dark:text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
				<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
			</svg>
			<span class="font-medium"><?php esc_html_e( 'Account Details', 'aqualuxe' ); ?></span>
		</a>
		
		<?php if ( class_exists( 'WC_Wishlist_Plugin' ) || function_exists( 'YITH_WCWL' ) ) : ?>
		<a href="<?php echo esc_url( wc_get_endpoint_url( 'wishlist' ) ); ?>" class="dashboard-link bg-gray-50 dark:bg-gray-700 p-4 rounded-md hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors duration-200 flex flex-col items-center text-center">
			<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mb-2 text-primary-600 dark:text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
				<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
			</svg>
			<span class="font-medium"><?php esc_html_e( 'Wishlist', 'aqualuxe' ); ?></span>
		</a>
		<?php else : ?>
		<a href="<?php echo esc_url( wc_get_endpoint_url( 'downloads' ) ); ?>" class="dashboard-link bg-gray-50 dark:bg-gray-700 p-4 rounded-md hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors duration-200 flex flex-col items-center text-center">
			<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mb-2 text-primary-600 dark:text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
				<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
			</svg>
			<span class="font-medium"><?php esc_html_e( 'Downloads', 'aqualuxe' ); ?></span>
		</a>
		<?php endif; ?>
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
	
	<?php
	// Get recent orders
	$customer_orders = wc_get_orders( array(
		'customer' => get_current_user_id(),
		'limit'    => 5,
		'status'   => array( 'wc-processing', 'wc-completed' ),
	) );
	
	if ( $customer_orders ) : ?>
		<div class="recent-orders mb-6">
			<h3 class="text-xl font-semibold mb-4"><?php esc_html_e( 'Recent Orders', 'aqualuxe' ); ?></h3>
			<div class="overflow-x-auto">
				<table class="woocommerce-orders-table woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table w-full">
					<thead>
						<tr>
							<th class="woocommerce-orders-table__header woocommerce-orders-table__header-order-number text-left py-2 px-4 bg-gray-50 dark:bg-gray-700"><span class="nobr"><?php esc_html_e( 'Order', 'aqualuxe' ); ?></span></th>
							<th class="woocommerce-orders-table__header woocommerce-orders-table__header-order-date text-left py-2 px-4 bg-gray-50 dark:bg-gray-700"><span class="nobr"><?php esc_html_e( 'Date', 'aqualuxe' ); ?></span></th>
							<th class="woocommerce-orders-table__header woocommerce-orders-table__header-order-status text-left py-2 px-4 bg-gray-50 dark:bg-gray-700"><span class="nobr"><?php esc_html_e( 'Status', 'aqualuxe' ); ?></span></th>
							<th class="woocommerce-orders-table__header woocommerce-orders-table__header-order-total text-left py-2 px-4 bg-gray-50 dark:bg-gray-700"><span class="nobr"><?php esc_html_e( 'Total', 'aqualuxe' ); ?></span></th>
							<th class="woocommerce-orders-table__header woocommerce-orders-table__header-order-actions text-right py-2 px-4 bg-gray-50 dark:bg-gray-700"><span class="nobr"><?php esc_html_e( 'Actions', 'aqualuxe' ); ?></span></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ( $customer_orders as $customer_order ) :
							$order      = wc_get_order( $customer_order );
							$item_count = $order->get_item_count();
							?>
							<tr class="woocommerce-orders-table__row woocommerce-orders-table__row--status-<?php echo esc_attr( $order->get_status() ); ?> order border-b border-gray-200 dark:border-gray-700">
								<td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-order-number py-3 px-4" data-title="<?php esc_attr_e( 'Order', 'aqualuxe' ); ?>">
									<a href="<?php echo esc_url( $order->get_view_order_url() ); ?>">
										<?php echo esc_html( _x( '#', 'hash before order number', 'aqualuxe' ) . $order->get_order_number() ); ?>
									</a>
								</td>
								<td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-order-date py-3 px-4" data-title="<?php esc_attr_e( 'Date', 'aqualuxe' ); ?>">
									<time datetime="<?php echo esc_attr( $order->get_date_created()->date( 'c' ) ); ?>"><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></time>
								</td>
								<td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-order-status py-3 px-4" data-title="<?php esc_attr_e( 'Status', 'aqualuxe' ); ?>">
									<?php echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?>
								</td>
								<td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-order-total py-3 px-4" data-title="<?php esc_attr_e( 'Total', 'aqualuxe' ); ?>">
									<?php
									/* translators: 1: formatted order total 2: total order items */
									printf( _n( '%1$s for %2$s item', '%1$s for %2$s items', $item_count, 'aqualuxe' ), $order->get_formatted_order_total(), $item_count );
									?>
								</td>
								<td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-order-actions py-3 px-4 text-right" data-title="<?php esc_attr_e( 'Actions', 'aqualuxe' ); ?>">
									<?php
									$actions = wc_get_account_orders_actions( $order );
									
									if ( ! empty( $actions ) ) {
										foreach ( $actions as $key => $action ) {
											echo '<a href="' . esc_url( $action['url'] ) . '" class="woocommerce-button button ' . sanitize_html_class( $key ) . ' inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 ml-2">' . esc_html( $action['name'] ) . '</a>';
										}
									}
									?>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
			<div class="text-right mt-4">
				<a href="<?php echo esc_url( wc_get_endpoint_url( 'orders' ) ); ?>" class="inline-flex items-center text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-300">
					<?php esc_html_e( 'View all orders', 'aqualuxe' ); ?>
					<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
					</svg>
				</a>
			</div>
		</div>
	<?php endif; ?>
</div>