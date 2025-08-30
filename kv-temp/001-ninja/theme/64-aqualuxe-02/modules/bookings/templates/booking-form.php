<?php
/**
 * Booking Form Template
 *
 * @package AquaLuxe
 * @subpackage Bookings
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

// Get services if not specified
if ( empty( $atts['service_id'] ) ) {
    $services = aqualuxe_get_services();
} else {
    $service = aqualuxe_get_service( $atts['service_id'] );
    $services = $service ? array( $service ) : array();
}

// Get resources if service and resource specified
$resources = array();
if ( ! empty( $atts['service_id'] ) && ! empty( $atts['resource_id'] ) ) {
    $resource = aqualuxe_get_resource( $atts['resource_id'] );
    $resources = $resource ? array( $resource ) : array();
} elseif ( ! empty( $atts['service_id'] ) ) {
    $resources = aqualuxe_get_service_resources( $atts['service_id'] );
}

// Generate unique ID
$form_id = 'booking-form-' . uniqid();

// Get current user info
$current_user = wp_get_current_user();
$user_name = $current_user->exists() ? $current_user->display_name : '';
$user_email = $current_user->exists() ? $current_user->user_email : '';

// Form classes
$form_classes = array(
    'aqualuxe-booking-form',
    'layout-' . $atts['layout'],
    $atts['class'],
);
?>

<div id="<?php echo esc_attr( $form_id ); ?>" class="<?php echo esc_attr( implode( ' ', $form_classes ) ); ?>">
    <?php if ( $atts['show_title'] && ! empty( $atts['title'] ) ) : ?>
        <h2 class="booking-form-title"><?php echo esc_html( $atts['title'] ); ?></h2>
    <?php endif; ?>

    <?php if ( $atts['show_description'] && ! empty( $atts['description'] ) ) : ?>
        <div class="booking-form-description"><?php echo wp_kses_post( $atts['description'] ); ?></div>
    <?php endif; ?>

    <form class="booking-form" method="post">
        <?php wp_nonce_field( 'aqualuxe_booking_form', 'booking_nonce' ); ?>

        <div class="booking-form-fields">
            <?php if ( empty( $atts['service_id'] ) ) : ?>
                <div class="booking-form-field booking-form-field-service">
                    <label for="<?php echo esc_attr( $form_id ); ?>-service"><?php esc_html_e( 'Service', 'aqualuxe' ); ?> <span class="required">*</span></label>
                    <select id="<?php echo esc_attr( $form_id ); ?>-service" name="service_id" required>
                        <option value=""><?php esc_html_e( 'Select a service', 'aqualuxe' ); ?></option>
                        <?php foreach ( $services as $service ) : ?>
                            <option value="<?php echo esc_attr( $service['id'] ); ?>"><?php echo esc_html( $service['title'] ); ?> - <?php echo aqualuxe_format_price( $service['price'] ); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php else : ?>
                <input type="hidden" name="service_id" value="<?php echo esc_attr( $atts['service_id'] ); ?>">
            <?php endif; ?>

            <?php if ( ! empty( $resources ) && empty( $atts['resource_id'] ) ) : ?>
                <div class="booking-form-field booking-form-field-resource">
                    <label for="<?php echo esc_attr( $form_id ); ?>-resource"><?php esc_html_e( 'Resource', 'aqualuxe' ); ?></label>
                    <select id="<?php echo esc_attr( $form_id ); ?>-resource" name="resource_id">
                        <option value=""><?php esc_html_e( 'Select a resource (optional)', 'aqualuxe' ); ?></option>
                        <?php foreach ( $resources as $resource ) : ?>
                            <option value="<?php echo esc_attr( $resource['id'] ); ?>"><?php echo esc_html( $resource['title'] ); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php elseif ( ! empty( $atts['resource_id'] ) ) : ?>
                <input type="hidden" name="resource_id" value="<?php echo esc_attr( $atts['resource_id'] ); ?>">
            <?php endif; ?>

            <div class="booking-form-field booking-form-field-date">
                <label for="<?php echo esc_attr( $form_id ); ?>-date"><?php esc_html_e( 'Date', 'aqualuxe' ); ?> <span class="required">*</span></label>
                <input type="text" id="<?php echo esc_attr( $form_id ); ?>-date" name="date" class="booking-date-picker" required readonly>
            </div>

            <div class="booking-form-field booking-form-field-time">
                <label for="<?php echo esc_attr( $form_id ); ?>-time"><?php esc_html_e( 'Time', 'aqualuxe' ); ?> <span class="required">*</span></label>
                <select id="<?php echo esc_attr( $form_id ); ?>-time" name="time" required disabled>
                    <option value=""><?php esc_html_e( 'Select a date first', 'aqualuxe' ); ?></option>
                </select>
            </div>

            <div class="booking-form-field booking-form-field-name">
                <label for="<?php echo esc_attr( $form_id ); ?>-name"><?php esc_html_e( 'Your Name', 'aqualuxe' ); ?> <span class="required">*</span></label>
                <input type="text" id="<?php echo esc_attr( $form_id ); ?>-name" name="customer_name" value="<?php echo esc_attr( $user_name ); ?>" required>
            </div>

            <div class="booking-form-field booking-form-field-email">
                <label for="<?php echo esc_attr( $form_id ); ?>-email"><?php esc_html_e( 'Your Email', 'aqualuxe' ); ?> <span class="required">*</span></label>
                <input type="email" id="<?php echo esc_attr( $form_id ); ?>-email" name="customer_email" value="<?php echo esc_attr( $user_email ); ?>" required>
            </div>

            <div class="booking-form-field booking-form-field-phone">
                <label for="<?php echo esc_attr( $form_id ); ?>-phone"><?php esc_html_e( 'Your Phone', 'aqualuxe' ); ?></label>
                <input type="tel" id="<?php echo esc_attr( $form_id ); ?>-phone" name="customer_phone">
            </div>

            <div class="booking-form-field booking-form-field-notes">
                <label for="<?php echo esc_attr( $form_id ); ?>-notes"><?php esc_html_e( 'Notes', 'aqualuxe' ); ?></label>
                <textarea id="<?php echo esc_attr( $form_id ); ?>-notes" name="notes" rows="4"></textarea>
            </div>
        </div>

        <div class="booking-form-summary">
            <h3><?php esc_html_e( 'Booking Summary', 'aqualuxe' ); ?></h3>
            <div class="booking-summary-content">
                <p><?php esc_html_e( 'Please select a service, date, and time to see your booking summary.', 'aqualuxe' ); ?></p>
            </div>
        </div>

        <div class="booking-form-actions">
            <button type="submit" class="booking-form-submit"><?php esc_html_e( 'Book Now', 'aqualuxe' ); ?></button>
        </div>
    </form>

    <div class="booking-form-message" style="display: none;"></div>
</div>

<script type="text/javascript">
    jQuery(document).ready(function($) {
        // Initialize date picker
        var $datePicker = $('#<?php echo esc_js( $form_id ); ?>-date');
        var $timePicker = $('#<?php echo esc_js( $form_id ); ?>-time');
        var $serviceSelect = $('#<?php echo esc_js( $form_id ); ?>-service');
        var $resourceSelect = $('#<?php echo esc_js( $form_id ); ?>-resource');
        var $form = $('#<?php echo esc_js( $form_id ); ?> form');
        var $summary = $('#<?php echo esc_js( $form_id ); ?> .booking-summary-content');
        var $message = $('#<?php echo esc_js( $form_id ); ?> .booking-form-message');
        
        // Initialize date picker
        $datePicker.datepicker({
            dateFormat: aqualuxeBookings.settings.dateFormat,
            minDate: '+' + aqualuxeBookings.settings.minDaysAdvance + 'd',
            maxDate: '+' + aqualuxeBookings.settings.maxDaysAdvance + 'd',
            firstDay: aqualuxeBookings.settings.firstDay,
            beforeShowDay: function(date) {
                // Disable past dates
                var today = new Date();
                today.setHours(0, 0, 0, 0);
                
                if (date < today) {
                    return [false, '', aqualuxeBookings.i18n.noAvailability];
                }
                
                return [true, '', ''];
            },
            onSelect: function(dateText, inst) {
                // Clear time picker
                $timePicker.empty().append('<option value="">' + aqualuxeBookings.i18n.loading + '</option>').prop('disabled', true);
                
                // Get service ID
                var serviceId = <?php echo ! empty( $atts['service_id'] ) ? absint( $atts['service_id'] ) : '$serviceSelect.val()'; ?>;
                
                // Get resource ID
                var resourceId = <?php echo ! empty( $atts['resource_id'] ) ? absint( $atts['resource_id'] ) : '$resourceSelect.val()'; ?>;
                
                // Check if service is selected
                if (!serviceId) {
                    $timePicker.empty().append('<option value="">' + aqualuxeBookings.i18n.selectService + '</option>').prop('disabled', true);
                    return;
                }
                
                // Get available time slots
                $.ajax({
                    url: aqualuxeBookings.ajaxUrl,
                    type: 'POST',
                    data: {
                        action: 'aqualuxe_booking_check_availability',
                        service_id: serviceId,
                        resource_id: resourceId,
                        date: dateText,
                        nonce: aqualuxeBookings.nonce
                    },
                    success: function(response) {
                        if (response.success && response.data.slots.length > 0) {
                            // Populate time picker
                            $timePicker.empty().append('<option value="">' + aqualuxeBookings.i18n.selectTime + '</option>');
                            
                            $.each(response.data.slots, function(index, slot) {
                                $timePicker.append('<option value="' + slot.time + '">' + slot.label + '</option>');
                            });
                            
                            $timePicker.prop('disabled', false);
                        } else {
                            $timePicker.empty().append('<option value="">' + aqualuxeBookings.i18n.noAvailability + '</option>').prop('disabled', true);
                        }
                        
                        updateSummary();
                    },
                    error: function() {
                        $timePicker.empty().append('<option value="">' + aqualuxeBookings.i18n.noAvailability + '</option>').prop('disabled', true);
                        updateSummary();
                    }
                });
            }
        });
        
        // Service change event
        $serviceSelect.on('change', function() {
            // Clear date and time
            $datePicker.val('');
            $timePicker.empty().append('<option value="">' + aqualuxeBookings.i18n.selectDate + '</option>').prop('disabled', true);
            
            // Update resource select
            var serviceId = $(this).val();
            
            if (serviceId) {
                // TODO: Load resources for service
            }
            
            updateSummary();
        });
        
        // Resource change event
        $resourceSelect.on('change', function() {
            // Clear date and time if date is selected
            if ($datePicker.val()) {
                $datePicker.datepicker('setDate', null);
                $timePicker.empty().append('<option value="">' + aqualuxeBookings.i18n.selectDate + '</option>').prop('disabled', true);
            }
            
            updateSummary();
        });
        
        // Time change event
        $timePicker.on('change', function() {
            updateSummary();
        });
        
        // Form submission
        $form.on('submit', function(e) {
            e.preventDefault();
            
            // Validate form
            if (!validateForm()) {
                return;
            }
            
            // Disable submit button
            $form.find('button[type="submit"]').prop('disabled', true).text(aqualuxeBookings.i18n.loading);
            
            // Get form data
            var formData = $(this).serialize();
            
            // Submit booking
            $.ajax({
                url: aqualuxeBookings.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_booking_create',
                    service_id: <?php echo ! empty( $atts['service_id'] ) ? absint( $atts['service_id'] ) : '$serviceSelect.val()'; ?>,
                    resource_id: <?php echo ! empty( $atts['resource_id'] ) ? absint( $atts['resource_id'] ) : '$resourceSelect.val() || 0'; ?>,
                    date: $datePicker.val(),
                    time: $timePicker.val(),
                    customer_name: $('#<?php echo esc_js( $form_id ); ?>-name').val(),
                    customer_email: $('#<?php echo esc_js( $form_id ); ?>-email').val(),
                    customer_phone: $('#<?php echo esc_js( $form_id ); ?>-phone').val(),
                    notes: $('#<?php echo esc_js( $form_id ); ?>-notes').val(),
                    nonce: aqualuxeBookings.nonce
                },
                success: function(response) {
                    // Enable submit button
                    $form.find('button[type="submit"]').prop('disabled', false).text('<?php esc_html_e( 'Book Now', 'aqualuxe' ); ?>');
                    
                    if (response.success) {
                        // Show success message
                        $message.removeClass('error').addClass('success').html(aqualuxeBookings.i18n.bookingSuccess).show();
                        
                        // Reset form
                        $form[0].reset();
                        $datePicker.datepicker('setDate', null);
                        $timePicker.empty().append('<option value="">' + aqualuxeBookings.i18n.selectDate + '</option>').prop('disabled', true);
                        
                        // Redirect if needed
                        if (response.data.redirect) {
                            window.location.href = response.data.redirect;
                        }
                    } else {
                        // Show error message
                        $message.removeClass('success').addClass('error').html(response.data.message).show();
                    }
                },
                error: function() {
                    // Enable submit button
                    $form.find('button[type="submit"]').prop('disabled', false).text('<?php esc_html_e( 'Book Now', 'aqualuxe' ); ?>');
                    
                    // Show error message
                    $message.removeClass('success').addClass('error').html(aqualuxeBookings.i18n.bookingError).show();
                }
            });
        });
        
        // Update summary
        function updateSummary() {
            var serviceId = <?php echo ! empty( $atts['service_id'] ) ? absint( $atts['service_id'] ) : '$serviceSelect.val()'; ?>;
            var resourceId = <?php echo ! empty( $atts['resource_id'] ) ? absint( $atts['resource_id'] ) : '$resourceSelect.val()'; ?>;
            var date = $datePicker.val();
            var time = $timePicker.val();
            
            if (!serviceId || !date || !time) {
                $summary.html('<p><?php esc_html_e( 'Please select a service, date, and time to see your booking summary.', 'aqualuxe' ); ?></p>');
                return;
            }
            
            // Get service details
            var serviceName = <?php echo ! empty( $atts['service_id'] ) ? "'" . esc_js( $service['title'] ) . "'" : '$serviceSelect.find("option:selected").text()'; ?>;
            var servicePrice = <?php echo ! empty( $atts['service_id'] ) ? "'" . esc_js( aqualuxe_format_price( $service['price'] ) ) . "'" : 'null'; ?>;
            
            if (!servicePrice) {
                servicePrice = $serviceSelect.find('option:selected').text().split(' - ')[1];
            }
            
            // Get resource details
            var resourceName = '';
            if (resourceId) {
                resourceName = <?php echo ! empty( $atts['resource_id'] ) ? "'" . esc_js( $resource['title'] ) . "'" : '$resourceSelect.find("option:selected").text()'; ?>;
            }
            
            // Build summary HTML
            var html = '<div class="booking-summary-item">';
            html += '<div class="booking-summary-label"><?php esc_html_e( 'Service:', 'aqualuxe' ); ?></div>';
            html += '<div class="booking-summary-value">' + serviceName + '</div>';
            html += '</div>';
            
            if (resourceName) {
                html += '<div class="booking-summary-item">';
                html += '<div class="booking-summary-label"><?php esc_html_e( 'Resource:', 'aqualuxe' ); ?></div>';
                html += '<div class="booking-summary-value">' + resourceName + '</div>';
                html += '</div>';
            }
            
            html += '<div class="booking-summary-item">';
            html += '<div class="booking-summary-label"><?php esc_html_e( 'Date:', 'aqualuxe' ); ?></div>';
            html += '<div class="booking-summary-value">' + date + '</div>';
            html += '</div>';
            
            html += '<div class="booking-summary-item">';
            html += '<div class="booking-summary-label"><?php esc_html_e( 'Time:', 'aqualuxe' ); ?></div>';
            html += '<div class="booking-summary-value">' + $timePicker.find('option:selected').text() + '</div>';
            html += '</div>';
            
            html += '<div class="booking-summary-item">';
            html += '<div class="booking-summary-label"><?php esc_html_e( 'Price:', 'aqualuxe' ); ?></div>';
            html += '<div class="booking-summary-value">' + servicePrice + '</div>';
            html += '</div>';
            
            $summary.html(html);
        }
        
        // Validate form
        function validateForm() {
            var isValid = true;
            
            // Check service
            var serviceId = <?php echo ! empty( $atts['service_id'] ) ? absint( $atts['service_id'] ) : '$serviceSelect.val()'; ?>;
            if (!serviceId) {
                $message.removeClass('success').addClass('error').html(aqualuxeBookings.i18n.selectService).show();
                isValid = false;
            }
            
            // Check date
            var date = $datePicker.val();
            if (!date) {
                $message.removeClass('success').addClass('error').html(aqualuxeBookings.i18n.selectDate).show();
                isValid = false;
            }
            
            // Check time
            var time = $timePicker.val();
            if (!time) {
                $message.removeClass('success').addClass('error').html(aqualuxeBookings.i18n.selectTime).show();
                isValid = false;
            }
            
            return isValid;
        }
    });
</script>