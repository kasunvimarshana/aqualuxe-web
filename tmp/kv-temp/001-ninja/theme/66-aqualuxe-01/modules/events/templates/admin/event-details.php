<?php
/**
 * Event Details Meta Box Template
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

// Get event data
$start_date = $event->get_start_date();
$end_date = $event->get_end_date();
$start_time = $event->get_start_time();
$end_time = $event->get_end_time();
$status = $event->get_status();
$capacity = $event->get_capacity();
$registration_status = $event->get_registration_status();
$registration_start_date = $event->get_registration_start_date();
$registration_end_date = $event->get_registration_end_date();
$featured = $event->get_featured();
$cost = $event->get_cost();
$cost_description = $event->get_cost_description();
$currency = $event->get_currency();
$website = $event->get_website();
$phone = $event->get_phone();
$email = $event->get_email();

// Create nonce
wp_nonce_field( 'aqualuxe_event_meta', 'aqualuxe_event_nonce' );
?>

<div class="aqualuxe-event-admin">
    <div class="aqualuxe-event-admin__section">
        <div class="aqualuxe-event-admin__field aqualuxe-event-admin__field--row">
            <div class="aqualuxe-event-admin__field-col">
                <label for="aqualuxe_event_start_date" class="aqualuxe-event-admin__label"><?php esc_html_e( 'Start Date', 'aqualuxe' ); ?></label>
                <input type="date" id="aqualuxe_event_start_date" name="aqualuxe_event_data[start_date]" value="<?php echo esc_attr( $start_date ); ?>" class="aqualuxe-event-admin__input" required>
            </div>
            
            <div class="aqualuxe-event-admin__field-col">
                <label for="aqualuxe_event_end_date" class="aqualuxe-event-admin__label"><?php esc_html_e( 'End Date', 'aqualuxe' ); ?></label>
                <input type="date" id="aqualuxe_event_end_date" name="aqualuxe_event_data[end_date]" value="<?php echo esc_attr( $end_date ); ?>" class="aqualuxe-event-admin__input" required>
            </div>
        </div>
        
        <div class="aqualuxe-event-admin__field aqualuxe-event-admin__field--row">
            <div class="aqualuxe-event-admin__field-col">
                <label for="aqualuxe_event_start_time" class="aqualuxe-event-admin__label"><?php esc_html_e( 'Start Time', 'aqualuxe' ); ?></label>
                <input type="time" id="aqualuxe_event_start_time" name="aqualuxe_event_data[start_time]" value="<?php echo esc_attr( $start_time ); ?>" class="aqualuxe-event-admin__input" required>
            </div>
            
            <div class="aqualuxe-event-admin__field-col">
                <label for="aqualuxe_event_end_time" class="aqualuxe-event-admin__label"><?php esc_html_e( 'End Time', 'aqualuxe' ); ?></label>
                <input type="time" id="aqualuxe_event_end_time" name="aqualuxe_event_data[end_time]" value="<?php echo esc_attr( $end_time ); ?>" class="aqualuxe-event-admin__input" required>
            </div>
        </div>
    </div>
    
    <div class="aqualuxe-event-admin__section">
        <div class="aqualuxe-event-admin__field aqualuxe-event-admin__field--row">
            <div class="aqualuxe-event-admin__field-col">
                <label for="aqualuxe_event_status" class="aqualuxe-event-admin__label"><?php esc_html_e( 'Event Status', 'aqualuxe' ); ?></label>
                <select id="aqualuxe_event_status" name="aqualuxe_event_data[status]" class="aqualuxe-event-admin__select">
                    <option value="published" <?php selected( $status, 'published' ); ?>><?php esc_html_e( 'Published', 'aqualuxe' ); ?></option>
                    <option value="draft" <?php selected( $status, 'draft' ); ?>><?php esc_html_e( 'Draft', 'aqualuxe' ); ?></option>
                    <option value="cancelled" <?php selected( $status, 'cancelled' ); ?>><?php esc_html_e( 'Cancelled', 'aqualuxe' ); ?></option>
                    <option value="postponed" <?php selected( $status, 'postponed' ); ?>><?php esc_html_e( 'Postponed', 'aqualuxe' ); ?></option>
                </select>
            </div>
            
            <div class="aqualuxe-event-admin__field-col">
                <label for="aqualuxe_event_capacity" class="aqualuxe-event-admin__label"><?php esc_html_e( 'Capacity', 'aqualuxe' ); ?></label>
                <input type="number" id="aqualuxe_event_capacity" name="aqualuxe_event_data[capacity]" value="<?php echo esc_attr( $capacity ); ?>" min="0" step="1" class="aqualuxe-event-admin__input">
                <p class="aqualuxe-event-admin__help"><?php esc_html_e( 'Set to 0 for unlimited capacity', 'aqualuxe' ); ?></p>
            </div>
        </div>
        
        <div class="aqualuxe-event-admin__field aqualuxe-event-admin__field--row">
            <div class="aqualuxe-event-admin__field-col">
                <label for="aqualuxe_event_registration_status" class="aqualuxe-event-admin__label"><?php esc_html_e( 'Registration Status', 'aqualuxe' ); ?></label>
                <select id="aqualuxe_event_registration_status" name="aqualuxe_event_data[registration_status]" class="aqualuxe-event-admin__select">
                    <option value="open" <?php selected( $registration_status, 'open' ); ?>><?php esc_html_e( 'Open', 'aqualuxe' ); ?></option>
                    <option value="closed" <?php selected( $registration_status, 'closed' ); ?>><?php esc_html_e( 'Closed', 'aqualuxe' ); ?></option>
                    <option value="invitation" <?php selected( $registration_status, 'invitation' ); ?>><?php esc_html_e( 'By Invitation', 'aqualuxe' ); ?></option>
                </select>
            </div>
            
            <div class="aqualuxe-event-admin__field-col">
                <label class="aqualuxe-event-admin__label aqualuxe-event-admin__label--checkbox">
                    <input type="checkbox" id="aqualuxe_event_featured" name="aqualuxe_event_data[featured]" value="1" <?php checked( $featured, true ); ?> class="aqualuxe-event-admin__checkbox">
                    <?php esc_html_e( 'Featured Event', 'aqualuxe' ); ?>
                </label>
            </div>
        </div>
        
        <div class="aqualuxe-event-admin__field aqualuxe-event-admin__field--row">
            <div class="aqualuxe-event-admin__field-col">
                <label for="aqualuxe_event_registration_start_date" class="aqualuxe-event-admin__label"><?php esc_html_e( 'Registration Start Date', 'aqualuxe' ); ?></label>
                <input type="date" id="aqualuxe_event_registration_start_date" name="aqualuxe_event_data[registration_start_date]" value="<?php echo esc_attr( $registration_start_date ); ?>" class="aqualuxe-event-admin__input">
            </div>
            
            <div class="aqualuxe-event-admin__field-col">
                <label for="aqualuxe_event_registration_end_date" class="aqualuxe-event-admin__label"><?php esc_html_e( 'Registration End Date', 'aqualuxe' ); ?></label>
                <input type="date" id="aqualuxe_event_registration_end_date" name="aqualuxe_event_data[registration_end_date]" value="<?php echo esc_attr( $registration_end_date ); ?>" class="aqualuxe-event-admin__input">
            </div>
        </div>
    </div>
    
    <div class="aqualuxe-event-admin__section">
        <div class="aqualuxe-event-admin__field aqualuxe-event-admin__field--row">
            <div class="aqualuxe-event-admin__field-col">
                <label for="aqualuxe_event_cost" class="aqualuxe-event-admin__label"><?php esc_html_e( 'Cost', 'aqualuxe' ); ?></label>
                <input type="number" id="aqualuxe_event_cost" name="aqualuxe_event_data[cost]" value="<?php echo esc_attr( $cost ); ?>" min="0" step="0.01" class="aqualuxe-event-admin__input">
            </div>
            
            <div class="aqualuxe-event-admin__field-col">
                <label for="aqualuxe_event_currency" class="aqualuxe-event-admin__label"><?php esc_html_e( 'Currency', 'aqualuxe' ); ?></label>
                <select id="aqualuxe_event_currency" name="aqualuxe_event_data[currency]" class="aqualuxe-event-admin__select">
                    <?php
                    $currencies = get_woocommerce_currencies();
                    $default_currency = $currency ? $currency : get_woocommerce_currency();
                    
                    foreach ( $currencies as $code => $name ) {
                        echo '<option value="' . esc_attr( $code ) . '" ' . selected( $default_currency, $code, false ) . '>' . esc_html( $name . ' (' . get_woocommerce_currency_symbol( $code ) . ')' ) . '</option>';
                    }
                    ?>
                </select>
            </div>
        </div>
        
        <div class="aqualuxe-event-admin__field">
            <label for="aqualuxe_event_cost_description" class="aqualuxe-event-admin__label"><?php esc_html_e( 'Cost Description', 'aqualuxe' ); ?></label>
            <input type="text" id="aqualuxe_event_cost_description" name="aqualuxe_event_data[cost_description]" value="<?php echo esc_attr( $cost_description ); ?>" class="aqualuxe-event-admin__input">
            <p class="aqualuxe-event-admin__help"><?php esc_html_e( 'Optional description for the cost (e.g., "per person", "includes lunch")', 'aqualuxe' ); ?></p>
        </div>
    </div>
    
    <div class="aqualuxe-event-admin__section">
        <div class="aqualuxe-event-admin__field">
            <label for="aqualuxe_event_website" class="aqualuxe-event-admin__label"><?php esc_html_e( 'Website', 'aqualuxe' ); ?></label>
            <input type="url" id="aqualuxe_event_website" name="aqualuxe_event_data[website]" value="<?php echo esc_attr( $website ); ?>" class="aqualuxe-event-admin__input">
        </div>
        
        <div class="aqualuxe-event-admin__field aqualuxe-event-admin__field--row">
            <div class="aqualuxe-event-admin__field-col">
                <label for="aqualuxe_event_phone" class="aqualuxe-event-admin__label"><?php esc_html_e( 'Phone', 'aqualuxe' ); ?></label>
                <input type="tel" id="aqualuxe_event_phone" name="aqualuxe_event_data[phone]" value="<?php echo esc_attr( $phone ); ?>" class="aqualuxe-event-admin__input">
            </div>
            
            <div class="aqualuxe-event-admin__field-col">
                <label for="aqualuxe_event_email" class="aqualuxe-event-admin__label"><?php esc_html_e( 'Email', 'aqualuxe' ); ?></label>
                <input type="email" id="aqualuxe_event_email" name="aqualuxe_event_data[email]" value="<?php echo esc_attr( $email ); ?>" class="aqualuxe-event-admin__input">
            </div>
        </div>
    </div>
</div>