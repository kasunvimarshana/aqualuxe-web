<?php
/**
 * Calendar Class
 *
 * @package AquaLuxe\Modules\Bookings
 */

namespace AquaLuxe\Modules\Bookings;

/**
 * Calendar Class
 */
class Calendar {
    /**
     * Bookable item ID
     *
     * @var int
     */
    private $bookable_id = 0;

    /**
     * Availability object
     *
     * @var Availability
     */
    private $availability;

    /**
     * Calendar settings
     *
     * @var array
     */
    private $settings = [];

    /**
     * Constructor
     *
     * @param int $bookable_id
     */
    public function __construct($bookable_id = 0) {
        $this->bookable_id = $bookable_id;
        $this->availability = new Availability($bookable_id);
        
        // Get module instance
        $theme = \AquaLuxe\Theme::get_instance();
        $module = $theme->get_active_modules()['bookings'] ?? null;
        
        if ($module) {
            $this->settings = [
                'first_day' => $module->get_setting('calendar_first_day', 0),
                'date_format' => $module->get_setting('date_format', 'F j, Y'),
                'time_format' => $module->get_setting('time_format', 'g:i a'),
                'min_booking_notice' => $module->get_setting('min_booking_notice', 0),
                'max_booking_advance' => $module->get_setting('max_booking_advance', 365),
            ];
        } else {
            $this->settings = [
                'first_day' => 0,
                'date_format' => 'F j, Y',
                'time_format' => 'g:i a',
                'min_booking_notice' => 0,
                'max_booking_advance' => 365,
            ];
        }
    }

    /**
     * Render calendar
     *
     * @param array $args
     */
    public function render($args = []) {
        $defaults = [
            'id' => 'aqualuxe-calendar-' . $this->bookable_id,
            'class' => 'aqualuxe-calendar',
            'show_title' => true,
            'title' => __('Select Date', 'aqualuxe'),
            'months' => 1,
            'start_date' => '',
            'end_date' => '',
            'selected_date' => '',
            'inline' => true,
        ];
        
        $args = wp_parse_args($args, $defaults);
        
        // Get available dates
        $available_dates = $this->get_available_dates();
        
        // Get booking type
        $booking_type = get_post_meta($this->bookable_id, '_aqualuxe_booking_type', true);
        $booking_type = $booking_type ? $booking_type : 'date';
        
        // Get min and max dates
        $min_date = $this->get_min_date();
        $max_date = $this->get_max_date();
        
        // Prepare calendar data
        $calendar_data = [
            'bookableId' => $this->bookable_id,
            'bookingType' => $booking_type,
            'availableDates' => $available_dates,
            'minDate' => $min_date,
            'maxDate' => $max_date,
            'firstDay' => $this->settings['first_day'],
            'dateFormat' => $this->settings['date_format'],
            'timeFormat' => $this->settings['time_format'],
            'months' => $args['months'],
            'startDate' => $args['start_date'],
            'endDate' => $args['end_date'],
            'selectedDate' => $args['selected_date'],
            'inline' => $args['inline'],
        ];
        
        // Output calendar
        ?>
        <div id="<?php echo esc_attr($args['id']); ?>" class="<?php echo esc_attr($args['class']); ?>" data-calendar="<?php echo esc_attr(json_encode($calendar_data)); ?>">
            <?php if ($args['show_title']) : ?>
                <h3 class="aqualuxe-calendar__title"><?php echo esc_html($args['title']); ?></h3>
            <?php endif; ?>
            
            <div class="aqualuxe-calendar__container"></div>
            
            <?php if ($booking_type === 'date_time' || $booking_type === 'time') : ?>
                <div class="aqualuxe-calendar__time-slots">
                    <h4><?php esc_html_e('Available Times', 'aqualuxe'); ?></h4>
                    <div class="aqualuxe-calendar__time-slots-container"></div>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }

    /**
     * Get available dates
     *
     * @return array
     */
    public function get_available_dates() {
        // Get date range
        $start_date = $this->get_min_date();
        $end_date = $this->get_max_date();
        
        // Get booking type
        $booking_type = get_post_meta($this->bookable_id, '_aqualuxe_booking_type', true);
        $booking_type = $booking_type ? $booking_type : 'date';
        
        // Get available dates
        $available_dates = [];
        $current_date = strtotime($start_date);
        $end_timestamp = strtotime($end_date);
        
        while ($current_date <= $end_timestamp) {
            $date = date('Y-m-d', $current_date);
            
            // Check availability
            if ($booking_type === 'date' || $booking_type === 'date_range') {
                // Check if date is available
                $is_available = $this->availability->check($date);
                
                if ($is_available) {
                    $available_dates[] = $date;
                }
            } else {
                // Get available time slots
                $time_slots = $this->get_available_time_slots($date);
                
                if (!empty($time_slots)) {
                    $available_dates[] = $date;
                }
            }
            
            // Move to next day
            $current_date = strtotime('+1 day', $current_date);
        }
        
        return $available_dates;
    }

    /**
     * Get available time slots
     *
     * @param string $date
     * @return array
     */
    public function get_available_time_slots($date) {
        // Get booking type
        $booking_type = get_post_meta($this->bookable_id, '_aqualuxe_booking_type', true);
        
        // If not time-based booking, return empty array
        if ($booking_type !== 'date_time' && $booking_type !== 'time') {
            return [];
        }
        
        // Get time slots
        $time_slots = [];
        
        // Get booking duration
        $duration = get_post_meta($this->bookable_id, '_aqualuxe_booking_duration', true);
        $duration = $duration ? absint($duration) : 60;
        
        // Get duration unit
        $duration_unit = get_post_meta($this->bookable_id, '_aqualuxe_booking_duration_unit', true);
        $duration_unit = $duration_unit ? $duration_unit : 'minute';
        
        // Convert duration to minutes
        switch ($duration_unit) {
            case 'hour':
                $duration_minutes = $duration * 60;
                break;
            case 'day':
                $duration_minutes = $duration * 24 * 60;
                break;
            case 'minute':
            default:
                $duration_minutes = $duration;
                break;
        }
        
        // Get operating hours
        $operating_hours = $this->get_operating_hours($date);
        
        // Generate time slots
        foreach ($operating_hours as $hours) {
            $start_time = strtotime($hours['start']);
            $end_time = strtotime($hours['end']);
            
            // Generate slots
            $current_time = $start_time;
            
            while ($current_time + ($duration_minutes * 60) <= $end_time) {
                $slot_start = date('H:i:s', $current_time);
                $slot_end = date('H:i:s', $current_time + ($duration_minutes * 60));
                
                // Check if slot is available
                $slot_date_start = $date . ' ' . $slot_start;
                $slot_date_end = $date . ' ' . $slot_end;
                
                $is_available = $this->availability->check($slot_date_start, $slot_date_end);
                
                if ($is_available) {
                    $time_slots[] = [
                        'start' => $slot_start,
                        'end' => $slot_end,
                        'formatted_start' => date_i18n($this->settings['time_format'], $current_time),
                        'formatted_end' => date_i18n($this->settings['time_format'], $current_time + ($duration_minutes * 60)),
                    ];
                }
                
                // Move to next slot
                $current_time += ($duration_minutes * 60);
            }
        }
        
        return $time_slots;
    }

    /**
     * Get operating hours
     *
     * @param string $date
     * @return array
     */
    private function get_operating_hours($date) {
        // Get day of week
        $day_of_week = date('w', strtotime($date));
        
        // Default operating hours
        $default_hours = [
            [
                'start' => '09:00:00',
                'end' => '17:00:00',
            ],
        ];
        
        // Get operating hours from post meta
        $operating_hours = get_post_meta($this->bookable_id, '_aqualuxe_operating_hours', true);
        
        if (!$operating_hours || !is_array($operating_hours)) {
            return $default_hours;
        }
        
        // Check if there are specific hours for this day
        if (isset($operating_hours[$day_of_week]) && !empty($operating_hours[$day_of_week])) {
            return $operating_hours[$day_of_week];
        }
        
        return $default_hours;
    }

    /**
     * Get min date
     *
     * @return string
     */
    private function get_min_date() {
        // Get min booking notice
        $min_booking_notice = $this->settings['min_booking_notice'];
        
        // Calculate min date
        if ($min_booking_notice > 0) {
            return date('Y-m-d', strtotime('+' . $min_booking_notice . ' hours'));
        }
        
        return date('Y-m-d');
    }

    /**
     * Get max date
     *
     * @return string
     */
    private function get_max_date() {
        // Get max booking advance
        $max_booking_advance = $this->settings['max_booking_advance'];
        
        // Calculate max date
        return date('Y-m-d', strtotime('+' . $max_booking_advance . ' days'));
    }

    /**
     * Get calendar data
     *
     * @return array
     */
    public function get_calendar_data() {
        return [
            'bookableId' => $this->bookable_id,
            'availableDates' => $this->get_available_dates(),
            'minDate' => $this->get_min_date(),
            'maxDate' => $this->get_max_date(),
            'firstDay' => $this->settings['first_day'],
            'dateFormat' => $this->settings['date_format'],
            'timeFormat' => $this->settings['time_format'],
        ];
    }

    /**
     * Set setting
     *
     * @param string $key
     * @param mixed $value
     */
    public function set_setting($key, $value) {
        $this->settings[$key] = $value;
    }

    /**
     * Get setting
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get_setting($key, $default = null) {
        return isset($this->settings[$key]) ? $this->settings[$key] : $default;
    }
}