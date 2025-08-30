<?php
/**
 * Event Registration Confirmation Template
 *
 * @package AquaLuxe\Modules\Events
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get registration and module from template args
$registration = $args['registration'] ?? null;
$module = $args['module'] ?? null;

if ( ! $registration || ! $module ) {
	return;
}

// Get event and ticket
$event = $registration->get_event();
$ticket = $registration->get_ticket();

// Get attendee data
$attendee = $registration->get_attendee_data();

// Get payment data
$payment = $registration->get_payment_data();
?>

<div class="aqualuxe-registration-confirmation">
	<div class="aqualuxe-registration-confirmation__header">
		<div class="aqualuxe-registration-confirmation__icon">
			<?php if ( $registration->is_confirmed() || $registration->is_completed() ) : ?>
				<span class="dashicons dashicons-yes-alt"></span>
			<?php elseif ( $registration->is_cancelled() || $registration->is_refunded() ) : ?>
				<span class="dashicons dashicons-dismiss"></span>
			<?php else : ?>
				<span class="dashicons dashicons-clock"></span>
			<?php endif; ?>
		</div>
		
		<h2 class="aqualuxe-registration-confirmation__title">
			<?php if ( $registration->is_confirmed() || $registration->is_completed() ) : ?>
				<?php esc_html_e( 'Registration Confirmed', 'aqualuxe' ); ?>
			<?php elseif ( $registration->is_cancelled() ) : ?>
				<?php esc_html_e( 'Registration Cancelled', 'aqualuxe' ); ?>
			<?php elseif ( $registration->is_refunded() ) : ?>
				<?php esc_html_e( 'Registration Refunded', 'aqualuxe' ); ?>
			<?php else : ?>
				<?php esc_html_e( 'Registration Pending', 'aqualuxe' ); ?>
			<?php endif; ?>
		</h2>
		
		<div class="aqualuxe-registration-confirmation__id">
			<?php printf( esc_html__( 'Registration #%s', 'aqualuxe' ), $registration->get_id() ); ?>
		</div>
		
		<div class="aqualuxe-registration-confirmation__date">
			<?php printf( esc_html__( 'Registered on %s', 'aqualuxe' ), $registration->get_formatted_date() ); ?>
		</div>
	</div>
	
	<div class="aqualuxe-registration-confirmation__content">
		<div class="aqualuxe-registration-confirmation__section aqualuxe-registration-confirmation__section--event">
			<h3 class="aqualuxe-registration-confirmation__section-title"><?php esc_html_e( 'Event Details', 'aqualuxe' ); ?></h3>
			
			<div class="aqualuxe-registration-confirmation__event">
				<div class="aqualuxe-registration-confirmation__event-name">
					<a href="<?php echo esc_url( $event->get_permalink() ); ?>" class="aqualuxe-registration-confirmation__event-link">
						<?php echo esc_html( $event->get_title() ); ?>
					</a>
				</div>
				
				<div class="aqualuxe-registration-confirmation__event-meta">
					<div class="aqualuxe-registration-confirmation__event-date">
						<span class="aqualuxe-registration-confirmation__event-icon dashicons dashicons-calendar-alt"></span>
						<span class="aqualuxe-registration-confirmation__event-text">
							<?php if ( $event->get_start_date() === $event->get_end_date() ) : ?>
								<?php echo esc_html( $event->get_formatted_start_date() ); ?>
							<?php else : ?>
								<?php echo esc_html( $event->get_formatted_start_date() ); ?> - <?php echo esc_html( $event->get_formatted_end_date() ); ?>
							<?php endif; ?>
						</span>
					</div>
					
					<div class="aqualuxe-registration-confirmation__event-time">
						<span class="aqualuxe-registration-confirmation__event-icon dashicons dashicons-clock"></span>
						<span class="aqualuxe-registration-confirmation__event-text">
							<?php echo esc_html( $event->get_formatted_start_time() ); ?> - <?php echo esc_html( $event->get_formatted_end_time() ); ?>
						</span>
					</div>
					
					<?php $venue = $event->get_venue_data(); ?>
					<?php if ( ! empty( $venue['name'] ) ) : ?>
						<div class="aqualuxe-registration-confirmation__event-venue">
							<span class="aqualuxe-registration-confirmation__event-icon dashicons dashicons-location"></span>
							<span class="aqualuxe-registration-confirmation__event-text">
								<?php echo esc_html( $venue['name'] ); ?>
								<?php if ( ! empty( $venue['address'] ) ) : ?>
									<br><?php echo esc_html( $venue['address'] ); ?>
									<?php if ( ! empty( $venue['city'] ) || ! empty( $venue['state'] ) || ! empty( $venue['zip'] ) ) : ?>
										<br>
										<?php 
										$location_parts = array();
										if ( ! empty( $venue['city'] ) ) {
											$location_parts[] = $venue['city'];
										}
										if ( ! empty( $venue['state'] ) ) {
											$location_parts[] = $venue['state'];
										}
										if ( ! empty( $venue['zip'] ) ) {
											$location_parts[] = $venue['zip'];
										}
										echo esc_html( implode( ', ', $location_parts ) );
										?>
									<?php endif; ?>
									
									<?php if ( ! empty( $venue['country'] ) ) : ?>
										<br><?php echo esc_html( $venue['country'] ); ?>
									<?php endif; ?>
								<?php endif; ?>
							</span>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
		
		<div class="aqualuxe-registration-confirmation__section aqualuxe-registration-confirmation__section--ticket">
			<h3 class="aqualuxe-registration-confirmation__section-title"><?php esc_html_e( 'Ticket Details', 'aqualuxe' ); ?></h3>
			
			<div class="aqualuxe-registration-confirmation__ticket">
				<div class="aqualuxe-registration-confirmation__ticket-header">
					<div class="aqualuxe-registration-confirmation__ticket-name"><?php echo esc_html( $ticket->get_name() ); ?></div>
					<div class="aqualuxe-registration-confirmation__ticket-quantity">
						<?php printf( esc_html( _n( '%s ticket', '%s tickets', $registration->get_quantity(), 'aqualuxe' ) ), number_format_i18n( $registration->get_quantity() ) ); ?>
					</div>
				</div>
				
				<div class="aqualuxe-registration-confirmation__ticket-price">
					<div class="aqualuxe-registration-confirmation__ticket-price-label"><?php esc_html_e( 'Price:', 'aqualuxe' ); ?></div>
					<div class="aqualuxe-registration-confirmation__ticket-price-value"><?php echo esc_html( $ticket->get_formatted_price() ); ?></div>
				</div>
				
				<div class="aqualuxe-registration-confirmation__ticket-total">
					<div class="aqualuxe-registration-confirmation__ticket-total-label"><?php esc_html_e( 'Total:', 'aqualuxe' ); ?></div>
					<div class="aqualuxe-registration-confirmation__ticket-total-value"><?php echo esc_html( $registration->get_formatted_price() ); ?></div>
				</div>
			</div>
		</div>
		
		<div class="aqualuxe-registration-confirmation__section aqualuxe-registration-confirmation__section--attendee">
			<h3 class="aqualuxe-registration-confirmation__section-title"><?php esc_html_e( 'Attendee Information', 'aqualuxe' ); ?></h3>
			
			<div class="aqualuxe-registration-confirmation__attendee">
				<div class="aqualuxe-registration-confirmation__attendee-name">
					<div class="aqualuxe-registration-confirmation__attendee-label"><?php esc_html_e( 'Name:', 'aqualuxe' ); ?></div>
					<div class="aqualuxe-registration-confirmation__attendee-value"><?php echo esc_html( $attendee['name'] ?? '' ); ?></div>
				</div>
				
				<div class="aqualuxe-registration-confirmation__attendee-email">
					<div class="aqualuxe-registration-confirmation__attendee-label"><?php esc_html_e( 'Email:', 'aqualuxe' ); ?></div>
					<div class="aqualuxe-registration-confirmation__attendee-value"><?php echo esc_html( $attendee['email'] ?? '' ); ?></div>
				</div>
				
				<?php if ( ! empty( $attendee['phone'] ) ) : ?>
					<div class="aqualuxe-registration-confirmation__attendee-phone">
						<div class="aqualuxe-registration-confirmation__attendee-label"><?php esc_html_e( 'Phone:', 'aqualuxe' ); ?></div>
						<div class="aqualuxe-registration-confirmation__attendee-value"><?php echo esc_html( $attendee['phone'] ); ?></div>
					</div>
				<?php endif; ?>
				
				<?php if ( ! empty( $attendee['address'] ) ) : ?>
					<div class="aqualuxe-registration-confirmation__attendee-address">
						<div class="aqualuxe-registration-confirmation__attendee-label"><?php esc_html_e( 'Address:', 'aqualuxe' ); ?></div>
						<div class="aqualuxe-registration-confirmation__attendee-value"><?php echo nl2br( esc_html( $attendee['address'] ) ); ?></div>
					</div>
				<?php endif; ?>
				
				<?php if ( ! empty( $attendee['notes'] ) ) : ?>
					<div class="aqualuxe-registration-confirmation__attendee-notes">
						<div class="aqualuxe-registration-confirmation__attendee-label"><?php esc_html_e( 'Notes:', 'aqualuxe' ); ?></div>
						<div class="aqualuxe-registration-confirmation__attendee-value"><?php echo nl2br( esc_html( $attendee['notes'] ) ); ?></div>
					</div>
				<?php endif; ?>
			</div>
		</div>
		
		<?php if ( ! empty( $payment ) ) : ?>
			<div class="aqualuxe-registration-confirmation__section aqualuxe-registration-confirmation__section--payment">
				<h3 class="aqualuxe-registration-confirmation__section-title"><?php esc_html_e( 'Payment Information', 'aqualuxe' ); ?></h3>
				
				<div class="aqualuxe-registration-confirmation__payment">
					<div class="aqualuxe-registration-confirmation__payment-method">
						<div class="aqualuxe-registration-confirmation__payment-label"><?php esc_html_e( 'Payment Method:', 'aqualuxe' ); ?></div>
						<div class="aqualuxe-registration-confirmation__payment-value">
							<?php
							switch ( $payment['method'] ?? '' ) {
								case 'stripe':
									esc_html_e( 'Credit Card', 'aqualuxe' );
									break;
								case 'paypal':
									esc_html_e( 'PayPal', 'aqualuxe' );
									break;
								case 'bank_transfer':
									esc_html_e( 'Bank Transfer', 'aqualuxe' );
									break;
								case 'cash':
									esc_html_e( 'Cash', 'aqualuxe' );
									break;
								default:
									echo esc_html( $payment['method'] ?? '' );
									break;
							}
							?>
						</div>
					</div>
					
					<div class="aqualuxe-registration-confirmation__payment-status">
						<div class="aqualuxe-registration-confirmation__payment-label"><?php esc_html_e( 'Payment Status:', 'aqualuxe' ); ?></div>
						<div class="aqualuxe-registration-confirmation__payment-value aqualuxe-registration-confirmation__payment-value--<?php echo esc_attr( $payment['status'] ?? 'pending' ); ?>">
							<?php
							switch ( $payment['status'] ?? '' ) {
								case 'completed':
									esc_html_e( 'Completed', 'aqualuxe' );
									break;
								case 'pending':
									esc_html_e( 'Pending', 'aqualuxe' );
									break;
								case 'failed':
									esc_html_e( 'Failed', 'aqualuxe' );
									break;
								case 'refunded':
									esc_html_e( 'Refunded', 'aqualuxe' );
									break;
								case 'cancelled':
									esc_html_e( 'Cancelled', 'aqualuxe' );
									break;
								default:
									echo esc_html( $payment['status'] ?? '' );
									break;
							}
							?>
						</div>
					</div>
					
					<?php if ( ! empty( $payment['transaction_id'] ) ) : ?>
						<div class="aqualuxe-registration-confirmation__payment-transaction">
							<div class="aqualuxe-registration-confirmation__payment-label"><?php esc_html_e( 'Transaction ID:', 'aqualuxe' ); ?></div>
							<div class="aqualuxe-registration-confirmation__payment-value"><?php echo esc_html( $payment['transaction_id'] ); ?></div>
						</div>
					<?php endif; ?>
					
					<?php if ( ! empty( $payment['date'] ) ) : ?>
						<div class="aqualuxe-registration-confirmation__payment-date">
							<div class="aqualuxe-registration-confirmation__payment-label"><?php esc_html_e( 'Payment Date:', 'aqualuxe' ); ?></div>
							<div class="aqualuxe-registration-confirmation__payment-value"><?php echo esc_html( date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $payment['date'] ) ) ); ?></div>
						</div>
					<?php endif; ?>
					
					<div class="aqualuxe-registration-confirmation__payment-amount">
						<div class="aqualuxe-registration-confirmation__payment-label"><?php esc_html_e( 'Amount:', 'aqualuxe' ); ?></div>
						<div class="aqualuxe-registration-confirmation__payment-value">
							<?php
							if ( ! empty( $payment['amount'] ) && ! empty( $payment['currency'] ) ) {
								echo esc_html( sprintf(
									get_woocommerce_price_format(),
									get_woocommerce_currency_symbol( $payment['currency'] ),
									number_format( $payment['amount'], wc_get_price_decimals(), wc_get_price_decimal_separator(), wc_get_price_thousand_separator() )
								) );
							} else {
								echo esc_html( $registration->get_formatted_price() );
							}
							?>
						</div>
					</div>
					
					<?php if ( $payment['method'] === 'bank_transfer' ) : ?>
						<div class="aqualuxe-registration-confirmation__payment-instructions">
							<div class="aqualuxe-registration-confirmation__payment-label"><?php esc_html_e( 'Payment Instructions:', 'aqualuxe' ); ?></div>
							<div class="aqualuxe-registration-confirmation__payment-value">
								<?php
								$bank_details = $module->get_setting( 'bank_transfer_details', '' );
								echo wp_kses_post( wpautop( $bank_details ) );
								?>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</div>
		<?php endif; ?>
		
		<div class="aqualuxe-registration-confirmation__section aqualuxe-registration-confirmation__section--status">
			<h3 class="aqualuxe-registration-confirmation__section-title"><?php esc_html_e( 'Registration Status', 'aqualuxe' ); ?></h3>
			
			<div class="aqualuxe-registration-confirmation__status">
				<div class="aqualuxe-registration-confirmation__status-badge aqualuxe-registration-confirmation__status-badge--<?php echo esc_attr( $registration->get_status() ); ?>">
					<?php
					switch ( $registration->get_status() ) {
						case 'pending':
							esc_html_e( 'Pending', 'aqualuxe' );
							break;
						case 'confirmed':
							esc_html_e( 'Confirmed', 'aqualuxe' );
							break;
						case 'cancelled':
							esc_html_e( 'Cancelled', 'aqualuxe' );
							break;
						case 'completed':
							esc_html_e( 'Completed', 'aqualuxe' );
							break;
						case 'refunded':
							esc_html_e( 'Refunded', 'aqualuxe' );
							break;
						case 'failed':
							esc_html_e( 'Failed', 'aqualuxe' );
							break;
						default:
							echo esc_html( $registration->get_status() );
							break;
					}
					?>
				</div>
				
				<?php if ( $registration->get_status() === 'pending' ) : ?>
					<div class="aqualuxe-registration-confirmation__status-message">
						<?php esc_html_e( 'Your registration is pending confirmation. You will receive an email once it is confirmed.', 'aqualuxe' ); ?>
					</div>
				<?php elseif ( $registration->get_status() === 'confirmed' ) : ?>
					<div class="aqualuxe-registration-confirmation__status-message">
						<?php esc_html_e( 'Your registration has been confirmed. We look forward to seeing you at the event!', 'aqualuxe' ); ?>
					</div>
				<?php elseif ( $registration->get_status() === 'cancelled' ) : ?>
					<div class="aqualuxe-registration-confirmation__status-message">
						<?php esc_html_e( 'Your registration has been cancelled.', 'aqualuxe' ); ?>
					</div>
				<?php elseif ( $registration->get_status() === 'completed' ) : ?>
					<div class="aqualuxe-registration-confirmation__status-message">
						<?php esc_html_e( 'Thank you for attending the event!', 'aqualuxe' ); ?>
					</div>
				<?php elseif ( $registration->get_status() === 'refunded' ) : ?>
					<div class="aqualuxe-registration-confirmation__status-message">
						<?php esc_html_e( 'Your registration has been refunded.', 'aqualuxe' ); ?>
					</div>
				<?php elseif ( $registration->get_status() === 'failed' ) : ?>
					<div class="aqualuxe-registration-confirmation__status-message">
						<?php esc_html_e( 'Your registration has failed. Please contact us for assistance.', 'aqualuxe' ); ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
	
	<div class="aqualuxe-registration-confirmation__footer">
		<div class="aqualuxe-registration-confirmation__actions">
			<?php if ( $registration->get_status() === 'confirmed' || $registration->get_status() === 'pending' ) : ?>
				<a href="#" class="aqualuxe-registration-confirmation__action aqualuxe-registration-confirmation__action--cancel" data-registration-id="<?php echo esc_attr( $registration->get_id() ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'aqualuxe-events-nonce' ) ); ?>">
					<?php esc_html_e( 'Cancel Registration', 'aqualuxe' ); ?>
				</a>
			<?php endif; ?>
			
			<a href="<?php echo esc_url( $event->get_permalink() ); ?>" class="aqualuxe-registration-confirmation__action aqualuxe-registration-confirmation__action--event">
				<?php esc_html_e( 'View Event', 'aqualuxe' ); ?>
			</a>
			
			<a href="#" class="aqualuxe-registration-confirmation__action aqualuxe-registration-confirmation__action--print" onclick="window.print(); return false;">
				<?php esc_html_e( 'Print', 'aqualuxe' ); ?>
			</a>
		</div>
		
		<div class="aqualuxe-registration-confirmation__contact">
			<?php esc_html_e( 'If you have any questions, please contact us:', 'aqualuxe' ); ?>
			<a href="mailto:<?php echo esc_attr( $module->get_setting( 'admin_email', get_option( 'admin_email' ) ) ); ?>" class="aqualuxe-registration-confirmation__contact-email">
				<?php echo esc_html( $module->get_setting( 'admin_email', get_option( 'admin_email' ) ) ); ?>
			</a>
		</div>
	</div>
</div>