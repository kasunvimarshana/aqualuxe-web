<?php
/**
 * Admin Calendar Template
 *
 * @package AquaLuxe
 * @subpackage Modules/Bookings
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Get month and year
$month = isset($month) ? $month : date('n');
$year = isset($year) ? $year : date('Y');

// Get month name
$month_name = date_i18n('F', mktime(0, 0, 0, $month, 1, $year));

// Get number of days in month
$days_in_month = date('t', mktime(0, 0, 0, $month, 1, $year));

// Get first day of month
$first_day = date('N', mktime(0, 0, 0, $month, 1, $year));

// Get bookings
$bookings = isset($bookings) ? $bookings : [];

// Get services
$services = $this->get_services();

// Group bookings by date
$bookings_by_date = [];
foreach ($bookings as $booking) {
    $date = $booking['date'];
    if (!isset($bookings_by_date[$date])) {
        $bookings_by_date[$date] = [];
    }
    $bookings_by_date[$date][] = $booking;
}

// Get service filter
$service_id = isset($_GET['service_id']) ? intval($_GET['service_id']) : 0;
?>

<div class="wrap">
    <h1 class="wp-heading-inline"><?php esc_html_e('Booking Calendar', 'aqualuxe'); ?></h1>
    <a href="<?php echo esc_url(admin_url('post-new.php?post_type=aqualuxe_booking')); ?>" class="page-title-action"><?php esc_html_e('Add New', 'aqualuxe'); ?></a>
    <hr class="wp-header-end">
    
    <div class="aqualuxe-booking-calendar-admin">
        <div class="aqualuxe-booking-calendar-admin__header">
            <h2 class="aqualuxe-booking-calendar-admin__title"><?php echo esc_html(sprintf(__('Calendar for %s %s', 'aqualuxe'), $month_name, $year)); ?></h2>
            
            <div class="aqualuxe-booking-calendar-admin__controls">
                <select class="aqualuxe-booking-calendar-admin__select aqualuxe-booking-calendar-admin__service-select">
                    <option value=""><?php esc_html_e('All Services', 'aqualuxe'); ?></option>
                    <?php foreach ($services as $service) : ?>
                        <option value="<?php echo esc_attr($service['id']); ?>" <?php selected($service_id, $service['id']); ?>>
                            <?php echo esc_html($service['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                
                <select class="aqualuxe-booking-calendar-admin__select aqualuxe-booking-calendar-admin__month-select">
                    <?php for ($m = 1; $m <= 12; $m++) : ?>
                        <option value="<?php echo esc_attr($m); ?>" <?php selected($m, $month); ?>>
                            <?php echo esc_html(date_i18n('F', mktime(0, 0, 0, $m, 1, $year))); ?>
                        </option>
                    <?php endfor; ?>
                </select>
                
                <select class="aqualuxe-booking-calendar-admin__select aqualuxe-booking-calendar-admin__year-select">
                    <?php for ($y = date('Y') - 1; $y <= date('Y') + 2; $y++) : ?>
                        <option value="<?php echo esc_attr($y); ?>" <?php selected($y, $year); ?>>
                            <?php echo esc_html($y); ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>
        </div>
        
        <table class="aqualuxe-booking-calendar-admin__table">
            <thead>
                <tr>
                    <th><?php esc_html_e('Monday', 'aqualuxe'); ?></th>
                    <th><?php esc_html_e('Tuesday', 'aqualuxe'); ?></th>
                    <th><?php esc_html_e('Wednesday', 'aqualuxe'); ?></th>
                    <th><?php esc_html_e('Thursday', 'aqualuxe'); ?></th>
                    <th><?php esc_html_e('Friday', 'aqualuxe'); ?></th>
                    <th><?php esc_html_e('Saturday', 'aqualuxe'); ?></th>
                    <th><?php esc_html_e('Sunday', 'aqualuxe'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Initialize variables
                $day = 1;
                $current_date = date('Y-m-d');
                
                // Loop through weeks
                for ($i = 0; $i < 6; $i++) {
                    // Start row
                    echo '<tr>';
                    
                    // Loop through days of week
                    for ($j = 1; $j <= 7; $j++) {
                        // Check if day is in current month
                        if (($i === 0 && $j < $first_day) || ($day > $days_in_month)) {
                            // Empty cell
                            echo '<td class="aqualuxe-booking-calendar-admin__cell aqualuxe-booking-calendar-admin__cell--other-month"></td>';
                        } else {
                            // Format date
                            $date = sprintf('%04d-%02d-%02d', $year, $month, $day);
                            
                            // Check if date is today
                            $is_today = ($date === $current_date);
                            
                            // Get cell classes
                            $cell_classes = ['aqualuxe-booking-calendar-admin__cell'];
                            
                            if ($is_today) {
                                $cell_classes[] = 'aqualuxe-booking-calendar-admin__cell--today';
                            }
                            
                            // Check if date is in the past
                            $is_past = ($date < $current_date);
                            
                            if (!$is_past) {
                                $cell_classes[] = 'aqualuxe-booking-calendar-admin__cell--available';
                            }
                            
                            // Output cell
                            echo '<td class="' . esc_attr(implode(' ', $cell_classes)) . '" data-date="' . esc_attr($date) . '">';
                            
                            // Add date
                            echo '<span class="aqualuxe-booking-calendar-admin__date">' . esc_html($day) . '</span>';
                            
                            // Add bookings
                            if (isset($bookings_by_date[$date])) {
                                echo '<div class="aqualuxe-booking-calendar-admin__bookings">';
                                
                                foreach ($bookings_by_date[$date] as $booking) {
                                    // Skip if service filter is set and doesn't match
                                    if ($service_id && $booking['service_id'] != $service_id) {
                                        continue;
                                    }
                                    
                                    // Get booking classes
                                    $booking_classes = ['aqualuxe-booking-calendar-admin__booking'];
                                    $booking_classes[] = 'aqualuxe-booking-calendar-admin__booking--' . $booking['status'];
                                    
                                    // Output booking
                                    echo '<div class="' . esc_attr(implode(' ', $booking_classes)) . '" data-booking-id="' . esc_attr($booking['id']) . '" data-tooltip="' . esc_attr($booking['customer']['name'] . ' - ' . $booking['service_name']) . '">';
                                    
                                    // Add booking time
                                    echo '<div class="aqualuxe-booking-calendar-admin__booking-time">' . esc_html(date_i18n(get_option('time_format'), strtotime($booking['time']))) . '</div>';
                                    
                                    // Add booking service
                                    echo '<div class="aqualuxe-booking-calendar-admin__booking-service">' . esc_html($booking['service_name']) . '</div>';
                                    
                                    // Add booking customer
                                    echo '<div class="aqualuxe-booking-calendar-admin__booking-customer">' . esc_html($booking['customer']['name']) . '</div>';
                                    
                                    // Add booking actions
                                    echo '<div class="aqualuxe-booking-calendar-admin__booking-actions">';
                                    echo '<a href="#" class="aqualuxe-booking-calendar-admin__edit-booking" data-booking-id="' . esc_attr($booking['id']) . '" title="' . esc_attr__('Edit', 'aqualuxe') . '">✎</a>';
                                    echo '<a href="#" class="aqualuxe-booking-calendar-admin__delete-booking" data-booking-id="' . esc_attr($booking['id']) . '" title="' . esc_attr__('Delete', 'aqualuxe') . '">×</a>';
                                    echo '</div>';
                                    
                                    echo '</div>';
                                }
                                
                                echo '</div>';
                            }
                            
                            // Add new booking button
                            if (!$is_past) {
                                echo '<a href="#" class="aqualuxe-booking-calendar-admin__add-booking" data-date="' . esc_attr($date) . '" title="' . esc_attr__('Add Booking', 'aqualuxe') . '">+</a>';
                            }
                            
                            echo '</td>';
                            
                            // Increment day
                            $day++;
                        }
                    }
                    
                    // End row
                    echo '</tr>';
                    
                    // Break if we've gone through all days
                    if ($day > $days_in_month) {
                        break;
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<script type="text/javascript">
    // Initialize services data for JavaScript
    window.aqualuxeServices = <?php echo json_encode(array_reduce($services, function($result, $service) {
        $result[$service['id']] = [
            'name' => $service['name'],
            'price' => $service['price'],
            'duration' => $service['duration'],
            'capacity' => $service['capacity'],
            'buffer_time' => $service['buffer_time'],
            'availability' => $this->get_service_availability($service['id']),
        ];
        return $result;
    }, [])); ?>;
</script>