<?php
/**
 * Booking Confirmation Template
 *
 * @package AquaLuxe
 * @subpackage Modules/Bookings
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Get booking ID
$booking_id = isset($_GET['booking_id']) ? intval($_GET['booking_id']) : 0;

// Get booking data
$booking_data = $this->get_booking_data($booking_id);

// Check if booking exists
if (!$booking_data) {
    ?>
    <div class="aqualuxe-booking-confirmation">
        <h2 class="aqualuxe-booking-confirmation__title"><?php esc_html_e('Booking Not Found', 'aqualuxe'); ?></h2>
        <p class="aqualuxe-booking-confirmation__message"><?php esc_html_e('The booking you are looking for could not be found. Please check the booking ID and try again.', 'aqualuxe'); ?></p>
        <div class="aqualuxe-booking-confirmation__buttons">
            <a href="<?php echo esc_url(home_url()); ?>" class="aqualuxe-booking-confirmation__button"><?php esc_html_e('Return to Home', 'aqualuxe'); ?></a>
        </div>
    </div>
    <?php
    return;
}

// Format date and time
$date = isset($booking_data['date']) ? date_i18n(get_option('date_format'), strtotime($booking_data['date'])) : '';
$time = isset($booking_data['time']) ? date_i18n(get_option('time_format'), strtotime($booking_data['time'])) : '';

// Get status
$status = isset($booking_data['status']) ? $booking_data['status'] : 'pending';
$status_labels = [
    'pending' => __('Pending', 'aqualuxe'),
    'confirmed' => __('Confirmed', 'aqualuxe'),
    'completed' => __('Completed', 'aqualuxe'),
    'cancelled' => __('Cancelled', 'aqualuxe'),
];
$status_label = isset($status_labels[$status]) ? $status_labels[$status] : $status;
$status_colors = [
    'pending' => '#f0ad4e',
    'confirmed' => '#5cb85c',
    'completed' => '#5bc0de',
    'cancelled' => '#d9534f',
];
$status_color = isset($status_colors[$status]) ? $status_colors[$status] : '#777777';
?>

<div class="aqualuxe-booking-confirmation">
    <h2 class="aqualuxe-booking-confirmation__title">
        <?php
        if ($status === 'confirmed' || $status === 'completed') {
            esc_html_e('Booking Confirmed', 'aqualuxe');
        } elseif ($status === 'cancelled') {
            esc_html_e('Booking Cancelled', 'aqualuxe');
        } else {
            esc_html_e('Booking Received', 'aqualuxe');
        }
        ?>
    </h2>
    
    <p class="aqualuxe-booking-confirmation__message">
        <?php
        if ($status === 'confirmed' || $status === 'completed') {
            esc_html_e('Thank you! Your booking has been confirmed. We look forward to seeing you.', 'aqualuxe');
        } elseif ($status === 'cancelled') {
            esc_html_e('Your booking has been cancelled. If you have any questions, please contact us.', 'aqualuxe');
        } else {
            esc_html_e('Thank you for your booking. We will confirm your appointment shortly.', 'aqualuxe');
        }
        ?>
    </p>
    
    <div class="aqualuxe-booking-confirmation__details">
        <div class="aqualuxe-booking-confirmation__detail">
            <div class="aqualuxe-booking-confirmation__detail-label"><?php esc_html_e('Booking ID', 'aqualuxe'); ?></div>
            <div class="aqualuxe-booking-confirmation__detail-value"><?php echo esc_html($booking_id); ?></div>
        </div>
        
        <div class="aqualuxe-booking-confirmation__detail">
            <div class="aqualuxe-booking-confirmation__detail-label"><?php esc_html_e('Service', 'aqualuxe'); ?></div>
            <div class="aqualuxe-booking-confirmation__detail-value"><?php echo esc_html(isset($booking_data['service_name']) ? $booking_data['service_name'] : ''); ?></div>
        </div>
        
        <div class="aqualuxe-booking-confirmation__detail">
            <div class="aqualuxe-booking-confirmation__detail-label"><?php esc_html_e('Date', 'aqualuxe'); ?></div>
            <div class="aqualuxe-booking-confirmation__detail-value"><?php echo esc_html($date); ?></div>
        </div>
        
        <div class="aqualuxe-booking-confirmation__detail">
            <div class="aqualuxe-booking-confirmation__detail-label"><?php esc_html_e('Time', 'aqualuxe'); ?></div>
            <div class="aqualuxe-booking-confirmation__detail-value"><?php echo esc_html($time); ?></div>
        </div>
        
        <div class="aqualuxe-booking-confirmation__detail">
            <div class="aqualuxe-booking-confirmation__detail-label"><?php esc_html_e('Status', 'aqualuxe'); ?></div>
            <div class="aqualuxe-booking-confirmation__detail-value">
                <span style="display: inline-block; padding: 3px 8px; background-color: <?php echo esc_attr($status_color); ?>; color: #ffffff; border-radius: 3px; font-size: 12px;"><?php echo esc_html($status_label); ?></span>
            </div>
        </div>
        
        <?php if (isset($booking_data['customer']) && is_array($booking_data['customer'])) : ?>
            <div class="aqualuxe-booking-confirmation__detail">
                <div class="aqualuxe-booking-confirmation__detail-label"><?php esc_html_e('Name', 'aqualuxe'); ?></div>
                <div class="aqualuxe-booking-confirmation__detail-value"><?php echo esc_html(isset($booking_data['customer']['name']) ? $booking_data['customer']['name'] : ''); ?></div>
            </div>
            
            <div class="aqualuxe-booking-confirmation__detail">
                <div class="aqualuxe-booking-confirmation__detail-label"><?php esc_html_e('Email', 'aqualuxe'); ?></div>
                <div class="aqualuxe-booking-confirmation__detail-value"><?php echo esc_html(isset($booking_data['customer']['email']) ? $booking_data['customer']['email'] : ''); ?></div>
            </div>
            
            <div class="aqualuxe-booking-confirmation__detail">
                <div class="aqualuxe-booking-confirmation__detail-label"><?php esc_html_e('Phone', 'aqualuxe'); ?></div>
                <div class="aqualuxe-booking-confirmation__detail-value"><?php echo esc_html(isset($booking_data['customer']['phone']) ? $booking_data['customer']['phone'] : ''); ?></div>
            </div>
            
            <?php if (isset($booking_data['customer']['notes']) && $booking_data['customer']['notes']) : ?>
                <div class="aqualuxe-booking-confirmation__detail">
                    <div class="aqualuxe-booking-confirmation__detail-label"><?php esc_html_e('Notes', 'aqualuxe'); ?></div>
                    <div class="aqualuxe-booking-confirmation__detail-value"><?php echo esc_html($booking_data['customer']['notes']); ?></div>
                </div>
            <?php endif; ?>
        <?php endif; ?>
        
        <?php if (isset($booking_data['order_id']) && $booking_data['order_id']) : ?>
            <div class="aqualuxe-booking-confirmation__detail">
                <div class="aqualuxe-booking-confirmation__detail-label"><?php esc_html_e('Order', 'aqualuxe'); ?></div>
                <div class="aqualuxe-booking-confirmation__detail-value">
                    <a href="<?php echo esc_url(wc_get_account_endpoint_url('view-order') . $booking_data['order_id']); ?>"><?php echo esc_html(sprintf(__('Order #%s', 'aqualuxe'), $booking_data['order_id'])); ?></a>
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="aqualuxe-booking-confirmation__buttons">
        <?php if ($status === 'pending' || $status === 'confirmed') : ?>
            <a href="#" class="aqualuxe-booking-confirmation__button aqualuxe-booking-confirmation__button--secondary aqualuxe-cancel-booking" data-booking-id="<?php echo esc_attr($booking_id); ?>"><?php esc_html_e('Cancel Booking', 'aqualuxe'); ?></a>
        <?php endif; ?>
        
        <a href="<?php echo esc_url(home_url()); ?>" class="aqualuxe-booking-confirmation__button"><?php esc_html_e('Return to Home', 'aqualuxe'); ?></a>
        
        <?php
        // Get booking page ID
        $booking_page_id = get_theme_mod('aqualuxe_booking_page', 0);
        
        // Check if booking page exists
        if ($booking_page_id) :
        ?>
            <a href="<?php echo esc_url(get_permalink($booking_page_id)); ?>" class="aqualuxe-booking-confirmation__button"><?php esc_html_e('Book Another Appointment', 'aqualuxe'); ?></a>
        <?php endif; ?>
    </div>
</div>

<script type="text/javascript">
    jQuery(document).ready(function($) {
        // Cancel booking button click
        $('.aqualuxe-cancel-booking').on('click', function(e) {
            // Prevent default action
            e.preventDefault();
            
            // Get booking ID
            var bookingId = $(this).data('booking-id');
            
            // Confirm cancellation
            if (confirm('<?php esc_html_e('Are you sure you want to cancel this booking?', 'aqualuxe'); ?>')) {
                // Send AJAX request
                $.ajax({
                    url: aqualuxeBookings.ajaxUrl,
                    type: 'POST',
                    data: {
                        action: 'aqualuxe_cancel_booking',
                        nonce: aqualuxeBookings.nonce,
                        booking_id: bookingId
                    },
                    success: function(response) {
                        // Check if request was successful
                        if (response.success) {
                            // Reload page
                            window.location.reload();
                        } else {
                            // Show error message
                            alert(response.data.message || '<?php esc_html_e('Error cancelling booking.', 'aqualuxe'); ?>');
                        }
                    },
                    error: function() {
                        // Show error message
                        alert('<?php esc_html_e('Error cancelling booking.', 'aqualuxe'); ?>');
                    }
                });
            }
        });
    });
</script>