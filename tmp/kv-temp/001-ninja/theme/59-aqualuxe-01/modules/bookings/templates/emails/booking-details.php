<?php
/**
 * Booking Details Email Template
 *
 * @package AquaLuxe
 * @subpackage Modules/Bookings
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Get booking data
$booking_data = isset($booking_data) ? $booking_data : [];

// Get booking ID
$booking_id = isset($booking_id) ? $booking_id : 0;

// Format date and time
$date = isset($booking_data['date']) ? date_i18n(get_option('date_format'), strtotime($booking_data['date'])) : '';
$time = isset($booking_data['time']) ? date_i18n(get_option('time_format'), strtotime($booking_data['time'])) : '';
?>

<div style="margin-bottom: 40px;">
    <h2 style="color: #1a1a1a; font-size: 18px; font-weight: bold; margin: 0 0 10px 0; text-align: left;"><?php esc_html_e('Booking Details', 'aqualuxe'); ?></h2>
    
    <table cellspacing="0" cellpadding="6" style="width: 100%; border: 1px solid #e5e5e5; margin-bottom: 20px;">
        <tbody>
            <tr>
                <th scope="row" style="text-align: left; border-bottom: 1px solid #e5e5e5; padding: 12px; font-weight: bold;"><?php esc_html_e('Service', 'aqualuxe'); ?></th>
                <td style="text-align: left; border-bottom: 1px solid #e5e5e5; padding: 12px;"><?php echo esc_html(isset($booking_data['service_name']) ? $booking_data['service_name'] : ''); ?></td>
            </tr>
            <tr>
                <th scope="row" style="text-align: left; border-bottom: 1px solid #e5e5e5; padding: 12px; font-weight: bold;"><?php esc_html_e('Date', 'aqualuxe'); ?></th>
                <td style="text-align: left; border-bottom: 1px solid #e5e5e5; padding: 12px;"><?php echo esc_html($date); ?></td>
            </tr>
            <tr>
                <th scope="row" style="text-align: left; border-bottom: 1px solid #e5e5e5; padding: 12px; font-weight: bold;"><?php esc_html_e('Time', 'aqualuxe'); ?></th>
                <td style="text-align: left; border-bottom: 1px solid #e5e5e5; padding: 12px;"><?php echo esc_html($time); ?></td>
            </tr>
            <tr>
                <th scope="row" style="text-align: left; padding: 12px; font-weight: bold;"><?php esc_html_e('Status', 'aqualuxe'); ?></th>
                <td style="text-align: left; padding: 12px;">
                    <?php
                    $status = isset($booking_data['status']) ? $booking_data['status'] : 'pending';
                    $status_labels = [
                        'pending' => __('Pending', 'aqualuxe'),
                        'confirmed' => __('Confirmed', 'aqualuxe'),
                        'completed' => __('Completed', 'aqualuxe'),
                        'cancelled' => __('Cancelled', 'aqualuxe'),
                    ];
                    $status_colors = [
                        'pending' => '#f0ad4e',
                        'confirmed' => '#5cb85c',
                        'completed' => '#5bc0de',
                        'cancelled' => '#d9534f',
                    ];
                    $status_label = isset($status_labels[$status]) ? $status_labels[$status] : $status;
                    $status_color = isset($status_colors[$status]) ? $status_colors[$status] : '#777777';
                    ?>
                    <span style="display: inline-block; padding: 3px 8px; background-color: <?php echo esc_attr($status_color); ?>; color: #ffffff; border-radius: 3px; font-size: 12px;"><?php echo esc_html($status_label); ?></span>
                </td>
            </tr>
        </tbody>
    </table>
    
    <?php if ($booking_id) : ?>
        <p style="margin: 0 0 16px;"><?php esc_html_e('You can view your booking details by logging into your account.', 'aqualuxe'); ?></p>
    <?php endif; ?>
</div>