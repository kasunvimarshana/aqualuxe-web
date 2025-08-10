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
	),
);
?>

<p>
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

<div class="dashboard-widgets grid gap-6 mt-8">
	<div class="dashboard-widget bg-white p-6 rounded-lg shadow-sm">
		<h3 class="text-lg font-medium mb-3 pb-2 border-b border-gray-200"><?php esc_html_e( 'Recent Orders', 'aqualuxe' ); ?></h3>
		<div class="dashboard-widget-content">
			<?php
			$customer_orders = wc_get_orders( array(
				'customer' => get_current_user_id(),
				'limit'    => 5,
				'status'   => array_map( 'wc_get_order_status_name', wc_get_is_paid_statuses() ),
			) );

			if ( $customer_orders ) : ?>
				<table class="w-full">
					<thead>
						<tr>
							<th class="text-left pb-2"><?php esc_html_e( 'Order', 'aqualuxe' ); ?></th>
							<th class="text-left pb-2"><?php esc_html_e( 'Date', 'aqualuxe' ); ?></th>
							<th class="text-left pb-2"><?php esc_html_e( 'Status', 'aqualuxe' ); ?></th>
							<th class="text-right pb-2"><?php esc_html_e( 'Total', 'aqualuxe' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php
						foreach ( $customer_orders as $customer_order ) :
							$order = wc_get_order( $customer_order );
							$item_count = $order->get_item_count();
							?>
							<tr>
								<td class="py-2">
									<a href="<?php echo esc_url( $order->get_view_order_url() ); ?>" class="text-primary hover:text-primary-dark">
										<?php echo esc_html( _x( '#', 'hash before order number', 'aqualuxe' ) . $order->get_order_number() ); ?>
									</a>
								</td>
								<td class="py-2">
									<?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?>
								</td>
								<td class="py-2">
									<?php echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?>
								</td>
								<td class="py-2 text-right">
									<?php echo wp_kses_post( $order->get_formatted_order_total() ); ?>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
				<div class="mt-4 text-right">
					<a href="<?php echo esc_url( wc_get_endpoint_url( 'orders' ) ); ?>" class="text-primary hover:text-primary-dark text-sm font-medium">
						<?php esc_html_e( 'View all orders', 'aqualuxe' ); ?> →
					</a>
				</div>
			<?php else : ?>
				<p><?php esc_html_e( 'No order has been made yet.', 'aqualuxe' ); ?></p>
				<a href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>" class="button mt-4 inline-block py-2 px-4 bg-primary hover:bg-primary-dark text-white font-medium rounded-md transition-colors duration-200">
					<?php esc_html_e( 'Browse products', 'aqualuxe' ); ?>
				</a>
			<?php endif; ?>
		</div>
	</div>
	
	<div class="dashboard-widget bg-white p-6 rounded-lg shadow-sm">
		<h3 class="text-lg font-medium mb-3 pb-2 border-b border-gray-200"><?php esc_html_e( 'Account Details', 'aqualuxe' ); ?></h3>
		<div class="dashboard-widget-content">
			<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
				<div>
					<h4 class="font-medium mb-1"><?php esc_html_e( 'Contact Information', 'aqualuxe' ); ?></h4>
					<p class="text-sm text-gray-600 mb-2"><?php echo esc_html( $current_user->user_email ); ?></p>
					<a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-account' ) ); ?>" class="text-primary hover:text-primary-dark text-sm">
						<?php esc_html_e( 'Edit', 'aqualuxe' ); ?>
					</a>
				</div>
				
				<div>
					<h4 class="font-medium mb-1"><?php esc_html_e( 'Shipping Address', 'aqualuxe' ); ?></h4>
					<?php
					$customer_id = get_current_user_id();
					$address = wc_get_account_formatted_address( 'shipping' );
					
					if ( $address ) {
						echo '<p class="text-sm text-gray-600 mb-2">' . wp_kses_post( $address ) . '</p>';
					} else {
						echo '<p class="text-sm text-gray-600 mb-2">' . esc_html__( 'You have not set up a shipping address yet.', 'aqualuxe' ) . '</p>';
					}
					?>
					<a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address', 'shipping' ) ); ?>" class="text-primary hover:text-primary-dark text-sm">
						<?php $address ? esc_html_e( 'Edit', 'aqualuxe' ) : esc_html_e( 'Add', 'aqualuxe' ); ?>
					</a>
				</div>
			</div>
		</div>
	</div>
	
	<?php if ( wc_get_customer_order_count( get_current_user_id() ) > 0 ) : ?>
	<div class="dashboard-widget bg-white p-6 rounded-lg shadow-sm">
		<h3 class="text-lg font-medium mb-3 pb-2 border-b border-gray-200"><?php esc_html_e( 'Recently Viewed Products', 'aqualuxe' ); ?></h3>
		<div class="dashboard-widget-content">
			<?php
			// Get recently viewed products
			$viewed_products = ! empty( $_COOKIE['woocommerce_recently_viewed'] ) ? explode( '|', wp_unslash( $_COOKIE['woocommerce_recently_viewed'] ) ) : array();
			$viewed_products = array_reverse( array_filter( array_map( 'absint', $viewed_products ) ) );
			
			if ( ! empty( $viewed_products ) ) {
				$viewed_products = array_slice( $viewed_products, 0, 4 );
				echo '<div class="grid grid-cols-2 md:grid-cols-4 gap-4">';
				
				foreach ( $viewed_products as $product_id ) {
					$product = wc_get_product( $product_id );
					
					if ( $product && $product->is_visible() ) {
						echo '<div class="product-item">';
						echo '<a href="' . esc_url( $product->get_permalink() ) . '" class="block">';
						echo $product->get_image( 'thumbnail', array( 'class' => 'w-full h-auto rounded-md' ) );
						echo '<h4 class="text-sm font-medium mt-2 mb-1">' . esc_html( $product->get_name() ) . '</h4>';
						echo '<span class="text-sm">' . $product->get_price_html() . '</span>';
						echo '</a>';
						echo '</div>';
					}
				}
				
				echo '</div>';
				echo '<div class="mt-4 text-right">';
				echo '<a href="' . esc_url( wc_get_page_permalink( 'shop' ) ) . '" class="text-primary hover:text-primary-dark text-sm font-medium">';
				esc_html_e( 'Shop more products', 'aqualuxe' );
				echo ' →</a>';
				echo '</div>';
			} else {
				echo '<p>' . esc_html__( 'You have not viewed any products yet.', 'aqualuxe' ) . '</p>';
				echo '<a href="' . esc_url( wc_get_page_permalink( 'shop' ) ) . '" class="button mt-4 inline-block py-2 px-4 bg-primary hover:bg-primary-dark text-white font-medium rounded-md transition-colors duration-200">';
				esc_html_e( 'Browse products', 'aqualuxe' );
				echo '</a>';
			}
			?>
		</div>
	</div>
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