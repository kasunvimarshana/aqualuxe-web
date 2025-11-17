<?php
/**
 * Booking Confirmation Template
 *
 * This template can be overridden by copying it to yourtheme/aqualuxe/bookings/booking-confirmation.php.
 *
 * @package AquaLuxe
 * @subpackage Bookings
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Get confirmation data
$booking = isset($booking) ? $booking : null;
$show_details = isset($show_details) ? $show_details : true;
$show_message = isset($show_message) ? $show_message : true;
$message = isset($message) ? $message : __('Thank you for your booking. We have received your request and will contact you shortly.', 'aqualuxe');

// Get booking ID from query string if not provided
if (!$booking && isset($_GET['booking_id'])) {
    $booking_id = absint($_GET['booking_id']);
    
    if ($booking_id > 0) {
        $bookings_data = new AquaLuxe_Bookings_Data();
        $booking = $bookings_data->get_booking($booking_id);
    }
}

// If no booking ID is provided, check for booking_confirmed parameter
if (!$booking && isset($_GET['booking_confirmed'])) {
    $booking_id = absint($_GET['booking_confirmed']);
    
    if ($booking_id > 0) {
        $bookings_data = new AquaLuxe_Bookings_Data();
        $booking = $bookings_data->get_booking($booking_id);
    }
}

// Format date and time if booking is available
if ($booking) {
    $date_format = get_option('date_format');
    $time_format = get_option('time_format');
    
    $booking_date = date_i18n($date_format, strtotime($booking['start_date']));
    $booking_time = date_i18n($time_format, strtotime($booking['start_date'])) . ' - ' . date_i18n($time_format, strtotime($booking['end_date']));
    
    // Format price
    $formatted_total = function_exists('wc_price') ? wc_price($booking['total']) : '$' . number_format($booking['total'], 2);
    
    // Get status label
    $status_labels = array(
        'aqualuxe-pending' => __('Pending', 'aqualuxe'),
        'aqualuxe-confirmed' => __('Confirmed', 'aqualuxe'),
        'aqualuxe-completed' => __('Completed', 'aqualuxe'),
        'aqualuxe-cancelled' => __('Cancelled', 'aqualuxe'),
    );
    
    $status_label = isset($status_labels[$booking['status']]) ? $status_labels[$booking['status']] : ucfirst(str_replace('aqualuxe-', '', $booking['status']));
    
    // Get status message
    $status_messages = array(
        'aqualuxe-pending' => __('Your booking is pending confirmation. We will contact you shortly to confirm your booking.', 'aqualuxe'),
        'aqualuxe-confirmed' => __('Your booking has been confirmed. We look forward to seeing you!', 'aqualuxe'),
        'aqualuxe-completed' => __('Your booking has been completed. Thank you for choosing us!', 'aqualuxe'),
        'aqualuxe-cancelled' => __('Your booking has been cancelled.', 'aqualuxe'),
    );
    
    $status_message = isset($status_messages[$booking['status']]) ? $status_messages[$booking['status']] : '';
    
    // Get order details if available
    $order = null;
    
    if (!empty($booking['order_id']) && function_exists('wc_get_order')) {
        $order = wc_get_order($booking['order_id']);
    }
}
?>

<div class="aqualuxe-bookings-confirmation">
    <?php if ($show_message) : ?>
        <div class="aqualuxe-bookings-confirmation-message">
            <?php if ($booking && $booking['status'] === 'aqualuxe-confirmed') : ?>
                <div class="confirmation-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="48" height="48" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                </div>
            <?php elseif ($booking && $booking['status'] === 'aqualuxe-cancelled') : ?>
                <div class="confirmation-icon cancelled">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="48" height="48" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="15" y1="9" x2="9" y2="15"></line>
                        <line x1="9" y1="9" x2="15" y2="15"></line>
                    </svg>
                </div>
            <?php else : ?>
                <div class="confirmation-icon pending">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="48" height="48" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                </div>
            <?php endif; ?>
            
            <h2><?php 
                if ($booking) {
                    if ($booking['status'] === 'aqualuxe-confirmed') {
                        echo esc_html__('Booking Confirmed!', 'aqualuxe');
                    } elseif ($booking['status'] === 'aqualuxe-cancelled') {
                        echo esc_html__('Booking Cancelled', 'aqualuxe');
                    } else {
                        echo esc_html__('Booking Received', 'aqualuxe');
                    }
                } else {
                    echo esc_html__('Thank You', 'aqualuxe');
                }
            ?></h2>
            
            <?php if ($booking && !empty($status_message)) : ?>
                <p class="status-message"><?php echo esc_html($status_message); ?></p>
            <?php else : ?>
                <p><?php echo esc_html($message); ?></p>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    
    <?php if ($show_details && $booking) : ?>
        <div class="aqualuxe-bookings-confirmation-details">
            <h3><?php _e('Booking Details', 'aqualuxe'); ?></h3>
            
            <div class="booking-details">
                <div class="booking-detail">
                    <span class="detail-label"><?php _e('Booking ID:', 'aqualuxe'); ?></span>
                    <span class="detail-value"><?php echo esc_html($booking['booking_id']); ?></span>
                </div>
                
                <div class="booking-detail">
                    <span class="detail-label"><?php _e('Service:', 'aqualuxe'); ?></span>
                    <span class="detail-value"><?php echo esc_html($booking['service_name']); ?></span>
                </div>
                
                <div class="booking-detail">
                    <span class="detail-label"><?php _e('Date:', 'aqualuxe'); ?></span>
                    <span class="detail-value"><?php echo esc_html($booking_date); ?></span>
                </div>
                
                <div class="booking-detail">
                    <span class="detail-label"><?php _e('Time:', 'aqualuxe'); ?></span>
                    <span class="detail-value"><?php echo esc_html($booking_time); ?></span>
                </div>
                
                <?php if ($booking['quantity'] > 1) : ?>
                    <div class="booking-detail">
                        <span class="detail-label"><?php _e('Quantity:', 'aqualuxe'); ?></span>
                        <span class="detail-value"><?php echo esc_html($booking['quantity']); ?></span>
                    </div>
                <?php endif; ?>
                
                <?php if ($booking['total'] > 0) : ?>
                    <div class="booking-detail">
                        <span class="detail-label"><?php _e('Total:', 'aqualuxe'); ?></span>
                        <span class="detail-value"><?php echo $formatted_total; ?></span>
                    </div>
                <?php endif; ?>
                
                <div class="booking-detail">
                    <span class="detail-label"><?php _e('Status:', 'aqualuxe'); ?></span>
                    <span class="detail-value status-<?php echo esc_attr(str_replace('aqualuxe-', '', $booking['status'])); ?>"><?php echo esc_html($status_label); ?></span>
                </div>
            </div>
            
            <?php if ($order) : ?>
                <div class="order-details">
                    <h4><?php _e('Order Details', 'aqualuxe'); ?></h4>
                    
                    <div class="booking-detail">
                        <span class="detail-label"><?php _e('Order Number:', 'aqualuxe'); ?></span>
                        <span class="detail-value"><?php echo esc_html($order->get_order_number()); ?></span>
                    </div>
                    
                    <div class="booking-detail">
                        <span class="detail-label"><?php _e('Order Date:', 'aqualuxe'); ?></span>
                        <span class="detail-value"><?php echo esc_html($order->get_date_created()->date_i18n(get_option('date_format'))); ?></span>
                    </div>
                    
                    <div class="booking-detail">
                        <span class="detail-label"><?php _e('Order Status:', 'aqualuxe'); ?></span>
                        <span class="detail-value"><?php echo esc_html(wc_get_order_status_name($order->get_status())); ?></span>
                    </div>
                    
                    <div class="booking-detail">
                        <span class="detail-label"><?php _e('Payment Method:', 'aqualuxe'); ?></span>
                        <span class="detail-value"><?php echo esc_html($order->get_payment_method_title()); ?></span>
                    </div>
                    
                    <div class="booking-actions">
                        <a href="<?php echo esc_url($order->get_view_order_url()); ?>" class="button"><?php _e('View Order', 'aqualuxe'); ?></a>
                    </div>
                </div>
            <?php endif; ?>
            
            <div class="customer-details">
                <h4><?php _e('Customer Details', 'aqualuxe'); ?></h4>
                
                <div class="booking-detail">
                    <span class="detail-label"><?php _e('Name:', 'aqualuxe'); ?></span>
                    <span class="detail-value"><?php echo esc_html($booking['customer_name']); ?></span>
                </div>
                
                <div class="booking-detail">
                    <span class="detail-label"><?php _e('Email:', 'aqualuxe'); ?></span>
                    <span class="detail-value"><?php echo esc_html($booking['customer_email']); ?></span>
                </div>
                
                <div class="booking-detail">
                    <span class="detail-label"><?php _e('Phone:', 'aqualuxe'); ?></span>
                    <span class="detail-value"><?php echo esc_html($booking['customer_phone']); ?></span>
                </div>
            </div>
            
            <?php
            // Get customer notes
            $customer_notes = get_post_meta($booking['id'], '_customer_notes', true);
            
            if (!empty($customer_notes)) : ?>
                <div class="customer-notes">
                    <h4><?php _e('Notes', 'aqualuxe'); ?></h4>
                    <p><?php echo esc_html($customer_notes); ?></p>
                </div>
            <?php endif; ?>
            
            <?php if ($booking['status'] === 'aqualuxe-confirmed' || $booking['status'] === 'aqualuxe-pending') : ?>
                <div class="booking-actions">
                    <a href="#" class="button cancel-booking" data-booking-id="<?php echo esc_attr($booking['id']); ?>" data-nonce="<?php echo esc_attr(wp_create_nonce('aqualuxe-bookings')); ?>"><?php _e('Cancel Booking', 'aqualuxe'); ?></a>
                    
                    <?php if (class_exists('AquaLuxe_Bookings_Calendar')) : ?>
                        <a href="#" class="button add-to-calendar"><?php _e('Add to Calendar', 'aqualuxe'); ?></a>
                        
                        <div class="calendar-dropdown">
                            <a href="<?php echo esc_url($this->get_google_calendar_url($booking)); ?>" target="_blank" rel="noopener noreferrer"><?php _e('Google Calendar', 'aqualuxe'); ?></a>
                            <a href="<?php echo esc_url($this->get_ical_url($booking)); ?>" target="_blank" rel="noopener noreferrer"><?php _e('Apple Calendar', 'aqualuxe'); ?></a>
                            <a href="<?php echo esc_url($this->get_outlook_url($booking)); ?>" target="_blank" rel="noopener noreferrer"><?php _e('Outlook', 'aqualuxe'); ?></a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    
    <div class="aqualuxe-bookings-confirmation-actions">
        <?php if (function_exists('wc_get_page_id') && wc_get_page_id('shop') > 0) : ?>
            <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="button"><?php _e('Continue Shopping', 'aqualuxe'); ?></a>
        <?php else : ?>
            <a href="<?php echo esc_url(home_url()); ?>" class="button"><?php _e('Back to Home', 'aqualuxe'); ?></a>
        <?php endif; ?>
        
        <?php
        // Get booking page URL
        $booking_page_id = get_option('aqualuxe_bookings_page_id');
        if ($booking_page_id) : ?>
            <a href="<?php echo esc_url(get_permalink($booking_page_id)); ?>" class="button button-primary"><?php _e('Book Another Service', 'aqualuxe'); ?></a>
        <?php endif; ?>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    // Handle cancel booking action
    $('.cancel-booking').on('click', function(e) {
        e.preventDefault();
        
        if (!confirm('<?php echo esc_js(__('Are you sure you want to cancel this booking?', 'aqualuxe')); ?>')) {
            return;
        }
        
        var button = $(this);
        var booking_id = button.data('booking-id');
        var nonce = button.data('nonce');
        
        $.ajax({
            url: aqualuxe_bookings_params.ajax_url,
            type: 'POST',
            data: {
                action: 'cancel_booking',
                booking_id: booking_id,
                nonce: nonce
            },
            beforeSend: function() {
                button.prop('disabled', true).text('<?php echo esc_js(__('Cancelling...', 'aqualuxe')); ?>');
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert(response.data.message);
                    button.prop('disabled', false).text('<?php echo esc_js(__('Cancel Booking', 'aqualuxe')); ?>');
                }
            },
            error: function() {
                alert('<?php echo esc_js(__('An error occurred. Please try again.', 'aqualuxe')); ?>');
                button.prop('disabled', false).text('<?php echo esc_js(__('Cancel Booking', 'aqualuxe')); ?>');
            }
        });
    });
    
    // Handle add to calendar dropdown
    $('.add-to-calendar').on('click', function(e) {
        e.preventDefault();
        $(this).next('.calendar-dropdown').toggleClass('active');
    });
    
    // Close calendar dropdown when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.booking-actions').length) {
            $('.calendar-dropdown').removeClass('active');
        }
    });
});
</script>

<style>
.aqualuxe-bookings-confirmation {
    max-width: 800px;
    margin: 0 auto;
    padding: 30px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.aqualuxe-bookings-confirmation-message {
    text-align: center;
    margin-bottom: 40px;
    padding-bottom: 30px;
    border-bottom: 1px solid #eee;
}

.confirmation-icon {
    margin-bottom: 20px;
    color: #5cb85c;
}

.confirmation-icon.pending {
    color: #f0ad4e;
}

.confirmation-icon.cancelled {
    color: #d9534f;
}

.aqualuxe-bookings-confirmation-message h2 {
    margin-top: 0;
    margin-bottom: 15px;
    color: var(--aqualuxe-bookings-primary-color, #0073aa);
}

.status-message {
    font-size: 1.1em;
    font-weight: 500;
}

.aqualuxe-bookings-confirmation-details {
    margin-bottom: 40px;
}

.aqualuxe-bookings-confirmation-details h3 {
    margin-top: 0;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 1px solid #eee;
    color: var(--aqualuxe-bookings-primary-color, #0073aa);
}

.aqualuxe-bookings-confirmation-details h4 {
    margin-top: 30px;
    margin-bottom: 15px;
    color: var(--aqualuxe-bookings-primary-color, #0073aa);
}

.booking-details,
.order-details,
.customer-details,
.customer-notes {
    margin-bottom: 30px;
}

.booking-detail {
    display: flex;
    margin-bottom: 10px;
    padding-bottom: 10px;
    border-bottom: 1px solid #f5f5f5;
}

.detail-label {
    flex: 0 0 150px;
    font-weight: 600;
}

.detail-value {
    flex: 1;
}

.status-pending {
    color: #f0ad4e;
    font-weight: 600;
}

.status-confirmed {
    color: #5cb85c;
    font-weight: 600;
}

.status-completed {
    color: #0073aa;
    font-weight: 600;
}

.status-cancelled {
    color: #d9534f;
    font-weight: 600;
}

.booking-actions {
    margin-top: 20px;
    position: relative;
}

.booking-actions .button {
    margin-right: 10px;
}

.calendar-dropdown {
    display: none;
    position: absolute;
    top: 100%;
    left: 0;
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 4px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    z-index: 10;
    min-width: 180px;
}

.calendar-dropdown.active {
    display: block;
}

.calendar-dropdown a {
    display: block;
    padding: 10px 15px;
    text-decoration: none;
    color: #333;
    border-bottom: 1px solid #f5f5f5;
}

.calendar-dropdown a:last-child {
    border-bottom: none;
}

.calendar-dropdown a:hover {
    background-color: #f9f9f9;
}

.aqualuxe-bookings-confirmation-actions {
    display: flex;
    justify-content: space-between;
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #eee;
}

@media (max-width: 768px) {
    .booking-detail {
        flex-direction: column;
    }
    
    .detail-label {
        flex: 0 0 100%;
        margin-bottom: 5px;
    }
    
    .aqualuxe-bookings-confirmation-actions {
        flex-direction: column;
        gap: 10px;
    }
    
    .aqualuxe-bookings-confirmation-actions .button {
        width: 100%;
        text-align: center;
    }
}
</style>