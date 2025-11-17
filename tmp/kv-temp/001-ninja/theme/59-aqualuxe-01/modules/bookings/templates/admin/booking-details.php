<?php
/**
 * Admin Booking Details Meta Box Template
 *
 * @package AquaLuxe
 * @subpackage Modules/Bookings
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Get booking data
$booking_data = isset($booking_data) ? $booking_data : [];

// Get services
$services = $this->get_services();

// Get booking statuses
$statuses = [
    'pending' => __('Pending', 'aqualuxe'),
    'confirmed' => __('Confirmed', 'aqualuxe'),
    'completed' => __('Completed', 'aqualuxe'),
    'cancelled' => __('Cancelled', 'aqualuxe'),
];
?>

<div class="aqualuxe-booking-details">
    <div class="aqualuxe-booking-details__section">
        <h3 class="aqualuxe-booking-details__section-title"><?php esc_html_e('Booking Information', 'aqualuxe'); ?></h3>
        
        <div class="aqualuxe-booking-details__field">
            <label for="aqualuxe-booking-service"><?php esc_html_e('Service', 'aqualuxe'); ?></label>
            <select id="aqualuxe-booking-service" name="aqualuxe_service_id">
                <option value=""><?php esc_html_e('Select a service', 'aqualuxe'); ?></option>
                <?php foreach ($services as $service) : ?>
                    <option value="<?php echo esc_attr($service['id']); ?>" <?php selected(isset($booking_data['service_id']) && $booking_data['service_id'] == $service['id']); ?>>
                        <?php echo esc_html($service['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="aqualuxe-booking-details__field">
            <label for="aqualuxe-booking-date"><?php esc_html_e('Date', 'aqualuxe'); ?></label>
            <input type="text" id="aqualuxe-booking-date" name="aqualuxe_booking_date" value="<?php echo esc_attr(isset($booking_data['date']) ? $booking_data['date'] : ''); ?>" class="aqualuxe-datepicker">
        </div>
        
        <div class="aqualuxe-booking-details__field">
            <label for="aqualuxe-booking-time"><?php esc_html_e('Time', 'aqualuxe'); ?></label>
            <input type="text" id="aqualuxe-booking-time" name="aqualuxe_booking_time" value="<?php echo esc_attr(isset($booking_data['time']) ? $booking_data['time'] : ''); ?>" class="aqualuxe-timepicker">
        </div>
        
        <div class="aqualuxe-booking-details__field">
            <label for="aqualuxe-booking-status"><?php esc_html_e('Status', 'aqualuxe'); ?></label>
            <select id="aqualuxe-booking-status" name="aqualuxe_booking_status">
                <?php foreach ($statuses as $status_key => $status_label) : ?>
                    <option value="<?php echo esc_attr($status_key); ?>" <?php selected(isset($booking_data['status']) && $booking_data['status'] == $status_key); ?>>
                        <?php echo esc_html($status_label); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <?php if (isset($booking_data['order_id']) && $booking_data['order_id']) : ?>
            <div class="aqualuxe-booking-details__field">
                <label><?php esc_html_e('Order', 'aqualuxe'); ?></label>
                <div>
                    <a href="<?php echo esc_url(admin_url('post.php?post=' . $booking_data['order_id'] . '&action=edit')); ?>" target="_blank">
                        <?php echo esc_html(sprintf(__('Order #%s', 'aqualuxe'), $booking_data['order_id'])); ?>
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="aqualuxe-booking-details__section">
        <h3 class="aqualuxe-booking-details__section-title"><?php esc_html_e('Customer Information', 'aqualuxe'); ?></h3>
        
        <div class="aqualuxe-booking-details__field">
            <label for="aqualuxe-customer-name"><?php esc_html_e('Name', 'aqualuxe'); ?></label>
            <input type="text" id="aqualuxe-customer-name" name="aqualuxe_customer_name" value="<?php echo esc_attr(isset($booking_data['customer']['name']) ? $booking_data['customer']['name'] : ''); ?>">
        </div>
        
        <div class="aqualuxe-booking-details__field">
            <label for="aqualuxe-customer-email"><?php esc_html_e('Email', 'aqualuxe'); ?></label>
            <input type="email" id="aqualuxe-customer-email" name="aqualuxe_customer_email" value="<?php echo esc_attr(isset($booking_data['customer']['email']) ? $booking_data['customer']['email'] : ''); ?>">
        </div>
        
        <div class="aqualuxe-booking-details__field">
            <label for="aqualuxe-customer-phone"><?php esc_html_e('Phone', 'aqualuxe'); ?></label>
            <input type="tel" id="aqualuxe-customer-phone" name="aqualuxe_customer_phone" value="<?php echo esc_attr(isset($booking_data['customer']['phone']) ? $booking_data['customer']['phone'] : ''); ?>">
        </div>
        
        <div class="aqualuxe-booking-details__field">
            <label for="aqualuxe-customer-notes"><?php esc_html_e('Notes', 'aqualuxe'); ?></label>
            <textarea id="aqualuxe-customer-notes" name="aqualuxe_customer_notes" rows="3"><?php echo esc_textarea(isset($booking_data['customer']['notes']) ? $booking_data['customer']['notes'] : ''); ?></textarea>
        </div>
    </div>
    
    <div class="aqualuxe-booking-details__section">
        <h3 class="aqualuxe-booking-details__section-title"><?php esc_html_e('Admin Notes', 'aqualuxe'); ?></h3>
        
        <div class="aqualuxe-booking-details__field">
            <textarea id="aqualuxe-admin-notes" name="aqualuxe_admin_notes" rows="3"><?php echo esc_textarea(isset($booking_data['admin_notes']) ? $booking_data['admin_notes'] : ''); ?></textarea>
        </div>
    </div>
</div>

<script type="text/javascript">
    jQuery(document).ready(function($) {
        // Initialize datepicker
        $('.aqualuxe-datepicker').datepicker({
            dateFormat: 'yy-mm-dd',
            minDate: 0
        });
        
        // Initialize timepicker
        $('.aqualuxe-timepicker').timepicker({
            timeFormat: 'HH:mm',
            step: 15,
            scrollDefault: '09:00'
        });
    });
</script>