<?php
/**
 * Event Details Template
 *
 * @package AquaLuxe\Modules\Events
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get event and module from template args
$event = $args['event'] ?? null;
$module = $args['module'] ?? null;

if ( ! $event || ! $module ) {
	return;
}
?>

<div class="aqualuxe-event">
	<div class="aqualuxe-event__header">
		<?php if ( $event->get_image_url() ) : ?>
			<div class="aqualuxe-event__image">
				<img src="<?php echo esc_url( $event->get_image_url( 'large' ) ); ?>" alt="<?php echo esc_attr( $event->get_title() ); ?>">
			</div>
		<?php endif; ?>

		<div class="aqualuxe-event__meta">
			<div class="aqualuxe-event__date">
				<?php if ( $event->get_start_date() === $event->get_end_date() ) : ?>
					<span class="aqualuxe-event__date-icon">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M17 3h4a1 1 0 0 1 1 1v16a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h4V1h2v2h6V1h2v2zm-2 2H9v2H7V5H4v4h16V5h-3v2h-2V5zm5 6H4v8h16v-8z"/></svg>
					</span>
					<span class="aqualuxe-event__date-text">
						<?php echo esc_html( $event->get_formatted_start_date() ); ?>
					</span>
				<?php else : ?>
					<span class="aqualuxe-event__date-icon">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M17 3h4a1 1 0 0 1 1 1v16a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h4V1h2v2h6V1h2v2zm-2 2H9v2H7V5H4v4h16V5h-3v2h-2V5zm5 6H4v8h16v-8z"/></svg>
					</span>
					<span class="aqualuxe-event__date-text">
						<?php echo esc_html( $event->get_formatted_start_date() ); ?> - <?php echo esc_html( $event->get_formatted_end_date() ); ?>
					</span>
				<?php endif; ?>
			</div>

			<div class="aqualuxe-event__time">
				<span class="aqualuxe-event__time-icon">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16zm1-8h4v2h-6V7h2v5z"/></svg>
				</span>
				<span class="aqualuxe-event__time-text">
					<?php echo esc_html( $event->get_formatted_start_time() ); ?> - <?php echo esc_html( $event->get_formatted_end_time() ); ?>
				</span>
			</div>

			<?php if ( $event->get_cost() > 0 ) : ?>
				<div class="aqualuxe-event__cost">
					<span class="aqualuxe-event__cost-icon">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16zm-3.5-6H14a.5.5 0 1 0 0-1h-4a2.5 2.5 0 1 1 0-5h1V6h2v2h2.5v2H10a.5.5 0 1 0 0 1h4a2.5 2.5 0 1 1 0 5h-1v2h-2v-2H8.5v-2z"/></svg>
					</span>
					<span class="aqualuxe-event__cost-text">
						<?php echo esc_html( $event->get_formatted_cost() ); ?>
						<?php if ( $event->get_cost_description() ) : ?>
							<span class="aqualuxe-event__cost-description"><?php echo esc_html( $event->get_cost_description() ); ?></span>
						<?php endif; ?>
					</span>
				</div>
			<?php endif; ?>

			<?php if ( $event->get_capacity() > 0 ) : ?>
				<div class="aqualuxe-event__capacity">
					<span class="aqualuxe-event__capacity-icon">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M2 22a8 8 0 1 1 16 0h-2a6 6 0 1 0-12 0H2zm8-9c-3.315 0-6-2.685-6-6s2.685-6 6-6 6 2.685 6 6-2.685 6-6 6zm0-2c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm8.284 3.703A8.002 8.002 0 0 1 23 22h-2a6.001 6.001 0 0 0-3.537-5.473l.82-1.824zm-.688-11.29A5.5 5.5 0 0 1 21 8.5a5.499 5.499 0 0 1-5 5.478v-2.013a3.5 3.5 0 0 0 1.041-6.609l.555-1.943z"/></svg>
					</span>
					<span class="aqualuxe-event__capacity-text">
						<?php 
						$available = $event->get_available_capacity();
						if ( $available === -1 ) {
							esc_html_e( 'Unlimited capacity', 'aqualuxe' );
						} else {
							printf( 
								esc_html__( '%1$d/%2$d spots available', 'aqualuxe' ),
								$available,
								$event->get_capacity()
							);
						}
						?>
					</span>
				</div>
			<?php endif; ?>

			<?php if ( $event->is_registration_open() ) : ?>
				<div class="aqualuxe-event__registration-status aqualuxe-event__registration-status--open">
					<span class="aqualuxe-event__registration-status-icon">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16zm-.997-4L6.76 11.757l1.414-1.414 2.829 2.829 5.656-5.657 1.415 1.414L11.003 16z"/></svg>
					</span>
					<span class="aqualuxe-event__registration-status-text">
						<?php esc_html_e( 'Registration is open', 'aqualuxe' ); ?>
					</span>
				</div>
			<?php else : ?>
				<div class="aqualuxe-event__registration-status aqualuxe-event__registration-status--closed">
					<span class="aqualuxe-event__registration-status-icon">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16zm0-9.414l2.828-2.829 1.415 1.415L13.414 12l2.829 2.828-1.415 1.415L12 13.414l-2.828 2.829-1.415-1.415L10.586 12 7.757 9.172l1.415-1.415L12 10.586z"/></svg>
					</span>
					<span class="aqualuxe-event__registration-status-text">
						<?php esc_html_e( 'Registration is closed', 'aqualuxe' ); ?>
					</span>
				</div>
			<?php endif; ?>
		</div>
	</div>

	<div class="aqualuxe-event__content">
		<?php if ( $module->get_setting( 'show_venue', true ) && ! empty( $event->get_venue_data() ) ) : ?>
			<div class="aqualuxe-event__venue">
				<h3 class="aqualuxe-event__venue-title"><?php esc_html_e( 'Venue', 'aqualuxe' ); ?></h3>
				
				<?php $venue = $event->get_venue_data(); ?>
				
				<div class="aqualuxe-event__venue-details">
					<?php if ( ! empty( $venue['name'] ) ) : ?>
						<div class="aqualuxe-event__venue-name"><?php echo esc_html( $venue['name'] ); ?></div>
					<?php endif; ?>
					
					<?php if ( ! empty( $venue['address'] ) ) : ?>
						<div class="aqualuxe-event__venue-address">
							<?php echo esc_html( $venue['address'] ); ?>
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
						</div>
					<?php endif; ?>
					
					<?php if ( ! empty( $venue['phone'] ) ) : ?>
						<div class="aqualuxe-event__venue-phone">
							<span class="aqualuxe-event__venue-phone-label"><?php esc_html_e( 'Phone:', 'aqualuxe' ); ?></span>
							<span class="aqualuxe-event__venue-phone-value"><?php echo esc_html( $venue['phone'] ); ?></span>
						</div>
					<?php endif; ?>
					
					<?php if ( ! empty( $venue['website'] ) ) : ?>
						<div class="aqualuxe-event__venue-website">
							<span class="aqualuxe-event__venue-website-label"><?php esc_html_e( 'Website:', 'aqualuxe' ); ?></span>
							<a href="<?php echo esc_url( $venue['website'] ); ?>" target="_blank" rel="noopener noreferrer" class="aqualuxe-event__venue-website-link"><?php echo esc_html( $venue['website'] ); ?></a>
						</div>
					<?php endif; ?>
				</div>
				
				<?php if ( $module->get_setting( 'show_map', true ) && ! empty( $venue['latitude'] ) && ! empty( $venue['longitude'] ) ) : ?>
					<div class="aqualuxe-event__venue-map" data-lat="<?php echo esc_attr( $venue['latitude'] ); ?>" data-lng="<?php echo esc_attr( $venue['longitude'] ); ?>" data-title="<?php echo esc_attr( $venue['name'] ); ?>"></div>
				<?php endif; ?>
			</div>
		<?php endif; ?>
		
		<?php if ( $module->get_setting( 'show_organizer', true ) && ! empty( $event->get_organizer_data() ) ) : ?>
			<div class="aqualuxe-event__organizer">
				<h3 class="aqualuxe-event__organizer-title"><?php esc_html_e( 'Organizer', 'aqualuxe' ); ?></h3>
				
				<?php $organizer = $event->get_organizer_data(); ?>
				
				<div class="aqualuxe-event__organizer-details">
					<?php if ( ! empty( $organizer['name'] ) ) : ?>
						<div class="aqualuxe-event__organizer-name"><?php echo esc_html( $organizer['name'] ); ?></div>
					<?php endif; ?>
					
					<?php if ( ! empty( $organizer['description'] ) ) : ?>
						<div class="aqualuxe-event__organizer-description"><?php echo wp_kses_post( $organizer['description'] ); ?></div>
					<?php endif; ?>
					
					<?php if ( ! empty( $organizer['phone'] ) ) : ?>
						<div class="aqualuxe-event__organizer-phone">
							<span class="aqualuxe-event__organizer-phone-label"><?php esc_html_e( 'Phone:', 'aqualuxe' ); ?></span>
							<span class="aqualuxe-event__organizer-phone-value"><?php echo esc_html( $organizer['phone'] ); ?></span>
						</div>
					<?php endif; ?>
					
					<?php if ( ! empty( $organizer['email'] ) ) : ?>
						<div class="aqualuxe-event__organizer-email">
							<span class="aqualuxe-event__organizer-email-label"><?php esc_html_e( 'Email:', 'aqualuxe' ); ?></span>
							<a href="mailto:<?php echo esc_attr( $organizer['email'] ); ?>" class="aqualuxe-event__organizer-email-link"><?php echo esc_html( $organizer['email'] ); ?></a>
						</div>
					<?php endif; ?>
					
					<?php if ( ! empty( $organizer['website'] ) ) : ?>
						<div class="aqualuxe-event__organizer-website">
							<span class="aqualuxe-event__organizer-website-label"><?php esc_html_e( 'Website:', 'aqualuxe' ); ?></span>
							<a href="<?php echo esc_url( $organizer['website'] ); ?>" target="_blank" rel="noopener noreferrer" class="aqualuxe-event__organizer-website-link"><?php echo esc_html( $organizer['website'] ); ?></a>
						</div>
					<?php endif; ?>
				</div>
			</div>
		<?php endif; ?>
		
		<div class="aqualuxe-event__description">
			<h3 class="aqualuxe-event__description-title"><?php esc_html_e( 'Description', 'aqualuxe' ); ?></h3>
			<div class="aqualuxe-event__description-content">
				<?php echo wp_kses_post( wpautop( $event->get_description() ) ); ?>
			</div>
		</div>
		
		<?php $tickets = $event->get_tickets(); ?>
		
		<?php if ( ! empty( $tickets ) && $event->is_registration_open() ) : ?>
			<div class="aqualuxe-event__tickets">
				<h3 class="aqualuxe-event__tickets-title"><?php esc_html_e( 'Tickets', 'aqualuxe' ); ?></h3>
				
				<div class="aqualuxe-event__tickets-list">
					<?php foreach ( $tickets as $ticket ) : ?>
						<?php if ( $ticket->is_available() ) : ?>
							<div class="aqualuxe-event__ticket">
								<div class="aqualuxe-event__ticket-header">
									<h4 class="aqualuxe-event__ticket-name"><?php echo esc_html( $ticket->get_name() ); ?></h4>
									<div class="aqualuxe-event__ticket-price"><?php echo esc_html( $ticket->get_formatted_price() ); ?></div>
								</div>
								
								<?php if ( $ticket->get_description() ) : ?>
									<div class="aqualuxe-event__ticket-description">
										<?php echo wp_kses_post( wpautop( $ticket->get_description() ) ); ?>
									</div>
								<?php endif; ?>
								
								<div class="aqualuxe-event__ticket-availability">
									<?php 
									$available = $ticket->get_available_count();
									if ( $available === -1 ) {
										esc_html_e( 'Unlimited tickets available', 'aqualuxe' );
									} else {
										printf( 
											esc_html__( '%d tickets available', 'aqualuxe' ),
											$available
										);
									}
									?>
								</div>
								
								<div class="aqualuxe-event__ticket-actions">
									<button class="aqualuxe-event__ticket-select-button" data-ticket-id="<?php echo esc_attr( $ticket->get_id() ); ?>" data-ticket-name="<?php echo esc_attr( $ticket->get_name() ); ?>" data-ticket-price="<?php echo esc_attr( $ticket->get_price() ); ?>">
										<?php esc_html_e( 'Select', 'aqualuxe' ); ?>
									</button>
								</div>
							</div>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>
				
				<div class="aqualuxe-event__registration-form" style="display: none;">
					<h3 class="aqualuxe-event__registration-form-title"><?php esc_html_e( 'Registration', 'aqualuxe' ); ?></h3>
					
					<form id="aqualuxe-event-registration-form" class="aqualuxe-event__registration-form-content">
						<input type="hidden" name="event_id" value="<?php echo esc_attr( $event->get_id() ); ?>">
						<input type="hidden" name="ticket_id" id="aqualuxe-event-ticket-id" value="">
						<input type="hidden" name="nonce" value="<?php echo esc_attr( wp_create_nonce( 'aqualuxe-events-nonce' ) ); ?>">
						
						<div class="aqualuxe-event__registration-form-ticket-info">
							<div class="aqualuxe-event__registration-form-ticket-name"></div>
							<div class="aqualuxe-event__registration-form-ticket-price"></div>
						</div>
						
						<div class="aqualuxe-event__registration-form-field">
							<label for="aqualuxe-event-quantity" class="aqualuxe-event__registration-form-label"><?php esc_html_e( 'Quantity', 'aqualuxe' ); ?></label>
							<select name="quantity" id="aqualuxe-event-quantity" class="aqualuxe-event__registration-form-select" required>
								<?php for ( $i = 1; $i <= 10; $i++ ) : ?>
									<option value="<?php echo esc_attr( $i ); ?>"><?php echo esc_html( $i ); ?></option>
								<?php endfor; ?>
							</select>
						</div>
						
						<div class="aqualuxe-event__registration-form-field">
							<label for="aqualuxe-event-attendee-name" class="aqualuxe-event__registration-form-label"><?php esc_html_e( 'Name', 'aqualuxe' ); ?></label>
							<input type="text" name="attendee_name" id="aqualuxe-event-attendee-name" class="aqualuxe-event__registration-form-input" required>
						</div>
						
						<div class="aqualuxe-event__registration-form-field">
							<label for="aqualuxe-event-attendee-email" class="aqualuxe-event__registration-form-label"><?php esc_html_e( 'Email', 'aqualuxe' ); ?></label>
							<input type="email" name="attendee_email" id="aqualuxe-event-attendee-email" class="aqualuxe-event__registration-form-input" required>
						</div>
						
						<div class="aqualuxe-event__registration-form-field">
							<label for="aqualuxe-event-attendee-phone" class="aqualuxe-event__registration-form-label"><?php esc_html_e( 'Phone', 'aqualuxe' ); ?></label>
							<input type="tel" name="attendee_phone" id="aqualuxe-event-attendee-phone" class="aqualuxe-event__registration-form-input">
						</div>
						
						<div class="aqualuxe-event__registration-form-field">
							<label for="aqualuxe-event-attendee-address" class="aqualuxe-event__registration-form-label"><?php esc_html_e( 'Address', 'aqualuxe' ); ?></label>
							<textarea name="attendee_address" id="aqualuxe-event-attendee-address" class="aqualuxe-event__registration-form-textarea"></textarea>
						</div>
						
						<div class="aqualuxe-event__registration-form-field">
							<label for="aqualuxe-event-attendee-notes" class="aqualuxe-event__registration-form-label"><?php esc_html_e( 'Notes', 'aqualuxe' ); ?></label>
							<textarea name="attendee_notes" id="aqualuxe-event-attendee-notes" class="aqualuxe-event__registration-form-textarea"></textarea>
						</div>
						
						<?php if ( $module->get_setting( 'enable_payments', true ) && $event->get_cost() > 0 ) : ?>
							<div class="aqualuxe-event__registration-form-payment">
								<h4 class="aqualuxe-event__registration-form-payment-title"><?php esc_html_e( 'Payment Method', 'aqualuxe' ); ?></h4>
								
								<?php 
								$payment = new Payment( new Registration() );
								$payment_methods = $payment->get_methods();
								?>
								
								<?php if ( ! empty( $payment_methods ) ) : ?>
									<div class="aqualuxe-event__registration-form-payment-methods">
										<?php foreach ( $payment_methods as $method ) : ?>
											<div class="aqualuxe-event__registration-form-payment-method">
												<input type="radio" name="payment_method" id="aqualuxe-event-payment-method-<?php echo esc_attr( $method['id'] ); ?>" value="<?php echo esc_attr( $method['id'] ); ?>" class="aqualuxe-event__registration-form-payment-method-input" required <?php checked( $method['id'], 'stripe' ); ?>>
												<label for="aqualuxe-event-payment-method-<?php echo esc_attr( $method['id'] ); ?>" class="aqualuxe-event__registration-form-payment-method-label">
													<span class="aqualuxe-event__registration-form-payment-method-icon dashicons dashicons-<?php echo esc_attr( $method['icon'] ); ?>"></span>
													<span class="aqualuxe-event__registration-form-payment-method-name"><?php echo esc_html( $method['name'] ); ?></span>
													<span class="aqualuxe-event__registration-form-payment-method-description"><?php echo esc_html( $method['description'] ); ?></span>
												</label>
											</div>
										<?php endforeach; ?>
									</div>
									
									<div class="aqualuxe-event__registration-form-payment-details">
										<div id="aqualuxe-event-payment-details-stripe" class="aqualuxe-event__registration-form-payment-detail">
											<div class="aqualuxe-event__registration-form-field">
												<label for="aqualuxe-event-card-number" class="aqualuxe-event__registration-form-label"><?php esc_html_e( 'Card Number', 'aqualuxe' ); ?></label>
												<input type="text" id="aqualuxe-event-card-number" class="aqualuxe-event__registration-form-input" placeholder="1234 5678 9012 3456">
											</div>
											
											<div class="aqualuxe-event__registration-form-row">
												<div class="aqualuxe-event__registration-form-field">
													<label for="aqualuxe-event-card-expiry" class="aqualuxe-event__registration-form-label"><?php esc_html_e( 'Expiry Date', 'aqualuxe' ); ?></label>
													<input type="text" id="aqualuxe-event-card-expiry" class="aqualuxe-event__registration-form-input" placeholder="MM/YY">
												</div>
												
												<div class="aqualuxe-event__registration-form-field">
													<label for="aqualuxe-event-card-cvc" class="aqualuxe-event__registration-form-label"><?php esc_html_e( 'CVC', 'aqualuxe' ); ?></label>
													<input type="text" id="aqualuxe-event-card-cvc" class="aqualuxe-event__registration-form-input" placeholder="123">
												</div>
											</div>
											
											<input type="hidden" name="payment_token" id="aqualuxe-event-payment-token" value="">
										</div>
										
										<div id="aqualuxe-event-payment-details-paypal" class="aqualuxe-event__registration-form-payment-detail" style="display: none;">
											<p><?php esc_html_e( 'You will be redirected to PayPal to complete your payment after submitting this form.', 'aqualuxe' ); ?></p>
										</div>
										
										<div id="aqualuxe-event-payment-details-bank_transfer" class="aqualuxe-event__registration-form-payment-detail" style="display: none;">
											<p><?php esc_html_e( 'You will receive bank transfer details after submitting this form.', 'aqualuxe' ); ?></p>
										</div>
										
										<div id="aqualuxe-event-payment-details-cash" class="aqualuxe-event__registration-form-payment-detail" style="display: none;">
											<p><?php esc_html_e( 'Please pay with cash upon arrival.', 'aqualuxe' ); ?></p>
										</div>
									</div>
								<?php endif; ?>
							</div>
						<?php endif; ?>
						
						<div class="aqualuxe-event__registration-form-total">
							<span class="aqualuxe-event__registration-form-total-label"><?php esc_html_e( 'Total:', 'aqualuxe' ); ?></span>
							<span class="aqualuxe-event__registration-form-total-value"></span>
						</div>
						
						<div class="aqualuxe-event__registration-form-actions">
							<button type="button" class="aqualuxe-event__registration-form-back-button">
								<?php esc_html_e( 'Back', 'aqualuxe' ); ?>
							</button>
							<button type="submit" class="aqualuxe-event__registration-form-submit-button">
								<?php esc_html_e( 'Register', 'aqualuxe' ); ?>
							</button>
						</div>
						
						<div class="aqualuxe-event__registration-form-message"></div>
					</form>
				</div>
			</div>
		<?php endif; ?>
		
		<?php if ( $module->get_setting( 'show_sharing', true ) ) : ?>
			<div class="aqualuxe-event__sharing">
				<h3 class="aqualuxe-event__sharing-title"><?php esc_html_e( 'Share This Event', 'aqualuxe' ); ?></h3>
				
				<div class="aqualuxe-event__sharing-buttons">
					<a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo esc_url( $event->get_permalink() ); ?>" target="_blank" rel="noopener noreferrer" class="aqualuxe-event__sharing-button aqualuxe-event__sharing-button--facebook">
						<span class="dashicons dashicons-facebook"></span>
						<span class="aqualuxe-event__sharing-button-text"><?php esc_html_e( 'Facebook', 'aqualuxe' ); ?></span>
					</a>
					
					<a href="https://twitter.com/intent/tweet?url=<?php echo esc_url( $event->get_permalink() ); ?>&text=<?php echo esc_attr( $event->get_title() ); ?>" target="_blank" rel="noopener noreferrer" class="aqualuxe-event__sharing-button aqualuxe-event__sharing-button--twitter">
						<span class="dashicons dashicons-twitter"></span>
						<span class="aqualuxe-event__sharing-button-text"><?php esc_html_e( 'Twitter', 'aqualuxe' ); ?></span>
					</a>
					
					<a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo esc_url( $event->get_permalink() ); ?>&title=<?php echo esc_attr( $event->get_title() ); ?>" target="_blank" rel="noopener noreferrer" class="aqualuxe-event__sharing-button aqualuxe-event__sharing-button--linkedin">
						<span class="dashicons dashicons-linkedin"></span>
						<span class="aqualuxe-event__sharing-button-text"><?php esc_html_e( 'LinkedIn', 'aqualuxe' ); ?></span>
					</a>
					
					<a href="mailto:?subject=<?php echo esc_attr( $event->get_title() ); ?>&body=<?php echo esc_attr( $event->get_permalink() ); ?>" class="aqualuxe-event__sharing-button aqualuxe-event__sharing-button--email">
						<span class="dashicons dashicons-email"></span>
						<span class="aqualuxe-event__sharing-button-text"><?php esc_html_e( 'Email', 'aqualuxe' ); ?></span>
					</a>
				</div>
			</div>
		<?php endif; ?>
		
		<?php if ( $module->get_setting( 'show_related_events', true ) ) : ?>
			<?php $related_events = $event->get_related_events(); ?>
			
			<?php if ( ! empty( $related_events ) ) : ?>
				<div class="aqualuxe-event__related">
					<h3 class="aqualuxe-event__related-title"><?php esc_html_e( 'Related Events', 'aqualuxe' ); ?></h3>
					
					<div class="aqualuxe-event__related-list">
						<?php foreach ( $related_events as $related_event ) : ?>
							<div class="aqualuxe-event__related-item">
								<a href="<?php echo esc_url( $related_event->get_permalink() ); ?>" class="aqualuxe-event__related-link">
									<?php if ( $related_event->get_image_url( 'thumbnail' ) ) : ?>
										<div class="aqualuxe-event__related-image">
											<img src="<?php echo esc_url( $related_event->get_image_url( 'thumbnail' ) ); ?>" alt="<?php echo esc_attr( $related_event->get_title() ); ?>">
										</div>
									<?php endif; ?>
									
									<div class="aqualuxe-event__related-content">
										<h4 class="aqualuxe-event__related-name"><?php echo esc_html( $related_event->get_title() ); ?></h4>
										
										<div class="aqualuxe-event__related-date">
											<?php echo esc_html( $related_event->get_formatted_start_date() ); ?>
										</div>
									</div>
								</a>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endif; ?>
		<?php endif; ?>
	</div>
</div>