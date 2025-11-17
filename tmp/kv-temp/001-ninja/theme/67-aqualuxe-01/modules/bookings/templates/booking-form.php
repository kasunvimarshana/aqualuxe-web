<?php
/**
 * Booking Form Template
 *
 * @package AquaLuxe
 * @subpackage Modules\Bookings
 * @since 1.0.0
 */

// Get service ID
$service_id = isset( $atts['service_id'] ) ? absint( $atts['service_id'] ) : 0;

// Get date and time
$date = isset( $atts['date'] ) ? sanitize_text_field( $atts['date'] ) : '';
$time = isset( $atts['time'] ) ? sanitize_text_field( $atts['time'] ) : '';

// Get title and button text
$title = isset( $atts['title'] ) ? sanitize_text_field( $atts['title'] ) : esc_html__( 'Book an Appointment', 'aqualuxe' );
$button_text = isset( $atts['button'] ) ? sanitize_text_field( $atts['button'] ) : esc_html__( 'Book Now', 'aqualuxe' );

// Get class
$class = isset( $atts['class'] ) ? sanitize_text_field( $atts['class'] ) : '';

// Get services
$services = aqualuxe_get_services();

// Check if services exist
if ( empty( $services ) ) {
    echo '<p class="no-services">' . esc_html__( 'No services available.', 'aqualuxe' ) . '</p>';
    return;
}

// Get selected service
$selected_service = null;

if ( $service_id ) {
    $selected_service = aqualuxe_get_service( $service_id );
    
    // Check if service exists
    if ( ! $selected_service->get_id() ) {
        $selected_service = null;
    }
}

// Get next available date and time
if ( $selected_service ) {
    if ( ! $date ) {
        $date = $selected_service->get_next_available_date();
    }
    
    if ( ! $time && $date ) {
        $availability = new AquaLuxe\Modules\Bookings\Availability( $service_id, $date );
        $available_slots = $availability->get_available_time_slots();
        
        if ( ! empty( $available_slots ) ) {
            $time = $available_slots[0];
        }
    }
}

// Get settings
$settings = AquaLuxe\Modules\Bookings\Settings::get_instance();
$woocommerce_integration = $settings->get_woocommerce_integration();

// Get WooCommerce checkout URL
$checkout_url = '';

if ( $woocommerce_integration && function_exists( 'wc_get_checkout_url' ) ) {
    $checkout_url = wc_get_checkout_url();
}

// Form action
$form_action = $woocommerce_integration ? wc_get_cart_url() : '';

// Get current user
$current_user = wp_get_current_user();
$user_name = $current_user->ID ? $current_user->display_name : '';
$user_email = $current_user->ID ? $current_user->user_email : '';
$user_phone = $current_user->ID ? get_user_meta( $current_user->ID, 'billing_phone', true ) : '';
?>

<div class="aqualuxe-booking-form <?php echo esc_attr( $class ); ?>">
    <h2 class="booking-form-title"><?php echo esc_html( $title ); ?></h2>
    
    <form action="<?php echo esc_url( $form_action ); ?>" method="post" class="booking-form">
        <?php wp_nonce_field( 'aqualuxe_booking_form', 'aqualuxe_booking_nonce' ); ?>
        
        <div class="booking-form-fields">
            <?php if ( ! $selected_service ) : ?>
                <div class="booking-service-field">
                    <label for="service_id"><?php esc_html_e( 'Service', 'aqualuxe' ); ?> <span class="required">*</span></label>
                    <select name="service_id" id="service_id" required>
                        <option value=""><?php esc_html_e( 'Select a service', 'aqualuxe' ); ?></option>
                        <?php foreach ( $services as $service ) : ?>
                            <option value="<?php echo esc_attr( $service->get_id() ); ?>" data-price="<?php echo esc_attr( $service->get_current_price() ); ?>" data-duration="<?php echo esc_attr( $service->get_duration() ); ?>"><?php echo esc_html( $service->get_title() ); ?> - <?php echo wp_kses_post( $service->get_formatted_price() ); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php else : ?>
                <input type="hidden" name="service_id" value="<?php echo esc_attr( $selected_service->get_id() ); ?>">
                <div class="booking-service-info">
                    <h3 class="service-title"><?php echo esc_html( $selected_service->get_title() ); ?></h3>
                    <div class="service-details">
                        <div class="service-price"><?php echo wp_kses_post( $selected_service->get_formatted_price() ); ?></div>
                        <div class="service-duration"><?php echo esc_html( $selected_service->get_formatted_duration() ); ?></div>
                    </div>
                    <?php if ( $selected_service->get_location() ) : ?>
                        <div class="service-location"><?php echo esc_html( $selected_service->get_location() ); ?></div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            <div class="booking-date-field">
                <label for="booking_date"><?php esc_html_e( 'Date', 'aqualuxe' ); ?> <span class="required">*</span></label>
                <input type="date" name="booking_date" id="booking_date" value="<?php echo esc_attr( $date ); ?>" required>
            </div>
            
            <div class="booking-time-field">
                <label for="booking_time"><?php esc_html_e( 'Time', 'aqualuxe' ); ?> <span class="required">*</span></label>
                <select name="booking_time" id="booking_time" required>
                    <?php if ( $selected_service && $date ) : ?>
                        <?php
                        $availability = new AquaLuxe\Modules\Bookings\Availability( $selected_service->get_id(), $date );
                        $available_slots = $availability->get_available_time_slots();
                        
                        if ( ! empty( $available_slots ) ) :
                            foreach ( $available_slots as $slot ) :
                                ?>
                                <option value="<?php echo esc_attr( $slot ); ?>" <?php selected( $time, $slot ); ?>><?php echo esc_html( aqualuxe_format_time( $slot ) ); ?></option>
                                <?php
                            endforeach;
                        else :
                            ?>
                            <option value=""><?php esc_html_e( 'No available time slots', 'aqualuxe' ); ?></option>
                            <?php
                        endif;
                    else :
                        ?>
                        <option value=""><?php esc_html_e( 'Select a date first', 'aqualuxe' ); ?></option>
                        <?php
                    endif;
                    ?>
                </select>
            </div>
            
            <?php if ( ! is_user_logged_in() ) : ?>
                <div class="booking-customer-fields">
                    <div class="booking-customer-name-field">
                        <label for="booking_customer_name"><?php esc_html_e( 'Name', 'aqualuxe' ); ?> <span class="required">*</span></label>
                        <input type="text" name="booking_customer_name" id="booking_customer_name" value="<?php echo esc_attr( $user_name ); ?>" required>
                    </div>
                    
                    <div class="booking-customer-email-field">
                        <label for="booking_customer_email"><?php esc_html_e( 'Email', 'aqualuxe' ); ?> <span class="required">*</span></label>
                        <input type="email" name="booking_customer_email" id="booking_customer_email" value="<?php echo esc_attr( $user_email ); ?>" required>
                    </div>
                    
                    <div class="booking-customer-phone-field">
                        <label for="booking_customer_phone"><?php esc_html_e( 'Phone', 'aqualuxe' ); ?> <span class="required">*</span></label>
                        <input type="tel" name="booking_customer_phone" id="booking_customer_phone" value="<?php echo esc_attr( $user_phone ); ?>" required>
                    </div>
                </div>
            <?php endif; ?>
            
            <div class="booking-customer-notes-field">
                <label for="booking_customer_notes"><?php esc_html_e( 'Notes', 'aqualuxe' ); ?></label>
                <textarea name="booking_customer_notes" id="booking_customer_notes" rows="3"></textarea>
            </div>
            
            <div class="booking-submit-field">
                <button type="submit" class="booking-submit-button"><?php echo esc_html( $button_text ); ?></button>
            </div>
        </div>
    </form>
    
    <div class="booking-form-messages">
        <div class="booking-form-message booking-form-message-success" style="display: none;">
            <?php esc_html_e( 'Your booking has been received and is pending confirmation. We will contact you shortly.', 'aqualuxe' ); ?>
        </div>
        
        <div class="booking-form-message booking-form-message-error" style="display: none;">
            <?php esc_html_e( 'There was an error processing your booking. Please try again.', 'aqualuxe' ); ?>
        </div>
    </div>
</div>

<script type="text/javascript">
    jQuery(document).ready(function($) {
        // Service change
        $('#service_id').on('change', function() {
            var serviceId = $(this).val();
            var dateField = $('#booking_date');
            var timeField = $('#booking_time');
            
            if (serviceId) {
                // Get available dates
                $.ajax({
                    url: aqualuxeBookings.ajaxUrl,
                    type: 'POST',
                    data: {
                        action: 'aqualuxe_get_available_dates',
                        service_id: serviceId,
                        nonce: aqualuxeBookings.nonce
                    },
                    success: function(response) {
                        if (response.success) {
                            // Set min and max dates
                            if (response.data.dates.length > 0) {
                                dateField.val(response.data.dates[0]);
                                dateField.trigger('change');
                            }
                        }
                    }
                });
            } else {
                timeField.html('<option value="">' + aqualuxeBookings.i18n.selectDate + '</option>');
            }
        });
        
        // Date change
        $('#booking_date').on('change', function() {
            var serviceId = $('#service_id').val();
            var date = $(this).val();
            var timeField = $('#booking_time');
            
            if (serviceId && date) {
                // Get available time slots
                $.ajax({
                    url: aqualuxeBookings.ajaxUrl,
                    type: 'POST',
                    data: {
                        action: 'aqualuxe_get_available_time_slots',
                        service_id: serviceId,
                        date: date,
                        nonce: aqualuxeBookings.nonce
                    },
                    success: function(response) {
                        if (response.success) {
                            // Update time slots
                            timeField.empty();
                            
                            if (response.data.slots.length > 0) {
                                $.each(response.data.slots, function(index, slot) {
                                    timeField.append('<option value="' + slot.value + '">' + slot.label + '</option>');
                                });
                            } else {
                                timeField.append('<option value="">' + aqualuxeBookings.i18n.noTimesAvailable + '</option>');
                            }
                        }
                    }
                });
            } else {
                timeField.html('<option value="">' + aqualuxeBookings.i18n.selectDate + '</option>');
            }
        });
        
        // Form submission
        $('.booking-form').on('submit', function(e) {
            <?php if ( ! $woocommerce_integration ) : ?>
            e.preventDefault();
            
            var form = $(this);
            var successMessage = $('.booking-form-message-success');
            var errorMessage = $('.booking-form-message-error');
            
            // Hide messages
            successMessage.hide();
            errorMessage.hide();
            
            // Disable submit button
            form.find('button[type="submit"]').prop('disabled', true).text(aqualuxeBookings.i18n.booking);
            
            // Submit form
            $.ajax({
                url: aqualuxeBookings.ajaxUrl,
                type: 'POST',
                data: form.serialize() + '&action=aqualuxe_create_booking',
                success: function(response) {
                    if (response.success) {
                        // Show success message
                        successMessage.show();
                        
                        // Reset form
                        form[0].reset();
                    } else {
                        // Show error message
                        errorMessage.text(response.data.message).show();
                    }
                },
                error: function() {
                    // Show error message
                    errorMessage.show();
                },
                complete: function() {
                    // Enable submit button
                    form.find('button[type="submit"]').prop('disabled', false).text('<?php echo esc_js( $button_text ); ?>');
                }
            });
            <?php endif; ?>
        });
    });
</script>