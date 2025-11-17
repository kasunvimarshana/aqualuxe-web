<?php
/**
 * View Order
 *
 * Shows the details of a particular order on the account page.
 *
 * This template overrides /woocommerce/templates/myaccount/view-order.php
 *
 * @package AquaLuxe
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

$notes = $order->get_customer_order_notes();
?>

<div class="woocommerce-order-details-wrapper">
	<div class="order-details-heading">
		<h2><?php esc_html_e( 'Order details', 'woocommerce' ); ?></h2>
		<span class="order-status status-<?php echo esc_attr( $order->get_status() ); ?>">
			<?php echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?>
		</span>
	</div>

	<div class="order-overview">
		<div class="order-info-item">
			<h4><?php esc_html_e( 'Order number', 'woocommerce' ); ?></h4>
			<p><?php echo esc_html( $order->get_order_number() ); ?></p>
		</div>
		<div class="order-info-item">
			<h4><?php esc_html_e( 'Date', 'woocommerce' ); ?></h4>
			<p><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></p>
		</div>
		<div class="order-info-item">
			<h4><?php esc_html_e( 'Total', 'woocommerce' ); ?></h4>
			<p><?php echo wp_kses_post( $order->get_formatted_order_total() ); ?></p>
		</div>
		<div class="order-info-item">
			<h4><?php esc_html_e( 'Payment method', 'woocommerce' ); ?></h4>
			<p><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></p>
		</div>
	</div>

	<?php if ( $order->get_status() === 'processing' || $order->get_status() === 'on-hold' || $order->get_status() === 'completed' ) : ?>
	<div class="order-tracking">
		<div class="tracking-header">
			<h3><?php esc_html_e( 'Order tracking', 'aqualuxe' ); ?></h3>
		</div>
		
		<div class="tracking-timeline">
			<div class="tracking-step completed">
				<div class="step-content">
					<div class="step-header">
						<h4><?php esc_html_e( 'Order placed', 'aqualuxe' ); ?></h4>
						<span class="step-date"><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></span>
					</div>
					<p class="step-description"><?php esc_html_e( 'Your order has been received and is being processed.', 'aqualuxe' ); ?></p>
				</div>
			</div>
			
			<?php if ( $order->get_status() === 'processing' || $order->get_status() === 'completed' ) : ?>
			<div class="tracking-step <?php echo $order->get_status() === 'processing' ? 'current' : 'completed'; ?>">
				<div class="step-content">
					<div class="step-header">
						<h4><?php esc_html_e( 'Processing', 'aqualuxe' ); ?></h4>
						<span class="step-date">
							<?php 
							$processing_date = $order->get_date_modified();
							echo esc_html( wc_format_datetime( $processing_date ) ); 
							?>
						</span>
					</div>
					<p class="step-description"><?php esc_html_e( 'Your order is being prepared for shipping.', 'aqualuxe' ); ?></p>
				</div>
			</div>
			<?php endif; ?>
			
			<?php 
			// Check for shipping information
			$tracking_number = get_post_meta( $order->get_id(), '_tracking_number', true );
			$tracking_provider = get_post_meta( $order->get_id(), '_tracking_provider', true );
			$shipped_date = get_post_meta( $order->get_id(), '_shipped_date', true );
			
			if ( $tracking_number && $tracking_provider && $shipped_date ) : 
			?>
			<div class="tracking-step <?php echo $order->get_status() === 'completed' ? 'completed' : 'current'; ?>">
				<div class="step-content">
					<div class="step-header">
						<h4><?php esc_html_e( 'Shipped', 'aqualuxe' ); ?></h4>
						<span class="step-date"><?php echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $shipped_date ) ) ); ?></span>
					</div>
					<p class="step-description">
						<?php esc_html_e( 'Your order has been shipped.', 'aqualuxe' ); ?>
						<?php if ( $tracking_number && $tracking_provider ) : ?>
							<br>
							<?php esc_html_e( 'Tracking number:', 'aqualuxe' ); ?> 
							<a href="<?php echo esc_url( aqualuxe_get_tracking_url( $tracking_provider, $tracking_number ) ); ?>" target="_blank">
								<?php echo esc_html( $tracking_number ); ?>
							</a>
							(<?php echo esc_html( $tracking_provider ); ?>)
						<?php endif; ?>
					</p>
				</div>
			</div>
			<?php endif; ?>
			
			<?php if ( $order->get_status() === 'completed' ) : ?>
			<div class="tracking-step completed">
				<div class="step-content">
					<div class="step-header">
						<h4><?php esc_html_e( 'Delivered', 'aqualuxe' ); ?></h4>
						<span class="step-date">
							<?php 
							$completed_date = $order->get_date_completed();
							echo esc_html( wc_format_datetime( $completed_date ) ); 
							?>
						</span>
					</div>
					<p class="step-description"><?php esc_html_e( 'Your order has been delivered. Thank you for shopping with us!', 'aqualuxe' ); ?></p>
				</div>
			</div>
			<?php endif; ?>
		</div>
	</div>
	<?php endif; ?>

	<?php do_action( 'woocommerce_view_order', $order_id ); ?>
</div>

<?php if ( $notes ) : ?>
	<h2><?php esc_html_e( 'Order updates', 'woocommerce' ); ?></h2>
	<ol class="woocommerce-OrderUpdates commentlist notes">
		<?php foreach ( $notes as $note ) : ?>
		<li class="woocommerce-OrderUpdate comment note">
			<div class="woocommerce-OrderUpdate-inner comment_container">
				<div class="woocommerce-OrderUpdate-text comment-text">
					<p class="woocommerce-OrderUpdate-meta meta"><?php echo date_i18n( esc_html__( 'l jS \o\f F Y, h:ia', 'woocommerce' ), strtotime( $note->comment_date ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
					<div class="woocommerce-OrderUpdate-description description">
						<?php echo wpautop( wptexturize( $note->comment_content ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
					<div class="clear"></div>
				</div>
				<div class="clear"></div>
			</div>
		</li>
		<?php endforeach; ?>
	</ol>
<?php endif; ?>