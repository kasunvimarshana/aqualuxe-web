<?php
/**
 * Booking Details Plain Email Template
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

// Get status
$status = isset($booking_data['status']) ? $booking_data['status'] : 'pending';
$status_labels = [
    'pending' => __('Pending', 'aqualuxe'),
    'confirmed' => __('Confirmed', 'aqualuxe'),
    'completed' => __('Completed', 'aqualuxe'),
    'cancelled' => __('Cancelled', 'aqualuxe'),
];
$status_label = isset($status_labels[$status]) ? $status_labels[$status] : $status;

echo "==========\n";
echo __('Booking Details', 'aqualuxe') . "\n";
echo "==========\n\n";

echo __('Service', 'aqualuxe') . ": " . (isset($booking_data['service_name']) ? $booking_data['service_name'] : '') . "\n";
echo __('Date', 'aqualuxe') . ": " . $date . "\n";
echo __('Time', 'aqualuxe') . ": " . $time . "\n";
echo __('Status', 'aqualuxe') . ": " . $status_label . "\n\n";

if ($booking_id) {
    echo __('You can view your booking details by logging into your account.', 'aqualuxe') . "\n";
}