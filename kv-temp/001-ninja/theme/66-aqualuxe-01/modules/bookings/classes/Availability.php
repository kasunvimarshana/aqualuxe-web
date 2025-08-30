<?php
/**
 * Availability Class
 *
 * @package AquaLuxe\Modules\Bookings
 */

namespace AquaLuxe\Modules\Bookings;

/**
 * Availability Class
 */
class Availability {
    /**
     * Bookable item ID
     *
     * @var int
     */
    private $bookable_id = 0;

    /**
     * Availability rules
     *
     * @var array
     */
    private $rules = [];

    /**
     * Constructor
     *
     * @param int $bookable_id
     */
    public function __construct($bookable_id = 0) {
        $this->bookable_id = $bookable_id;
        $this->load_rules();
    }

    /**
     * Load availability rules
     */
    private function load_rules() {
        // Get availability type
        $availability_type = get_post_meta($this->bookable_id, '_aqualuxe_availability_type', true);
        $availability_type = $availability_type ? $availability_type : 'default';
        
        // Get availability rules
        $rules = get_post_meta($this->bookable_id, '_aqualuxe_availability_rules', true);
        
        // Set default rules based on availability type
        if ($availability_type === 'default' || !$rules || !is_array($rules)) {
            $this->rules = [
                [
                    'type' => 'default',
                    'bookable' => true,
                    'priority' => 10,
                ],
            ];
        } else {
            // Sort rules by priority
            usort($rules, function($a, $b) {
                return $b['priority'] - $a['priority'];
            });
            
            $this->rules = $rules;
        }
    }

    /**
     * Check availability
     *
     * @param string $start_date
     * @param string $end_date
     * @param int $quantity
     * @return bool
     */
    public function check($start_date, $end_date = '', $quantity = 1) {
        // If no end date, use start date
        if (!$end_date) {
            $end_date = $start_date;
        }
        
        // Parse dates
        $start_timestamp = strtotime($start_date);
        $end_timestamp = strtotime($end_date);
        
        // Check if dates are valid
        if (!$start_timestamp || !$end_timestamp) {
            return false;
        }
        
        // Check if start date is before end date
        if ($start_timestamp > $end_timestamp) {
            return false;
        }
        
        // Check booking capacity
        $capacity = $this->get_capacity();
        
        if ($capacity > 0) {
            $booked = $this->get_booked_quantity($start_date, $end_date);
            
            if ($booked + $quantity > $capacity) {
                return false;
            }
        }
        
        // Check availability rules
        $is_available = $this->check_rules($start_date, $end_date);
        
        return $is_available;
    }

    /**
     * Check availability rules
     *
     * @param string $start_date
     * @param string $end_date
     * @return bool
     */
    private function check_rules($start_date, $end_date) {
        // Parse dates
        $start_timestamp = strtotime($start_date);
        $end_timestamp = strtotime($end_date);
        
        // Default availability
        $is_available = true;
        
        // Check each rule
        foreach ($this->rules as $rule) {
            // Check if rule applies
            $applies = false;
            
            switch ($rule['type']) {
                case 'date_range':
                    $rule_start = strtotime($rule['from']);
                    $rule_end = strtotime($rule['to']);
                    
                    // Check if booking dates overlap with rule dates
                    $applies = ($start_timestamp <= $rule_end && $end_timestamp >= $rule_start);
                    break;
                    
                case 'weekday':
                    // Get day of week for start and end dates
                    $start_day = date('w', $start_timestamp);
                    $end_day = date('w', $end_timestamp);
                    
                    // Get rule days
                    $rule_days = explode(',', $rule['from']);
                    
                    // Check if any day in the range matches rule days
                    $current_day = $start_timestamp;
                    
                    while ($current_day <= $end_timestamp) {
                        $day = date('w', $current_day);
                        
                        if (in_array($day, $rule_days)) {
                            $applies = true;
                            break;
                        }
                        
                        $current_day = strtotime('+1 day', $current_day);
                    }
                    break;
                    
                case 'time':
                    // Get time components
                    $rule_start_time = strtotime($rule['from']);
                    $rule_end_time = strtotime($rule['to']);
                    
                    // Extract time components
                    $rule_start_hour = date('H', $rule_start_time);
                    $rule_start_minute = date('i', $rule_start_time);
                    $rule_end_hour = date('H', $rule_end_time);
                    $rule_end_minute = date('i', $rule_end_time);
                    
                    // Check if booking time overlaps with rule time
                    $booking_start_hour = date('H', $start_timestamp);
                    $booking_start_minute = date('i', $start_timestamp);
                    $booking_end_hour = date('H', $end_timestamp);
                    $booking_end_minute = date('i', $end_timestamp);
                    
                    // Convert to minutes for easier comparison
                    $rule_start_minutes = ($rule_start_hour * 60) + $rule_start_minute;
                    $rule_end_minutes = ($rule_end_hour * 60) + $rule_end_minute;
                    $booking_start_minutes = ($booking_start_hour * 60) + $booking_start_minute;
                    $booking_end_minutes = ($booking_end_hour * 60) + $booking_end_minute;
                    
                    // Check if booking time overlaps with rule time
                    $applies = ($booking_start_minutes <= $rule_end_minutes && $booking_end_minutes >= $rule_start_minutes);
                    break;
                    
                case 'default':
                    $applies = true;
                    break;
            }
            
            // If rule applies, update availability
            if ($applies) {
                $is_available = isset($rule['bookable']) ? (bool) $rule['bookable'] : true;
                
                // High priority rule applies, stop checking
                break;
            }
        }
        
        return $is_available;
    }

    /**
     * Get capacity
     *
     * @return int
     */
    private function get_capacity() {
        $capacity = get_post_meta($this->bookable_id, '_aqualuxe_booking_capacity', true);
        return $capacity ? absint($capacity) : 0;
    }

    /**
     * Get booked quantity
     *
     * @param string $start_date
     * @param string $end_date
     * @return int
     */
    private function get_booked_quantity($start_date, $end_date) {
        global $wpdb;
        
        // Parse dates
        $start_timestamp = strtotime($start_date);
        $end_timestamp = strtotime($end_date);
        
        // Query bookings
        $query = $wpdb->prepare(
            "SELECT SUM(pm_quantity.meta_value) as booked
            FROM {$wpdb->posts} p
            INNER JOIN {$wpdb->postmeta} pm_bookable ON p.ID = pm_bookable.post_id AND pm_bookable.meta_key = '_aqualuxe_booking_bookable_id'
            INNER JOIN {$wpdb->postmeta} pm_start ON p.ID = pm_start.post_id AND pm_start.meta_key = '_aqualuxe_booking_start_date'
            INNER JOIN {$wpdb->postmeta} pm_end ON p.ID = pm_end.post_id AND pm_end.meta_key = '_aqualuxe_booking_end_date'
            INNER JOIN {$wpdb->postmeta} pm_status ON p.ID = pm_status.post_id AND pm_status.meta_key = '_aqualuxe_booking_status'
            INNER JOIN {$wpdb->postmeta} pm_quantity ON p.ID = pm_quantity.post_id AND pm_quantity.meta_key = '_aqualuxe_booking_quantity'
            WHERE p.post_type = 'aqualuxe_booking'
            AND pm_bookable.meta_value = %d
            AND pm_status.meta_value IN ('pending', 'confirmed')
            AND (
                (UNIX_TIMESTAMP(pm_start.meta_value) <= %d AND UNIX_TIMESTAMP(pm_end.meta_value) >= %d)
                OR (UNIX_TIMESTAMP(pm_start.meta_value) >= %d AND UNIX_TIMESTAMP(pm_start.meta_value) <= %d)
                OR (UNIX_TIMESTAMP(pm_end.meta_value) >= %d AND UNIX_TIMESTAMP(pm_end.meta_value) <= %d)
            )",
            $this->bookable_id,
            $end_timestamp,
            $start_timestamp,
            $start_timestamp,
            $end_timestamp,
            $start_timestamp,
            $end_timestamp
        );
        
        $result = $wpdb->get_var($query);
        
        return $result ? absint($result) : 0;
    }

    /**
     * Get available dates
     *
     * @param string $start_date
     * @param string $end_date
     * @return array
     */
    public function get_available_dates($start_date = '', $end_date = '') {
        // If no start date, use current date
        if (!$start_date) {
            $start_date = date('Y-m-d');
        }
        
        // If no end date, use start date + 30 days
        if (!$end_date) {
            $end_date = date('Y-m-d', strtotime($start_date . ' +30 days'));
        }
        
        // Parse dates
        $start_timestamp = strtotime($start_date);
        $end_timestamp = strtotime($end_date);
        
        // Check if dates are valid
        if (!$start_timestamp || !$end_timestamp) {
            return [];
        }
        
        // Get available dates
        $available_dates = [];
        $current_timestamp = $start_timestamp;
        
        while ($current_timestamp <= $end_timestamp) {
            $current_date = date('Y-m-d', $current_timestamp);
            
            // Check if date is available
            if ($this->check($current_date)) {
                $available_dates[] = $current_date;
            }
            
            // Move to next day
            $current_timestamp = strtotime('+1 day', $current_timestamp);
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
        $time_slots = [];
        
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
                
                if ($this->check($slot_date_start, $slot_date_end)) {
                    $time_slots[] = [
                        'start' => $slot_start,
                        'end' => $slot_end,
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
     * Add rule
     *
     * @param array $rule
     */
    public function add_rule($rule) {
        $this->rules[] = $rule;
        
        // Sort rules by priority
        usort($this->rules, function($a, $b) {
            return $b['priority'] - $a['priority'];
        });
    }

    /**
     * Get rules
     *
     * @return array
     */
    public function get_rules() {
        return $this->rules;
    }

    /**
     * Save rules
     */
    public function save_rules() {
        update_post_meta($this->bookable_id, '_aqualuxe_availability_rules', $this->rules);
    }
}