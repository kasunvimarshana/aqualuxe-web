<?php
/**
 * Booking Calendar Template
 *
 * @package AquaLuxe
 * @subpackage Modules/Bookings
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Get calendar style
$style = isset($atts['style']) ? $atts['style'] : 'standard';

// Get services
$services = isset($services) ? $services : [];

// Get month and year
$month = isset($month) ? $month : date('n');
$year = isset($year) ? $year : date('Y');

// Get month name
$month_name = date_i18n('F', mktime(0, 0, 0, $month, 1, $year));

// Get number of days in month
$days_in_month = date('t', mktime(0, 0, 0, $month, 1, $year));

// Get first day of month
$first_day = date('N', mktime(0, 0, 0, $month, 1, $year));

// Get booking page URL
$booking_page_id = get_theme_mod('aqualuxe_booking_page', 0);
$booking_page_url = $booking_page_id ? get_permalink($booking_page_id) : '';
?>

<div class="aqualuxe-booking-calendar" data-style="<?php echo esc_attr($style); ?>">
    <div class="aqualuxe-booking-calendar__header">
        <h2 class="aqualuxe-booking-calendar__title"><?php echo esc_html(sprintf(__('Availability for %s %s', 'aqualuxe'), $month_name, $year)); ?></h2>
        
        <div class="aqualuxe-booking-calendar__controls">
            <select class="aqualuxe-booking-calendar__service-select">
                <option value=""><?php esc_html_e('All Services', 'aqualuxe'); ?></option>
                <?php foreach ($services as $service) : ?>
                    <option value="<?php echo esc_attr($service['id']); ?>" <?php selected(isset($_GET['service_id']) && $_GET['service_id'] == $service['id']); ?>>
                        <?php echo esc_html($service['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            
            <select class="aqualuxe-booking-calendar__month-select">
                <?php for ($m = 1; $m <= 12; $m++) : ?>
                    <option value="<?php echo esc_attr($m); ?>" <?php selected($m, $month); ?>>
                        <?php echo esc_html(date_i18n('F', mktime(0, 0, 0, $m, 1, $year))); ?>
                    </option>
                <?php endfor; ?>
            </select>
            
            <select class="aqualuxe-booking-calendar__year-select">
                <?php for ($y = date('Y'); $y <= date('Y') + 2; $y++) : ?>
                    <option value="<?php echo esc_attr($y); ?>" <?php selected($y, $year); ?>>
                        <?php echo esc_html($y); ?>
                    </option>
                <?php endfor; ?>
            </select>
        </div>
    </div>
    
    <div class="aqualuxe-booking-calendar__message" style="display: none;"></div>
    
    <table class="aqualuxe-booking-calendar__table">
        <thead>
            <tr>
                <th><?php esc_html_e('Mon', 'aqualuxe'); ?></th>
                <th><?php esc_html_e('Tue', 'aqualuxe'); ?></th>
                <th><?php esc_html_e('Wed', 'aqualuxe'); ?></th>
                <th><?php esc_html_e('Thu', 'aqualuxe'); ?></th>
                <th><?php esc_html_e('Fri', 'aqualuxe'); ?></th>
                <th><?php esc_html_e('Sat', 'aqualuxe'); ?></th>
                <th><?php esc_html_e('Sun', 'aqualuxe'); ?></th>
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
                        echo '<td class="aqualuxe-booking-calendar__cell aqualuxe-booking-calendar__cell--other-month"></td>';
                    } else {
                        // Format date
                        $date = sprintf('%04d-%02d-%02d', $year, $month, $day);
                        
                        // Check if date is today
                        $is_today = ($date === $current_date);
                        
                        // Check if date is in the past
                        $is_past = ($date < $current_date);
                        
                        // Get cell classes
                        $cell_classes = ['aqualuxe-booking-calendar__cell'];
                        
                        if ($is_today) {
                            $cell_classes[] = 'aqualuxe-booking-calendar__cell--today';
                        }
                        
                        if ($is_past) {
                            $cell_classes[] = 'aqualuxe-booking-calendar__cell--unavailable';
                        } else {
                            // Check availability for selected service
                            $service_id = isset($_GET['service_id']) ? intval($_GET['service_id']) : 0;
                            
                            if ($service_id) {
                                // Get service availability
                                $availability = isset($services[$service_id]['availability']) ? $services[$service_id]['availability'] : [];
                                
                                // Check if date is blocked
                                $is_blocked = isset($availability['blocked_dates']) && in_array($date, $availability['blocked_dates']);
                                
                                // Get day of week
                                $day_of_week = strtolower(date('l', strtotime($date)));
                                
                                // Check if day is enabled
                                $is_enabled = isset($availability['rules'][$day_of_week]['enabled']) && $availability['rules'][$day_of_week]['enabled'];
                                
                                if ($is_blocked || !$is_enabled) {
                                    $cell_classes[] = 'aqualuxe-booking-calendar__cell--unavailable';
                                } else {
                                    $cell_classes[] = 'aqualuxe-booking-calendar__cell--available';
                                }
                            } else {
                                $cell_classes[] = 'aqualuxe-booking-calendar__cell--available';
                            }
                        }
                        
                        // Output cell
                        echo '<td class="' . esc_attr(implode(' ', $cell_classes)) . '" data-date="' . esc_attr($date) . '">';
                        echo '<span class="aqualuxe-booking-calendar__date">' . esc_html($day) . '</span>';
                        
                        // Add availability indicator
                        if (!$is_past && !isset($cell_classes['aqualuxe-booking-calendar__cell--unavailable'])) {
                            echo '<span class="aqualuxe-booking-calendar__availability aqualuxe-booking-calendar__availability--available">' . esc_html__('Available', 'aqualuxe') . '</span>';
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

<script type="text/javascript">
    // Initialize booking page URL for JavaScript
    window.aqualuxeBookings = window.aqualuxeBookings || {};
    window.aqualuxeBookings.bookingPageUrl = '<?php echo esc_js($booking_page_url); ?>';
    
    // Initialize services data for JavaScript
    window.aqualuxeServices = <?php echo json_encode(array_reduce($services, function($result, $service) {
        $result[$service['id']] = [
            'name' => $service['name'],
            'price' => $service['price'],
            'duration' => $service['duration'],
            'capacity' => $service['capacity'],
            'buffer_time' => $service['buffer_time'],
        ];
        return $result;
    }, [])); ?>;
</script>